<?php

namespace App;

use Symfony\Component\Dotenv\Dotenv;

class DatabaseFactory
{
    public static function create(string $env): Database
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/../.env');
        if (file_exists(__DIR__.sprintf('/../.env.%s', $env))) {
            $dotenv->load(__DIR__.sprintf('/../.env.%s', $env));
        }
        $dsn = $_ENV['DATABASE_DSN'];
        $matches = [];
        preg_match('/^(\w+):/', $dsn, $matches);
        $type = $matches[1];

        switch ($type) {
            case 'postgres':
                $params = parse_url($dsn);
                return new Database(sprintf("pgsql:host=%s;port=%d;dbname=%s", $params['host'], $params['port'], str_replace('/', '', $params['path'])),
                    $params['user'],
                    $params['pass']
                );
            default:
                return new Database("sqlite://" . __DIR__ . sprintf("/../var/%s.db",$env));
        }
    }
}
