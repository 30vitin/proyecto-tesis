<?php

//TODO

//TODO CUANDO CARGO UNA ORDEN DE COMPRA EN EL PEDIDO VALIDAR QUE ESTE DESCUENTE LAS UNIDADES QUE YA SE AN SOLICITADO

//TODO CREAR COTIZACION,FACTURA, PEDIDO
//TODO CREAR ICONO PARA BACK TO LIST (breadcrumb)
//TODO AGREGAR FONDO AL LOGIN CON LOGO DE LA U
//TODO CAMBIAR EL PRIMARY COLOR
//TODO COLOCAR EL LOGO DE LA U EN EL MENU
//TODO CREAR LOS PDF NECESARIOS CON PAGINACION
#Error #001: id no enviados
#Error #002: error al subir archivo
#Error #003: error al actualizar la imagen del producto

require_once 'Config/Functions.php';
$VAR_SESSION = Session::getInstance();

$cls = new Functions;  //llamando al objeto
date_default_timezone_set('America/Panama');

$time = time();
$datetime = date('Y-m-d H:i:s');


$mensaje = array();


/**
 * VENTAS
 *
 */
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-CUSTOMER') {
    $cls->autocommitF();
    $check = true;

    if(!isset($_POST['name'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');

    }
    if(!isset($_POST['email'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo email es obligatorio');
    }
    if(!isset($_POST['telephone1'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Telèfono 1 es obligatorio');
    }
    if(!isset($_POST['type_credit'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Tipo de Crédito es obligatorio');
    }


    if ($check) {
        $id = $cls->getId_autoincrement("customers");

        $name = $_POST['name'];
        $email = $_POST['email'];
        $telephone1 =  $_POST['telephone1'];
        $telephone2 =  $_POST['telephone2'];
        $type_credit = $_POST['type_credit'];

        $sql = "INSERT INTO customers (id,
                       name,
                       email,
                       telephone1,
                       telephone2,
                       type_credit,
                       created_by,
                       created_at)
                values('$id',
                       '$name',
                       '$email',
                       '$telephone1',
                       '$telephone2',
                       '$type_credit',
                       '$VAR_SESSION->username', '$datetime')";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Cliente registrado con exito.', 'url' => './?view=customers-edit&id=' . $id, "post_name" => "Clientes", "id" => $id);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-CUSTOMER') {
    $cls->autocommitF();
    $check = true;

    if(!isset($_POST['name'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');

    }
    if(!isset($_POST['email'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo email es obligatorio');
    }
    if(!isset($_POST['telephone1'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Telèfono 1 es obligatorio');
    }
    if(!isset($_POST['type_credit'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Tipo de Crédito es obligatorio');
    }
    if(!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }
    if ($check) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $telephone1 =  $_POST['telephone1'];
        $telephone2 =  $_POST['telephone2'];
        $type_credit = $_POST['type_credit'];
        $sql = "UPDATE customers 
                       SET name ='$name',
                       email='$email',
                       telephone1 = '$telephone1',
                       telephone2 = '$telephone2',
                       type_credit = '$type_credit',
                       updated_by ='$VAR_SESSION->username',
                       updated_at ='$datetime' where id='$id'";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Cliente actualizado con exito.');

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'DELETE-CUSTOMER') {
    $cls->autocommitF();
    $check = true;
    if(!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }
    if ($check) {
        $id = $_POST['id'];

        $sql = "UPDATE customers set status='DELETE',updated_by='$VAR_SESSION->username',updated_at ='$datetime'WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'url' => './?view=customers');

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    }

}


if (isset($_POST['a']) && $_POST['a'] == 'CREATE-QUOTE') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['purchase_order'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo orden de compra es obligatorio');
    }
    if (!isset($_POST['customer'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }

    $id = $cls->getId_autoincrement("quotes");
    $date = $_POST['date'];
    $customer= $_POST['customer'];
    $comment = trim($_POST['comment']);
    $purchase_order = $_POST['purchase_order'];
    $reference = $_POST['reference'];

    $sql1="INSERT INTO quotes (id,
                             date,
                             customer,
                             comment,
                             purchase_order,
                             reference,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$customer',
                                    '$comment',
                                    '$purchase_order',
                                    '$reference',
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

                    $sql2="INSERT INTO quotes_details (quote,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
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
            $mensaje = array('success' => true, 'mens' => 'Cotización registrada con exito.', 'url' => './?view=quotes-edit&id=' . $id, "post_name" => "Cotización", "id" => $id);

        }else{

            $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

        }
    }else {
        $cls->exeQuery('ROLLBACK');
        $mensaje = array('success' => false, 'mens' => $res);

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-QUOTE') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['purchase_order'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo orden de compra es obligatorio');
    }
    if (!isset($_POST['customer'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if(!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

    $id = $cls->getId_autoincrement("quotes");
    $date = $_POST['date'];
    $customer= $_POST['customer'];
    $comment = trim($_POST['comment']);
    //$purchase_order = $_POST['purchase_order'];
    $reference = $_POST['reference'];

    $sql1="UPDATE quotes SET date ='$date',
                             customer ='$customer',
                             comment ='$comment',
                             reference ='$reference',
                             created_at ='$datetime',
                             created_by ='$VAR_SESSION->username'";
    $mensaje =$_POST;
    $res = $cls->exeQuery($sql1);
    if($res){
        $data_table = json_decode($_POST['data_table']);
        $check = true; // check data table
        foreach ($data_table as $data){

            if($data->costs > 0 && $data->total > 0 && $data->unit){

                $sql3="UPDATE products set price='$data->costs' WHERE id ='$data->product_id'";
                $res3 = $cls->exeQuery($sql3);
                if($res3){

                    $sql2="INSERT INTO quotes_details (quote,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
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
            $mensaje = array('success' => true, 'mens' => 'Cotización actualizada con exito.');

        }else{

            $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

        }
    }else {
        $cls->exeQuery('ROLLBACK');
        $mensaje = array('success' => false, 'mens' => $res);

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'APROVE-QUOTE') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $sql = "UPDATE quotes set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Cotización ha sido aprobada con exito.','reload'=>true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}


if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-ORDER-TO-ORDER') {

    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date,comment,reference FROM purchase_orders t1 WHERE t1.id = '$id' ";
        $result_lis = $cls->consulQuery($sql);//query
        $inputs = array("id"=>$id,
            "data"=>array(
                array('type'=>'input','id'=>'date','value'=>$result_lis['date']),
                array('type'=>'input','id'=>'comment','value'=>$result_lis['comment']),
                array('type'=>'input','id'=>'reference','value'=>$result_lis['reference'])
            ));

        $mensaje = $inputs;
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-ORDER') {

    $mensaje = $_POST;
}

/**
 * INVENTARIO
 *
 */


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
    $reference = $_POST['reference'];

    $sql1="INSERT INTO purchase_requests (id,date,provider,reference,comment,created_at,created_by)values('$id','$date','$provider','$reference', '$comment','$datetime','$VAR_SESSION->username')";
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
    $reference = $_POST['reference'];

    $sqlstatus ="SELECT status FROM purchase_requests WHERE id='$id'";
    $response = $cls->consulQuery($sqlstatus);
    if($response['status']=="ACTIVO"){
        $sql1="UPDATE purchase_requests set date ='$date',
                             provider = '$provider',
                             reference ='$reference',
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
if (isset($_POST['a']) && $_POST['a'] == 'APROVE-PURCHASE-REQUEST') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $sql = "UPDATE purchase_requests set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido aprobada con exito.','reload'=>true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'CANCEL-PURCHASE-REQUEST') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        $comment_canceled="";
        if(isset($_POST['comment_canceled'])){
            $comment_canceled = $_POST['comment_canceled'];
        }
        $sql = "UPDATE purchase_requests set status='CANCELADA',comment_canceled='$comment_canceled',canceled_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', canceled_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido aprobada con exito.','reload'=>true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'CONVERT-TO-PURCHASE-ORDER') {
    $cls->autocommitF();
    $check = true;

    if(!isset($_POST['id'])){
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001 ');

    }
    $request_id = $_POST['id'];
    $sql1="SELECT date,provider,comment,reference,status FROM purchase_requests WHERE id ='$request_id'";
    $response1 = $cls->consulQuery($sql1);
    if ($response1) {
        if($response1['status'] =='APROBADA'){

            $id = $cls->getId_autoincrement("purchase_orders");
            $date = $response1['date'];
            $provider = $response1['provider'];
            $comment = trim($response1['comment']);
            $reference = trim($response1['reference']);
            $sql2="INSERT INTO purchase_orders (id,
                             date,
                             provider,
                             purchase_request,
                             comment,
                             reference,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$provider',
                                    '$request_id',
                                    '$comment',
                                    '$reference',
                                    '$datetime',
                                    '$VAR_SESSION->username')";

            $res2 = $cls->exeQuery($sql2);
            if($res2){

                $sql3="SELECT product_id,costs,units as unit,total FROM purchase_requests_details WHERE purchase_request ='$request_id'";
                $response3 = $cls->consultListQuery($sql3);

                $data_table = $response3;
                $check = true; // check data table
                foreach ($data_table as $data){

                    if($data->costs > 0 && $data->total > 0 && $data->unit){

                        $sql4="UPDATE products set price='$data->costs' WHERE id ='$data->product_id'";
                        $res4 = $cls->exeQuery($sql4);
                        if($res4){

                            $sql5="INSERT INTO purchase_orders_details (purchase_order,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                            $sql5 = $cls->exeQuery($sql5);
                            if (!$sql5) {
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
                    $mensaje = array('success' => true, 'mens' => 'Orden de compra registrada con exito.',
                        'url' => './?view=purchase-order-edit&id=' . $id,
                        "post_name" => "Orden de compra",
                        "id" => $id,"title"=>"Redireccionando a O/C");

                }else{

                    $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos. #001');

                }
            }else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res2);

            }


        }else{

            $mensaje = array('success' => false, 'mens' => 'No se puede convertir a O/C, la requisición tiene que estar aprobada');

        }



    }else{

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001 ');

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
    $id = $cls->getId_autoincrement("purchase_orders");

    $date = $_POST['date'];
    $provider = $_POST['provider'];
    $comment = trim($_POST['comment']);
    $purchase_request = trim($_POST['purchase_request']);
    $reference = $_POST['reference'];
    $sql1="INSERT INTO purchase_orders (id,
                             date,
                             provider,
                             purchase_request,
                             reference,
                             comment,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$provider',
                                    '$purchase_request',
                                    '$reference',
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
    $reference = $_POST['reference'];

    $sqlstatus ="SELECT status FROM purchase_orders WHERE id='$id'";
    $response = $cls->consulQuery($sqlstatus);
    if($response['status']=="ACTIVO"){
        $sql1="UPDATE purchase_orders set date ='$date',
                             provider = '$provider',
                             reference ='$reference',
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
if (isset($_POST['a']) && $_POST['a'] == 'APROVE-PURCHASE-ORDER') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $sql = "UPDATE purchase_orders set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido aprobada con exito.','reload'=>true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'CANCEL-PURCHASE-ORDER') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        $comment_canceled="";
        if(isset($_POST['comment_canceled'])){
            $comment_canceled = $_POST['comment_canceled'];
        }

        $sql = "UPDATE purchase_orders set status='CANCELADA',comment_canceled='$comment_canceled',canceled_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', canceled_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido cancelada con exito.','reload'=>true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su adminsitrador de sistema #001');
    }

}

if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-ORDER-TO-QUOTE') {

    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date,comment,reference FROM purchase_orders t1 WHERE t1.id = '$id' ";
        $result_lis = $cls->consulQuery($sql);//query
        $inputs = array("id"=>$id,
            "data"=>array(
                array('type'=>'input','id'=>'date','value'=>$result_lis['date']),
                array('type'=>'input','id'=>'reference','value'=>$result_lis['reference']),
                array('type'=>'input','id'=>'comment','value'=>$result_lis['comment'])
            ));

        $mensaje = $inputs;
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
