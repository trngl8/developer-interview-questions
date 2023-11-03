<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core;
use App\Question;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

$corePath = __DIR__.'/';
$dotenv = new Dotenv();
$dotenv->load($corePath . '.env');

$core = new Core($_ENV['APP_ENV'], $_ENV['APP_DEBUG']);
$core->init();

$request = Request::createFromGlobals();
$request->overrideGlobals();

session_start();

$twig = $core->getTemplateEngine();

if(array_key_exists('message', $_SESSION)) {
    $twig->addGlobal('message', $_SESSION['message']);
    session_unset();
    session_destroy();
}

$db = $core->getDatabase($_ENV['DATABASE_DSN']);

$model = new Question($db);

$response = $core->run($request, $model);
$response->send();
