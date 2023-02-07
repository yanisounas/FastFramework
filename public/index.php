<?php
//TODO: Exceptions code and messages
//TODO: Use "realpath" in some cases instead of DIRECTORY_SEPARATOR + can help to guess the absolute path
ini_set('display_errors', 1);

if (is_file($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"]))
{
    if (array_slice(explode('.', $_SERVER["REQUEST_URI"]), -1)[0] == "css")
        header("Content-Type: text/css; charset=utf-8");
    else
        header(sprintf("Content-Type: %s;", mime_content_type($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"])));

    include $_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"];
    die;
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

\Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();

$router = new \FastFramework\Router\Router();
try {
    $router->findAndLoad()->listen();
} catch (Exception $e) {
    throw new \RuntimeException($e->getMessage());
}