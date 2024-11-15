<?php declare(strict_types=1);

use Oscurlo\ComponentRenderer\Component;
use Src\Core\Util;

/**
 * @throws Exception
 */
function CashRegisterTable(object $props): string
{
    $props->class ??= "table";
    $props->{"data-action"} ??= "cash_registers/api/serverside";

    return Component::render(
        Component::template(
            filename: Util::getTemplateBlade(__FILE__),
            props: $props
        )
    );
}