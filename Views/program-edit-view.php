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

if(!isset($_GET['programid']) || $_GET['programid']==''){

  header("Location:./?view=program");
}
$program="active";
$activesublink1_program="active-sublink";


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
  Editar Programa | Olimpoathletics
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="Views/assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="Views/assets/demo/demo.css" rel="stylesheet" />
    <script src="//cdn.ckeditor.com/4.15.1/full/ckeditor.js"></script>

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

#myProgress {
width: 100%;
background-color: #F2F3F4;
}

#myBar {
width: 1%;
height: 30px;
background-color: #F1C40F;
}
.modal-body{
  height: 450px !important;
  width: 100% !important;
  overflow-y: auto !important;
}
.modal-content{

  width: 200% !important;
  height: 582px !important;
}
#myModal-1{
  padding-right: 24% !important;
}
  </style>
</head>

<body class="">
    <div id="ht-preloader">
      <div class="loader clear-loader">
        <img class="img-fluid" src="Views/assets_login/images/loader.gif" alt="">

        <div id="myBar" style="display:none;"></div></div>

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
            <a class="navbar-brand" href="javascript:;">Editar Programa</a>
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

            <div class="col-md-9">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <?php
                      $programid=$_GET['programid'];
                    ?>
                    <h4 class="card-title">Editar Programa <?php echo $programid;?></h4>
                  </div>
                    <a href="http://olimpoathletics.com/single-program/<?php echo $programid;?>" target="_blank" class="btn btn-info pull-right"><i class="material-icons">remove_red_eye</i>Ver programa en la website </a>
                </div>
                <div class="card-body ">

                  <?php
                  $sql="SELECT * FROM program_section WHERE id='$programid' limit 1";
                  $resul= (object)$cls->consulQuery($sql);

                  ?>

                  <form method="post" action="/" class="form-horizontal" id="formprofile">

                        <input type="hidden" name="a" value="EDIT-PROGRAM-INFO">
                        <input type="hidden" name="id" value="<?php echo $programid;?>">



                     <h4>Datos Generales</h4>
                     <hr/>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Nombre</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">
                          <input type="text" class="form-control validate" name="product_name" value="<?php echo $resul->name;?>" id="product_name" placeholder="Nombre">
                          <small  class="form-text text-muted product_name-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">Status</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                             <select class="form-control validate" name="status" id="status">


                              <option value="ACTIVE"<?php if($resul->status=='ACTIVE'){echo "selected";}?> >ACTIVE</option>
                              <option value="INACTIVE"<?php if($resul->status=='INACTIVE'){echo "selected";}?> >INACTIVE</option>


                            </select>
                          <small  class="form-text text-muted status-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">Precio</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">

                            <input type="number" class="form-control validate"  min="0" max="4" step="0.2" value="<?php echo $resul->price;?>" name="price" id="price"/>

                          <small  class="form-text text-muted price-error" style="color:red !important;"></small>
                        </div>
                      </div>
                    </div>


                    <div class="row">
                      <label class="col-sm-2 col-form-label">Introducción</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">


                            <textarea name="editor1" id="editor1" ><?php if(isset($resul->introduccion)){echo base64_decode($resul->introduccion);}?></textarea>


                <input type="hidden" name="introduccion" value="" id="introduccion" >



                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">Objetivos</label>
                      <div class="col-sm-10">
                        <div class="form-group bmd-form-group">


                          <textarea name="editor2" id="editor2" ><?php if(isset($resul->objective)){echo base64_decode($resul->objective);}?></textarea>

                          <input type="hidden" name="objective" value="" id="objective" >

                        </div>
                      </div>
                  </div>


                  <div class="row">
                    <label class="col-sm-2 col-form-label">Calentamiento</label>
                    <div class="col-sm-10">
                      <div class="form-group bmd-form-group">


                        <textarea name="editor3" id="editor3"><?php if(isset($resul->calentamiento)){echo base64_decode($resul->calentamiento);}?></textarea>


                        <input type="hidden" name="calentamiento" value="" id="calentamiento" >



                      </div>
                    </div>
                </div>
                <div class="row">

                  <label class="col-sm-2 col-form-label">Dias del Programa</label>

                  <div class="col-sm-10">
                    <a href="#" class="btn btn-info" onclick="ShowModal('myModal-1','','Agregar Dia de Programa','ADD-DAYS-PROGRAM','','')"><i class="material-icons">add</i>Agregar</a>


                    <div class="form-group bmd-form-group">

                      <table class="table table-bordered  table-hover table-responsive">
                          <thead>

                          </thead>
                          <tbody>

                            <?php
                              $col=0;
                              $abre="<tr>";
                              $rowsDays=0;
                              $cierra="</tr>";
                                $itm_c="SELECT t2.days,t2.name,t2.status,t2.content,t2.id as routine_id from program_section t1 join program_section_routine t2 on t1.id=t2.section_id where t1.id= '$programid'  order by t2.days asc  ";

                                $resl= $cls->consultListQuery($itm_c);//query

                              foreach($resl as $itm)
                              {
                                  $rowsDays++;
                                if($col==0){

                                      echo $abre;
                                }


                                $color="table-warning";
                                $link="myModalDaysEdit-".$rowsDays;


                                if($itm->status=='INACTIVE'){

                                    $color="table-dark";
                                    $link="";
                                }

                                ?>

                                  <td class="<?php echo $color;?>">



                                       <strong>Dia <?php echo $itm->days;?></strong> <br>


                                          <a href="#"  onclick="ShowModal('myModal-1','<?php echo $itm->name;?>', 'Editar Dia de Programa','EDIT-DAYS-PROGRAM','<?php echo $itm->routine_id;?>','<?php if($itm->days==$cls->getActualProgramDays($programid)){echo "YES";}else{echo "NO";}?>')"><span class="badge badge-warning"><i class="las las la-arrow-right"></i> <?php echo $itm->name;?></span></a>


                                          <!-- Modal HTML -->

                                   </td>

                                   <?php
                                   if($col==6){
                                         $col=0;
                                         echo $cierra;
                                   }else{
                                     $col++;
                                   }
                               }?>


                          </tbody>
                        </table>



                    </div>
                  </div>

                </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">Glosario</label>
                      <button type="button" class="btn btn-info  add-glosary-links"><i class="material-icons">add</i>Agregar</button>

                      <div class="col-sm-10">

                        <div class="form-group bmd-form-group">


                          <table class="table table-responsive">
                            <thead class=" text-primary">
                              <tr>
                            <th>Nombre</th>
                            <th>Link</th>
                            <th>Eliminar</th>


                            </tr>
                            </thead>
                            <tbody class="gloasaryContent">
                              <?php
                              $rowsGlos=0;
                              $sqlGlos="SELECT count(*) from program_glosary WHERE section_id='$programid'";
                              $resulG=$cls->consulQuery($sqlGlos);
                              if($resulG[0]>0){

                              $sqlGlos="SELECT * from program_glosary WHERE section_id='$programid'";
                              $sqlGlos_r= $cls->consultListQuery($sqlGlos);//query

                              foreach($sqlGlos_r as $itemG)
                              {
                                ?>
                                  <tr id="ln-<?php echo $rowsGlos;?>">
                                    <input type="hidden" name="idGlosary[]" value="<?php echo $itemG->id;?>">

                                    <td><input type="hidden" name="namesGlosary[]" value="<?php echo $itemG->name;?>"><?php echo $itemG->name;?></td>
                                    <td><input type="hidden" name="linksGlosary[]" value="<?php echo $itemG->link;?>"> <?php echo $itemG->link;?></td>
                                    <td>
                                      <button type="button" class="btn btn-danger pull-right" onclick="deleteLine('ln-<?php echo $rowsGlos;?>')"><i class="material-icons">clear</i></button>
                                    </td>
                                  </tr>


                             <?php   $rowsGlos++;

                              }

                           }?>



                            </tbody>
                            <input type="hidden" name="rowsGlosario" value="<?php echo $rowsGlos;?>" id="rowsGlosario" >

                          </table>
                        </div>
                      </div>
                  </div>

                  <div class="row">
                    <label class="col-sm-2 col-form-label">Imagen del Programa List (270x433)</label>


                    <?php
                    $rowpht=0;?>


                      <?php for ($i=$rowpht;$i<1;$i++){?>

                         <div class="col-md-4 col-sm-4">

                           <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                             <div class="fileinput-new thumbnail">
                              <?php if(isset($resul->photo_section) && $resul->photo_section!='' )
                              {?>
                                 <img src="https://www.admin.olimpoathletics.com/Others/program/<?php echo $resul->id.'/'.$resul->photo_section;?>" alt="...">

                              <?php }else {?>


                                <img src="Views/assets/img/image_placeholder.jpg" alt="...">
                              <?php }?>


                             </div>
                             <div class="fileinput-preview fileinput-exists thumbnail"></div>
                             <div>
                               <span class="btn btn-rose btn-round btn-file">
                                 <span class="fileinput-new">Select image</span>
                                 <span class="fileinput-exists">Change</span>
                                 <input type="file" name="uploadofile[]" accept=".png, .jpg, .jpeg">
                               </span>
                               <a href="#img-mis-<?php echo $i;?>" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                             </div>
                           </div>
                         </div>


                     <?php }?>

                  </div>

                  <div class="row">
                    <label class="col-sm-2 col-form-label">Imagen del Programa Single (770X369)</label>


                    <?php
                    $rowpht2=0;?>


                      <?php for ($i=$rowpht2;$i<1;$i++){?>

                         <div class="col-md-4 col-sm-4">

                           <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                             <div class="fileinput-new thumbnail">
                              <?php if(isset($resul->photo_section_single) && $resul->photo_section_single!='' )
                              {?>
                                 <img src="https://www.admin.olimpoathletics.com/Others/program/<?php echo $resul->id.'/'.$resul->photo_section_single;?>" alt="...">

                              <?php }else {?>


                                <img src="Views/assets/img/image_placeholder.jpg" alt="...">
                              <?php }?>


                             </div>
                             <div class="fileinput-preview fileinput-exists thumbnail"></div>
                             <div>
                               <span class="btn btn-rose btn-round btn-file">
                                 <span class="fileinput-new">Select image</span>
                                 <span class="fileinput-exists">Change</span>
                                 <input type="file" name="uploadofile2[]" accept=".png, .jpg, .jpeg">
                               </span>
                               <a href="#img-mis-<?php echo $i;?>" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                             </div>
                           </div>
                         </div>


                     <?php }?>

                  </div>

                  <div class="row">
                    <label class="col-sm-2 col-form-label">Imagen del Programa Portada (1920X1080)</label>


                    <?php
                    $rowpht3=0;?>


                      <?php for ($i=$rowpht3;$i<1;$i++){?>

                         <div class="col-md-4 col-sm-4">

                           <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                             <div class="fileinput-new thumbnail">
                              <?php if(isset($resul->img_slider_promo) && $resul->img_slider_promo!='' )
                              {?>
                                 <img src="https://www.admin.olimpoathletics.com/Others/program/<?php echo $resul->id.'/'.$resul->img_slider_promo;?>" alt="...">

                              <?php }else {?>


                                <img src="Views/assets/img/image_placeholder.jpg" alt="...">
                              <?php }?>


                             </div>
                             <div class="fileinput-preview fileinput-exists thumbnail"></div>
                             <div>
                               <span class="btn btn-rose btn-round btn-file">
                                 <span class="fileinput-new">Select image</span>
                                 <span class="fileinput-exists">Change</span>
                                 <input type="file" name="uploadofile3[]" accept=".png, .jpg, .jpeg">
                               </span>
                               <a href="#img-mis-<?php echo $i;?>" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                             </div>
                           </div>
                         </div>


                     <?php }?>

                  </div>

                          <button type="submit" class="btn btn-primary pull-right btn-update-product">Editar Programa</button>
                          <div class="clearfix"></div>

                  </form>
                </div>
              </div>
            </div>

          </div>

          <div id="myModal-1" class="modal fade">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title " id="modaltitle"></h5>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <form method="post" action="/" class="form-horizontal" id="form-formprofileADD">
                          <input type="hidden" name="a" id="valueModalA" value="">
                          <input type="hidden" name="routine_id" id="routine_id" value="">
                          <input type="hidden" name="id" value="<?php echo $programid;?>">

                          <div class="col-sm-10">
                            <div class="form-group bmd-form-group">
                              <input type="text" class="form-control " name="name" value="" id="name" placeholder="Nombre...">
                              <small  class="form-text text-muted name-error" style="color:red !important;"></small>
                            </div>
                          </div>
                           <br>

                            <h4>Instrucciones</h4>
                          <textarea name="editor5" id="editor5"></textarea>
                          <input type="hidden" name="daysprogramintrucction" value="" id="daysprogramintrucction" >
                          <br>

                          <h4>Rutina</h4>

                          <textarea name="editor4" id="editor4"></textarea>
                          <input type="hidden" name="daysprogram" value="" id="daysprogram" >
                            <br>
                          <h4>Glosario</h4>

                          <div  class="glosaryDay">

                            <?php
                            $rowsGlos2=0;
                            $sqlGlos2="SELECT count(*) from program_glosary WHERE section_id='$programid'";
                            $sqlGlos2_r=$cls->consulQuery($sqlGlos2);
                            if($sqlGlos2_r[0]>0){

                                $sqlGlos3="SELECT * from program_glosary WHERE section_id='$programid'";
                                $sqlGlos3_r= $cls->consultListQuery($sqlGlos3);//query

                                foreach($sqlGlos3_r as $itemG2)
                                { $rowsGlos2++;
                                   ?>

                                    <input type="checkbox" id="glodaryDay-<?php echo $rowsGlos2;?>" name="glodaryDay[]" value="<?php echo $itemG2->id;?>">
                                    <label for="glodaryDay-<?php echo $rowsGlos2;?>"><?php echo $itemG2->name;?></label><br>

                                <?php }?>

                            <?php }?>

                          </div>

                            <span class="box-del-btn"></span>
                          <button type="submit" class="btn btn-primary pull-right btn-modal-form" id="formprofileADD">Guardar</button>
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
  <script src="Views/assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->

  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="Views/assets/js/plugins/bootstrap-tagsinput.js"></script>
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

  <script src="Views/assets/js/admin-js.js?id=95"></script>


  <script type="text/javascript">
        //  CKEDITOR.replace( 'editor1' );
      var editor =  CKEDITOR.replace( 'editor1', {
                      toolbar: [
                      { name: 'document', groups: [ 'mode', 'document', 'doctools' ],
                       items: ['Print', '-' ] },
                      { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                      { name: 'editing', groups: [ 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },

                      '/',
                      { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                      { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                      { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                      { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak' ] },
                      '/',
                      { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                      { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                      { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                      { name: 'others', items: [ '-' ] },
                      { name: 'about', items: [ 'About' ] }
                    ]
                });

      //    var editor = CKEDITOR.replace( 'editor1');


          var editor_2 = CKEDITOR.replace( 'editor2', {
                          toolbar: [
                          { name: 'document', groups: [ 'mode', 'document', 'doctools' ],
                           items: ['Print', '-' ] },
                          { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                          { name: 'editing', groups: [ 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },

                          '/',
                          { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                          { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                          { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                          { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak' ] },
                          '/',
                          { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                          { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                          { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                          { name: 'others', items: [ '-' ] },
                          { name: 'about', items: [ 'About' ] }
                        ]
                    });

          var editor_3 = CKEDITOR.replace( 'editor3', {
                          toolbar: [
                          { name: 'document', groups: [ 'mode', 'document', 'doctools' ],
                           items: ['Print', '-' ] },
                          { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                          { name: 'editing', groups: [ 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },

                          '/',
                          { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                          { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                          { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                          { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak' ] },
                          '/',
                          { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                          { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                          { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                          { name: 'others', items: [ '-' ] },
                          { name: 'about', items: [ 'About' ] }
                        ]
                    });
          var editor_4 = CKEDITOR.replace( 'editor4', {
                          toolbar: [
                          { name: 'document', groups: [ 'mode', 'document', 'doctools' ],
                           items: ['Print', '-' ] },
                          { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                          { name: 'editing', groups: [ 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },

                          '/',
                          { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                          { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                          { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                          { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak' ] },
                          '/',
                          { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                          { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                          { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                          { name: 'others', items: [ '-' ] },
                          { name: 'about', items: [ 'About' ] }
                        ]
                    });
          var editor_5 = CKEDITOR.replace( 'editor5', {
                          toolbar: [
                          { name: 'document', groups: [ 'mode', 'document', 'doctools' ],
                           items: ['Print', '-' ] },
                          { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                          { name: 'editing', groups: [ 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },

                          '/',
                          { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                          { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                          { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                          { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak' ] },
                          '/',
                          { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                          { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                          { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                          { name: 'others', items: [ '-' ] },
                          { name: 'about', items: [ 'About' ] }
                        ]
                    });



        //  var __editorName = __main.children('.editor5').attr('id');

          //var editor_5 = CKEDITOR.replaceClass='editor5';

          // The "change" event is fired whenever a change is made in the editor.
          editor.on( 'change', function( evt ) {
              // getData() returns CKEditor's HTML content.
              //console.log( 'Total bytes: ' + evt.editor.getData() );
              document.getElementById('introduccion').value = CKEDITOR.instances['editor1'].getData();
          });
          editor_2.on( 'change', function( evt ) {
              // getData() returns CKEditor's HTML content.
              //console.log( 'Total bytes: '+ CKEDITOR.instances['editor2'].getData());
              document.getElementById('objective').value = CKEDITOR.instances['editor2'].getData();
          });

          editor_3.on( 'change', function( evt ) {
              // getData() returns CKEditor's HTML content.
              //console.log( 'Total bytes: '+ CKEDITOR.instances['editor2'].getData());
              document.getElementById('calentamiento').value = CKEDITOR.instances['editor3'].getData();
          });
          editor_4.on( 'change', function( evt ) {
              // getData() returns CKEditor's HTML content.
              //console.log( 'Total bytes: '+ CKEDITOR.instances['editor2'].getData());
              document.getElementById('daysprogram').value = CKEDITOR.instances['editor4'].getData();
          });
          editor_5.on( 'change', function( evt ) {
              // getData() returns CKEditor's HTML content.
              //console.log( 'Total bytes: '+ CKEDITOR.instances['editor2'].getData());
              document.getElementById('daysprogramintrucction').value = CKEDITOR.instances['editor5'].getData();
          });

          function deleteDay(){

              Swal.fire({
                  title: 'Estas seguro de eliminar dia del programa?',
                  showDenyButton: true,
                  showCancelButton: true,
                  confirmButtonText: `Si`,
                  denyButtonText: `NO`,
                  }).then((result) => {
                  /* Read more about isConfirmed, isDenied below */
                  if (result.value) {
                    console.log('confirmo')
                    $("#ht-preloader").css("display", "block");

                    var xhttp2 = new XMLHttpRequest();
                    xhttp2.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                                console.log(this.responseText);
                                var data=this.responseText;
                                var idx = data.indexOf('OK');

                              if (idx != -1) {
                                    location.reload();
                              }else{
                                $("#ht-preloader").css("display", "none");

                                  swal('Ha ocurrido un error al eliminar');
                              }

                       }
                    };
                    xhttp2.open("GET", "./?action=admin&a=DEL-DAY-PROGRAM&routine_id=<?php echo $programid;?>", true);
                    xhttp2.send();

                  }
               })
          }
          function ShowModal(modalid,name,tittle,valueA,routine,day){
              $('#modaltitle').html(tittle);
            $('#name').val(name);
            $('#valueModalA').val(valueA);
            $('#routine_id').val(routine);


            if(routine!=''){
              if(day=='YES'){
                $(".box-del-btn").html('<button type="button" class="btn btn-danger pull-right btn-modal-del-routine" onclick="deleteDay()">Eliminar</button>');

              }
              $("#ht-preloader").css("display", "block");



                                var xhttp = new XMLHttpRequest();
                                xhttp.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {

                                         CKEDITOR.instances['editor4'].setData(this.responseText);

                                         $('#daysprogram').val(this.responseText);
                                         //$("#ht-preloader").css("display", "none");

                                         //console.log(this.responseText)
                                   }
                                };
                                xhttp.open("GET", "./?action=admin&a=CONSULT-DAYS-PROGRAM&routine_id="+$('#routine_id').val(), true);
                                xhttp.send();

                                var xhttp2 = new XMLHttpRequest();
                                xhttp2.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {

                                         CKEDITOR.instances['editor5'].setData(this.responseText);

                                         $('#daysprogramintrucction').val(this.responseText);
                                        //  $("#ht-preloader").css("display", "none");
                                         //console.log(this.responseText)
                                   }
                                };
                                xhttp2.open("GET", "./?action=admin&a=CONSULT-DAYS-PROGRAM-INTRUCCTION&routine_id="+$('#routine_id').val(), true);
                                xhttp2.send();

                                var xhttp3 = new XMLHttpRequest();
                                xhttp3.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {


                                         $('.glosaryDay').html(this.responseText);
                                         $("#ht-preloader").css("display", "none");
                                         //console.log(this.responseText)
                                   }
                                };
                                xhttp3.open("GET", "./?action=admin&a=CONSULT-DAYS-PROGRAM-GLOSARY&routine_id="+$('#routine_id').val()+'&programid=<?php echo $programid;?>', true);
                                xhttp3.send();

            }else{
              $(".box-del-btn").html('');
              CKEDITOR.instances['editor4'].setData('');
              CKEDITOR.instances['editor5'].setData('');

            }



           $("#"+modalid).modal('show');

          }
      </script>

  <script>
    $(document).ready(function() {


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

      function deleteLine(id){

        $("#"+id).remove();
      }
  </script>
</body>

</html>
