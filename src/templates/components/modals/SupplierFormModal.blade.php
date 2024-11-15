<?php use Src\App\Components\FormMaster; ?>

{{ static::register_component([FormMaster::class => "build"]) }}

<div class="modal fade" id="{{ $props->{"modal-id"} }}" aria-labelledby="{{ $props->{"modal-id"} }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <FormMaster::build class="modal-content" encrypt="{{ $props->{"form-encrypt"} }}"
            data-action="{{ $props->{"form-action"} }}" table="{{ $props->{"form-table"} }}"
            condition="{{ $props->{"form-condition"} }}">
            <div class="modal-header">
                <h2 class="modal-title">Agregar Producto</h2>
            </div>
            <div class="modal-body row">
                <div class="col-12 col-lg-6 mb-3">
                    <label for="supplier_name" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Nombre
                    </label>
                    <input name="data[name]" id="supplier_name" type="text" class="form-control" required>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="supplier_contact" class="form-label">
                        Contacto
                    </label>
                    <input name="data[contact]" id="supplier_contact" type="text" class="form-control">
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="supplier_phone" class="form-label">
                        Teléfono
                    </label>
                    <input name="data[phone]" id="supplier_phone" type="number" class="form-control">
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="supplier_email" class="form-label">
                        Correo electrónico
                    </label>
                    <input name="data[email]" id="supplier_email" type="email" class="form-control">
                </div>
                <div class="col-12 mb-3">
                    <label for="supplier_address" class="form-label">
                        Dirección
                    </label>
                    <textarea name="data[address]" id="supplier_address" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </FormMaster::build>
    </div>
</div>