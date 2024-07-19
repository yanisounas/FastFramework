<?php

declare(strict_types=1);

namespace FastFramework\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    use MessageTrait;

    public function __construct(
        private int $statusCode = 200,
        array $headers = [],
        private ?string $reasonPhrase = null,
        ?StreamInterface $body = null
    )
    {
        $this->reasonPhrase = $this->reasonPhrase ?? Utils::getReasonPhraseFromCode($this->statusCode);
        $this->body = $body;
        $this->setHeaders($headers);
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int { return $this->statusCode; }

    /**
     * @inheritDoc
     */
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $newResponse = clone $this;
        $newResponse->statusCode = $code;
        $newResponse->reasonPhrase = $reasonPhrase ?? Utils::getReasonPhraseFromCode($code);

        return $newResponse;
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string { return $this->reasonPhrase; }
}
