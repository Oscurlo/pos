<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class Purchase extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "purchases";
    const COLUMNS = [
        "total" => "DECIMAL(10, 2) NOT NULL",
        "supplier_id" => "INT"
    ];
    const FOREIGN_KEYS = ["supplier_id" => [Supplier::TABLE, Supplier::PRIMARY_KEY]];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS, self::FOREIGN_KEYS);
    }
}