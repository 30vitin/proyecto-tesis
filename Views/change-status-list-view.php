<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';


$configuration = "active";
$cambiarstatus = "active-sublink";


if (!isset($_GET['option'])) {
    header("Location:javascript:window.history.go(-2);");
}
if (isset($VAR_SESSION->permission) && !in_array("PER0006", $VAR_SESSION->permission)) {

    header('location:?view=nopermission');
}

$option = $_GET['option'];
$optionName = "";
switch ($option) {
    case 1:
        $optionName = "Cambiar Status a Requisición";
        break;
    case 2:
        $optionName = "Cambiar Status a O/C";
        break;

    case 3:
        $optionName = "Cambiar Status a Pedido";
        break;
    case 4:
        $optionName = "Cambiar Status a Factura";
        break;
    case 5:
        $optionName = "Cambiar Status a Recepción de Mercancía";
        break;
    case 6:
        $optionName = "Cambiar Status a Despacho de Mercancía";
        break;

    default:
        $optionName = "";
};

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
        <?php echo $optionName; ?> | Cafeteria
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
        $breadcrumbData = array(
            array("name" => "Cambiar status", "link" => "./?view=change-status", "current" => false),
            array("name" => $optionName, "current" => true),
        ); 

        $breadcrumb = json_decode(json_encode($breadcrumbData), FALSE);
        include "navbar.php"; ?>

        <!-- End Navbar -->
        <div class="content">
            <div class="container-fluid">

                <div class="row">

                    <div class="col-8 col-lg-1"></div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title "> <?php echo $optionName; ?></h4>
                            </div>
                            <div class="card-body">
                                <?php if ($option == 1) { ?>

                                    <div class="table-responsive">
                                        <table id="table" class="table table-striped table-bordered" style="width:100%">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>Proveedor</th>
                                                <th>Unidades</th>
                                                <th>Costo</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Acción</th>
                                            </tr>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sql = "SELECT  id as id,sum(units) as units,sum(costs) as costs,sum(total) as total,date,provider,status FROM (
                                                    (SELECT   t1.id,t3.units,t3.costs,t3.total,t1.date,t2.name as provider,t1.status
                                                    FROM purchase_requests t1 
                                                    join providers t2 on t1.provider = t2.id 
                                                    join purchase_requests_details t3 on t1.id = t3.purchase_request 
                                                    WHERE t1.status <>'DELETE' ) as datas
                                                    
                                            ) group by id ORDER BY date desc ";


                                            $result_lis = $cls->consultListQuery($sql);//query

                                            foreach ($result_lis as $item) {
                                                ?>
                                                <tr>

                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->date; ?></td>

                                                    <td><?php echo ucwords($item->provider); ?></td>
                                                    <td><?php echo $item->units; ?></td>
                                                    <td><?php echo $item->costs; ?></td>
                                                    <td><?php echo $item->total; ?></td>


                                                    <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status); ?>">
                                                        <?php echo $item->status; ?></span>
                                                    </td>
                                                    <td class="td-actions">
                                                        <button  data-id="<?php echo $item->id; ?>" data-action="PURCHASE-REQUEST-SET-STATUS"
                                                           class="btn btn-primary btn-link btn-sm set-status-document"  data-original-title="Cambiar Status"
                                                             >
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </button>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="5" style="text-align:right">Total:</th>

                                                <th id="custom-total" data-colum="5"></th>
                                                <th ></th>

                                            </tr>
                                            </tfoot>

                                        </table>
                                    </div>

                                <?php } ?>
                                <?php if ($option == 2) { ?>

                                    <div class="table-responsive">
                                        <table id="table" class="table table-striped table-bordered" style="width:100%">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>Requisición</th>
                                                <th>Proveedor</th>
                                                <th>Unidades</th>
                                                <th>Costo</th>
                                                <th>Total</th>
                                                <th>Status</th>
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
                                                    <td><?php echo $item->units; ?></td>
                                                    <td><?php echo $item->costs; ?></td>
                                                    <td><?php echo $item->total; ?></td>
                                                    <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status);?>">
                                                        <?php echo $item->status;?></span>
                                                    </td>

                                                    <td class="td-actions">
                                                        
                                                        <button  data-id="<?php echo $item->id; ?>" data-action="PURCHASE-ORDER-SET-STATUS"
                                                           class="btn btn-primary btn-link btn-sm set-status-document"  data-original-title="Cambiar Status"
                                                             >
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </button>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="6" style="text-align:right">Total:</th>

                                                <th id="custom-total" data-colum="6"></th>
                                                <th ></th>

                                            </tr>
                                            </tfoot>

                                        </table>
                                    </div>

                                <?php } ?>
                                <?php if ($option == 3) { ?>

                                    <div class="table-responsive">
                                        <table id="table" class="table table-striped table-bordered" style="width:100%">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>O/C</th>
                                                <th>Compradas</th>
                                                <th>Solicitadas</th>
                                                <th>Diferencia</th>
                                                <th>Status</th>
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
                                                    WHERE t1.status <>'DELETE' 
																										) as datas
                                                    
                                            ) group by id ORDER BY date desc ";


                                            $result_lis = $cls->consultListQuery($sql);//query

                                            foreach ($result_lis as $item) {
                                                ?>
                                                <tr>

                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->date; ?></td>
                                                    <td><?php echo $item->purchase_order; ?></td>
                                                    <td><?php echo $item->units_buy; ?></td>
                                                    <td><?php echo $item->units_request; ?></td>
                                                    <td><?php echo $item->units_diff; ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $cls->getStatusClass($item->status);?>"><?php echo $item->status;?></span>
                                                    </td>

                                                    <td class="td-actions">
                                                     
                                                        <button  data-id="<?php echo $item->id; ?>" data-action="ORDERS-SET-STATUS"
                                                           class="btn btn-primary btn-link btn-sm set-status-document"  data-original-title="Cambiar Status"
                                                             >
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="4" style="text-align:right">Total:</th>

                                                <th id="total-unrequest-tbl"></th>
                                                <th ></th>

                                            </tr>
                                            </tfoot>

                                        </table>
                                    </div>

                                <?php } ?>
                                <?php if ($option == 4) { ?>

                                    <div class="table-responsive">
                                        <table id="table" class="table table-striped table-bordered" style="width:100%">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Pedido</th>
                                                <th>Unidades</th>
                                                <th>Costo</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Acción</th>
                                            </tr>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sql = "SELECT  id as id,sum(units) as units,sum(costs) as costs,sum(total) as total,date,customer,status,order_id,credit_term FROM (
                                                    (SELECT   t1.credit_term,t1.id,t3.units,t3.costs,t3.total,t2.name as customer,t1.status,t1.order_id,DATE_FORMAT(t1.date,'%Y-%m-%d') as date
                                                    FROM bills t1 
                                                    left join customers t2 on t1.customer = t2.id 
                                                    join bills_details t3 on t1.id = t3.bill 
                                                    WHERE t1.status <>'DELETE' ) as datas
                                                    
                                            ) group by id ORDER BY date desc";

                                            $result_lis = $cls->consultListQuery($sql);//query

                                            foreach ($result_lis as $item) {
                                                ?>
                                                <tr>

                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->date; ?></td>
                                                    <td><?php echo $item->customer; ?></td>
                                                    <td><?php echo $item->credit_term; ?></td>

                                                    <td><?php echo $item->order_id; ?></td>
                                                    <td><?php echo $item->units; ?></td>
                                                    <td><?php echo $item->costs; ?></td>

                                                    <td><?php echo $item->total ; ?></td>
                                                    <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status);?>">
                                                        <?php echo $item->status;?></span>
                                                    </td>
                                                    <td class="td-actions">
                                                    
                                                        <button  data-id="<?php echo $item->id; ?>" data-action="BILLS-SET-STATUS"
                                                           class="btn btn-primary btn-link btn-sm set-status-document"  data-original-title="Cambiar Status"
                                                             >
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </button>

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


                                <?php } ?>
                                <?php if ($option == 5) { ?>
                                    <div class="table-responsive">
                                        <table id="table" class="table table-striped table-bordered" style="width:100%">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Fecha</th>
                                                <th>Factura</th>
                                                <th>Unidades</th>
                                                <th>Costo</th>
                                                <th>Total</th>
                                                <th>Status</th>
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
                                                    WHERE t1.status <>'DELETE' ) as datas
                                                    
                                            ) group by id ORDER BY date desc";

                                            $result_lis = $cls->consultListQuery($sql);//query

                                            foreach ($result_lis as $item) {
                                                ?>
                                                <tr>

                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->date; ?></td>
                                                    <td><?php echo $item->bills; ?></td>
                                                    <td><?php echo $item->units; ?></td>
                                                    <td><?php echo $item->costs; ?></td>

                                                    <td><?php echo $item->total ; ?></td>
                                                    <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status);?>">
                                                        <?php echo $item->status;?></span>
                                                    </td>
                                                    <td class="td-actions">
                                                        <button  data-id="<?php echo $item->id; ?>" data-action="RECEIVE-MERCHANT-SET-STATUS"
                                                           class="btn btn-primary btn-link btn-sm set-status-document"
                                                             >
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </button>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="5" style="text-align:right">Total:</th>

                                                <th id="custom-total" data-colum="5"></th>
                                                <th ></th>

                                            </tr>
                                            </tfoot>

                                        </table>
                                    </div>
                                <?php } ?>
                                <?php if ($option == 6) { ?>
                                    <div class="table-responsive">
                                        <table id="table" class="table table-striped table-bordered" style="width:100%">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Fecha</th>
                                                <th>Recepción</th>
                                                <th>Recibidas</th>
                                                <th>Solicitadas</th>
                                                <th>Diferencia</th>
                                                <th>Status</th>
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
                                                    <td><?php echo $item->units_buy; ?></td>
                                                    <td><?php echo $item->units_request; ?></td>
                                                    <td><?php echo $item->units_diff; ?></td>
                                                    <td>
                                                    <span class="badge <?php echo $cls->getStatusClass($item->status);?>">
                                                        <?php echo $item->status;?></span>
                                                    </td>
                                                    <td class="td-actions">
                                                        <button  data-id="<?php echo $item->id; ?>" data-action="DISPATCH-MERCHANT-SET-STATUS"
                                                           class="btn btn-primary btn-link btn-sm set-status-document"
                                                             >
                                                            <i class="material-icons">edit</i>
                                                            <div class="ripple-container"></div>
                                                        </button>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="4" style="text-align:right">Total:</th>

                                                <th id="custom-total" data-colum="4" data-fixed="NO"></th>
                                                <th ></th>

                                            </tr>
                                            </tfoot>

                                        </table>
                                    </div>
                                <?php } ?>
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
