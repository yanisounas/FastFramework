<?php

namespace FastFramework\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        // TODO: Implement close() method.
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        // TODO: Implement detach() method.
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        // TODO: Implement getSize() method.
    }

    /**
     * @inheritDoc
     */
    public function tell(): int
    {
        // TODO: Implement tell() method.
    }

    /**
     * @inheritDoc
     */
    public function eof(): bool
    {
        // TODO: Implement eof() method.
    }

    /**
     * @inheritDoc
     */
    public function isSeekable(): bool
    {
        // TODO: Implement isSeekable() method.
    }

    /**
     * @inheritDoc
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        // TODO: Implement seek() method.
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        // TODO: Implement rewind() method.
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        // TODO: Implement isWritable() method.
    }

    /**
     * @inheritDoc
     */
    public function write(string $string): int
    {
        // TODO: Implement write() method.
    }

    /**
     * @inheritDoc
     */
    public function isReadable(): bool
    {
        // TODO: Implement isReadable() method.
    }

    /**
     * @inheritDoc
     */
    public function read(int $length): string
    {
        // TODO: Implement read() method.
    }

    /**
     * @inheritDoc
     */
    public function getContents(): string
    {
        // TODO: Implement getContents() method.
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(?string $key = null)
    {
        // TODO: Implement getMetadata() method.
    }
}