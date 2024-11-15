<?php use Src\Config\{AppConfig}; ?>

{{ static::register_component([
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\tables" => "SupplierTable",
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\modals" => "SupplierFormModal",
]) }}

<Layout::system title="Suppliers">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Proveedores
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn tool" data-bs-toggle="modal" data-bs-target="#supplier_modal">
                            <i class="bi bi-plus-lg mx-1"></i>Agregar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <SupplierTable />
                </div>
            </div>
        </div>
    </div>

    <SupplierFormModal modal-id="supplier_modal" />
</Layout::system>