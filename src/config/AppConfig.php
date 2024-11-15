<?php declare(strict_types=1);

namespace Src\Config;

final class AppConfig
{
    const APP_NAME = "admins5";

    const IN_PRODUCTION = false;
    const LANGUAGE = "es";
    const COUNTRY = "CO";
    const TIMEZONE = "America/Bogota";
    const CHARSET = "UTF-8";
    const LOCALE_UNDERSCORE = self::LANGUAGE . "_" . self::COUNTRY;
    const LOCALE_HYPHEN = self::LANGUAGE . "-" . self::COUNTRY;

    const BASE_FOLDER = BASE_FOLDER;
    const BASE_SERVER = BASE_SERVER;
    const CURRENT_ROUTE = CURRENT_ROUTE;

    const COMPANY = [
        "NAME" => self::APP_NAME,
        "ADDRESS" => "",
        "PHONE" => "",
        "EMAIL" => "",
        "WEBSITE" => "",
        "TAX_ID" => "",
        "LEGAL_REPRESENTATIVE" => "",
        "LOGO_PATH" => "./assets/img/logo-sin-fondo.webp"
    ];
}

# JUST IN CASE
$documentRoot = $_SERVER["DOCUMENT_ROOT"] ?? "";
$requestUri = $_SERVER["REQUEST_URI"] ?? "";
$requestScheme = $_SERVER["REQUEST_SCHEME"] ?? "";
$httpHost = $_SERVER["HTTP_HOST"] ?? "";

[$baseFolder] = explode("/src/", str_replace("\\", "/", __DIR__));
define("BASE_FOLDER", $baseFolder);

$currentRoute = str_replace($baseFolder, "", "{$documentRoot}{$requestUri}");
define("CURRENT_ROUTE", $currentRoute);

// $baseServer = "{$requestScheme}://{$httpHost}{$currentRoute}";
$baseServer = "{$requestScheme}://{$httpHost}" . ($currentRoute !== "/" ? str_replace(
    $currentRoute,
    "",
    $requestUri
) : $requestUri);

$baseServer = rtrim($baseServer, "\\/");

define("BASE_SERVER", $baseServer);
