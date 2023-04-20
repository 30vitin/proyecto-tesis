<?php

//TODO


#Error #001: id no enviados
#Error #002: error al subir archivo
#Error #003: error al actualizar la imagen del producto
#Error #004: error al actualizar el status
#Error #005: error al guardar permisos,no se postearon los permisos

require_once 'Config/Functions.php';
$VAR_SESSION = Session::getInstance();

$cls = new Functions;  //llamando al objeto
date_default_timezone_set('America/Panama');

$time = time();
$datetime = date('Y-m-d H:i:s');


$mensaje = array();

/**
 * ALMACEN
 *
 */

if (isset($_POST['a']) && $_POST['a'] == 'GET-BILLS-TO-RECEIVE-MERCHANT') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date,comment,reference FROM bills  WHERE id = '$id' ";
        //$result_lis = $cls->consulQuery($sql);//query
        /*$inputs = array("id" => $id,
            "data" => array(array('type' => 'input', 'id' => 'date', 'value' => $result_lis['date']),
                array('type' => 'input', 'id' => 'reference', 'value' => $result_lis['reference']),
                array('type' => 'input', 'id' => 'comment', 'value' => $result_lis['comment'])
            ));*/

        // $mensaje = $inputs;
        $mensaje = array("id" => $id);
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'GET-BILLS-DETAILS-TO-RECEIVE-MERCHANT') {

    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_compra,t2.name FROM bills_details t1 join products t2 on t1.product_id = t2.id WHERE t1.bill = '$id' ";
        $result_lis = $cls->consultListQuery($sql);//query
        $mensaje = $result_lis;

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-RECEIVE-MERCHANT') {
    $cls->autocommitF();
    $check = true;

    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['bills'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo factura es obligatorio');
    }
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }

    $bills = $_POST['bills'];

    $sqlcheck = "SELECT COUNT(*) as count FROM bills WHERE id ='$bills' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta recepción de mercancía porque la factura #" . $bills . " debe estar APROBADA");

    }

    //checar si ya fue completada
    if ($cls->chetIfBillsIsComplete($bills)) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => 'La factura # ' . $bills . ' ya fue completada ');

    }

    //checar que no la hayan recibido
    if ($cls->checkIfAlreadyReceived($bills)) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => 'La factura # ' . $bills . ' ya fue recibida ');

    }

    if ($check) {
        $id = $cls->getId_autoincrement("received_merchant");
        $date = $_POST['date'];
        $comment = trim($_POST['comment']);
        $reference = $_POST['reference'];


        $sql1 = "INSERT INTO received_merchant (id,date,bills,comment,reference,created_at,created_by)values('$id','$date','$bills','$comment','$reference','$datetime','$VAR_SESSION->username')";

        $res = $cls->exeQuery($sql1);

        if ($res) {
            $data_table = json_decode($_POST['data_table']);
            $check = true; // check data table
            foreach ($data_table as $data) {

                if ($data->costs > 0 && $data->total > 0 && $data->unit > 0) {

                    $sql2 = "INSERT INTO received_merchant_details (received,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                    $res2 = $cls->exeQuery($sql2);
                    if (!$res2) {
                        $check = false;
                    }


                } else {

                    $check = false;

                }
            }

            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Recepción registrada con exito.', 'url' => './?view=receive-merchant-edit&id=' . $id, "post_name" => "Recepción de Mercancía", "id" => $id);

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}
if (isset($_POST['a']) && $_POST['a'] == 'GET-MERCHANT-DETAILS-TO-RECEIVE-MERCHANT-DETAILS') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_almacen as unidad_para_compra,t2.name FROM received_merchant_details t1 join products t2 on t1.product_id = t2.id WHERE t1.received = '$id' ";
        $result_lis = $cls->consultListQuery($sql);//query
        $mensaje = $result_lis;

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-RECEIVE-MERCHANT') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['bills'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo factura es obligatorio');
    }


    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

    $bills = $_POST['bills'];

    $sqlcheck = "SELECT COUNT(*) as count FROM bills WHERE id ='$bills' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta recepció porque la factura #" . $bills . " debe estar APROBADA");

    }

    $received_id = $_POST['id'];


    $sqlcheck2 = "SELECT COUNT(*) as count FROM received_merchant WHERE id ='$received_id' and status ='ACTIVO'";
    $rescheck2 = $cls->consulQuery($sqlcheck2);
    if ($rescheck2['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "Para editar la recepción de mercancía debe estar ACTIVA");

    }


    $date = $_POST['date'];
    $comment = trim($_POST['comment']);
    $reference = $_POST['reference'];


    if ($check) {

        $sql1 = "UPDATE received_merchant set date ='$date',
                                bills = '$bills',
                                comment ='$comment',
                                reference ='$reference',
                                updated_at ='$datetime',
                                updated_by ='$VAR_SESSION->username'
                                WHERE id ='$received_id'";
        $res = $cls->exeQuery($sql1);
        if ($res) {

            $sql2 = "DELETE FROM received_merchant_details WHERE received='$received_id'";
            $res2 = $cls->exeQuery($sql2);
            if ($res2) {
                $data_table = json_decode($_POST['data_table']);

                foreach ($data_table as $data) {

                    if ($data->costs > 0 && $data->total > 0 && $data->unit) {

                        $sql2 = "INSERT INTO received_merchant_details (received,product_id,costs,units,total)values('$received_id','$data->product_id','$data->costs','$data->unit','$data->total')";
                        $res2 = $cls->exeQuery($sql2);
                        if (!$res2) {
                            $check = false;
                        }


                    } else {

                        $check = false;

                    }
                }

                if ($check) {

                    $cls->commitSet();
                    $mensaje = array('success' => true, 'mens' => 'Recepción de mercancía actualizada con exito.');
                } else {

                    $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

                }

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

            }


        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}
if (isset($_POST['a']) && $_POST['a'] == 'APROVE-RECEIVE-MERCHANT') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');

    }

    $id = $_POST['id'];


    $sql11 = "SELECT bills,status FROM received_merchant WHERE id ='$id'";
    $resl11 = $cls->consulQuery($sql11);

    $bills = $resl11['bills'];


    $sqlcheck = "SELECT COUNT(*) as count FROM bills WHERE id ='$bills' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta recepción porque la factura #" . $bills . " debe estar APROBADA");

    }


    if ($resl11['status'] != "ACTIVO") {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede APROBAR esta recepción de mercancía porque debe estar ACTIVA");

    }

    if ($check) {

        $sql = "UPDATE received_merchant set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Recepción de mercancía ha sido aprobada con exito.', 'reload' => true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}


if (isset($_POST['a']) && $_POST['a'] == 'CLOSE-RECEIVE-MERCHANT') {
    $cls->autocommitF();

    $check = true;

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');

    }


    $id = $_POST['id'];

    $sqlstatus = "SELECT COUNT(*) AS count FROM received_merchant WHERE id='$id' AND status ='APROBADA'";
    $response = $cls->consulQuery($sqlstatus);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Para cerrar esta recepción debe estar APROBADO');
    }


    $sqlc = "SELECT COUNT(*) as count FROM dispatch_merchant WHERE received ='$id' and status ='ACTIVO'";
    $responsesc = $cls->consulQuery($sqlc);
    if ($responsesc['count'] > 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede cerrr esta recepción porque tiene despachos ACTIVOS');
    }

    if ($check) {

        $sql = "UPDATE received_merchant set status='CERRADO',updated_by='$VAR_SESSION->username',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Recepción ha sido cerrado con exito.', 'reload' => true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}


if (isset($_POST['a']) && $_POST['a'] == 'GET-RECEIVED-MERCHANT-TO-DISPATCH-MERCHANT') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date,comment,reference FROM received_merchant  WHERE id = '$id' ";
        // $result_lis = $cls->consulQuery($sql);//query
        /*$inputs = array("id" => $id,
            "data" => array(array('type' => 'input', 'id' => 'date', 'value' => $result_lis['date']),
                array('type' => 'input', 'id' => 'reference', 'value' => $result_lis['reference']),
                array('type' => 'input', 'id' => 'comment', 'value' => $result_lis['comment'])
            ));*/

        //$mensaje = $inputs;
        $mensaje = array("id" => $id);
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-DISPATCH-MERCHANT') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['received'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo recepción es obligatorio');
    }
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }

    $received = $_POST['received'];

    $sqlcheck = "SELECT COUNT(*) as count FROM received_merchant WHERE id ='$received' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear este despacho de mercancía porque la recepción #" . $received . " debe estar APROBADA");

    }


    if ($check) {
        $id = $cls->getId_autoincrement("dispatch_merchant");
        $date = $_POST['date'];
        $comment = trim($_POST['comment']);
        $reference = $_POST['reference'];

        if ($check) {

            $sql1 = "INSERT INTO dispatch_merchant(id,
                             date,
                             comment,
                             received,
                             reference,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$comment',
                                    '$received',
                                    '$reference',
                                    '$datetime',
                                    '$VAR_SESSION->username')";
            $res = $cls->exeQuery($sql1);
            if ($res) {
                $data_table = json_decode($_POST['data_table']);
                $check = true; // check data table
                $units = 0;
                $units_request = 0;
                foreach ($data_table as $data) {
                    $units += $data->unit;
                    $units_request += $data->units_request;
                    if (($data->unit == 0 && $data->units_request == 0) || ($data->unit > 0 && $data->units_request > 0)) {


                        $sql2 = "INSERT INTO dispatch_merchant_details (dispatch,product_id,units_buy,units_request,units_diff)values
                                                                                       ('$id',
                                                                                        '$data->product_id',
                                                                                        '$data->unit',
                                                                                        '$data->units_request',
                                                                                        '$data->units_diff')";
                        $res2 = $cls->exeQuery($sql2);
                        if (!$res2) {
                            $check = false;
                        }


                    } else {

                        $check = false;

                    }
                }

                if ($units > 0 && $units_request > 0) {
                    if ($check) {

                        $cls->commitSet();
                        $mensaje = array('success' => true, 'mens' => 'Despacho de mercancía registrado con exito.', 'url' => './?view=dispatch-merchant-edit&id=' . $id, "post_name" => "Despacho de mercancía", "id" => $id);

                    } else {

                        $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

                    }
                } else {

                    $mensaje = array('success' => false, 'mens' => 'No se puede crear mas despacho de mercancía con la recepción #' . $received . " porque ya se fue completada");

                }

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }

        }


    }


}
if (isset($_POST['a']) && $_POST['a'] == 'GET-RECEIVED-MERCHANT-DETAILS-TO-DISPATCH-MERCHANT') {

    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $mensaje = $cls->getReceivedMerchantToDispatch($id);

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'GET-DISPATCH-MERCHANT-DETAILS-TO-DISPATCH-MERCHANT-DETAILS') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT t1.product_id as id,t1.units_buy as unit,t1.units_request as units_request,t1.units_diff,t2.unidad_para_almacen as unidad_para_compra,t2.name FROM dispatch_merchant_details t1 join products t2 on t1.product_id = t2.id WHERE t1.dispatch = '$id' ";
        $result_lis = $cls->consultListQuery($sql);//query
        $mensaje = $result_lis;

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-DISPATCH-MERCHANT') {

    $mensaje = $_POST;
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['received'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo recepción es obligatorio');
    }
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

    $dispatch_id = $_POST['id'];
    $mensaje = $_POST;
    $received = $_POST['received'];

    $sqlstatus = "SELECT COUNT(*) AS count FROM dispatch_merchant WHERE id='$dispatch_id' AND status ='ACTIVO'";
    $response = $cls->consulQuery($sqlstatus);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Para editar este despacho, debe estar activa');
    }

    $sqlcheck = "SELECT COUNT(*) as count FROM received_merchant WHERE id ='$received' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede editar este despacho porque la recepción de mercancía #" . $received . " debe estar APROBADA");

    }

    //checar unidades que traigo con las que ya entan en otros despachos
    if (!$cls->checkUnitsReceivedDispatch(json_decode($_POST['data_table']), $received, $dispatch_id)) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede editar este despacho, favor validar las unidades solicitadas puede quedar en negativo.");

    }

    if ($check) {

        $date = $_POST['date'];
        $comment = trim($_POST['comment']);
        $reference = $_POST['reference'];

        $sql1 = "UPDATE dispatch_merchant 
                             SET date ='$date',
                             comment ='$comment',
                             received ='$received',
                             reference ='$reference',
                             updated_at ='$datetime',
                             updated_by ='$VAR_SESSION->username'";

        $res = $cls->exeQuery($sql1);
        if ($res) {


            $sql21 = "DELETE FROM dispatch_merchant_details WHERE dispatch ='$dispatch_id'";
            $res21 = $cls->exeQuery($sql21);
            if ($res21) {

                $data_table = json_decode($_POST['data_table']);

                foreach ($data_table as $data) {

                    if (($data->unit == 0 && $data->units_request == 0) || ($data->unit > 0 && $data->units_request > 0)) {

                        $sql2 = "INSERT INTO dispatch_merchant_details (dispatch,product_id,units_buy,units_request,units_diff)values
                                                                                       ('$dispatch_id',
                                                                                        '$data->product_id',
                                                                                        '$data->unit',
                                                                                        '$data->units_request',
                                                                                        '$data->units_diff')";
                        $res2 = $cls->exeQuery($sql2);
                        if (!$res2) {
                            $check = false;
                        }


                    } else {

                        $check = false;

                    }
                }

            } else {

                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res21);
            }


            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Despacho actualizado con exito.');

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }


    }

}
if (isset($_POST['a']) && $_POST['a'] == 'APROVE-DISPATCH-MERCHANT') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');

    }

    $id = $_POST['id'];


    $sql11 = "SELECT received,status FROM dispatch_merchant WHERE id ='$id'";
    $resl11 = $cls->consulQuery($sql11);

    $received = $resl11['received'];


    $sqlcheck = "SELECT COUNT(*) as count FROM received_merchant WHERE id ='$received' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear este despacho porque la recepción #" . $received . " debe estar APROBADA " . $sqlcheck);

    }


    if ($resl11['status'] != "ACTIVO") {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede APROBAR este despacho de mercancía porque debe estar ACTIVA");

    }

    if ($check) {

        $sql = "UPDATE dispatch_merchant set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Despacho de mercancía ha sido aprobada con exito.', 'reload' => true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}

if (isset($_POST['a']) && $_POST['a'] == 'CLOSE-DISPATCH-MERCHANT') {
    $cls->autocommitF();

    $check = true;

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');

    }


    $id = $_POST['id'];

    $sqlstatus = "SELECT COUNT(*) AS count FROM dispatch_merchant WHERE id='$id' AND status ='APROBADA'";
    $response = $cls->consulQuery($sqlstatus);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Para cerrar este despacho, debe estar APROBADO');
    }

    if ($check) {

        $sql = "UPDATE dispatch_merchant set status='CERRADO',updated_by='$VAR_SESSION->username',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Despacho ha sido cerrado con exito.', 'reload' => true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}
if (isset($_POST['a']) && $_POST['a'] == 'GET-DISPATCH-RELATED-RECEIVE-MERCHANT') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT  id as id,received,date,sum(units_buy) as units_buy,sum(units_request) as units_request,sum(units_diff) as units_diff, status FROM (
                                                    (SELECT  t1.id,t3.units_buy,t3.units_request,t3.units_diff,DATE_FORMAT(t1.date,'%Y-%m-%d') as date,t1.status,t1.received
                                                    FROM dispatch_merchant t1 
                                                    join dispatch_merchant_details t3 on t1.id = t3.dispatch 
                                                    WHERE t1.status <>'DELETE' and t1.received='$id'
																										) as datas
                                                    
                                            ) group by id ORDER BY date desc";
        $result_lis = $cls->consultListQuery($sql);//query
        $data = [];
        foreach ($result_lis as $result) {

            $data[] = array("Id" => $result->id, "Fecha" => $result->date, "Compradas" => $result->units_buy,
                "Solicitadas" => $result->units_request, "Diferencia" => $result->units_diff, "Status" => $result->status);
        }
        $mensaje = $data;

    }

}

