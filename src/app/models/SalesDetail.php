<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class SalesDetail extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "sales_details";
    const COLUMNS = [
        "amount" => "INT NOT NULL",
        "sale_price" => "DECIMAL(10, 2) NOT NULL",
        "subtotal" => "DECIMAL(10, 2) NOT NULL",
        "sale_id" => "INT",
        "product_id" => "INT"
    ];
    const FOREIGN_KEYS = [
        "sale_id" => [Sale::TABLE, Sale::PRIMARY_KEY],
        "product_id" => [Product::TABLE, Product::PRIMARY_KEY]
    ];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS);
    }
}