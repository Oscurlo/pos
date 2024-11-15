<?php use Oscurlo\ComponentRenderer\Component; ?>

<form {{ Component::get_attributes($props, $exclude) }}>
    {{ $props->children }}
</form>