<?php use Src\Config\{AppConfig}; ?>

{{ static::register_component([
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\tables" => "ProductTable",
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\modals" => "ProductFormModal",
]) }}

<Layout::system title="Productos">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Productos
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn tool" data-bs-toggle="modal" data-bs-target="#product_modal">
                            <i class="bi bi-plus-lg mx-1"></i>Agregar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <ProductTable />
                </div>
            </div>
        </div>
    </div>

    <ProductFormModal modal-id="product_modal" />
</Layout::system>