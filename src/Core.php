<?php

namespace App;

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
    }

    public function run(Request $request, $model): Response
    {
        if ($request->getMethod() === 'POST') {
            if(!$request->request->get('question')) {
                $_SESSION['message'] = 'Question is required';
                return new RedirectResponse('/', Response::HTTP_MOVED_PERMANENTLY);
            }
            $model->addQuestion([
                'title' => $request->request->get('question'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $_SESSION['message'] = 'Question added';
            return new RedirectResponse('/', Response::HTTP_MOVED_PERMANENTLY);
        }
        $records = $model->getQuestions();
        $content = $this->twig->render('index.html.twig', ['questions' => $records]);
        return new Response($content);
    }

    public function getTemplateEngine(): Environment
    {
        return $this->twig;
    }
}
