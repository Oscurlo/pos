<?php declare(strict_types=1);

namespace Src\Abstract;

abstract class MiddlewareAware
{
    protected array $middlewares = [];

    public function middleware(callable $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    protected function callMiddlewares(callable $callback, array $args = []): mixed
    {
        $next = fn() => call_user_func_array($callback, $args);

        foreach ($this->middlewares as $middleware) {
            $next = fn() => $middleware($next);
        }

        return $next();
    }
}