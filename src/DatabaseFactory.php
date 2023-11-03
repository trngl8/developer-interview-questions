<?php

namespace App;

class DatabaseFactory
{
    public static function create(string $dsn): DatabaseConnection
    {
        $matches = [];
        preg_match('/^(\w+):\/\/(.*)/', $dsn, $matches);
        $type = $matches[1];
        $path = $matches[2];

        switch ($type) {
            case 'postgres':
                $params = parse_url($dsn);
                return new PostgresDatabaseConnection(sprintf("pgsql:host=%s;port=%d;dbname=%s", $params['host'], $params['port'], str_replace('/', '', $params['path'])),
                    $params['user'],
                    $params['pass']
                );
            case 'sqlite':
                return new SqliteDatabaseConnection(sprintf("sqlite://%s", $path));
            default:
                throw new \Exception(sprintf('Unsupported database type: %s', $type));
        }
    }
}
