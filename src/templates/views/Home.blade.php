<Layout::system title="Dashboard">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dashboard</h3>
                    <div class="card-tools">
                        <button class="btn btn-tool" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFormBuilder"
                            aria-controls="offcanvasFormBuilder" aria-label="offcanvas">
                            <i class="bi bi-gear"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="test_form" class="row">
                        <!------------------------------------------------------------------>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="test">
                                <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                                Template fields
                                <sup class="text-info"><i class="bi bi-info-lg"></i></sup>
                            </label>
                            <input type="text" id="test" class="form-control">
                        </div>
                        <!------------------------------------------------------------------>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="test2">
                                <sup class="text-danger"><i class="bi bi-asterisk"></i></sup>
                                Template fields
                                <sup class="text-info"><i class="bi bi-info-lg"></i></sup>
                            </label>
                            <input type="text" id="test2" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Header 1</th>
                                <th>Header 2</th>
                                <th>Header 3</th>
                                <th>Header 4</th>
                                <th>Header 5</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasFormBuilder"
        aria-labelledby="offcanvasFormBuilderLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasFormBuilderLabel">Offcanvas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            Content for the offcanvas goes here. You can place just about any Bootstrap component or custom elements
            here.
        </div>
    </div>
</Layout::system>