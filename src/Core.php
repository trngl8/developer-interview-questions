<?php

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Core
{
    private string $env;

    private bool $debug;

    private $corePath;

    private Environment $twig;

    private Dotenv $dotenv;

    private Response $lastResponse;

    private  $databaseDSN;

    public function __construct(string $APP_ENV, bool $APP_DEBUG)
    {
        $this->dotenv = new Dotenv();
        $this->corePath = __DIR__.'/../';
        $this->dotenv->load($this->corePath . '.env');
        $this->env = $APP_ENV;
        $this->debug = $APP_DEBUG;
    }

    public function init(): void
    {
        $customEnv = sprintf($this->corePath . '.env.%s', $this->env);
        if (file_exists($customEnv)) {
            $this->dotenv->load($customEnv);
        }

        $loader = new FilesystemLoader($this->corePath . 'templates');
        $this->twig = new Environment($loader, [
            'cache' => $this->corePath . 'var/cache/twig',
            'debug' => $this->debug,
        ]);

        $this->databaseDSN = $_ENV['DATABASE_DSN'];
    }

    public function run(Request $request, $model): Response
    {
        if ($request->getMethod() === 'POST') {
            if(!$request->request->get('question')) {
                $_SESSION['message'] = 'Question is required';
                $this->lastResponse = new RedirectResponse('/', Response::HTTP_MOVED_PERMANENTLY);
                return $this->lastResponse;
            }
            $model->addQuestion([
                'title' => $request->request->get('question'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $_SESSION['message'] = 'Question added';
            $this->lastResponse = new RedirectResponse('/', Response::HTTP_MOVED_PERMANENTLY);
            return $this->lastResponse;
        }
        $records = $model->getQuestions();
        $content = $this->twig->render('index.html.twig', ['questions' => $records]);
        $this->lastResponse = new Response($content);
        return $this->lastResponse;
    }

    public function getDatabase($dsn = null)
    {
        if (!$dsn) {
            $dsn = $this->databaseDSN;
        }

        try {
            $db = DatabaseFactory::create($dsn);
        } catch (\Exception $e) {
            $log = new Logger('database');
            $log->pushHandler(new StreamHandler(__DIR__ . '/var/logs/database.log', Level::Warning));
            $log->pushProcessor(function ($logItem) use ($e) {
                $logItem->extra['file'] = $e->getFile();
                return $logItem;
            });
            $log->error($e->getMessage(), ['line' => $e->getLine()]);
            $this->lastResponse = new Response($this->twig->render('error.html.twig', ['message' => "Database error: {$e->getCode()}"]));
        }

        return $db;
    }

    public function getTemplateEngine(): Environment
    {
        return $this->twig;
    }
}
