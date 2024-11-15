<?php use Src\Config\{AppConfig}; ?>

{{ static::register_component([
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\tables" => "CustomerTable",
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\modals" => "CustomerFormModal",
]) }}

<Layout::system title="Clientes">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Clientes
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn tool" data-bs-toggle="modal" data-bs-target="#customer_modal">
                            <i class="bi bi-plus-lg mx-1"></i>Agregar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <CustomerTable />
                </div>
            </div>
        </div>
    </div>

    <CustomerFormModal modal-id="customer_modal" />
</Layout::system>