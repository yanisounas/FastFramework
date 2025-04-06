<?php

namespace App\Controller;

use App\Entity\UsersEntity;
use FastFramework\AbstractClass\Controller;
use FastFramework\AbstractClass\Entity;
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
        $user = new UsersEntity();
        $user->username = "admin8";
        $user->password = "admin";
        $orm = new ORM();
        $orm->persist($user);
        $users = $orm->getAll("Users");
        var_dump(Entity::toAssocArrayAll($users));

        return $this->view("home");
    }
}
