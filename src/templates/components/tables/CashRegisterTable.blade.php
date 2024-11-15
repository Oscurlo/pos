<?php use Oscurlo\ComponentRenderer\Component; ?>

<table {{ Component::get_attributes($props) }}>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Saldo inicial</th>
            <th>Saldo actual</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
</table>