/**
 * CONFIGURACIONES
 * 
 */
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-PASSWORD') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['current_password'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo de contraseña actual es obligatorio.');
    }
    if (!isset($_POST['new_password'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo de nueva contraseña es obligatorio.');
    }
    if (!isset($_POST['password_confirm'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo de repetir nueva contraseña es obligatorio.');
    }
    if ($_POST['new_password'] != $_POST['password_confirm']) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Las contraseñas no coinciden.');
    }
    $key = $cls->getAuthKey();
    $password = $_POST['current_password'];
    $sql22 = "SELECT count(*) FROM users_access WHERE username='$VAR_SESSION->username' and password = AES_ENCRYPT('$password', '$key') limit 1";
    $sql22_res = $cls->consulQuery($sql22);
    if ($sql22_res[0] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Las contraseña actual es incorrecta.');
    }
    if ($check) {
        $password_confirm = $_POST['password_confirm'];
        $sql = "SELECT AES_ENCRYPT('$password_confirm', '$key') as newpassword;";
        $res = $cls->consulQuery($sql);
        $new_password = $res['newpassword'];

        $sql2 = "UPDATE users_access SET password='$new_password',updated_at='$datetime',updated_by='$VAR_SESSION->username' WHERE username ='$VAR_SESSION->username' limit 1";
        $res2 = $cls->exeQuery($sql2);
        if ($res2) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Contraseña actualizada con exito.');

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res2);

        }

    }

}


if (isset($_POST['a']) && $_POST['a'] == 'PURCHASE-ORDER-SET-STATUS-GET-CURRENT-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $id = $_POST['id'];

    $sql = "SELECT status FROM purchase_orders WHERE id ='$id' limit 1";
    $res = $cls->consulQuery($sql);
    $status = $res['status'];
    $mensaje = array('success' => true, 'status' => $status);


}
if (isset($_POST['a']) && $_POST['a'] == 'PURCHASE-ORDER-SET-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    if (!isset($_POST['status'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #002');
    }
    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($check) {
        $extraitem = "";
        if ($status == "CANCELADA") {
            $extraitem = ",comment_canceled ='cancelada desde el administrador',canceled_by='$VAR_SESSION->username',canceled_at='$datetime'";
        }
        $observations = "";
        if ($extraitem == "") {
            $observations = ",observations='Cambio efectuado desde el administrado de status [$status] :: $datetime'";

        }

        $sql = "UPDATE purchase_orders SET status ='$status',updated_by='$VAR_SESSION->username',updated_at ='$datetime' $extraitem $observations WHERE id ='$id' limit 1";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Status actualizado con exito.', 'reload' => true);
        } else {

            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'PURCHASE-REQUEST-SET-STATUS-GET-CURRENT-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $id = $_POST['id'];

    $sql = "SELECT status FROM purchase_requests WHERE id ='$id' limit 1";
    $res = $cls->consulQuery($sql);
    $status = $res['status'];
    $mensaje = array('success' => true, 'status' => $status);


}
if (isset($_POST['a']) && $_POST['a'] == 'PURCHASE-REQUEST-SET-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    if (!isset($_POST['status'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #002');
    }
    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($check) {
        $extraitem = "";
        if ($status == "CANCELADA") {
            $extraitem = ",comment_canceled ='cancelada desde el administrador',canceled_by='$VAR_SESSION->username',canceled_at='$datetime'";
        }
        $observations = "";
        if ($extraitem == "") {
            $observations = ",observations='Cambio efectuado desde el administrado de status [$status] :: $datetime'";
        }
        $sql = "UPDATE purchase_requests SET status ='$status',updated_by='$VAR_SESSION->username',updated_at ='$datetime' $extraitem $observations WHERE id ='$id' limit 1";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Status actualizado con exito.', 'reload' => true);
        } else {

            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'ORDERS-SET-STATUS-GET-CURRENT-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $id = $_POST['id'];

    $sql = "SELECT status FROM orders WHERE id ='$id' limit 1";
    $res = $cls->consulQuery($sql);
    $status = $res['status'];
    $mensaje = array('success' => true, 'status' => $status);


}
if (isset($_POST['a']) && $_POST['a'] == 'ORDERS-SET-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    if (!isset($_POST['status'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #002');
    }
    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($check) {
        $extraitem = "";
        if ($status == "CANCELADA") {
            $extraitem = ",comment_canceled ='cancelada desde el administrador',canceled_by='$VAR_SESSION->username',canceled_at='$datetime'";
        }
        $observations = "";
        if ($extraitem == "") {
            $observations = ",observations='Cambio efectuado desde el administrado de status [$status] :: $datetime'";
        }

        $sql = "UPDATE orders SET status ='$status',updated_by='$VAR_SESSION->username',updated_at ='$datetime' $extraitem $observations WHERE id ='$id' limit 1";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Status actualizado con exito.', 'reload' => true);
        } else {

            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'BILLS-SET-STATUS-GET-CURRENT-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $id = $_POST['id'];

    $sql = "SELECT status FROM bills WHERE id ='$id' limit 1";
    $res = $cls->consulQuery($sql);
    $status = $res['status'];
    $mensaje = array('success' => true, 'status' => $status);


}
if (isset($_POST['a']) && $_POST['a'] == 'BILLS-SET-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    if (!isset($_POST['status'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #002');
    }
    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($check) {
        $extraitem = "";
        if ($status == "CANCELADA") {
            $extraitem = ",comment_canceled ='Cancelada desde el administrador',canceled_by='$VAR_SESSION->username',canceled_at='$datetime'";
        }
        $observations = "";
        if ($extraitem == "") {
            $observations = ",observations='Cambio efectuado desde el administrado de status [$status] :: $datetime'";
        }

        $sql = "UPDATE bills SET status ='$status',updated_by='$VAR_SESSION->username',updated_at ='$datetime' $extraitem $observations WHERE id ='$id' limit 1";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Status actualizado con exito.', 'reload' => true);
        } else {

            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }

}

