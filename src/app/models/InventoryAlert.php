<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class InventoryAlert extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "inventory_alerts";
    const COLUMNS = [
        "message" => "VARCHAR(255) NOT NULL",
        "status" => "ENUM('PENDING', 'ATTENDED') DEFAULT 'PENDING'",
        "product_id" => "INT"
    ];
    const FOREIGN_KEYS = ["product_id" => [Product::TABLE, Product::PRIMARY_KEY]];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS, self::FOREIGN_KEYS);
    }
}