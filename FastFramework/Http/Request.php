<?php

declare(strict_types=1);

namespace FastFramework\Http;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{
    use MessageTrait;

    private ?string $requestTarget;

    public function __construct(
        private UriInterface $uri,
        private string $method = "GET",
        array $headers = [],
        ?StreamInterface $body = null,
        string $protocolVersion = "1.1"
    )
    {
        $this->setHeaders($headers);
        if (!isset($this->headersNormalized["host"])) { $this->updateHostFromUri(); }
        $this->protocolVersion = $protocolVersion;
        $this->body = $body;
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        if ($this->requestTarget) { return $this->requestTarget; }

        $this->requestTarget = $this->uri->getPath();
        if ($this->requestTarget == "") { $this->requestTarget = "/"; }
        if ($this->uri->getQuery()) { $this->requestTarget .= "?". $this->uri->getQuery(); }

        return $this->requestTarget;
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        if (str_contains($requestTarget, " ")) { throw new InvalidArgumentException("Request target cannot contain spaces"); }

        $newRequest = clone $this;
        $newRequest->requestTarget = $requestTarget;

        return $newRequest;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string { return $this->method; }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestInterface
    {
        $newRequest = clone $this;
        $newRequest->method = $method;

        return $newRequest;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface { return $this->uri; }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $newRequest = clone $this;
        $newRequest->uri = $uri;

        if (!$preserveHost || !isset($this->headers["host"])) { $newRequest->updateHostFromUri(); }

        return $newRequest;
    }

    /*
     * Method taken from the guzzle/psr7 package
     */
    private function updateHostFromUri(): void
    {
        $host = $this->uri->getHost();

        if ($host == "") { return; }
        if (($port = $this->uri->getPort()) !== null) { $host .= ":".$port; }

        // Ensure Host is the first header.
        // See: https://datatracker.ietf.org/doc/html/rfc7230#section-5.4
        $this->headers["host"] = (array)$host + $this->headers;
    }
}