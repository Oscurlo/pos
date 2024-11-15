<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class PurchaseDetails extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "purchase_details";
    const COLUMNS = [
        "amount" => "INT NOT NULL",
        "purchase_price" => "DECIMAL(10, 2) NOT NULL",
        "subtotal" => "DECIMAL(10, 2) NOT NULL",
        "purchase_id" => "INT",
        "product_id" => "INT"
    ];
    const FOREIGN_KEYS = [
        "purchase_id" => [Purchase::TABLE, Purchase::PRIMARY_KEY],
        "product_id" => [Product::TABLE, Product::PRIMARY_KEY]
    ];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS, self::FOREIGN_KEYS);
    }
}