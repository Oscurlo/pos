<?php declare(strict_types=1);

use Oscurlo\ComponentRenderer\Component;

function Image(object $props): string
{
    $props->loading ??= "lazy";
    $props->alt ??= "...";
    $filename = realpath($props->src);

    if ($filename !== false) if (file_exists($filename)) {
        [$props->width, $props->height] = getimagesize($filename);
    }

    $attr = Component::get_attributes($props);

    return <<<HTML
    <img {$attr}>
    HTML;
}