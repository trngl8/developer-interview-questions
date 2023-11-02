<?php

namespace App;

use Symfony\Component\Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Core
{
    private string $env;

    private bool $debug;

    private $corePath;

    private Environment $twig;
    public function __construct(string $APP_ENV, bool $APP_DEBUG)
    {
        $this->env = $APP_ENV;
        $this->debug = $APP_DEBUG;
    }

    public function init(): void
    {
        $dotenv = new Dotenv();
        $corePath = __DIR__.'/../';
        $dotenv->load($corePath . '.env');

        $customEnv = sprintf($corePath . '.env.%s', $this->env);
        if (file_exists($customEnv)) {
            $dotenv->load($customEnv);
        }

        $loader = new FilesystemLoader($corePath . 'templates');
        $this->twig = new Environment($loader, [
            'cache' => $corePath . 'var/cache/twig',
            'debug' => $this->debug,
        ]);
    }

    public function getTemplateEngine(): Environment
    {
        return $this->twig;
    }
}
