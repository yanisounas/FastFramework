<?php

namespace App\Controller;

use FastFramework\AbstractClass\Controller;
use FastFramework\Response\View;
use FastFramework\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/", method: "GET")]
    public function index(): View
    {
        return $this->view("home");
    }
}