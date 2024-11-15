<?php declare(strict_types=1);

namespace Src\Abstract;

use Src\Config\AppConfig;
use Src\Core\Util;

abstract class Script
{
    const COLOR = [
        "RESET" => "\033[0m",
        "BLACK" => "\033[0;30m",
        "RED" => "\033[0;31m",
        "GREEN" => "\033[0;32m",
        "YELLOW" => "\033[0;33m",
        "BLUE" => "\033[0;34m",
        "PURPLE" => "\033[0;35m",
        "CYAN" => "\033[0;36m",
        "WHITE" => "\033[0;37m"
    ];

    /**
     * @param string ...$expressions
     * @return void
     */
    public static function print(string ...$expressions): void
    {
        echo implode(" ", $expressions);
    }

    /**
     * @param string ...$expressions
     * @return void
     */
    public static function println(string ...$expressions): void
    {
        echo implode(" ", $expressions) . PHP_EOL;
    }

    /**
     * @param string $needle
     * @param string $command
     * @return bool
     */
    public static function match(string $needle, string $command): bool
    {
        [$p1, $p2] = explode(separator: ":", string: $command);

        return in_array($needle, [
            "{$p1[0]}:{$p2[0]}",
            "{$p1[0]}:{$p2}",
            "{$p1}:{$p2[0]}",
            "{$p1}:{$p2}"
        ]);
    }

    /**
     * @param string &$path
     * @return array
     */
    public static function splitPath(string &$path): array
    {
        $path = str_replace("/", "\\", $path);

        return [
            strtolower(dirname($path)),
            ucfirst(basename($path))
        ];
    }

    /**
     * @param string $path
     * @return array
     */
    public static function getNamespace(string $path): array
    {
        [$dirname, $basename] = self::splitPath($path);
        $baseFolder = str_replace("/", "\\", AppConfig::BASE_FOLDER);
        $namespace = str_ireplace("{$baseFolder}\\", "", $dirname);
        $namespace = Util::upperFirstNamespace($namespace);
        $classname = explode(".", $basename)[0];

        return [
            "namespace" => $namespace,
            "classname" => $classname
        ];
    }
}
