<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class Sale extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "sales";
    const COLUMNS = [
        "client_id" => "INT",
        "total" => "DECIMAL(10, 2) NOT NULL",
        "payment_method" => "ENUM('CASH', 'CARD', 'TRANSFER') NOT NULL"
    ];
    const FOREIGN_KEYS = ["client_id" => [Client::TABLE, Client::PRIMARY_KEY]];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS);
    }
}