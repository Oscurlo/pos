<?php declare(strict_types=1);

namespace Src\App\Components;

use Oscurlo\ComponentRenderer\Component;
use Src\Core\{Database, Util};

final class SelectMaster
{
    private $props;
    public function build(object $props): string
    {
        $this->props = $props;
        $this->props->table ??= "";
        $this->props->columns ??= "*";
        $this->props->condition ??= "1=1";
        $this->props->encrypt ??= false;

        $this->props->{"default-selected"} ??= -1;
        $this->props->{"default-option"} ??= "Seleccione";

        $options = [];

        $db = new Database;

        if (!empty($this->props->table) && $db->tableExists($this->props->table)) {
            $data = $db->fetchAll("SELECT {$this->props->columns} FROM {$this->props->table} WHERE {$this->props->condition}");

            if (!empty($data)) {
                $this->load($data, $options);
            }
        }

        array_unshift($options, <<<HTML
        <option value="" disabled {$this->selected(-1)}>
            {$this->props->{"default-option"} }
        </option>
        HTML);

        $this->props->children = implode(PHP_EOL, $options);

        return Component::render(
            Component::template(
                filename: Util::getTemplateBlade(__FILE__),
                props: $this->props,
                vars: ["exclude" => ["table", "columns", "condition", "default-selected", "default-option"]]
            )
        );
    }

    private function selected(mixed $var): string
    {
        return $var == $this->props->{"default-selected"} ? "selected" : "";
    }

    private function load(array $data, array &$options)
    {
        $template = $this->props->children;

        foreach ($data as $i => $row) {
            $options[$i] = $template;
            foreach ($row as $key => $value) {
                $options[$i] = str_replace(
                    ["{{$key}}", "<option"],
                    [(string) $value, "<option {$this->selected($value)}"],
                    $options[$i]
                );
            }
        }
    }
}