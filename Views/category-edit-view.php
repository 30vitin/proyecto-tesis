<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';

// consultar

if (!isset($_GET['id'])) {
    header("Location:javascript:window.history.go(-2);");
}

if (isset($VAR_SESSION->permission) && !in_array("PER0003", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}
$id = $_GET['id'];

$sql = "SELECT id, name from products_category where id = '$id' AND status ='ACTIVO'  limit 1";
$response = $cls->consulQuery($sql);
if(!$response){
    header("Location:javascript:window.history.go(-2);");
}

$inventory = "active";
$categoria = "active-sublink";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Editar Categoría # <?php echo $id;?> | Cafeteria
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport'/>
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
            array("name"=>"Lista de Categorias","link"=>"./?view=category-products","current"=>false),
            array("name"=>"Editar Categoría #$id","current"=>true),
        );

        $breadcrumb = json_decode(json_encode($breadcrumbData), FALSE);
        include "navbar.php"; ?>

        <!-- End Navbar -->
        <div class="content" >
            <div class="container-fluid">
                <div class="row">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header card-header-warning card-header-text">
                                <div class="card-text">
                                    <h4 class="card-title">Editar Categoría #<?php echo $response['id'];?> <i
                                                class="spinner-border spinner-border-sm"></i></h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-danger pull-right btn-delete-form"
                                                data-form="form" data-id="<?php echo $id;?>" data-action="DELETE-CATEGORY" data-text="¿Estas seguro de eliminar esta categoría?">Eliminar
                                        </button>
                                        <button type="button" class="btn btn-primary pull-right btn-send-form" data-reset="false"
                                                data-form="form">Guardar
                                        </button>

                                    </div>

                                </div>

                                <form class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"  action="">

                                    <input type="hidden" name="a" value="UPDATE-CATEGORY">
                                    <input type="hidden" name="id" value="<?php echo $response['id'];?>">

                                    <?php include 'alert-form.php';?>


                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label">Nombre</label>
                                        <div class="col-sm-10">
                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control validate" name="name" value="<?php echo $response['name'];?>"
                                                       id="name" placeholder="Nombre">
                                                <small class="form-text text-muted name-error"
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
