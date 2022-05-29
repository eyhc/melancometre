<?php
require_once('src/init.php');

// Already connected
$session->AlreadyConnectedWithRedirection();

/*
 * Checking username and password
 */

// error constants
define('USERNAME_MISSING', 1);
define('PASSWORD_MISSING', 2);
define('UNKNOWN_USERNAME', 3);
define('WRONG_PASSWORD', 4);
define('DISABLED_USER', 5);

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['username'])) {
        $errors[] = USERNAME_MISSING;
    }
    if (empty($_POST['password'])) {
        $errors[] = PASSWORD_MISSING;
    }
    if(count($errors) == 0) {
        $user = $model->getUserByUserName($_POST['username']);

        if ($user === false) {
            $errors[] = UNKNOWN_USERNAME;
        }
        elseif ($user->enabled == false) {
            $errors[] = DISABLED_USER;
        }
        elseif (crypt($_POST['password'], $user->password) == $user->password) {
            $session->connectWithRedirection($user);
        }
        else {
            $errors[] = WRONG_PASSWORD;
        }
    }
}

require('templates/login.html.php');
