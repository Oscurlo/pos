<?php use Src\App\Components\{FormMaster, SelectMaster}; ?>
<?php use Src\App\Models\{Category, Supplier}; ?>

{{ static::register_component([
    SelectMaster::class => "build",
    FormMaster::class => "build"
]) }}

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
                <div class="col-12 mb-3">
                    <label for="customer_name" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Nombre
                    </label>
                    <input name="data[name]" id="customer_name" type="text" class="form-control" required>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="customer_phone" class="form-label">
                        Telefono
                    </label>
                    <input name="data[phone]" id="customer_phone" type="text" class="form-control">
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="customer_email" class="form-label">
                        Correo
                    </label>
                    <input name="data[email]" id="customer_email" type="text" class="form-control">
                </div>
                <div class="col-12 mb-3">
                    <label for="customer_address" class="form-label">
                        Dirección
                    </label>
                    <textarea name="data[address]" id="customer_address" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </FormMaster::build>
    </div>
</div>