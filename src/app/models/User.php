<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class User extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "users";
    const COLUMNS = [
        "name" => "VARCHAR(255) NOT NULL",
        "email" => "VARCHAR(100)",
        "password" => "VARCHAR(255) NOT NULL",
        "rol" => "ENUM('ADMINISTRATOR', 'SALESMAN') NOT NULL"
    ];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS);
    }
}