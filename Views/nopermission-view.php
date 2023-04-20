<!---->
<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Acceso Denegado | Cafeteria</title>
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

        <!--no cuenta con permisos -->
        <div class="content">
            <div class="container-fluid ">

                <div class="row d-flex justify-content-center">


                    <div class="col-lg-8 col-md-6 col-sm-6">
                        <div class="card card-stats text-center p-3">

                            <h3>
                                <i class="fa fa-exclamation-triangle"></i> Error, No cuenta con permisos para ver esta
                                sección.
                            </h3>
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
