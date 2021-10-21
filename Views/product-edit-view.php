<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';

if (!isset($_GET['id'])) {
    header("Location:javascript:window.history.go(-2);");
}

if (isset($VAR_SESSION->permission) && !in_array("PER0003", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}
$id = $_GET['id'];

$sql = "SELECT name,description,category,price,img_portada,code_extern,
        unidad_para_compra,unidad_para_almacen,provider FROM products WHERE id='$id' and status='ACTIVO'";
$response = $cls->consulQuery($sql);
if (empty($response)) {
    header("Location:javascript:window.history.go(-2);");
}

$inventory = "active";
$productos = "active-sublink";


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Editar Producto # <?php echo $id;?> | Cafeteria
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
            array("name"=>"Lista de Productos","link"=>"./?view=products","current"=>false),
            array("name"=>"Editar Producto #$id","current"=>true),
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
                                    <h4 class="card-title">Editar Producto #<?php echo $id; ?> </h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-danger pull-right btn-delete-form"
                                                data-form="form" data-id="<?php echo $id; ?>"
                                                data-action="DELETE-PRODUCT"
                                                data-text="¿Estas seguro de eliminar este producto?">Eliminar
                                        </button>
                                        <button type="button" class="btn btn-primary pull-right btn-send-form-file"
                                                data-form="form" data-reset="false">Guardar
                                        </button>

                                    </div>

                                </div>

                                <form class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"
                                      action="" enctype="multipart/form-data">

                                    <input type="hidden" name="a" value="UPDATE-PRODUCT">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <?php include 'alert-form.php'; ?>

                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="col-form-label">Nombre</label>

                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control validate"
                                                       name="name" value="<?php echo $response['name']; ?>"
                                                       id="name" placeholder="Nombre">
                                                <small class="form-text text-muted name-error"
                                                       style="color:red !important;"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="col-form-label">Categoría</label>
                                            <div class="form-group bmd-form-group">

                                                <?php
                                                $sql_CT = "SELECT id,name from products_category WHERE status='ACTIVO'";
                                                $result_CT = $cls->consultListQuery($sql_CT);//query
                                                ?>

                                                <select class="form-control select2" name="category" id="category">
                                                    <option value="">-Seleccione-</option>
                                                    <?php
                                                    foreach ($result_CT as $item) { ?>

                                                        <option value="<?php echo $item->id; ?>" <?php echo ($response['category'] == $item->id) ? 'selected' : ''; ?>><?php echo $item->name; ?></option>

                                                    <?php } ?>

                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="col-form-label">Precio</label>

                                            <div class="form-group bmd-form-group">

                                                <input type="number" class="form-control validate" min="0" max="4"
                                                       step="0.2" value="<?php echo $response['price']; ?>" name="price"
                                                       id="price"/>

                                                <small class="form-text text-muted price-error"
                                                       style="color:red !important;"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-form-label">Unidad para compra</label>
                                            <div class="form-group bmd-form-group">

                                                <select class="form-control validate" name="unidad_para_compra"
                                                        id="unidad_para_compra">
                                                    <option value="UND" <?php echo ($response['unidad_para_compra'] == 'UND') ? 'selected' : ''; ?>>
                                                        UND
                                                    </option>
                                                    <option value="DOCENA" <?php echo ($response['unidad_para_compra'] == 'DOCENA') ? 'selected' : ''; ?>>
                                                        DOCENA
                                                    </option>
                                                    <option value="PAQUETE" <?php echo ($response['unidad_para_compra'] == 'PAQUETE') ? 'selected' : ''; ?>>
                                                        PAQUETE
                                                    </option>
                                                    <option value="LIBRAS" <?php echo ($response['unidad_para_compra'] == 'LIBRAS') ? 'selected' : ''; ?>>
                                                        LIBRAS
                                                    </option>
                                                    <option value="BULTOS" <?php echo ($response['unidad_para_compra'] == 'BULTOS') ? 'selected' : ''; ?>>
                                                        BULTOS
                                                    </option>

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
                                                    <option value="UND" <?php echo ($response['unidad_para_almacen'] == 'UND') ? 'selected' : ''; ?>>
                                                        UND
                                                    </option>
                                                    <option value="DOCENA" <?php echo ($response['unidad_para_almacen'] == 'DOCENA') ? 'selected' : ''; ?>>
                                                        DOCENA
                                                    </option>
                                                    <option value="PAQUETE" <?php echo ($response['unidad_para_almacen'] == 'PAQUETE') ? 'selected' : ''; ?>>
                                                        PAQUETE
                                                    </option>
                                                    <option value="LIBRAS" <?php echo ($response['unidad_para_almacen'] == 'LIBRAS') ? 'selected' : ''; ?>>
                                                        LIBRAS
                                                    </option>
                                                    <option value="BULTOS" <?php echo ($response['unidad_para_almacen'] == 'BULTOS') ? 'selected' : ''; ?>>
                                                        BULTOS
                                                    </option>

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

                                                <input type="text" class="form-control " name="code_extern"
                                                       value="<?php echo $response['code_extern']; ?>"
                                                       id="code_extern" placeholder="Nombre">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="col-form-label">Descripción</label>

                                            <div class="form-group bmd-form-group">

                                                <textarea class="form-control" rows="5" name="description"
                                                          id="description"
                                                          placeholder="Descripcion"> <?php echo $response['description']; ?></textarea>

                                            </div>
                                        </div>

                                    </div>


                                    <h4>Imagen del Producto </h4>
                                    <hr/>


                                    <div class="row">


                                        <div class="col-md-4 col-sm-4">

                                            <div class="fileinput fileinput-new text-center"
                                                 data-provides="fileinput">
                                                <div class="fileinput-new <?php echo (isset($response['img_portada'])) ?'fileinput-exists':'';?> thumbnail">

                                                    <img src="<?php echo $cls->getVarData('default-image'); ?>"
                                                         alt="default">
                                                </div>
                                                <div class="fileinput-preview <?php echo (!isset($response['img_portada'])) ?'fileinput-exists':'';?> thumbnail">
                                                    <?php

                                                        if(isset($response['img_portada'])){
                                                              $path = $cls->getVarData('product-image').'/'.$id.'/'.$response['img_portada'];
                                                              if(file_exists($path)){?>

                                                                  <img src="<?php echo $path; ?>"
                                                                       alt="<?php $response['name'];?>">

                                                              <?php }else{?>

                                                                        <img src="<?php echo $cls->getVarData('default-image'); ?>"
                                                                       alt="default">
                                                             <?php }?>

                                                       <?php }?>
                                                </div>
                                                <div>
                                                          <span class="btn btn-rose btn-round btn-file">

                                                               <?php if(!isset($response['img_portada'])){?>
                                                                        <span class="fileinput-new">Select image</span
                                                                <?php } ?>
                                                              <?php if(isset($response['img_portada'])){?>
                                                                         <span class="">Change</span>
                                                              <?php } ?>
                                                            <input type="file" name="file" accept=".png, .jpg, .jpeg"
                                                                   id="file">
                                                          </span>



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
