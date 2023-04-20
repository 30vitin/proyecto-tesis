<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';


$dashboard = "active";


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Dashboard | Cafeteria</title>
    <?php include "styles.php"; ?>
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet"/>

</head>

<body class="">

<?php include 'loader.php'; ?>

<div class="wrapper ">

    <?php include "sidebar.php"; ?>

    <div class="main-panel">
        <!-- Navbar -->
        <?php include "navbar.php"; ?>

        <!-- End Navbar -->
        <!--colocar permisos -->

        <div class="content">
            <div class="container-fluid ">

                <div class="row d-flex justify-content-center">


                    <div class="col-lg-8 col-md-6 col-sm-6">
                        <div class="card card-stats text-center p-3">
                            <h2 class="text-muted"> Control de Pedidos</h2>
                            <p class="text-muted"> CRUV - Departamento de Cafetería. </p>

                            <?php if (isset($VAR_SESSION->permission) && in_array("PER0012", $VAR_SESSION->permission)) { ?>
                                <p class="pt-4">Links de acceso rapido</p>
                                <div class="row d-flex justify-content-center">

                                    <?php if (isset($VAR_SESSION->permission) && in_array("PER0002", $VAR_SESSION->permission)) {?>
                                        <a href="./?view=purchase-requests-create"
                                           class="col-md-3 p-2 list-group-item-action"><i class="fa fa-external-link"></i>
                                            Registrar requisición</a>
                                        <a href="./?view=purchase-order-create" class="col-md-3 p-2 list-group-item-action"><i
                                                    class="fa fa-external-link"></i> Registrar O/C</a>
                                    <?php }?>
                                    <?php if (isset($VAR_SESSION->permission) && in_array("PER0010", $VAR_SESSION->permission)) {?>
                                        <a href="./?view=orders-create" class=" col-md-3 p-2 list-group-item-action"><i
                                                    class="fa fa-external-link"></i> Registrar Pedido</a>
                                    <?php }?>
                                    <?php if (isset($VAR_SESSION->permission) && in_array("PER0008", $VAR_SESSION->permission)) {?>
                                        <a href="./?view=bills-create" class="col-md-3 p-2 list-group-item-action"><i
                                                    class="fa fa-external-link"></i> Registrar Factura</a>
                                    <?php }?>

                                </div>
                            <?php } ?>

                            <?php if (isset($VAR_SESSION->permission) && in_array("PER0013", $VAR_SESSION->permission)) { ?>
                                <p class="pt-4">Links de acceso rapido</p>
                                <div class="row d-flex justify-content-center">
                                    <a href="./?view=receive-merchant-create"
                                       class="col-md-3 p-2 list-group-item-action"><i class="fa fa-external-link"></i>
                                        Registrar recepción</a>
                                    <a href="./?view=dispatch-merchant-create" class="col-md-3 p-2 list-group-item-action"><i
                                                class="fa fa-external-link"></i> Registrar despacho</a>
                                </div>
                            <?php } ?>


                        </div>

                    </div>
                </div>

                <?php if (isset($VAR_SESSION->permission) && in_array("PER0012", $VAR_SESSION->permission)) { ?>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-12 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon">
                                        <i class="material-icons">assignment</i>
                                    </div>
                                </div>
                                <div class="card-header"><h4 class="font-weight-bold text-muted">Facturas realizadas en
                                        lo que va del mes</h4></div>
                                <div class="card-footer">


                                    <div class="table-responsive">
                                        <table class="table-dashboard table table-striped table-bordered"
                                               style="width:100%">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
                                                <th>Pedido</th>
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
                                            $sql1 = "SELECT id AS id,sum( units ) AS units,sum( costs ) AS costs,sum( total ) AS total,date,customer,status,order_id 
                                        FROM ( (SELECT t1.id,t3.units,t3.costs,t3.total,t2.name AS customer,t1.status,t1.order_id,DATE_FORMAT( t1.created_at, '%Y-%m-%d' ) AS date 
                                                    FROM bills t1 JOIN customers t2 ON t1.customer = t2.id JOIN bills_details t3 ON t1.id = t3.bill 
                                                    WHERE t1.STATUS <> 'DELETE'   AND t1.date BETWEEN (date_add( curdate(), INTERVAL - DAY ( curdate()) + 1 DAY )) AND DATE_ADD(curdate(), INTERVAL 1 DAY)
                                                    ) AS datas 
                                                )GROUP BY id ORDER BY date DESC";

                                            $result_lis1 = $cls->consultListQuery($sql1);//query

                                            foreach ($result_lis1 as $item) {
                                                ?>
                                                <tr>

                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->date; ?></td>
                                                    <td><?php echo $item->customer; ?></td>
                                                    <td><?php echo $item->order_id; ?></td>
                                                    <td>
                                            <span class="badge <?php echo $cls->getStatusClass($item->status); ?>">
                                                            <?php echo $item->status; ?></span>
                                                    </td>
                                                    <td><?php echo $item->units; ?></td>
                                                    <td>$<?php echo $item->costs; ?></td>


                                                    <td>$<?php echo $item->total; ?></td>
                                                    <td class="td-actions">
                                                        <a href="./?view=bills-edit&id=<?php echo $item->id; ?>"
                                                           rel="tooltip" title=""
                                                           class="btn btn-primary btn-link btn-sm"
                                                           data-original-title="Mostrar Factura">
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
                        <div class="col-lg-12 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon">
                                        <i class="material-icons">assignment</i>
                                    </div>
                                </div>
                                <div class="card-header"><h4 class="font-weight-bold text-muted">Pedidos realizados en
                                        lo que va del mes</h4></div>

                                <div class="card-footer">


                                    <div class="table-responsive">
                                        <table id="" class="table-dashboard table table-striped table-bordered"
                                               style="width:100%">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>O/C</th>
                                                <th>Status</th>
                                                <th>Compradas</th>
                                                <th>Solicitadas</th>
                                                <th>Diferencia</th>

                                                <th>Acción</th>
                                            </tr>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sql = "SELECT  id as id,purchase_order,date,sum(units_buy) as units_buy,sum(units_request) as units_request,sum(units_diff) as units_diff, status FROM (
                                                    (SELECT  t1.id,t3.units_buy,t3.units_request,t3.units_diff,DATE_FORMAT(t1.date,'%Y-%m-%d') as date,t1.status,t1.purchase_order
                                                    FROM orders t1 
                                                    join orders_details t3 on t1.id = t3.order_id 
                                                    WHERE t1.status <>'DELETE'  AND t1.date BETWEEN (date_add( curdate(), INTERVAL - DAY ( curdate()) + 1 DAY )) AND DATE_ADD(curdate(), INTERVAL 1 DAY)
																										) as datas
                                                    
                                            ) group by id ORDER BY date desc ";


                                            $result_lis = $cls->consultListQuery($sql);//query

                                            foreach ($result_lis as $item) {
                                                ?>
                                                <tr>

                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->date; ?></td>
                                                    <td><?php echo $item->purchase_order; ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $cls->getStatusClass($item->status); ?>"><?php echo $item->status; ?></span>
                                                    </td>
                                                    <td><?php echo $item->units_buy; ?></td>
                                                    <td><?php echo $item->units_request; ?></td>
                                                    <td><?php echo $item->units_diff; ?></td>


                                                    <td class="td-actions">
                                                        <a href="./?view=orders-edit&id=<?php echo $item->id; ?>"
                                                           rel="tooltip" title=""
                                                           class="btn btn-primary btn-link btn-sm"
                                                           data-original-title="Mostrar Pedido">
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
                        <div class="col-lg-12 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon">
                                        <i class="fa fa-shopping-cart"></i>
                                    </div>

                                </div>
                                <div class="card-header"><h4 class="font-weight-bold text-muted">O/C realizados en lo
                                        que va del mes</h4></div>

                                <div class="card-footer">


                                    <div class="table-responsive">
                                        <table class="table-dashboard table table-striped table-bordered"
                                               style="width:100%" id="data-table2">
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
                                            $sql1 = "SELECT id AS id,sum( units ) AS units,sum( costs ) AS costs,sum( total ) AS total,date,provider,purchase_request,status 
                                        FROM ( (SELECT t1.id,t3.units,t3.costs,t3.total,t2.name AS provider,t1.status,t1.purchase_request,DATE_FORMAT( t1.created_at, '%Y-%m-%d' ) AS date 
                                                    FROM purchase_orders t1 JOIN providers t2 ON t1.provider = t2.id JOIN purchase_orders_details t3 ON t1.id = t3.purchase_order 
                                                    WHERE t1.STATUS <> 'DELETE'   AND t1.date BETWEEN (date_add( curdate(), INTERVAL - DAY ( curdate()) + 1 DAY )) AND DATE_ADD(curdate(), INTERVAL 1 DAY)
                                                    ) AS datas 
                                                )GROUP BY id ORDER BY date DESC";

                                            $result_lis1 = $cls->consultListQuery($sql1);//query

                                            foreach ($result_lis1 as $item) {
                                                ?>
                                                <tr>

                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->date; ?></td>
                                                    <td><?php echo $item->purchase_request; ?></td>
                                                    <td><?php echo $item->provider; ?></td>
                                                    <td>
                                            <span class="badge <?php echo $cls->getStatusClass($item->status); ?>">
                                                            <?php echo $item->status; ?></span>
                                                    </td>
                                                    <td><?php echo $item->units; ?></td>
                                                    <td>$<?php echo $item->costs; ?></td>


                                                    <td>$<?php echo $item->total; ?></td>
                                                    <td class="td-actions">
                                                        <a href="./?view=purchase-order-edit&id=<?php echo $item->id; ?>"
                                                           rel="tooltip" title=""
                                                           class="btn btn-primary btn-link btn-sm"
                                                           data-original-title="Mostrar Factura">
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

                <?php } ?>
                <?php if (isset($VAR_SESSION->permission) && in_array("PER0013", $VAR_SESSION->permission)) { ?>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-12 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon">
                                        <i class="fa fa-paste"></i>
                                    </div>

                                </div>
                                <div class="card-header"><h4 class="font-weight-bold text-muted">Recepción de mercancía
                                        en lo
                                        que va del mes</h4></div>

                                <div class="card-footer">


                                    <div class="table-responsive">
                                        <table class="table-dashboard table table-striped table-bordered"
                                               style="width:100%">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Fecha</th>
                                                <th>Factura</th>
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
                                            $sql = "SELECT  id as id,sum(units) as units,sum(costs) as costs,sum(total) as total,date,bills,status FROM (
                                                    (SELECT   t1.id,t3.units,t3.costs,t3.total,t1.bills as bills,t1.status,DATE_FORMAT(t1.date,'%Y-%m-%d') as date
                                                    FROM received_merchant t1 
                                                    join received_merchant_details t3 on t1.id = t3.received 
                                                    WHERE t1.status <>'DELETE' AND t1.date BETWEEN (date_add( curdate(), INTERVAL - DAY ( curdate()) + 1 DAY )) AND DATE_ADD(curdate(), INTERVAL 1 DAY) ) as datas  
                                                    
                                            ) group by id ORDER BY date desc";

                                            $result_lis = $cls->consultListQuery($sql);//query

                                            foreach ($result_lis as $item) {
                                                ?>
                                                <tr>

                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->date; ?></td>
                                                    <td><?php echo $item->bills; ?></td>
                                                    <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status); ?>">
                                                        <?php echo $item->status; ?></span>
                                                    </td>
                                                    <td><?php echo $item->units; ?></td>
                                                    <td><?php echo $item->costs; ?></td>

                                                    <td><?php echo $item->total; ?></td>

                                                    <td class="td-actions">
                                                        <a href="./?view=receive-merchant-edit&id=<?php echo $item->id; ?>"
                                                           rel="tooltip" title=""
                                                           class="btn btn-primary btn-link btn-sm"
                                                           data-original-title="Mostrar Recepción">
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
                        <div class="col-lg-12 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon">
                                        <i class="fa fa-paste"></i>
                                    </div>

                                </div>
                                <div class="card-header"><h4 class="font-weight-bold text-muted">Despacho de mercancía
                                        en lo
                                        que va del mes</h4></div>

                                <div class="card-footer">


                                    <div class="table-responsive">
                                        <table id="" class="table-dashboard table table-striped table-bordered"
                                               style="width:100%">
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
                                                    WHERE t1.status <>'DELETE' AND t1.date BETWEEN (date_add( curdate(), INTERVAL - DAY ( curdate()) + 1 DAY )) AND DATE_ADD(curdate(), INTERVAL 1 DAY) )
																										) as datas
                                                    
                                             group by id ORDER BY date desc";
                                
                                            $result_lis = $cls->consultListQuery($sql);//query

                                            foreach ($result_lis as $item) {
                                                ?>
                                                <tr>

                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->date; ?></td>
                                                    <td><?php echo $item->received; ?></td>
                                                    <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status); ?>">
                                                        <?php echo $item->status; ?></span>
                                                    </td>
                                                    <td><?php echo $item->units_buy; ?></td>
                                                    <td><?php echo $item->units_request; ?></td>
                                                    <td><?php echo $item->units_diff; ?></td>

                                                    <td class="td-actions">
                                                        <a href="./?view=dispatch-merchant-edit&id=<?php echo $item->id; ?>"
                                                           rel="tooltip" title=""
                                                           class="btn btn-primary btn-link btn-sm"
                                                           data-original-title="Mostrar Recepción">
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
                <?php } ?>

            </div>
        </div>

        <?php include "footer.php"; ?>

    </div>


</div>

<?php include "scripts/scripts.php"; ?>
<?php include "scripts/data-table-dashboard.php"; ?>


</body>

</html>
