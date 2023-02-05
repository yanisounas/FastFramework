<?php
//TODO: Exceptions code and messages
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

$router = new \FastFramework\Router\Router();

$router->loadFromController(\App\Controller\HomeController::class);
$router->listen();