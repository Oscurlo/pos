<?php use Src\App\Components\FormMaster; ?>

{{ static::register_component([FormMaster::class => "build"]) }}

<div class="modal fade" id="{{ $props->{"modal-id"} }}" aria-labelledby="{{ $props->{"modal-id"} }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <FormMaster::build class="modal-content" encrypt="{{ $props->{"form-encrypt"} }}"
            data-action="{{ $props->{"form-action"} }}" table="{{ $props->{"form-table"} }}"
            condition="{{ $props->{"form-condition"} }}">
            <div class="modal-header">
                <h2 class="modal-title">Agregar Caja</h2>
            </div>
            <div class="modal-body row">
                <div class="col-12 mb-3">
                    <label for="cashregister_name" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Nombre
                    </label>
                    <input name="data[name]" id="cashregister_name" type="text" class="form-control" required>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="cashregister_opening_balance" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Saldo inicial
                    </label>
                    <input name="data[opening_balance]" id="cashregister_opening_balance" type="text"
                        class="form-control" required>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="cashregister_current_balance" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Saldo actual
                    </label>
                    <input name="data[current_balance]" id="cashregister_current_balance" type="text"
                        class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </FormMaster::build>
    </div>
</div>