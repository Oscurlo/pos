<?php use Oscurlo\ComponentRenderer\Component; ?>

<table {{ Component::get_attributes($props) }}>
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio de compra</th>
            <th>Precio de venta</th>
            <th>Stock actual</th>
            <th>Stock mínimo</th>
            <th>Categoría</th>
            <th>Proveedor</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
</table>