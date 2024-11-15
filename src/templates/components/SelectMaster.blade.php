<?php use Oscurlo\ComponentRenderer\Component; ?>

<select {{ Component::get_attributes($props, $exclude) }}>
    {{ $props->children }}
</select>