<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto

include 'utils.php';


$configuration = "active";
$usersregister = "active-sublink";

if (isset($VAR_SESSION->permission) && !in_array("PER0014", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}
if (isset($_POST['page'])) {

    $page = $_POST['page'];

} else {
    $page = 1;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Lista de usuarios | Cafeteria
    </title>
    <?php include "styles.php";?>
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet"/>

</head>

<body class="">

<?php include 'loader.php';?>

<div class="wrapper ">

    <?php include "sidebar.php";?>
    <div class="main-panel">
        <!-- Navbar -->

        <?php
        $navbar="Lista de Usuarios";
        include 'navbar.php';?>
        <!-- End Navbar -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header ">
                                <h4 class="card-title ">Usuarios</h4>

                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5 pb-3">

                                        <a href="./?view=users-create" class="btn btn-primary">
                                            Registrar usuario
                                        </a>

                                    </div>

                                </div>

                                <div class="table-responsive">

                                    <table id="table" class="table table-striped table-bordered" style="width:100%">
                                        <thead class="text-primary">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Username</th>
                                            <th>Status</th>
                                            <th>Registrado</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT username,name,status,created_at FROM users_access order by created_at desc";


                                        $result_lis = $cls->consultListQuery($sql);//query
                                        $rows = 0;
                                        foreach ($result_lis as $item) { ?>
                                            <tr>
                                                <td><?php echo $item->name; ?></td>
                                                <td><?php echo $item->username; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status);?>">
                                                        <?php echo $item->status;?></span>
                                                </td>
                                                <td><?php echo $item->created_at; ?></td>
                                                <td class="td-actions">
                                                    <a href="./?view=users-edit&id=<?php echo $item->username; ?>"
                                                       rel="tooltip" title="" class="btn btn-primary btn-link btn-sm"
                                                       data-original-title="Ver Usuario">
                                                        <i class="material-icons">edit</i>
                                                        <div class="ripple-container"></div>
                                                    </a>

                                                    <a href="./?view=users-permission&id=<?php echo $item->username; ?>"
                                                       rel="tooltip" title="" class="btn btn-primary btn-link btn-sm"
                                                       data-original-title="Ver Permisos">
                                                        <i class="material-icons">lock</i>
                                                        <div class="ripple-container"></div>
                                                    </a>

                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <?php include 'footer.php';?>
    </div>
</div>

<?php include "scripts/scripts.php"; ?>
<?php include "scripts/data-table.php"; ?>
</body>


</html>
