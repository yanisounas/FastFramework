<?php

namespace App\Controller;

use FastFramework\AbstractClass\Controller;
use FastFramework\ORM\Exceptions\ORMException;
use FastFramework\ORM\ORM;
use FastFramework\Response\View;
use FastFramework\Router\Attributes\Route;
use ReflectionException;

class HomeController extends Controller
{
    /**
     * @throws ReflectionException
     * @throws ORMException
     */
    #[Route("/", method: "GET")]
    public function index(): View
    {
        $orm = new ORM();
        $orm->make("Users", ["username" => "admin1", "password" => "admin"]);
        return $this->view("home");
    }
}