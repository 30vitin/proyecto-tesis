<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';


$configuration = "active";
$usersregister = "active-sublink";
if (!isset($_GET['id'])) {
    header("Location:javascript:window.history.go(-2);");
}
if (isset($VAR_SESSION->permission) && !in_array("PER0014", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}
if (isset($VAR_SESSION->permission) && !in_array("PER0007", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}
$id = $_GET['id'];
$sql = "SELECT name,username,status from users_access where username = '$id' limit 1";

$response = $cls->consulQuery($sql);
if (!$response) {
    header("Location:javascript:window.history.go(-2);");
}

$currentPermission = $cls->getUserPermission($id);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Editar usuario <?php echo $id; ?> | Cafeteria
    </title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

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
            array("name" => "Lista de Usuarios", "link" => "./?view=users", "current" => false),
            array("name" => "Editar Permisos $id", "current" => true),
        );

        $breadcrumb = json_decode(json_encode($breadcrumbData), FALSE);
        include "navbar.php"; ?>

        <!-- End Navbar -->
        <div class="content">
            <div class="container-fluid">
                <div class="row d-flex justify-content-center">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-7">
                        <div class="card ">
                            <div class="card-header card-header-warning card-header-text">
                                <div class="card-text">
                                    <h4 class="card-title">Editar Permisos de Usuario <?php echo $id; ?> </h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-primary pull-right btn-send-form"
                                                data-form="form"
                                                data-reset="false">Guardar
                                        </button>

                                    </div>

                                </div>

                                <form class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"
                                      action="">

                                    <input type="hidden" name="a" value="UPDATE-USERS-PERMISSION">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">


                                    <?php include 'alert-form.php'; ?>


                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-sm-12">

                                            <div class="accordion" id="accordionModuleDashboard">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseDashboard" aria-expanded="true"
                                                                    aria-controls="collapseDashboard">
                                                                Dashboard
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseDashboard" class="collapse"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionModuleDashboard">
                                                        <div class="card-body row pl-5">

                                                            <?php
                                                            $sql1 = "SELECT permission,name,module FROM permission WHERE module='Dashboard'";
                                                            $res1 = $cls->consultListQuery($sql1);
                                                            foreach ($res1 as $item) {
                                                                $checked = "";
                                                                if (in_array($item->permission, $currentPermission))
                                                                {
                                                                    $checked="checked ='checked'";
                                                                }
                                                                ?>
                                                                <div class="col-md-12">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           value="<?php echo $item->permission; ?>"
                                                                           id="id<?php echo $item->permission; ?>" <?php echo $checked;?> name="permission[]">
                                                                    <label class="form-check-label"
                                                                           for="id<?php echo $item->permission; ?>">
                                                                        <?php echo $item->name; ?>
                                                                    </label>
                                                                </div>


                                                            <?php } ?>


                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="accordion" id="accordionModuleSale">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseOne" aria-expanded="true"
                                                                    aria-controls="collapseOne">
                                                                Módulo de Ventas
                                                            </button>
                                                        </h5>
                                                    </div>
                                                     <div id="collapseOne" class="collapse"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionModuleSale">
                                                        <div class="card-body row pl-5">

                                                            <?php
                                                            $sql1 = "SELECT permission,name,module FROM permission WHERE module='Ventas' and submodulo<>'Ventas'";
                                                            $res1 = $cls->consultListQuery($sql1);
                                                            foreach ($res1 as $item) {
                                                                $checked = "";
                                                                if (in_array($item->permission, $currentPermission))
                                                                {
                                                                    $checked="checked ='checked'";
                                                                }
                                                                ?>
                                                                <div class="col-md-12">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           value="<?php echo $item->permission; ?>"
                                                                           id="id<?php echo $item->permission; ?>" <?php echo $checked;?> name="permission[]">
                                                                    <label class="form-check-label"
                                                                           for="id<?php echo $item->permission; ?>">
                                                                        <?php echo $item->name; ?>
                                                                    </label>
                                                                </div>


                                                            <?php } ?>


                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="accordion" id="accordionModulePurchase">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseTwo" aria-expanded="true"
                                                                    aria-controls="collapseTwo">
                                                                Módulo de Compras
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseTwo" class="collapse"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionModulePurchase">
                                                        <div class="card-body row pl-5">

                                                            <?php
                                                            $sql1 = "SELECT permission,name,module FROM permission WHERE module='Compras'";
                                                            $res1 = $cls->consultListQuery($sql1);
                                                            foreach ($res1 as $item) {
                                                                $checked = "";
                                                                if (in_array($item->permission, $currentPermission))
                                                                {
                                                                    $checked="checked ='checked'";
                                                                }
                                                                ?>
                                                                <div class="col-md-12">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           value="<?php echo $item->permission; ?>"
                                                                           id="id<?php echo $item->permission; ?>" <?php echo $checked;?> name="permission[]">
                                                                    <label class="form-check-label"
                                                                           for="id<?php echo $item->permission; ?>">
                                                                        <?php echo $item->name; ?>
                                                                    </label>
                                                                </div>


                                                            <?php } ?>


                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="accordion" id="accordionModuleInventory">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseThree" aria-expanded="true"
                                                                    aria-controls="collapseThree">
                                                                Módulo de Inventario
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseThree" class="collapse"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionModuleInventory">
                                                        <div class="card-body row pl-5">

                                                            <?php
                                                            $sql1 = "SELECT permission,name,module FROM permission WHERE module='Inventario'";
                                                            $res1 = $cls->consultListQuery($sql1);
                                                            foreach ($res1 as $item) {
                                                                $checked = "";
                                                                if (in_array($item->permission, $currentPermission))
                                                                {
                                                                    $checked="checked ='checked'";
                                                                }
                                                                ?>
                                                                <div class="col-md-12">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           value="<?php echo $item->permission; ?>"
                                                                           id="id<?php echo $item->permission; ?>" <?php echo $checked;?> name="permission[]">
                                                                    <label class="form-check-label"
                                                                           for="id<?php echo $item->permission; ?>">
                                                                        <?php echo $item->name; ?>
                                                                    </label>
                                                                </div>


                                                            <?php } ?>


                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="accordion" id="accordionModuleStore">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseFour" aria-expanded="true"
                                                                    aria-controls="collapseFour">
                                                                Módulo de Almacen
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseFour" class="collapse"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionModuleStore">
                                                        <div class="card-body row pl-5">

                                                            <?php
                                                            $sql1 = "SELECT permission,name,module FROM permission WHERE module='Almacen'";
                                                            $res1 = $cls->consultListQuery($sql1);
                                                            foreach ($res1 as $item) {
                                                                $checked = "";
                                                                if (in_array($item->permission, $currentPermission))
                                                                {
                                                                    $checked="checked ='checked'";
                                                                }
                                                                ?>
                                                                <div class="col-md-12">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           value="<?php echo $item->permission; ?>"
                                                                           id="id<?php echo $item->permission; ?>" <?php echo $checked;?> name="permission[]">
                                                                    <label class="form-check-label"
                                                                           for="id<?php echo $item->permission; ?>">
                                                                        <?php echo $item->name; ?>
                                                                    </label>
                                                                </div>


                                                            <?php } ?>


                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="accordion" id="accordionModuleConfig">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseFive" aria-expanded="true"
                                                                    aria-controls="collapseFive">
                                                                Módulo de Configuración
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseFive" class="collapse"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionModuleConfig">
                                                        <div class="card-body row pl-5">

                                                            <?php
                                                            $sql1 = "SELECT permission,name,module FROM permission WHERE module='Configuración'";
                                                            $res1 = $cls->consultListQuery($sql1);
                                                            foreach ($res1 as $item) {
                                                                $checked = "";
                                                                if (in_array($item->permission, $currentPermission))
                                                                {
                                                                    $checked="checked ='checked'";
                                                                }
                                                                ?>
                                                                <div class="col-md-12">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           value="<?php echo $item->permission; ?>"
                                                                           id="id<?php echo $item->permission; ?>" <?php echo $checked;?> name="permission[]">
                                                                    <label class="form-check-label"
                                                                           for="id<?php echo $item->permission; ?>">
                                                                        <?php echo $item->name; ?>
                                                                    </label>
                                                                </div>


                                                            <?php } ?>


                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

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
<?php include "scripts/select2.php"; ?>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>


</body>

</html>
