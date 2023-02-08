<?php

namespace FastFramework\Router;

use Exception;
use FastFramework\FileSystem\Utils;
use FastFramework\Router\Attributes\Group;
use FastFramework\Router\Attributes\Route;
use FastFramework\Router\Exceptions\MethodNotSupported;
use FastFramework\Router\Exceptions\RouteAlreadyDeclared;
use FastFramework\Router\Exceptions\RouteNotFound;
use ReflectionException;
use ReflectionMethod;

class Router
{
    private RouteCollection $_routeCollection;

    public function __construct()
    {
        $this->_routeCollection = new RouteCollection();
    }

    /**
     * @param string ...$controller
     * @return void
     * @throws ReflectionException
     * @throws RouteAlreadyDeclared
     */
    public function loadFromController(string ...$controller): void
    {
        foreach ($controller as $c)
        {
            $reflect = new \ReflectionClass($c);
            $group = (empty($attr = $reflect->getAttributes(Group::class))) ? null : $attr[0]->newInstance();
            foreach ($reflect->getMethods() as $method)
            {
                if (!$route = $this->_isRoute($method)) continue;
                if ($group !== null) $route->addPrefix($group->getGroupName() ?? strtolower(explode("Controller", array_slice(explode("\\", $reflect->getName()), -1)[0])[0]));

                $this->_routeCollection->add($route, [$c, $method->getName()]);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function findAndLoad(): Router
    {
        $controllers = $this->_getControllers();
        $this->loadFromController(...$controllers);
        return $this;
    }

    /**
     * @param string|null $dir
     * @param string|null $suffix
     * @return array
     * @throws Exception
     */
    private function _getControllers(?string $dir = null, ?string $suffix = null): array
    {
        $controllers = [];
        if ($suffix !== null) $suffix = trim($suffix, "\\");
        $controllerNamespace = trim("App\\Controller\\" . ($suffix ?? "$suffix"), "\\");

        $dir = ($dir == null) ? Utils::guessPathByNamespace($controllerNamespace) : realpath("$dir/" . ($suffix ?? "$suffix/"));
        if ($dir === false) throw new Exception("Can't find the controllers directory.");

        foreach (array_diff(scandir($dir), [".", "..", ".gitignore"]) as $path)
        {
            $subject = $dir . DIRECTORY_SEPARATOR . $path;
            if (is_file($subject)) $controllers[] = $controllerNamespace . "\\" . explode(".", $path)[0];
            if (is_dir($subject)) $controllers = array_merge($controllers, $this->_getControllers($dir, $path));
        }
        return $controllers;
    }

    /**
     * @return mixed
     * @throws MethodNotSupported
     * @throws RouteNotFound
     */
    public function listen(): mixed
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = (!str_contains($_SERVER['REQUEST_URI'], '?')) ? $_SERVER['REQUEST_URI'] : explode('?', $_SERVER['REQUEST_URI'])[0];

        if (!$match = $this->_routeCollection->match($path)) throw new RouteNotFound("Route '$path' not found");
        if (!in_array($method, $match["route"]->getMethod())) throw new MethodNotSupported("Method '$method' not supported");

        return call_user_func_array($match["callback"], $match["route"]->getMatches());
    }

    /**
     * @param ReflectionMethod $method
     * @return bool|Route
     */
    private function _isRoute(ReflectionMethod $method): bool|Route
    {
        return (empty($method->getAttributes(Route::class))) ? false : $method->getAttributes(Route::class)[0]->newInstance();
    }
}