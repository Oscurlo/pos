<?php declare(strict_types=1);

namespace Src\Core;

use Src\Abstract\Script;
use Src\Config\{AppConfig, Session};

final class Util
{
    public static function pre(mixed $value): string
    {
        return "<pre>" . json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "</pre>";
    }

    public static function print(string ...$expressions): void
    {
        Script::print(...$expressions);
    }

    public static function getTemplateBlade(string $file): string
    {
        return str_replace(
            ["\\app\\", ".php"],
            ["\\templates\\", ".blade.php"],
            $file
        );
    }

    public static function getRouteView(): array
    {
        $namespaceView = "Src\\App\\Views";

        $route = $namespaceView . AppConfig::CURRENT_ROUTE;

        if (str_ends_with($route, "/"))
            $route .= "Home";

        $route = str_replace(["/", "_"], ["\\", ""], $route);

        $route = self::upperFirstNamespace($route);

        return [AppConfig::CURRENT_ROUTE, [$route, "view"]];
    }

    public static function upperFirstNamespace(string $string): string
    {
        return implode(
            "\\",
            array_map(
                fn($name) => ucfirst($name),
                explode("\\", $string)
            )
        );
    }

    /**
     * @return string
     */
    public static function generateCsrfToken(): string
    {
        return bin2hex(random_bytes(32));
    }


    /**
     * @param string $token
     * @return bool
     */
    public static function verifyCsrfToken(string $token): bool
    {
        $csrf_token = Session::get("csrf");
        return !is_null($csrf_token) && hash_equals($csrf_token, $token);
    }

    /**
     * @param array|callable $response
     * @param bool $checkCsrf
     * @param int $status
     */
    public static function apiResponse(array|callable $response, bool $checkCsrf = true, int $status = 200): string
    {
        $csrfToken = $_SERVER["HTTP_CSRF_TOKEN"] ?? "";

        if ($checkCsrf && !self::verifyCsrfToken($csrfToken)) {
            http_response_code(403);
            return json_encode(["error" => "Invalid CSrf"]);
        } else if (is_callable($response)) {
            $response = $response();
        }

        http_response_code($status);
        return json_encode(
            $response,
            JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
        );
    }
}