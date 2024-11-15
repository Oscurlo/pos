<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class POS extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "pos";
    const COLUMNS = [
        "sale_id" => "INT",
        "box_id" => "INT",
        "seller_id" => "INT"
    ];
    const FOREIGN_KEYS = [
        "sale_id" => [Sale::TABLE, Sale::PRIMARY_KEY],
        "box_id" => [CashRegister::TABLE, CashRegister::PRIMARY_KEY],
        "seller_id" => [User::TABLE, User::PRIMARY_KEY]
    ];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS, self::FOREIGN_KEYS);
    }
}