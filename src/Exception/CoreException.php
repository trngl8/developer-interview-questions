<?php

namespace App\Exception;

class CoreException extends \RuntimeException
{
    public function __construct(string $message = 'Core error', int $code = 500, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
