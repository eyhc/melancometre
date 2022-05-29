<?php
require_once('Session.class.php');

/**
 *   STATIC CLASS:
 *       API ERRORS RESPONSES MANAGER
 */
class API
{
    const DATABASE_ERROR = 0;
    const WRONG_SESSION = 1;
    const UNKNOWN_USER = 2;
    const DISABLED_USER = 3;
    const WRONG_PASSWORD = 4;
    const ALREADY_EXISTS_USERNAME = 5;
    const INVALID_REPEATED_PASSWORD = 7;


    public static function getReason($code)
    {
        switch ($code) {
            case self::UNKNOWN_USER: return 'unknown user';
            case self::DISABLED_USER: return 'disabled user';
            case self::WRONG_PASSWORD: return 'invalid password';
            case self::DATABASE_ERROR: return 'database error';
            case self::WRONG_SESSION: return 'invalid session';
            case self::ALREADY_EXISTS_USERNAME: return 'username already exists';
            case self::INVALID_REPEATED_PASSWORD: return 'both passwords do not match';
            default: return 'something went wrong';
        }
    }

    // generate and return an error
    public static function returnError($code, $message)
    {
        http_response_code($code);
        echo json_encode(array(
            'error' => array(
                'status' => $code,
                'message' => $message
            )
        ));
        exit;
    }

    public static function returnSuccessFailed($code)
    {
        echo json_encode(array(
            'success' => 0,
            'code' => $code,
            'reason' => self::getReason($code)
        ));
        exit;
    }

    // verify if the param exists and is valid to add data
    public static function existsParam($paramName, $canBeEmpty = false)
    {
        if (!isset($_POST[$paramName]))
        {
            self::returnError(400, 'Missing parameter ' . $paramName);
        }
        elseif (!$canBeEmpty && (empty($_POST[$paramName]) && $_POST[$paramName] != 0)) {
            self::returnError(400, 'Empty parameter ' . $paramName);
        }
        return true;
    }

    // check validity of add_data param
    public static function existsParams($params)
    {
        foreach ($params as $p) {
            self::existsParam($p);
        }
        return true;
    }

    public static function isValidSession($token)
    {
        $plainSession = $_POST['user_id'] . $_POST['timestamp'] . $_POST['action'];
        $session = base64_encode(hash_hmac("sha256", $plainSession, $token, true));
        return $_POST['session_id'] === $session;
    }

    public static function generateToken()
    {
        return base64_encode(bin2hex(openssl_random_pseudo_bytes(32)));
    }

    public static function verifyUser($model) {
        $user = $model->getUserById($_POST['user_id']);
        if ($user === false) {
            API::returnSuccessFailed(API::UNKNOWN_USER);
        }
        elseif (!$user->enabled) {
            API::returnSuccessFailed(API::DISABLED_USER);
        }
        return $user;
    }

    public static function verifySession($token)
    {
        if (!API::isValidSession($token)) {
            API::returnSuccessFailed(API::WRONG_SESSION);
        }
    }

    public static function verifyNbRequest($model, $user) {
        $last_req = explode('-', $user->date_last_request);
        if (time() - mktime(0, 0, 0, (int)$last_req[1], (int)$last_req[2], (int)$last_req[0]) >= 24*60*60) {
            $model->setRequest($user, (new DateTime()), 0);
        }
        elseif ($user->nb_requests > Session::MAX_REQUEST_DAY) {
            header('Retry-After: 3600');
            http_response_code(429);
            echo json_encode(array(
                'error' => array(
                    'status' => 429,
                    'message' => 'Too many requests',
                    'max_requests_day' => Session::MAX_REQUEST_DAY
                )
            ));
            exit;
        }

        API::incrementRequest($user, $model);
    }

    private static function incrementRequest($user, $model) {
        $model->setRequest($user, new DateTime($user->date_last_request), $user->nb_requests + 1);
    }

    public static function getAllDataHeader()
    {
        $date = new DateTime();
        $data['info'] = array(
            'note' => "You find all your data in our database in this json file.",
            'date' => array(
                'date' => $date->format('Y-m-d H:i:s.u'),
                'timezone' => $date->format('e')
            )
        );
        return $data;
    }
}