if (isset($_POST['a']) && $_POST['a'] == 'RECEIVE-MERCHANT-SET-STATUS-GET-CURRENT-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $id = $_POST['id'];

    $sql = "SELECT status FROM received_merchant WHERE id ='$id' limit 1";
    $res = $cls->consulQuery($sql);
    $status = $res['status'];
    $mensaje = array('success' => true, 'status' => $status);


}
if (isset($_POST['a']) && $_POST['a'] == 'RECEIVE-MERCHANT-SET-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    if (!isset($_POST['status'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #002');
    }
    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($check) {
        $extraitem = "";
        if ($status == "CANCELADA") {
            $extraitem = ",comment_canceled ='Cancelada desde el administrador',canceled_by='$VAR_SESSION->username',canceled_at='$datetime'";
        }
        $observations = "";
        if ($extraitem == "") {
            $observations = ",observations='Cambio efectuado desde el administrado de status [$status] :: $datetime'";
        }

        $sql = "UPDATE received_merchant SET status ='$status',updated_by='$VAR_SESSION->username',updated_at ='$datetime' $extraitem $observations WHERE id ='$id' limit 1";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Status actualizado con exito.', 'reload' => true);
        } else {

            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }

}

if (isset($_POST['a']) && $_POST['a'] == 'DISPATCH-MERCHANT-SET-STATUS-GET-CURRENT-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $id = $_POST['id'];

    $sql = "SELECT status FROM dispatch_merchant WHERE id ='$id' limit 1";
    $res = $cls->consulQuery($sql);
    $status = $res['status'];
    $mensaje = array('success' => true, 'status' => $status);


}
if (isset($_POST['a']) && $_POST['a'] == 'DISPATCH-MERCHANT-SET-STATUS') {

    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    if (!isset($_POST['status'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #002');
    }
    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($check) {
        $extraitem = "";
        if ($status == "CANCELADA") {
            $extraitem = ",comment_canceled ='Cancelada desde el administrador',canceled_by='$VAR_SESSION->username',canceled_at='$datetime'";
        }
        $observations = "";
        if ($extraitem == "") {
            $observations = ",observations='Cambio efectuado desde el administrado de status [$status] :: $datetime'";
        }

        $sql = "UPDATE dispatch_merchant SET status ='$status',updated_by='$VAR_SESSION->username',updated_at ='$datetime' $extraitem $observations WHERE id ='$id' limit 1";
        $res = $cls->exeQuery($sql);
        if ($res) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Status actualizado con exito.', 'reload' => true);
        } else {

            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }

}

/**
 * VENTAS
 *
 */
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-BILLS') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['order_id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo orden de compra es obligatorio');
    }
    if (!isset($_POST['customer'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if (!isset($_POST['credit_term'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo tipo de crèdito es obligatorio');
    }
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }

    $order_id = $_POST['order_id'];
    $customer = $_POST['customer'];

    $sqlcheck = "SELECT COUNT(*) as count FROM orders WHERE id ='$order_id' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el pedido #" . $order_id . " debe estar APROBADA");

    }

    $sqlcheck = "SELECT COUNT(*) as count FROM customers WHERE id ='$customer' and status ='DELETE'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] > 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el cliente #" . $customer . " se encuentra eliminado");

    }


    $id = $cls->getId_autoincrement("bills");
    $date = $_POST['date'];
    $comment = trim($_POST['comment']);
    $reference = $_POST['reference'];
    $credit_term = $_POST['credit_term'];

    if ($check) {
        $sql1 = "INSERT INTO bills (id,date,customer,comment,order_id,reference,credit_term,created_at,created_by)values('$id','$date','$customer','$comment','$order_id','$reference','$credit_term','$datetime','$VAR_SESSION->username')";
        //$mensaje = array("slq",$sql1);
        $res = $cls->exeQuery($sql1);
        if ($res) {
            $data_table = json_decode($_POST['data_table']);
            $check = true; // check data table
            foreach ($data_table as $data) {

                if ($data->costs > 0 && $data->total > 0 && $data->unit) {

                    $sql2 = "INSERT INTO bills_details (bill,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                    $res2 = $cls->exeQuery($sql2);
                    if (!$res2) {
                        $check = false;
                    }


                } else {

                    $check = false;

                }
            }

            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Factura registrada con exito.', 'url' => './?view=bills-edit&id=' . $id, "post_name" => "Facturas", "id" => $id);

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-BILLS') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['order_id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo orden de compra es obligatorio');
    }
    if (!isset($_POST['customer'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if (!isset($_POST['credit_term'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo tipo de crèdito es obligatorio');
    }
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $id = $_POST['id'];
    $order_id = $_POST['order_id'];
    $customer = $_POST['customer'];

    $sqlcheck = "SELECT COUNT(*) as count FROM orders WHERE id ='$order_id' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el pedido #" . $order_id . " debe estar APROBADA");

    }

    $sqlcheck = "SELECT COUNT(*) as count FROM customers WHERE id ='$customer' and status ='DELETE'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] > 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el cliente #" . $customer . " se encuentra eliminado");

    }

    $sqlcheck2 = "SELECT COUNT(*) as count FROM bills WHERE id ='$id' and status ='ACTIVO'";
    $rescheck2 = $cls->consulQuery($sqlcheck2);
    if ($rescheck2['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "Para editar la factura debe estar ACTIVA");

    }


    $date = $_POST['date'];

    $comment = trim($_POST['comment']);
    $reference = $_POST['reference'];
    $credit_term = $_POST['credit_term'];

    if ($check) {

        $sql1 = "UPDATE bills set date ='$date',
                                customer ='$customer',
                                comment ='$comment',
                                order_id ='$order_id',
                                reference ='$reference',
                                credit_term ='$credit_term',
                                updated_at ='$datetime',
                                updated_by ='$VAR_SESSION->username'
                                WHERE id ='$id'";
        $res = $cls->exeQuery($sql1);
        if ($res) {

            $sql2 = "DELETE FROM bills_details WHERE bill='$id'";
            $res2 = $cls->exeQuery($sql2);
            if ($res2) {
                $data_table = json_decode($_POST['data_table']);

                foreach ($data_table as $data) {

                    if ($data->costs > 0 && $data->total > 0 && $data->unit) {

                        $sql2 = "INSERT INTO bills_details (bill,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                        $res2 = $cls->exeQuery($sql2);
                        if (!$res2) {
                            $check = false;
                        }


                    } else {

                        $check = false;

                    }
                }

                if ($check) {

                    $cls->commitSet();
                    $mensaje = array('success' => true, 'mens' => 'Factura actualizada con exito.');
                } else {

                    $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

                }

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

            }


        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}
if (isset($_POST['a']) && $_POST['a'] == 'GET-BILLS-DETAILS-TO-BILLS') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_compra,t2.name FROM bills_details t1 join products t2 on t1.product_id = t2.id WHERE t1.bill = '$id' ";
        $result_lis = $cls->consultListQuery($sql);//query
        $mensaje = $result_lis;

    }
} 
if (isset($_POST['a']) && $_POST['a'] == 'APROVE-BILLS') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        $check = true;

        $sql11 = "SELECT order_id,customer,status FROM bills WHERE id ='$id'";
        $resl11 = $cls->consulQuery($sql11);

        $order_id = $resl11['order_id'];
        $customer = $resl11['customer'];

        if ($customer == "") {

            $check = false;
            $mensaje = array('success' => false, 'mens' => "El campo cliente es obligatorio para aprobar");

        }

        $sqlcheck = "SELECT COUNT(*) as count FROM orders WHERE id ='$order_id' and status ='APROBADA'";
        $rescheck = $cls->consulQuery($sqlcheck);
        if ($rescheck['count'] == 0) {

            $check = false;
            $mensaje = array('success' => false, 'mens' => "No se puede crear esta factura porque el pedido #" . $order_id . " debe estar APROBADA");

        }

        $sqlcheck = "SELECT COUNT(*) as count FROM customers WHERE id ='$customer' and status ='DELETE'";
        $rescheck = $cls->consulQuery($sqlcheck);
        if ($rescheck['count'] > 0) {

            $check = false;
            $mensaje = array('success' => false, 'mens' => "No se puede crear esta factura porque el cliente #" . $customer . " se encuentra eliminado");

        }

        if ($resl11['status'] != "ACTIVO") {

            $check = false;
            $mensaje = array('success' => false, 'mens' => "No se puede APROBAR esta factura porque debe estar ACTIVA");

        }

        if ($check) {

            $sql = "UPDATE bills set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Factura ha sido aprobada con exito.', 'reload' => true);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }

        }

    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

}

if (isset($_POST['a']) && $_POST['a'] == 'CLOSE-BILLS') {
    $cls->autocommitF();


    $check = true;

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');

    }


    $id = $_POST['id'];

    $sqlstatus = "SELECT COUNT(*) AS count FROM bills WHERE id='$id' AND status ='APROBADA'";
    $response = $cls->consulQuery($sqlstatus);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Para cerrar esta factura, debe estar APROBADO');
    }

    if ($check) {

        $sql = "UPDATE bills set status='CERRADO',updated_by='$VAR_SESSION->username',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Factura ha sido cerrado con exito.', 'reload' => true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}


//listo
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-QUOTE') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['date'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo fecha es obligatorio');
    }
    if (!isset($_POST['order_id'])) {
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

    $order_id = $_POST['order_id'];
    $customer = $_POST['customer'];

    $sqlcheck = "SELECT COUNT(*) as count FROM orders WHERE id ='$order_id' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el pedido #" . $order_id . " debe estar APROBADA");

    }

    $sqlcheck = "SELECT COUNT(*) as count FROM customers WHERE id ='$customer' and status ='DELETE'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] > 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el cliente #" . $customer . " se encuentra eliminado");

    }


    $id = $cls->getId_autoincrement("quotes");
    $date = $_POST['date'];

    $comment = trim($_POST['comment']);
    $reference = $_POST['reference'];


    $date_expired = date("Y-m-d");
    $days_expired = $_POST['days_expired'];
    if ($_POST['days_expired'] > 0) {

        $date_expired = $cls->getDaystoAddDays(date("Y-m-d"), $_POST['days_expired']);

    }


    $sql1 = "INSERT INTO quotes (id,
                             date,
                             customer,
                             comment,
                             order_id,
                             reference,
                             date_expire,
                             days_expired,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$customer',
                                    '$comment',
                                    '$order_id',
                                    '$reference',
                                    '$date_expired',
                                    '$days_expired',
                                    '$datetime',
                                    '$VAR_SESSION->username')";
    $res = $cls->exeQuery($sql1);
    if ($res) {
        $data_table = json_decode($_POST['data_table']);
        $check = true; // check data table
        foreach ($data_table as $data) {

            if ($data->costs > 0 && $data->total > 0 && $data->unit) {

                $sql2 = "INSERT INTO quotes_details (quote,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                $res2 = $cls->exeQuery($sql2);
                if (!$res2) {
                    $check = false;
                }


            } else {

                $check = false;

            }
        }

        if ($check) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Cotización registrada con exito.', 'url' => './?view=quotes-edit&id=' . $id, "post_name" => "Cotización", "id" => $id);

        } else {

            $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

        }
    } else {
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
    if (!isset($_POST['order_id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo pedido es obligatorio');
    }
    if (!isset($_POST['customer'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo proveedor es obligatorio');
    }
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $id = $_POST['id'];
    $order_id = $_POST['order_id'];
    $customer = $_POST['customer'];

    $sqlcheck = "SELECT COUNT(*) as count FROM orders WHERE id ='$order_id' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el pedido #" . $order_id . " debe estar APROBADA");

    }

    $sqlcheck = "SELECT COUNT(*) as count FROM customers WHERE id ='$customer' and status ='DELETE'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] > 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el cliente #" . $customer . " se encuentra eliminado");

    }


    if ($check) {
        $date = $_POST['date'];
        $customer = $_POST['customer'];
        $comment = trim($_POST['comment']);
        $reference = $_POST['reference'];
        $date_expired = "";
        $days_expired = 0;
        $extrasql = "";
        if ($_POST['days_expired'] > 0) {
            $days_expired = $_POST['days_expired'];
            $date_expired = $cls->getDaystoAddDays(date("Y-m-d"), $days_expired);

            $extrasql = ",days_expired='$days_expired',date_expire='$date_expired'";

        }
        $sql1 = "UPDATE quotes SET date ='$date',customer ='$customer',order_id='$order_id',comment ='$comment',reference ='$reference',updated_at ='$datetime',updated_by ='$VAR_SESSION->username'";
        if ($_POST['days_expired'] > 0) {
            $sql1 .= $extrasql;
        }
        $sql1 .= " WHERE id='$id'";

        $res = $cls->exeQuery($sql1);
        if ($res) {

            //consultar e insertar
            //por aqui
            $sql2 = "DELETE FROM quotes_details WHERE quote='$id'";
            $res2 = $cls->exeQuery($sql2);
            if ($res2) {
                $data_table = json_decode($_POST['data_table']);

                foreach ($data_table as $data) {

                    if ($data->costs > 0 && $data->total > 0 && $data->unit) {

                        $sql2 = "INSERT INTO quotes_details (quote,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                        $res2 = $cls->exeQuery($sql2);
                        if (!$res2) {
                            $check = false;
                        }


                    } else {

                        $check = false;

                    }
                }

                if ($check) {

                    $cls->commitSet();
                    $mensaje = array('success' => true, 'mens' => 'Cotización actualizada con exito.');

                } else {

                    $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

                }

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

            }

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}
if (isset($_POST['a']) && $_POST['a'] == 'APROVE-QUOTE') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        $check = true;

        $sql11 = "SELECT order_id,customer,status FROM quotes WHERE id ='$id'";
        $resl11 = $cls->consulQuery($sql11);

        $order_id = $resl11['order_id'];
        $customer = $resl11['customer'];

        $sqlcheck = "SELECT COUNT(*) as count FROM orders WHERE id ='$order_id' and status ='APROBADA'";
        $rescheck = $cls->consulQuery($sqlcheck);
        if ($rescheck['count'] == 0) {

            $check = false;
            $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el pedido #" . $order_id . " debe estar APROBADA");

        }

        $sqlcheck = "SELECT COUNT(*) as count FROM customers WHERE id ='$customer' and status ='DELETE'";
        $rescheck = $cls->consulQuery($sqlcheck);
        if ($rescheck['count'] > 0) {

            $check = false;
            $mensaje = array('success' => false, 'mens' => "No se puede crear esta cotización porque el cliente #" . $customer . " se encuentra eliminado");

        }

        if ($resl11['status'] != "ACTIVO") {

            $check = false;
            $mensaje = array('success' => false, 'mens' => "No se puede APROBAR esta cotizacion porque debe estar activa");

        }

        if ($check) {

            $sql = "UPDATE quotes set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Cotización ha sido aprobada con exito.', 'reload' => true);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }

        }

    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'GET-QUOTES-DETAILS-TO-QUOTE') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_compra,t2.name FROM quotes_details t1 join products t2 on t1.product_id = t2.id WHERE t1.quote = '$id' ";
        $result_lis = $cls->consultListQuery($sql);//query
        $mensaje = $result_lis;

    }
}


