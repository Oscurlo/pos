<?php declare(strict_types=1);

namespace Src\App\Components;

use DOMDocument;
use Oscurlo\ComponentRenderer\Component;
use Src\Config\{AppConfig, Security};
use Src\Core\{Database, Util};

final class FormMaster
{
    private $props;
    public function build(object $props): string
    {
        $this->props = $props;
        $this->props->table ??= "";
        $this->props->condition ??= "";
        $this->props->encrypt ??= false;

        Component::interface([
            "table" => "string",
            "condition" => "string",
            "encrypt" => "bool"
        ], $props);

        $form = Component::template(
            filename: Util::getTemplateBlade(__FILE__),
            props: $props,
            vars: ["exclude" => ["table", "condition", "encrypt"]]
        );

        $db = new Database;
        $dom = new DOMDocument(
            encoding: AppConfig::CHARSET
        );

        if ($dom->loadHTML(Component::html_base($form, AppConfig::CHARSET), LIBXML_NOERROR | LIBXML_NOWARNING)) {
            if (!empty($this->props->table) && $db->tableExists($this->props->table)) {
                $row = $db->fetch("SELECT * FROM `{$this->props->table}` WHERE {$this->props->condition}");

                if (!empty($row)) {
                    $form = Component::get_body($this->load($row, $dom));
                }
            }

            if ($this->props->encrypt == true) {
                $form = Component::get_body($this->encrypt($dom));
            }
        }



        return Component::render($form);
    }

    private function load(array $row, DOMDocument $dom): string
    {
        $tags = ["input", "textarea"];

        foreach ($tags as $tag) {
            $fields = $dom->getElementsByTagName($tag);

            foreach ($fields as $field) {
                foreach ($row as $key => $value) {
                    $name = $field->getAttribute("name");
                    $type = $field->getAttribute("type");

                    if ($type !== "file" && $name === "data[{$key}]") {
                        $safe_value = htmlspecialchars($value, ENT_QUOTES, AppConfig::CHARSET);

                        if ($tag === "input") {
                            $field->setAttribute("value", $safe_value);
                        } else if ($tag === "textarea") {
                            while ($field->firstChild) {
                                $field->removeChild($field->firstChild);
                            }

                            $field->appendChild($dom->createTextNode($safe_value));
                        }
                    }
                }
            }
        }

        return $dom->saveHTML();
    }

    protected function encrypt(DOMDocument $dom): string
    {
        $tags = ["input", "textarea", "select"];

        foreach ($tags as $tag) {
            $fields = $dom->getElementsByTagName($tag);

            foreach ($fields as $field) {
                $name = preg_replace_callback(
                    "/\[(.*?)\]/",
                    fn($matches) => "[" . Security::encrypt($matches[1]) . "]",
                    $field->getAttribute("name")
                );
                $safe_name = htmlspecialchars($name, ENT_QUOTES, AppConfig::CHARSET);

                $field->setAttribute("name", $safe_name);
            }
        }

        return $dom->saveHTML();
    }
}