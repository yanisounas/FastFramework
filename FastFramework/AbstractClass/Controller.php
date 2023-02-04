<?php

namespace FastFramework\AbstractClass;

use FastFramework\Response\JSONResponse;
use FastFramework\Response\Response;
use FastFramework\Response\View;

class Controller
{
    public function response(string $content, ?int $statusCode = null, ?string $contentType = null): Response
    {
        return new Response($content, $statusCode, $contentType);
    }

    public function json(array $content, ?int $statusCode = null): JSONResponse
    {
        return new JSONResponse($content, $statusCode);
    }

    public function view(string $path, ?array $args = null, ?int $statusCode = null, bool $extract = true): View
    {
        return new View();
    }
}