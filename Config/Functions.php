<?php
date_default_timezone_set('America/Panama');
require("Config.php");
require('Session.php');
require('Others/fpdf/fpdf.php');

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

class Functions extends dba
{
    private $maximumFileTam = 2097152;
    private $enableClose = true;
    private $enableCancel = true;
    private $enableControlClosePurchaseOrder = true;


    public function __construct()
    {

    }

    public function enableClose()
    {

        return $this->enableClose;
    }

    public function enableControlClosePurchaseOrder()
    {

        return $this->enableControlClosePurchaseOrder;
    }

    public function enableCancel()
    {

        return $this->enableCancel;
    }

    public function getLogov1()
    {

        return "Views/assets/img/logo-up.png";
    }


    public function autocommitF()
    {

        $db = dba::getInstance();
        $mysqli = $db->autocommitF();

    }

    public function commitSet()
    {
        $db = dba::getInstance();
        $mysqli = $db->commitF();

    }

    public function getAuthKey()
    {

        return "23097d223405d8228642a";
    }

    public function getVarData($key)
    {

        $datos = array(
            "limitPage" => 15,
            "product-image" => "Others/Files_products",
            "default-image" => "Views/assets/img/image_placeholder.jpg"
        );
        return $datos[$key];
    }

    public function uploadFile(array $file, $path)
    {
        $check = true;
        $file_name_final = "";
        if (!empty($file) && !$file['error']) {

            $file_name = uniqid();
            $original_name = $file['name'];

            $file_size = $file['size'];
            $file_tmp = $file['tmp_name'];
            $file_type = $file['type'];

            if ($file_size > $this->maximumFileTam) {
                $check = false;
            }
            if ($check) {
                if (!file_exists($path)) {
                    mkdir($path, 0777);
                }
                $file_name_final = $file_name . '.' . pathinfo($original_name, PATHINFO_EXTENSION);
                move_uploaded_file($file_tmp, $path . '/' . $file_name_final);
            }

        }

        $mensaje = array("success" => $check, "filename" => $file_name_final);
        return json_decode(json_encode($mensaje), true);
    }

    public function getStatusClass($status)
    {

        $class = "";
        switch ($status) {

            case "ACTIVO":
                $class = "badge-success";
                break;
            case "CERRADO":
                $class = "badge-danger";
                break;
            case "APROBADA":
                $class = "badge-info";
                break;
            case "CANCELADA":
                $class = "badge-secondary";
                break;
            case "INACTIVO":
                $class = "badge-secondary";
                break;

        }
        return $class;


    }

    public function exeQuery($sql)
    {

        $db = dba::getInstance();
        $mysqli = $db->connet();


        $result = $mysqli->query($sql);
        if ($result) {

            $result = 1;
        } else {

            $result = "Query Failed! SQL: $sql - Error:  " . mysqli_error($db->connet());
        }

        return $result;
    }

    public function checkIfTotalPurchase($id)
    {
        $currentUnits = 0;

        $sql = "SELECT SUM(t1.units) as units FROM purchase_orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_order = '$id'";
        $res1 = $this->consulQuery($sql);
        $units = $res1['units'];


        $sql2 = "select sum(t2.units_request) as request from orders t1 join orders_details t2 on t1.id = t2.order_id WHERE t1.purchase_order = '$id'";
        $res2 = $this->consulQuery($sql2);
        $request = $res2['request'];

        return (($units + $request) > 0);
    }

    public function chetIfReceiveMerchantIsComplete($id)
    {

        $sql1 = "SELECT sum(t2.units) as units FROM received_merchant t1 join received_merchant_details t2 on t1.id= t2.received WHERE t1.bills='$id'";
        $res1 = $this->consulQuery($sql1);
        $untis1 = $res1['units'];


        $sql2 = "SELECT sum(t2.units) as units FROM  bills_details t2  WHERE t2.bill='$id'";
        $res2 = $this->consulQuery($sql2);
        $untis2 = $res2['units'];
        return ($untis1 == $untis2);
    }

