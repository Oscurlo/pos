<?php declare(strict_types=1);

namespace Src\Scripts;

use Src\Abstract\Script;
use Src\Config\AppConfig;
use Src\Core\Util;

final class View extends Script
{
    private static function getFiles(string $param): array
    {
        $namespaceView = "Src\\App\\Views";

        $path = AppConfig::BASE_FOLDER . "\\{$namespaceView}\\{$param}";
        [$dirname, $basename] = self::splitPath($path);

        $fileView = "{$dirname}\\{$basename}.php";
        $fileTemplate = Util::getTemplateBlade($fileView);

        return [
            "view" => $fileView,
            "template" => $fileTemplate
        ];
    }

    public static function createView(string $param): void
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

    public static function removeView(string $param): void
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

    public static function template(string $key, string $file): string
    {
        extract(self::getNamespace($file));

        return match ($key) {
            "view" => <<<HTML
            <?php declare(strict_types=1);

            namespace {$namespace};

            use Exception;
            use Oscurlo\ComponentRenderer\Component;
            use Src\App\Layouts\Layout;
            use Src\Config\AppConfig;
            use Src\Core\Util;
            use Src\Interfaces\IView;
            use Throwable;

            final class {$classname} implements IView
            {
                /**
                 * @param object \$props
                 * @return void
                 * @throws Exception
                 */
                public static function view(object \$props): void
                {
                    Util::print(
                        Component::render(
                            Component::template(
                                filename: Util::getTemplateBlade(__FILE__),
                                props: \$props
                            ),
                            [Layout::class => "system"]
                        )
                    );
                }

                /**
                 * @param object \$props
                 * @return void
                 */
                public static function api(object \$props): void
                {
                    try {
                        \$response = match (\$props->action ?? null) {
                            "test" => [["request" => \$_REQUEST], false],
                            default => [["error" => "Acción no válida."], false, 405]
                        };
                    } catch (Throwable \$th) {
                        \$response = [["error" => AppConfig::IN_PRODUCTION ? "Hubo un problema en el servidor. Inténtalo de nuevo." : \$th->getMessage()], false, 500];
                    } finally {
                        Util::print(
                            Util::apiResponse(
                                ...\$response
                            )
                        );
                    }
                }
            }
            HTML,
            "template" => <<<XML
            <Layout::system title="{$classname}">
                ...
            </Layout::system>
            XML,
            default => "..."
        };
    }
}