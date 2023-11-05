<?php

namespace App;

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

    public function run(Request $request, Model $model): Response
    {
        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory();
        $form = $formFactory->create(NewQuestionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $request->getMethod() === 'POST') {
            $data = $form->getData();
            if(!$data->title) {
                $_SESSION['message'] = [
                    'title' => 'Question title is required',
                    'type' => 'error',
                ];
                $this->lastResponse = new RedirectResponse('/', Response::HTTP_MOVED_PERMANENTLY);
                return $this->lastResponse;
            }
            $model->addQuestion([
                'title' => $data->title,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $_SESSION['message'] = [
                'title' => 'Question added',
                'type' => 'success',
            ];
            $this->lastResponse = new RedirectResponse('/', Response::HTTP_MOVED_PERMANENTLY);
            return $this->lastResponse;
        }

        $records = $model->getRecords();
        $content = $this->twig->render('index.html.twig', [
            'questions' => $records,
            'form' => $form->createView(),
        ]);

        $this->lastResponse = new Response($content);
        return $this->lastResponse;
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
           throw new \Exception($e->getMessage());
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
