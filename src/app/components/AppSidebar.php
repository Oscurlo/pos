<?php declare(strict_types=1);

use Oscurlo\ComponentRenderer\Component;
use Src\Core\Util;

/**
 * @throws Exception
 */
function AppSidebar(object $props): string
{
    return Component::render(
        Component::template(
            filename: Util::getTemplateBlade(__FILE__),
            props: $props
        )
    );
}