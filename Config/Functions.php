<?php
date_default_timezone_set('America/Panama');
require("Config.php");
require('Session.php');
require('Others/fpdf/fpdf.php');

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Functions extends dba
{
    private $maximumFileTam = 2097152;
    private $enableClose = true;
    private $enableCancel = true;
    private $enableAutoIds = false;
    private $enableControlClosePurchaseOrder = true;
    private $decimalPdf = 2;
    private $importe_parcial_n = 0.00;

    public function __construct()
    {
    }
    public function enableAutoId()
    {

        return $this->enableAutoIds;
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

    public function getUserPermission($username)
    {
        $sqlper = "SELECT permission FROM users_permission WHERE username ='$username'";
        $resper = $this->consultListQuery($sqlper);
        $permission = array();
        if (count($resper) > 0) {
            foreach ($resper as $per) {
                $permission[] = $per->permission;
            }
        }
        return $permission;
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

        $sql1 = "SELECT IFNULL(sum(t2.units),0) as units FROM received_merchant t1 join received_merchant_details t2 on t1.id= t2.received WHERE t1.bills='$id'";
        $res1 = $this->consulQuery($sql1);
        $untis1 = (int)$res1['units'];


        $sql2 = "SELECT IFNULL(sum(t2.units),0) as units FROM  bills_details t2  WHERE t2.bill='$id'";
        $res2 = $this->consulQuery($sql2);
        $untis2 = (int)$res2['units'];

        return ($untis1 == $untis2);
    }

    public function checkIfAlreadyReceived($id)
    {

        $sql = "SELECT count(*) as count FROM received_merchant t1 WHERE t1.bills='$id'";
        $res = $this->consulQuery($sql);
        return ($res['count'] > 0);
    }

    public function chetIfBillsIsComplete($id)
    {

        $sql1 = "SELECT IFNULL(sum(t2.units),0) as units FROM received_merchant t1 join received_merchant_details t2 on t1.id= t2.received WHERE t1.id='$id'";
        $res1 = $this->consulQuery($sql1);
        $untis1 = $res1['units'];

        $sql2 = "SELECT IFNULL(sum(t2.units_request),0) as units FROM dispatch_merchant t1 join dispatch_merchant_details t2 on t1.id= t2.dispatch WHERE t1.received='$id'";
        $res2 = $this->consulQuery($sql2);
        $untis2 = $res2['units'];

        return ($untis1 == $untis2 && $untis2 > 0);
    }

    public function getPurchaseOrderToOrders($id)
    {
        $res = [];
        $sql = "SELECT t1.product_id as id,0 as units_diff,t1.units as unit,0 as units_request,t2.unidad_para_compra,t2.name FROM purchase_orders_details t1 join products t2 on t1.product_id = t2.id WHERE t1.purchase_order = '$id' ";
        $result_lis = $this->consultListQuery($sql); //query
        foreach ($result_lis as $result) {
            $sql2 = "select sum(t2.units_request) as units from orders t1 join orders_details t2 on t1.id = t2.order_id WHERE t1.purchase_order = '$id' and t2.product_id='$result->id' LIMIT 1";
            $response = $this->consulQuery($sql2);
            $currentUnits = $response['units'];
            $res[] = array(
                "id" => $result->id,
                "units_diff" => 0,
                "units_request" => 0,
                "unit" => ($result->unit - $currentUnits),
                "unidad_para_compra" => $result->unidad_para_compra,
                "name" => $result->name
            );
        }
        return $res;
    }

    public function getReceivedMerchantToDispatch($id)
    {
        $res = [];
        $sql = "SELECT t1.product_id as id,0 as units_diff,t1.units as unit,0 as units_request,t2.unidad_para_almacen,t2.name FROM received_merchant_details t1 join products t2 on t1.product_id = t2.id WHERE t1.received = '$id' ";
        $result_lis = $this->consultListQuery($sql); //query
        foreach ($result_lis as $result) {
            $sql2 = "select sum(t2.units_request) as units from dispatch_merchant t1 join dispatch_merchant_details t2 on t1.id = t2.dispatch WHERE t1.received = '$id' and t2.product_id='$result->id' LIMIT 1";
            $response = $this->consulQuery($sql2);
            $currentUnits = $response['units'];
            $res[] = array(
                "id" => $result->id,
                "units_diff" => 0,
                "units_request" => 0,
                "unit" => ($result->unit - $currentUnits),
                "unidad_para_compra" => $result->unidad_para_almacen,
                "name" => $result->name
            );
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

        $fecha1 = new DateTime($datedba); //fecha inicial
        $fecha2 = new DateTime($datecurr); //fecha de cierre

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



    public function generatePdfPurchaseRequest($id)
    {

        $pdf = new FPDF('P', 'mm', "A4");
        $pdf->SetAutoPagebreak(False);
        $pdf->SetMargins(0, 0, 0);

        $sql1 = "SELECT date,provider,reference FROM purchase_requests WHERE id='$id'";
        $maindata = $this->consulQuery($sql1);


        /**
         * DETALLE
         * *************************************************************
         */

        //$y += 32; // colocar para el detalle

        $totalRowPage = 20;
        $TOTAL = 0;
        $sql2 = "SELECT t1.product_id,t2.unidad_para_compra,t2.name,t1.units,t1.costs,t1.total FROM  purchase_requests_details t1 JOIN products t2 ON t1.product_id = t2.id WHERE t1.purchase_request='$id' ";
        $details = $this->consultListQuery($sql2);
        if (count($details) <= $totalRowPage) {
            $pdf->AddPage();


            $pdf->SetLineWidth(0.1);
            $pdf->SetFillColor(192);

            $pdf->Image($this->getLogov1(), 95, 4, 18);

            //$pdf->Rect(120, 15, 85, 8, "DF");

            $pdf->SetFont("Arial", "B", 8);
            $pdf->SetXY(165, 10);
            $pdf->AliasNbPages();
            $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
            $pdf->SetXY(80, 24);
            $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
            $pdf->SetXY(80, 28);
            $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
            $pdf->SetXY(80, 32);
            $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE COMPRAS'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(80, 38);
            $pdf->Cell(45, 8, utf8_decode('REQUISICIÓN DE COMPRA'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(120, 38);
            $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

            $pdf->SetXY(145, 38);
            $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

            $hei1 = 4;
            // nom du fichier final
            $pdf->SetFont("Arial", "", 11);
            $pdf->SetXY(10, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'L');

            $pdf->SetXY(25, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');


            $pdf->SetXY(10, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Unidad Gestora:'), 0, 0, 'L');

            $pdf->SetXY(41, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'L');


            ///////////////////////////////


            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(85, 63);
            $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


            $y = $pdf->GetY() - 9;


            $pdf->Rect(5, $y + 20, 200, 160, "D");

            $Yheader = 22;

            $pdf->SetFont("Arial", "", 8);

            $pdf->SetXY(8, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
            $pdf->Line(24, $y + 20, 24, $y + 172);

            $pdf->SetXY(12, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('UNIDAD'), 0, 0, 'C');
            $pdf->Line(45, $y + 20, 45, $y + 172);

            $pdf->SetXY(65, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
            $pdf->Line(135, $y + 20, 135, $y + 172);

            $pdf->SetXY(115, $y + $Yheader);
            $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
            $pdf->Line(147, $y + 20, 147, $y + 172);

            $pdf->SetXY(137, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

            $pdf->Line(170, $y + 20, 170, $y + 180);


            $pdf->SetXY(165, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
            $y += 32;
            for ($i = 0; $i < 20; $i++) {
                if (isset($details[$i])) {
                    $pdf->SetXY(5, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->product_id, 0, 'L');

                    $pdf->SetXY(24, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->unidad_para_compra, 0, 'L');


                    if (strlen($details[$i]->name) >= 90) {
                        $pdf->SetXY(46, $y + 1);
                        $pdf->MultiCell(90, 3, utf8_decode($details[$i]->name), 0, 'L');
                    } else {
                        $pdf->SetXY(46, $y + 3);
                        $pdf->MultiCell(90, 2, utf8_decode($details[$i]->name), 0, 'L');
                    }

                    $pdf->SetXY(137, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->units, 0, 'L');

                    $pdf->SetXY(120, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($details[$i]->costs, $this->decimalPdf), 0, 'R');

                    $pdf->SetXY(152, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($details[$i]->total, $this->decimalPdf), 0, 'R');
                    $TOTAL += $details[$i]->total;
                }
                $pdf->Line(5, $y, 205, $y);

                $y += 7;
            }
        } else {

            $numrows = count($details);
            $ln = ($numrows / $totalRowPage);

            if (is_float($ln)) {
                $numpages = intval(($numrows / $totalRowPage)) + 1;
            } else {
                $numpages = intval(($numrows / $totalRowPage));
            }
            $lasti = 0;
            for ($i = 1; $i <= $numpages; $i++) {
                $largeRect = 207;
                $lineVertical = 227;
                $lineVerticalTotal = 227;


                $checkiflast = ($numrows - ($lasti + 1));
                if ($checkiflast <= $totalRowPage) {
                    $nrow = $totalRowPage;
                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $numpages -= 1;
                } else {
                    $nrow = 27;
                }
                if ($i == $numpages) {

                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $nrow = 20;
                }
                $pdf->AddPage();

                $pdf->SetLineWidth(0.1);
                $pdf->SetFillColor(192);

                $pdf->Image($this->getLogov1(), 95, 4, 18);


                $pdf->SetFont("Arial", "B", 8);
                $pdf->SetXY(165, 10);
                $pdf->AliasNbPages();
                $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');

                $pdf->SetXY(80, 24);
                $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ '), 0, 0, 'C');
                $pdf->SetXY(80, 28);
                $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
                $pdf->SetXY(80, 32);
                $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE COMPRAS'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(80, 38);
                $pdf->Cell(45, 8, utf8_decode('REQUISICIÓN DE COMPRA'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 12);
                $pdf->SetXY(120, 44);
                $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

                $pdf->SetXY(145, 44);
                $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

                $hei1 = 4;
                // nom du fichier final
                $pdf->SetFont("Arial", "", 11);
                $pdf->SetXY(10, 55 - $hei1);

                $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'L');
                $pdf->SetXY(25, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');

                $pdf->SetXY(10, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Unidad Gestora:'), 0, 0, 'L');
                $pdf->SetFont("Arial", "", 11);
                $pdf->SetXY(41, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'L');


                ///////////////////////////////


                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(85, 63);
                $pdf->Cell(45, 8, utf8_decode('CAFETERÍA '), 0, 0, 'C');


                $y = $pdf->GetY() - 9;
                $pdf->Rect(5, $y + 20, 200, $largeRect, "D");


                $Yheader = 22;

                $pdf->SetFont("Arial", "", 8);

                $pdf->SetXY(8, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
                $pdf->Line(24, $y + 20, 24, $y + $lineVertical);

                $pdf->SetXY(12, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('UNIDAD'), 0, 0, 'C');
                $pdf->Line(45, $y + 20, 45, $y + $lineVertical);

                $pdf->SetXY(65, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
                $pdf->Line(135, $y + 20, 135, $y + $lineVertical);

                $pdf->SetXY(115, $y + $Yheader);
                $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
                $pdf->Line(147, $y + 20, 147, $y + $lineVertical);

                $pdf->SetXY(137, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

                $pdf->Line(170, $y + 20, 170, $y + $lineVerticalTotal);


                $pdf->SetXY(165, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
                $y += 32;

                for ($j = 0; $j < $nrow; $j++) {

                    if (isset($details[$lasti])) {
                        $pdf->SetXY(5, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->product_id, 0, 'L');

                        $pdf->SetXY(24, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->unidad_para_compra, 0, 'L');


                        if (strlen($details[$lasti]->name) >= 90) {
                            $pdf->SetXY(46, $y + 1);
                            $pdf->MultiCell(90, 3, utf8_decode($details[$lasti]->name), 0, 'L');
                        } else {
                            $pdf->SetXY(46, $y + 3);
                            $pdf->MultiCell(90, 2, utf8_decode($details[$lasti]->name), 0, 'L');
                        }

                        $pdf->SetXY(137, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->units, 0, 'L');

                        $pdf->SetXY(120, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($details[$lasti]->costs, $this->decimalPdf), 0, 'R');

                        $pdf->SetXY(152, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($details[$lasti]->total, $this->decimalPdf), 0, 'R');
                        $TOTAL += $details[$lasti]->total;
                        $lasti++;
                    }
                    $pdf->Line(5, $y, 205, $y);

                    $y += 7;
                }
            }
        }

        $pdf->Line(5, $y, 205, $y);
        /**
         * TOTAL DE LA TABLA
         */
        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetXY(137, $y);
        $pdf->Cell(45, 8, utf8_decode('TOTAL '), 0, 0, 'C');
        $pdf->SetXY(158, $y);
        $pdf->Cell(45, 8, '$' . number_format($TOTAL, $this->decimalPdf), 0, 0, 'R');


        /**
         * FOOTER
         * *************************************************
         */

        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetXY(1, $y + 10);
        $pdf->MultiCell(50, 8, utf8_decode('OBSERVACIONES:'), 0, 'C');

        $pdf->SetFont("Arial", "", 8);

        $pdf->SetXY(4, $y + 17);
        $pdf->MultiCell(80, 4, utf8_decode('Certifico que los materiales solicitados Contienen las especificaciones diseñadas por nosotros.'), 0, 'L');

        $pdf->SetXY(4, $y + 40);
        $pdf->MultiCell(100, 8, utf8_decode('Nombre y Firma del jefe del Departamento'), 0, 'L');
        $pdf->Line(5, $y + 40, 70, $y + 40);


        $pdf->SetFont("Arial", "", 8);
        $pdf->SetXY(4, $y + 58);
        $pdf->MultiCell(100, 8, utf8_decode('Nombre y Firma del Secretario(a) Administrativo'), 0, 'L');
        $pdf->Line(5, $y + 58, 70, $y + 58);
        /*
         * __________________________________________________________________________________
         *
         */

        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetXY(120, $y + 10);
        $pdf->MultiCell(50, 8, utf8_decode('DEPARTAMENTO DE COMPRAS'), 0, 'C');

        $pdf->SetFont("Arial", "", 8);
        $wb = -10;
        $pdf->SetXY(125 + $wb, $y + 17);
        $pdf->MultiCell(50, 8, utf8_decode('Fecha:'), 0, 'L');
        $pdf->Line(200, $y + 22, 126, $y + 22);

        $pdf->SetXY(115 + $wb, $y + 25);
        $pdf->MultiCell(50, 8, utf8_decode('Verificado por:'), 0, 'L');
        $pdf->Line(200, $y + 30, 126, $y + 30);

        $pdf->SetXY(118 + $wb, $y + 32);
        $pdf->MultiCell(50, 8, utf8_decode('Asignado a:'), 0, 'L');
        $pdf->Line(200, $y + 37, 126, $y + 37);

        $pdf->SetXY(125 + $wb, $y + 40);
        $pdf->MultiCell(50, 8, utf8_decode('Fecha:'), 0, 'L');
        $pdf->Line(200, $y + 45, 126, $y + 45);

        $pdf->SetXY(110 + $wb, $y + 47);
        $pdf->MultiCell(50, 8, utf8_decode('Forma de compra:'), 0, 'L');

        $pdf->SetXY(140 + $wb, $y + 48);
        $pdf->MultiCell(50, 8, utf8_decode('Contado:'), 0, 'L');


        $pdf->SetXY(155 + $wb, $y + 50);
        $pdf->Cell(3, 3, false, 1, 0);


        $pdf->SetXY(167 + $wb, $y + 48);
        $pdf->MultiCell(50, 8, utf8_decode('Crédito:'), 0, 'L');
        $pdf->SetXY(180 + $wb, $y + 50);
        $pdf->Cell(3, 3, false, 1, 0);


        $pdf->SetXY(163 + $wb, $y + 62);
        $pdf->MultiCell(100, 8, utf8_decode('Nombre y Vº.Bº.'), 0, 'L');
        $pdf->Line(120, $y + 63, 200, $y + 63);

        $pdf->Output("I", "file.pdf");
    }

    public function generatePdfPurchaseOrder($id)
    {

        $pdf = new FPDF('P', 'mm', "A4");
        $pdf->SetAutoPagebreak(False);
        $pdf->SetMargins(0, 0, 0);

        $sql1 = "SELECT t1.date,t1.reference,t1.purchase_request,t2.name as provider,t2.telephone1,t2.ruc as ruc,t2.dv FROM purchase_orders t1 JOIN providers t2 on t1.provider = t2.id WHERE t1.id='$id'";
        $maindata = $this->consulQuery($sql1);


        /**
         * DETALLE
         * *************************************************************
         */

        //$y += 32; // colocar para el detalle

        $totalRowPage = 20;
        $TOTAL = 0;
        $sql2 = "SELECT t1.product_id,t2.unidad_para_compra,t2.name,t1.units,t1.costs,t1.total FROM  purchase_orders_details t1 JOIN products t2 ON t1.product_id = t2.id WHERE t1.purchase_order='$id' ";
        $details = $this->consultListQuery($sql2);
        if (count($details) <= $totalRowPage) {
            $pdf->AddPage();


            $pdf->SetLineWidth(0.1);
            $pdf->SetFillColor(192);

            $pdf->Image($this->getLogov1(), 95, 4, 18);

            //$pdf->Rect(120, 15, 85, 8, "DF");


            $pdf->SetFont("Arial", "B", 8);
            $pdf->SetXY(165, 10);
            $pdf->AliasNbPages();
            $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
            $pdf->SetXY(80, 24);
            $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
            $pdf->SetXY(80, 28);
            $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
            $pdf->SetXY(80, 32);
            $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE COMPRAS'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(80, 38);
            $pdf->Cell(45, 8, utf8_decode('ORDEN DE COMPRA'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(120, 38);
            $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

            $pdf->SetXY(145, 38);
            $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

            $hei1 = 9;

            //IZQUIERO
            $pdf->SetFont("Arial", "", 11);
            $pdf->SetXY(10, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Señor:'), 0, 0, 'L');

            $pdf->SetXY(25, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['provider']), 0, 0, 'L');


            $pdf->SetXY(10, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');

            $pdf->SetXY(28, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['telephone1']), 0, 0, 'L');

            $pdf->SetXY(10, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('R.U.C:'), 0, 0, 'L');

            $pdf->SetXY(25, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['ruc']), 0, 0, 'L');

            $pdf->SetXY(10, 70 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('D.V:'), 0, 0, 'L');
            $pdf->SetXY(25, 70 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['dv']), 0, 0, 'L');

            ///////////////////////////////
            /// //DERECHO
            $heleft = 98;
            $pdf->SetXY($heleft, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');
            $pdf->SetXY($heleft + 45, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');


            $pdf->SetXY($heleft, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Requisición N°:'), 0, 0, 'R');

            $pdf->SetXY($heleft + 45, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['purchase_request']), 0, 0, 'L');


            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(85, 73);
            $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


            $y = $pdf->GetY() - 11;


            $pdf->Rect(5, $y + 20, 200, 160, "D");

            $Yheader = 22;

            $pdf->SetFont("Arial", "", 8);

            $pdf->SetXY(8, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
            $pdf->Line(24, $y + 20, 24, $y + 172);

            $pdf->SetXY(12, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('UNIDAD'), 0, 0, 'C');
            $pdf->Line(45, $y + 20, 45, $y + 172);

            $pdf->SetXY(65, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
            $pdf->Line(135, $y + 20, 135, $y + 172);

            $pdf->SetXY(115, $y + $Yheader);
            $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
            $pdf->Line(147, $y + 20, 147, $y + 172);

            $pdf->SetXY(137, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

            $pdf->Line(170, $y + 20, 170, $y + 180);


            $pdf->SetXY(165, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
            $y += 32;
            for ($i = 0; $i < 20; $i++) {
                if (isset($details[$i])) {
                    $pdf->SetXY(5, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->product_id, 0, 'L');

                    $pdf->SetXY(24, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->unidad_para_compra, 0, 'L');


                    if (strlen($details[$i]->name) >= 90) {
                        $pdf->SetXY(46, $y + 1);
                        $pdf->MultiCell(90, 3, utf8_decode($details[$i]->name), 0, 'L');
                    } else {
                        $pdf->SetXY(46, $y + 3);
                        $pdf->MultiCell(90, 2, utf8_decode($details[$i]->name), 0, 'L');
                    }

                    $pdf->SetXY(137, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->units, 0, 'L');

                    $pdf->SetXY(120, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($details[$i]->costs, $this->decimalPdf), 0, 'R');

                    $pdf->SetXY(152, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($details[$i]->total, $this->decimalPdf), 0, 'R');
                    $TOTAL += $details[$i]->total;
                }
                $pdf->Line(5, $y, 205, $y);

                $y += 7;
            }
        } else {

            $numrows = count($details);
            $ln = ($numrows / $totalRowPage);

            if (is_float($ln)) {
                $numpages = intval(($numrows / $totalRowPage)) + 1;
            } else {
                $numpages = intval(($numrows / $totalRowPage));
            }
            $lasti = 0;
            for ($i = 1; $i <= $numpages; $i++) {
                $largeRect = 207;
                $lineVertical = 227;
                $lineVerticalTotal = 227;


                $checkiflast = ($numrows - ($lasti + 1));
                if ($checkiflast <= $totalRowPage) {
                    $nrow = $totalRowPage;
                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $numpages -= 1;
                } else {
                    $nrow = 27;
                }
                if ($i == $numpages) {

                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $nrow = 20;
                }
                $pdf->AddPage();

                $pdf->SetLineWidth(0.1);
                $pdf->SetFillColor(192);

                $pdf->Image($this->getLogov1(), 95, 4, 18);


                $pdf->SetFont("Arial", "B", 8);

                $pdf->SetXY(165, 10);
                $pdf->AliasNbPages();
                $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');

                $pdf->SetXY(80, 24);
                $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ '), 0, 0, 'C');
                $pdf->SetXY(80, 28);
                $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
                $pdf->SetXY(80, 32);
                $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE COMPRAS'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(80, 38);
                $pdf->Cell(45, 8, utf8_decode('ORDEN DE COMPRA'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 12);
                $pdf->SetXY(120, 38);
                $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

                $pdf->SetXY(145, 38);
                $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

                $hei1 = 9;

                //IZQUIERO
                $pdf->SetFont("Arial", "", 11);
                $pdf->SetXY(10, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Señor:'), 0, 0, 'L');
                $pdf->SetXY(10, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');
                $pdf->SetXY(10, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('R.U.C:'), 0, 0, 'L');
                $pdf->SetXY(10, 70 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('D.V:'), 0, 0, 'L');


                ///////////////////////////////
                /// //DERECHO
                $heleft = 98;
                $pdf->SetXY($heleft, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');

                $pdf->SetXY($heleft, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Requisición N°:'), 0, 0, 'R');
                ///////////////////////////////


                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(85, 63);
                $pdf->Cell(45, 8, utf8_decode('CAFETERÍA '), 0, 0, 'C');


                $y = $pdf->GetY() - 9;
                $pdf->Rect(5, $y + 20, 200, $largeRect, "D");


                $Yheader = 22;

                $pdf->SetFont("Arial", "", 8);

                $pdf->SetXY(8, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
                $pdf->Line(24, $y + 20, 24, $y + $lineVertical);

                $pdf->SetXY(12, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('UNIDAD'), 0, 0, 'C');
                $pdf->Line(45, $y + 20, 45, $y + $lineVertical);

                $pdf->SetXY(65, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
                $pdf->Line(135, $y + 20, 135, $y + $lineVertical);

                $pdf->SetXY(115, $y + $Yheader);
                $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
                $pdf->Line(147, $y + 20, 147, $y + $lineVertical);

                $pdf->SetXY(137, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

                $pdf->Line(170, $y + 20, 170, $y + $lineVerticalTotal);


                $pdf->SetXY(165, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
                $y += 32;

                for ($j = 0; $j < $nrow; $j++) {

                    if (isset($details[$lasti])) {
                        $pdf->SetXY(5, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->product_id, 0, 'L');

                        $pdf->SetXY(24, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->unidad_para_compra, 0, 'L');


                        if (strlen($details[$lasti]->name) >= 90) {
                            $pdf->SetXY(46, $y + 1);
                            $pdf->MultiCell(90, 3, utf8_decode($details[$lasti]->name), 0, 'L');
                        } else {
                            $pdf->SetXY(46, $y + 3);
                            $pdf->MultiCell(90, 2, utf8_decode($details[$lasti]->name), 0, 'L');
                        }

                        $pdf->SetXY(137, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->units, 0, 'L');

                        $pdf->SetXY(120, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($details[$lasti]->costs, $this->decimalPdf), 0, 'R');

                        $pdf->SetXY(152, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($details[$lasti]->total, $this->decimalPdf), 0, 'R');
                        $TOTAL += $details[$lasti]->total;
                        $lasti++;
                    }
                    $pdf->Line(5, $y, 205, $y);

                    $y += 7;
                }
            }
        }

        $pdf->Line(5, $y, 205, $y);
        /**
         * TOTAL DE LA TABLA
         */
        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetXY(137, $y);
        $pdf->Cell(45, 8, utf8_decode('TOTAL '), 0, 0, 'C');
        $pdf->SetXY(158, $y);
        $pdf->Cell(45, 8, '$' . number_format($TOTAL, $this->decimalPdf), 0, 0, 'R');


        /**
         * FOOTER
         * *************************************************
         */

        $pdf->SetFont("Arial", "", 8);
        $pdf->SetXY(75, $y + 7);
        $pdf->Cell(50, 8, utf8_decode('Observaciones: Solicitud URGENTE, para uso en el Kiosco de esta unidad administrativa'), 0, 0, 'C');

        $pdf->SetXY(76, $y + 20);
        $pdf->Cell(50, 8, utf8_decode('LUGAR DE ENTREGA: '), 0, 0, 'C');


        $y -= 8;
        $pdf->Line(5, $y + 30, 60, $y + 30);
        $pdf->SetXY(10, $y + 28);
        $pdf->Cell(50, 8, utf8_decode('Director del CRUV'), 0, 0, 'C');

        $pdf->Line(5, $y + 43, 60, $y + 43);
        $pdf->SetXY(10, $y + 41);
        $pdf->Cell(50, 8, utf8_decode('Jefe de Contabilidad'), 0, 0, 'C');

        $pdf->Line(5, $y + 56, 60, $y + 56);
        $pdf->SetXY(10, $y + 54);
        $pdf->Cell(50, 8, utf8_decode('Jefe de Compras'), 0, 0, 'C');


        $pdf->Line(205, $y + 30, 150, $y + 30);
        $pdf->SetXY(155, $y + 28);
        $pdf->Cell(50, 8, utf8_decode('Jefe de Fiscalización'), 0, 0, 'C');

        $pdf->Line(205, $y + 43, 150, $y + 43);
        $pdf->SetXY(155, $y + 41);
        $pdf->Cell(50, 8, utf8_decode('Acepto Proveedor Firma y Fecha'), 0, 0, 'C');


        $pdf->Line(205, $y + 56, 150, $y + 56);
        $pdf->SetXY(155, $y + 54);
        $pdf->Cell(50, 8, utf8_decode('Recibido en Almacén Firma y Fecha'), 0, 0, 'C');

        $y += 59;
        $pdf->SetFont("Arial", "", 6);
        $pdf->SetXY(75, $y);
        $pdf->Cell(50, 8, utf8_decode('LA UNIVERSIDAD DE PANAMÁ ESTA EXENTA DEL IMPUESTO, SEGUN EL ARTICULO N°59, LEY 24 DEL 14 DEL JULIO DE 2005'), 0, 0, 'C');
        $pdf->SetXY(75, $y + 3);
        $pdf->Cell(50, 8, utf8_decode('FUNDAMENTO LEGAL: LEY N°22 DEL 27 DE JUNIO DE 2006. DECRETO EJECUTIVO N°366 DE 28 DE DICIEMBRE DE 2006'), 0, 0, 'C');
        $pdf->SetFont("Arial", "b", 6);
        $pdf->SetXY(75, $y + 6);
        $pdf->Cell(50, 8, utf8_decode('Para su pago, Sirvase remitir adjunti a su cuenta,esta Orden de Compra'), 0, 0, 'C');

        $pdf->Output("I", "file.pdf");
    }

    public function generatePdfReceiveMerchant($id)
    {

        $pdf = new FPDF('P', 'mm', "A4");
        $pdf->SetAutoPagebreak(False);
        $pdf->SetMargins(0, 0, 0);

        $sql1 = "SELECT DATE_FORMAT(t1.date,'%Y-%m-%d') as date,t1.reference,t1.bills,t2.order_id,t3.purchase_order,t5.name as provider,t5.ruc,t5.dv,t5.telephone1 FROM received_merchant t1 JOIN bills t2 on t1.bills=t2.id JOIN orders t3 on t2.order_id= t3.id JOIN purchase_orders t4 on t3.purchase_order=t4.id JOIN providers t5 on t4.provider=t5.id WHERE t1.id='$id' ";
        $maindata = $this->consulQuery($sql1);


        /**
         * DETALLE
         * *************************************************************
         */

        //$y += 32; // colocar para el detalle

        $totalRowPage = 20;
        $TOTAL = 0;
        $sql2 = "SELECT t1.product_id,t2.unidad_para_almacen,t2.name,t1.units,t1.costs,t1.total FROM  received_merchant_details t1 JOIN products t2 ON t1.product_id = t2.id WHERE t1.received='$id' ";
        $details = $this->consultListQuery($sql2);
        if (count($details) <= $totalRowPage) {
            $pdf->AddPage();


            $pdf->SetLineWidth(0.1);
            $pdf->SetFillColor(192);

            $pdf->Image($this->getLogov1(), 95, 4, 18);

            //$pdf->Rect(120, 15, 85, 8, "DF");


            $pdf->SetFont("Arial", "B", 8);
            $pdf->SetXY(165, 10);
            $pdf->AliasNbPages();
            $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
            $pdf->SetXY(80, 24);
            $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
            $pdf->SetXY(80, 28);
            $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
            $pdf->SetXY(80, 32);
            $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE ALMACÉN'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(80, 38);
            $pdf->Cell(45, 8, utf8_decode('RECEPCIÓN DE MERCANCÍA'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(120, 38);
            $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

            $pdf->SetXY(145, 38);
            $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

            $hei1 = 9;

            //IZQUIERO
            $pdf->SetFont("Arial", "", 11);
            $pdf->SetXY(10, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Señor:'), 0, 0, 'L');

            $pdf->SetXY(25, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['provider']), 0, 0, 'L');


            $pdf->SetXY(10, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');

            $pdf->SetXY(28, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['telephone1']), 0, 0, 'L');

            $pdf->SetXY(10, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('R.U.C:'), 0, 0, 'L');

            $pdf->SetXY(25, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['ruc']), 0, 0, 'L');

            $pdf->SetXY(10, 70 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('D.V:'), 0, 0, 'L');
            $pdf->SetXY(25, 70 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['dv']), 0, 0, 'L');

            ///////////////////////////////
            /// //DERECHO
            $heleft = 98;
            $pdf->SetXY($heleft, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');
            $pdf->SetXY($heleft + 45, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');

            $pdf->SetXY($heleft, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Factura N°:'), 0, 0, 'R');
            $pdf->SetXY($heleft + 45, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['bills']), 0, 0, 'L');


            $pdf->SetXY($heleft, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Orden de Compra N°:'), 0, 0, 'R');

            $pdf->SetXY($heleft + 45, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['purchase_order']), 0, 0, 'L');


            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(85, 68);
            $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


            $y = $pdf->GetY() - 11;


            $pdf->Rect(5, $y + 20, 200, 160, "D");

            $Yheader = 22;

            $pdf->SetFont("Arial", "", 8);

            $pdf->SetXY(8, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
            $pdf->Line(24, $y + 20, 24, $y + 172);

            $pdf->SetXY(12, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('UNIDAD'), 0, 0, 'C');
            $pdf->Line(45, $y + 20, 45, $y + 172);

            $pdf->SetXY(65, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
            $pdf->Line(135, $y + 20, 135, $y + 172);

            $pdf->SetXY(115, $y + $Yheader);
            $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
            $pdf->Line(147, $y + 20, 147, $y + 172);

            $pdf->SetXY(137, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

            $pdf->Line(170, $y + 20, 170, $y + 180);


            $pdf->SetXY(165, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
            $y += 32;
            for ($i = 0; $i < 20; $i++) {
                if (isset($details[$i])) {
                    $pdf->SetXY(5, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->product_id, 0, 'L');

                    $pdf->SetXY(24, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->unidad_para_almacen, 0, 'L');


                    if (strlen($details[$i]->name) >= 90) {
                        $pdf->SetXY(46, $y + 1);
                        $pdf->MultiCell(90, 3, utf8_decode($details[$i]->name), 0, 'L');
                    } else {
                        $pdf->SetXY(46, $y + 3);
                        $pdf->MultiCell(90, 2, utf8_decode($details[$i]->name), 0, 'L');
                    }

                    $pdf->SetXY(137, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->units, 0, 'L');

                    $pdf->SetXY(120, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($details[$i]->costs, $this->decimalPdf), 0, 'R');

                    $pdf->SetXY(152, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($details[$i]->total, $this->decimalPdf), 0, 'R');
                    $TOTAL += $details[$i]->total;
                }
                $pdf->Line(5, $y, 205, $y);

                $y += 7;
            }
        } else {

            $numrows = count($details);
            $ln = ($numrows / $totalRowPage);

            if (is_float($ln)) {
                $numpages = intval(($numrows / $totalRowPage)) + 1;
            } else {
                $numpages = intval(($numrows / $totalRowPage));
            }
            $lasti = 0;
            for ($i = 1; $i <= $numpages; $i++) {
                $largeRect = 207;
                $lineVertical = 227;
                $lineVerticalTotal = 227;


                $checkiflast = ($numrows - ($lasti + 1));
                if ($checkiflast <= $totalRowPage) {
                    $nrow = $totalRowPage;
                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $numpages -= 1;
                } else {
                    $nrow = 27;
                }
                if ($i == $numpages) {

                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $nrow = 20;
                }
                $pdf->AddPage();


                $pdf->SetLineWidth(0.1);
                $pdf->SetFillColor(192);

                $pdf->Image($this->getLogov1(), 95, 4, 18);

                //$pdf->Rect(120, 15, 85, 8, "DF");


                $pdf->SetFont("Arial", "B", 8);
                $pdf->SetXY(165, 10);
                $pdf->AliasNbPages();
                $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
                $pdf->SetXY(80, 24);
                $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
                $pdf->SetXY(80, 28);
                $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
                $pdf->SetXY(80, 32);
                $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE ALMACÉN'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(80, 38);
                $pdf->Cell(45, 8, utf8_decode('RECEPCIÓN DE MERCANCÍA'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 12);
                $pdf->SetXY(120, 38);
                $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

                $pdf->SetXY(145, 38);
                $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

                $hei1 = 9;

                //IZQUIERO
                $pdf->SetFont("Arial", "", 11);
                $pdf->SetXY(10, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Señor:'), 0, 0, 'L');

                $pdf->SetXY(25, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['provider']), 0, 0, 'L');


                $pdf->SetXY(10, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');

                $pdf->SetXY(28, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['telephone1']), 0, 0, 'L');

                $pdf->SetXY(10, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('R.U.C:'), 0, 0, 'L');

                $pdf->SetXY(25, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['ruc']), 0, 0, 'L');

                $pdf->SetXY(10, 70 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('D.V:'), 0, 0, 'L');
                $pdf->SetXY(25, 70 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['dv']), 0, 0, 'L');

                ///////////////////////////////
                /// //DERECHO
                $heleft = 98;
                $pdf->SetXY($heleft, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');
                $pdf->SetXY($heleft + 45, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');

                $pdf->SetXY($heleft, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Factura N°:'), 0, 0, 'R');
                $pdf->SetXY($heleft + 45, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['bills']), 0, 0, 'L');


                $pdf->SetXY($heleft, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Orden de Compra N°:'), 0, 0, 'R');

                $pdf->SetXY($heleft + 45, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['purchase_order']), 0, 0, 'L');


                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(85, 68);
                $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


                $y = $pdf->GetY() - 9;
                $pdf->Rect(5, $y + 20, 200, $largeRect, "D");


                $Yheader = 22;

                $pdf->SetFont("Arial", "", 8);

                $pdf->SetXY(8, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
                $pdf->Line(24, $y + 20, 24, $y + $lineVertical);

                $pdf->SetXY(12, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('UNIDAD'), 0, 0, 'C');
                $pdf->Line(45, $y + 20, 45, $y + $lineVertical);

                $pdf->SetXY(65, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
                $pdf->Line(135, $y + 20, 135, $y + $lineVertical);

                $pdf->SetXY(115, $y + $Yheader);
                $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
                $pdf->Line(147, $y + 20, 147, $y + $lineVertical);

                $pdf->SetXY(137, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

                $pdf->Line(170, $y + 20, 170, $y + $lineVerticalTotal);


                $pdf->SetXY(165, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
                $y += 32;

                for ($j = 0; $j < $nrow; $j++) {

                    if (isset($details[$lasti])) {
                        $pdf->SetXY(5, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->product_id, 0, 'L');

                        $pdf->SetXY(24, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->unidad_para_almacen, 0, 'L');


                        if (strlen($details[$lasti]->name) >= 90) {
                            $pdf->SetXY(46, $y + 1);
                            $pdf->MultiCell(90, 3, utf8_decode($details[$lasti]->name), 0, 'L');
                        } else {
                            $pdf->SetXY(46, $y + 3);
                            $pdf->MultiCell(90, 2, utf8_decode($details[$lasti]->name), 0, 'L');
                        }

                        $pdf->SetXY(137, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->units, 0, 'L');

                        $pdf->SetXY(120, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($details[$lasti]->costs, $this->decimalPdf), 0, 'R');

                        $pdf->SetXY(152, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($details[$lasti]->total, $this->decimalPdf), 0, 'R');
                        $TOTAL += $details[$lasti]->total;
                        $lasti++;
                    }
                    $pdf->Line(5, $y, 205, $y);

                    $y += 7;
                }
            }
        }

        $pdf->Line(5, $y, 205, $y);
        /**
         * TOTAL DE LA TABLA
         */
        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetXY(137, $y);
        $pdf->Cell(45, 8, utf8_decode('TOTAL '), 0, 0, 'C');
        $pdf->SetXY(158, $y);
        $pdf->Cell(45, 8, '$' . number_format($TOTAL, $this->decimalPdf), 0, 0, 'R');


        /**
         * FOOTER
         * *************************************************
         */

        $pdf->SetFont("Arial", "", 8);
        $pdf->SetXY(75, $y + 10);
        $pdf->Cell(50, 8, utf8_decode('AFECTACIÓN CONTABLE'), 0, 0, 'C');

        $pdf->Rect(5, $y + 16, 200, 29, "D");
        $pdf->SetFont("Arial", "", 8);

        $pdf->SetXY(8, $y + 15);
        $pdf->Cell(45, 8, utf8_decode('CÓDIGO'), 0, 0, 'L');
        $pdf->Line(37, $y + 16, 37, $y + 45);

        $pdf->SetXY(60, $y + 15);
        $pdf->Cell(45, 8, utf8_decode('NOMBRE DE LA CUENTA'), 0, 0, 'C');
        $pdf->Line(128, $y + 16, 128, $y + 45);

        $pdf->SetXY(120, $y + 15);
        $pdf->Cell(45, 8, utf8_decode('DEBE'), 0, 0, 'C');
        $pdf->Line(167, $y + 16, 167, $y + 45);

        $pdf->SetXY(160, $y + 15);
        $pdf->Cell(45, 8, utf8_decode('HABER'), 0, 0, 'C');

        $pdf->Line(5, $y + 21, 205, $y + 21);
        $pdf->Line(5, $y + 28, 205, $y + 28);
        $pdf->Line(5, $y + 37, 205, $y + 37);


        $pdf->SetXY(10, $y + 47);
        $pdf->Cell(45, 8, utf8_decode('Observaciones:'), 0, 0, 'L');

        $pdf->Line(32, $y + 52, 205, $y + 52);

        $pdf->SetXY(10, $y + 56);

        $pdf->Cell(45, 8, utf8_decode('Recibido por:'), 0, 0, 'L');
        $pdf->Line(30, $y + 61, 100, $y + 61);

        $pdf->SetXY(82, $y + 56);
        $pdf->Cell(40, 8, utf8_decode('Revisado por:'), 0, 0, 'R');
        $pdf->Line(122, $y + 61, 205, $y + 61);


        $pdf->Output("I", "file.pdf");
    }

    public function generatePdfDispatchMerchant($id)
    {

        $pdf = new FPDF('P', 'mm', "A4");
        $pdf->SetAutoPagebreak(False);
        $pdf->SetMargins(0, 0, 0);

        $sql1 = "SELECT DATE_FORMAT( t1.date, '%Y-%m-%d' ) AS date,t1.reference,t3.order_id,t1.received FROM dispatch_merchant t1 JOIN received_merchant t2 ON t1.received = t2.id JOIN bills t3 ON t2.bills = t3.id WHERE
                    t1.id = '$id'";
        $maindata = $this->consulQuery($sql1);


        /**
         * DETALLE
         * *************************************************************
         */

        //$y += 32; // colocar para el detalle

        $totalRowPage = 20;
        $TOTAL = 0;
        $sql2 = "SELECT t1.product_id,t5.unidad_para_almacen,t5.NAME as name,t1.units_request AS units FROM dispatch_merchant_details t1 JOIN dispatch_merchant t2 ON t1.dispatch = t2.id JOIN products t5 ON t1.product_id = t5.id WHERE t1.dispatch = '$id'";
        $details = $this->consultListQuery($sql2);
        if (count($details) <= $totalRowPage) {
            $pdf->AddPage();


            $pdf->SetLineWidth(0.1);
            $pdf->SetFillColor(192);

            $pdf->Image($this->getLogov1(), 95, 4, 18);

            //$pdf->Rect(120, 15, 85, 8, "DF");

            $pdf->SetFont("Arial", "B", 8);
            $pdf->SetXY(165, 10);
            $pdf->AliasNbPages();
            $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
            $pdf->SetXY(80, 24);
            $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
            $pdf->SetXY(80, 28);
            $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
            $pdf->SetXY(80, 32);
            $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE ALMACÉN'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(80, 38);
            $pdf->Cell(45, 8, utf8_decode('DESPACHO DE MERCANCÍA'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(120, 38);
            $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

            $pdf->SetXY(145, 38);
            $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

            $hei1 = 4;
            // nom du fichier final
            $pdf->SetFont("Arial", "", 11);
            $pdf->SetXY(10, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'L');

            $pdf->SetXY(25, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');



            $pdf->SetXY(10, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Pedido:'), 0, 0, 'L');

            $pdf->SetXY(25, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['order_id']), 0, 0, 'L');




            ///////////////////////////////


            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(85, 63);
            $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


            $y = $pdf->GetY() - 9;


            $pdf->Rect(5, $y + 20, 200, 160, "D");

            $Yheader = 22;

            $pdf->SetFont("Arial", "", 8);

            $pdf->SetXY(8, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
            $pdf->Line(24, $y + 20, 24, $y + 172);

            $pdf->SetXY(12, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('UNIDAD'), 0, 0, 'C');
            $pdf->Line(45, $y + 20, 45, $y + 172);

            $pdf->SetXY(65, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
            $pdf->Line(135, $y + 20, 135, $y + 172);

            $pdf->SetXY(115, $y + $Yheader);
            $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
            $pdf->Line(147, $y + 20, 147, $y + 172);

            $pdf->SetXY(137, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

            $pdf->Line(170, $y + 20, 170, $y + 180);


            $pdf->SetXY(165, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
            $y += 32;
            for ($i = 0; $i < 20; $i++) {
                if (isset($details[$i])) {
                    $pdf->SetXY(5, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->product_id, 0, 'L');

                    $pdf->SetXY(24, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->unidad_para_almacen, 0, 'L');


                    if (strlen($details[$i]->name) >= 90) {
                        $pdf->SetXY(46, $y + 1);
                        $pdf->MultiCell(90, 3, utf8_decode($details[$i]->name), 0, 'L');
                    } else {
                        $pdf->SetXY(46, $y + 3);
                        $pdf->MultiCell(90, 2, utf8_decode($details[$i]->name), 0, 'L');
                    }

                    $pdf->SetXY(137, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->units, 0, 'L');


                    $recmerchantid = $maindata['received'];
                    $productid = $details[$i]->product_id;
                    $sqlcosts = "SELECT costs from received_merchant_details WHERE product_id='$productid' and received='$recmerchantid' limit 1";
                    $rescosts = $this->consulQuery($sqlcosts);

                    $productCosts = ($rescosts['costs'] * $details[$i]->units);
                    $pdf->SetXY(137, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->units, 0, 'L');

                    $pdf->SetXY(120, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($rescosts['costs'], $this->decimalPdf), 0, 'R');

                    $pdf->SetXY(152, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($productCosts, $this->decimalPdf), 0, 'R');
                    $TOTAL += $productCosts;
                }
                $pdf->Line(5, $y, 205, $y);

                $y += 7;
            }
        } else {

            $numrows = count($details);
            $ln = ($numrows / $totalRowPage);

            if (is_float($ln)) {
                $numpages = intval(($numrows / $totalRowPage)) + 1;
            } else {
                $numpages = intval(($numrows / $totalRowPage));
            }
            $lasti = 0;
            for ($i = 1; $i <= $numpages; $i++) {
                $largeRect = 207;
                $lineVertical = 227;
                $lineVerticalTotal = 227;


                $checkiflast = ($numrows - ($lasti + 1));
                if ($checkiflast <= $totalRowPage) {
                    $nrow = $totalRowPage;
                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $numpages -= 1;
                } else {
                    $nrow = 27;
                }
                if ($i == $numpages) {

                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $nrow = 20;
                }
                $pdf->AddPage();

                $pdf->SetLineWidth(0.1);
                $pdf->SetFillColor(192);

                $pdf->Image($this->getLogov1(), 95, 4, 18);


                $pdf->SetFont("Arial", "B", 8);
                $pdf->SetXY(165, 10);
                $pdf->AliasNbPages();
                $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');

                $pdf->SetXY(80, 24);
                $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ '), 0, 0, 'C');
                $pdf->SetXY(80, 28);
                $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
                $pdf->SetXY(80, 32);
                $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE ALMACÉN'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(80, 38);
                $pdf->Cell(45, 8, utf8_decode('DESPACHO DE MERCANCÍA'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 12);
                $pdf->SetXY(120, 44);
                $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

                $pdf->SetXY(145, 44);
                $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

                $hei1 = 4;
                // nom du fichier final
                $pdf->SetFont("Arial", "", 11);
                $pdf->SetXY(10, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'L');

                $pdf->SetXY(25, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');


                $pdf->SetXY(10, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Pedido:'), 0, 0, 'L');

                $pdf->SetXY(25, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['order_id']), 0, 0, 'L');


                ///////////////////////////////


                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(85, 63);
                $pdf->Cell(45, 8, utf8_decode('CAFETERÍA '), 0, 0, 'C');


                $y = $pdf->GetY() - 9;
                $pdf->Rect(5, $y + 20, 200, $largeRect, "D");


                $Yheader = 22;

                $pdf->SetFont("Arial", "", 8);

                $pdf->SetXY(8, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
                $pdf->Line(24, $y + 20, 24, $y + $lineVertical);

                $pdf->SetXY(12, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('UNIDAD'), 0, 0, 'C');
                $pdf->Line(45, $y + 20, 45, $y + $lineVertical);

                $pdf->SetXY(65, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
                $pdf->Line(135, $y + 20, 135, $y + $lineVertical);

                $pdf->SetXY(115, $y + $Yheader);
                $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
                $pdf->Line(147, $y + 20, 147, $y + $lineVertical);

                $pdf->SetXY(137, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

                $pdf->Line(170, $y + 20, 170, $y + $lineVerticalTotal);


                $pdf->SetXY(165, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
                $y += 32;

                for ($j = 0; $j < $nrow; $j++) {

                    if (isset($details[$lasti])) {
                        $pdf->SetXY(5, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->product_id, 0, 'L');

                        $pdf->SetXY(24, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->unidad_para_almacen, 0, 'L');


                        if (strlen($details[$lasti]->name) >= 90) {
                            $pdf->SetXY(46, $y + 1);
                            $pdf->MultiCell(90, 3, utf8_decode($details[$lasti]->name), 0, 'L');
                        } else {
                            $pdf->SetXY(46, $y + 3);
                            $pdf->MultiCell(90, 2, utf8_decode($details[$lasti]->name), 0, 'L');
                        }

                        $pdf->SetXY(137, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->units, 0, 'L');

                        $recmerchantid = $maindata['received'];
                        $productid = $details[$lasti]->product_id;
                        $sqlcosts = "SELECT costs from received_merchant_details WHERE product_id='$productid' and received='$recmerchantid' limit 1";
                        $rescosts = $this->consulQuery($sqlcosts);

                        $productCosts = ($rescosts['costs'] * $details[$lasti]->units);
                        $pdf->SetXY(137, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->units, 0, 'L');

                        $pdf->SetXY(120, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($rescosts['costs'], $this->decimalPdf), 0, 'R');

                        $pdf->SetXY(152, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($productCosts, $this->decimalPdf), 0, 'R');
                        $TOTAL += $productCosts;

                        $lasti++;
                    }
                    $pdf->Line(5, $y, 205, $y);

                    $y += 7;
                }
            }
        }

        $pdf->Line(5, $y, 205, $y);
        /**
         * TOTAL DE LA TABLA
         */
        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetXY(137, $y);
        $pdf->Cell(45, 8, utf8_decode('TOTAL '), 0, 0, 'C');
        $pdf->SetXY(158, $y);
        $pdf->Cell(45, 8, '$' . number_format($TOTAL, $this->decimalPdf), 0, 0, 'R');


        /**
         * FOOTER
         * *************************************************
         */


        $pdf->SetFont("Arial", "", 8);
        $pdf->SetXY(25, $y + 58);
        $pdf->MultiCell(100, 8, utf8_decode('Recibido por'), 0, 'L');
        $pdf->Line(5, $y + 58, 70, $y + 58);
        /*
         * __________________________________________________________________________________
         *
         */


        $wb = -10;

        $pdf->SetXY(163 + $wb, $y + 58);
        $pdf->MultiCell(100, 8, utf8_decode('Entregado Almacenista'), 0, 'L');
        $pdf->Line(205, $y + 58, 135, $y + 58);


        $pdf->Output("I", "file.pdf");
    }
    public function generatePdfOrders($id)
    {

        $pdf = new FPDF('P', 'mm', "A4");
        $pdf->SetAutoPagebreak(False);
        $pdf->SetMargins(0, 0, 0);

        $sql1 = "SELECT  DATE_FORMAT(t1.date,'%Y-%m-%d') as date,t1.reference,t2.`name` AS provider,t2.telephone1,t1.purchase_order,t2.ruc,t2.dv  from orders t1 join providers t2 WHERE t1.id='$id' ";
        $maindata = $this->consulQuery($sql1);


        /**
         * DETALLE
         * *************************************************************
         */

        //$y += 32; // colocar para el detalle

        $totalRowPage = 20;
        $TOTAL = 0;
        $sql2 = "SELECT t1.product_id,t2.name,t1.units_buy,t1.units_request,t2.price FROM  orders_details t1 JOIN products t2 ON t1.product_id = t2.id WHERE t1.order_id='$id' ";
        $details = $this->consultListQuery($sql2);

        if (count($details) <= $totalRowPage) {
            $pdf->AddPage();


            $pdf->SetLineWidth(0.1);
            $pdf->SetFillColor(192);

            $pdf->Image($this->getLogov1(), 95, 4, 18);

            //$pdf->Rect(120, 15, 85, 8, "DF");


            $pdf->SetFont("Arial", "B", 8);
            $pdf->SetXY(165, 10);
            $pdf->AliasNbPages();
            $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
            $pdf->SetXY(80, 24);
            $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
            $pdf->SetXY(80, 28);
            $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
            $pdf->SetXY(80, 32);
            $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE CAFETERIA'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(80, 38);
            $pdf->Cell(45, 8, utf8_decode('PEDIDOS'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(120, 38);
            $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

            $pdf->SetXY(145, 38);
            $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

            $hei1 = 9;

            //IZQUIERO
            $pdf->SetFont("Arial", "", 11);
            $pdf->SetXY(10, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Señor:'), 0, 0, 'L');

            $pdf->SetXY(25, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['provider']), 0, 0, 'L');


            $pdf->SetXY(10, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');

            $pdf->SetXY(28, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['telephone1']), 0, 0, 'L');

            $pdf->SetXY(10, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('R.U.C:'), 0, 0, 'L');

            $pdf->SetXY(25, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['ruc']), 0, 0, 'L');

            $pdf->SetXY(10, 70 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('D.V:'), 0, 0, 'L');
            $pdf->SetXY(25, 70 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['dv']), 0, 0, 'L');

            ///////////////////////////////
            /// //DERECHO
            $heleft = 98;
            $pdf->SetXY($heleft, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');
            $pdf->SetXY($heleft + 45, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');

            $pdf->SetXY($heleft, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Orden de Compra N°:'), 0, 0, 'R');
            $pdf->SetXY($heleft + 45, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['purchase_order']), 0, 0, 'L');



            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(85, 68);
            $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


            $y = $pdf->GetY() - 11;


            $pdf->Rect(5, $y + 20, 200, 160, "D");

            $Yheader = 22;

            $pdf->SetFont("Arial", "", 8);

            $pdf->SetXY(8, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
            $pdf->Line(24, $y + 20, 24, $y + 172);

            $pdf->SetXY(12, $y + $Yheader);
            $pdf->Cell(70, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
            $pdf->Line(75, $y + 20, 75, $y + 172);

            $pdf->SetXY(65, $y + $Yheader);
            $pdf->Cell(55, 8, utf8_decode('UND COMP.'), 0, 0, 'C');
            $pdf->Line(115, $y + 20, 115, $y + 172);

            $pdf->SetXY(115, $y + $Yheader);
            $pdf->Cell(35, 8, utf8_decode('UND SOLIC.'), 0, 0, 'C');
            $pdf->Line(147, $y + 20, 147, $y + 172);

            $pdf->SetXY(137, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

            $pdf->Line(170, $y + 20, 170, $y + 180);


            $pdf->SetXY(165, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
            $y += 32;
            for ($i = 0; $i < 20; $i++) {
                if (isset($details[$i])) {
                    $pdf->SetXY(5, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->product_id, 0, 'L');


                    if (strlen($details[$i]->name) >= 90) {
                        $pdf->SetXY(46, $y + 1);
                        $pdf->MultiCell(50, 3, $details[$i]->name, 0, 'L');
                    } else {
                        $pdf->SetXY(24, $y);
                        $pdf->MultiCell(50, 8, $details[$i]->name, 0, 'L');
                    }
                    $pdf->SetXY(90, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->units_buy, 0, 'L');

                    $pdf->SetXY(127, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->units_request, 0, 'L');

                    $pdf->SetXY(112, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($details[$i]->price, $this->decimalPdf), 0, 'R');

                    $total_line = ($details[$i]->units_request) * ($details[$i]->price);

                    $pdf->SetXY(145, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($total_line, $this->decimalPdf), 0, 'R');

                    $TOTAL += $total_line;
                }
                $pdf->Line(5, $y, 205, $y);

                $y += 7;
            }
        } else {

            $numrows = count($details);
            $ln = ($numrows / $totalRowPage);

            if (is_float($ln)) {
                $numpages = intval(($numrows / $totalRowPage)) + 1;
            } else {
                $numpages = intval(($numrows / $totalRowPage));
            }
            $lasti = 0;
            for ($i = 1; $i <= $numpages; $i++) {
                $largeRect = 207;
                $lineVertical = 227;
                $lineVerticalTotal = 227;


                $checkiflast = ($numrows - ($lasti + 1));
                if ($checkiflast <= $totalRowPage) {
                    $nrow = $totalRowPage;
                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $numpages -= 1;
                } else {
                    $nrow = 27;
                }
                if ($i == $numpages) {

                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $nrow = 20;
                }
                $pdf->AddPage();


                $pdf->SetLineWidth(0.1);
                $pdf->SetFillColor(192);

                $pdf->Image($this->getLogov1(), 95, 4, 18);

                //$pdf->Rect(120, 15, 85, 8, "DF");


                $pdf->SetFont("Arial", "B", 8);
                $pdf->SetXY(165, 10);
                $pdf->AliasNbPages();
                $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
                $pdf->SetXY(80, 24);
                $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
                $pdf->SetXY(80, 28);
                $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
                $pdf->SetXY(80, 32);
                $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE CAFETERIA'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(80, 38);
                $pdf->Cell(45, 8, utf8_decode('PEDIDOS'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 12);
                $pdf->SetXY(120, 38);
                $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

                $pdf->SetXY(145, 38);
                $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

                $hei1 = 9;

                //IZQUIERO
                $pdf->SetFont("Arial", "", 11);
                $pdf->SetXY(10, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Señor:'), 0, 0, 'L');

                $pdf->SetXY(25, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['provider']), 0, 0, 'L');


                $pdf->SetXY(10, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');

                $pdf->SetXY(28, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['telephone1']), 0, 0, 'L');

                $pdf->SetXY(10, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('R.U.C:'), 0, 0, 'L');

                $pdf->SetXY(25, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['ruc']), 0, 0, 'L');

                $pdf->SetXY(10, 70 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('D.V:'), 0, 0, 'L');
                $pdf->SetXY(25, 70 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['dv']), 0, 0, 'L');

                ///////////////////////////////
                /// //DERECHO
                $heleft = 98;
                $pdf->SetXY($heleft, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');
                $pdf->SetXY($heleft + 45, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');

                $pdf->SetXY($heleft, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Orden de Compra N°:'), 0, 0, 'R');
                $pdf->SetXY($heleft + 45, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['purchase_order']), 0, 0, 'L');



                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(85, 68);
                $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


                $y = $pdf->GetY() - 11;


                $pdf->Rect(5, $y + 20, 200, 160, "D");

                $Yheader = 22;

                $pdf->SetFont("Arial", "", 8);

                $pdf->SetXY(8, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
                $pdf->Line(24, $y + 20, 24, $y + 172);

                $pdf->SetXY(12, $y + $Yheader);
                $pdf->Cell(70, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
                $pdf->Line(75, $y + 20, 75, $y + 172);

                $pdf->SetXY(65, $y + $Yheader);
                $pdf->Cell(55, 8, utf8_decode('UND COMP.'), 0, 0, 'C');
                $pdf->Line(115, $y + 20, 115, $y + 172);

                $pdf->SetXY(115, $y + $Yheader);
                $pdf->Cell(35, 8, utf8_decode('UND SOLIC.'), 0, 0, 'C');
                $pdf->Line(147, $y + 20, 147, $y + 172);

                $pdf->SetXY(137, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

                $pdf->Line(170, $y + 20, 170, $y + 180);


                $pdf->SetXY(165, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
                $y += 32;

                for ($j = 0; $j < $nrow; $j++) {

                    if (isset($details[$lasti])) {
                        $pdf->SetXY(5, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->product_id, 0, 'L');


                        if (strlen($details[$lasti]->name) >= 90) {
                            $pdf->SetXY(46, $y + 1);
                            $pdf->MultiCell(50, 3, $details[$lasti]->name, 0, 'L');
                        } else {
                            $pdf->SetXY(24, $y);
                            $pdf->MultiCell(50, 8, $details[$lasti]->name, 0, 'L');
                        }
                        $pdf->SetXY(90, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->units_buy, 0, 'L');

                        $pdf->SetXY(127, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->units_request, 0, 'L');

                        $pdf->SetXY(112, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($details[$lasti]->price, $this->decimalPdf), 0, 'R');

                        $total_line = ($details[$lasti]->units_request) * ($details[$lasti]->price);

                        $pdf->SetXY(145, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($total_line, $this->decimalPdf), 0, 'R');

                        $TOTAL += $total_line;
                        $lasti++;
                    }
                    $pdf->Line(5, $y, 205, $y);

                    $y += 7;
                }
            }
        }

        $pdf->Line(5, $y, 205, $y);
        /**
         * TOTAL DE LA TABLA
         */
        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetXY(137, $y);
        $pdf->Cell(45, 8, utf8_decode('TOTAL '), 0, 0, 'C');
        $pdf->SetXY(158, $y);
        $pdf->Cell(45, 8, '$' . number_format($TOTAL, $this->decimalPdf), 0, 0, 'R');





        $pdf->SetXY(10, $y + 47);
        $pdf->Cell(45, 8, utf8_decode('Observaciones:'), 0, 0, 'L');

        $pdf->Line(32, $y + 52, 205, $y + 52);

        $pdf->SetXY(10, $y + 56);

        $pdf->Cell(45, 8, utf8_decode('Recibido por:'), 0, 0, 'L');
        $pdf->Line(30, $y + 61, 100, $y + 61);

        $pdf->SetXY(82, $y + 56);
        $pdf->Cell(40, 8, utf8_decode('Revisado por:'), 0, 0, 'R');
        $pdf->Line(122, $y + 61, 205, $y + 61);


        $pdf->Output("I", "file.pdf");
    }

    public function generatePdfBills($id)
    {

        $pdf = new FPDF('P', 'mm', "A4");
        $pdf->SetAutoPagebreak(False);
        $pdf->SetMargins(0, 0, 0);

        $sql1 = "SELECT  DATE_FORMAT(t1.date,'%Y-%m-%d') as date,t1.reference,t2.`name` AS customer,t2.telephone1,t1.credit_term,t1.order_id as orders  from bills t1 join customers t2 WHERE t1.id='$id' ";
        $maindata = $this->consulQuery($sql1);

        /**
         * DETALLE
         * *************************************************************
         */

        //$y += 32; // colocar para el detalle

        $totalRowPage = 20;
        $TOTAL = 0;
        $sql2 = "SELECT t1.product_id,t2.name,t1.costs,t1.units,t2.price FROM  bills_details t1 JOIN products t2 ON t1.product_id = t2.id WHERE t1.bill='$id' ";
        $details = $this->consultListQuery($sql2);


        if (count($details) <= $totalRowPage) {

            $pdf->AddPage();


            $pdf->SetLineWidth(0.1);
            $pdf->SetFillColor(192);

            $pdf->Image($this->getLogov1(), 95, 4, 18);

            //$pdf->Rect(120, 15, 85, 8, "DF");


            $pdf->SetFont("Arial", "B", 8);
            $pdf->SetXY(165, 10);
            $pdf->AliasNbPages();
            $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
            $pdf->SetXY(80, 24);
            $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
            $pdf->SetXY(80, 28);
            $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
            $pdf->SetXY(80, 32);
            $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE COMPRAS'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(80, 38);
            $pdf->Cell(45, 8, utf8_decode('FACTURA'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(120, 38);
            $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

            $pdf->SetXY(145, 38);
            $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

            $hei1 = 9;

            //IZQUIERO
            $pdf->SetFont("Arial", "", 11);
            $pdf->SetXY(10, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Cliente:'), 0, 0, 'L');

            $pdf->SetXY(25, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['customer']), 0, 0, 'L');


            $pdf->SetXY(10, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');

            $pdf->SetXY(28, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['telephone1']), 0, 0, 'L');

            $pdf->SetXY(10, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Tipo:'), 0, 0, 'L');

            $pdf->SetXY(22, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['credit_term']), 0, 0, 'L');


            ///////////////////////////////
            /// //DERECHO
            $heleft = 98;
            $pdf->SetXY($heleft, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');
            $pdf->SetXY($heleft + 45, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');


            $pdf->SetXY($heleft, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Pedido N°:'), 0, 0, 'R');

            $pdf->SetXY($heleft + 45, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['orders']), 0, 0, 'L');


            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(85, 73);
            $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


            $y = $pdf->GetY() - 11;


            $pdf->Rect(5, $y + 20, 200, 160, "D");

            $Yheader = 22;

            $pdf->SetFont("Arial", "", 8);

            $pdf->SetXY(8, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
            $pdf->Line(24, $y + 20, 24, $y + 172);


            $pdf->SetXY(65, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
            $pdf->Line(135, $y + 20, 135, $y + 172);

            $pdf->SetXY(115, $y + $Yheader);
            $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
            $pdf->Line(147, $y + 20, 147, $y + 172);

            $pdf->SetXY(137, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

            $pdf->Line(170, $y + 20, 170, $y + 180);


            $pdf->SetXY(165, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
            $y += 32;
            for ($i = 0; $i < 20; $i++) {
                if (isset($details[$i])) {
                    $pdf->SetXY(5, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->product_id, 0, 'L');

                    if (strlen($details[$i]->name) >= 90) {
                        $pdf->SetXY(46, $y + 1);
                        $pdf->MultiCell(90, 3, utf8_decode($details[$i]->name), 0, 'L');
                    } else {
                        $pdf->SetXY(46, $y + 3);
                        $pdf->MultiCell(90, 2, utf8_decode($details[$i]->name), 0, 'L');
                    }

                    $pdf->SetXY(137, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->units, 0, 'L');

                    $pdf->SetXY(120, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($details[$i]->costs, $this->decimalPdf), 0, 'R');

                    $total_line = ($details[$i]->costs) * ($details[$i]->units);

                    $pdf->SetXY(152, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($total_line, $this->decimalPdf), 0, 'R');
                    $TOTAL += $total_line;
                }
                $pdf->Line(5, $y, 205, $y);

                $y += 7;
            }
        } else {

            $numrows = count($details);
            $ln = ($numrows / $totalRowPage);

            if (is_float($ln)) {
                $numpages = intval(($numrows / $totalRowPage)) + 1;
            } else {
                $numpages = intval(($numrows / $totalRowPage));
            }
            $lasti = 0;
            for ($i = 1; $i <= $numpages; $i++) {
                $largeRect = 207;
                $lineVertical = 227;
                $lineVerticalTotal = 227;


                $checkiflast = ($numrows - ($lasti + 1));
                if ($checkiflast <= $totalRowPage) {
                    $nrow = $totalRowPage;
                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $numpages -= 1;
                } else {
                    $nrow = 27;
                }
                if ($i == $numpages) {

                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $nrow = 20;
                }
                $pdf->AddPage();


                $pdf->SetLineWidth(0.1);
                $pdf->SetFillColor(192);

                $pdf->Image($this->getLogov1(), 95, 4, 18);

                //$pdf->Rect(120, 15, 85, 8, "DF");


                $pdf->SetFont("Arial", "B", 8);
                $pdf->SetXY(165, 10);
                $pdf->AliasNbPages();
                $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
                $pdf->SetXY(80, 24);
                $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
                $pdf->SetXY(80, 28);
                $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
                $pdf->SetXY(80, 32);
                $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE COMPRAS'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(80, 38);
                $pdf->Cell(45, 8, utf8_decode('FACTURA'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 12);
                $pdf->SetXY(120, 38);
                $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

                $pdf->SetXY(145, 38);
                $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

                $hei1 = 9;

                //IZQUIERO
                $pdf->SetFont("Arial", "", 11);
                $pdf->SetXY(10, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Cliente:'), 0, 0, 'L');

                $pdf->SetXY(25, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['customer']), 0, 0, 'L');


                $pdf->SetXY(10, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');

                $pdf->SetXY(28, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['telephone1']), 0, 0, 'L');


                $pdf->SetXY(10, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Tipo:'), 0, 0, 'L');
    
                $pdf->SetXY(22, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['credit_term']), 0, 0, 'L');
    
    
                ///////////////////////////////
                /// //DERECHO
                $heleft = 98;
                $pdf->SetXY($heleft, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');
                $pdf->SetXY($heleft + 45, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');


                $pdf->SetXY($heleft, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Pedido N°:'), 0, 0, 'R');

                $pdf->SetXY($heleft + 45, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['orders']), 0, 0, 'L');


                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(85, 73);
                $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


                $y = $pdf->GetY() - 11;


                $pdf->Rect(5, $y + 20, 200, 160, "D");

                $Yheader = 22;

                $pdf->SetFont("Arial", "", 8);

                $pdf->SetXY(8, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
                $pdf->Line(24, $y + 20, 24, $y + 172);


                $pdf->SetXY(65, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
                $pdf->Line(135, $y + 20, 135, $y + 172);

                $pdf->SetXY(115, $y + $Yheader);
                $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
                $pdf->Line(147, $y + 20, 147, $y + 172);

                $pdf->SetXY(137, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

                $pdf->Line(170, $y + 20, 170, $y + 180);


                $pdf->SetXY(165, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
                $y += 32;
                for ($j = 0; $j < $nrow; $j++) {

                    if (isset($details[$lasti])) {
                        $pdf->SetXY(5, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->product_id, 0, 'L');

                        if (strlen($details[$lasti]->name) >= 90) {
                            $pdf->SetXY(46, $y + 1);
                            $pdf->MultiCell(90, 3, utf8_decode($details[$lasti]->name), 0, 'L');
                        } else {
                            $pdf->SetXY(46, $y + 3);
                            $pdf->MultiCell(90, 2, utf8_decode($details[$lasti]->name), 0, 'L');
                        }

                        $pdf->SetXY(137, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->units, 0, 'L');

                        $pdf->SetXY(120, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($details[$lasti]->costs, $this->decimalPdf), 0, 'R');

                        $total_line = ($details[$lasti]->costs) * ($details[$lasti]->units);

                        $pdf->SetXY(152, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($total_line, $this->decimalPdf), 0, 'R');

                        $TOTAL += $total_line;
                        $lasti++;
                    }
                    $pdf->Line(5, $y, 205, $y);

                    $y += 7;
                }
            }
        }

        $pdf->Line(5, $y, 205, $y);
        /**
         * TOTAL DE LA TABLA
         */
        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetXY(137, $y);
        $pdf->Cell(45, 8, utf8_decode('TOTAL '), 0, 0, 'C');
        $pdf->SetXY(158, $y);
        $pdf->Cell(45, 8, '$' . number_format($TOTAL, $this->decimalPdf), 0, 0, 'R');


        $pdf->SetXY(10, $y + 47);
        $pdf->Cell(45, 8, utf8_decode('Observaciones:'), 0, 0, 'L');

        $pdf->Line(32, $y + 52, 205, $y + 52);

        $pdf->SetXY(10, $y + 56);

        $pdf->Cell(45, 8, utf8_decode('Recibido por:'), 0, 0, 'L');
        $pdf->Line(30, $y + 61, 100, $y + 61);

        $pdf->SetXY(82, $y + 56);
        $pdf->Cell(40, 8, utf8_decode('Revisado por:'), 0, 0, 'R');
        $pdf->Line(122, $y + 61, 205, $y + 61);


        $pdf->Output("I", "file.pdf");
    }

    public function generatePdfQuotes($id)
    {
        $pdf = new FPDF('P', 'mm', "A4");
        $pdf->SetAutoPagebreak(False);
        $pdf->SetMargins(0, 0, 0);

        $sql1 = "SELECT  DATE_FORMAT(t1.date,'%Y-%m-%d') as date,t1.reference,t2.`name` AS customer,t2.telephone1,DATE(t1.date_expire) AS date_expire ,t1.order_id as orders  from quotes t1 join customers t2 WHERE t1.id='$id' ";
        $maindata = $this->consulQuery($sql1);

        /**
         * DETALLE
         * *************************************************************
         */

        //$y += 32; // colocar para el detalle

        $totalRowPage = 20;
        $TOTAL = 0;
        $sql2 = "SELECT t1.product_id,t2.name,t1.costs,t1.units,t2.price FROM  quotes_details t1 JOIN products t2 ON t1.product_id = t2.id WHERE t1.quote='$id' ";
        $details = $this->consultListQuery($sql2);


        if (count($details) <= $totalRowPage) {

            $pdf->AddPage();


            $pdf->SetLineWidth(0.1);
            $pdf->SetFillColor(192);

            $pdf->Image($this->getLogov1(), 95, 4, 18);

            //$pdf->Rect(120, 15, 85, 8, "DF");


            $pdf->SetFont("Arial", "B", 8);
            $pdf->SetXY(165, 10);
            $pdf->AliasNbPages();
            $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
            $pdf->SetXY(80, 24);
            $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
            $pdf->SetXY(80, 28);
            $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
            $pdf->SetXY(80, 32);
            $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE COMPRAS'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(80, 38);
            $pdf->Cell(45, 8, utf8_decode('COTIZACIÓN'), 0, 0, 'C');

            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(120, 38);
            $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

            $pdf->SetXY(145, 38);
            $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

            $hei1 = 9;

            //IZQUIERO
            $pdf->SetFont("Arial", "", 11);
            $pdf->SetXY(10, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Cliente:'), 0, 0, 'L');

            $pdf->SetXY(25, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['customer']), 0, 0, 'L');


            $pdf->SetXY(10, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');

            $pdf->SetXY(28, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['telephone1']), 0, 0, 'L');

            $pdf->SetXY(10, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Valido:'), 0, 0, 'L');

            $pdf->SetXY(23, 65 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['date_expire']), 0, 0, 'L');



            ///////////////////////////////
            /// //DERECHO
            $heleft = 98;
            $pdf->SetXY($heleft, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');
            $pdf->SetXY($heleft + 45, 55 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');


            $pdf->SetXY($heleft, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode('Pedido N°:'), 0, 0, 'R');

            $pdf->SetXY($heleft + 45, 60 - $hei1);
            $pdf->Cell(45, 8, utf8_decode($maindata['orders']), 0, 0, 'L');


            $pdf->SetFont("Arial", "B", 13);
            $pdf->SetXY(85, 73);
            $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


            $y = $pdf->GetY() - 11;


            $pdf->Rect(5, $y + 20, 200, 160, "D");

            $Yheader = 22;

            $pdf->SetFont("Arial", "", 8);

            $pdf->SetXY(8, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
            $pdf->Line(24, $y + 20, 24, $y + 172);


            $pdf->SetXY(65, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
            $pdf->Line(135, $y + 20, 135, $y + 172);

            $pdf->SetXY(115, $y + $Yheader);
            $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
            $pdf->Line(147, $y + 20, 147, $y + 172);

            $pdf->SetXY(137, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

            $pdf->Line(170, $y + 20, 170, $y + 180);


            $pdf->SetXY(165, $y + $Yheader);
            $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
            $y += 32;
            for ($i = 0; $i < 20; $i++) {
                if (isset($details[$i])) {
                    $pdf->SetXY(5, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->product_id, 0, 'L');

                    if (strlen($details[$i]->name) >= 90) {
                        $pdf->SetXY(46, $y + 1);
                        $pdf->MultiCell(90, 3, utf8_decode($details[$i]->name), 0, 'L');
                    } else {
                        $pdf->SetXY(46, $y + 3);
                        $pdf->MultiCell(90, 2, utf8_decode($details[$i]->name), 0, 'L');
                    }

                    $pdf->SetXY(137, $y);
                    $pdf->MultiCell(50, 8, $details[$i]->units, 0, 'L');

                    $pdf->SetXY(120, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($details[$i]->costs, $this->decimalPdf), 0, 'R');

                    $total_line = ($details[$i]->costs) * ($details[$i]->units);

                    $pdf->SetXY(152, $y);
                    $pdf->MultiCell(50, 8, "$" . number_format($total_line, $this->decimalPdf), 0, 'R');
                    $TOTAL += $total_line;
                }
                $pdf->Line(5, $y, 205, $y);

                $y += 7;
            }
        } else {

            $numrows = count($details);
            $ln = ($numrows / $totalRowPage);

            if (is_float($ln)) {
                $numpages = intval(($numrows / $totalRowPage)) + 1;
            } else {
                $numpages = intval(($numrows / $totalRowPage));
            }
            $lasti = 0;
            for ($i = 1; $i <= $numpages; $i++) {
                $largeRect = 207;
                $lineVertical = 227;
                $lineVerticalTotal = 227;


                $checkiflast = ($numrows - ($lasti + 1));
                if ($checkiflast <= $totalRowPage) {
                    $nrow = $totalRowPage;
                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $numpages -= 1;
                } else {
                    $nrow = 27;
                }
                if ($i == $numpages) {

                    $largeRect = 160;
                    $lineVertical = 172;
                    $lineVerticalTotal = 180;
                    $nrow = 20;
                }
                $pdf->AddPage();


                $pdf->SetLineWidth(0.1);
                $pdf->SetFillColor(192);

                $pdf->Image($this->getLogov1(), 95, 4, 18);

                //$pdf->Rect(120, 15, 85, 8, "DF");


                $pdf->SetFont("Arial", "B", 8);
                $pdf->SetXY(165, 10);
                $pdf->AliasNbPages();
                $pdf->Cell(45, 8, $pdf->PageNo() . '/{nb}', 0, 0, 'R');
                $pdf->SetXY(80, 24);
                $pdf->Cell(45, 8, utf8_decode('UNIVERSIDAD DE PANAMÁ'), 0, 0, 'C');
                $pdf->SetXY(80, 28);
                $pdf->Cell(45, 8, utf8_decode('CENTRO REGIONAL UNIVERSITARIO DE VERAGUAS'), 0, 0, 'C');
                $pdf->SetXY(80, 32);
                $pdf->Cell(45, 8, utf8_decode('DEPARTAMENTO DE COMPRAS'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(80, 38);
                $pdf->Cell(45, 8, utf8_decode('COTIZACIÓN'), 0, 0, 'C');

                $pdf->SetFont("Arial", "B", 12);
                $pdf->SetXY(120, 38);
                $pdf->Cell(45, 8, utf8_decode('Nº'), 0, 0, 'C');

                $pdf->SetXY(145, 38);
                $pdf->Cell(45, 8, utf8_decode($id), 0, 0, 'L');

                $hei1 = 9;

                //IZQUIERO
                $pdf->SetFont("Arial", "", 11);
                $pdf->SetXY(10, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Cliente:'), 0, 0, 'L');

                $pdf->SetXY(25, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['customer']), 0, 0, 'L');


                $pdf->SetXY(10, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Telefono:'), 0, 0, 'L');

                $pdf->SetXY(28, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['telephone1']), 0, 0, 'L');

                $pdf->SetXY(10, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Valido:'), 0, 0, 'L');
    
                $pdf->SetXY(23, 65 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['date_expire']), 0, 0, 'L');
    
    

                ///////////////////////////////
                /// //DERECHO
                $heleft = 98;
                $pdf->SetXY($heleft, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Fecha:'), 0, 0, 'R');
                $pdf->SetXY($heleft + 45, 55 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['date']), 0, 0, 'L');


                $pdf->SetXY($heleft, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode('Pedido N°:'), 0, 0, 'R');

                $pdf->SetXY($heleft + 45, 60 - $hei1);
                $pdf->Cell(45, 8, utf8_decode($maindata['orders']), 0, 0, 'L');


                $pdf->SetFont("Arial", "B", 13);
                $pdf->SetXY(85, 73);
                $pdf->Cell(45, 8, utf8_decode('CAFETERÍA'), 0, 0, 'C');


                $y = $pdf->GetY() - 11;


                $pdf->Rect(5, $y + 20, 200, 160, "D");

                $Yheader = 22;

                $pdf->SetFont("Arial", "", 8);

                $pdf->SetXY(8, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('CODIGO'), 0, 0, 'L');
                $pdf->Line(24, $y + 20, 24, $y + 172);


                $pdf->SetXY(65, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
                $pdf->Line(135, $y + 20, 135, $y + 172);

                $pdf->SetXY(115, $y + $Yheader);
                $pdf->Cell(52, 8, utf8_decode('CANT.'), 0, 0, 'C');
                $pdf->Line(147, $y + 20, 147, $y + 172);

                $pdf->SetXY(137, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('PRECIO UNIT.'), 0, 0, 'C');

                $pdf->Line(170, $y + 20, 170, $y + 180);


                $pdf->SetXY(165, $y + $Yheader);
                $pdf->Cell(45, 8, utf8_decode('TOTAL'), 0, 0, 'C');
                $y += 32;
                for ($j = 0; $j < $nrow; $j++) {

                    if (isset($details[$lasti])) {
                        $pdf->SetXY(5, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->product_id, 0, 'L');

                        if (strlen($details[$lasti]->name) >= 90) {
                            $pdf->SetXY(46, $y + 1);
                            $pdf->MultiCell(90, 3, utf8_decode($details[$lasti]->name), 0, 'L');
                        } else {
                            $pdf->SetXY(46, $y + 3);
                            $pdf->MultiCell(90, 2, utf8_decode($details[$lasti]->name), 0, 'L');
                        }

                        $pdf->SetXY(137, $y);
                        $pdf->MultiCell(50, 8, $details[$lasti]->units, 0, 'L');

                        $pdf->SetXY(120, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($details[$lasti]->costs, $this->decimalPdf), 0, 'R');

                        $total_line = ($details[$lasti]->costs) * ($details[$lasti]->units);

                        $pdf->SetXY(152, $y);
                        $pdf->MultiCell(50, 8, "$" . number_format($total_line, $this->decimalPdf), 0, 'R');

                        $TOTAL += $total_line;
                        $lasti++;
                    }
                    $pdf->Line(5, $y, 205, $y);

                    $y += 7;
                }
            }
        }

        $pdf->Line(5, $y, 205, $y);
        /**
         * TOTAL DE LA TABLA
         */
        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetXY(137, $y);
        $pdf->Cell(45, 8, utf8_decode('TOTAL '), 0, 0, 'C');
        $pdf->SetXY(158, $y);
        $pdf->Cell(45, 8, '$' . number_format($TOTAL, $this->decimalPdf), 0, 0, 'R');


        $pdf->SetXY(10, $y + 47);
        $pdf->Cell(45, 8, utf8_decode('Observaciones:'), 0, 0, 'L');

        $pdf->Line(32, $y + 52, 205, $y + 52);

        $pdf->SetXY(10, $y + 56);

        $pdf->Cell(45, 8, utf8_decode('Recibido por:'), 0, 0, 'L');
        $pdf->Line(30, $y + 61, 100, $y + 61);

        $pdf->SetXY(82, $y + 56);
        $pdf->Cell(40, 8, utf8_decode('Revisado por:'), 0, 0, 'R');
        $pdf->Line(122, $y + 61, 205, $y + 61);


        $pdf->Output("I", "file.pdf");
    }

    function centimos()
    {

        $this->importe_parcial_n = number_format($this->importe_parcial_n, 2, ".", "") * 100;

        if ($this->importe_parcial_n > 0)
            $num_letra = " con " . $this->decena_centimos($this->importe_parcial_n);
        else
            $num_letra = "";

        return $num_letra;
    }

    function unidad_centimos($numero)
    {
        switch ($numero) {
            case 9: {
                    $num_letra = "nueve céntimos";
                    break;
                }
            case 8: {
                    $num_letra = "ocho céntimos";
                    break;
                }
            case 7: {
                    $num_letra = "siete céntimos";
                    break;
                }
            case 6: {
                    $num_letra = "seis céntimos";
                    break;
                }
            case 5: {
                    $num_letra = "cinco céntimos";
                    break;
                }
            case 4: {
                    $num_letra = "cuatro céntimos";
                    break;
                }
            case 3: {
                    $num_letra = "tres céntimos";
                    break;
                }
            case 2: {
                    $num_letra = "dos céntimos";
                    break;
                }
            case 1: {
                    $num_letra = "un céntimo";
                    break;
                }
        }
        return $num_letra;
    }

    function decena_centimos($numero)
    {
        if ($numero >= 10) {
            if ($numero >= 90 && $numero <= 99) {
                if ($numero == 90)
                    return "noventa céntimos";
                else if ($numero == 91)
                    return "noventa y un céntimos";
                else
                    return "noventa y " . $this->unidad_centimos($numero - 90);
            }
            if ($numero >= 80 && $numero <= 89) {
                if ($numero == 80)
                    return "ochenta céntimos";
                else if ($numero == 81)
                    return "ochenta y un céntimos";
                else
                    return "ochenta y " . $this->unidad_centimos($numero - 80);
            }
            if ($numero >= 70 && $numero <= 79) {
                if ($numero == 70)
                    return "setenta céntimos";
                else if ($numero == 71)
                    return "setenta y un céntimos";
                else
                    return "setenta y " . $this->unidad_centimos($numero - 70);
            }
            if ($numero >= 60 && $numero <= 69) {
                if ($numero == 60)
                    return "sesenta céntimos";
                else if ($numero == 61)
                    return "sesenta y un céntimos";
                else
                    return "sesenta y " . $this->unidad_centimos($numero - 60);
            }
            if ($numero >= 50 && $numero <= 59) {
                if ($numero == 50)
                    return "cincuenta céntimos";
                else if ($numero == 51)
                    return "cincuenta y un céntimos";
                else
                    return "cincuenta y " . $this->unidad_centimos($numero - 50);
            }
            if ($numero >= 40 && $numero <= 49) {
                if ($numero == 40)
                    return "cuarenta céntimos";
                else if ($numero == 41)
                    return "cuarenta y un céntimos";
                else
                    return "cuarenta y " . $this->unidad_centimos($numero - 40);
            }
            if ($numero >= 30 && $numero <= 39) {
                if ($numero == 30)
                    return "treinta céntimos";
                else if ($numero == 91)
                    return "treinta y un céntimos";
                else
                    return "treinta y " . $this->unidad_centimos($numero - 30);
            }
            if ($numero >= 20 && $numero <= 29) {
                if ($numero == 20)
                    return "veinte céntimos";
                else if ($numero == 21)
                    return "veintiun céntimos";
                else
                    return "veinti" . $this->unidad_centimos($numero - 20);
            }
            if ($numero >= 10 && $numero <= 19) {
                if ($numero == 10)
                    return "diez céntimos";
                else if ($numero == 11)
                    return "once céntimos";
                else if ($numero == 11)
                    return "doce céntimos";
                else if ($numero == 11)
                    return "trece céntimos";
                else if ($numero == 11)
                    return "catorce céntimos";
                else if ($numero == 11)
                    return "quince céntimos";
                else if ($numero == 11)
                    return "dieciseis céntimos";
                else if ($numero == 11)
                    return "diecisiete céntimos";
                else if ($numero == 11)
                    return "dieciocho céntimos";
                else if ($numero == 11)
                    return "diecinueve céntimos";
            }
        } else
            return $this->unidad_centimos($numero);
    }

    function unidad($numero)
    {
        switch ($numero) {
            case 9: {
                    $num = "nueve";
                    break;
                }
            case 8: {
                    $num = "ocho";
                    break;
                }
            case 7: {
                    $num = "siete";
                    break;
                }
            case 6: {
                    $num = "seis";
                    break;
                }
            case 5: {
                    $num = "cinco";
                    break;
                }
            case 4: {
                    $num = "cuatro";
                    break;
                }
            case 3: {
                    $num = "tres";
                    break;
                }
            case 2: {
                    $num = "dos";
                    break;
                }
            case 1: {
                    $num = "uno";
                    break;
                }
        }
        return $num;
    }

    function decena($numero)
    {
        if ($numero >= 90 && $numero <= 99) {
            $num_letra = "noventa ";

            if ($numero > 90)
                $num_letra = $num_letra . "y " . $this->unidad($numero - 90);
        } else if ($numero >= 80 && $numero <= 89) {
            $num_letra = "ochenta ";

            if ($numero > 80)
                $num_letra = $num_letra . "y " . $this->unidad($numero - 80);
        } else if ($numero >= 70 && $numero <= 79) {
            $num_letra = "setenta ";

            if ($numero > 70)
                $num_letra = $num_letra . "y " . $this->unidad($numero - 70);
        } else if ($numero >= 60 && $numero <= 69) {
            $num_letra = "sesenta ";

            if ($numero > 60)
                $num_letra = $num_letra . "y " . $this->unidad($numero - 60);
        } else if ($numero >= 50 && $numero <= 59) {
            $num_letra = "cincuenta ";

            if ($numero > 50)
                $num_letra = $num_letra . "y " . $this->unidad($numero - 50);
        } else if ($numero >= 40 && $numero <= 49) {
            $num_letra = "cuarenta ";

            if ($numero > 40)
                $num_letra = $num_letra . "y " . $this->unidad($numero - 40);
        } else if ($numero >= 30 && $numero <= 39) {
            $num_letra = "treinta ";

            if ($numero > 30)
                $num_letra = $num_letra . "y " . $this->unidad($numero - 30);
        } else if ($numero >= 20 && $numero <= 29) {
            if ($numero == 20)
                $num_letra = "veinte ";
            else
                $num_letra = "veinti" . $this->unidad($numero - 20);
        } else if ($numero >= 10 && $numero <= 19) {
            switch ($numero) {
                case 10: {
                        $num_letra = "diez ";
                        break;
                    }
                case 11: {
                        $num_letra = "once ";
                        break;
                    }
                case 12: {
                        $num_letra = "doce ";
                        break;
                    }
                case 13: {
                        $num_letra = "trece ";
                        break;
                    }
                case 14: {
                        $num_letra = "catorce ";
                        break;
                    }
                case 15: {
                        $num_letra = "quince ";
                        break;
                    }
                case 16: {
                        $num_letra = "dieciseis ";
                        break;
                    }
                case 17: {
                        $num_letra = "diecisiete ";
                        break;
                    }
                case 18: {
                        $num_letra = "dieciocho ";
                        break;
                    }
                case 19: {
                        $num_letra = "diecinueve ";
                        break;
                    }
            }
        } else
            $num_letra = $this->unidad($numero);

        return $num_letra;
    }

    function centena($numero)
    {
        if ($numero >= 100) {
            if ($numero >= 900 & $numero <= 999) {
                $num_letra = "novecientos ";

                if ($numero > 900)
                    $num_letra = $num_letra . $this->decena($numero - 900);
            } else if ($numero >= 800 && $numero <= 899) {
                $num_letra = "ochocientos ";

                if ($numero > 800)
                    $num_letra = $num_letra . $this->decena($numero - 800);
            } else if ($numero >= 700 && $numero <= 799) {
                $num_letra = "setecientos ";

                if ($numero > 700)
                    $num_letra = $num_letra . $this->decena($numero - 700);
            } else if ($numero >= 600 && $numero <= 699) {
                $num_letra = "seiscientos ";

                if ($numero > 600)
                    $num_letra = $num_letra . $this->decena($numero - 600);
            } else if ($numero >= 500 && $numero <= 599) {
                $num_letra = "quinientos ";

                if ($numero > 500)
                    $num_letra = $num_letra . $this->decena($numero - 500);
            } else if ($numero >= 400 && $numero <= 499) {
                $num_letra = "cuatrocientos ";

                if ($numero > 400)
                    $num_letra = $num_letra . $this->decena($numero - 400);
            } else if ($numero >= 300 && $numero <= 399) {
                $num_letra = "trescientos ";

                if ($numero > 300)
                    $num_letra = $num_letra . $this->decena($numero - 300);
            } else if ($numero >= 200 && $numero <= 299) {
                $num_letra = "doscientos ";

                if ($numero > 200)
                    $num_letra = $num_letra . $this->decena($numero - 200);
            } else if ($numero >= 100 && $numero <= 199) {
                if ($numero == 100)
                    $num_letra = "cien ";
                else
                    $num_letra = "ciento " . $this->decena($numero - 100);
            }
        } else
            $num_letra = $this->decena($numero);

        return $num_letra;
    }

    function cien()
    {


        $parcial = 0;
        $car = 0;

        while (substr($this->importe_parcial_n, 0, 1) == 0)
            $this->importe_parcial_n = substr($this->importe_parcial_n, 1, strlen($this->importe_parcial_n) -
                1);

        if ($this->importe_parcial_n >= 1 && $this->importe_parcial_n <= 9.99)
            $car = 1;
        else if ($this->importe_parcial_n >= 10 && $this->importe_parcial_n <= 99.99)
            $car = 2;
        else if ($this->importe_parcial_n >= 100 && $this->importe_parcial_n <= 999.99)
            $car = 3;

        $parcial = substr($this->importe_parcial_n, 0, $car);
        $this->importe_parcial_n = substr($this->importe_parcial_n, $car);

        $num_letra = $this->centena($parcial) . $this->centimos();

        return $num_letra;
    }

    function cien_mil()
    {


        $parcial = 0;
        $car = 0;

        while (substr($this->importe_parcial_n, 0, 1) == 0)
            $this->importe_parcial_n = substr($this->importe_parcial_n, 1, strlen($this->importe_parcial_n) -
                1);

        if ($this->importe_parcial_n >= 1000 && $this->importe_parcial_n <= 9999.99)
            $car = 1;
        else if ($this->importe_parcial_n >= 10000 && $this->importe_parcial_n <= 99999.99)
            $car = 2;
        else if ($this->importe_parcial_n >= 100000 && $this->importe_parcial_n <= 999999.99)
            $car = 3;

        $parcial = substr($this->importe_parcial_n, 0, $car);
        $this->$this->importe_parcial_n = substr($this->importe_parcial_n, $car);

        if ($parcial > 0) {
            if ($parcial == 1)
                $num_letra = "mil ";
            else
                $num_letra = $this->centena($parcial) . " mil ";
        }

        return $num_letra;
    }

    function millon()
    {


        $parcial = 0;
        $car = 0;

        while (substr($this->importe_parcial_n, 0, 1) == 0)
            $this->importe_parcial_n = substr($this->importe_parcial_n, 1, strlen($this->importe_parcial_n) -
                1);

        if ($this->importe_parcial_n >= 1000000 && $this->importe_parcial_n <= 9999999.99)
            $car = 1;
        else if ($this->importe_parcial_n >= 10000000 && $this->importe_parcial_n <= 99999999.99)
            $car = 2;
        else if ($this->importe_parcial_n >= 100000000 && $this->importe_parcial_n <= 999999999.99)
            $car = 3;

        $parcial = substr($this->importe_parcial_n, 0, $car);
        $this->importe_parcial_n = substr($this->importe_parcial_n, $car);

        if ($parcial == 1)
            $num_letras = "un millón ";
        else
            $num_letras = $this->centena($parcial) . " millones ";

        return $num_letras;
    }

    function convertir_a_letras($numero)
    {


        $this->importe_parcial_n = $numero;

        if ($numero < 1000000000) {
            if ($numero >= 1000000 && $numero <= 999999999.99)
                $num_letras = $this->millon() . $this->cien_mil() . $this->cien();
            else if ($numero >= 1000 && $numero <= 999999.99)
                $num_letras = $this->cien_mil() . $this->cien();
            else if ($numero >= 1 && $numero <= 999.99)
                $num_letras = $this->cien();
            else if ($numero >= 0.01 && $numero <= 0.99) {
                if ($numero == 0.01)
                    $num_letras = "un céntimo";
                else
                    $num_letras = $this->convertir_a_letras(($numero * 100) . "/100") . " céntimos";
            }
        }
        return $num_letras;
    }
}//fin de la clase
