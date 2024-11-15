<?php declare(strict_types=1);

namespace Src\Scripts;

use Src\Abstract\Script;
use Src\Config\AppConfig;
use Src\Core\Util;

final class Component extends Script
{
    private static function getFiles(string $param)
    {
        $namespaceComponent = "Src\\App\\Components";

        $path = AppConfig::BASE_FOLDER . "\\{$namespaceComponent}\\{$param}";
        [$dirname, $basename] = self::splitPath($path);

        $fileComponent = "{$dirname}\\{$basename}.php";
        $fileTemplate = Util::getTemplateBlade($fileComponent);

        return [
            "component" => $fileComponent,
            "template" => $fileTemplate
        ];
    }

    public static function createComponent(string $param): void
    {
        foreach (self::getFiles($param) as $key => $file) {
            [$dirname] = self::splitPath($file);

            if (file_exists($file)) {
                self::println(self::COLOR["YELLOW"] . "This file already exists: {$file}" . self::COLOR["RESET"]);
                continue;
            }

            if (!file_exists($dirname)) {
                self::println(self::COLOR["GREEN"] . "Created directory {$dirname}" . self::COLOR["RESET"]);
                mkdir($dirname);
            }

            $content = self::template($key, $file);

            if (file_put_contents($file, $content) !== false) {
                self::println(self::COLOR["GREEN"] . "Created {$key}: {$file}" . self::COLOR["RESET"]);
            } else {
                self::println(self::COLOR["RED"] . "Failed to create {$key}: {$file}" . self::COLOR["RESET"]);
            }
        }
    }

    public static function removeComponent(string $param): void
    {
        foreach (self::getFiles($param) as $key => $file) {
            [$dirname] = self::splitPath($file);

            if (!file_exists($file)) {
                self::println(self::COLOR["YELLOW"] . "This file no exists: {$file}" . self::COLOR["RESET"]);
                continue;
            }

            if (unlink($file)) {
                self::println(self::COLOR["GREEN"] . "Deleted {$key}: {$file}" . self::COLOR["RESET"]);
            } else {
                self::println(self::COLOR["RED"] . "Failed to delete {$key}: {$file}" . self::COLOR["RESET"]);
            }

            $countFiles = count(glob("{$dirname}/*"));

            if (empty($countFiles)) {
                self::println(self::COLOR["GREEN"] . "Deleted directory {$dirname}" . self::COLOR["RESET"]);
                rmdir($dirname);
            }
        }
    }

    private static function template(string $key, string $file): string
    {
        extract(self::getNamespace($file));

        return match ($key) {
            "component" => <<<HTML
            <?php declare(strict_types=1);

            use Oscurlo\ComponentRenderer\Component;
            use Src\Core\Util;

            /**
             * @throws Exception
             */
            function {$classname}(object \$props): string
            {
                return Component::render(
                    Component::template(
                        filename: Util::getTemplateBlade(__FILE__),
                        props: \$props
                    )
                );
            }
            HTML,
            "template" => <<<HTML
            <div>
                ...
            </div>
            HTML,
            default => "..."
        };
    }
}