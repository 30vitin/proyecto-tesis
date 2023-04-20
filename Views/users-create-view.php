<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';


$configuration="active";
$usersregister="active-sublink";
if (isset($VAR_SESSION->permission) && !in_array("PER0014", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Registrar | Cafeteria
    </title>

    <?php include "styles.php";?>
</head>

<body class="">
<?php include 'loader.php';?>

<div class="wrapper ">

    <?php include "sidebar.php";?>

    <div class="main-panel">
        <!-- Navbar -->
        <?php
        $breadcrumbData = array(
            array("name"=>"Lista de Usuarios","link"=>"./?view=users","current"=>false),
            array("name"=>"Crear Usuario","current"=>true),
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
                                    <h4 class="card-title">Crear Nuevo Usuario </h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-primary pull-right btn-send-form" data-form="form"
                                                data-reset="true">Guardar Usuario</button>

                                    </div>

                                </div>

                                <form  class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"  action="">

                                    <input type="hidden" name="a" value="CREATE-USERS">

                                    <?php include 'alert-form.php';?>



                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="col-form-label">Nombre</label>

                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control validate" name="name" value="" id="name" placeholder="Nombre">
                                                <small  class="form-text text-muted name-error" style="color:red !important;"></small>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="col-form-label">Username</label>

                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control validate" name="username" value="" id="username" placeholder="Username">
                                                <small  class="form-text text-muted username-error" style="color:red !important;"></small>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="col-form-label">Contraseña</label>

                                            <div class="form-group bmd-form-group">
                                                <input type="password" class="form-control validate" name="new_password" value="" id="new_password" placeholder="Nueva contraseña">
                                                <small  class="form-text text-muted new_password-error" style="color:red !important;"></small>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="col-form-label">Repetir contraseña</label>

                                            <div class="form-group bmd-form-group">
                                                <input type="password" class="form-control validat password_confirm" name="password_confirm" value="" id="password_confirm" placeholder="Repetir contraseña">
                                                <small  class="form-text text-muted password_confirm-error" style="color:red !important;"></small>
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
