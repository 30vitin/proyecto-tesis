<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';

$sales = "active";
$pedidos = "active-sublink";
if (isset($VAR_SESSION->permission) && !in_array("PER0010", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Crear Pedido | Cafeteria
    </title>
    <?php include "styles.php"; ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

</head>

<body class="">

<?php include 'loader.php'; ?>

<div class="wrapper ">
    <?php include "sidebar.php"; ?>
    <div class="main-panel">
        <!-- Navbar -->

        <?php
        $breadcrumbData = array(
            array("name"=>"Lista de Pedidos","link"=>"./?view=orders","current"=>false),
            array("name"=>"Crear Pedido","current"=>true),
        );

        $breadcrumb = json_decode(json_encode($breadcrumbData), FALSE);
        include "navbar.php"; ?>

        <!-- End Navbar -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header card-header-warning card-header-text">
                                <div class="card-text">
                                    <h4 class="card-title">Crear Nuevo Pedido </h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-primary pull-right btn-send-form-table"
                                                data-form="form" data-reset="true"> Guardar Pedido
                                        </button>

                                    </div>

                                </div>
                                <form class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"
                                      action="">


                                    <input type="hidden" name="a" value="CREATE-ORDER">

                                    <?php include 'alert-form.php'; ?>

                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="col-form-label">Orden de compra</label>
                                                <div class="form-group bmd-form-group">

                                                    <?php
                                                    $sql_CT = "SELECT id from purchase_orders WHERE status='APROBADA'";
                                                    $result_CT = $cls->consultListQuery($sql_CT);//query
                                                    ?>
                                                    <select class="form-control validate select2 change-and-consult"
                                                            name="purchase_order"
                                                            id="purchase_order"
                                                            data-action="GET-PURCHASE-ORDER-TO-ORDER"
                                                            data-form="form">
                                                        <option value="">-Seleccione-</option>
                                                        <?php
                                                        foreach ($result_CT as $item) { ?>

                                                            <option value="<?php echo $item->id; ?>">#<?php echo $item->id; ?></option>

                                                        <?php } ?>

                                                    </select>
                                                    <small class="form-text text-muted purchase_order-error"
                                                           style="color:red !important;"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="col-form-label">Fecha</label>

                                                <div class="form-group bmd-form-group">
                                                    <input type="date" class="form-control validate" name="date"
                                                           value=""
                                                           id="date"
                                                           placeholder="Fecha">
                                                    <small class="form-text text-muted date-error"
                                                           style="color:red !important;"></small>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="col-form-label">Comentario</label>

                                                <div class="form-group bmd-form-group">

                                                    <textarea class="form-control" placeholder="Comentario"
                                                              name="comment" id="comment">

                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="col-form-label">Referencia #</label>

                                                <div class="form-group bmd-form-group">
                                                    <input type="text" class="form-control" name="reference"
                                                           value=""
                                                           id="reference" placeholder="Referencia #">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 table-responsive">

                                            <table class="table ">
                                                <thead class=" text-primary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>UoM Unid</th>
                                                    <th>Unt. Compradas</th>
                                                    <th>Unt. Solicitadas</th>
                                                    <th>Diferencia</th>
                                                </tr>
                                                </thead>
                                                <tbody class="table-data-add-sect2" data-action="GET-PURCHASE-ORDER-DETAILS-TO-ORDER">

                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <th>Total</th>
                                                    <td id="unit-buy">0.00</td>
                                                    <td id="unit-request">0.00</td>
                                                    <td id="unit-diff">0.00</td>
                                                </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>

                                    <div class="clearfix"></div>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>

        <?php include "footer.php"; ?>
    </div>
</div>

<?php include "scripts/scripts.php"; ?>
<?php include "scripts/select2.php"; ?>

</body>

</html>
