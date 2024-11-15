<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class CashRegister extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "cash_register";
    const COLUMNS = [
        "name" => "VARCHAR(100) NOT NULL",
        "opening_balance" => "DECIMAL(10, 2) NOT NULL",
        "current_balance" => "DECIMAL(10, 2) NOT NULL",
        "status" => "ENUM('OPEN', 'CLOSED') DEFAULT 'OPEN'"
    ];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS);
    }
}