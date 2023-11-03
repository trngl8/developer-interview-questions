<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core;
use App\Question;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
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

try {
    $db = $core->getDatabase($_ENV['DATABASE_DSN']);
    $model = new Question($db);
    $response = $core->run($request, $model);
} catch (Exception $e) {
    $log = new Logger('database');
    $log->pushHandler(new StreamHandler(__DIR__ . '/var/logs/database.log', Level::Warning));
    $log->pushProcessor(function ($logItem) use ($e) {
        $logItem->extra['file'] = $e->getFile();
        return $logItem;
    });
    $log->error($e->getMessage(), ['line' => $e->getLine()]);
    $response = $core->getExceptionResponse();
}

$response->send();
