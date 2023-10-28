<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\DatabaseFactory;
use App\Question;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');
$db = DatabaseFactory::create($_ENV['APP_ENV']);
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/var/cache/twig',
    'debug' => $_ENV['APP_DEBUG'],
]);

$model = new Question($db);
$records = $model->getQuestions();
echo $twig->render('index.html.twig', ['questions' => $records]);
