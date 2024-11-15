<?php declare(strict_types=1);

namespace Src\Config;

use Dotenv\Dotenv;
use Exception;

final class Env
{
    public static bool $isLoaded = false;
    public function __construct()
    {
        self::load();
    }

    /**
     * @return void
     */
    public static function load(): void
    {
        self::$isLoaded = true;
        Dotenv::createImmutable(AppConfig::BASE_FOLDER)->load();
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public static function get(string $name): mixed
    {
        if (!self::$isLoaded) {
            throw new Exception("Environment variables are not loaded");
        }

        return $_ENV[$name] ?? null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll(): array
    {
        if (!self::$isLoaded) {
            throw new Exception("Environment variables are not loaded");
        }

        return $_ENV;
    }
}