//listo
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-CUSTOMER') {
    $cls->autocommitF();
    $check = true;

    if (!isset($_POST['name'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');

    }
    if (!isset($_POST['email'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo email es obligatorio');
    }
    if (!isset($_POST['telephone1'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Telèfono 1 es obligatorio');
    }
    if (!isset($_POST['type_credit'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Tipo de Crédito es obligatorio');
    }


    if ($check) {
        $id = $cls->getId_autoincrement("customers");

        $name = $_POST['name'];
        $email = $_POST['email'];
        $telephone1 = $_POST['telephone1'];
        $telephone2 = $_POST['telephone2'];
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

    if (!isset($_POST['name'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');

    }
    if (!isset($_POST['email'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo email es obligatorio');
    }
    if (!isset($_POST['telephone1'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Telèfono 1 es obligatorio');
    }
    if (!isset($_POST['type_credit'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo Tipo de Crédito es obligatorio');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $id = $_POST['id'];
    $sqlstatus = "SELECT COUNT(*) AS count FROM customers WHERE id='$id' AND status ='ACTIVO'";
    $response = $cls->consulQuery($sqlstatus);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Para editar este cliente, debe estar activa');
    }

    if ($check) {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $telephone1 = $_POST['telephone1'];
        $telephone2 = $_POST['telephone2'];
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
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

    $id = $_POST['id'];

    //checar que no este en una factura abierta
    $sqlch2 = "SELECT count(*) as count FROM bills WHERE customer = '$id' AND status ='ACTIVO'";
    $resulcheck2 = $cls->consulQuery($sqlch2);
    if ($resulcheck2['count'] > 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede eliminar este cliente porque tiene facturas relacionadas ABIERTAS.');

    }

    //checar que no este en una cotizacion abierta
    $sqlch2 = "SELECT count(*) as count FROM quotes WHERE customer = '$id' AND status ='ACTIVO'";
    $resulcheck2 = $cls->consulQuery($sqlch2);
    if ($resulcheck2['count'] > 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede eliminar este cliente porque tiene cotizaciones relacionadas ABIERTAS.');

    }

    if ($check) {


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


//listo
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-ORDER') {


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
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }

    // check if cero

    $check = true;
    $id = $cls->getId_autoincrement("orders");
    $date = $_POST['date'];
    $comment = trim($_POST['comment']);
    $purchase_order = $_POST['purchase_order'];
    $reference = $_POST['reference'];


    $sqlcheck = "SELECT COUNT(*) as count FROM purchase_orders WHERE id ='$purchase_order' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "Para crear este pedido porque la orden de compra #" . $purchase_order . " debe estar APROBADA");

    }

    if ($check) {

        $sql1 = "INSERT INTO orders (id,
                             date,
                             comment,
                             purchase_order,
                             reference,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$comment',
                                    '$purchase_order',
                                    '$reference',
                                    '$datetime',
                                    '$VAR_SESSION->username')";
        $res = $cls->exeQuery($sql1);
        if ($res) {
            $data_table = json_decode($_POST['data_table']);
            $check = true; // check data table
            $units = 0;
            $units_request = 0;
            foreach ($data_table as $data) {
                $units += $data->unit;
                $units_request += $data->units_request;
                if (($data->unit == 0 && $data->units_request == 0) || ($data->unit > 0 && $data->units_request > 0)) {


                    $sql2 = "INSERT INTO orders_details (order_id,product_id,units_buy,units_request,units_diff)values
                                                                                       ('$id',
                                                                                        '$data->product_id',
                                                                                        '$data->unit',
                                                                                        '$data->units_request',
                                                                                        '$data->units_diff')";
                    $res2 = $cls->exeQuery($sql2);
                    if (!$res2) {
                        $check = false;
                    }


                } else {

                    $check = false;

                }
            }

            if ($units > 0 && $units_request > 0) {
                if ($check) {

                    $cls->commitSet();
                    $mensaje = array('success' => true, 'mens' => 'Pedido registrado con exito.', 'url' => './?view=orders-edit&id=' . $id, "post_name" => "Pedido", "id" => $id);

                } else {

                    $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

                }
            } else {

                $mensaje = array('success' => false, 'mens' => 'No se puede crear mas pedidos con la orden de compra #' . $purchase_order . " porque ya se fue completada");

            }

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'CONVERT-TO-ORDER') {
    $cls->autocommitF();
    $check = true;

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001 ');

    }

    $purchase_order = $_POST['id'];

    $sqlcheck = "SELECT COUNT(*) as count FROM purchase_orders WHERE id ='$purchase_order' AND status='APROBADA'";
    $response = $cls->consulQuery($sqlcheck);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede convertir a pedido, la orden de compra tiene que estar APROBADA');

    }

    //checar que no tenga pedidos realizados
    $sqlcheck2 = "SELECT COUNT(*) as count FROM orders WHERE purchase_order='$purchase_order' limit 1";
    $response2 = $cls->consulQuery($sqlcheck2);
    if ($response2['count'] > 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede convertir a pedido, ya tiene pedidos relacionados');

    }

    $sql1 = "SELECT date,provider,comment,reference,status,purchase_request FROM purchase_orders WHERE id ='$purchase_order'";
    $response1 = $cls->consulQuery($sql1);
    $purchase_request = $response1['purchase_request'];

    $sqlcheck3 = "SELECT COUNT(*) as count FROM purchase_requests WHERE id ='$purchase_request' AND status='APROBADA'";
    $response3 = $cls->consulQuery($sqlcheck3);
    if ($response3['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede convertir a pedido, la requisición de compra # ' . $purchase_request . ' tiene que estar APROBADA');

    }

    if ($check) {


        $id = $cls->getId_autoincrement("orders");
        $date = $response1['date'];
        $comment = trim($response1['comment']);
        $reference = trim($response1['reference']);

        $sql2 = "INSERT INTO orders (id,
                             date,
                             purchase_order,
                             comment,
                             reference,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$purchase_order',
                                    '$comment',
                                    '$reference',
                                    '$datetime',
                                    '$VAR_SESSION->username')";

        $res2 = $cls->exeQuery($sql2);

        if ($res2) {

            $data_table = $cls->getPurchaseOrderToOrders($purchase_order);
            $check = true; // check data table

            foreach ($data_table as $data) {

                $product_id = $data['id'];
                $units = $data['unit'];
                $sql5 = "INSERT INTO orders_details (order_id,product_id,units_buy,units_request,units_diff)values('$id','$product_id',$units,$units,0)";
                $sql5 = $cls->exeQuery($sql5);
                if (!$sql5) {
                    $check = false;
                }
            }

            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Pedido registrado con exito.',
                    'url' => './?view=orders-edit&id=' . $id,
                    "post_name" => "Pedido",
                    "id" => $id, "title" => "Redireccionando a Pedido");

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos. #001 ');

            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res2);

        }

    }

}


if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-ORDER') {

    $mensaje = $_POST;
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
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $order_id = $_POST['id'];
    $mensaje = $_POST;
    $purchase_order = $_POST['purchase_order'];

    $sqlstatus = "SELECT COUNT(*) AS count FROM orders WHERE id='$order_id' AND status ='ACTIVO'";
    $response = $cls->consulQuery($sqlstatus);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Para editar este pedido, debe estar activa');
    }

    $sqlcheck = "SELECT COUNT(*) as count FROM purchase_orders WHERE id ='$purchase_order' and status ='APROBADA'";
    $rescheck = $cls->consulQuery($sqlcheck);
    if ($rescheck['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede editar este pedido porque la orden de compra #" . $purchase_order . " debe estar APROBADA");

    }

    //checar unidades que traigo con las que ya entan en otros despachos
    if (!$cls->checkUnitsPurchaseOrderToOrder(json_decode($_POST['data_table']), $purchase_order, $order_id)) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede editar este pedido, favor validar las unidades solicitadas puede quedar en negativo.");

    }

    if ($check) {

        $date = $_POST['date'];
        $comment = trim($_POST['comment']);
        $reference = $_POST['reference'];

        $sql1 = "UPDATE orders 
                             SET date ='$date',
                             comment ='$comment',
                             purchase_order ='$purchase_order',
                             reference ='$reference',
                             updated_at ='$datetime',
                             updated_by ='$VAR_SESSION->username'";

        $res = $cls->exeQuery($sql1);
        if ($res) {


            $sql21 = "DELETE FROM orders_details WHERE order_id='$order_id'";
            $res21 = $cls->exeQuery($sql21);
            if ($res21) {

                $data_table = json_decode($_POST['data_table']);
                $check = true; // check data table
                foreach ($data_table as $data) {

                    // if ($data->unit > 0) {
                    if (($data->unit == 0 && $data->units_request == 0) || ($data->unit > 0 && $data->units_request > 0)) {

                        $sql2 = "INSERT INTO orders_details (order_id,product_id,units_buy,units_request,units_diff)values
                                                                                       ('$order_id',
                                                                                        '$data->product_id',
                                                                                        '$data->unit',
                                                                                        '$data->units_request',
                                                                                        '$data->units_diff')";
                        $res2 = $cls->exeQuery($sql2);
                        if (!$res2) {
                            $check = false;
                        }


                    } else {
                        $check = false;

                    }
                }

            } else {

                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res21);
            }


            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Pedido actualizado con exito.');

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }


    }

}
if (isset($_POST['a']) && $_POST['a'] == 'APROVE-ORDER') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $check = true;
        $sqlstatus = "SELECT COUNT(*) AS count FROM orders WHERE id='$id' AND status ='ACTIVO'";
        $response = $cls->consulQuery($sqlstatus);
        if ($response['count'] == 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'Para editar este pedido, debe estar activa');
        }

        if ($check) {
            $sql = "UPDATE orders set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Pedido ha sido aprobada con exito.', 'reload' => true);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }

        }

    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'CLOSE-ORDER') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $check = true;
        $sqlstatus = "SELECT COUNT(*) AS count FROM orders WHERE id='$id' AND status ='APROBADA'";
        $response = $cls->consulQuery($sqlstatus);
        if ($response['count'] == 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'Para cerrar el pedido este debe estar APROBADO');
        }

        if ($check) {

            $sql = "UPDATE orders set status='CERRADO',updated_by='$VAR_SESSION->username',updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Pedido ha sido cerrado con exito.', 'reload' => true);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }

        } else {

            $mensaje = array('success' => false, 'mens' => 'No se puede cerrar este pedido porque tiene que estar aprovado');

        }


    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'GET-ORDERS-DETAILS') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT t1.product_id as id,t1.units_buy as unit,t1.units_request as units_request,t1.units_diff,t2.unidad_para_compra,t2.name FROM orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.order_id = '$id' ";
        $result_lis = $cls->consultListQuery($sql);//query
        $mensaje = $result_lis;

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'CONVERT-ORDER-TO-QUOTE') {
    $cls->autocommitF();
    $check = true;

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001 ');

    }

    $order_id = $_POST['id'];

    $sqlcheck = "SELECT COUNT(*) as count FROM orders WHERE id ='$order_id' AND status='APROBADA'";
    $response = $cls->consulQuery($sqlcheck);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede convertir a Cotizacion, el pedido tiene que estar APROBADA');

    }

    if ($check) {
        $sql1 = "SELECT date,comment,reference,status,purchase_order FROM orders WHERE id ='$order_id'";
        $response1 = $cls->consulQuery($sql1);

        $id = $cls->getId_autoincrement("quotes");
        $date = $response1['date'];
        $purchase_order = $response1['purchase_order'];
        $comment = trim($response1['comment']);
        $reference = trim($response1['reference']);
        $date_expired = date("Y-m-d");
        $days_expired = 0;
        $sql1 = "INSERT INTO quotes (id,
                             date,
                             comment,
                             order_id,
                             reference,
                             date_expire,
                             days_expired,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$comment',
                                    '$order_id',
                                    '$reference',
                                    '$date_expired',
                                    '$days_expired',
                                    '$datetime',
                                    '$VAR_SESSION->username')";
        $res2 = $cls->exeQuery($sql1);
        if ($res2) {

            $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_compra,t2.name FROM purchase_orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_order = '$purchase_order'  and t1.product_id in(SELECT product_id FROM orders_details WHERE order_id ='$order_id') ";

            $result_lis = $cls->consultListQuery($sql);//query
            foreach ($result_lis as $result) {

                $units_request = $cls->getUnitsProductsInOrder($order_id, $result->id);
                if ($units_request > 0) {
                    $costs = ($result->costs * $units_request);
                    $sql21 = "INSERT INTO quotes_details (quote,product_id,costs,units,total)values('$id','$result->id','$result->costs','$units_request','$costs')";
                    $res21 = $cls->exeQuery($sql21);
                    if (!$res21) {
                        $check = false;
                    }
                }


            }


            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Cotización registrada con exito.',
                    "url" => './?view=quotes-edit&id=' . $id,
                    "post_name" => "Cotización",
                    "id" => $id,
                    "title" => "Redireccionando a Cotización");


            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos. #001');

            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res2);

        }


    }


}
if (isset($_POST['a']) && $_POST['a'] == 'CONVERT-ORDER-TO-BILLS') {
    $cls->autocommitF();
    $check = true;

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001 ');

    }

    $order_id = $_POST['id'];

    $sqlcheck = "SELECT COUNT(*) as count FROM orders WHERE id ='$order_id' AND status='APROBADA'";
    $response = $cls->consulQuery($sqlcheck);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede convertir a Cotizacion, el pedido tiene que estar APROBADA');

    }

    if ($check) {
        $sql1 = "SELECT date,comment,reference,status,purchase_order FROM orders WHERE id ='$order_id'";
        $response1 = $cls->consulQuery($sql1);

        $id = $cls->getId_autoincrement("bills");
        $date = $response1['date'];
        $purchase_order = $response1['purchase_order'];
        $comment = trim($response1['comment']);
        $reference = trim($response1['reference']);
        $date_expired = date("Y-m-d");
        $days_expired = 0;
        $sql1 = "INSERT INTO bills(id,
                             date,
                             comment,
                             order_id,
                             reference,
                             created_at,
                             created_by)
                             values(
                                    '$id',
                                    '$date',
                                    '$comment',
                                    '$order_id',
                                    '$reference',
                                    '$datetime',
                                    '$VAR_SESSION->username')";
        $res2 = $cls->exeQuery($sql1);
        if ($res2) {

            $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_compra,t2.name FROM purchase_orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_order = '$purchase_order' and t1.product_id in(SELECT product_id FROM orders_details WHERE order_id ='$order_id')";
            $result_lis = $cls->consultListQuery($sql);//query
            foreach ($result_lis as $result) {

                $units_request = $cls->getUnitsProductsInOrder($order_id, $result->id);
                if ($units_request > 0) {
                    $costs = ($result->costs * (int)$units_request);
                    $sql21 = "INSERT INTO bills_details (bill,product_id,costs,units,total)values('$id','$result->id','$result->costs','$units_request','$costs')";
                    $res21 = $cls->exeQuery($sql21);
                    if (!$res21) {
                        $check = false;
                    }
                }


            }


            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Factura registrada con exito.',
                    "url" => './?view=bills-edit&id=' . $id,
                    "post_name" => "Factura",
                    "id" => $id,
                    "title" => "Redireccionando a Facturas");


            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos. #001');

            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res2);

        }


    }


}

