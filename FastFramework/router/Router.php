<?php

namespace FastFramework\Router;

use FastFramework\Router\Attributes\Route;
use FastFramework\Router\Exceptions\MethodNotSupported;
use FastFramework\Router\Exceptions\RouteAlreadyDeclared;
use FastFramework\Router\Exceptions\RouteNotFound;
use ReflectionException;

class Router
{
    private RouteCollection $_routeCollection;

    public function __construct()
    {
        $this->_routeCollection = new RouteCollection();
    }

    /**
     * @throws ReflectionException|RouteAlreadyDeclared
     */
    public function loadFromController(string ...$controller): void
    {
        foreach ($controller as $c)
            foreach ((new \ReflectionClass($c))->getMethods() as $method)
                if ($route = $this->_isRoute($method)) $this->_routeCollection->add($route, [$c, $method->getName()]);
    }

    /**
     * @throws RouteNotFound|MethodNotSupported
     */
    public function listen(): mixed
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = (!str_contains($_SERVER['REQUEST_URI'], '?')) ? $_SERVER['REQUEST_URI'] : explode('?', $_SERVER['REQUEST_URI'])[0];

        if (!$match = $this->_routeCollection->match($path)) throw new RouteNotFound("Route '$path' not found");
        if (!in_array($method, $match["route"]->getMethod())) throw new MethodNotSupported("Method '$method' not supported");

        return call_user_func_array($match["callback"], $match["route"]->getMatches());
    }

    private function _isRoute(\ReflectionMethod $method): bool|Route
    {
        return (empty($method->getAttributes(Route::class))) ? false : $method->getAttributes(Route::class)[0]->newInstance();
    }
}