    public function chetIfBillsIsComplete($id)
    {

        $sql1 = "SELECT sum(t2.units) as units FROM received_merchant t1 join received_merchant_details t2 on t1.id= t2.received WHERE t1.id='$id'";
        $res1 = $this->consulQuery($sql1);
        $untis1 = $res1['units'];

        $sql2 = "SELECT sum(t2.units_request) as units FROM dispatch_merchant t1 join dispatch_merchant_details t2 on t1.id= t2.dispatch WHERE t1.received='$id'";
        $res2 = $this->consulQuery($sql2);
        $untis2 = $res2['units'];

        return ($untis1 == $untis2);
    }

    public function getPurchaseOrderToOrders($id)
    {
        $res = [];
        $sql = "SELECT t1.product_id as id,0 as units_diff,t1.units as unit,0 as units_request,t2.unidad_para_compra,t2.name FROM purchase_orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_order = '$id' ";
        $result_lis = $this->consultListQuery($sql);//query
        foreach ($result_lis as $result) {
            $sql2 = "select sum(t2.units_request) as units from orders t1 join orders_details t2 on t1.id = t2.order_id WHERE t1.purchase_order = '$id' and t2.product_id='$result->id' LIMIT 1";
            $response = $this->consulQuery($sql2);
            $currentUnits = $response['units'];
            $res[] = array("id" => $result->id,
                "units_diff" => 0,
                "units_request" => 0,
                "unit" => ($result->unit - $currentUnits),
                "unidad_para_compra" => $result->unidad_para_compra,
                "name" => $result->name);

        }
        return $res;

    }

    public function getReceivedMerchantToDispatch($id)
    {
        $res = [];
        $sql = "SELECT t1.product_id as id,0 as units_diff,t1.units as unit,0 as units_request,t2.unidad_para_almacen,t2.name FROM received_merchant_details t1 join products t2 on t1.product_id = t2.id WHERE t1.received = '$id' ";
        $result_lis = $this->consultListQuery($sql);//query
        foreach ($result_lis as $result) {
            $sql2 = "select sum(t2.units_request) as units from dispatch_merchant t1 join dispatch_merchant_details t2 on t1.id = t2.dispatch WHERE t1.received = '$id' and t2.product_id='$result->id' LIMIT 1";
            $response = $this->consulQuery($sql2);
            $currentUnits = $response['units'];
            $res[] = array("id" => $result->id,
                "units_diff" => 0,
                "units_request" => 0,
                "unit" => ($result->unit - $currentUnits),
                "unidad_para_compra" => $result->unidad_para_almacen,
                "name" => $result->name);

        }
        return $res;

    }

    public function checkUnitsReceivedDispatch($data_table, $received_id, $dispatch_id)
    {

        //current received units
        $sql1 = "SELECT sum(units) as units FROM received_merchant_details WHERE received ='$received_id'";
        $res1 = $this->consulQuery($sql1);
        $units1 = $res1['units'];

        //current units in otther documents without this dispatch
        $sql2 = "SELECT sum(t1.units_request) as units FROM dispatch_merchant_details t1 join dispatch_merchant t2 on t1.dispatch = t2.id WHERE t2.received='$received_id' and t2.id<>'$dispatch_id'";
        $res2 = $this->consulQuery($sql2);
        $units2 = $res2['units'];

        //get current request units
        $unitsdocrequest = $this->getCurrentRequestUnits($data_table);

        return ($units2 + $unitsdocrequest) <= $units1;
    }

    public function checkUnitsPurchaseOrderToOrder($data_table, $purchase_order_id, $order_id)
    {

        //current received units
        $sql1 = "SELECT sum(units) as units FROM purchase_orders_details WHERE purchase_order ='$purchase_order_id'";
        $res1 = $this->consulQuery($sql1);
        $units1 = $res1['units'];

        //current units in otther documents without this
        $sql2 = "SELECT sum(t1.units_request) as units FROM orders_details t1 join orders t2 on t1.order_id = t2.id WHERE t2.purchase_order='$purchase_order_id' and t2.id<>'$order_id'";
        $res2 = $this->consulQuery($sql2);
        $units2 = $res2['units'];

        //get current request units
        $unitsdocrequest = $this->getCurrentRequestUnits($data_table);

        return ($units2 + $unitsdocrequest) <= $units1;

    }


