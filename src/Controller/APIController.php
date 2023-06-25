<?php

namespace App\Controller;

use FastFramework\AbstractClass\Controller;
use FastFramework\Response\Response;
use FastFramework\Router\Attributes\Group;
use FastFramework\Router\Attributes\Route;

#[Group]
class APIController extends Controller
{
    #[Route("/doc")]
    public function home(): Response
    {
        return $this->response("DOC");
    }
}