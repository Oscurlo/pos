<?php declare(strict_types=1);

use Oscurlo\ComponentRenderer\Component;
use Src\Core\Util;

/**
 * @throws Exception
 */
function SupplierFormModal(object $props): string
{
    $props->{"modal-id"} ??= "supplier_modal";

    $props->{"form-action"} ??= "suppliers/api/insert";
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