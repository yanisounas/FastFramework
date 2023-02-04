<?php

namespace FastFramework\Router;

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
            foreach ((new \ReflectionClass($c))->getMethods() as $method)
                if ($route = $this->_isRoute($method)) $this->_routeCollection->add($route, [$c, $method->getName()]);
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