    public function getCurrentRequestUnits($data_table)
    {

        $units = 0;
        foreach ($data_table as $data) {
            $units += $data->units_request;
        }
        return $units;
    }

    public function getUnitsProductsInOrder($order_id, $product_id)
    {

        $sql2 = "SELECT units_request FROM orders_details WHERE order_id ='$order_id' AND product_id='$product_id' limit 1";
        $units_request = $this->consulQuery($sql2);
        return $units_request['units_request'];
    }

    public function getDaystoAddDays($date, $days)
    {

        return date('Y-m-d', strtotime($date . ' + ' . $days . ' days'));

    }

    public function getKeyPass()
    {

        $sql_2 = " SELECT value  FROM config WHERE var='Keypass' limit 1";
        $sql_2_rs = $this->consulQuery($sql_2);
        $key = $sql_2_rs[0];

        return $key;
    }

    public function consulQuery($sql)
    {

        $connection = new dba();
        $result = mysqli_query($connection->connet(), $sql);
        if ($result) {

            $result = mysqli_fetch_array($result);

        } else {

            $result = "Query Failed! SQL: $sql - Error:  " . mysqli_error($this->conn);
        }
        return $result;

    }


    public function consultListQuery($sql)
    {
        $rowsfiel = array();
        $connection = new dba();
        $result = mysqli_query($connection->connet(), $sql);

        while ($member = mysqli_fetch_object($result)) $rowsfiel[] = $member;


        return $rowsfiel;
    }

    public function consultListQueryArray($sql)
    {
        $rowsfiel = array();
        $connection = new dba();
        $result = mysqli_query($connection->connet(), $sql);

        while ($member = mysqli_fetch_row($result)) $rowsfiel[] = $member;

        return $rowsfiel;
    }

    public function getTimetoDate($datecurr, $datedba)
    {

        $string = "";

        $fecha1 = new DateTime($datedba);//fecha inicial
        $fecha2 = new DateTime($datecurr);//fecha de cierre

        $intervalo = $fecha1->diff($fecha2);
        $days = $intervalo->format("%d");
        $hours = $intervalo->format("%H");
        $minutes = $intervalo->format("%i");

        if ($days != 0) {
            $string = 'Hace ' . $days . 'dias';
        }

        if ($days == 0 && $hours != 0 && $hours != 0) {
            $string = 'Hace ' . $hours . ' horas';
        }

        if ($days == 0 && $hours == 0 && $minutes != 0) {
            $string = 'Hace ' . $minutes . ' minutos ';
        }


        return $string;

    }

    public function convertoBase64($pathIMG)
    {

        $path = $pathIMG;

        // Extensión de la imagen
        $type = pathinfo($path, PATHINFO_EXTENSION);

        // Cargando la imagen
        $data = file_get_contents($path);

        // Decodificando la imagen en base64
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;

    }

    public function encodeString($string)
    {


        return utf8_encode($string);
    }

    public function getId_autoincrement($tablename)
    {
        /**
         *
         * TODO hacer el autoincrement como en kwprofile
         */
        $sql = "SELECT count(*) from prefix_id_autoincrement where table_name='$tablename' AND status='ACTIVO'";

        $result = $this->consulQuery($sql);
        $datetime = date('Y-m-d H:i:s');
        if ($result[0] == 0) {


            $sql = "INSERT INTO prefix_id_autoincrement (table_name,counter,tam,zero,updated,status)VALUES('$tablename',1,5,'SI','$datetime','ACTIVO')";
            $this->exeQuery($sql);

        }

        $sql_in = "SELECT counter,prefix,tam,zero FROM prefix_id_autoincrement WHERE table_name ='$tablename' and status='ACTIVO' ";
        $res_ini = $this->consulQuery($sql_in);

        $counter = (int)$res_ini[0];
        $prefix = $res_ini[1];
        $tam = (int)$res_ini[2];
        $zero = $res_ini[3];
        $counter += 1;

        $zeros_str = "";
        if ($zero == 'SI') {
            $size_id = strlen($counter);

            for ($i = 0; $i < ($tam - $size_id); $i++) {
                $zeros_str .= "0";

            }

            $id = $prefix . $zeros_str . $counter;

        } else {

            $id = $prefix . $counter;
        }

        $sql = "UPDATE prefix_id_autoincrement SET counter='$counter',updated='$datetime'  WHERE table_name ='$tablename' and status='ACTIVO'";
        $this->exeQuery($sql);


        return $id;

    }

