<?php

declare(strict_types=1);

namespace FastFramework\Http;

use Psr\Http\Message\StreamInterface;
use RuntimeException;
use InvalidArgumentException;

class Stream implements StreamInterface
{
    private const READABLE_MODE = ["r", "r+", "w+", "a+", "x+", "c+"];
    private const WRITABLE_MODE = ["w", "w+", "rw", "r+", "a", "a+", "x", "x+", "c", "c+"];

    private $stream;
    private bool $seekable;
    private bool $readable;
    private bool $writable;
    private array $metadata;

    public function __construct($stream = "php://temp", string $mode = "r+")
    {
        if (is_string($stream))
        {
            $this->stream = fopen($stream, $mode);
            if ($this->stream === false) throw new RuntimeException("Unable to open stream: $stream.");
        }
        elseif (is_resource($stream)) $this->stream = $stream;
        else throw new InvalidArgumentException("Invalid stream. Must be a string or a resource");

        $this->metadata = stream_get_meta_data($this->stream);
        $this->seekable = $this->metadata['seekable'];
        $this->readable = in_array($this->metadata["mode"], self::READABLE_MODE);
        $this->writable = in_array($this->metadata["mode"], self::WRITABLE_MODE);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        try
        {
            $this->seek(0);
            $content = stream_get_contents($this->stream);
            return ($content === false) ? "" : $content;
        }
        catch (RuntimeException $e)
        {
            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        if (is_resource($this->stream)) fclose($this->stream);
        $this->detach();
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        $result = $this->stream;
        $this->stream = null;
        $this->seekable = false;
        $this->readable = false;
        $this->writable = false;
        $this->metadata = [];

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        if (!$this->stream) return null;

        $stats = fstat($this->stream);
        return ($stats && isset($stats['size'])) ? $stats['size'] : null;
    }

    /**
     * @inheritDoc
     */
    public function tell(): int
    {
        if (!$this->stream) throw new RuntimeException("No stream available.");

        $result = ftell($this->stream);
        if ($result === false) throw new RuntimeException("Unable to determine stream position.");

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function eof(): bool { return !$this->stream || feof($this->stream); }

    /**
     * @inheritDoc
     */
    public function isSeekable(): bool { return $this->seekable; }

    /**
     * @inheritDoc
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->seekable) throw new RuntimeException("Stream is not seekable");

        if (fseek($this->stream, $offset, $whence) === -1) throw new RuntimeException("Unable to seek in stream.");
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void { $this->seek(0); }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool { return $this->writable; }

    /**
     * @inheritDoc
     */
    public function write(string $string): int
    {
        if (!$this->writable) throw new RuntimeException("Stream is not writable.");

        $result = fwrite($this->stream, $string);
        if ($result === false) throw new RuntimeException("Unable to write to stream.");

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isReadable(): bool { return $this->readable; }

    /**
     * @inheritDoc
     */
    public function read(int $length): string
    {
        if (!$this->readable) throw new RuntimeException("Stream is not readable");

        $result = fread($this->stream, $length);

        if ($result === false) throw new RuntimeException("Unable to read from stream.");

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getContents(): string
    {
        if (!$this->stream) throw new RuntimeException('No stream available.');

        $result = stream_get_contents($this->stream);
        if ($result === false) throw new RuntimeException('Unable to get stream contents.');


        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(?string $key = null)
    {
        if (!$this->stream) return $key === null ? [] : null;

        return ($key === null) ? $this->metadata : $this->metadata[$key] ?? null ;
    }
}