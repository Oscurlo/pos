<?php declare(strict_types=1);

namespace Src\Core;

final class Datatable extends Database
{
    private array $request;
    private bool $tablesExist = true;
    private string $table, $columns, $filter, $order, $limit, $condition;
    private array $columnsArray;
    private array $preparedParams;

    public function __construct()
    {
        parent::__construct();
        $this->request = $_REQUEST;
    }

    public function setRequest(array $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function setTables(array|string $tables): self
    {
        if (is_string($tables))
            $tables = [["db" => $tables]];

        $this->table = implode(" ", array_unique(array_map(
            function ($data): string {
                if (!self::tableExists($data["db"])) {
                    $this->tablesExist = false;
                }

                return trim($data["db"] . " " . ($data["as"] ?? ""));
            },
            $tables
        )));

        return $this;
    }

    public function setColumns(array $columns, ?string $use = null): self
    {
        $use ??= implode(", ", array_unique(array_map(
            fn($data): string => trim($data["db"] . " " . ($data["as"] ?? "")),
            $columns
        )));

        $this->columnsArray = $columns;
        $this->columns = $use;

        $this->applyFilter()->applyLimit()->applyOrder();

        return $this;
    }

    public function addCondition(string $condition): self
    {
        $this->condition = "AND ($condition)";
        return $this;
    }

    private function applyFilter(): self
    {
        $search = $this->request["search"]["value"] ?? null;

        $this->filter = implode(" OR ", array_unique(array_map(
            function ($data) use ($search): string {
                $db = $data["db"] ?? "";
                $key = ":" . str_replace("=", "", base64_encode((string) rand()));

                $this->preparedParams[$key] = "%{$search}%";
                return "{$db} LIKE {$key}";
            },
            $this->columnsArray
        )));

        return $this;
    }

    private function applyOrder(): self
    {
        $columnId = $this->request["order"][0]["column"] ?? null;
        $orderDirection = $this->request["order"][0]["dir"] ?? null;

        $this->order = "";

        if (!is_numeric($columnId) || !$orderDirection)
            return $this;

        $column = $this->columnsArray[$columnId]["db"];

        $this->order = "ORDER BY {$column} {$orderDirection}";

        return $this;
    }

    private function applyLimit(): self
    {
        $start = $this->request["start"] ?? null;
        $length = $this->request["length"] ?? null;

        $this->limit = "";

        if (!is_numeric($start) || !$length)
            return $this;

        $this->limit = "LIMIT {$start}, {$length}";

        return $this;
    }

    private function formatValue(array $result): array
    {
        $response = [];

        foreach ($result as $i => $data) foreach ($this->columnsArray as $key => $column) {
                $dbField = $column["db"];
                $as = $column["as"] ?? null;
                $formatter = $column["formatter"] ?? null;
                $fallback = $column["fallback"] ?? null;

                $db = explode(".", $dbField);
                $value = $as ? $data[$as] : $data[$db[1] ?? $db[0]] ?? null;
                $response[$i][] = is_callable($formatter) ? $formatter($value, $data, $i, $key) : ($value ?? $fallback);
            }

        return $response;
    }

    private function fetchRecords(): array
    {
        $buildExtra = fn(array $array): string => implode(
            " ",
            array_unique(array_filter($array, fn($val): bool => !empty ($val)))
        );

        $this->condition ??= "";

        $data = $this->fetchAll("SELECT {$this->columns} FROM {$this->table} WHERE {$this->filter} {$buildExtra([$this->condition, $this->order, $this->limit])}", $this->preparedParams);
        $totalRecords = $this->fetchColumn("SELECT COUNT(*) as total FROM {$this->table} WHERE 1 = 1 {$buildExtra([$this->condition])}");
        $filteredRecords = $this->fetchColumn("SELECT COUNT(*) as total FROM {$this->table} WHERE {$this->filter} {$buildExtra([$this->condition])}", $this->preparedParams);

        return [
            "data" => $this->formatValue($data),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "query" => "SELECT {$this->columns} FROM {$this->table} WHERE {$this->filter} {$buildExtra([$this->condition, $this->order, $this->limit])}"
        ];
    }

    public function getServerSideData(): array
    {
        return $this->tablesExist ? ["draw" => $this->request["draw"] ?? null, ...$this->fetchRecords()] : ["error" => "Not available"];
    }
}