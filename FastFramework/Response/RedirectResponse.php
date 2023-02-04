<?php

namespace FastFramework\Response;

class RedirectResponse extends Response
{
    public function __construct(
        string $location,
        ?int $statusCode = Response::MOVED_PERMANENTLY,
        ?string $contentType = null)
    {
        parent::__construct("", $statusCode, $contentType);
        die(header("Location: $location"));
    }
}