<?php

declare(strict_types=1);

namespace Src\Core;

final class Middleware
{
    public static function auth(callable $next): mixed
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            echo "Access denied: Please log in";
            exit;
        }

        return $next();
    }
}