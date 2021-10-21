<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';

$inventory = "active";
$productos = "active-sublink";


if (isset($VAR_SESSION->permission) && !in_array("PER0003", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Crear Producto | Cafeteria
    </title>

    <?php include "styles.php"; ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body class="">
<?php include 'loader.php'; ?>

<div class="wrapper ">
    <?php include "sidebar.php"; ?>
    <div class="main-panel">
        <!-- Navbar -->
        <?php
        $breadcrumbData = array(
            array("name"=>"Lista de Productos","link"=>"./?view=products","current"=>false),
            array("name"=>"Crear Producto","current"=>true),
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
                                    <h4 class="card-title">Crear Nuevo Producto </h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-primary pull-right btn-send-form-file" data-reset="true" data-form="form">Guardar Producto</button>

                                    </div>

                                </div>

                                <form  class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"  action="" enctype="multipart/form-data">

                                    <input type="hidden" name="a" value="CREATE-PRODUCT">
                                    <?php include 'alert-form.php';?>

                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="col-form-label">Nombre</label>

                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control validate" name="name" value=""
                                                       id="name" placeholder="Nombre">
                                                <small class="form-text text-muted name-error"
                                                       style="color:red !important;"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="col-form-label">Categoría</label>
                                            <div class="form-group bmd-form-group">

                                                <?php
                                                    $sql_CT="SELECT id,name from products_category WHERE status='ACTIVO'";
                                                    $result_CT = $cls->consultListQuery($sql_CT);//query
                                                ?>

                                                <select class="form-control select2" name="category" id="category">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    foreach ($result_CT as $item) {?>

                                                        <option value="<?php echo $item->id;?>"><?php echo $item->name;?></option>

                                                    <?php }?>

                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="col-form-label">Precio</label>

                                            <div class="form-group bmd-form-group">

                                                <input type="number" class="form-control validate" min="0" max="4"
                                                       step="0.2" value="0.00" name="price" id="price"/>

                                                <small class="form-text text-muted price-error"
                                                       style="color:red !important;"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label">Proovedor</label>
                                            <div class="form-group bmd-form-group">

                                                <?php
                                                $sql_CT="SELECT id,name from providers WHERE status='ACTIVO'";
                                                $result_CT = $cls->consultListQuery($sql_CT);//query
                                                ?>
                                                <select class="form-control validate select2" name="provider" id="provider">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    foreach ($result_CT as $item) {?>

                                                        <option value="<?php echo $item->id;?>">[<?php echo $item->id;?>] <?php echo $item->name;?></option>

                                                    <?php }?>

                                                </select>
                                                <small class="form-text text-muted provider-error"
                                                       style="color:red !important;"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-form-label">Unidad para compra</label>
                                            <div class="form-group bmd-form-group">

                                                <select class="form-control validate" name="unidad_para_compra"
                                                        id="unidad_para_compra">
                                                    <option value="UND">UND</option>
                                                    <option value="DOCENA">DOCENA</option>
                                                    <option value="PAQUETE">PAQUETE</option>
                                                    <option value="LIBRAS">LIBRAS</option>
                                                    <option value="BULTOS">BULTOS</option>

                                                </select>
                                                <small class="form-text text-muted unidad_para_compra-error"
                                                       style="color:red !important;"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-form-label">Unidad para almacen</label>
                                            <div class="form-group bmd-form-group">

                                                    <select class="form-control validate" name="unidad_para_almacen"
                                                            id="unidad_para_almacen">
                                                        <option value="UND">UND</option>
                                                        <option value="DOCENA">DOCENA</option>
                                                        <option value="PAQUETE">PAQUETE</option>
                                                        <option value="LIBRAS">LIBRAS</option>
                                                        <option value="BULTOS">BULTOS</option>

                                                    </select>
                                                    <small class="form-text text-muted unidad_para_almacen-error"
                                                           style="color:red !important;"></small>
                                                </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-form-label">Código externo</label>
                                            <div class="form-group bmd-form-group">

                                                <input type="text" class="form-control " name="code_extern" value=""
                                                       id="code_extern" placeholder="Nombre">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="col-form-label">Descripción</label>

                                            <div class="form-group bmd-form-group">

                                                <textarea class="form-control" rows="5" name="description"
                                                          id="description" placeholder="Descripcion"></textarea>

                                            </div>
                                        </div>

                                    </div>


                                    <h4>Imagen del Producto </h4>
                                    <hr/>


                                    <div class="row">



                                            <div class="col-md-4 col-sm-4">

                                                <div class="fileinput fileinput-new text-center"
                                                     data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail">

                                                        <img src="Views/assets/img/image_placeholder.jpg" alt="...">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                                    <div>
                                                          <span class="btn btn-rose btn-round btn-file">
                                                            <span class="fileinput-new">Select image</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input type="file" name="file" accept=".png, .jpg, .jpeg" id="file">
                                                          </span>
                                                        <a href="#img-mis-0"
                                                           class="btn btn-danger btn-round fileinput-exists"
                                                           data-dismiss="fileinput"><i class="fa fa-times"></i>
                                                            Remove
                                                        </a>
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

</body>

</html>
