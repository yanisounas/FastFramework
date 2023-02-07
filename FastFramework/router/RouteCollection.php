<?php

namespace FastFramework\Router;

use ArrayAccess;
use FastFramework\Router\Attributes\Route;
use FastFramework\Router\Exceptions\RouteAlreadyDeclared;
use JetBrains\PhpStorm\ArrayShape;

class RouteCollection
{
    #[ArrayShape(["route" => Route::class, "callback" => 'callable'])]
    private array $_data = [];

    public function __toString(): string
    {
        //TODO: Implements __toString();
        return "";
    }

    /**
     * @param Route $route
     * @param callable|array $callback
     * @return void
     * @throws RouteAlreadyDeclared
     */
    public function add(Route $route, callable|array $callback): void
    {
        if (isset($this->_data[$route->getPath()])) throw new RouteAlreadyDeclared(sprintf("Router %s already exists", $route->getPath()));
        if (!is_callable($callback)) $callback[0] = new $callback[0]();

        $this->_data[$route->getPath()] = ["route" => $route, "callback" => $callback];
    }

    /**
     * @param string $path
     * @return array|bool
     */
    public function match(string $path): array|bool
    {
        foreach ($this->_data as $data)
            if ($data["route"]->match($path)) return $data;
        return false;
    }
}