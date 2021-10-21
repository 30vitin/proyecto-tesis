<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';

$sales = "active";
$clientes = "active-sublink";

if (!isset($_GET['id'])) {
    header("Location:javascript:window.history.go(-2);");
}

if (isset($VAR_SESSION->permission) && !in_array("PER0009", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}

$id = $_GET['id'];
$sql = "SELECT name,email,telephone1,telephone2,type_credit FROM customers WHERE id='$id' and status='ACTIVO'";
$response = $cls->consulQuery($sql);
if (!$response) {
    header("Location:javascript:window.history.go(-2);");
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Editar Cliente # <?php echo $id;?> | Cafeteria
    </title>
    <?php include "styles.php"; ?>

</head>

<body class="">
<?php include 'loader.php'; ?>


<div class="wrapper ">
    <?php include "sidebar.php"; ?>

    <div class="main-panel">
        <!-- Navbar -->

        <?php
        $breadcrumbData = array(
            array("name"=>"Lista de Clientes","link"=>"./?view=customers","current"=>false),
            array("name"=>"Crear Cliente #$id","current"=>true),
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
                                    <h4 class="card-title">Editar Cliente #<?php echo $id; ?></h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">

                                        <button type="button" class="btn btn-danger pull-right btn-delete-form"
                                                data-form="form" data-id="<?php echo $id; ?>"
                                                data-action="DELETE-CUSTOMER"
                                                data-text="¿Estas seguro de eliminar este cliente?">Eliminar
                                        </button>
                                        <button type="button" class="btn btn-primary pull-right btn-send-form"
                                                data-form="form" data-reset="false"> Guardar
                                        </button>

                                    </div>

                                </div>
                                <form class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"
                                      action="">

                                    <input type="hidden" name="a" value="UPDATE-CUSTOMER">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <?php include 'alert-form.php'; ?>


                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="col-form-label">Nombre</label>
                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control validate" name="name"
                                                       value="<?php echo $response['name']; ?>"
                                                       id="name" placeholder="Nombre">
                                                <small class="form-text text-muted name-error"
                                                       style="color:red !important;"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label">Email</label>
                                            <div class="form-group bmd-form-group">
                                                <input type="email" class="form-control " name="email"
                                                       value="<?php echo $response['email']; ?>"
                                                       id="email" placeholder="Email">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="col-form-label">Teléfono 1</label>
                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control validate" name="telephone1"
                                                       value="<?php echo $response['telephone1']; ?>" id="telephone1"
                                                       placeholder="Teléfono 1">

                                                <small class="form-text text-muted telephone1-error"
                                                       style="color:red !important;"></small>
                                            </div>

                                        </div>
                                        <div class="col-md-5">
                                            <label class="col-form-label">Teléfono 2</label>

                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control " name="telephone2"
                                                       value="<?php echo $response['telephone2']; ?>"
                                                       id="telephone2" placeholder="Teléfono 2">
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="col-form-label">Tipo de Crédito</label>
                                            <div class="form-group bmd-form-group">
                                                <select name="type_credit" id="type_credit"
                                                        class="form-control validate">
                                                    <option value="CONTADO" <?php echo ($response['type_credit'] == 'CONTADO') ? 'selected' : ''; ?>>
                                                        CONTADO
                                                    </option>
                                                    <option value="CREDITO"
                                                            <?php echo ($response['type_credit'] == 'CREDITO') ? 'selected' : ''; ?>CREDITO
                                                    </option>
                                                </select>

                                                <small class="form-text text-muted type_credit-error"
                                                       style="color:red !important;"></small>
                                            </div>

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

</body>

</html>
