<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';


$configuration="active";
$cambiarstatus="active-sublink";
if (isset($VAR_SESSION->permission) && !in_array("PER0006", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Cambiar Status | Cafeteria
    </title>

    <?php include "styles.php";?>
</head>

<body class="">
<?php include 'loader.php';?>

<div class="wrapper ">

    <?php include "sidebar.php";?>

    <div class="main-panel">
        <!-- Navbar -->

        <!-- End Navbar -->
        <div class="content">
            <div class="container-fluid">
                <div class="row d-flex justify-content-center">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-7">
                        <div class="card ">
                            <div class="card-header card-header-warning card-header-text">
                                <div class="card-text">
                                    <h4 class="card-title">Cambiar status</h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <form  class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"  action="">



                                    <hr/>
                                    <div class="row pl-4">
                                        <div class="col-sm-12">
                                            <div class="row ">
                                                <a href="./?view=change-status-list&option=1" class="col-md-12 p-2 list-group-item-action"><i class="fa fa-external-link"></i> Cambiar Status a requisición</a>
                                                <a href="./?view=change-status-list&option=2" class="col-md-12 p-2 list-group-item-action"><i class="fa fa-external-link"></i> Cambiar Status a O/C</a>
                                                <a href="./?view=change-status-list&option=3" class=" col-md-12 p-2 list-group-item-action"><i class="fa fa-external-link"></i> Cambiar Status a Pedido</a>
                                                <a href="./?view=change-status-list&option=4" class="col-md-12 p-2 list-group-item-action"><i class="fa fa-external-link"></i> Cambiar Status a Factura</a>
                                                <a href="./?view=change-status-list&option=5" class="col-md-12 p-2 list-group-item-action"><i class="fa fa-external-link"></i> Cambiar Status a Recepción de Mercancía</a>
                                                <a href="./?view=change-status-list&option=6" class="col-md-12 p-2 list-group-item-action"><i class="fa fa-external-link"></i> Cambiar Status a Despacho de Mercancía</a>

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


<?php include "scripts/scripts.php";?>
</body>

</html>