    public function generate_string($strength = 16)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $input_length = strlen($permitted_chars);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    public function getRatingv1($rating)
    {

        $div = '<div class="star-rating">';

        for ($i = 0; $i < $rating; $i++) {

            $div .= '<i class="las la-star"></i>';
        }

        $div .= '</div>';
        return $div;
    }

    public function generarToken($longitud)
    {


        return bin2hex(openssl_random_pseudo_bytes(($longitud - ($longitud % 2)) / 2));
    }

    public function getAvailableProduct($product_id, $talla)
    {
        $count = 0;
        $sql1 = "SELECT COUNT(*) FROM products_available WHERE product_id='$product_id' AND talla='$talla' ";
        $resul = $this->consulQuery($sql1);
        if ($resul[0] > 0) {
            $sql2 = "SELECT (cantidad_entrada-cantidad_salida) as available FROM products_available WHERE product_id='$product_id' AND talla='$talla' ";
            $resu2 = $this->consulQuery($sql2);
            $count = $resu2[0];
        } else {
            $count = 0;
        }
        return $count;
    }

    public function getAvailableProductLibras($product_id, $libras)
    {
        $count = 0;
        $sql1 = "SELECT COUNT(*) FROM products_available WHERE product_id='$product_id' AND libras='$libras' ";
        $resul = $this->consulQuery($sql1);
        if ($resul[0] > 0) {
            $sql2 = "SELECT (cantidad_entrada-cantidad_salida) as available FROM products_available WHERE product_id='$product_id' AND libras='$libras' ";
            $resu2 = $this->consulQuery($sql2);
            $count = $resu2[0];
        } else {
            $count = 0;
        }
        return $count;
    }

    public function getAvailableProductGlobal($product_id)
    {
        $count = 0;
        $sql1 = "SELECT COUNT(*) FROM products_available WHERE product_id='$product_id'";
        $resul = $this->consulQuery($sql1);
        if ($resul[0] > 0) {

            $sql2 = "SELECT (sum(cantidad_entrada)-sum(cantidad_salida)) as available FROM products_available WHERE product_id='$product_id'";
            $resu2 = $this->consulQuery($sql2);
            $count = $resu2[0];
        } else {
            $count = 0;
        }


        return $count;
    }

    public function getAvailableAcesoriosGlobal($product_id)
    {
        $count = 0;
        $sql1 = "SELECT COUNT(*) FROM accesories_available WHERE product_id='$product_id'";
        $resul = $this->consulQuery($sql1);
        if ($resul[0] > 0) {
            $sql2 = "SELECT (sum(cantidad_entrada)-sum(cantidad_salida)) as available FROM accesories_available WHERE product_id='$product_id'";
            $resu2 = $this->consulQuery($sql2);
            $count = $resu2[0];
        } else {
            $count = 0;
        }


        return $count;
    }

    public function getProductPrice($product_id)
    {

        $slq = "SELECT price FROM products WHERE id='$product_id' limit 1";
        $resul = $this->consulQuery($slq);
        return $resul[0];
    }

    public function getProductItbms($product_id)
    {

        $slq = "SELECT itbms FROM products WHERE id='$product_id' limit 1";
        $resul = $this->consulQuery($slq);
        return $resul[0];
    }

    public function getProductType($product_id)
    {

        $slq = "SELECT type FROM products WHERE id='$product_id' limit 1";
        $resul = $this->consulQuery($slq);
        return $resul[0];

    }

