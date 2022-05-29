<?php
header('Content-Type: text/html');

require_once('config.php');
require_once('Model.class.php');
require_once('API.class.php');
require_once('Session.class.php');
require_once('DataTransformer.class.php');
require_once('Translator.class.php');

$model = new Model();
$session = new Session($model);

$user_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : 'en';
$lang = isset($_GET['lang']) ? $_GET['lang'] : $user_lang;
$translator = new Translator($lang);

function trans($value)
{
    global $translator;
    return $translator->trans($value);
}
