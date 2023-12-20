<?php

namespace App;

use App\Controller\ApiController;
use App\Controller\IndexController;
use App\Database\DatabaseConnection;
use App\Database\DatabaseFactory;
use App\Exception\CoreException;
use App\Exception\DatabaseException;
use App\Model\Question;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
        $this->env = $APP_ENV;
        $this->debug = $APP_DEBUG;
        $this->lastResponse = new Response("Not Found", Response::HTTP_NOT_FOUND);
    }

    public function init(): void
    {
        $this->dotenv = new Dotenv();

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

        $customEnv = sprintf($this->corePath . '.env.%s', $this->env);
        if (file_exists($customEnv)) {
            $this->dotenv->load($customEnv);
        }

        if(isset($_ENV['APP_THEME'])) {
            if (!file_exists($this->corePath . 'twig/themes/' . $_ENV['APP_THEME'])) {
                throw new \Exception(sprintf('Theme %s not found', $_ENV['APP_THEME']));
            }
            $loader = new FilesystemLoader($this->corePath . 'twig/themes/' . $_ENV['APP_THEME']);
            $this->twig = new Environment($loader, [
                'cache' => $this->corePath . 'var/cache/twig/themes/' . $_ENV['APP_THEME'],
                'debug' => $this->debug,
            ]);
        } else {
            $loader = new FilesystemLoader($this->corePath . 'templates');
            $this->twig = new Environment($loader, [
                'cache' => $this->corePath . 'var/cache/twig',
                'debug' => $this->debug,
            ]);
        }

        //TODO: check root template exists

        $this->databaseDSN = $_ENV['DATABASE_DSN'];
    }

    public function run(Request $request): void
    {
        try {
            $db = $this->connectDatabase($_ENV['DATABASE_DSN']);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        $model = new Question($db);

        $requestUri = $request->getPathInfo();
        $routes = [
            '/' => [IndexController::class, 'index'],
            '/api' => [ApiController::class, 'index'],
            '/api/questions/(\d+)/show' => [ApiController::class, 'show'],
            '/api/questions/(\d+)/delete' => [ApiController::class, 'delete'],
        ];
        $routes = array_reverse($routes);
        $matches = [];
        foreach ($routes as $pattern => $action) {
            $pattern = '#' . str_replace('/', '\/', $pattern) . '#';

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);
                $controller = new $action[0]($this->twig);
                try {
                    $this->lastResponse = call_user_func_array([$controller, $action[1]], array_merge([$request, $model], $matches));
                } catch (\TypeError $e) {
                    throw new CoreException($e->getMessage());
                }

                break;
            }
        }
    }

    public function connectDatabase($dsn = null): DatabaseConnection
    {
        if (!$dsn) {
            $dsn = $this->databaseDSN;
        }

        $dsn = str_replace('%kernel.project_directory%', $this->corePath, $dsn);
        try {
            $db = DatabaseFactory::create($dsn);
        } catch (DatabaseException $e) {
           throw new CoreException($e->getMessage());
        }

        return $db;
    }

    public function getRootDir(): string
    {
        return $this->corePath;
    }

    public function getExceptionResponse(): Response
    {
        $this->lastResponse = new Response($this->twig->render('error.html.twig', ['message' => 'Not found']), Response::HTTP_NOT_FOUND);
        return $this->lastResponse;
    }

    public function getTemplateEngine(): Environment
    {
        return $this->twig;
    }

    public function getLastResponse(): Response
    {
        return $this->lastResponse;
    }

    public static function getMailerSettings() : \ArrayObject
    {
        if($_ENV['MAILER_ADMIN_EMAIL'])  {
            new \ArrayObject([
                'adminEmail' => $_ENV['MAILER_ADMIN_EMAIL'],
            ]);
        }
        return new \ArrayObject();
    }
}
