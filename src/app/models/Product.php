<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class Product extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "products";
    const COLUMNS = [
        "code" => "VARCHAR(100) NOT NULL UNIQUE",
        "name" => "VARCHAR(255) NOT NULL",
        "description" => "TEXT",
        "purchase_price" => "DECIMAL(10, 2) NOT NULL",
        "sale_price" => "DECIMAL(10, 2) NOT NULL",
        "stock_current" => "INT NOT NULL DEFAULT 0",
        "stock_minimum" => "INT NOT NULL DEFAULT 0",
        "status" => "ENUM('ACTIVE', 'INACTIVE') DEFAULT 'ACTIVE'",
        "category_id" => "INT",
        "supplier_id" => "INT"
    ];
    const FOREIGN_KEYS = [
        "category_id" => [Category::TABLE, Category::PRIMARY_KEY],
        "supplier_id" => [Supplier::TABLE, Supplier::PRIMARY_KEY]
    ];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS, self::FOREIGN_KEYS);
    }
}