if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-ORDER-TO-ORDER') {

    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date,comment,reference FROM purchase_orders t1 WHERE t1.id = '$id' ";
        //$result_lis = $cls->consulQuery($sql);//query
        /* $inputs = array("id" => $id,
             "data" => array(
                 array('type' => 'input', 'id' => 'date', 'value' => $result_lis['date']),
                 array('type' => 'input', 'id' => 'comment', 'value' => $result_lis['comment']),
                 array('type' => 'input', 'id' => 'reference', 'value' => $result_lis['reference'])
             ));*/

        //$mensaje = $inputs;
        $mensaje = array("id" => $id);
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-ORDER-DETAILS-TO-ORDER') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $mensaje = $cls->getPurchaseOrderToOrders($id);

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-ORDER-DETAILS-TO-ORDER-DETAILS') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $res = [];
        $sql = "SELECT t1.product_id as id,0 as units_diff,t1.units as unit,0 as units_request,t2.unidad_para_compra,t2.name FROM purchase_orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_order = '$id' ";
        $result_lis = $cls->consultListQuery($sql);//query


    }

}


/**
 * INVENTARIO
 *
 */

//listo
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
            $sqlstatus = "SELECT COUNT(*) AS count FROM products_category WHERE id='$id' AND status ='ACTIVO'";
            $response = $cls->consulQuery($sqlstatus);
            if ($response['count'] == 0) {
                $check = false;
                $mensaje = array('success' => false, 'mens' => 'Para editar categorias, esta deben estar activos');
            }


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

            $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
        }

    } else {
        $mensaje = array('success' => false, 'mens' => 'El campo nombre es obligatorio');
    }
}
if (isset($_POST['a']) && $_POST['a'] == 'DELETE-CATEGORY') {
    $cls->autocommitF();


    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $check = true;
        $sqlstatus = "SELECT COUNT(*) AS count FROM products WHERE category='$id' AND status ='ACTIVO'";
        $response = $cls->consulQuery($sqlstatus);
        if ($response['count'] > 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'No se puede eliminar categorias, que esten relacionados a productos activos');
        }
 
        if ($check) {
            $sql = "UPDATE products_category set status='DELETE',updated_by='$VAR_SESSION->username',updated_at ='$datetime'WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'url' => './?view=category-products');

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }
        }


    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

}


