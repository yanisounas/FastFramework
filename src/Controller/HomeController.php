<?php

namespace App\Controller;

use FastFramework\Router\Attributes\Route;

class HomeController
{
    #[Route("/", method: "GET")]
    public function index(): void
    {
        echo "index";
    }
}