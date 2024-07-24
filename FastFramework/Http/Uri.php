<?php

declare(strict_types=1);

namespace FastFramework\Http;

use FastFramework\Http\Exceptions\MalformedUriException;
use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $scheme = "";
    private string $userInfo = "";
    private string $host = "";
    private ?int $port = null;
    private string $path = "";
    private string $query = "";
    private string $fragment = "";
    private ?string $composed = null;

    /**
     * @throws MalformedUriException
     */
    public function __construct(string $uri = "")
    {
        if ($uri)
        {
            $parts = parse_url($uri);
            if (!$parts) throw new MalformedUriException("Unable to parse URI: $uri");

            $this->scheme = $parts["scheme"] ?? "";
            $this->userInfo = $parts["user"] ?? "";
            if (isset($parts['pass'])) {
                $this->userInfo .= ":{$parts['pass']}";
            }
            $this->host = $parts["host"] ?? "";
            $this->port = $parts["port"] ?? null;
            $this->path = $parts["path"] ?? "";
            $this->query = $parts["query"] ?? "";
            $this->fragment = $parts["fragment"] ?? "";
        }
    }

    /**
     * @inheritDoc
     */
    public function getScheme(): string { return $this->scheme; }

    /**
     * @inheritDoc
     */
    public function getAuthority(): string
    {
        $authority = $this->host;

        if (!empty($this->userInfo)) $authority = $this->userInfo . "@" . $authority;
        if ($this->port) $authority .= ":" . $this->port;

        return $authority;
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo(): string { return $this->userInfo; }

    /**
     * @inheritDoc
     */
    public function getHost(): string { return $this->host; }

    /**
     * @inheritDoc
     */
    public function getPort(): ?int { return $this->port; }

    /**
     * @inheritDoc
     */
    public function getPath(): string { return $this->path; }

    /**
     * @inheritDoc
     */
    public function getQuery(): string { return $this->query; }

    /**
     * @inheritDoc
     */
    public function getFragment(): string { return $this->fragment; }

    /**
     * @inheritDoc
     */
    public function withScheme(string $scheme): UriInterface
    {
        $newUri = clone $this;
        $newUri->scheme = $scheme;
        $newUri->composed = null;
        return $newUri;
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $newUri = clone $this;
        $newUri->userInfo = ($password !== null) ? "$user:$password" : $user;
        $newUri->composed = null;
        return $newUri;
    }

    /**
     * @inheritDoc
     */
    public function withHost(string $host): UriInterface
    {
        $newUri = clone $this;
        $newUri->host = $host;
        $newUri->composed = null;
        return $newUri;
    }

    /**
     * @inheritDoc
     */
    public function withPort(?int $port): UriInterface
    {
        $newUri = clone $this;
        $newUri->port = $port;
        $newUri->composed = null;
        return $newUri;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): UriInterface
    {
        $newUri = clone $this;
        $newUri->path = $path;
        $newUri->composed = null;
        return $newUri;
    }

    /**
     * @inheritDoc
     */
    public function withQuery(string $query): UriInterface
    {
        $newUri = clone $this;
        $newUri->query = $query;
        $newUri->composed = null;
        return $newUri;
    }

    /**
     * @inheritDoc
     */
    public function withFragment(string $fragment): UriInterface
    {
        $newUri = clone $this;
        $newUri->fragment = $fragment;
        $newUri->composed = null;
        return $newUri;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        if (!$this->composed) $this->composed = $this->_compose();

        return $this->composed;
    }

    private function _compose(): string
    {
        $uri = '';

        if ($this->scheme !== "") $uri .= $this->scheme . ":";
        if ($this->getAuthority() !== "" || $this->scheme === "file") $uri .= "//" . $this->getAuthority();
        $uri .= ($this->getAuthority() !== "" && $this->path !== "" && $this->path[0] !== "/") ? "/" . $this->path : $this->path;
        if ($this->query !== "") $uri .= "?" . $this->query;
        if ($this->fragment !== "") $uri .= "#" . $this->fragment;

        return $uri;
    }
}