//listo
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


    $provider = $_POST['provider'];

    $sqlcheck = "SELECT COUNT(*) as count FROM providers WHERE id ='$provider' AND status ='ACTIVO'";
    $resulcheck = $cls->consulQuery($sqlcheck);
    if ($resulcheck['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'para crear este producto el proovedor #' . $provider . ' debe estar ACTIVO');

    }


    if ($check) {
        $id = $cls->getId_autoincrement("products");
        $category = $_POST['category'];
        $code_extern = $_POST['code_extern'];
        $description = $_POST['description'];
        $file = $_FILES['file'];
        $name = $_POST['name'];
        $price = $_POST['price'];

        $unidad_para_compra = $_POST['unidad_para_compra'];
        $unidad_para_almacen = $_POST['unidad_para_almacen'];

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

            $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #002');


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

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $provider = $_POST['provider'];

    $sqlcheck = "SELECT COUNT(*) as count FROM providers WHERE id ='$provider' AND status ='ACTIVO'";
    $resulcheck = $cls->consulQuery($sqlcheck);
    if ($resulcheck['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'para crear este producto el proovedor #' . $provider . ' debe estar ACTIVO');

    }

    if ($check) {
        $id = $_POST['id'];
        $category = $_POST['category'];
        $code_extern = $_POST['code_extern'];
        $description = $_POST['description'];
        $file = $_FILES['file'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $unidad_para_compra = $_POST['unidad_para_compra'];
        $unidad_para_almacen = $_POST['unidad_para_almacen'];
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
                $mensaje = array('success' => false, 'mens' => ($res != "") ? $res : 'Pongase en contacto con su administrador de sistema #003');

            }


        } else {

            $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #002');


        }


    }
    //
}
if (isset($_POST['a']) && $_POST['a'] == 'DELETE-PRODUCT') {
    $cls->autocommitF();


    if (isset($_POST['id'])) {
        $check = true;
        $id = $_POST['id'];

        //checar si esta en una requisición
        $sqlch1 = "SELECT count(*) as count FROM purchase_requests_details t1 JOIN purchase_requests t2 ON t1.purchase_request = t2.id 
                    WHERE t1.product_id = '$id' AND t2.status ='ACTIVO'";
        $resulcheck1 = $cls->consulQuery($sqlch1);
        if ($resulcheck1['count'] > 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'Este producto no se puede eliminar porque tiene requisiciones de compras activas');

        }

        //checar si esta en una orden
        $sqlch2 = "SELECT count(*) as count FROM purchase_orders_details t1 JOIN purchase_orders t2 ON t1.purchase_order = t2.id 
                    WHERE t1.product_id = '$id' AND t2.status ='ACTIVO'";
        $resulcheck2 = $cls->consulQuery($sqlch2);
        if ($resulcheck2['count'] > 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'Este producto no se puede eliminar porque tiene ordenes de compras activas');

        }

        if ($check) {

            $sql = "UPDATE products set status='DELETE',updated_by='$VAR_SESSION->username', updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'url' => './?view=products');

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }
        }

    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'GET-PRODUCTS') {

    $whereIds = "";
    if (isset($_POST['ids'])) {
        $whereIds = "AND t1.id in('" . implode("','", $_POST['ids']) . "')";

    }
    $sql = "SELECT t1.id as id,t1.name as name,t2.name as category,t1.price as price,t1.status as status,t1.img_portada,t1.unidad_para_compra,t1.unidad_para_almacen FROM products t1 left join products_category t2 on t1.category = t2.id WHERE t1.status = 'ACTIVO' $whereIds order by t1.created_at desc";
    $result_lis = $cls->consultListQuery($sql);//query

    $mensaje = $result_lis;


}


/**
 * COMPRAS
 *
 */
//listo

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
    if (!$cls->enableAutoId()) {
        if (!isset($_POST['id'])) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'El campo id es obligatorio');
        }else{

            $id = $_POST['id'];

        }
    }else{
        $id = $cls->getId_autoincrement("providers");

    }

    if ($check) {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $telephone1 = $_POST['telephone1'];
        $telephone2 = $_POST['telephone2'];
        $fax = $_POST['fax'];
        $account = $_POST['account'];
        $address = $_POST['address'];
        $ruc = $_POST['ruc'];
        $dv = $_POST['dv'];

        $sql = "INSERT INTO providers (id,
                       name,
                       ruc,
                       dv,
                       email,
                       telephone1,
                       telephone2,
                       fax,
                       account,
                       address,
                       created_at,
                       created_by) values('$id',
                                          '$name',
                                          '$ruc',
                                          '$dv',
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
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

    $id = $_POST['id'];
    
    $sqlstatus = "SELECT COUNT(*) AS count FROM providers WHERE id='$id' AND status ='ACTIVO'";
    $response = $cls->consulQuery($sqlstatus);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Para editar proovedores, estos deben estar activos');
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
        $dv = $_POST['dv'];
        $ruc = $_POST['ruc'];

        $sql = "UPDATE providers
                       SET name='$name',
                       email ='$email',
                       telephone1 = '$telephone1',
                       telephone2 = '$telephone2',
                       fax = '$fax',
                       account = '$account',
                       address = '$address',
                       updated_at = '$datetime',
                       dv = '$dv',
                       ruc = '$ruc',
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
        //check purchaser request
        //check purchase order

        $sqlcheck1 = "SELECT COUNT(*) AS count FROM purchase_requests WHERE provider ='$id' and status ='ACTIVO'";
        $resulcheck1 = $cls->consulQuery($sqlcheck1);
        $check = true;
        if ($resulcheck1['count'] > 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'No se puede eliminar este proovedor porque esta en una requisicion activa.');
        }

        $sqlcheck1 = "SELECT COUNT(*) AS count FROM purchase_orders WHERE provider ='$id' and status ='ACTIVO'";
        $resulcheck1 = $cls->consulQuery($sqlcheck1);
        if ($resulcheck1['count'] > 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'No se puede eliminar este proovedor porque esta en una order de compra activa.');
        }

        $sqlstatus = "SELECT COUNT(*) AS count FROM providers WHERE id='$id' AND status ='ACTIVO'";
        $response = $cls->consulQuery($sqlstatus);
        if ($response['count'] == 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'Para editar proovedores, estos deben estar activos');
        }

        if ($check) {

            $sql = "UPDATE providers set status='DELETE',updated_by='$VAR_SESSION->username', updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'url' => './?view=providers');

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }
        }

    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

}


//listo
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
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }

    //checar proveedor activo
    $id = $cls->getId_autoincrement("purchase_requests");
    $date = $_POST['date'];
    $provider = $_POST['provider'];

    $comment = trim($_POST['comment']);
    $reference = $_POST['reference'];

    $sqlcheck = "SELECT COUNT(*) as count FROM providers WHERE id ='$provider' AND status='ACTIVO'";
    $resulcheck = $cls->consulQuery($sqlcheck);
    if ($resulcheck['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'para crear una requisicion el proovedor #' . $provider . ' debe estar ACTIVO');

    }
    if ($check) {

        $sql1 = "INSERT INTO purchase_requests (id,date,provider,reference,comment,created_at,created_by)values('$id','$date','$provider','$reference', '$comment','$datetime','$VAR_SESSION->username')";
        $res = $cls->exeQuery($sql1);
        if ($res) {
            $data_table = json_decode($_POST['data_table']);
            $check = true; // check data table
            foreach ($data_table as $data) {

                if ($data->costs > 0 && $data->total > 0 && $data->unit) {

                    $sql2 = "INSERT INTO purchase_requests_details (purchase_request,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                    $res2 = $cls->exeQuery($sql2);
                    if (!$res2) {
                        $check = false;
                    }

                } else {

                    $check = false;

                }
            }

            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Requisición de compra registrada con exito.', 'url' => './?view=purchase-requests-edit&id=' . $id, "post_name" => "Requisición de compra", "id" => $id);

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

            }

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

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
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

    $date = $_POST['date'];
    $id = $_POST['id'];
    $provider = $_POST['provider'];
    $comment = trim($_POST['comment']);
    $reference = $_POST['reference'];


    $sqlcheck = "SELECT COUNT(*) as count FROM purchase_requests WHERE id ='$id' AND status='ACTIVO'";
    $response = $cls->consulQuery($sqlcheck);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede editar esta requisición porque debe estar activa.');

    }

    $sqlcheck = "SELECT COUNT(*) as count FROM providers WHERE id ='$provider' AND status ='ACTIVO'";
    $resulcheck = $cls->consulQuery($sqlcheck);
    if ($resulcheck['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'para editar una requisicion el proovedor #' . $provider . ' debe estar ACTIVO');

    }
    if ($check) {
        $sql1 = "UPDATE purchase_requests set date ='$date',
                             provider = '$provider',
                             reference ='$reference',
                             comment ='$comment',
                             updated_at = '$datetime',
                             updated_by = '$VAR_SESSION->username' WHERE id='$id'";
        $res = $cls->exeQuery($sql1);
        if ($res) {
            $sql2 = "DELETE FROM purchase_requests_details WHERE purchase_request='$id'";
            $res2 = $cls->exeQuery($sql2);
            if ($res2) {

                $data_table = json_decode($_POST['data_table']);
                $check = true; // check data table
                foreach ($data_table as $data) {

                    if ($data->costs > 0 && $data->total > 0 && $data->unit) {

                        $sql3 = "INSERT INTO purchase_requests_details (purchase_request,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                        $res3 = $cls->exeQuery($sql3);
                        if (!$res3) {
                            $check = false;
                        }

                    } else {

                        $check = false;

                    }
                }

                if ($check) {

                    $cls->commitSet();
                    $mensaje = array('success' => true, 'mens' => 'Requisición de compra actualizada con exito.');

                } else {

                    $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

                }

            } else {

                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);


            }


        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    }


}
if (isset($_POST['a']) && $_POST['a'] == 'APROVE-PURCHASE-REQUEST') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        $check = true;
        $sqlcheck = "SELECT COUNT(*) as count FROM purchase_requests WHERE id ='$id' AND status='ACTIVO'";
        $response = $cls->consulQuery($sqlcheck);
        if ($response['count'] == 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'No se puede APROBAR esta requisición porque debe estar activa.');

        }


        if ($check) {
            $sql = "UPDATE purchase_requests set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido aprobada con exito.', 'reload' => true);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }
        }


    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'CANCEL-PURCHASE-REQUEST') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        //CHECK CANCEL
        $check = true;

        $sqlcheck = "SELECT COUNT(*) AS count FROM purchase_requests WHERE id='$id' and status ='ACTIVO' ";
        $resulcheck = $cls->consulQuery($sqlcheck);
        if ($resulcheck['count'] == 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'Para cancelar la requisición, esta debe estar activa');
        }

        if ($check) {
            $comment_canceled = "";
            if (isset($_POST['comment_canceled'])) {
                $comment_canceled = $_POST['comment_canceled'];
            }
            $sql = "UPDATE purchase_requests set status='CANCELADA',comment_canceled='$comment_canceled',canceled_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', canceled_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido aprobada con exito.', 'reload' => true);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }
        }

    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'CLOSE-REQUEST') {
    $cls->autocommitF();


    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        //checar que no tenga ordenes abiertas

        $sql1 = "SELECT COUNT(*) AS cont from purchase_orders WHERE purchase_request='$id' and status = 'ACTIVO'";
        $resl = $cls->consulQuery($sql1);
        $check = true;
        $entro = "";
        if ((int)$resl['cont'] > 0) {
            if ($cls->enableClose()) {
                $check = false;
                $mensaje = array('success' => false,
                    'mens' => 'No se puede cerrar esta requisición porque tiene ordenes de compra relacionadas (ACTIVAS),<br>Debe cerrar/aprobar para poder cerrar esta requisición');

            }
        }


        $check = true;
        $sqlcheck = "SELECT COUNT(*) as count FROM purchase_requests WHERE id ='$id' AND status='APROBADA'";
        $response = $cls->consulQuery($sqlcheck);
        if ($response['count'] == 0) {
            if ($cls->enableClose()) {
                $check = false;
                $mensaje = array('success' => false, 'mens' => 'No se puede CERRAR esta requisición porque debe estar APROBADA.');
            }

        }


        if ($check) {

            $sql = "UPDATE purchase_requests set status='CERRADO',updated_by='$VAR_SESSION->username', updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Requisición de compra ha sido cerrada con exito.', 'reload' => true);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }

        }

    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
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
if (isset($_POST['a']) && $_POST['a'] == 'CONVERT-TO-PURCHASE-ORDER') {
    $cls->autocommitF();
    $check = true;

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001 ');

    }

    $request_id = $_POST['id'];

    $sqlcheck = "SELECT COUNT(*) as count FROM purchase_requests WHERE id ='$request_id' AND status='APROBADA'";
    $response = $cls->consulQuery($sqlcheck);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede convertir a O/C, la requisición tiene que estar APROBADA');

    }


    if ($check) {
        $sql1 = "SELECT date,provider,comment,reference,status FROM purchase_requests WHERE id ='$request_id'";
        $response1 = $cls->consulQuery($sql1);

        $id = $cls->getId_autoincrement("purchase_orders");
        $date = $response1['date'];
        $provider = $response1['provider'];
        $comment = trim($response1['comment']);
        $reference = trim($response1['reference']);
        $sql2 = "INSERT INTO purchase_orders (id,
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
        if ($res2) {

            $sql3 = "SELECT product_id,costs,units as unit,total FROM purchase_requests_details WHERE purchase_request ='$request_id'";
            $response3 = $cls->consultListQuery($sql3);

            $data_table = $response3;
            $check = true; // check data table
            foreach ($data_table as $data) {

                if ($data->costs > 0 && $data->total > 0 && $data->unit) {


                    $sql5 = "INSERT INTO purchase_orders_details (purchase_order,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                    $sql5 = $cls->exeQuery($sql5);
                    if (!$sql5) {
                        $check = false;
                    }


                } else {

                    $check = false;

                }
            }

            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Orden de compra registrada con exito.',
                    'url' => './?view=purchase-order-edit&id=' . $id,
                    "post_name" => "Orden de compra",
                    "id" => $id, "title" => "Redireccionando a O/C");

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos. #001');

            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res2);

        }

    }


}


