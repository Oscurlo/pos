<?php declare(strict_types=1);

namespace Src\App\Views;

use Exception;
use Oscurlo\ComponentRenderer\Component;
use Src\App\Layouts\Layout;
use Src\Core\Util;
use Src\Interfaces\IView;

final class Home implements IView
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
        echo "test";
    }
}