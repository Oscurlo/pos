<?php declare(strict_types=1);

use Oscurlo\ComponentRenderer\Component;
use Src\Config\AppConfig;
use Src\Config\Session;
use Src\Core\Util;

/**
 * @throws Exception
 */
function AppMain(object $props): string
{
    $props->title ??= AppConfig::COMPANY["NAME"];
    $props->breadcrumb ??= AppConfig::CURRENT_ROUTE;

    if (str_ends_with($props->breadcrumb, "/")) {
        $props->breadcrumb .= "Home";
    }

    $vars = [];
    $vars["split_breadcrumb"] = array_filter(explode("/", $props->breadcrumb), fn($val) => !empty ($val));
    $vars["split_breadcrumb"] = array_map(fn($val) => ucwords($val), $vars["split_breadcrumb"]);
    $vars["count_breadcrumb"] = count($vars["split_breadcrumb"]);

    $vars["csrf"] = Session::get("csrf");

    return Component::render(
        Component::template(
            Util::getTemplateBlade(__FILE__),
            $vars,
            $props
        )
    );
}