//listo
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
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    $id = $cls->getId_autoincrement("purchase_orders");

    $date = $_POST['date'];
    $provider = $_POST['provider'];
    $comment = trim($_POST['comment']);
    $purchase_request = trim($_POST['purchase_request']);

    $sqlch = "SELECT COUNT(*) AS count FROM purchase_requests where id ='$purchase_request' and status ='APROBADA'";
    $resch = $cls->consulQuery($sqlch);

    if ($resch['count'] == 0) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => "No se puede crear esta orden de compra porque la requisicion debe estar APROBADA");

    }

    $sqlcheck = "SELECT COUNT(*) as count FROM providers WHERE id ='$provider' AND status='ACTIVO'";
    $resulcheck = $cls->consulQuery($sqlcheck);
    if ($resulcheck['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'para crear una orden de compra el proovedor #' . $provider . ' debe estar ACTIVO');

    }

    if ($check) {

        $reference = $_POST['reference'];
        $sql1 = "INSERT INTO purchase_orders (id,
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
        if ($res) {
            $data_table = json_decode($_POST['data_table']);
            $check = true; // check data table
            foreach ($data_table as $data) {

                if ($data->costs > 0 && $data->total > 0 && $data->unit) {

                    //solo en las compras se debe actualiza el precio
                    $sql3 = "UPDATE products set price='$data->costs' WHERE id ='$data->product_id'";
                    $res3 = $cls->exeQuery($sql3);
                    if ($res3) {

                        $sql2 = "INSERT INTO purchase_orders_details (purchase_order,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                        $res2 = $cls->exeQuery($sql2);
                        if (!$res2) {
                            $check = false;
                        }

                    } else {

                        $check = false;

                    }


                } else {

                    $check = false;

                }
            }

            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Orden de compra registrada con exito.', 'url' => './?view=purchase-order-edit&id=' . $id, "post_name" => "Orden de compra", "id" => $id);

            } else {

                $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }


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
    if (!isset($_POST['data_table'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar todos los campos de la tabla de productos');
    }
    if (!isset($_POST['purchase_request'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo requisición es obligatorio');
    }
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }


    $date = $_POST['date'];
    $id = $_POST['id'];
    $provider = $_POST['provider'];
    $comment = trim($_POST['comment']);
    $purchase_request = trim($_POST['purchase_request']);
    $reference = $_POST['reference'];

    $sqlstatus = "SELECT COUNT(*) AS count FROM purchase_orders WHERE id='$id' AND status ='ACTIVO'";
    $response = $cls->consulQuery($sqlstatus);
    if ($response['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Para editar la requisicióm, esta debe estar activa');
    }

    $sqlcheck = "SELECT COUNT(*) as count FROM providers WHERE id ='$provider' AND status='ACTIVO'";
    $resulcheck = $cls->consulQuery($sqlcheck);
    if ($resulcheck['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'para editar una orden de compra el proovedor #' . $provider . ' debe estar ACTIVO');

    }
    if ($check) {

        $sql1 = "UPDATE purchase_orders set date ='$date',
                             provider = '$provider',
                             reference ='$reference',
                             comment ='$comment',
                             purchase_request='$purchase_request',
                             updated_at = '$datetime',
                             updated_by = '$VAR_SESSION->username' WHERE id='$id'";

        $res = $cls->exeQuery($sql1);
        if ($res) {
            $sql2 = "DELETE FROM purchase_orders_details WHERE purchase_order='$id'";
            $res2 = $cls->exeQuery($sql2);
            if ($res2) {

                $data_table = json_decode($_POST['data_table']);
                $check = true; // check data table
                foreach ($data_table as $data) {

                    if ($data->costs > 0 && $data->total > 0 && $data->unit) {

                        //solo en las compras se debe actualiza el precio
                        $sql4 = "UPDATE products set price='$data->costs' WHERE id ='$data->product_id'";
                        $res4 = $cls->exeQuery($sql4);
                        if ($res4) {
                            $sql3 = "INSERT INTO purchase_orders_details (purchase_order,product_id,costs,units,total)values('$id','$data->product_id','$data->costs','$data->unit','$data->total')";
                            $res3 = $cls->exeQuery($sql3);
                            if (!$res3) {
                                $check = false;
                            }
                        } else {

                            $check = false;
                        }


                    } else {

                        $check = false;

                    }
                }

                if ($check) {

                    $cls->commitSet();
                    $mensaje = array('success' => true, 'mens' => 'Orden de compra actualizada con exito.');

                } else {

                    $mensaje = array('success' => false, 'mens' => 'Hubo un error al insertar los detalles de la tabla de productos.');

                }

            } else {

                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);


            }


        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }

    }


}
if (isset($_POST['a']) && $_POST['a'] == 'CLOSE-PURCHASE-ORDER') {
    $cls->autocommitF();
    $check = true;

    if (!isset($_POST['id'])) {

        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');

    }
    $id = $_POST['id'];

    $checkUnitsTotal = true;
    if ($cls->enableControlClosePurchaseOrder()) {
        //NO DEJA CERRAR LA OC HASTA QUE SE COMPLETE LAS UNIDADES EN PEDIDOS
        //if ($cls->checkIfTotalPurchase($id)) {

        // $checkUnitsTotal = false;
        // }

    }

    if (!$checkUnitsTotal) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Debe completar el pedido de todas las unidades compradas');

    }
    //checar que no tenga pedidos realizados
    /*$sqlcheck2 = "SELECT COUNT(*) as count FROM orders WHERE purchase_order='$id' limit 1";
    $response2 = $cls->consulQuery($sqlcheck2);
    if ($response2['count'] > 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'No se puede convertir a pedido, ya tiene pedidos relacionados');

    }*/
    if ($checkUnitsTotal && $check) {

        //por aqui va la vaina

        $sql1 = "SELECT COUNT(*) AS cont from orders WHERE purchase_order='$id' and status = 'ACTIVO'";
        $resl = $cls->consulQuery($sql1);
        $check = true;
        $entro = "";
        if ($resl['cont'] > 0) {
            if ($cls->enableClose()) {
                $check = false;
                $mensaje = array('success' => false,
                    'mens' => 'No se puede cerrar esta orden de compra porque tiene pedidos relacionados (ACTIVOS),<br>Debe cerrar/aprobar para poder cerrar esta orden de compra');
            }
        }

        $sql2 = "SELECT COUNT(*) AS count from purchase_orders WHERE id='$id' and status = 'APROBADA'";
        $res2 = $cls->consulQuery($sql2);
        if ($res2['count'] == 0) {
            $check = false;
            $mensaje = array('success' => false,
                'mens' => 'La orden de compra debe estar APROBADA para poder cerrarla.');


        }

        if ($check) {
            $sql = "UPDATE purchase_orders set status='CERRADO',updated_by='$VAR_SESSION->username', updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido cerrada con exito.', 'reload' => true);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }

        }


    }

}

if (isset($_POST['a']) && $_POST['a'] == 'APROVE-PURCHASE-ORDER') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');

    }

    $id = $_POST['id'];

    $sql2 = "SELECT COUNT(*) AS count from purchase_orders WHERE id='$id' and status = 'ACTIVO'";
    $res2 = $cls->consulQuery($sql2);
    if ($res2['count'] == 0) {
        $check = false;
        $mensaje = array('success' => false,
            'mens' => 'La orden de compra debe estar ACTIVA para poder aprobarla.');

    }

    if ($check) {
        $sql = "UPDATE purchase_orders set status='APROBADA',approved_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', approved_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
        $res = $cls->exeQuery($sql);
        if ($res) {
            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido aprobada con exito.', 'reload' => true);

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res);

        }
    }


}

if (isset($_POST['a']) && $_POST['a'] == 'CANCEL-PURCHASE-ORDER') {
    $cls->autocommitF();

    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        $comment_canceled = "";
        if (isset($_POST['comment_canceled'])) {
            $comment_canceled = $_POST['comment_canceled'];
        }

        //CHECK CANCEL
        $check = true;

        $sqlcheck = "SELECT COUNT(*) AS count FROM purchase_orders WHERE id='$id' and status ='ACTIVO' ";
        $resulcheck = $cls->consulQuery($sqlcheck);
        if ($resulcheck['count'] == 0) {
            $check = false;
            $mensaje = array('success' => false, 'mens' => 'Para cancelar la orden de compra, esta debe estar activa');
        }

        if ($check) {

            $sql = "UPDATE purchase_orders set status='CANCELADA',comment_canceled='$comment_canceled',canceled_by='$VAR_SESSION->username',updated_by='$VAR_SESSION->username', canceled_at ='$datetime',updated_at ='$datetime' WHERE id ='$id' ";
            $res = $cls->exeQuery($sql);
            if ($res) {
                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Orden de compra ha sido cancelada con exito.', 'reload' => true);

            } else {
                $cls->exeQuery('ROLLBACK');
                $mensaje = array('success' => false, 'mens' => $res);

            }
        }


    } else {

        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
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
if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-REQUEST-TO-ORDER') {

    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date ,provider,comment,reference FROM purchase_requests t1 WHERE t1.id = '$id' ";
        //$result_lis = $cls->consulQuery($sql);//query
        /*$inputs = array("id" => $id,
            "data" => array(array('type' => 'input', 'id' => 'date', 'value' => $result_lis['date']),
                array('type' => 'select', 'id' => 'provider', 'value' => $result_lis['provider']),
                array('type' => 'input', 'id' => 'comment', 'value' => $result_lis['comment']),
                array('type' => 'input', 'id' => 'reference', 'value' => $result_lis['reference'])
            ));*/

        //$mensaje = $inputs;
        $mensaje = array("id" => $id);
    }

}


