<?php
require_once('src/init.php');

// not connected
$session->NotConnectedWithRedirection();

// get user;
$user = $session->getUser();

require('templates/index.html.php');
