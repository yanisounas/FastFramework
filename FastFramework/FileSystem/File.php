<?php
declare(strict_types=1);

namespace FastFramework\FileSystem;


class File
{
    private array $stat;

    public function __construct(private readonly string $path)
    {
    }

    public function open() {}
    public function close() {}
    public function read() {}
    public function write() {}


    /**
     * @return string
     */
    public function getPath(): string { return $this->path; }
}