<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
include 'utils.php';


if (!isset($_GET['id'])) {
    header("Location:javascript:window.history.go(-2);");
}
if (!isset($_GET['section'])) {
    header("Location:javascript:window.history.go(-2);");
}

$id = $_GET['id'];
$section = $_GET['section'];
$check = true;

if($section =='purchase_request')
{
    $sql="SELECT COUNT(*) as count FROM purchase_requests WHERE id='$id' AND status ='APROBADA' OR status='CERRADO' ";
    $res = $cls->consulQuery($sql);
    if($res['count'] == 0){
        $check = false;
    }else{
        $cls->generatePdfPurchaseRequest($id);
    }


}
if($section =='purchase_order')
{
    $sql="SELECT COUNT(*) as count FROM purchase_orders WHERE id='$id' AND status ='APROBADA' OR status='CERRADO' ";
    $res = $cls->consulQuery($sql);
   
    if($res['count'] == 0){
        $check = false;
    }else{
        $cls->generatePdfPurchaseOrder($id);
    }
}

if($section =='receive_merchant')
{
    $sql="SELECT COUNT(*) as count FROM received_merchant WHERE id='$id' AND status ='APROBADA' OR status='CERRADO' ";
    $res = $cls->consulQuery($sql);
    if($res['count'] == 0){
        $check = false;
    }else{
        $cls->generatePdfReceiveMerchant($id);
    }
}

if($section =='dispatch_merchant')
{
    $sql="SELECT COUNT(*) as count FROM dispatch_merchant WHERE id='$id' AND status ='APROBADA' OR status='CERRADO' ";
    $res = $cls->consulQuery($sql);
    if($res['count'] == 0){
        $check = false;
    }else{
        $cls->generatePdfDispatchMerchant($id);
    }
}

if($section =='orders')
{
    $sql="SELECT COUNT(*) as count FROM orders WHERE id='$id' AND status ='APROBADA' OR status='CERRADO' ";
    $res = $cls->consulQuery($sql);
    if($res['count'] == 0){
        $check = false;
    }else{
        $cls->generatePdfOrders($id);
    }
}

if($section =='bills')
{
    $sql="SELECT COUNT(*) as count FROM bills WHERE id='$id' AND status ='APROBADA' OR status='CERRADO' ";
    $res = $cls->consulQuery($sql);
    if($res['count'] == 0){
        $check = false;
    }else{
        $cls->generatePdfBills($id);
    }

}
if($section =='quotes')
{
    $sql="SELECT COUNT(*) as count FROM quotes WHERE id='$id' AND status ='APROBADA' OR status='CERRADO' ";
    $res = $cls->consulQuery($sql);
    if($res['count'] == 0){
        $check = false;
    }else{
        $cls->generatePdfQuotes($id);
    }

}

if(!$check){
    header('location:?view=nopermission');
}
