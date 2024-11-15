<?php declare(strict_types=1);

namespace Src\App\Views;

use Exception;
use Oscurlo\ComponentRenderer\Component;
use Src\App\Layouts\Layout;
use Src\App\Models\Category;
use Src\Config\AppConfig;
use Src\Core\Util;
use Src\Interfaces\IView;
use Throwable;

final class Categories implements IView
{
    /**
     * @param object $props
     * @return void
     * @throws Exception
     */
    public static function view(object $props): void
    {
        Util::print(
            Component::render(
                Component::template(
                    filename: Util::getTemplateBlade(__FILE__),
                    props: $props
                ),
                [Layout::class => "system"]
            )
        );
    }

    /**
     * @param object $props
     * @return void
     */
    public static function api(object $props): void
    {
        $category = new Category;

        try {
            $response = match ($props->action ?? null) {
                "insert" => [fn() => $category->insert($props->query_params), true, 201],
                "update" => [fn() => $category->update($props->query_params, $props->id), true, 200],
                "serverside" => [
                    fn() => $category->serverSide([
                        ["db" => "name"],
                        ["db" => "description"],
                        ["db" => "id"],
                        ["db" => "id"]
                    ]),
                    false
                ],
                default => [["error" => "Acción no válida."], false, 405]
            };
        } catch (Throwable $th) {
            $response = [["error" => AppConfig::IN_PRODUCTION ? "Hubo un problema en el servidor. Inténtalo de nuevo." : $th->getMessage()], false, 500];
        } finally {
            Util::print(
                Util::apiResponse(
                    ...$response
                )
            );
        }
    }
}