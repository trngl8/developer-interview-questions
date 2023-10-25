<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Question;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$model = new Question(
    sprintf("pgsql:host=localhost;port=5432;dbname=%s;", $_ENV['POSTGRES_DB']),
    $_ENV['POSTGRES_USER'],
    $_ENV['POSTGRES_PASSWORD']);

$records = $model->getRecords();

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/var/cache/twig'
]);

echo $twig->render('index.html.twig', ['records' => $records]);
