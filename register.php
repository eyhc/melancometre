<?php
require_once('src/init.php');

// Already connected
$session->AlreadyConnectedWithRedirection();

/*
 * Checking register form
 */
// error constants
define('MISSING_USERNAME', 1);
define('ALREADY_EXISTS_USERNAME', 2);
define('INVALID_PASSWORD', 3);
define('NONCORRESPONDING_PASSWORD', 4);
define('SOMETHING_WENT_WRONG', 5);

// if form sent
$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // validation
    $ln = empty($_POST['lastName']) ? null : $_POST['lastName'];
    $fn = empty($_POST['firstName']) ? null : $_POST['firstName'];

    if (empty($_POST['password'])) {
        $errors[] = INVALID_PASSWORD;
    }
    if (
        isset($_POST['password']) &&
        isset($_POST['repeatPassword']) &&
        $_POST['password'] != $_POST['repeatPassword']
    )
    {
        $errors[] = NONCORRESPONDING_PASSWORD;
    }
    if (empty($_POST['username'])) {
        $errors[] = MISSING_USERNAME;
    }
    else {
        $user = $model->getUserByUserName($_POST['username']);

        if ($user !== false) {
            $errors[] = ALREADY_EXISTS_USERNAME;
        }
    }

    // register
    if (count($errors) == 0) {
        // hash password
        $password = crypt($_POST['password']);
        $status = $model->addUser($_POST['username'], $fn, $ln, $password);
        if ($status) {
            // login
            $session->connectWithRedirection($model->getUserByUserName($_POST['username']));
        }
        else {
            $errors[] = SOMETHING_WENT_WRONG;
        }
    }
}

require('templates/register.html.php');
