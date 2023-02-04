<?php

namespace FastFramework\AbstractClass;

use FastFramework\Response\JSONResponse;
use FastFramework\Response\Response;
use FastFramework\Response\View;

class Controller
{
    /**
     * @param string $content
     * @param int|null $statusCode
     * @param string|null $contentType
     * @return Response
     */
    public function response(string $content, ?int $statusCode = null, ?string $contentType = null): Response
    {
        return new Response($content, $statusCode, $contentType);
    }

    /**
     * @param array $content
     * @param int|null $statusCode
     * @return JSONResponse
     */
    public function json(array $content, ?int $statusCode = null): JSONResponse
    {
        return new JSONResponse($content, $statusCode);
    }

    /**
     * @param string $path
     * @param array|null $args
     * @param int|null $statusCode
     * @return View
     */
    public function view(string $path, ?array $args = null, ?int $statusCode = null): View
    {
        return new View($path, $statusCode, args: $args);
    }
}