<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Question;
use App\Database;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');
$db = new Database(
    sprintf("pgsql:host=localhost;port=5432;dbname=%s;", $_ENV['POSTGRES_DB']),
    $_ENV['POSTGRES_USER'],
    $_ENV['POSTGRES_PASSWORD']);
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/var/cache/twig'
]);

$model = new Question($db);
$records = $model->getQuestions();

echo $twig->render('index.html.twig', ['questions' => $records]);
