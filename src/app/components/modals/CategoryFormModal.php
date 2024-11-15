<?php declare(strict_types=1);

use Oscurlo\ComponentRenderer\Component;
use Src\Core\Util;

/**
 * @throws Exception
 */
function CategoryFormModal(object $props): string
{
    $props->{"modal-id"} ??= "category_modal";

    $props->{"form-action"} ??= "categories/api/insert";
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