if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-ORDER-TO-QUOTE') {

    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date,comment,reference FROM orders  WHERE id = '$id' ";
        // $result_lis = $cls->consulQuery($sql);//query
        /*$inputs = array("id" => $id,
             "data" => array(array('type' => 'input', 'id' => 'date', 'value' => $result_lis['date']),
                 array('type' => 'input', 'id' => 'reference', 'value' => $result_lis['reference']),
                 array('type' => 'input', 'id' => 'comment', 'value' => $result_lis['comment'])
             ));*/

        // $mensaje = $inputs;
        $mensaje = array("id" => $id);
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'GET-PURCHASE-ORDER-TO-BILLS') {

    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date,comment,reference FROM orders  WHERE id = '$id' ";
        /*$result_lis = $cls->consulQuery($sql);//query
        $inputs = array("id" => $id,
            "data" => array(array('type' => 'input', 'id' => 'date', 'value' => $result_lis['date']),
                array('type' => 'input', 'id' => 'reference', 'value' => $result_lis['reference']),
                array('type' => 'input', 'id' => 'comment', 'value' => $result_lis['comment'])
            ));

        $mensaje = $inputs;*/
        $mensaje = array("id" => $id);
    }

}
if (isset($_POST['a']) && $_POST['a'] == 'GET-ORDER-DETAILS-TO-QUOTE') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql1 = "SELECT purchase_order FROM orders WHERE id ='$id' limit 1";
        $res1 = $cls->consulQuery($sql1);
        $purchase_order = $res1['purchase_order'];

        $res = [];
        $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_compra,t2.name FROM purchase_orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_order = '$purchase_order' and t1.product_id in(SELECT product_id FROM orders_details WHERE order_id ='$id')";
        $result_lis = $cls->consultListQuery($sql);//query
        foreach ($result_lis as $result) {

            $units_request = $cls->getUnitsProductsInOrder($id, $result->id);
            // $mensaje = array("id"=>$result->id);
            if ($units_request > 0) {

                $res[] = array("id" => $result->id,
                    "costs" => $result->costs,
                    "unit" => $units_request,
                    "total" => ($result->costs * $units_request),
                    "unidad_para_compra" => $result->unidad_para_compra,
                    "name" => $result->name);

            }


        }


        $mensaje = $res;

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'GET-ORDER-DETAILS-TO-BILLS') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql1 = "SELECT purchase_order FROM orders WHERE id ='$id' limit 1";
        $res1 = $cls->consulQuery($sql1);
        $purchase_order = $res1['purchase_order'];

        $res = [];
        $sql = "SELECT t1.product_id as id,t1.costs,t1.units as unit,t1.total,t2.unidad_para_compra,t2.name FROM purchase_orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_order = '$purchase_order' and t1.product_id in(SELECT product_id FROM orders_details WHERE order_id ='$id') ";
        $result_lis = $cls->consultListQuery($sql);//query
        foreach ($result_lis as $result) {

            $units_request = $cls->getUnitsProductsInOrder($id, $result->id);
            // $mensaje = array("id"=>$result->id);
            if ($units_request > 0) {

                $res[] = array("id" => $result->id,
                    "costs" => $result->costs,
                    "unit" => $units_request,
                    "total" => ($result->costs * $units_request),
                    "unidad_para_compra" => $result->unidad_para_compra,
                    "name" => $result->name);

            }


        }


        $mensaje = $res;

    }
}
if (isset($_POST['a']) && $_POST['a'] == 'GET-ORDER-RELATED-PURCHASE-ORDER') {
    $mensaje = [];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT  id as id,purchase_order,date,sum(units_buy) as units_buy,sum(units_request) as units_request,sum(units_diff) as units_diff, status FROM (
                                                    (SELECT  t1.id,t3.units_buy,t3.units_request,t3.units_diff,DATE_FORMAT(t1.date,'%Y-%m-%d') as date,t1.status,t1.purchase_order
                                                    FROM orders t1 
                                                    join orders_details t3 on t1.id = t3.order_id 
                                                    WHERE t1.status <>'DELETE' and t1.purchase_order='$id'
																										) as datas
                                                    
                                            ) group by id ORDER BY date desc ";
        $result_lis = $cls->consultListQuery($sql);//query
        $data = [];
        foreach ($result_lis as $result) {

            $data[] = array("Id" => $result->id, "Fecha" => $result->date, "Compradas" => $result->units_buy,
                "Solicitadas" => $result->units_request, "Diferencia" => $result->units_diff, "Status" => $result->status);
        }
        $mensaje = $data;

    }

}


if (isset($_POST['a']) && $_POST['a'] == 'LOGIN') {
    $key = $cls->getAuthKey();

    if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT COUNT(*) FROM users_access WHERE username='$username'";
        $result = $cls->consulQuery($sql);
        if ($result[0] > 0) {

            $sql22 = "SELECT count(*) FROM users_access WHERE username='$username' and password = AES_ENCRYPT('$password', '$key') and status='ACTIVO' limit 1";
            $sql22_res = $cls->consulQuery($sql22);

            if ($sql22_res[0] > 0) {

                $VAR_SESSION = Session::getInstance();
                $CPASS = TRUE;
                $cls->autocommitF();

                // Let's store datas in the session
                $VAR_SESSION->username = $username;
                $VAR_SESSION->loggedin = true;
                $sqlper = "SELECT permission FROM users_permission WHERE username ='$username'";
                $resper = $cls->consultListQuery($sqlper);
                $permission = array();
                if (count($resper) > 0) {
                    foreach ($resper as $per) {
                        $permission[] = $per->permission;

                    }
                }

                $VAR_SESSION->permission = $permission;

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
            $mens = "Usuario no encontrado en nuestros registros.";
            $url = "";

        }


    } else {
        $type = "error";
        $mens = "Dijiste un usuario y una contraseña valida";
        $url = "";

    }
    $mensaje = array('type' => $type, 'mens' => $mens, 'url' => $url);

}
if (isset($_POST['a']) && $_POST['a'] == 'CREATE-USERS') {
    $cls->autocommitF();
    $check = true;
    if (!isset($_POST['name'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo de nombre es obligatorio.');
    }
    if (!isset($_POST['username'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo de username es obligatorio.');
    }

    if (!isset($_POST['new_password'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo de contraseña es obligatorio.');
    }
    if (!isset($_POST['password_confirm'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo de repetir contraseña es obligatorio.');
    }
    if ($_POST['new_password'] != $_POST['password_confirm']) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Las contraseñas no coinciden.');
    }
    if (ctype_space($_POST['username'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El username no debe tener espacios en blanco.');
    }
    $username = str_replace(" ", "", $_POST['username']);
    $sqlcheku = "SELECT COUNT(*) as count FROM users_access WHERE username ='$username' LIMIT 1";
    $resqlcheku = $cls->consulQuery($sqlcheku);
    if ($resqlcheku['count'] > 0) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El username ' . $username . ' ya se encuentra en nuestros registros.');

    }


    $key = $cls->getAuthKey();
    if ($check) {
        $password_confirm = $_POST['password_confirm'];
        $sql = "SELECT AES_ENCRYPT('$password_confirm', '$key') as newpassword;";
        $res = $cls->consulQuery($sql);
        $new_password = $res['newpassword'];
        $name = $_POST['name'];


        $sql2 = "INSERT INTO users_access (username,name,password,created_at,created_by)value('$username','$name','$new_password','$datetime','$VAR_SESSION->username')";
        $res2 = $cls->exeQuery($sql2);
        if ($res2) {

            $cls->commitSet();

            $mensaje = array('success' => true, 'mens' => 'Usuario registrado con exito.', 'url' => './?view=users-edit&id=' . $username, "post_name" => "Usuarios", "id" => $username);


        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res2);

        }

    }

}
if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-USERS') {
    $cls->autocommitF();

    $check = true;
    if (!isset($_POST['name'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo de nombre es obligatorio.');
    }
    if (!isset($_POST['status'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'El campo de status es obligatorio.');
    }

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }

    $changePass = false;
    if (!isset($_POST['new_password'])) {
        if (!isset($_POST['password_confirm'])) {
            if ($_POST['new_password'] == $_POST['password_confirm']) {

                $changePass = true;

            } else {
                $check = false;
                $mensaje = array('success' => false, 'mens' => 'Las contraseñas no coinciden.');
            }
        }
    }

    if ($check) {
        $name = $_POST['name'];
        $status = $_POST['status'];
        $id = $_POST['id'];

        $extrafield = "";
        if ($changePass) {

            $password_confirm = $_POST['password_confirm'];
            $sql = "SELECT AES_ENCRYPT('$password_confirm', '$key') as newpassword;";
            $res = $cls->consulQuery($sql);
            $new_password = $res['newpassword'];
            $extrafield = ",password ='$new_password'";

        }


        $sql2 = "UPDATE users_access SET status='$status',name='$name',updated_at='$datetime',updated_by='$VAR_SESSION->username' $extrafield WHERE username ='$id' limit 1";
        $res2 = $cls->exeQuery($sql2);
        if ($res2) {

            $cls->commitSet();
            $mensaje = array('success' => true, 'mens' => 'Contraseña actualizada con exito.');

        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res2);

        }

    }


}

if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-USERS-PERMISSION') {
    $cls->autocommitF();

    $check = true;

    if (!isset($_POST['permission'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Ha ocurrido un error al guardar permisos #005');
    }

    if (!isset($_POST['id'])) {
        $check = false;
        $mensaje = array('success' => false, 'mens' => 'Pongase en contacto con su administrador de sistema #001');
    }
    $permissions = $_POST['permission'];

    if ($check) {

        //delete
        $id = $_POST['id'];
        $sql1 = "DELETE FROM users_permission WHERE username = '$id'";
        $res1 = $cls->exeQuery($sql1);
        if ($res1) {
            for ($i = 0; $i < count($permissions); $i++) {
                //insert
                $permission = $permissions[$i];
                $sql2 = "INSERT INTO users_permission(username,permission,created_at)VALUES('$id','$permission','$datetime')";
                $res2 = $cls->exeQuery($sql2);
                if (!$res2) {
                    $check = false;
                    $mensaje = array('success' => false, 'mens' => $res2);

                }
            }

            if ($check) {

                $cls->commitSet();
                $mensaje = array('success' => true, 'mens' => 'Permisos actualizados con exito.');

            } else {
                $cls->exeQuery('ROLLBACK');


            }
        } else {
            $cls->exeQuery('ROLLBACK');
            $mensaje = array('success' => false, 'mens' => $res1);

        }


    }


}


echo json_encode($mensaje);
exit;
