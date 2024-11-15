<?php declare(strict_types=1);

namespace Src\Core;

use Exception;
use PDO;
use PDOException;
use Src\Config\{AppConfig, Env};

class Database
{
    public ?PDO $conn;
    private bool $createDatabase = !AppConfig::IN_PRODUCTION;
    private array $defaultColumns = [
        "id" => "INT AUTO_INCREMENT PRIMARY KEY",
        "registration_status" => "BOOLEAN DEFAULT TRUE",
        "created_at" => "DATETIME DEFAULT CURRENT_TIMESTAMP",
        "updated_at" => "DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
    ];

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->connect(
            Env::get("DB_DSN"),
            Env::get("DB_USERNAME"),
            Env::get("DB_PASSWORD")
        );
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * @param string $dsn
     * @param null|string $username
     * @param null|string $password
     * @param null|array $options
     * @return PDO
     * @throws Exception
     */
    public function connect(string $dsn, ?string $username = null, ?string $password = null, ?array $options = null): PDO
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                $dsn,
                $username,
                $password,
                $options
            );
        } catch (PDOException $e) {
            if ($this->createDatabase && $e->getCode() === 1049) {
                $this->createDatabase = false;

                if ($this->createDatabase($dsn, $username, $password)) {
                    return $this->connect($dsn, $username, $password, $options);
                }
            }

            throw $e;
        }

        return $this->conn;
    }

    /**
     * @return void
     */
    public function disconnect(): void
    {
        $this->conn = null;
    }


    /**
     * @param string $query
     * @param null|array $params
     * @return array
     * @throws Exception
     */
    public function fetch(string $query, ?array $params = null): array
    {
        return $this->fetchData($query, $params, __FUNCTION__);
    }


    /**
     * @param string $query
     * @param null|array $params
     * @return array
     * @throws Exception
     */
    public function fetchAll(string $query, ?array $params = null): array
    {
        return $this->fetchData($query, $params, __FUNCTION__);
    }

    /**
     * @param string $query
     * @param null|array $params
     * @return mixed
     * @throws Exception
     */
    public function fetchColumn(string $query, ?array $params = null, int $column = 0): mixed
    {
        return $this->fetchData($query, $params, __FUNCTION__, $column);
    }

    /**
     * @param string $query
     * @param null|array $params
     * @param string $method
     * @return mixed
     * @throws Exception
     */
    private function fetchData(string $query, ?array $params = null, string $method = "", int $param = PDO::FETCH_ASSOC): mixed
    {
        try {
            $stmt = $this->conn->prepare(query: $query, options: [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Failed execeute query: {$query} -> {$e->getMessage()}");
        }

        return $stmt->{$method}($param) ?: [];
    }

    /**
     * @param string $dsn
     * @param null|string $username
     * @param null|string $password
     * @return bool
     */
    private function createDatabase(string $dsn, ?string $username = null, ?string $password = null): bool
    {
        try {
            $dsn = preg_replace_callback(["/dbname\=(.*?)\;/", "/dbname\=(.*?)/"], function ($matches) use (&$database) {
                [$all, $database] = $matches;
                return "";
            }, strtolower(str_replace(" ", "", $dsn)));

            $connTemp = new PDO($dsn, $username, $password);
            $executed = $connTemp->prepare("CREATE DATABASE IF NOT EXISTS {$database}")->execute();
            $connTemp = null;
            return $executed;
        } catch (PDOException $e) {
            throw new Exception("Failed to create database: {$e->getMessage()}");
        }
    }

    /**
     * @param string $table
     * @return bool
     */
    public function tableExists(string $table): bool
    {
        return !empty(current($this->fetch("SHOW TABLES LIKE '{$table}'")));
    }

    /**
     * @param string $table
     * @param array $columns
     * @return bool
     */
    protected function createTable(string $table, array $columns = []): bool
    {
        if ($this->tableExists($table)) {
            return true;
        }

        $columns = [...$this->defaultColumns, ...$columns];
        $columnsSql = implode(", ", array_map(fn($name, $type) => "`{$name}` {$type}", array_keys($columns), $columns));

        $query = "CREATE TABLE `{$table}` ({$columnsSql})";

        try {
            return $this->conn->exec($query) !== false;
        } catch (PDOException $e) {
            throw new Exception("Failed to create table {$table}: {$e->getMessage()}");
        }
    }

    /**
     * @param string $table
     * @param string $column
     * @return bool
     */
    public function columnExists(string $table, string $column): bool
    {
        return !empty(current($this->fetch("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'")));
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $type
     * @return bool
     */
    protected function createColumn(string $table, string $column, string $type): bool
    {
        if ($this->columnExists($table, $column)) {
            return true;
        }

        // $query = "ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$type} AFTER `registration_status`";
        $query = "ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$type}";

        try {
            return $this->conn->exec($query) !== false;
        } catch (PDOException $e) {
            throw new Exception("Failed to add column {$column} in table {$table}: {$e->getMessage()}");
        }
    }

    /**
     * @param array $from
     * @param array $to
     */
    protected function addForeignKey(array $from, array $to)
    {
        [$t1, $c1] = $from;
        [$t2, $c2] = $to;

        if (!$this->tableExists($t2)) {
            $this->createTable($t2);
        }

        $query = "ALTER TABLE `{$t1}` ADD FOREIGN KEY ({$c1}) REFERENCES {$t2}({$c2})";

        try {
            return $this->conn->exec($query) !== false;
        } catch (PDOException $e) {
            throw new Exception("Failed to add foreign key: {$e->getMessage()}");
        }
    }
}