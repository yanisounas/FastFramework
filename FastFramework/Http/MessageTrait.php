<?php

namespace FastFramework\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/*
 * Implementation of redundant methods in Request and Response
 */
Trait MessageTrait
{
    /** @var string[][] All registered headers with original name */
    private array $headers;

    /** @var string[] Lowercase header's name => original name */
    private array $headersNormalized;

    private string $protocolVersion = "1.1";

    private StreamInterface $body;

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string { return $this->protocolVersion; }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        if ($this->protocolVersion == $version) { return $this; }

        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array { return $this->headers; }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool { return isset($this->headersNormalized[strtolower($name)]); }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        $name = strtolower($name);
        if (!isset($this->headersNormalized[$name])) { return []; }

        return $this->headers[$this->headersNormalized[$name]];
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string { return implode(', ', $this->getHeader($name)); }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        $normalized = strtolower($name);

        $new = clone $this;
        $new->headersNormalized[$normalized] = $name;
        $new->headers[$name] = (array)$value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $normalized = strtolower($name);

        $new = clone $this;
        if(!isset($new->headersNormalized[$normalized]))
        {
            $new->headersNormalized[$normalized] = $name;
            $new->headers[$name] = [];
        }
        $new->headers[$name] = array_merge($new->headers[$name], (array)$value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): MessageInterface
    {
        $normalized = strtolower($name);

        if (!isset($this->headersNormalized[$normalized])) { return $this; }

        $new = clone $this;
        $header = $new->headersNormalized[$normalized];
        unset($new->headersNormalized[$normalized], $new->headers[$header]);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface { return $this->body; }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        if ($body === $this->body) { return $this; }

        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    private function setHeaders(array $headers): void
    {
        $this->headers = $this->headersNormalized = [];

        foreach ($headers as $header => $value)
        {
            $normalized = strtolower($header);

            if (isset($this->headersNormalized[$normalized]))
            {
                $header = $this->headersNormalized[$normalized];
                $this->headers[$header] = array_merge($this->headers[$header], $value);
            }
            else
            {
                $this->headersNormalized[$normalized] = $header;
                $this->headers[$header] = $value;
            }
        }
    }
}