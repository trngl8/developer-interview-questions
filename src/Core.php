<?php

namespace App;

use Symfony\Component\Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Core
{
    private string $env;

    private bool $debug;

    private Environment $twig;
    public function __construct(string $APP_ENV, bool $APP_DEBUG)
    {
        $this->env = $APP_ENV;
        $this->debug = $APP_DEBUG;
    }

    public function init(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/.env');

        $customEnv = sprintf('%s/.env.%s', __DIR__, $this->env);
        if (file_exists($customEnv)) {
            $dotenv->load($customEnv);
        }

        $loader = new FilesystemLoader(__DIR__ . '/templates');
        $this->twig = new Environment($loader, [
            'cache' => __DIR__ . '/var/cache/twig',
            'debug' => $this->debug,
        ]);
    }

    public function getTemplateEngine(): Environment
    {
        return $this->twig;
    }
}
