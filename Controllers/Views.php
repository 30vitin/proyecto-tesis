<?php


class Views {

      public static function load($show){

            if(Views::isValid($show)){
                include "Views/".$show."-view.php";
            }else{

                Views::redirect();
            }

      }


      public static function isValid($show){ /// valida si el archivo existe
    		$valid=false;
    		if(file_exists($file = "Views/".$show."-view.php")){
    			$valid = true;
    		}
    		return $valid;
    	}

      public static function redirect(){

        echo "<script> window.location='./'; </script>";
      }
  


}







 