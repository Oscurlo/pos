<?php declare(strict_types=1);

namespace Src\Core;

use Exception;
use Src\Abstract\MiddlewareAware;
use Src\Config\AppConfig;

final class Router extends MiddlewareAware
{
    protected string $method;
    protected string $uri;
    protected array $routes;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        self::loadRoutes(AppConfig::BASE_FOLDER . "/src/routes/web.php");

        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->routes = Route::getRoutes();
    }

    public static function provider(?string $route = null): mixed
    {
        $static = new static;
        [$static->uri] = explode("?", $route ?? AppConfig::CURRENT_ROUTE);
        return $static->callMiddlewares([$static, "processRoutes"]);
    }

    /**
     * @throws Exception
     */
    protected function processRoutes(): void
    {
        if (isset($this->routes[strtolower($this->method)])) {
            foreach ($this->routes[strtolower($this->method)] as $route) {
                extract($route);
                if (self::matchRoute($regex)) {
                    $params = self::extractParams($regex);
                    self::executeCallback($callable, $params);
                    return;
                }
            }
        }

        self::notFound();
    }


    protected function matchRoute(string $regex): bool
    {
        return (bool) preg_match($regex, $this->uri);
    }

    protected function extractParams(string $regex): array
    {
        $params = [];

        $params["query_params"] = $_REQUEST;
        $params["json_body"] = json_decode(file_get_contents("php://input"), true);

        if (preg_match($regex, $this->uri, $matches)) {
            foreach ($matches as $key => $value) {
                if (!is_numeric($key)) {
                    $params[$key] = $value;
                }
            }
        }

        return $params;
    }

    /**
     * @throws Exception
     */
    protected function executeCallback(callable|array|string $callback, array $params): void
    {
        $props = [(object) $params];

        if (is_callable($callback)) {
            call_user_func_array($callback, $props);
        } elseif (is_array($callback) && count($callback) === 2) {
            [$controller, $method] = $callback;

            if (class_exists($controller) && method_exists($controller, $method)) {
                call_user_func_array([new $controller, $method], $props);
            } else {
                throw new Exception("Método o controlador no encontrado: {$controller}::{$method}");
            }

        } else {
            throw new Exception("Callback no válido");
        }
    }

    protected function notFound(): void
    {
        http_response_code(404);
        Util::print("404 - Not Found");
    }

    /**
     * @throws Exception
     */
    private static function loadRoutes(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception("File not found: {$path}");
        }

        include_once $path;
    }
}