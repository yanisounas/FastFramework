<?php
declare(strict_types=1);

namespace FastFramework\FileSystem;

use FastFramework\FileSystem\Exceptions\FileSystemException;

class Utils
{
    /**
     * Guess absolute path using namespace and composer.json
     *
     * @param string $namespace
     * @return string|false Return the absolute path or false if directory does not exist or other failure (see realpath)
     */
    public static function guessPathByNamespace(string $namespace): string|false
    {
        $destructuredNamespace = explode("\\", trim($namespace, "\\"));
        $base = $destructuredNamespace[0] . "\\";
        array_shift($destructuredNamespace);

        $composer = json_decode(file_get_contents(realpath(BASE_DIR . "/composer.json")))
            ->autoload
            ->{"psr-4"};

        return realpath(sprintf("%s/%s", $composer->$base, implode("/", $destructuredNamespace)));
    }

    /**
     * Find the namespace of the src folder using composer.json
     *
     * @return string|false
     */
    public static function getSrcNamespace(): string|false
    {
        $composer = json_decode(file_get_contents(realpath(BASE_DIR . "/composer.json")), true)["autoload"]["psr-4"];
        $src = array_filter($composer, function ($dir) { return in_array($dir, ["src", "src/", "src\\"]); });

        return (empty($src)) ? false : array_key_first($src);
    }

    /**
     * Check if a path is absolute
     *
     * @param string $path
     * @return bool return true if path is absolute, false otherwise
     */
    public static function isAbsolutePath(string $path): bool
    {
        return strspn($path, '/\\', 0, 1)
            || (strlen($path) > 3 && ctype_alpha($path[0])
                && substr($path, 1, 1) === ':'
                && strspn($path, '/\\', 2, 1)
            )
            || null !== parse_url($path, PHP_URL_SCHEME)
            ;
    }
}