    public function generaInvoice($orderid, $urldownload)
    {

//
// exemple de facture avec mysqli
// gere le multi-page
// Ver 1.0 THONGSOUME Jean-Paul
//
        /*
        date_default_timezone_set('America/Panama');
        header('Content-type: text/html; charset=UTF-8');


        require_once 'Config/Functions.php';
        $this = new Functions;  //llamando al objeto


            require('Others/fpdf/fpdf.php');*/

        $pdf = new FPDF('P', 'mm', array("216", "280"));


        $var_id_facture = $orderid;

        // on sup les 2 cm en bas
        $pdf->SetAutoPagebreak(False);
        $pdf->SetMargins(0, 0, 0);


        $pdf->AddPage();

        $pdf->Image('Others/Files_site/logo-v02.png', 10, 8, 80, 35);

        $pdf->SetXY(120, 5);
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(160, 8, 1 . '/' . 1, 0, 0, 'C');


        $pdf->SetLineWidth(0.1);
        $pdf->SetFillColor(192);
        $pdf->Rect(120, 15, 85, 8, "DF");
        $pdf->SetXY(120, 15);
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(85, 8, 'Orden #' . $var_id_facture, 0, 0, 'C');

        // nom du fichier final
        $nom_file = $urldownload . "Order_" . $orderid . ".pdf";


        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(133, 30);
        $pdf->Cell(60, 8, "Fecha " . date("m/d/Y"), 0, 0, '');


        $sql = "SELECT * FROM ordenes_company_deatils WHERE orden_id='$var_id_facture' limit 1";
        $item = (object)$this->consulQuery($sql);

        $carritoid = $item->carrito;
        $sql2 = "SELECT * FROM ordenes_company WHERE id='$var_id_facture' limit 1";
        $item2 = (object)$this->consulQuery($sql2);


        $pdf->SetFont("Arial", "B", 12);
        $pdf->SetXY(10, 48);
        $pdf->Cell(60, 0, "Datos del Cliente", 0, "L");

        $pdf->SetFont("Arial", "", 10);
        $pdf->SetXY(10, 55);
        $pdf->Cell(60, 0, "Nombre:", 0, "L");

        $pdf->SetXY(25, 55);
        $pdf->Cell(60, 0, $item2->Name . ' ' . $item2->LastName, 0, "L");


        $pdf->SetXY(10, 63);
        $pdf->Cell(60, 0, utf8_decode("Teléfono:"), 0, "L");

        $pdf->SetXY(26, 63);
        $pdf->Cell(60, 0, $item->phone, 0, "L");


        $pdf->SetXY(10, 70);
        $pdf->Cell(60, 0, utf8_decode("Dirección:"), 0, "L");

        $pdf->SetXY(27, 70);
        $pdf->Cell(60, 0, $item->address, 0, "L");


        $pdf->SetXY(10, 78);
        $pdf->Cell(60, 0, utf8_decode("Calle,Apartamento:"), 0, "L");

        $pdf->SetXY(42, 78);
        $pdf->Cell(60, 0, $item->towncity, 0, "L");


        $pdf->SetXY(10, 85);
        $pdf->Cell(60, 0, utf8_decode("Ciudad:"), 0, "L");


        $citi = $item->citi;
        $sqlcit = "SELECT name FROM cities WHERE id='$citi'";
        $itemcit = (object)$this->consulQuery($sqlcit);

        $pdf->SetXY(25, 85);
        $pdf->Cell(60, 0, utf8_decode($itemcit->name), 0, "L");


        $pdf->SetXY(10, 92);
        $pdf->Cell(60, 0, utf8_decode("Región:"), 0, "L");


        $sqlreg = "SELECT name FROM cities_region WHERE cities='$citi'";
        $itemreg = (object)$this->consulQuery($sqlreg);
        $pdf->SetXY(25, 92);
        $pdf->Cell(60, 0, utf8_decode($itemreg->name), 0, "L");


        $pdf->SetFont("Arial", "B", 12);
        $pdf->SetXY(120, 48);
        $pdf->Cell(60, 0, "Datos de Entrega", 0, "L");

        $pdf->SetFont("Arial", "", 10);

        $pdf->SetXY(120, 55);
        $pdf->Cell(60, 0, utf8_decode("Compañia:"), 0, "L");

        $sqlenv = "SELECT name FROM envios_company WHERE id='$item->send_to'";
        $sqlenv_r = (object)$this->consulQuery($sqlenv);

        $pdf->SetXY(139, 55);
        $pdf->Cell(60, 0, utf8_decode($sqlenv_r->name), 0, "L");


        $pdf->SetXY(120, 63);
        $pdf->Cell(60, 0, utf8_decode("Sucursal:"), 0, "L");

        $sqlenv2 = "SELECT name FROM envios_company_locations WHERE id='$item->send_to'";
        $sqlenv_r2 = (object)$this->consulQuery($sqlenv2);

        $pdf->SetXY(139, 63);
        $pdf->Cell(60, 0, utf8_decode($sqlenv_r2->name), 0, "L");

        $pdf->SetXY(120, 71);
        $pdf->Cell(60, 0, "Metodo de Pago:", 0, "L");

        $pdf->SetXY(150, 71);
        $pdf->Cell(60, 0, utf8_decode($item2->CardType), 0, "L");


        // adr fact du client


        $pdf->SetLineWidth(0.1);
        $pdf->Rect(5, 110, 200, 70, "D");


        $pdf->Line(5, 116, 205, 116);

        $pdf->Line(130, 110, 130, 180);
        $pdf->Line(150, 110, 150, 180);
        $pdf->Line(170, 110, 170, 180);
        $pdf->Line(187, 110, 187, 180);
        // titre colonne
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(1, 109);

        $pdf->Cell(140, 8, utf8_decode("Descripción"), 0, 0, 'C');

        $pdf->SetXY(129, 109);
        $pdf->Cell(22, 8, "Cantidad", 0, 0, 'C');

        $pdf->SetXY(148, 109);
        $pdf->Cell(22, 8, "Precio", 0, 0, 'C');

        $pdf->SetXY(174, 109);
        $pdf->Cell(10, 8, "Itbms", 0, 0, 'C');

        $pdf->SetXY(185, 109);
        $pdf->Cell(22, 8, "Total", 0, 0, 'C');


        $pdf->SetFont('Arial', '', 10);
        $sqlpro = "SELECT t3.id as id, t3.name as name,t3.type as type,t2.cantidad as cantidad,t2.itbms as itbms,t3.price as price  FROM carrito t1 join carrito_details t2 on t1.id=t2.carrito_id join  products t3 on t2.product_id=t3.id WHERE t1.id='$carritoid'";

        $result_lisc = $this->consultListQuery($sqlpro);//query
        $salt = 111;
        $totall = 0;
        $itbmsl = 0;
        $subtotal = 0;

        foreach ($result_lisc as $itemc) {
            if ($itemc->type == 'PRODUCT') {
                $sqltalla = "SELECT talla FROM products_tallas WHERE product_id='$itemc->id' AND status='ACTIVE'";
                $resulta = $this->consulQuery($sqltalla);

                $salt = $salt + 7;
                $pdf->SetXY(7, $salt);
                $pdf->Cell(140, 8, utf8_decode($itemc->name) . ' Talla ' . $resulta[0], 0, 0, 'L');

            } else {

                $salt = $salt + 7;
                $pdf->SetXY(7, $salt);
                $pdf->Cell(140, 8, utf8_decode($itemc->name), 0, 0, 'L');
            }

            $pdf->SetXY(137, $salt);
            $pdf->Cell(140, 8, utf8_decode($itemc->cantidad), 0, 0, 'L');

            $pdf->SetXY(30, $salt);
            $pdf->Cell(140, 8, "$" . utf8_decode($itemc->price), 0, 0, 'R');

            $pdf->SetXY(47, $salt);
            $pdf->Cell(140, 8, "$" . utf8_decode($itemc->itbms), 0, 0, 'R');
            $itbmsl += $itemc->itbms;
            $subtotal += ($itemc->cantidad * $itemc->price);
            $TOTAL = ($itemc->cantidad * $itemc->price) + $itemc->itbms;

            $pdf->SetXY(65, $salt);
            $pdf->Cell(140, 8, "$" . number_format($TOTAL, 2), 0, 0, 'R');
            $totall += $TOTAL;
        }


        // les articles
        $pdf->SetFont('Arial', '', 8);
        $y = 97;


        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(155, 185);
        $pdf->Cell(25, 6, "Subtotal", 0, 0, 'R');
        $pdf->SetXY(178, 185);
        $pdf->Cell(25, 6, "$" . number_format($subtotal, 2), 0, 0, 'R');

        $pdf->SetXY(155, 191);
        $pdf->Cell(25, 6, "Itbms", 0, 0, 'R');

        $pdf->SetXY(178, 191);
        $pdf->Cell(25, 6, "$" . number_format($itbmsl, 2), 0, 0, 'R');


        $enviocost = "";
        $sqlp = "SELECT price FROM envios_company WHERE id='$item->send_to' ";
        $result = $this->consulQuery($sqlp);
        if ($result[0] == '0.00') {
            $enviocost = "$" . $result[0];
        } else {
            $enviocost = "$" . $result[0];
            $totall = number_format($totall + $result[0], 2);
        }

        $pdf->SetXY(155, 198);
        $pdf->Cell(25, 6, "Envio", 0, 0, 'R');

        $pdf->SetXY(178, 198);
        $pdf->Cell(25, 6, $enviocost, 0, 0, 'R');


        $pdf->SetXY(155, 206);
        $pdf->Cell(25, 6, "Total", 0, 0, 'R');

        $pdf->SetXY(178, 206);
        $pdf->Cell(25, 6, "$" . number_format($totall, 2), 0, 0, 'R');

        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetXY(118, 220);
        $pdf->Cell(25, 6, "Gracias Por su Compra", 0, 0, 'R');


        $pdf->Output("F", $nom_file);


    }


