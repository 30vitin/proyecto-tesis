<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Panama');

$VAR_SESSION = Session::getInstance();
if($VAR_SESSION->email=="" || $VAR_SESSION->loggedin!=true){

    header("Location:./");
}
$product_id=$_GET['product_id'];
$sql="SELECT COUNT(*) FROM products WHERE id='$product_id'";
$result=$cls->consulQuery($sql);
if($result[0]==0){
    header("Location:./?view=products");
}else{
    $sql="SELECT type FROM products WHERE id='$product_id'";
    $result=$cls->consulQuery($sql);
    if($result[0]=='ACCESORIO'){
      header("Location:./?view=product-accesorios-edit&product_id=$product_id");

    }
}

$products="active";
$activesublink_products="active-sublink";


?>


<!--
=========================================================
Material Dashboard - v2.1.2
=========================================================

Product Page: https://www.creative-tim.com/product/material-dashboard
Copyright 2020 Creative Tim (https://www.creative-tim.com)
Coded by Creative Tim

=========================================================
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="shortcut icon" href="Views/assets_login/images/favicon-01-ol.ico">

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Productos | Olimpoathletics
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="Views/assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="Views/assets/demo/demo.css" rel="stylesheet" />
  <style>
          #ht-preloader { background: #ffffff; bottom: 0; height: 100%; left: 0; overflow: hidden !important; position: fixed; right: 0; text-align: center; top: 0; width: 100%; z-index: 99999; }
.clear-loader { transform: translateX(-50%) translateY(-50%); -webkit-transform: translateX(-50%) translateY(-50%); -o-transform: translateX(-50%) translateY(-50%); -ms-transform: translateX(-50%) translateY(-50%); -moz-transform: translateX(-50%) translateY(-50%); z-index: 999; box-sizing: border-box; display: inline-block; left: 50%; position: absolute; text-align: center; top: 50%; }
.loader { position: absolute; top: 50%; left: 50%; margin: auto; text-align: center; transform: translateX(-50%) translateY(-50%); -webkit-transform: translateX(-50%) translateY(-50%); -o-transform: translateX(-50%) translateY(-50%); -ms-transform: translateX(-50%) translateY(-50%); -moz-transform: translateX(-50%) translateY(-50%); }
.loader span { width: 20px; height: 20px; background-color: #f85438; border-radius: 50%; display: inline-block; animation: motion 3s ease-in-out infinite; }
.loader p { color: #fe4c1c; margin-top: 5px; font-size: 30px; animation: shake 5s ease-in-out infinite; }



     img.img-fluid.simple-text.logo-normal {
        height: 80px !important;
        width: 88% !important;
    }
    .sidebar .logo .simple-text{

            padding: 0px 0px !important;
    }
    .sidebar[data-color="purple"] li.active>a{
            background-color: #fbca08 !important;

    }
    .sidebar .nav li.active>[data-toggle="collapse"] i{
        color: #fff !important;

    }
    .sidebar .nav li.active>a, .sidebar .nav li.active>a i{
        color: #fff !important;
    }
    .btn.btn-primary{
      background-color: #fbca08 !important;
      border-color: #fbca08 !important;
    }
    .btn.btn-primary:focus, .btn.btn-primary.focus, .btn.btn-primary:hover{

       background-color: #fbca08 !important;
       border-color: #fbca08 !important;
    }

    li.nav-item.active-sublink {
        background-color: rgba(200, 200, 200, 0.2);
    }
    .btn.btn-primary.btn-link {
        color:#fff !important;
    }


    .logo {
        background: #fff !important;
    }

    .sidebar-wrapper {
        background: #fff !important;
    }


  .fileinput-exists .fileinput-new,.fileinput-new .fileinput-exists {
    display: none;
}
.fileinput-new.input-group .btn-file,.fileinput-new .input-group .btn-file {
    border-radius: 0 4px 4px 0;
}

.fileinput-new.input-group .btn-file.btn-sm,.fileinput-new .input-group .btn-file.btn-sm,.fileinput-new.input-group .btn-file.btn-xs,.fileinput-new .input-group .btn-file.btn-xs,.fileinput-new.input-group .btn-group-sm>.btn-file.btn,.fileinput-new .input-group .btn-group-sm>.btn-file.btn {
    border-radius: 0 3px 3px 0;
}

.fileinput-new.input-group .btn-file.btn-lg,.fileinput-new .input-group .btn-file.btn-lg,.fileinput-new.input-group .btn-group-lg>.btn-file.btn,.fileinput-new .input-group .btn-group-lg>.btn-file.btn {
    border-radius: 0 6px 6px 0;
}

.btn-file>input{
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    font-size: 23px;
    cursor: pointer;
    filter: alpha(opacity=0);
    opacity: 0;
    direction: ltr;
}

.fileinput .thumbnail>img {
    max-height: 100%;
    width: 100%;
}
  .fileinput-preview.fileinput-exists.thumbnail {
    height: 148px !important;
}
.fileinput-new.thumbnail {
    height: 148px !important;
}


  </style>
</head>

<body class="">
    <div id="ht-preloader">
      <div class="loader clear-loader">
        <img class="img-fluid" src="Views/assets_login/images/loader.gif" alt="">
      </div>
    </div>

  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo">

        <img class="img-fluid simple-text logo-normal" src="Others/Files_site/logo-v02.png" alt="">

        </div>

      <?php include "menu.php";?>

    </div>
    <div class="main-panel">
      <!-- Navbar -->

     <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Productos</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">

            <ul class="navbar-nav">


            </ul>
          </div>
        </div>
      </nav>

      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">

            <div class="col-8 col-lg-1"></div>

            <div class="col-md-8">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Producto # <?php echo $product_id;?></h4>
                  </div>
                    <a href="http://olimpoathletics.com/single-product/<?php echo $product_id;?>" target="_blank" class="btn btn-info pull-right"><i class="material-icons">remove_red_eye</i>Ver producto en la website </a>
                </div>
                <div class="card-body ">

                    <?php
                     $sql="SELECT * FROM products WHERE id='$product_id'";
                     $item= (object)$cls->consulQuery($sql);
                     $ptype=$item->type;
                     $pprice=$item->price;

                    ?>

                  <form method="get" action="/" class="form-horizontal" id="formprofile">
                        <input type="hidden" id="product_id" name="product_id" value="<?php echo $product_id;?>">
                        <input type="hidden" name="a" value="UPDATE-PRODUCT-INFO">


                     <h4>Datos Generales</h4>
                     <hr/>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Nombre</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">
                          <input type="text" class="form-control validate" name="product_name" value="<?php echo ucwords($item->name);?>" id="product_name" placeholder="Nombre">
                          <small  class="form-text text-muted product_name-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">Descripción</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                          <textarea class="form-control validate" rows="5" name="descripcion" id="descripcion" placeholder="Descripcion"><?php echo ucwords($item->description);?></textarea>
                          <small  class="form-text text-muted descripcion-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>
                    <?php if($item->type =='PRODUCT'){ ?>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Categoría</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                             <select class="form-control validate" name="category" id="category">
                              <option value="">-Seleccione-</option>
                              <?php
                               $sql2="SELECT * FROM products_category WHERE status='ACTIVE' and type='$ptype'";
                               $result_lis2= $cls->consultListQuery($sql2);//query
                            	foreach($result_lis2 as $item2)
                            	{
                            	   $selected="";
                            	   if($item->category==$item2->id){
                            	     $selected="selected";

                            	   }
                            	  ?>
                            	     <option value="<?php echo $item2->id;?>"<?php echo $selected;?> ><?php echo ucwords($item2->name);?></option>

                            	<?php }?>


                            </select>
                          <small  class="form-text text-muted category-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Tipo</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                          <p class="form-control-static"><?php echo $item->type;?></p>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">Status</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                             <select class="form-control validate" name="status" id="status">


                              <option value="ACTIVE" <?php if($item->status=="ACTIVE"){echo "selected";}?>>ACTIVE</option>
                              <option value="INACTIVE" <?php if($item->status=="INACTIVE"){echo "selected";}?>>INACTIVE</option>


                            </select>
                          <small  class="form-text text-muted status-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">Precio</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                            <input type="number" class="form-control validate"  min="0" max="4" step="0.2" value="<?php echo $item->price;?>" name="price" id="price"/>

                          <small  class="form-text text-muted price-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>


                    <div class="row">
                      <label class="col-sm-2 col-form-label">Itbms</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                             <select class="form-control validate" name="itbms" id="itbms">


                              <option value="0.00" <?php if($item->price=="0.00"){echo "selected";}?>>0.00%</option>
                              <option value="0.07" <?php if($item->price=="0.07"){echo "selected";}?>>0.07%</option>
                              <option value="0.10" <?php if($item->price=="0.10"){echo "selected";}?>>0.10%</option>
                              <option value="0.15" <?php if($item->price=="0.15"){echo "selected";}?>>0.15%</option>

                            </select>
                          <small  class="form-text text-muted itbms-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">Habilitar Reviews</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                             <select class="form-control validate" name="available_reviews" id="available_reviews">


                              <option value="YES" <?php if($item->reviewed=="YES"){echo "selected";}?>>YES</option>
                              <option value="NO" <?php if($item->reviewed=="NO"){echo "selected";}?>>NO</option>

                            </select>
                          <small  class="form-text text-muted available_reviews-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">PRE-SALE</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                             <select class="form-control " name="pre_sale" id="pre_sale">

                              <option value="NO" <?php if($item->pre_sale=="NO"){echo "selected";}?>>NO</option>
                              <option value="YES" <?php if($item->pre_sale=="YES"){echo "selected";}?>>YES</option>

                            </select>
                        </div>
                      </div>
                    </div>

                    <div <?php if($item->pre_sale=="NO"){?> style="display: none;" <?php }?>  id="div_days_to_pre_sale">
                      
                      <div class="row" >

                      <label class="col-sm-2 col-form-label">Fecha de PRE-SALE</label>
                          <div class="col-sm-9">
                            <div class="form-group bmd-form-group">
                                  <?php $date = date_create($item->days_to_pre_sale);
                                        $datePreSale ="";
                                        if($item->pre_sale=="YES"){
                                          $datePreSale =  date_format($date,"Y-m-d");
                                        }
                                  ?>  

                                 <input type="date" id="days_to_pre_sale" name="days_to_pre_sale" class="form-control " 
                                 value="<?php echo $datePreSale; ?>" placeholder ="<?php echo $datePreSale; ?>">

                            </div>
                          </div>
                        </div>

                    </div>
                    


                     <h4>Imagenes del Producto (480X480)</h4>
                     <hr/>


                <div class="row">


                   <?php
                   $rowpht=0;
                   $sqlphot="SELECT * FROM products_files WHERE product_id='$product_id'";
                   $result_lis=$cls->consultListQuery($sqlphot);

                   foreach($result_lis as $item)
                    {$rowpht++;?>
                	    <div class="col-md-4 col-sm-4">

                          <div class="fileinput text-center fileinput-exists" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                 <img src="Views/assets/img/image_placeholder.jpg" alt="...">
                            </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" id="div-<?php echo $rowpht;?>">
                                   <?php
                                    $urlphoto="Views/assets/img/image_placeholder.jpg";
                                    if($item->urlphoto_big!=''){
                                        $urlphoto="https://www.admin.olimpoathletics.com/Others/Files_products/$product_id/$item->urlphoto_big";
                                    }?>
                                  <img src="<?php echo $urlphoto;?>" alt="...">
                                 <input type="hidden" name="currentimage[]" value="<?php echo $item->id;?>" >

                                </div>


                            <div>
                              <!--<span class="btn btn-rose btn-round btn-file">
                                <span class="fileinput-new">Select image</span>
                                 <span class="fileinput-exists">Change</span>
                                <input type="file" name="uploadofile[]" accept=".png, .jpg, .jpeg">
                              </span>-->
                              <a href="#img-<?php echo $rowpht;?>" class="btn btn-danger btn-round fileinput-exists remove-image" id="<?php echo $item->id;?>-<?php echo $product_id;?>"><i class="fa fa-times"></i> Remove</a>

                              <a href="#" class="fileinput-new" >
                                <span class="btn btn-rose btn-round btn-file">
                                  <span class="fileinput-new"><i class="fa fa-plus"></i> Select image</span>
                                  <!--<span class="fileinput-exists">Change</span>-->
                                  <input type="file" name="uploadofile[]" accept=".png, .jpg, .jpeg">
                                </span>
                              </a>

                            </div>


                          </div>
                        </div>
                    <?php }?>



                     <?php for ($i=$rowpht;$i<5;$i++){?>

                        <div class="col-md-4 col-sm-4">

                          <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">

                              <img src="Views/assets/img/image_placeholder.jpg" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <div>
                              <!--<span class="btn btn-rose btn-round btn-file">
                                <span class="fileinput-new">Select image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="uploadofile[]" accept=".png, .jpg, .jpeg">
                              </span>-->
                              <a href="#img-mis-<?php echo $i;?>" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>

                              <a href="#" class="fileinput-new" >
                                <span class="btn btn-rose btn-round btn-file">
                                  <span class="fileinput-new"><i class="fa fa-plus"></i> Select image</span>
                                  <!--<span class="fileinput-exists">Change</span>-->
                                  <input type="file" name="uploadofile[]" accept=".png, .jpg, .jpeg">
                                </span>
                              </a>
                            </div>
                          </div>
                        </div>


                    <?php }?>



                  </div>



                  <h4>Imagenes del Producto Portada (1920X1080)</h4>
                  <hr/>

                <div class="row">

                   <?php
                   $rowpht2=0;
                   $sqlphot2="SELECT * FROM products WHERE id='$product_id' and img_slider_promo!='' ";
                   $result_lis2=$cls->consultListQuery($sqlphot2);

                   foreach($result_lis2 as $item2)
                    {$rowpht2++;?>
                      <div class="col-md-4 col-sm-4">

                          <div class="fileinput text-center fileinput-exists" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                 <img src="Views/assets/img/image_placeholder.jpg" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail">
                               <?php
                                $urlphoto="Views/assets/img/image_placeholder.jpg";
                                if($item2->img_slider_promo!=''){
                                    $urlphoto="https://www.admin.olimpoathletics.com/Others/Files_products/$product_id/$item2->img_slider_promo";
                                }?>
                              <img src="<?php echo $urlphoto;?>" alt="...">
                             <input type="hidden" name="currentimage2[]" value="<?php echo $item2->id;?>" >

                            </div>
                            <div>
                              <!--<span class="btn btn-rose btn-round btn-file">
                                <span class="fileinput-new">Select image</span>
                                 <span class="fileinput-exists">Change</span>
                                <input type="file" name="uploadofile2[]" accept=".png, .jpg, .jpeg">
                              </span>-->
                              <a href="#img-<?php echo $rowpht2;?>" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>

                              <a href="#" class="fileinput-new" >
                                <span class="btn btn-rose btn-round btn-file">
                                  <span class="fileinput-new"><i class="fa fa-plus"></i> Select image</span>
                                  <!--<span class="fileinput-exists">Change</span>-->
                                  <input type="file" name="uploadofile2[]" accept=".png, .jpg, .jpeg">
                                </span>
                              </a>

                            </div>
                          </div>
                        </div>
                    <?php }?>



                     <?php for ($i=$rowpht2;$i<1;$i++){?>

                        <div class="col-md-4 col-sm-4">

                          <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">

                              <img src="Views/assets/img/image_placeholder.jpg" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <div>
                              <!--<span class="btn btn-rose btn-round btn-file">
                                <span class="fileinput-new">Select image</span>
                                  <span class="fileinput-exists">Change</span>
                                <input type="file" name="uploadofile2[]" accept=".png, .jpg, .jpeg">
                              </span>-->
                              <a href="#img-mis-<?php echo $i;?>" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>

                              <a href="#" class="fileinput-new">
                                <span class="btn btn-rose btn-round btn-file">
                                  <span class="fileinput-new"><i class="fa fa-plus"></i> Select image</span>
                                  <!--<span class="fileinput-exists">Change</span>-->
                                  <input type="file" name="uploadofile2[]" accept=".png, .jpg, .jpeg">
                                </span>
                              </a>
                            </div>
                          </div>
                        </div>


                    <?php }?>



                  </div>
                     <h4>Disponibilidad del Producto</h4>
                     <hr/>
                     <?php
                      $rowstalla=0;
                     ?>

                      <?php if($ptype!='PESAS'){?>
                       <div class="row">
                         <label class="col-sm-3 col-form-label">Tallas del Producto</label>

                        <div class="form-group bmd-form-group col-sm-5">
                             <div class="row ">
                             <?php
                               $sql3="SELECT * FROM products_tallas WHERE product_id='$product_id'";
                               $result_lis3= $cls->consultListQuery($sql3);//query
                            	foreach($result_lis3 as $item3)
                            	{
                            	$rowstalla++;
                            	?>

                                  	<div class="form-check disabled col-sm-3">
                                        <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="" disabled="" checked=""> <?php echo $item3->talla;?>
                                        <span class="form-check-sign">
                                          <span class="check"></span>
                                        </span>
                                      </label>
                                     </div>

                            	<?php }?>
                            	</div>
                        <button type="submit" class="btn btn-primary pull-right btn-add-talla"><i class="material-icons">add</i>Agregar Talla<div class="ripple-container"></div></button>
                      </div>
                       </div>

                       <?php }else {?>

                         <div class="row">
                        <label class="col-sm-3 col-form-label">Libras del Producto</label>

                          <div class="form-group bmd-form-group col-sm-5">
                               <div class="row ">
                               <?php
                                 $sql3="SELECT * FROM products_libras WHERE product_id='$product_id'";
                                 $result_lis3= $cls->consultListQuery($sql3);//query
                               foreach($result_lis3 as $item3)
                               {
                               $rowstalla++;
                               ?>

                                     <div class="form-check disabled col-sm-3">
                                          <label class="form-check-label">
                                          <input class="form-check-input" type="checkbox" value="" disabled="" checked=""> <?php echo $item3->libras;?>
                                          <span class="form-check-sign">
                                            <span class="check"></span>
                                          </span>
                                        </label>
                                       </div>

                               <?php }?>
                               </div>
                          <button type="submit" class="btn btn-primary pull-right btn-add-libras"><i class="material-icons">add</i>Agregar Libras<div class="ripple-container"></div></button>
                        </div>
                         </div>

                       <?php }?>

                    <?php if($rowstalla>0){?>
                      <div class="row">

                      <?php if($ptype!='PESAS'){?>
                        <label class="col-sm-4 col-form-label">Disponibilidad por Talla</label>

                        <div class="form-group bmd-form-group col-sm-7">
                        <div class="table-responsive">
                          <table class="table /* table-responsive*/">
                            <thead class=" text-primary">

                             <tr>
                                <th>Talla</th>
                                <th>Disponibilidad</th>
                                <th>Status</th>

                            </tr>
                            </thead>
                            <tbody>
                          <?php
                             $sql4="SELECT * FROM products_tallas WHERE product_id='$product_id'";
                             $result_lis4= $cls->consultListQuery($sql4);//query
                             foreach($result_lis4 as $item4)
                             {?>

                             <tr>


                               <td><?php echo $item4->talla;?>
                               <input type="hidden" name="tallaname[]" value="<?php echo $item4->talla;?>">
                                </td>
                               <td>

                               <input type="number" min="0" class="form-control" name="tallaavailable[]" value="<?php echo $cls->getAvailableProduct($product_id,$item4->talla);?>">
                               </td>
                               <td>
                                   <select name="tallastatus[]" class="form-control">
                                        <option value="ACTIVE" <?php if($item4->status=='ACTIVE'){echo "selected";}?>>ACTIVE</option>
                                        <option value="INACTIVE" <?php if($item4->status=='INACTIVE'){echo "selected";}?>>INACTIVE</option>

                                      </select>
                                  </td>

                              </tr>

                           <?php }?>




                            </tbody>
                          </table>
                        </div>



                            </div>

                        <?php }else {?>
                          <label class="col-sm-4 col-form-label">Disponibilidad por Libras</label>

                          <div class="form-group bmd-form-group col-sm-7">
                          <div class="table-responsive">
                            <table class="table /* table-responsive*/">
                              <thead class=" text-primary">

                               <tr>
                                  <th>Libras</th>
                                  <th>Disponibilidad</th>
                                  <th>Precio</th>
                                  <th>Status</th>

                              </tr>
                              </thead>
                              <tbody>
                            <?php
                               $sql4="SELECT * FROM products_libras WHERE product_id='$product_id'";
                               $result_lis4= $cls->consultListQuery($sql4);//query
                               foreach($result_lis4 as $item4)
                               {?>

                               <tr>


                                 <td><?php echo $item4->libras;?>
                                 <input type="hidden" name="librasname[]" value="<?php echo $item4->libras;?>">
                                  </td>
                                 <td>

                                 <input type="number" min="0" class="form-control" name="librasavailable[]" value="<?php echo $cls->getAvailableProductLibras($product_id,$item4->libras);?>">
                                 </td>
                                 <td>

                                 <input type="number" min="0" class="form-control" value="<?php echo number_format(($item4->libras*$pprice),2);?>" readonly>
                                 </td>


                                 <td>
                                     <select name="librastatus[]" class="form-control">
                                          <option value="ACTIVE" <?php if($item4->status=='ACTIVE'){echo "selected";}?>>ACTIVE</option>
                                          <option value="INACTIVE" <?php if($item4->status=='INACTIVE'){echo "selected";}?>>INACTIVE</option>

                                        </select>
                                    </td>

                                </tr>

                             <?php }?>




                              </tbody>
                            </table>
                          </div>



                              </div>
                        <?php }?>


                          </div>
                    <?php }else{?>


                        <div class="alert alert-danger">

                                <span>
                                      <?php if($ptype!='PESAS'){?>
                                    <b> Alerta - </b> Debe agregar talla para darle disponibilidad por tallas.</span>
                                      <?php }else{?>
                                        <b> Alerta - </b> Debe agregar libras para darle disponibilidad por libras.</span>


                                      <?php }?>
                         </div>
                    <?php }?>


                    <?php
                    $sqlcarr="SELECT count(*) from products t1 join carrito_details t2 on t1.id=t2.product_id join carrito t3 on t2.carrito_id=t3.id where t1.id='$product_id' and t3.status='ACTIVE'";
                    $resulcarr=$cls->consulQuery($sqlcarr);
                    if($resulcarr[0]==0){?>
                          <button type="submit" class="btn btn-primary pull-right btn-update-product">Actualizar Producto</button>
                          <div class="clearfix"></div>

                    <?php }else{?>
                              <div class="alert alert-danger">

                                <span>
                                  <b> Alerta - </b> Nose puede editar este producto porque esta activo en un carrito.</span>
                              </div>

                    <?php }?>



                  </form>
                </div>
              </div>


            </div>

          </div>



        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>

              </li>
              <li>

              </li>
              <li>

              </li>
              <li>

              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
              Copyright
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>
            olimpoathletics Todos los derechos reservados.
          </div>
        </div>
      </footer>
    </div>
  </div>

  <!--   Core JS Files   -->
  <script src="Views/assets/js/core/jquery.min.js"></script>
  <script src="Views/assets/js/core/popper.min.js"></script>
  <script src="Views/assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="Views/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="Views/assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="Views/assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="Views/assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->


  <!--
  Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/4.0.0/js/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->

  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="Views/assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="Views/assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="Views/assets/js/plugins/arrive.min.js"></script>



  <!--  Notifications Plugin    -->
  <script src="Views/assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="Views/assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="Views/assets/demo/demo.js"></script>

  <script src="Views/assets/js/admin-js.js?id=15"></script>

  <script>
    $(document).ready(function() {



      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();

    });
     $(window).on('load', function() {
         preloader();

      

      });
        function preloader() {
           $('#ht-preloader').fadeOut();
        };


  </script>
  <script>

  $('#pre_sale').on('change', function(event) {
      var value  = this.value;
      if(value == 'YES'){

        document.getElementById("div_days_to_pre_sale").style.display="block";
      }else{

        document.getElementById("div_days_to_pre_sale").style.display="none";

      }


  })

  $('.bigimage').on('click', function(event) {
      event.preventDefault();
       var idl=this.id;

      var str = idl;
      var res = str.replace("myImg-", "");

      var modal = document.getElementById("myModal-"+res);
      var modalImg = document.getElementById("img01-"+res);
      var captionText = document.getElementById("caption-"+res);

     modal.style.display = "block";
     img = document.getElementById(idl);
     modalImg.src = img.src;
     captionText.innerHTML = img.alt;

      // Get the image and insert it inside the modal - use its "alt" text as a caption


        // Get the <span> element that closes the modal


  });

        $('.close').on('click', function(event) {
            event.preventDefault();
            var id=this.id;
            $("#myModal-"+id).css("display","none");
        });
        // When the user clicks on <span> (x), close the modal

      // Get the modal


  </script>
</body>

</html>
