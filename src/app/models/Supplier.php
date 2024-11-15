<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class Supplier extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "suppliers";
    const COLUMNS = [
        "name" => "VARCHAR(255) NOT NULL",
        "contact" => "VARCHAR(100)",
        "phone" => "VARCHAR(15)",
        "email" => "VARCHAR(100)",
        "address" => "TEXT"
    ];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS);
    }
}