    public function updateAavilableProduct($product_id, $cantidad, $talla)
    {
        $datetime = date('Y-m-d H:i:s');
        if ($this->getProductType($product_id) == 'PRODUCT') {

            $sql = "SELECT cantidad_salida FROM products_available WHERE product_id='$product_id' and talla='$talla'";
            $resul1 = $this->consulQuery($sql);
            $cantidad_salida = $resul1[0] + $cantidad;

            $sql2 = "UPDATE products_available SET cantidad_salida='$cantidad_salida',salidate_date='$datetime' WHERE product_id='$product_id' and talla='$talla' ";
            $this->exeQuery($sql2);
        } else {

            $sql = "SELECT cantidad_salida FROM accesories_available WHERE product_id='$product_id' ";
            $resul1 = $this->consulQuery($sql);
            $cantidad_salida = $resul1[0] + $cantidad;

            $sql2 = "UPDATE accesories_available SET cantidad_salida='$cantidad_salida',salidate_date='$datetime' WHERE product_id='$product_id' ";
            $this->exeQuery($sql2);

        }

    }


    public function getPendingOrders()
    {


        $sql = "SELECT count(*) FROM ordenes_company t1 join ordenes_company_deatils t2 on t1.id=t2.orden_id join envios_company t3 on t3.id=t2.send_to WHERE t1.status_interno='PENDIENTE' AND t1.Status='Approved' AND t2.pre_sale='NO'";
        $result = $this->consulQuery($sql);

        return $result[0];
    }

    public function getPendingPreSaleOrders()
    {


        $sql = "SELECT count(*) FROM ordenes_company t1 join ordenes_company_deatils t2 on t1.id=t2.orden_id join envios_company t3 on t3.id=t2.send_to WHERE t1.status_interno='PENDIENTE' AND t1.Status='Approved' AND t2.pre_sale='YES'";
        $result = $this->consulQuery($sql);

        return $result[0];
    }

}//fin de la clase
