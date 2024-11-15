<?php use Oscurlo\ComponentRenderer\Component; ?>

<table {{ Component::get_attributes($props) }}>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Direccion</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
</table>