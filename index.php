<?php

declare(strict_types=1);

use Src\Config\{Env, Security, Session};
use Src\Core\Router;

include_once "./vendor/autoload.php";

foreach (["REQUEST_URI", "REQUEST_SCHEME", "HTTP_HOST"] as $needle) {
    if (!in_array($needle, array_keys($_SERVER))) {
        exit;
    }
}

try {
    Security::captureErrors();
    Session::start();
    Env::load();
} catch (Throwable $th) {
    echo $th->getMessage();
} finally {
    Router::provider();
}