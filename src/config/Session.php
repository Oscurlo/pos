<?php declare(strict_types=1);

namespace Src\Config;

final class Session
{
    private static array $options = [
        "name" => "AppSession-" . AppConfig::APP_NAME,
        "cookie_lifetime" => 86400,
        "use_strict_mode" => true,
        "cookie_secure" => AppConfig::IN_PRODUCTION,
        "cookie_httponly" => true,
        "cookie_samesite" => "Lax",
    ];

    /**
     * @param array $options
     * @return void
     */
    public static function start(array $options = []): void
    {
        self::setOptions($options);

        if (session_status() !== PHP_SESSION_ACTIVE) {
            date_default_timezone_set(AppConfig::TIMEZONE);
            setlocale(LC_TIME, AppConfig::LOCALE_UNDERSCORE . "." . AppConfig::CHARSET, AppConfig::LOCALE_UNDERSCORE);
            session_start(self::$options);
        }
    }

    /**
     * @return void
     */
    public static function destroy(): void
    {
        if (ini_get("session.use_cookies")) {
            [
                "path" => $path,
                "domain" => $domain,
                "secure" => $secure,
                "httponly" => $httponly
            ] = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $path,
                $domain,
                $secure,
                $httponly
            );
        }

        session_destroy();
    }

    /**
     * @param array $options
     * @return void
     */
    private static function setOptions(array $options): void
    {
        self::$options = [...self::$options, ...$options];
    }

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return self::$options;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public static function set(string $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function get(string $name): mixed
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return $_SESSION;
    }
}