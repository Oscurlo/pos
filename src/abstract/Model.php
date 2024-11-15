<?php declare(strict_types=1);

namespace Src\Abstract;

use Exception;
use Src\Core\Datatable;
use Src\Core\ProcessData;
use Throwable;

abstract class Model extends ProcessData
{
    const PRIMARY_KEY = "id";
    const TABLE = "";
    const COLUMNS = [];
    const FOREIGN_KEYS = [];

    private string $__table;
    public bool $checkActiveRecords = true;

    public function __construct(string $table)
    {
        parent::__construct();
        $this->__table = $table;
    }

    /**
     * Insert
     * 
     * @param array $data
     * @return array
     */
    protected function __create(array $data): array
    {
        $this->isEncrypted = true;
        return $this->prepare($this->__table, $data)->executeInsert();
    }

    /**
     * get
     * 
     * @param string $columns
     * @param string $condition
     * @param array $prepare
     * @return array
     */
    protected function __read(string $columns = "*", string $condition = "1 = 1", array $prepare = []): array
    {
        return $this->fetch("SELECT {$columns} FROM `{$this->__table}` WHERE {$this->verifyActiveRecords($condition)}", $prepare);
    }

    /**
     * get all
     * 
     * @param string $columns
     * @param string $condition
     * @param array $prepare
     * @return array
     */
    protected function __readAll(string $columns = "*", string $condition = "1 = 1", array $prepare = []): array
    {
        return $this->fetchAll("SELECT {$columns} FROM `{$this->__table}` WHERE {$this->verifyActiveRecords($condition)}", $prepare);
    }

    /**
     * update
     * 
     * @param array $data
     * @param int $id
     * @return array
     */
    protected function __update(array $data, int $id): array
    {
        return self::prepare($this->__table, $data)->executeUpdate($id);
    }

    /**
     * delete
     * 
     * @param int $id
     * @return array
     */
    protected function __delete(int $id): array
    {
        $this->deleteRecords = true;
        return self::prepare($this->__table)->executeDelete($id);
    }

    /**
     * change state
     * 
     * @param int $id
     * @return array
     */
    protected function __disable(int $id): array
    {
        $this->deleteRecords = false;
        return self::prepare($this->__table)->executeDelete($id);
    }

    /**
     * check active records
     * 
     * @param string $condition
     * @return string
     */
    private function verifyActiveRecords(string $condition): string
    {
        if (!$this->checkActiveRecords || str_contains($condition, "registration_status")) {
            return $condition;
        }

        return "registration_status = 1 AND {$condition}";
    }

    /**
     * @param array $columns
     * @param null|string $use
     * @return array
     */
    public static function serverSide(array $columns, ?string $use = null): array
    {
        return (new Datatable)->setTables(static::TABLE)->setColumns($columns, $use)->getServerSideData();
    }

    public function insert(array $data)
    {
        extract($this->__create($data));

        return [
            "status" => $status,
            "lastId" => $lastId,
            "message" => "Registro creado correctamente."
        ];
    }

    public function update(array $data, int $id)
    {
        extract($this->__update($data, $id));

        return [
            "status" => $status,
            "rowCount" => $rowCount,
            "message" => "Actualización realizada correctamente."
        ];
    }
}