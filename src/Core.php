<?php

namespace App;

use App\Controller\IndexController;
use App\Exception\DatabaseException;
use App\Form\NewQuestionType;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;
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

        $this->env = $APP_ENV;
        $this->debug = $APP_DEBUG;
        $r = new \ReflectionObject($this);
        $dir = $rootDir = \dirname($r->getFileName());
        while (!is_file($dir.'/composer.json')) {
            if ($dir === \dirname($dir)) {
                $dir = $rootDir;
            }
            $dir = \dirname($dir);
        }
        $this->corePath = $dir . '/';
        $this->dotenv->load($this->corePath . '.env');
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

    public function run(Request $request): void
    {
        try {
            $db = $this->getDatabase($_ENV['DATABASE_DSN']);
            $model = new Question($db);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        $path = $request->getPathInfo();
        switch ($path) {
            case '/':
                $this->lastResponse = (new IndexController($this->twig))->index($request, $model);
                break;
            default:
                $this->lastResponse = new Response('Not found', Response::HTTP_NOT_FOUND);
        }
        $this->lastResponse->send();
    }

    public function getDatabase($dsn = null)
    {
        if (!$dsn) {
            $dsn = $this->databaseDSN;
        }

        $dsn = str_replace('%kernel.project_directory%', $this->corePath, $dsn);
        try {
            $db = DatabaseFactory::create($dsn);
        } catch (\Exception $e) {
           throw new DatabaseException($e->getMessage());
        }

        return $db;
    }

    public function getRootDir(): string
    {
        return $this->corePath;
    }

    public function getExceptionResponse(): Response
    {
        $this->lastResponse = new Response($this->twig->render('error.html.twig', ['message' => 'General error']));
        return $this->lastResponse;
    }

    public function getTemplateEngine(): Environment
    {
        return $this->twig;
    }
}
