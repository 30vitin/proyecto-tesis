<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';

$purchase = "active";
$requisicion = "active-sublink";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Crear Requisición | Cafeteria
    </title>
    <?php include "styles.php"; ?>
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

                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header card-header-rose card-header-text">
                                <div class="card-text">
                                    <h4 class="card-title">Crear Nueva Requisición </h4>
                                </div>
                            </div>
                            <div class="card-body ">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-primary pull-right btn-send-table-form" data-form="form" data-reset="true"> Crear Requisición</button>

                                    </div>

                                </div>
                                <form  class="form-horizontal" id="form" onkeydown="return event.key != 'Enter';"  action="">


                                    <input type="hidden" name="a" value="CREATE-PURCHASE-REQUEST">

                                    <?php include 'alert-form.php';?>

                                    <h4>Datos Generales</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="col-form-label">Fecha</label>

                                                <div class="form-group bmd-form-group">
                                                    <input type="date" class="form-control validate" name="date" value=""
                                                           id="date" placeholder="Fecha">
                                                    <small class="form-text text-muted date-error"
                                                           style="color:red !important;"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
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

                                                            <option value="<?php echo $item->id;?>"><?php echo $item->name;?></option>

                                                        <?php }?>

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

                                                    <textarea class="form-control" placeholder="Comentario" name="comment">

                                                    </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <style>

                                        #myImg {
                                            border-radius: 5px;
                                            cursor: pointer;
                                            transition: 0.3s;
                                        }

                                        #myImg:hover {opacity: 0.7;}

                                        /* The Modal (background) */
                                        .modal {
                                            display: none; /* Hidden by default */
                                            position: fixed; /* Stay in place */
                                            z-index: 1; /* Sit on top */
                                            padding-top: 100px; /* Location of the box */
                                            left: 0;
                                            top: 0;
                                            width: 100%; /* Full width */
                                            height: 100%; /* Full height */
                                            overflow: auto; /* Enable scroll if needed */
                                            background-color: rgb(0,0,0); /* Fallback color */
                                            background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
                                        }

                                        /* Modal Content (image) */
                                        .modal-content {
                                            margin: auto;
                                            display: block;
                                            width: 58%;
                                            max-width: 318px;

                                        }

                                        /* Caption of Modal Image */
                                        #caption {
                                            margin: auto;
                                            display: block;
                                            width: 80%;
                                            max-width: 700px;
                                            text-align: center;
                                            color: #ccc;
                                            padding: 10px 0;
                                            height: 150px;
                                        }

                                        /* Add Animation */
                                        .modal-content, #caption {
                                            -webkit-animation-name: zoom;
                                            -webkit-animation-duration: 0.6s;
                                            animation-name: zoom;
                                            animation-duration: 0.6s;
                                        }

                                        @-webkit-keyframes zoom {
                                            from {-webkit-transform:scale(0)}
                                            to {-webkit-transform:scale(1)}
                                        }

                                        @keyframes zoom {
                                            from {transform:scale(0)}
                                            to {transform:scale(1)}
                                        }

                                        /* The Close Button */
                                        .close {
                                            position: absolute;

                                            top: 45px;
                                            right: 27px;

                                            color: #f1f1f1;
                                            font-size: 40px;
                                            font-weight: bold;
                                            transition: 0.3s;
                                        }

                                        .close:hover,
                                        .close:focus {
                                            color: #bbb;
                                            text-decoration: none;
                                            cursor: pointer;
                                        }

                                        /* 100% Image Width on Smaller Screens */
                                        @media only screen and (max-width: 700px){
                                            .modal-content {
                                                width: 100%;
                                            }
                                        }
                                    </style>
                                    <div class="table-responsive">
                                        <div class="row">
                                            <div class="col-md-6 pt-3">
                                                <button type="button" class="btn btn-primary pull-left btn-add-table-line" > Agregar Linea</button>

                                            </div>

                                        </div>
                                        <table class="table ">
                                            <thead class=" text-primary">
                                            <tr>

                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>UoM Unid</th>
                                                <th>Unidades</th>
                                                <th>Costo</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>

                                                </tr>

                                            </tbody>
                                        </table>
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
