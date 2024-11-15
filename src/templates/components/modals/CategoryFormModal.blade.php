<?php use Src\App\Components\FormMaster; ?>

{{ static::register_component([FormMaster::class => "build"]) }}

<div class="modal fade" id="{{ $props->{"modal-id"} }}" aria-labelledby="{{ $props->{"modal-id"} }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <FormMaster::build class="modal-content" encrypt="{{ $props->{"form-encrypt"} }}"
            data-action="{{ $props->{"form-action"} }}" table="{{ $props->{"form-table"} }}"
            condition="{{ $props->{"form-condition"} }}">
            <div class="modal-header">
                <h2 class="modal-title">Agregar Categoria</h2>
            </div>
            <div class="modal-body row">
                <div class="col-12 mb-3">
                    <label for="category_name" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Nombre
                    </label>
                    <input name="data[name]" id="category_name" type="text" class="form-control" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="category_description" class="form-label">
                        Descripción
                    </label>
                    <textarea name="data[description]" id="category_description" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </FormMaster::build>
    </div>
</div>