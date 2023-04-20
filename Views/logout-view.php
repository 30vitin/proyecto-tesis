<?php
date_default_timezone_set('America/Panama');
header('Content-type: text/html; charset=UTF-8');


require_once 'Config/Functions.php';

$cls = new Functions;  //llamando al objeto

$VAR_SESSION = Session::getInstance();

$VAR_SESSION->username = "";
$VAR_SESSION->email = "";
$VAR_SESSION->loggedin = false;

$VAR_SESSION->destroy();

header('location:./');
