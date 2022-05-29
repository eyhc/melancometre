<?php
require_once('src/init.php');

header('Content-Type: application/json');

// verify if the param exists and is valid to add data
function isValidParam($paramName)
{
    if (!API::existsParam($paramName) || !is_numeric($_POST[$paramName]) || 0 > $_POST[$paramName] || $_POST[$paramName] > 100)
    {
        API::returnError(400, 'Bad ' . $paramName . ' field');
    }
    return true;
}

// check validity of add_data param
function isValidAddDataParams()
{
    return isValidParam('general') && isValidParam('moral') && isValidParam('energy') && isValidParam('suicidal_ideas');
}

// not allowed method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    API::returnError(405, 'Only post method is available');
}

// not connected
if (!$session->isConnected()) {
    API::returnError(401, 'Authentication required');
}
else {
    $user = $session->getUser();
}

//missing parameter action
if (!isset($_POST['action'])) {
    API::returnError(400, 'Missing parameter action');
}
else {
    switch ($_POST['action']) {
        case 'get_data':
            $states = $model->getAllData($user);
            $transformer = new DataTransformer($states);
            $data =  $transformer->getTransformedData();
            $data['labels'] = $translator->transDates($data['labels']);
            echo json_encode($data);
            break;
        case 'get_last_data':
            $state = $model->getLastData($user);
            echo json_encode(array(
                'success' => ($state === false) ? 0 : 1,
                'last_state' => ($state !== false) ? $state : null
            ));
            break;
        case 'add_data':
            if (isValidAddDataParams()) {
                $result = $model->addNowData(
                    $user,
                    $_POST['general'],
                    $_POST['moral'],
                    $_POST['energy'],
                    $_POST['suicidal_ideas']
                );
                echo json_encode(array('success' => $result ? 1 : 0));
            }
            break;
        case 'get_all_data':
            if (!isset($_POST['password']))
            {
                API::returnError(400, 'Missing parameter password');
            }
            elseif (crypt($_POST['password'], $user->password) != $user->password) {
                API::returnError(403, 'Invalid password');
            }

            $data = API::getAllDataHeader();
            $user = (array) $session->getUser();
            unset($user['password']);
            unset($user['token']);
            $data['user'] = $user;
            $data['states']  = $model->getAllData($session->getUser());

            echo json_encode($data);
            break;
        default:
            API::returnError(400, 'Bad action field ' . $_POST['action']);
    }
}
