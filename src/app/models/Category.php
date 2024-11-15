<?php declare(strict_types=1);

namespace Src\App\Models;

use Src\Abstract\Model;

final class Category extends Model
{
    const PRIMARY_KEY = "id";
    const TABLE = "categorys";
    const COLUMNS = [
        "name" => "VARCHAR(255) NOT NULL",
        "description" => "TEXT"
    ];

    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->interfaceColumnTypes(self::COLUMNS);
    }
}