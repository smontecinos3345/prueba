<?php

namespace App;

use App\Adapter\In\Http\NotFoundException;
use App\Adapter\In\Http\Request;

class Kernel
{
    private array $routes;

    public function __construct(
        array $routes,
        private Container $container
    ) {
        $this->routes = $routes;
    }

    public function handle(Request $request)
    {
        $route = $request->getMethod() . ' ' . $request->getUri();

        if (!isset($this->routes[$route])) {
            throw new NotFoundException("Route not found: $route");
        }

        $controllerClass = $this->container->get($this->routes[$route]);

        return $controllerClass($request);
    }
}
