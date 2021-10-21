<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';

$sales = "active";
$cotizacion = "active-sublink";

if (isset($VAR_SESSION->permission) && !in_array("PER0011", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Crear Cotización | Cafeteria
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
            array("name"=>"Listar Cotizaciones","link"=>"./?view=quotes","current"=>false),
            array("name"=>"Crear Cotización","current"=>true),
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
                                    <h4 class="card-title">Crear Nueva Cotización </h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-primary pull-right btn-send-form-table"
                                                data-form="form" data-reset="true"> Guardar Cotización
                                        </button>

                                    </div>

                                </div>
                                <form class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"
                                      action="">


                                    <input type="hidden" name="a" value="CREATE-QUOTE">

                                    <?php include 'alert-form.php'; ?>

                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="col-form-label">Pedido</label>
                                                <div class="form-group bmd-form-group">

                                                    <?php
                                                    $sql_CT = "SELECT id from orders WHERE status='APROBADA'";
                                                    $result_CT = $cls->consultListQuery($sql_CT);//query
                                                    ?>
                                                    <select class="form-control validate select2 change-and-consult"
                                                            name="order_id"
                                                            id="order_id"
                                                            data-action="GET-PURCHASE-ORDER-TO-QUOTE"
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
                                                <label class="col-form-label">Cliente</label>
                                                <div class="form-group bmd-form-group">

                                                    <?php
                                                    $sql_CT = "SELECT id,name from customers WHERE status='ACTIVO'";
                                                    $result_CT = $cls->consultListQuery($sql_CT);//query
                                                    ?>
                                                    <select class="form-control validate select2 " name="customer"
                                                            id="customer" data-reset-select-field="true">
                                                        <option value="">-Seleccione-</option>
                                                        <?php
                                                        foreach ($result_CT as $item) { ?>

                                                            <option value="<?php echo $item->id; ?>"><?php echo $item->name; ?></option>

                                                        <?php } ?>

                                                    </select>
                                                    <small class="form-text text-muted customer-error"
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
                                            <div class="col-md-12">
                                                <label class="col-form-label">Dias de vencimiento</label>

                                                <div class="form-group bmd-form-group">
                                                    <input type="number" class="form-control" name="days_expired"
                                                           value="0"
                                                           id="days_expired"
                                                           placeholder="Dias de vencimiento">

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
                                                    <th class="small-th"></th>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>UoM Unid</th>
                                                    <th>Unidades</th>
                                                    <th>Costo</th>
                                                    <th>Total</th>
                                                </tr>
                                                </thead>
                                                <tbody class="table-data-add disable-button"  data-action="GET-ORDER-DETAILS-TO-QUOTE">

                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <th>Total</th>
                                                    <td id="total-table">0.00</td>
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
