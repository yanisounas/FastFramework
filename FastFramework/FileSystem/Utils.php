<?php

namespace FastFramework\FileSystem;

class Utils
{
    /**
     * Guess absolute path using namespace and composer.json
     *
     *
     * @param string $namespace
     * @return string|false Return the absolute path or false if directory does not exist or other failure (see realpath)
     */
    public static function guessPathByNamespace(string $namespace): string|false
    {
        $destructuredNamespace = explode("\\", trim($namespace, "\\"));
        $base = $destructuredNamespace[0] . "\\";
        array_shift($destructuredNamespace);

        $composer = json_decode(file_get_contents(realpath(dirname($_SERVER["DOCUMENT_ROOT"]) . "/composer.json")))
            ->autoload
            ->{"psr-4"};

        return realpath(sprintf("%s/%s", $composer->$base, implode("/", $destructuredNamespace)));
    }
}