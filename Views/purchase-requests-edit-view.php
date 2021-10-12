<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';


if (!isset($_GET['id'])) {
    header("Location:javascript:window.history.go(-2);");
}
$id = $_GET['id'];
$sql = "SELECT date,provider,comment,status FROM purchase_requests WHERE id='$id' and status<>'DELETE'";

$response = $cls->consulQuery($sql);
if (!$response) {
    header("Location:javascript:window.history.go(-2);");
}
$disabled="";
$classDisable="";
if($response['status']=='CERRADO'){

    $disabled="disabled='disabled'";
    $classDisable="disable-button";

}

$purchase = "active";
$requisicion = "active-sublink";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Editar Requisición | Cafeteria
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


        <?php include "navbar.php"; ?>

        <!-- End Navbar -->
        <div class="content">
            <div class="container-fluid">

                <div class="row">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-10">
                        <div class="card ">
                            <div class="card-header card-header-rose card-header-text">
                                <div class="card-text">
                                    <h4 class="card-title">Editar Requisición # <?php echo $id;?> </h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-danger pull-right btn-delete-form <?php echo $classDisable;?>"
                                                data-form="form" data-id="<?php echo $id; ?>"
                                                data-action="CLOSE-REQUEST"
                                                data-text="¿Estas seguro de cerrar esta requisición?" <?php echo $disabled;?>>Cerrar
                                        </button>
                                        <button type="button" class="btn btn-primary pull-right btn-send-form-table <?php echo $classDisable;?>"
                                                data-form="form" data-reset="false" <?php echo $disabled;?>>Guardar
                                        </button>
                                        <button type="button" class="btn btn-success pull-right <?php echo $classDisable;?>"
                                                data-form="form" data-reset="false" <?php echo $disabled;?>>Convertir a compra
                                        </button>

                                    </div>

                                </div>
                                <form class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"
                                      action="">


                                    <input type="hidden" name="a" value="UPDATE-PURCHASE-REQUEST">
                                    <input type="hidden" name="id" value="<?php echo $id;?>">

                                    <?php include 'alert-form.php'; ?>

                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="col-form-label">Status</label>

                                                <div class="form-group bmd-form-group">

                                                    <span class="badge <?php echo ($response['status'] =='CERRADO')? 'badge-danger':'badge-success'?>"><?php echo $response['status'];?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="col-form-label">Fecha</label>

                                                <div class="form-group bmd-form-group">
                                                    <input type="date" class="form-control validate" name="date"
                                                           value="<?php echo date_format(date_create($response['date']), 'Y-m-d');?>"
                                                           id="date" placeholder="Fecha">
                                                    <small class="form-text text-muted date-error"
                                                           style="color:red !important;"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="col-form-label">Proovedor</label>
                                                <div class="form-group bmd-form-group">

                                                    <?php
                                                    $sql_CT = "SELECT id,name from providers WHERE status='ACTIVO'";
                                                    $result_CT = $cls->consultListQuery($sql_CT);//query
                                                    ?>
                                                    <select class="form-control validate select2" name="provider"
                                                            id="provider">
                                                        <option value="">-Seleccione-</option>
                                                        <?php
                                                        foreach ($result_CT as $item) { ?>

                                                            <option value="<?php echo $item->id; ?>" <?php echo ($response['provider'] == $item->id) ? 'selected' : ''; ?>><?php echo $item->name; ?></option>

                                                        <?php } ?>

                                                    </select>
                                                    <small class="form-text text-muted provider-error"
                                                           style="color:red !important;"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="col-form-label">Comentario</label>

                                                <div class="form-group bmd-form-group">
                                                    <textarea class="form-control" placeholder="Comentario"
                                                              name="comment"><?php echo trim($response['comment']);?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 table-responsive">
                                            <div class="row">
                                                <div class="col-md-6 pt-3">
                                                    <button type="button"
                                                            class="btn btn-primary pull-left btn-product-table-line <?php echo $classDisable;?>" <?php echo $disabled;?>>
                                                        Agregar producto
                                                    </button>

                                                </div>

                                            </div>
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
                                                <tbody class="table-data-add table-data-edit" data-id="<?php echo $id;?>" data-action="GET-PURCHASE-REQUEST-DETAILS">

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
        <?php include "scripts/modal-products.php"; ?>
        <?php include "footer.php"; ?>
    </div>
</div>


<?php include "scripts/scripts.php"; ?>
<?php include "scripts/data-table.php"; ?>
<?php include "scripts/select2.php"; ?>

</body>

</html>
