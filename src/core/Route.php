<?php declare(strict_types=1);

namespace Src\Core;

class Route
{
    protected static array $routes = [];

    /**
     * @param string $route
     * @param array|callable $callback
     * @return self
     */
    public static function get(string $route, array|callable $callback): self
    {
        return self::setRoutes(__FUNCTION__, $route, $callback);
    }

    /**
     * @param string $route
     * @param array|callable $callback
     * @return self
     */
    public static function post(string $route, array|callable $callback): self
    {
        return self::setRoutes(__FUNCTION__, $route, $callback);
    }

    /**
     * @param string $route
     * @param array|callable $callback
     * @return self
     */
    public static function put(string $route, array|callable $callback): self
    {
        return self::setRoutes(__FUNCTION__, $route, $callback);
    }

    /**
     * @param string $route
     * @param array|callable $callback
     * @return self
     */
    public static function delete(string $route, array|callable $callback): self
    {
        return self::setRoutes(__FUNCTION__, $route, $callback);
    }


    /**
     * @param string $route
     * @param array|callable $callback
     * @return self
     */
    public static function patch(string $route, array|callable $callback): self
    {
        return self::setRoutes(__FUNCTION__, $route, $callback);
    }

    /**
     * @param string $route
     * @param array|callable $callback
     * @return self
     */
    public static function options(string $route, array|callable $callback): self
    {
        return self::setRoutes(__FUNCTION__, $route, $callback);
    }

    /**
     * @param string $route
     * @param array|callable $callback
     * @return self
     */
    public static function head(string $route, array|callable $callback): self
    {
        return self::setRoutes(__FUNCTION__, $route, $callback);
    }

    /**
     * @param string $method
     * @param string $route
     * @param array|callable $callback
     * @return self
     */
    private static function setRoutes(string $method, string $route, array|callable $callback): self
    {
        self::$routes[$method][] = [
            "callable" => $callback,
            "path" => $route,
            "regex" => self::pregRoute($route)
        ];

        return new self;
    }

    /**
     * @param string $route
     * @return string
     */
    private static function pregRoute(string $route): string
    {
        $regex = preg_replace_callback(
            "/{([^}]+)}/",
            fn($matches) => "(?P<{$matches[1]}>[^/]+)",
            $route
        );

        $replaceSlashes = fn($subject) => str_replace("/", "\/", $subject);
        return "/^{$replaceSlashes($regex)}$/";
    }

    /**
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }
}