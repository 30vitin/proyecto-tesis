<div class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-labelledby="modalProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProductLabel">Agregar Productos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="table" class="table table-striped table-bordered" style="width:100%">
                        <thead class="text-primary">
                            <tr  class="column-image" data-target="[0]">
                                <th></th>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary btn-add-product-table">Agregar selecionados</button>
            </div>
        </div>
    </div>
</div>