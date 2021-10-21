<?php

$VAR_SESSION = Session::getInstance();
if ($VAR_SESSION->username == "" || $VAR_SESSION->loggedin != true) {

    header("Location:./");
}


error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Panama');
?>