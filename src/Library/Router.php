<?php

namespace App\Library;

use App\Library\Route;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Router
{
    private array $routes = [];

    public function registerControllerRoutes(object $controller): array
    {
        $reflection = new \ReflectionClass($controller);
        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(Route::class);
            foreach ($attributes as $attribute) {
                /** @var Route $instance */
                $route = $attribute->newInstance();
                $key = $route->path . ':' . $route->method;
                $this->routes[$key] = [$controller, $method->getName()];
            }
        }
        return $this->routes;
    }
}
