<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';

$almacen = "active";
$rep_mercancia = "active-sublink";


if (!isset($_GET['id'])) {
    header("Location:javascript:window.history.go(-2);");
}
if (isset($VAR_SESSION->permission) && !in_array("PER0004", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}

$id = $_GET['id'];
$sql = "SELECT date,bills,comment,status,reference,updated_by,approved_by,updated_at,approved_at FROM received_merchant WHERE id='$id' and status<>'DELETE'";

$response = $cls->consulQuery($sql);
if (!$response) {
    header("Location:javascript:window.history.go(-2);");
}

$disabled = "";
$classDisable = "";
$readOnly = "";
if ($response['status'] == 'CERRADO' || $response['status'] == 'APROBADA') {
    $disabled = "disabled='disabled'";
    $classDisable = "disable-button";
    $readOnly = "readonly='readonly'";
}


$completeOrder = false;
if ($cls->chetIfBillsIsComplete($id)) {
    $completeOrder = true;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Editar Recepción de Mercancía # <?php echo $id; ?> | Cafeteria
    </title>
    <?php include "styles.php"; ?>
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet"/>

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
            array("name" => "Lista de Recepción de Mercancía", "link" => "./?view=receive-merchant", "current" => false),
            array("name" => "Crear  Recepción de Mercancía", "current" => true),
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
                                    <h4 class="card-title">Editar Recepción de Mercancía # <?php echo $id; ?> </h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <?php if ($response['status'] == 'ACTIVO') { ?>

                                            <button type="button"
                                                    class="btn btn-primary pull-right btn-send-form-table" <?php echo $classDisable; ?>
                                                    data-form="form" <?php echo $disabled; ?>> Guardar
                                            </button>
                                            <button type="button"
                                                    class="btn btn-success pull-right btn-confirm-action <?php echo $classDisable; ?>"
                                                    data-id="<?php echo $id; ?>" data-action="APROVE-RECEIVE-MERCHANT"
                                                    data-text="¿Estas seguro de aprobar esta recepción de mercancía?" <?php echo $disabled; ?>
                                                    data-validform="true"
                                                    data-validtableform="true">
                                                Aprobar para imprimir
                                            </button>
                                        <?php } ?>

                                        <?php if ($response['status'] == 'APROBADA') { ?>
                                            <?php if ($cls->enableClose()) { ?>
                                                <button type="button" class="btn btn-danger pull-right btn-delete-form"
                                                        data-form="form" data-id="<?php echo $id; ?>"
                                                        data-action="CLOSE-RECEIVE-MERCHANT"
                                                        data-text="¿Estas seguro de cerrar este recepción de mercancía?">
                                                    Cerrar
                                                </button>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if ($completeOrder){ ?>

                                            <button type="button"
                                                    class="btn btn-success pull-right show-data-modal"
                                                    data-action="GET-DISPATCH-RELATED-RECEIVE-MERCHANT"
                                                    data-columns="Id,Fecha,Compradas,Solicitadas,Diferencia,Status"
                                                    data-applylink="true"
                                                    data-title="Despachos relacionados"
                                                    data-id="<?php echo $id; ?>">Ver despachos

                                            </button>
                                        <?php } ?>
                                            <?php if ($response['status'] == 'APROBADA' || $response['status'] == 'CERRADO') { ?>

                                                <a href="./?view=pdf-generate&id=<?php echo $id;?>&section=receive_merchant" class="btn btn-secondary pull-right print"
                                                   data-form="form" data-reset="true"> Imprimir
                                                </a>

                                            <?php } ?>




                                    </div>

                                </div>
                                <form class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"
                                      action="">


                                    <input type="hidden" name="a" value="UPDATE-RECEIVE-MERCHANT">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                                    <?php include 'alert-form.php'; ?>

                                    <h4>Datos Generales</h4>
                                    <?php if ($completeOrder) { ?>
                                        <div class="col-md-12">
                                            <div class="form-group bmd-form-group">

                                                <span class="badge badge-warning ">Recepción completada!</span>
                                            </div>
                                        </div>

                                    <?php } ?>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="col-form-label">Status</label>

                                                <div class="form-group bmd-form-group">

                                                    <span class="badge <?php echo $cls->getStatusClass($response['status']) ?>"><?php echo $response['status']; ?></span>
                                                </div>
                                            </div>
                                            <?php if ($response['status'] == 'APROBADA' || $response['status'] == 'CERRADO') { ?>

                                                <div class="col-md-12 row">

                                                    <div class="col-md-6">
                                                        <label class="col-form-label">Aprobado por</label>

                                                        <div class="form-group bmd-form-group">
                                                            <?php echo $response['approved_by']; ?>
                                                            <p><?php echo $response['approved_at']; ?></p>
                                                        </div>


                                                    </div>

                                                    <?php if ($response['status'] == 'CERRADO') { ?>
                                                        <div class="col-md-6">
                                                            <label class="col-form-label">Cerrado por</label>

                                                            <div class="form-group bmd-form-group">
                                                                <?php echo $response['updated_by']; ?>
                                                                <p><?php echo $response['updated_at']; ?></p>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                </div>

                                            <?php } ?>

                                            <div class="col-md-12">
                                                <label class="col-form-label">Factura</label>
                                                <div class="form-group bmd-form-group">
                                                    <?php if ($response['status'] == 'APROBADA' || $response['status'] == 'CERRADO') { ?>


                                                        <div class="form-group bmd-form-group">
                                                            <a href="./?view=bills-edit&id=<?php echo $response['bills']; ?>"
                                                               class="btn btn-outline-info"
                                                               target="_blank">#<?php echo $response['bills']; ?></a>
                                                        </div>


                                                    <?php } else { ?>

                                                        <?php
                                                        $sql_CT = "SELECT id from bills WHERE status='APROBADA'";
                                                        $result_CT = $cls->consultListQuery($sql_CT);//query
                                                        ?>
                                                        <select class="form-control validate select2 change-and-consult-edit"
                                                                name="bills"
                                                                id="bills"
                                                                data-action="GET-BILLS-TO-RECEIVE-MERCHANT"
                                                                data-form="form"<?php echo $disabled; ?>>
                                                            <option value="">-Seleccione-</option>
                                                            <?php
                                                            foreach ($result_CT as $item) { ?>

                                                                <option value="<?php echo $item->id; ?>" <?php echo ($response['bills'] == $item->id) ? 'selected' : ''; ?>>
                                                                    #<?php echo $item->id; ?></option>

                                                            <?php } ?>

                                                        </select>
                                                        <small class="form-text text-muted bills-error"
                                                               style="color:red !important;"></small>

                                                    <?php } ?>

                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="col-form-label">Fecha</label>

                                                <div class="form-group bmd-form-group">
                                                    <input type="date" class="form-control validate" name="date"
                                                           value="<?php echo date_format(date_create($response['date']), 'Y-m-d'); ?>"
                                                           id="date"
                                                           placeholder="Fecha"
                                                        <?php echo $readOnly ?>>
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
                                                              name="comment"
                                                              id="comment"  <?php echo $readOnly ?>><?php echo trim($response['comment']); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="col-form-label">Referencia #</label>

                                                <div class="form-group bmd-form-group">
                                                    <input type="text" class="form-control" name="reference"
                                                           value="<?php echo $response['reference']; ?>"
                                                           id="reference"
                                                           placeholder="Referencia #" <?php echo $readOnly ?>>
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
                                                <tbody class="table-data-edit disable-button"
                                                       data-action="GET-MERCHANT-DETAILS-TO-RECEIVE-MERCHANT-DETAILS"
                                                       data-action-change="GET-BILLS-DETAILS-TO-RECEIVE-MERCHANT"
                                                       data-id="<?php echo $id; ?>">

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

        <?php include "scripts/modal-global.php"; ?>
        <?php include "footer.php"; ?>

    </div>
</div>

<?php include "scripts/scripts.php"; ?>
<?php include "scripts/data-table.php"; ?>
<?php include "scripts/select2.php"; ?>

</body>

</html>
