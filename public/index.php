<?php
//TODO: Exceptions code and messages
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

$router = new \FastFramework\Router\Router();

$router->loadFromController(\App\Controller\HomeController::class);
$router->listen();