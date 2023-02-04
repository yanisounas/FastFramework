<?php

namespace FastFramework\Router\Exceptions;

use Throwable;

class RouteAlreadyDeclared extends RouterException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}