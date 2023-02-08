<?php

namespace FastFramework\FileSystem;

use FastFramework\FileSystem\Exceptions\FileNotFoundExceptions;

class File
{
    private array $stat;

    /**
     * @throws FileNotFoundExceptions
     */
    public function __construct(private string $path)
    {
        if (!is_file($path)) throw new FileNotFoundExceptions();

        $this->stat = stat($path);
        var_dump($this->stat);
    }


    /**
     * @return string
     */
    public function getPath(): string { return $this->path; }
    /**
     * @return int
     */
    public function device(): int { return $this->stat["dev"]; }
    public function inode(): int { return $this->stat["inode"]; }
    public function mode(): int { return $this->stat["mode"]; }
}