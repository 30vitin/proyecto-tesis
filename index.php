<?php


define("ROOT", dirname(__FILE__));

error_reporting(E_ALL);
ini_set('display_errors', '1');


include 'Config/Autoload.php';


$lb = new Lb();
$lb->start();
?>
