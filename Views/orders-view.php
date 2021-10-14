<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';

$sales="active";
$pedidos="active-sublink";


if(isset($_POST['page'])){

$page=$_POST['page'];

}else{
	$page=1;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Pedidos | Cafeteria
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
        $navbar="Lista de Pedidos";
        include 'navbar.php'; ?>

      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">

            <div class="col-8 col-lg-1"></div>

            <div class="col-md-12">
              <div class="card">
                <div class="card-header ">
                  <h4 class="card-title ">Pedidos</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5 pb-3">
                            <a href="./?view=orders-create" class="btn btn-primary">
                                Registrar pedido
                            </a>

                        </div>

                    </div>

                    <div class="table-responsive">
                        <table id="table" class="table table-striped table-bordered" style="width:100%">
                            <thead class="text-primary">
                            <tr>
                                <th>ID</th>
                                <th>O/C</th>
                                <th>Fecha</th>
                                <td>Status</td>
                                <th>Acción</th>
                            </tr>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "SELECT t1.id, t1.purchase_order,t1.date,t1.status FROM orders t1 WHERE t1.status <>'DELETE'  order by t1.created_at desc ";


                            $result_lis = $cls->consultListQuery($sql);//query

                            foreach ($result_lis as $item) {
                                ?>
                                <tr>

                                    <td><?php echo $item->id; ?></td>
                                    <td><?php echo $item->purchase_order; ?></td>
                                    <td><?php echo $item->date; ?></td>

                                    <td>
                                        <span class="badge <?php echo $cls->getStatusClass($item->status);?>"><?php echo $item->status;?></span>
                                    </td>

                                    <td class="td-actions">
                                        <a href="./?view=orders-edit&id=<?php echo $item->id; ?>"
                                           rel="tooltip" title="" class="btn btn-primary btn-link btn-sm"
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
