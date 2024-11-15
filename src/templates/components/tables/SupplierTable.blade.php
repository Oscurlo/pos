<?php use Oscurlo\ComponentRenderer\Component; ?>

<table {{ Component::get_attributes($props) }}>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Contacto</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Dirección</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
</table>