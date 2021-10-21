<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto

include 'utils.php';

$purchase = "active";
$proveedor = "active-sublink";

if (isset($VAR_SESSION->permission) && !in_array("PER0002", $VAR_SESSION->permission)) {

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
        Proovedor | Cafeteria
    </title>
    <?php include "styles.php"; ?>
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet"/>

</head>

<body class="">
    <?php include 'loader.php'; ?>

<div class="wrapper ">
    <?php include "sidebar.php"; ?>
    <div class="main-panel">
        <!-- Navbar -->

        <?php
        $navbar="Listar Proveedor";
        include 'navbar.php'; ?>
        <!-- End Navbar -->
        <div class="content">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title ">Proveedores</h4>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5 pb-3">
                                        <a href="./?view=provider-create" class="btn btn-primary">
                                            Registrar proveedor
                                        </a>

                                    </div>

                                </div>

                                <div class="table-responsive">
                                    <table id="table" class="table table-striped table-bordered" style="width:100%">
                                        <thead class="text-primary">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Acción</th>
                                        </tr>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT t1.id as id,t1.name as name,t1.email as email,t1.telephone1 as telephone1
                                         FROM providers t1  WHERE status = 'ACTIVO'  order by t1.created_at desc ";


                                        $result_lis = $cls->consultListQuery($sql);//query

                                        foreach ($result_lis as $item) {
                                            ?>
                                            <tr>

                                                <td><?php echo $item->id; ?></td>
                                                <td><?php echo ucwords($item->name); ?></td>
                                                <td><?php echo $item->email; ?></td>
                                                <td><?php echo $item->telephone1; ?></td>

                                                <td class="td-actions">
                                                    <a href="./?view=provider-edit&id=<?php echo $item->id; ?>"
                                                       rel="tooltip" title="" class="btn btn-primary btn-link btn-sm"
                                                       data-original-title="Mostrar Proveedor">
                                                        <i class="material-icons">edit</i>
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

        <?php include 'footer.php'; ?>
    </div>
</div>
    <?php include "scripts/scripts.php"; ?>
    <?php include "scripts/data-table.php"; ?>
</body>

</html>
