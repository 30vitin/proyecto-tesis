<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require("vars.php");


class dba{

      public $servname;
      public $username;
      private static $_instance; //The single instance

      public $password;
      public $dbaname;
      public $conn;


      public static function getInstance() {
      		if(!self::$_instance) { // If no instance then make one
      			self::$_instance = new self();
      		}
      		return self::$_instance;
      	}

      public function __construct() {
        $this->servname=DB_HOST;
        $this->username=DB_USERNAME;
        $this->password=DB_PASSWORD;
        $this->dbaname=DB_DATABASE;

        $this->conn = new mysqli($this->servname,$this->username,$this->password,$this->dbaname);
    		if ($this->conn->connect_error) {
    			echo 'Failed to connect to MySQL - ' . $this->conn->connect_error;
    		}
    		$this->conn->set_charset('utf8');
  	}


     public function connet(){
       return $this->conn;

      }

      public function autocommitF(){
            $this->conn->autocommit(false);
      }
      public function commitF(){

            $this->conn->commit();
      }
      public function rollbackf(){

            $this->conn->rollback();
      }





}
