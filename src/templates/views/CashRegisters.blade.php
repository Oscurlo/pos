<?php use Src\Config\{AppConfig}; ?>

{{ static::register_component([
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\tables" => "CashRegisterTable",
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\modals" => "CashRegisterFormModal",
]) }}

<Layout::system title="Cajas registradoras">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Cajas registradoras
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn tool" data-bs-toggle="modal" data-bs-target="#cashregister_modal">
                            <i class="bi bi-plus-lg mx-1"></i>Agregar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <CashRegisterTable />
                </div>
            </div>
        </div>
    </div>

    <CashRegisterFormModal modal-id="cashregister_modal" />
</Layout::system>