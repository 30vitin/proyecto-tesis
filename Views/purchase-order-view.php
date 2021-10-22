<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';


$purchase = "active";
$ordenescompra = "active-sublink";

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
        Orden de Compra | Cafeteria
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
        $navbar="Lista de Ordenes de Compra";
        include 'navbar.php'; ?>

        <!-- End Navbar -->
        <div class="content">
            <div class="container-fluid">

                <div class="row">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title ">Ordenes de compra</h4>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5 pb-3">
                                        <a href="./?view=purchase-order-create" class="btn btn-primary">
                                            Registrar orden de compra
                                        </a>

                                    </div>

                                </div>

                                <div class="table-responsive">
                                    <table id="table" class="table table-striped table-bordered" style="width:100%">
                                        <thead class="text-primary">
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha</th>
                                            <th>Requisición</th>
                                            <th>Proveedor</th>
                                            <th>Status</th>
                                            <th>Unidades</th>
                                            <th>Costo</th>
                                            <th>Total</th>
                                            <th>Acción</th>
                                        </tr>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        $sql = "SELECT  id as id,sum(units) as units,sum(costs) as costs,sum(total) as total,date,provider,status,purchase_request FROM (
                                                    (SELECT   t1.id,t3.units,t3.costs,t3.total,t1.date,t2.name as provider,t1.status,t1.purchase_request
                                                    FROM purchase_orders t1 
                                                    join providers t2 on t1.provider = t2.id 
                                                    join purchase_orders_details t3 on t1.id = t3.purchase_order 
                                                    WHERE t1.status <>'DELETE' ) as datas
                                                    
                                            ) group by id ORDER BY date desc ";
                                        $result_lis = $cls->consultListQuery($sql);//query

                                        foreach ($result_lis as $item) {
                                            ?>
                                            <tr>

                                                <td><?php echo $item->id; ?></td>
                                                <td><?php echo $item->date; ?></td>
                                                <td><?php echo $item->purchase_request; ?></td>


                                                <td><?php echo ucwords($item->provider); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status);?>">
                                                        <?php echo $item->status;?></span>
                                                </td>
                                                <td><?php echo $item->units; ?></td>
                                                <td><?php echo $item->costs; ?></td>
                                                <td><?php echo $item->total; ?></td>


                                                <td class="td-actions">
                                                    <a href="./?view=purchase-order-edit&id=<?php echo $item->id; ?>"
                                                       rel="tooltip" title="" class="btn btn-primary btn-link btn-sm"
                                                       data-original-title="Mostrar Orden de Compra">
                                                        <i class="material-icons">edit</i>
                                                        <div class="ripple-container"></div>
                                                    </a>

                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align:right">Total:</th>

                                            <th id="custom-total" data-colum="7"></th>
                                            <th ></th>

                                        </tr>
                                        </tfoot>

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
