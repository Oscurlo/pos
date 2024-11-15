<?php declare(strict_types=1);

use Oscurlo\ComponentRenderer\Component;
use Src\Core\Util;

/**
 * @throws Exception
 */
function CashRegisterFormModal(object $props): string
{
    $props->{"modal-id"} ??= "cash_register_modal";

    $props->{"form-action"} ??= "cash_registers/api/insert";
    $props->{"form-encrypt"} ??= true;
    $props->{"form-table"} ??= "";
    $props->{"form-condition"} ??= "";

    return Component::render(
        Component::template(
            filename: Util::getTemplateBlade(__FILE__),
            props: $props
        )
    );
}