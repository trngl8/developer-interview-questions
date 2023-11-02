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
use Symfony\Component\HttpFoundation\Request;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$customEnv = sprintf('%s/.env.%s', __DIR__, $_ENV['APP_ENV']);
if (file_exists($customEnv)) {
    $dotenv->load($customEnv);
}

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/var/cache/twig',
    'debug' => $_ENV['APP_DEBUG'],
]);

$request = Request::createFromGlobals();
$request->overrideGlobals();

session_start();

if(array_key_exists('message', $_SESSION)) {
    $twig->addGlobal('message', $_SESSION['message']);
    session_unset();
    session_destroy();
}

try {
    $db = DatabaseFactory::create($_ENV['DATABASE_DSN']);
    $model = new Question($db);
    $records = $model->getQuestions();
} catch (\Exception $e) {
    $log = new Logger('database');
    $log->pushHandler(new StreamHandler(__DIR__ . '/var/logs/database.log', Level::Warning));
    $log->pushProcessor(function ($logItem) use ($e) {
        $logItem->extra['file'] = $e->getFile();
        return $logItem;
    });
    $log->error($e->getMessage(), ['line' => $e->getLine()]);
    echo $twig->render('error.html.twig', ['message' => "Database error: {$e->getCode()}"]);
    exit;
}

if ($request->getMethod() === 'POST') {
    if(!$request->request->get('question')) {
        $_SESSION['message'] = 'Question is required';
        header('Location: /');
        exit;
    }
    $model->addQuestion([
        'title' => $_POST['question'],
        'created_at' => date('Y-m-d H:i:s'),
    ]);
    $_SESSION['message'] = 'Question added';
    header('Location: /');
    exit;
}

echo $twig->render('index.html.twig', ['questions' => $records]);
