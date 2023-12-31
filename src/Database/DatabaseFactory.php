<?php

namespace App\Database;

use App\Exception\DatabaseException;

class DatabaseFactory
{
    public static function create(string $dsn): DatabaseConnection
    {
        $matches = [];
        if(!preg_match('/^(\w+):\/\/(.*)/', $dsn, $matches) || count($matches) !== 3) {
            throw new DatabaseException(sprintf('Invalid DSN: %s', $dsn));
        }

        $type = $matches[1];
        $path = $matches[2];

        try {
            switch ($type) {
                case 'postgres':
                    $params = parse_url($dsn);
                    return new PostgresDatabaseConnection(new \PDO(sprintf("pgsql:host=%s;port=%d;dbname=%s", $params['host'], $params['port'], str_replace('/', '', $params['path'])),
                        $params['user'],
                        $params['pass']
                    ));
                case 'sqlite':
                    return new SqliteDatabaseConnection(new \PDO(sprintf("sqlite://%s", $path)));
                default:
                    throw new DatabaseException(sprintf('Unsupported database type: %s', $type));
            }
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}
