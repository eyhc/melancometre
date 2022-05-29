<?php
require_once('src/init.php');

// not connected
$session->NotConnectedWithRedirection();

//user
$user = $session->getUser();

// form and errors
define('SOMETHING_WENT_WRONG', 0);

define('MISSING_USERNAME', 1);
define('ALREADY_EXISTS_USERNAME', 2);
define('MISSING_PASSWORD', 3);
define('INVALID_PASSWORD', 4);

define('INVALID_NEW_PASSWORD', 5);
define('INVALID_REPEAT_PASSWORD', 6);
define('MISSING_PASSWORD_2', 7);
define('INVALID_PASSWORD_2', 8);

define('MISSING_PASSWORD_3', 8);
define('INVALID_PASSWORD_3', 9);

define('MISSING_PASSWORD_4', 10);
define('INVALID_PASSWORD_4', 11);

define('SUCCESS_INFO', 12);
define('SUCCESS_CHANGE_PASSWORD', 13);

// if form sent
$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'info':
            $ln = !isset($_POST['lastName']) ? null : $_POST['lastName'];
            $fn = !isset($_POST['firstName']) ? null : $_POST['firstName'];
            if (empty($_POST['username'])) {
                $errors[] = MISSING_USERNAME;
            }
            else {
                if ($model->getUserByUserName($_POST['username']) !== false && $_POST['username'] != $user->username) {
                    $errors[] = ALREADY_EXISTS_USERNAME;
                }
                elseif (empty($_POST['password'])) {
                    $errors[] = MISSING_PASSWORD;
                }
                elseif (crypt($_POST['password'], $user->password) != $user->password) {
                    $errors[] = INVALID_PASSWORD;
                }
                else {
                    $r = $model->updateUser($user, $_POST['username'], $fn, $ln);

                    if ($r === false) {
                        $errors[] = SOMETHING_WENT_WRONG;
                    }
                    else {
                        $session->changeUser($_POST['username']);
                        $errors[] = SUCCESS_INFO;
                    }
                }
            }
            break;
        case 'change_password':
            if (empty($_POST['newPassword'])) {
                $errors[] = INVALID_NEW_PASSWORD;
            }
            elseif (empty($_POST['repeatPassword']) || $_POST['repeatPassword'] != $_POST['newPassword']) {
                $errors[] = INVALID_REPEAT_PASSWORD;
            }
            if (empty($_POST['password'])) {
                $errors[] = MISSING_PASSWORD_2;
                var_dump('error');
            }
            elseif (crypt($_POST['password'], $user->password) != $user->password) {
                $errors[] = INVALID_PASSWORD_2;
            }
            else {
                // change password
                $password = crypt($_POST['newPassword']);
                $r = $model->updateUser($user, null, null, null, $password);

                if ($r === false) {
                    $errors[] = SOMETHING_WENT_WRONG;
                }
                else {
                    $errors[] = SUCCESS_CHANGE_PASSWORD;
                }
            }
            break;
        case 'delete':
            if (empty($_POST['password'])) {
                $errors[] = MISSING_PASSWORD_4;
            }
            elseif (crypt($_POST['password'], $user->password) != $user->password) {
                $errors[] = INVALID_PASSWORD_4;
            }
            else {
                // delete user
                $r = $model->deleteUser($user);

                if ($r === false) {
                    $errors[] = SOMETHING_WENT_WRONG;
                }
                else {
                    $session->resetWithRedirection();
                }
            }
            break;
    }

}

$user = $session->update()->getUser();

require('templates/my-account.html.php');
