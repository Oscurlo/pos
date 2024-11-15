<?php

declare(strict_types=1);

namespace Src\Core;

use Exception;
use PDO;
use Src\Config\{AppConfig, Security};

class ProcessData extends Database
{
    private bool $isPrepared = false;
    private array $fields = ["keys" => [], "values" => []];
    private array $columns = [];
    private array $prepareData = [];
    private array $pendingFiles = [];
    private array $columnTypes = [];
    private ?string $query = null;
    private ?string $table = null;
    private ?array $data = null;
    private ?array $files = null;
    private ?array $foreignKeys = null;
    private string $folder = AppConfig::BASE_FOLDER . "/assets/uploads/@table/@id";
    protected bool $automaticCreation = !AppConfig::IN_PRODUCTION;
    protected bool $deleteRecords = false;

    protected bool $isEncrypted = false;

    /**
     * @param string $table Nombre de la tabla
     * @param array $data Datos enviados desde el formulario solo acepta un arreglo con los datos y archivos ["data"=> ?, "files"=> ?]
     * @return static
     */
    public function prepare(string $table, array $data = []): static
    {
        $this->isPrepared = true;
        $this->prepareData = [];
        $this->table = $table;
        $this->data = $data["data"] ?? null;
        $this->files = $data["files"] ?? null;

        return $this;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function executeInsert(): array
    {
        return $this->formatQuery("insert")->executeQuery();
    }

    /**
     * @param int $id
     * @param null|string $condition
     * @return array
     * @throws Exception
     */
    public function executeUpdate(int $id, ?string $condition = null): array
    {
        $condition = "id = {$id}" . (!is_null($condition) ? " AND {$condition}" : "");
        return $this->formatQuery("update", $condition)->executeQuery($id);
    }

    /**
     * @param int $id
     * @param null|string $condition
     * @return array
     * @throws Exception
     */
    public function executeDelete(int $id, ?string $condition = null): array
    {
        $this->isPrepared = true;
        $this->prepareData = [];
        $this->data = [
            "registration_status" => false
        ];
        $this->files = null;

        $condition = "id = {$id}" . (!is_null($condition) ? " AND {$condition}" : "");
        return $this->formatQuery($this->deleteRecords ? "delete" : "update", $condition)->executeQuery($id);
    }

    /**
     * @param string $type insert, update or delete
     * @param null|string $condition Solo aplica para actualizar
     * @return static
     * @throws Exception
     */
    private function formatQuery(string $type, ?string $condition = null): static
    {
        if (!$this->isPrepared) {
            throw new Exception("The consultation has not been prepared.");
        }

        $this->processData()->processFiles();

        $implode = fn(array $array): string => implode(", ", $array);
        $map = fn(?callable $callback, array $array, array ...$arrays): array => array_map($callback, $array, $arrays);

        $this->query = match (strtoupper(string: $type)) {
            "INSERT" => "INSERT INTO `{$this->table}` ({$implode($this->fields["keys"])}) VALUES ({$implode($this->fields["values"])})",
            "UPDATE" => "UPDATE `{$this->table}` SET {$implode($map(fn($key, $value): string => "{$key} = {$value}", $this->fields["keys"], $this->fields["values"]))}",
            "DELETE" => "DELETE FROM `{$this->table}`"
        };

        if ($condition) {
            $this->query .= " WHERE {$condition}";
        }

        return $this;
    }

    /**
     * @return static
     * @throws Exception
     */
    private function processData(): static
    {
        if (!empty($this->data)) {
            foreach ($this->data as $name => $value) {
                if ($this->isEncrypted)
                    Security::decrypt($name);

                $this->setColumn($name, $value);
            }
        }

        return $this;
    }

    /**
     * @return void
     */
    private function processFiles(): void
    {
        if (!empty($this->files)) {
            foreach ($this->files as $name => $file) {
                $value = [];

                if (is_array($file['name'])) {
                    foreach ($file['name'] as $i => $_) {
                        $destinationPath = "{$this->folder}/{$file['name'][$i]}";
                        $this->pendingFiles[] = ["from" => $file['tmp_name'][$i], "to" => $destinationPath];

                        $value[] = $destinationPath;
                    }
                } else {
                    $destinationPath = "{$this->folder}/{$file['name']}";
                    $this->pendingFiles[] = ["from" => $file['tmp_name'], "to" => $destinationPath];

                    $value[] = $destinationPath;
                }

                if (!empty($value)) {
                    $this->setColumn($name, $value);
                }
            }
        }
    }


    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    private function setColumn(string $name, mixed $value): void
    {
        $keyEnc = str_replace("=", "", base64_encode($name));
        $key = ":{$keyEnc}";

        $this->fields["keys"][] = "`{$name}`";
        $this->fields["values"][] = $key;

        $this->columns[$name] = $this->columnTypes[$name] ?? "TEXT";

        $this->prepareData[$key] = match (gettype($value)) {
            "array" => json_encode($value, JSON_UNESCAPED_UNICODE),
            default => (string) $value
        };
    }

    /**
     * @param null|int $id
     * @return array
     * @throws Exception
     */
    private function executeQuery(?int $id = null): array
    {
        if ($this->automaticCreation) {
            $this->createTableAndColumns();
        }

        $stmt = $this->conn->prepare($this->query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        $status = $stmt->execute($this->prepareData);
        $lastId = (int) $this->conn->lastInsertId();

        $id ??= $lastId;

        if ($status) {
            $this->uploadPendingFiles((int) $id);
        }

        return [
            "status" => (bool) $status,
            "query" => (string) $this->query,
            "prepare" => (array) $this->prepareData,
            "lastId" => (int) $id,
            "rowCount" => (int) $stmt->rowCount()
        ];
    }

    /**
     * @return void
     * @throws Exception
     */
    private function createTableAndColumns(): void
    {
        if (!$this->tableExists($this->table)) {
            $this->createTable(
                $this->table,
                $this->columns
            );

            if ($this->foreignKeys) {
                foreach ($this->foreignKeys as $column => $references) {
                    if (isset($this->columns[$column])) {
                        $this->addForeignKey([$this->table, $column], $references);
                    }
                }
            }
        } else {
            foreach ($this->fields["keys"] as $column) {
                if (!$this->columnExists($this->table, $column)) {
                    $column = str_replace("`", "", $column);
                    $this->createColumn(
                        $this->table,
                        $column,
                        $this->columns[$column]
                    );

                    if (isset($this->foreignKeys[$column])) {
                        $references = $this->foreignKeys[$column];
                        $this->addForeignKey([$this->table, $column], $this->foreignKeys[$column]);
                    }
                }
            }
        }
    }

    /**
     * @param array $columns [name => type]
     * @param null|array $foreignKeys [column => [table, id]]
     * @return void
     */
    public function interfaceColumnTypes(array $columns, ?array $foreignKeys = null): void
    {
        $this->columnTypes = $columns;
        $this->foreignKeys = $foreignKeys;
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    private function uploadPendingFiles(int $id): void
    {
        $keys = [
            "@id" => $id,
            "@table" => $this->table,
            "@date" => date("Y-m-d"),
            "@datetime" => date("Y-m-d H:i:s"),
        ];

        $replace = fn(string $subject) => str_replace(
            array_keys($keys),
            array_values($keys),
            $subject
        );

        foreach ($this->pendingFiles as $pending) {
            extract($pending);
            $to = $replace($to);
            $dirname = dirname($to);

            if (!file_exists($dirname)) {
                mkdir($dirname, 0777, true);
            }

            if (!move_uploaded_file($from, $to)) {
                throw new Exception("Error moving file {$from} to {$to}");
            }
        }
    }
}
