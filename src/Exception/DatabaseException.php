<?php

namespace App\Exception;

class DatabaseException extends \RuntimeException
{
    public function __construct(string $message = 'Database connection error', int $code = 500, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
