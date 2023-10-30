<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\DatabaseFactory;
use App\Question;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/var/cache/twig',
    'debug' => $_ENV['APP_DEBUG'],
]);

try {
    $db = DatabaseFactory::create($_ENV['APP_ENV']);
    $model = new Question($db);
    $records = $model->getQuestions();
} catch (\Exception $e) {
    $log = new Logger('database');
    $log->pushHandler(new StreamHandler(__DIR__ . '/var/logs/database.log', Level::Warning));
    $log->pushProcessor(function ($record) use ($e) {
        $record->extra['file'] = $e->getFile();
        return $record;
    });
    $log->warning($e->getMessage(), ['line' => $e->getLine()]);
    $log->error($e->getTraceAsString());
    echo $twig->render('error.html.twig', ['message' => $e->getMessage()]);
    exit;
}

echo $twig->render('index.html.twig', ['questions' => $records]);
