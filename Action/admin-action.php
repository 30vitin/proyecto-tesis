<?php

require_once 'Config/Functions.php';
$VAR_SESSION = Session::getInstance();

$cls = new Functions;  //llamando al objeto
date_default_timezone_set('America/Panama');

$time = time();
$datetime = date('Y-m-d H:i:s');


$mensaje = array();

/**
 * INVENTARIO
 *
 */
#Error #001: id no enviados
#Error #002: error al subir archivo
#Error #003: error al actualizar la imagen del producto


if (isset($_POST['a']) && $_POST['a'] == 'CREATE-CATEGORY') {
    $cls->autocommitF();
    if (isset($_POST['name'])) {
        $id = $cls->getId_autoincrement("products_category");

        $name = $_POST['name'];

        $sql = "INSERT INTO products_category (id,name,created_by,created_at)
                values('$id','$name','$VAR_SESSION->username', '$datetime')";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Categoría registrada con exito.', 'url' => './?view=category-edit&id=' . $id, "post_name" => "Categoría", "id" => $id);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    } else {
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');
    }
}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-CATEGORY') {
    $cls->autocommitF();
    if (isset($_POST['name'])) {

        if (isset($_POST['id'])) {

            $name = $_POST['name'];
            $id = $_POST['id'];

            $sql = "UPDATE products_category set name='$name',updated_by='$VAR_SESSION->username' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Categoría actualizada con exito.');

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }
        } else {

            $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
        }

    } else {
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');
    }
}
if (isset($_POST['a']) && $_POST['a'] == 'DELETE-CATEGORY') {
    $cls->autocommitF();


    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $sql = "UPDATE products_category set status='DELETE',updated_by='$VAR_SESSION->username',updated_at ='$datetime'WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'url' => './?view=category-products');

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}


