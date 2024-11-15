<?php use Src\Config\{AppConfig}; ?>

{{ static::register_component([
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\tables" => "CategoryTable",
    AppConfig::BASE_FOLDER . "\\src\\app\\components\\modals" => "CategoryFormModal",
]) }}

<Layout::system title="Categorias">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Categorias
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn tool" data-bs-toggle="modal" data-bs-target="#category_modal">
                            <i class="bi bi-plus-lg mx-1"></i>Agregar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <CategoryTable />
                </div>
            </div>
        </div>
    </div>

    <CategoryFormModal modal-id="category_modal" />
</Layout::system>