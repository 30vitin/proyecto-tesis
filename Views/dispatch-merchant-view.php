<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';


$almacen = "active";
$des_mercancia = "active-sublink";

if (isset($VAR_SESSION->permission) && !in_array("PER0004", $VAR_SESSION->permission)) {

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
        Despacho de Mercancía | Cafeteria
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
        $navbar="Lista de Despacho de Mercancía";
        include 'navbar.php'; ?>

        <!-- End Navbar -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title ">Despacho de mercancía</h4>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5 pb-3">
                                        <a href="./?view=dispatch-merchant-create" class="btn btn-primary">
                                            Registrar despacho
                                        </a>

                                    </div>

                                </div>


                                <div class="table-responsive">
                                    <table id="table" class="table table-striped table-bordered" style="width:100%">
                                        <thead class="text-primary">
                                        <tr>
                                            <th>Id</th>
                                            <th>Fecha</th>
                                            <th>Recepción</th>
                                            <th>Status</th>
                                            <th>Recibidas</th>
                                            <th>Solicitadas</th>
                                            <th>Diferencia</th>
                                            <th>Acción</th>
                                        </tr>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT  id as id,received,date,sum(units_buy) as units_buy,sum(units_request) as units_request,sum(units_diff) as units_diff, status FROM (
                                                    (SELECT  t1.id,t3.units_buy,t3.units_request,t3.units_diff,DATE_FORMAT(t1.date,'%Y-%m-%d') as date,t1.status,t1.received
                                                    FROM dispatch_merchant t1 
                                                    join dispatch_merchant_details t3 on t1.id = t3.dispatch 
                                                    WHERE t1.status <>'DELETE' 
																										) as datas
                                                    
                                            ) group by id ORDER BY date desc";

                                        $result_lis = $cls->consultListQuery($sql);//query

                                        foreach ($result_lis as $item) {
                                            ?>
                                            <tr>

                                                <td><?php echo $item->id; ?></td>
                                                <td><?php echo $item->date; ?></td>
                                                <td><?php echo $item->received; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status);?>">
                                                        <?php echo $item->status;?></span>
                                                </td>
                                                <td><?php echo $item->units_buy; ?></td>
                                                <td><?php echo $item->units_request; ?></td>
                                                <td><?php echo $item->units_diff; ?></td>

                                                <td class="td-actions">
                                                    <a href="./?view=dispatch-merchant-edit&id=<?php echo $item->id; ?>"
                                                       rel="tooltip" title="" class="btn btn-primary btn-link btn-sm"
                                                       data-original-title="Mostrar Recepción">
                                                        <i class="material-icons">edit</i>
                                                        <div class="ripple-container"></div>
                                                    </a>

                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align:right">Total:</th>

                                            <th id="custom-total" data-colum="5" data-fixed="NO"></th>
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
