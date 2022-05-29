<?php
require_once('../src/config.php');
require_once('../src/Model.class.php');
require_once('../src/DataTransformer.class.php');
require_once('../src/Translator.class.php');
require_once('../src/API.class.php');

$model = new Model();
$translator = new Translator();

header('Content-Type: application/json');

// not allowed method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    API::returnError(405, 'Only post method is available');
}

//missing parameter action
if (!isset($_POST['action'])) {
    API::returnError(400, 'Missing parameter action');
}
else {
    switch ($_POST['action']) {

        /*
         *  GENERATE A USER TOKEN FOR SESSION (LOGIN)
         */
        case 'get_user_token':
            if (API::existsParams(array('user_login', 'password'))) {
                $user = $model->getUserByUserName($_POST['user_login']);
                if ($user === false) {
                    API::returnSuccessFailed(API::UNKNOWN_USER);
                }
                elseif ($user->enabled == false) {
                    API::returnSuccessFailed(API::DISABLED_USER);
                }
                elseif (crypt($_POST['password'], $user->password) !== $user->password) {
                    API::returnSuccessFailed(API::WRONG_PASSWORD);
                }
                $token = API::generateToken($user);
                $r = $model->setToken($user, $token);
                if ($r === false) {
                    API::returnSuccessFailed(API::DATABASE_ERROR);
                }
                $model->setLastLogin($user, new DateTime);
                echo json_encode(array(
                    'success' => 1,
                    'token' => $token,
                    'user_id' => $user->id
                ));
            }
            exit;

        /*
         *  GET USER INFO
         */
        case 'get_user_info':
            if (API::existsParams(API::SESSION_FIELDS)) {
                $user = API::verifyUser($model);
                API::verifySession($user->token);
            }
            API::verifyNbRequest($model, $user);

            $state = $model->getLastData($user);
            if ($state === false) {
                API::returnSuccessFailed(API::DATABASE_ERROR);
            }
            $user = (array) $user;
            unset($user['token']);
            unset($user['password']);
            echo json_encode(array(
                'success' => 1,
                'user' => $user
            ));
            exit;

        /*
         *  GET DICTIONARY
         */
        case 'get_dictionary':
            if (API::existsParams(API::SESSION_FIELDS)) {
                $user = API::verifyUser($model);
                API::verifySession($user->token);
            }
            API::verifyNbRequest($model, $user);

            $dico = $translator->getDictionary();
            echo json_encode(array(
                'success' => 1,
                'dictionary' => $dico
            ));
            exit;

        /*
         *  GET LAST DATA FOR FORM
         */
        case 'get_last_data':
            if (API::existsParams(API::SESSION_FIELDS)) {
                $user = API::verifyUser($model);
                API::verifySession($user->token);
            }
            API::verifyNbRequest($model, $user);

            $state = $model->getLastData($user);
            if ($state === false) {
                API::returnSuccessFailed(API::DATABASE_ERROR);
            }
            echo json_encode(array(
                'success' => 1,
                'last_state' => $state
            ));
            exit;

        /*
         *  GET DATA FOR CHART
         */
        case 'get_data':
            if (API::existsParams(API::SESSION_FIELDS)) {
                $user = API::verifyUser($model);
                API::verifySession($user->token);
            }
            API::verifyNbRequest($model, $user);

            $states = $model->getAllData($user);
            if ($states === false) {
                API::returnSuccessFailed(API::DATABASE_ERROR);
            }

            $transformer = new DataTransformer($states);
            $data = $transformer->getTransformedData();
            echo json_encode(array(
                'success' => 1,
                'data' => $data
            ));
            exit;

        /*
         *  EDIT USER PROFILE
         */
        case 'edit_user_profile':
            if (
                API::existsParams(API::SESSION_FIELDS) &&
                API::existsParams(array('username', 'password')) &&
                API::existsParam('last_name', true) &&
                API::existsParam('first_name', true)
            ) {
                $user = API::verifyUser($model);
                API::verifySession($user->token);
            }
            API::verifyNbRequest($model, $user);

            if ($model->getUserByUserName($_POST['username']) !== false && $_POST['username'] != $user->username) {
                API::returnSuccessFailed(API::ALREADY_EXISTS_USERNAME);
            }
            elseif (crypt($_POST['password'], $user->password) != $user->password) {
                API::returnSuccessFailed(API::WRONG_PASSWORD);
            }

            $r = $model->updateUser($user, $_POST['username'], $_POST['first_name'], $_POST['last_name']);
            if ($r === false) {
                API::returnSuccessFailed(API::DATABASE_ERROR);
            }

            echo json_encode(array('success' => 1));
            exit;

        /*
         *  EDIT USER PASSWORD
         */
        case 'edit_user_password':
            if (API::existsParams(API::SESSION_FIELDS) && API::existsParams(array('new_password', 'repeat_password', 'password'))) {
                $user = API::verifyUser($model);
                API::verifySession($user->token);
            }
            API::verifyNbRequest($model, $user);

            if ($_POST['repeat_password'] != $_POST['new_password']) {
                API::returnSuccessFailed(API::INVALID_REPEATED_PASSWORD);
            }
            if (crypt($_POST['password'], $user->password) != $user->password) {
                API::returnSuccessFailed(API::WRONG_PASSWORD);
            }
            // change password
            $password = crypt($_POST['new_password']);
            $r = $model->updateUser($user, null, null, null, $password);
            if ($r === false) {
                API::returnSuccessFailed(API::DATABASE_ERROR);
            }

            echo json_encode(array('success' => 1));
            exit;

        /*
         *  USER GET ALL DATA
         */
        case 'data_user_request':
            if (API::existsParams(API::SESSION_FIELDS) && API::existsParams(array('password'))) {
                $user = API::verifyUser($model);
                API::verifySession($user->token);
            }
            API::verifyNbRequest($model, $user);

            if (crypt($_POST['password'], $user->password) != $user->password) {
                API::returnSuccessFailed(API::WRONG_PASSWORD);
            }

            $data = API::getAllDataHeader();
            $user = (array) $user;
            unset($user['password']);
            unset($user['token']);
            $data['user'] = $user;
            $data['states']  = $model->getAllData((object) $user);

            echo json_encode($data);
            exit;

        /*
         *  DELETE USER
         */
        case 'delete_user':
            if (API::existsParams(API::SESSION_FIELDS) && API::existsParams(array('password'))) {
                $user = API::verifyUser($model);
                API::verifySession($user->token);
            }
            API::verifyNbRequest($model, $user);

            if (crypt($_POST['password'], $user->password) != $user->password) {
                API::returnSuccessFailed(API::WRONG_PASSWORD);
            }

            // delete user
            $r = $model->deleteUser($user);

            if ($r === false) {
                API::returnSuccessFailed(API::DATABASE_ERROR);
            }

            echo json_encode(array('success' => 1));
            exit;

        /*
         *  REGISTER
         */
        case 'add_user':
            API::existsParams(array('username', 'password', 'repeat_password'));
            API::existsParam('last_name', true);
            API::existsParam('first_name', true);

            if ($model->getUserByUserName($_POST['username']) !== false) {
                API::returnSuccessFailed(API::ALREADY_EXISTS_USERNAME);
            }
            if ($_POST['password'] != $_POST['repeat_password'])
            {
                API::returnSuccessFailed(API::INVALID_REPEATED_PASSWORD);
            }

            // hash password
            $password = crypt($_POST['password']);
            $r = $model->addUser($_POST['username'], $_POST['first_name'], $_POST['last_name'], $password);
            if ($r === false) {
                API::returnSuccessFailed(API::DATABASE_ERROR);
            }

            echo json_encode(array('success' => 1));
            exit;

        default:
            API::returnError(400, 'Bad action field ' . $_POST['action']);
    }
}
