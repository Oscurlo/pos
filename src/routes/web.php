<?php declare(strict_types=1);

use Src\App\Views\{Categories, Products, Suppliers, CashRegisters, Customers};
use Src\Config\AppConfig;
use Src\Core\{Route, Util};

$extension = explode(".", AppConfig::CURRENT_ROUTE)[1] ?? null;

if (!$extension && $_SERVER["REQUEST_METHOD"] === "GET") {
    Route::get(...Util::getRouteView());
}

Route::get("/products/api/{action}", [Products::class, "api"]);
Route::post("/products/api/{action}", [Products::class, "api"]);

Route::get("/categories/api/{action}", [Categories::class, "api"]);
Route::post("/categories/api/{action}", [Categories::class, "api"]);

Route::get("/suppliers/api/{action}", [Suppliers::class, "api"]);
Route::post("/suppliers/api/{action}", [Suppliers::class, "api"]);

Route::get("/cash_registers/api/{action}", [CashRegisters::class, "api"]);
Route::post("/cash_registers/api/{action}", [CashRegisters::class, "api"]);

Route::get("/customers/api/{action}", [Customers::class, "api"]);
Route::post("/customers/api/{action}", [Customers::class, "api"]);