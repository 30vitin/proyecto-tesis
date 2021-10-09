<?php


class Action {

      public static function load($show){

            if(Action::isValid($show)){
              
               include "Action/".$show."-action.php";
            }else{

                Action::redirect();
            }

      }


      public static function isValid($show){ /// valida si el archivo existe
    		$valid=false;
    		if(file_exists($file = "Action/".$show."-action.php")){
    			$valid = true;
    		}
    		return $valid;
    	}

      public static function redirect(){

        echo "vacio dentro";
      }
  


}