if (isset($_POST['a']) && $_POST['a'] == 'CREATE-PRODUCT') {
    $check = true;
    if (!isset($_POST['name'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');
    }
    if (!isset($_POST['provider'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if (!isset($_POST['unidad_para_compra'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Unidad de compra es obligatorio');
    }
    if (!isset($_POST['unidad_para_almacen'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Unidad para almacen es obligatorio');
    }
    if (!isset($_POST['unidad_almacen'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Cantidad de unidad para almacen es obligatorio');
    }

    if ($check) {
        $id = $cls->getId_autoincrement("products");
        $category = $_POST['category'];
        $code_extern = $_POST['code_extern'];
        $description = $_POST['description'];
        $file = $_FILES['file'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $provider = $_POST['provider'];
        $unidad_para_compra = $_POST['unidad_para_compra'];
        $unidad_para_almacen = $_POST['unidad_para_almacen'];
        $unidad_almacen = $_POST['unidad_almacen'];
        $code_extern = $_POST['code_extern'];

        $image_name = "";
        $path = $cls->getVarData('product-image') . '/' . $id;

        $file_res = $cls->uploadFile($file, $path);


        if ($file_res['success']) {

            $image_name = $file_res['filename'];

            $sql = "INSERT INTO products (id,
                      name,
                      description,
                      category,
                      price,
                      img_portada,
                      unidad_almacen,   
                      code_extern,
                      unidad_para_compra,
                      unidad_para_almacen,
                      provider,
                      created_at,
                      created_by)
                     values( '$id',
                            '$name',
                            '$description',
                            '$category',
                             $price,
                            '$image_name',
                             $unidad_almacen,
                            '$code_extern',
                            '$unidad_para_compra',
                            '$unidad_para_almacen',
                            '$provider',
                            '$datetime',
                            '$VAR_SESSION->username')";
            $res = $cls->exeQuery($sql);
            if ($res) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Producto registrado con exito.', 'url' => './?view=product-edit&id=' . $id, "post_name" => "Producto", "id" => $id);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }


        } else {

            $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #002');


        }


    }
    //
}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-PRODUCT') {
    $check = true;
    if (!isset($_POST['name'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');
    }
    if (!isset($_POST['provider'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if (!isset($_POST['unidad_para_compra'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Unidad de compra es obligatorio');
    }
    if (!isset($_POST['unidad_para_almacen'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Unidad para almacen es obligatorio');
    }
    if (!isset($_POST['unidad_almacen'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Cantidad de unidad para almacen es obligatorio');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

    if ($check) {
        $id = $_POST['id'];
        $category = $_POST['category'];
        $code_extern = $_POST['code_extern'];
        $description = $_POST['description'];
        $file = $_FILES['file'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $provider = $_POST['provider'];
        $unidad_para_compra = $_POST['unidad_para_compra'];
        $unidad_para_almacen = $_POST['unidad_para_almacen'];
        $unidad_almacen = $_POST['unidad_almacen'];
        $code_extern = $_POST['code_extern'];

        $image_name = "";

        if (!empty($file) && !$file['error']) {
            $currentimage = "SELECT img_portada from products WHERE id = '$id'";
            $resp = $cls->consulQuery($currentimage);
            if ($resp && isset($resp['img_portada'])) {

                $path = $cls->getVarData('product-image') . '/' . $id . '/' . $resp['img_portada'];
                if (file_exists($path)) {
                    unlink($path);
                }

            }
        }
        $path = $cls->getVarData('product-image') . '/' . $id;
        $file_res = $cls->uploadFile($file, $path);

        if ($file_res['success']) {

            $image_name = $file_res['filename'];
            $resql1 = true;
            if ($image_name != "") {
                $sql1 = "UPDATE products set img_portada ='$image_name' WHERE id = '$id'";
                $res1 = $cls->exeQuery($sql1);
                if (!$res1) {
                    $resql1 = false;
                }
            }

            $sql = "UPDATE products
                      set name = '$name',
                      description = '$description',
                      category = '$category',
                      price = $price,
                      unidad_almacen = $unidad_almacen,   
                      code_extern ='$code_extern',
                      unidad_para_compra = '$unidad_para_compra',
                      unidad_para_almacen = '$unidad_para_almacen',
                      provider = '$provider',
                      updated_at ='$datetime',
                      updated_by ='$VAR_SESSION->username' WHERE id ='$id'";

            $res = $cls->exeQuery($sql);
            if ($res && $resql1) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Producto actualizado con exito.');

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => ($res != "") ? $res : 'Pongase en contacto con su adminsitrador de sistema #003');

            }


        } else {

            $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #002');


        }


    }
    //
}
if (isset($_POST['a']) && $_POST['a'] == 'DELETE-PRODUCT') {
    $cls->autocommitF();


    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $sql = "UPDATE products set status='DELETE',updated_by='$VAR_SESSION->username', updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'url' => './?view=products');

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'GET-PRODUCTS') {

    $whereIds = "";
    if (isset($_POST['ids'])) {
        $whereIds = "AND t1.id in(" . implode(",", $_POST['ids']) . ")";

    }
    $sql = "SELECT t1.id as id,t1.name as name,t2.name as category,t1.price as price,t1.status as status,t1.img_portada,t1.unidad_para_compra,t1.unidad_almacen,t1.unidad_para_almacen FROM products t1 left join products_category t2 on t1.category = t2.id WHERE t1.status = 'ACTIVO' $whereIds order by t1.created_at desc";
    $result_lis = $cls->consultListQuery($sql);//query
    $mensaje = $result_lis;
}


/**
 * COMPRAS
 *
 */
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-PROVIDER') {
    $check = true;
    if (!isset($_POST['name'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');
    }
    if (!isset($_POST['telephone1'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo teléfono 1 es obligatorio');
    }

    if ($check) {
        $id = $cls->getId_autoincrement("providers");

        $name = $_POST['name'];
        $email = $_POST['email'];
        $telephone1 = $_POST['telephone1'];
        $telephone2 = $_POST['telephone2'];
        $fax = $_POST['fax'];
        $account = $_POST['account'];
        $address = $_POST['address'];

        $sql = "INSERT INTO providers (id,
                       name,
                       email,
                       telephone1,
                       telephone2,
                       fax,
                       account,
                       address,
                       created_at,
                       created_by) values('$id',
                                          '$name',
                                          '$email',
                                          '$telephone1',
                                          '$telephone2',
                                          '$fax',
                                          '$account',
                                          '$address',
                                          '$datetime',
                                          '$VAR_SESSION->username')";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Proveedor registrado con exito.', 'url' => './?view=provider-edit&id=' . $id, "post_name" => "Proveedor", "id" => $id);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    }
}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-PROVIDER') {
    $check = true;
    if (!isset($_POST['name'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');
    }
    if (!isset($_POST['telephone1'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo teléfono 1 es obligatorio');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

    if ($check) {
        $id = $_POST['id'];

        $name = $_POST['name'];
        $email = $_POST['email'];
        $telephone1 = $_POST['telephone1'];
        $telephone2 = $_POST['telephone2'];
        $fax = $_POST['fax'];
        $account = $_POST['account'];
        $address = $_POST['address'];

        $sql = "UPDATE providers
                       SET name='$name',
                       email ='$email',
                       telephone1 = '$telephone1',
                       telephone2 = '$telephone2',
                       fax = '$fax',
                       account = '$account',
                       address = '$address',
                       updated_at = '$datetime',
                       updated_by = '$VAR_SESSION->username' WHERE id ='$id'";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Proveedor actualizado con exito.');

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    }
}
if (isset($_POST['a']) && $_POST['a'] == 'DELETE-PROVIDER') {
    $cls->autocommitF();


    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $sql = "UPDATE providers set status='DELETE',updated_by='$VAR_SESSION->username', updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'url' => './?view=providers');

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}


if (isset($_POST['a']) && $_POST['a'] == 'CREATE-PURCHASE-REQUEST') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['provider'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if(!isset($_POST['data_table'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }

    $id = $cls->getId_autoincrement("purchase_requests");
    $date = $_POST['date'];
    $provider = $_POST['provider'];
    $comment = trim($_POST['comment']);

    $sql1="INSERT INTO purchase_requests (id,date,provider,comment,created_at,created_by)values('$id','$date','$provider','$comment','$datetime','$VAR_SESSION->username')";
    $res = $cls->exeQuery($sql1);
    if ($res) {
        $data_table = json_decode($_POST['data_table']);
        $check = true; // check data table
        foreach ($data_table as $data){

            if($data->costs > 0 && $data->total > 0 && $data->unit){

                $sql2="INSERT INTO purchase_requests_details (purchase_request,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                $res2 = $cls->exeQuery($sql2);
                if (!$res2) {
                    $check = false;
                }

            }else{

                $check = false;

            }
        }

        if($check){

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Requisición de compra registrada con exito.', 'url' => './?view=purchase-requests-edit&id=' . $id, "post_name" => "Requisición de compra", "id" => $id);

        }else{

            $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

        }

    } else {
        $cls->exeQuery('ROLLBACK');
        $mensaje = array('success' => false, 'mens' => $res);

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-PURCHASE-REQUEST') {
    $cls->autocommitF();
    $check = true;
    $mensaje = json_decode($_POST['data_table']);
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['provider'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if(!isset($_POST['data_table'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }


    $date = $_POST['date'];
    $id = $_POST['id'];
    $provider = $_POST['provider'];
    $comment = trim($_POST['comment']);

    $sqlstatus ="SELECT status FROM purchase_requests WHERE id='$id'";
    $response = $cls->consulQuery($sqlstatus);
    if($response['status']!="CERRADO"){
        $sql1="UPDATE purchase_requests set date ='$date',
                             provider = '$provider',
                             comment ='$comment',
                             updated_at = '$datetime',
                             updated_by = '$VAR_SESSION->username' WHERE id='$id'";

        $res = $cls->exeQuery($sql1);
        if ($res) {
            $sql2="DELETE FROM purchase_requests_details WHERE purchase_request='$id'";
            $res2 = $cls->exeQuery($sql2);
            if ($res2) {

                $data_table = json_decode($_POST['data_table']);
                $check = true; // check data table
                foreach ($data_table as $data){

                    if($data->costs > 0 && $data->total > 0 && $data->unit){

                        $sql3="INSERT INTO purchase_requests_details (purchase_request,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                        $res3 = $cls->exeQuery($sql3);
                        if (!$res3) {
                            $check = false;
                        }

                    }else{

                        $check = false;

                    }
                }

                if($check){

                    $cls->commitSet();
                    $mensaje = array('success' => true, 'mens' => 'Requisición de compra actualizada con exito.');

                }else{

                    $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

                }

            }else{

                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);


            }


        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }else{

        $mensaje = array('success' => false, 'mens' => 'No se puede editar esta requisición porque ya se encuentra cerrada.');
    }


}
if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-REQUEST-DETAILS') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_compra,t2.name FROM purchase_requests_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_request = '$id' ";
        $result_lis = $cls->consultListQuery($sql);//query
        $mensaje = $result_lis;

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'CLOSE-REQUEST') {
    $cls->autocommitF();


    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $sql = "UPDATE purchase_requests set status='CERRADO',updated_by='$VAR_SESSION->username', updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Requisición de compra ha sido cerrada con exito.','reload'=>true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}


if (isset($_POST['a']) && $_POST['a'] == 'CREATE-PURCHASE-ORDER') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['provider'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if (!isset($_POST['purchase_request'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo requisición es obligatorio');
    }
    if(!isset($_POST['data_table'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    $id = $cls->getId_autoincrement("purchase_requests");
    $mensaje = $_POST;
    $date = $_POST['date'];
    $provider = $_POST['provider'];
    $comment = trim($_POST['comment']);
    $purchase_request = trim($_POST['purchase_request']);
    $sql1="INSERT INTO purchase_orders (id,
                             date,
                             provider,
                             purchase_request,
                             comment,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$provider',
                                    '$purchase_request',
                                    '$comment',
                                    '$datetime',
                                    '$VAR_SESSION->username')";
    $res = $cls->exeQuery($sql1);
    if($res){
        $data_table = json_decode($_POST['data_table']);
        $check = true; // check data table
        foreach ($data_table as $data){

            if($data->costs > 0 && $data->total > 0 && $data->unit){

                $sql3="UPDATE products set price='$data->costs' WHERE id ='$data->product_id'";
                $res3 = $cls->exeQuery($sql3);
                if($res3){

                    $sql2="INSERT INTO purchase_orders_details (purchase_order,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                    $res2 = $cls->exeQuery($sql2);
                    if (!$res2) {
                        $check = false;
                    }

                }else{

                    $check = false;

                }



            }else{

                $check = false;

            }
        }

        if($check){

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Orden de compra registrada con exito.', 'url' => './?view=purchase-order-edit&id=' . $id, "post_name" => "Orden de compra", "id" => $id);

        }else{

            $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

        }
    }else {
        $cls->exeQuery('ROLLBACK');
        $mensaje = array('success' => false, 'mens' => $res);

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-PURCHASE-ORDER') {
    $cls->autocommitF();
    $check = true;
    $mensaje = json_decode($_POST['data_table']);
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['provider'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if(!isset($_POST['data_table'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if (!isset($_POST['purchase_request'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo requisición es obligatorio');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }


    $date = $_POST['date'];
    $id = $_POST['id'];
    $provider = $_POST['provider'];
    $comment = trim($_POST['comment']);
    $purchase_request = trim($_POST['purchase_request']);

    $sqlstatus ="SELECT status FROM purchase_orders WHERE id='$id'";
    $response = $cls->consulQuery($sqlstatus);
    if($response['status']!="CERRADO"){
        $sql1="UPDATE purchase_orders set date ='$date',
                             provider = '$provider',
                             comment ='$comment',
                             purchase_request='$purchase_request',
                             updated_at = '$datetime',
                             updated_by = '$VAR_SESSION->username' WHERE id='$id'";

        $res = $cls->exeQuery($sql1);
        if ($res) {
            $sql2="DELETE FROM purchase_orders_details WHERE purchase_order='$id'";
            $res2 = $cls->exeQuery($sql2);
            if ($res2) {

                $data_table = json_decode($_POST['data_table']);
                $check = true; // check data table
                foreach ($data_table as $data){

                    if($data->costs > 0 && $data->total > 0 && $data->unit){

                        $sql3="INSERT INTO purchase_orders_details (purchase_order,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                        $res3 = $cls->exeQuery($sql3);
                        if (!$res3) {
                            $check = false;
                        }

                    }else{

                        $check = false;

                    }
                }

                if($check){

                    $cls->commitSet();
                    $mensaje = array('success' => true, 'mens' => 'Orden de compra actualizada con exito.');

                }else{

                    $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

                }

            }else{

                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);


            }


        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }else{

        $mensaje = array('success' => false, 'mens' => 'No se puede editar esta orden de compra porque se encuentra cerrada.');
    }


}
if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-REQUEST-TO-ORDER') {

    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date ,provider,comment FROM purchase_requests t1 WHERE t1.id = '$id' ";
        $result_lis = $cls->consulQuery($sql);//query
        $inputs = array("id"=>$id,
            "data"=>array(array('type'=>'input','id'=>'date','value'=>$result_lis['date']),
                        array('type'=>'select','id'=>'provider','value'=>$result_lis['provider']),
                        array('type'=>'input','id'=>'comment','value'=>$result_lis['comment'])
            ));

        $mensaje = $inputs;
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-ORDER-DETAILS') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_compra,t2.name FROM purchase_orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_order = '$id' ";
        $result_lis = $cls->consultListQuery($sql);//query
        $mensaje = $result_lis;

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'CLOSE-PURCHASE-ORDER') {
    $cls->autocommitF();


    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $sql = "UPDATE purchase_orders set status='CERRADO',updated_by='$VAR_SESSION->username', updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido cerrada con exito.','reload'=>true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'APPROVE-PURCHASE-ORDER') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $sql = "UPDATE purchase_orders set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido aprovada con exito.','reload'=>true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}



if (isset($_POST['a']) && $_POST['a'] == 'LOGIN') {
    $key = $cls->getAuthKey();
    //$sql ="SELECT AES_ENCRYPT('3001', '$key');";
    //$sql2="INSERT INTO users_access(username,password)values('30vitin',AES_ENCRYPT('3001','23097d223405d8228642a'))";

    if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT COUNT(*) FROM users_access WHERE username='$username'";
        $result = $cls->consulQuery($sql);
        if ($result[0] > 0) {

            $sql22 = "SELECT count(*) FROM users_access WHERE username='$username' and password = AES_ENCRYPT('$password', '$key') limit 1";
            $sql22_res = $cls->consulQuery($sql22);

            if ($sql22_res[0] > 0) {

                $VAR_SESSION = Session::getInstance();
                $CPASS = TRUE;
                $cls->autocommitF();

                // Let's store datas in the session
                $VAR_SESSION->username = $username;
                $VAR_SESSION->loggedin = true;
                $SESSIONID = session_id();
                session_write_close();


                if ($CPASS) {

                    $type = "success";
                    $mens = "";
                    $url = "./?view=dashboard";


                } else {

                    $type = "error";
                    $mens = "Ocurrio un error al autenticar";
                    $url = "";


                }


            } else {

                $type = "error";
                $mens = "Contraseña no valida! ";
                $url = "";
            }


        } else {

            $type = "error";
            $mens = "Usuario no registrado como admin!";
            $url = "";

        }


    } else {
        $type = "error";
        $mens = "Dijiste un email y una contraseña valida";
        $url = "";

    }
    $mensaje = array('type' => $type, 'mens' => $mens, 'url' => $url);

}


echo json_encode($mensaje);
exit;
