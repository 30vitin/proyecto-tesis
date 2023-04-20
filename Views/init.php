<?php


if(isset($_GET["action"])){
         
       
        Action::load($_GET["action"]);
}else{

    if(isset($_GET["view"])){
        
        Views::load($_GET["view"]);
        
    }else{
        
        //echo "vacio";
        Views::load("index");
    }
         

    
    
}
     
