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
                <div class="col-12 col-lg-6 mb-3">
                    <label for="product_code" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Código
                    </label>
                    <input name="data[code]" id="product_code" type="text" class="form-control" required>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="product_name" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Nombre
                    </label>
                    <input name="data[name]" id="product_name" type="text" class="form-control" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="product_description" class="form-label">
                        Descripción
                    </label>
                    <textarea name="data[description]" id="product_description" class="form-control"></textarea>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="product_purchase_price" class="form-label">
                        Precio de compra
                    </label>
                    <input name="data[purchase_price]" id="product_purchase_price" type="number" class="form-control"
                        min="0">
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="product_sale_price" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Precio de venta
                    </label>
                    <input name="data[sale_price]" id="product_sale_price" type="number" class="form-control" min="0"
                        required>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="product_stock_current" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Stock actual
                    </label>
                    <input name="data[stock_current]" id="product_stock_current" type="number" class="form-control"
                        min="0" required>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="product_stock_minimum" class="form-label">
                        Stock mínimo
                    </label>
                    <input name="data[stock_minimum]" id="product_stock_minimum" type="number" class="form-control"
                        min="0">
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="product_category_id" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Categoría
                    </label>
                    <SelectMaster::build table="{{ Category::TABLE }}" name="data[category_id]" id="product_category_id"
                        class="form-control" required="{{ true }}">
                        <option value="{id}">{name}</option>
                    </SelectMaster::build>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <label for="product_supplier_id" class="form-label">
                        <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                        Proveedor
                    </label>
                    <SelectMaster::build table="{{ Supplier::TABLE }}" name="data[supplier_id]" id="product_supplier_id"
                        class="form-control" required="{{ true }}">
                        <option value="{id}">{name}</option>
                    </SelectMaster::build>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </FormMaster::build>
    </div>
</div>