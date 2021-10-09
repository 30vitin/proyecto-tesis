<?php


require_once 'Config/Functions.php';
require("Others/class.phpmailer.php");
$cls = new Functions;  //llamando al objeto
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Panama');

$time = time();
$datetime = date('Y-m-d H:i:s');


$mensaje = array();

if (isset($_POST['a']) && $_POST['a'] == 'LOGIN') {
    $key = $cls->getAuthKey();
    //$sql ="SELECT AES_ENCRYPT('3001', '$key');";
    //$sql2="INSERT INTO users_access(username,password)values('30vitin',AES_ENCRYPT('3001','23097d223405d8228642a'))";

    if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT COUNT(*) FROM users_access WHERE username='$username'";
        $result = $cls->consulQuery($sql);
        if ($result[0] > 0) {

            $sql22 = "SELECT count(*) FROM users_access WHERE username='$username' and password = AES_ENCRYPT('$password', '$key') limit 1";
            $sql22_res = $cls->consulQuery($sql22);

            if ($sql22_res[0] > 0) {

                $VAR_SESSION = Session::getInstance();
                $CPASS = TRUE;
                $cls->autocommitF();

                // Let's store datas in the session
                $VAR_SESSION->username = $username;
                $VAR_SESSION->loggedin = true;
                $SESSIONID = session_id();
                session_write_close();



                if ($CPASS) {

                    $type = "success";
                    $mens = "";
                    $url = "./?view=dashboard";


                } else {

                    $type = "error";
                    $mens = "Ocurrio un error al autenticar";
                    $url = "";


                }


            } else {

                $type = "error";
                $mens = "Contraseña no valida! ";
                $url = "";
            }


        } else {

            $type = "error";
            $mens = "Usuario no registrado como admin!";
            $url = "";

        }


    } else {
        $type = "error";
        $mens = "Dijiste un email y una contraseña valida";
        $url = "";

    }
    $mensaje = array('type' => $type, 'mens' => $mens, 'url' => $url);

}


if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-PRODUCT-INFO') {

    $CPASS = TRUE;
    $cls->autocommitF();
    if (isset($_POST['product_id']) && $_POST['product_id'] != '') {
        if (isset($_POST['product_name']) && $_POST['product_name'] != '') {

            if (isset($_POST['descripcion']) && $_POST['descripcion'] != '') {
                $product_id = $_POST['product_id'];
                $sqltyp = "SELECT type from products WHERE id='$product_id'";
                $restype = $cls->consulQuery($sqltyp);

                if (isset($_POST['category']) && $_POST['category'] != '' || $restype[0] == 'PESAS') {

                    if (isset($_POST['status']) && $_POST['status'] != '') {

                        if (isset($_POST['price']) && $_POST['price'] != '') {

                            if (isset($_POST['itbms']) && $_POST['itbms'] != '') {

                                $chekPreSale = TRUE;


                                if (isset($_POST['pre_sale']) && $_POST['pre_sale'] != '' || $restype[0] == "ACCESORIO") {


                                    if (isset($_POST['pre_sale']) && $_POST['pre_sale'] == 'YES') {

                                        if (isset($_POST['days_to_pre_sale']) && $_POST['days_to_pre_sale'] != '') {

                                            if ($_POST['days_to_pre_sale'] == '0000-00-00' || $_POST['days_to_pre_sale'] == "") {

                                                $chekPreSale = FALSE;

                                            }

                                        } else {

                                            $chekPreSale = FALSE;
                                        }

                                    }


                                    if ($chekPreSale) {


                                        $sql1 = "SELECT COUNT(*) FROM products WHERE id='$product_id'";
                                        $result1 = $cls->consulQuery($sql1);
                                        if ($result1[0] > 0) {

                                            $sqlcarr = "SELECT count(*) from products t1 join carrito_details t2 on t1.id=t2.product_id join carrito t3 on t2.carrito_id=t3.id where t1.id='$product_id' and t3.status='ACTIVE'";
                                            $resulcarr = $cls->consulQuery($sqlcarr);
                                            if ($resulcarr[0] == 0) {

                                                $product_name = $_POST['product_name'];
                                                if ($product_name != '') {


                                                    $descripcion = $_POST['descripcion'];
                                                    $category = 0;

                                                    if (isset($_POST['category'])) {
                                                        $category = $_POST['category'];

                                                    }
                                                    $status = $_POST['status'];
                                                    $price = $_POST['price'];
                                                    $itbms = $_POST['itbms'];

                                                    $checkIMG1 = FALSE;
                                                    $checkIMG2 = TRUE;
                                                    $checkpor = FALSE;
                                                    if (isset($_FILES)) {

                                                        foreach ($_FILES['uploadofile']["name"] as $file => $key) {
                                                            if (!empty($_FILES['uploadofile']["name"][$file])) {

                                                                $checkIMG1 = TRUE;
                                                            }


                                                        }
                                                        foreach ($_FILES['uploadofile2']["name"] as $file => $key) {
                                                            if (!empty($_FILES['uploadofile2']["name"][$file])) {

                                                                $checkpor = TRUE;
                                                            }


                                                        }


                                                    } else {
                                                        $checkIMG1 = FALSE;

                                                    }


                                                    if (isset($_POST['currentimage']) && count(array($_POST['currentimage'])) == 0) {
                                                        $checkIMG2 = FALSE;

                                                    } else {

                                                        if (!isset($_POST['currentimage'])) {
                                                            $checkIMG2 = FALSE;
                                                        }
                                                    }

                                                    if ($checkIMG1 || $checkIMG2 || $checkpor) {


                                                        if ($price > 0) {

                                                            $pre_sale = "NO";
                                                            $days_to_pre_sale = "";
                                                            if (isset($_POST['pre_sale']) && $_POST['pre_sale'] == 'YES') {
                                                                $pre_sale = "YES";
                                                                $days_to_pre_sale = $_POST['days_to_pre_sale'];
                                                            }


                                                            $available_reviews = $_POST['available_reviews'];
                                                            if (isset($_POST['pre_sale']) && $_POST['pre_sale'] == 'YES') {

                                                                $sqlu1 = "UPDATE products SET name='$product_name',description='$descripcion',category='$category',status='$status',price='$price',itbms='$itbms',reviewed='$available_reviews',updated='$datetime',pre_sale='$pre_sale',days_to_pre_sale='$days_to_pre_sale' WHERE id='$product_id'";
                                                            } else {


                                                                $sqlu1 = "UPDATE products SET name='$product_name',description='$descripcion',category='$category',status='$status',price='$price',itbms='$itbms',reviewed='$available_reviews',updated='$datetime',pre_sale='$pre_sale',days_to_pre_sale=NULL WHERE id='$product_id'";
                                                            }


                                                            if ($restype[0] == 'PRODUCT') {
                                                                if (isset($_POST['tallaname']) && count($_POST['tallaname']) > 0) {

                                                                    $checkdis = TRUE;
                                                                    $chekupd = TRUE;
                                                                    for ($i = 0; $i < count($_POST['tallaname']); $i++) {
                                                                        $tallaname = $_POST['tallaname'][$i];
                                                                        $tallaavailable = $_POST['tallaavailable'][$i];
                                                                        $tallastatus = $_POST['tallastatus'][$i];
                                                                        if ($checkdis) {
                                                                            if ($tallaavailable >= 0) {

                                                                                $updasta = "UPDATE products_tallas SET status='$tallastatus' WHERE product_id='$product_id' AND talla='$tallaname' ";
                                                                                $updasta_r = $cls->exeQuery($updasta);
                                                                                if ($updasta_r == 1) {

                                                                                    $sqlverif = "SELECT COUNT(*) FROM products_available WHERE product_id='$product_id' AND talla='$tallaname'";
                                                                                    $resulrevif = $cls->consulQuery($sqlverif);
                                                                                    if ($resulrevif[0] > 0) {

                                                                                        if ($tallaavailable > 0) {
                                                                                            $navailable = 0;
                                                                                            $total = 0;
                                                                                            $disactual = $cls->getAvailableProduct($product_id, $tallaname);
                                                                                            //  if($tallaavailable>$disactual){

                                                                                            $sqlgeten = "SELECT cantidad_entrada,cantidad_salida FROM products_available WHERE product_id='$product_id' AND talla='$tallaname' limit 1";
                                                                                            $resultgeten = $cls->consulQuery($sqlgeten);
                                                                                            //$total=$navailable+$resultgeten[0];

                                                                                            //  $navailable=$tallaavailable-$disactual;
                                                                                            $total = ($tallaavailable) - ($resultgeten[0] - $resultgeten[1]) + $resultgeten[0];

                                                                                            if ($total >= 0) {

                                                                                                $sqlupgeten = "UPDATE products_available SET cantidad_entrada='$total' WHERE product_id='$product_id' AND talla='$tallaname'";
                                                                                                $sqlupgeten_r = $cls->exeQuery($sqlupgeten);
                                                                                                if ($sqlupgeten_r != 1) {
                                                                                                    $chekupd = FALSE;
                                                                                                    $CPASS = FALSE;
                                                                                                    $type = "error";
                                                                                                    $mens = "Hubo un error " . $sqlupgeten_r;
                                                                                                    $url = "";
                                                                                                }
                                                                                            } else {

                                                                                                $CPASS = FALSE;
                                                                                                $type = "error";
                                                                                                $mens = "Error al insertar disponibilidad el producto quedara en negativo";
                                                                                                $url = "";
                                                                                            }
                                                                                            //  }
                                                                                            /*else{
                                                                  if($tallaavailable<$disactual){
                                                                       $navailable=$tallaavailable-$disactual;
                                                                       if($navailable>=0){

                                                                          $sqlgeten="SELECT cantidad_entrada FROM products_available WHERE product_id='$product_id' AND talla='$tallaname' limit 1";
                                                                          $resultgeten=$cls->consulQuery($sqlgeten);
                                                                          $sqlupgeten="UPDATE products_available SET cantidad_entrada='$total' WHERE product_id='$product_id' AND talla='$tallaname'";
                                                                          $sqlupgeten_r=$cls->exeQuery($sqlupgeten);
                                                                          if($sqlupgeten_r!=1){$chekupd=FALSE;

                                                                              $CPASS=FALSE;
                                                                              $type="error";
                                                                              $mens="Hubo un error ".$sqlupgeten_r;
                                                                              $url="";

                                                                          }
                                                                       }

                                                                  }

                                                              }*/
                                                                                        }

                                                                                    } else {
                                                                                        $sqline = "INSERT INTO products_available (product_id,talla,cantidad_entrada,entrada_date) VALUES('$product_id','$tallaname','$tallaavailable','$datetime')";
                                                                                        $sqline_r = $cls->exeQuery($sqline);

                                                                                        if ($sqline_r != 1) {
                                                                                            $CPASS = FALSE;
                                                                                            $type = "error";
                                                                                            $mens = "Hubo un error " . $sqline_r;
                                                                                            $url = "";
                                                                                        }
                                                                                    }

                                                                                } else {

                                                                                    $CPASS = FALSE;
                                                                                    $type = "error";
                                                                                    $mens = "Hubo un error " . $updasta_r;
                                                                                    $url = "";
                                                                                }

                                                                            } else {
                                                                                if ($tallaavailable < 0) {
                                                                                    $checkdis = FALSE;
                                                                                }

                                                                            }

                                                                        }


                                                                    }


                                                                    if ($checkdis && $chekupd) {

                                                                        $sqlu1_r = $cls->exeQuery($sqlu1);
                                                                        if ($sqlu1_r == 1) {

                                                                            if (isset($_POST['currentimage']) && count(array($_POST['currentimage'])) > 0) {
                                                                                $defaultImage = "";

                                                                                $sqlte = "SELECT id,urlphoto_big FROM products_files WHERE product_id='$product_id'";
                                                                                $rest = $cls->consultListQuery($sqlte);
                                                                                for ($j = 0; $j < count($rest); $j++) {
                                                                                    $idfile = "";

                                                                                    if (!in_array($rest[$j]->id, $_POST['currentimage'])) {

                                                                                        $idfile = $rest[$j]->id;


                                                                                        $sqldlimg = "DELETE FROM products_files WHERE product_id='$product_id' AND id='$idfile'";
                                                                                        $sqlu1_r = $cls->exeQuery($sqldlimg);
                                                                                        if ($sqlu1_r != 1) {
                                                                                            $CPASS = FALSE;
                                                                                            $type = "error";
                                                                                            $mens = "Hubo un error " . $sqlu1_r;
                                                                                            $url = "";
                                                                                        }


                                                                                        if ($rest[$j]->urlphoto_big != '') {
                                                                                            $namebig = $rest[$j]->urlphoto_big;

                                                                                            $urlphoto1 = "Others/Files_products/$product_id/$namebig";

                                                                                            if (file_exists($urlphoto1)) {
                                                                                                unlink($urlphoto1);

                                                                                            }
                                                                                        }

                                                                                    }

                                                                                }


                                                                            }

                                                                            if (isset($_POST['currentimage2'])) {

                                                                                $sqlte2 = "SELECT img_slider_promo FROM products WHERE id='$product_id' and img_slider_promo<>''";
                                                                                $rest3 = $cls->consultListQuery($sqlte2);
                                                                                for ($j = 0; $j < count($rest3); $j++) {
                                                                                    if (isset($rest3[$j]->id) && !in_array($rest3[$j]->id, $_POST['currentimage2'])) {

                                                                                        $sqldlimg2 = "UPDATE products SET img_slider_promo='' WHERE  id='$product_id'";
                                                                                        $sqlu1_r2 = $cls->exeQuery($sqldlimg2);
                                                                                        if ($sqlu1_r2 != 1) {
                                                                                            $CPASS = FALSE;
                                                                                            $type = "error";
                                                                                            $mens = "Hubo un error " . $sqlu1_r2;
                                                                                            $url = "";
                                                                                        }
                                                                                        if ($rest3[$j]->img_slider_promo != '') {
                                                                                            $namebig2 = $rest3[$j]->img_slider_promo;

                                                                                            $urlphoto12 = "Others/Files_products/$product_id/$namebig2";

                                                                                            if (file_exists($urlphoto12)) {
                                                                                                unlink($urlphoto12);

                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }

                                                                            }


                                                                            $module_file1 = "Others/Files_products/$product_id/";
                                                                            $carpeta = $module_file1;

                                                                            if (!file_exists($carpeta)) {
                                                                                mkdir($carpeta, 0777);
                                                                                chmod($carpeta, 0777);

                                                                            }

                                                                            if ($checkIMG1) {
                                                                                foreach ($_FILES['uploadofile']["name"] as $file => $key) {

                                                                                    if (!empty($_FILES['uploadofile']["name"][$file])) {
                                                                                        $string = $cls->generate_string(30);


                                                                                        $filename_BIG = str_replace(" ", "_", $_FILES["uploadofile"]["name"][$file]); //Obtenemos el nombre original del archivo
                                                                                        $source1 = $_FILES["uploadofile"]["tmp_name"][$file]; //Obtenemos un fuente temporal del archivo
                                                                                        $newname_file_BIG = $string . "_big" . $filename_BIG;
                                                                                        $path1 = $module_file1 . $newname_file_BIG;


                                                                                        // Here we move images to their
                                                                                        if (!move_uploaded_file($source1, $path1)) {
                                                                                            $CPASS = FALSE;
                                                                                            $type = "error";
                                                                                            $mens = "Hubo un error al guardar imagen";
                                                                                            $url = "";
                                                                                        }

                                                                                        // Here we move images to their


                                                                                        $ins1im = "INSERT INTO products_files(product_id,urlphoto_big)VALUES('$product_id','$newname_file_BIG')";
                                                                                        $resfil = $cls->exeQuery($ins1im);
                                                                                        if ($resfil != 1) {
                                                                                            $CPASS = FALSE;
                                                                                            $type = "error";
                                                                                            $mens = "Hubo un error " . $resfil;
                                                                                            $url = "";
                                                                                        }

                                                                                    }


                                                                                }//FIN DEL FOR RECORRE FOTOS

                                                                            }

                                                                            if ($checkpor) {

                                                                                foreach ($_FILES['uploadofile2']["name"] as $file => $key) {

                                                                                    if (!empty($_FILES['uploadofile2']["name"][$file])) {
                                                                                        $string = $cls->generate_string(30);


                                                                                        $filename_BIG2 = str_replace(" ", "_", $_FILES["uploadofile2"]["name"][$file]); //Obtenemos el nombre original del archivo
                                                                                        $source12 = $_FILES["uploadofile2"]["tmp_name"][$file]; //Obtenemos un fuente temporal del archivo
                                                                                        $newname_file_BIG2 = $string . "_big" . $filename_BIG2;
                                                                                        $path12 = $module_file1 . $newname_file_BIG2;


                                                                                        // Here we move images to their
                                                                                        if (!move_uploaded_file($source12, $path12)) {
                                                                                            $CPASS = FALSE;
                                                                                            $type = "error";
                                                                                            $mens = "Hubo un error al guardar imagen";
                                                                                            $url = "";
                                                                                        }

                                                                                        // Here we move images to their

                                                                                        $ins1im2 = "UPDATE products set img_slider_promo='$newname_file_BIG2' where id='$product_id' ";
                                                                                        $resfil2 = $cls->exeQuery($ins1im2);
                                                                                        if ($resfil2 != 1) {
                                                                                            $CPASS = FALSE;
                                                                                            $type = "error";
                                                                                            $mens = "Hubo un error " . $resfil2;
                                                                                            $url = "";
                                                                                        }

                                                                                    }


                                                                                }//FIN DEL FOR RECORRE FOTOS

                                                                            }


                                                                            $type = "success";
                                                                            $mens = "";
                                                                            $url = "";


                                                                        } else {
                                                                            $CPASS = FALSE;
                                                                            $type = "error";
                                                                            $mens = "Hubo un error " . $sqlu1_r;
                                                                            $url = "";

                                                                        }//CONDICION


                                                                    } else {

                                                                        $type = "error";
                                                                        $mens = "El la cantidad disponible no es correcta debe ser mayor a cero.";
                                                                        $url = "";


                                                                    }

                                                                } else {

                                                                    $type = "error";
                                                                    $mens = "El debe Producto debe tener talla especificada.";
                                                                    $url = "";

                                                                }

                                                            } else {

                                                                if ($restype[0] == 'PESAS') {
                                                                    if (isset($_POST['librasname']) && count($_POST['librasname']) > 0) {

                                                                        $checkdis = TRUE;
                                                                        $chekupd = TRUE;
                                                                        for ($i = 0; $i < count($_POST['librasname']); $i++) {
                                                                            $tallaname = $_POST['librasname'][$i];
                                                                            $tallaavailable = $_POST['librasavailable'][$i];
                                                                            $tallastatus = $_POST['librastatus'][$i];

                                                                            if ($checkdis) {
                                                                                if ($tallaavailable >= 0) {

                                                                                    $updasta = "UPDATE products_libras SET status='$tallastatus' WHERE product_id='$product_id' AND libras='$tallaname' ";
                                                                                    $updasta_r = $cls->exeQuery($updasta);
                                                                                    if ($updasta_r == 1) {

                                                                                        $sqlverif = "SELECT COUNT(*) FROM products_available WHERE product_id='$product_id' AND libras='$tallaname'";
                                                                                        $resulrevif = $cls->consulQuery($sqlverif);
                                                                                        if ($resulrevif[0] > 0) {

                                                                                            if ($tallaavailable >= 0) {
                                                                                                $navailable = 0;
                                                                                                $total = 0;
                                                                                                $disactual = $cls->getAvailableProductLibras($product_id, $tallaname);
                                                                                                //  if($tallaavailable>$disactual){

                                                                                                $sqlgeten = "SELECT cantidad_entrada,cantidad_salida FROM products_available WHERE product_id='$product_id' AND libras='$tallaname' limit 1";
                                                                                                $resultgeten = $cls->consulQuery($sqlgeten);
                                                                                                //$total=$navailable+$resultgeten[0];

                                                                                                //  $navailable=$tallaavailable-$disactual;
                                                                                                $total = ($tallaavailable) - ($resultgeten[0] - $resultgeten[1]) + $resultgeten[0];

                                                                                                if ($total >= 0) {

                                                                                                    $sqlupgeten = "UPDATE products_available SET cantidad_entrada='$total' WHERE product_id='$product_id' AND libras='$tallaname'";
                                                                                                    $sqlupgeten_r = $cls->exeQuery($sqlupgeten);
                                                                                                    if ($sqlupgeten_r != 1) {
                                                                                                        $chekupd = FALSE;
                                                                                                        $CPASS = FALSE;
                                                                                                        $type = "error";
                                                                                                        $mens = "Hubo un error " . $sqlupgeten_r;
                                                                                                        $url = "";
                                                                                                    }
                                                                                                } else {

                                                                                                    $CPASS = FALSE;
                                                                                                    $type = "error";
                                                                                                    $mens = "Error al insertar disponibilidad el producto quedara en negativo";
                                                                                                    $url = "";
                                                                                                }
                                                                                                //  }
                                                                                                /*else{
                                                                      if($tallaavailable<$disactual){
                                                                           $navailable=$tallaavailable-$disactual;
                                                                           if($navailable>=0){

                                                                              $sqlgeten="SELECT cantidad_entrada FROM products_available WHERE product_id='$product_id' AND talla='$tallaname' limit 1";
                                                                              $resultgeten=$cls->consulQuery($sqlgeten);
                                                                              $sqlupgeten="UPDATE products_available SET cantidad_entrada='$total' WHERE product_id='$product_id' AND talla='$tallaname'";
                                                                              $sqlupgeten_r=$cls->exeQuery($sqlupgeten);
                                                                              if($sqlupgeten_r!=1){$chekupd=FALSE;

                                                                                  $CPASS=FALSE;
                                                                                  $type="error";
                                                                                  $mens="Hubo un error ".$sqlupgeten_r;
                                                                                  $url="";

                                                                              }
                                                                           }

                                                                      }

                                                                  }*/
                                                                                            }

                                                                                        } else {
                                                                                            $sqline = "INSERT INTO products_available (product_id,libras,cantidad_entrada,entrada_date) VALUES('$product_id','$tallaname','$tallaavailable','$datetime')";
                                                                                            $sqline_r = $cls->exeQuery($sqline);

                                                                                            if ($sqline_r != 1) {
                                                                                                $CPASS = FALSE;
                                                                                                $type = "error";
                                                                                                $mens = "Hubo un error " . $sqline_r;
                                                                                                $url = "";
                                                                                            }
                                                                                        }

                                                                                    } else {

                                                                                        $CPASS = FALSE;
                                                                                        $type = "error";
                                                                                        $mens = "Hubo un error " . $updasta_r;
                                                                                        $url = "";
                                                                                    }

                                                                                } else {
                                                                                    if ($tallaavailable < 0) {
                                                                                        $CPASS = FALSE;
                                                                                        $checkdis = FALSE;
                                                                                    }

                                                                                }

                                                                            }


                                                                        }

                                                                        if ($checkdis && $chekupd) {

                                                                            $sqlu1_r = $cls->exeQuery($sqlu1);
                                                                            if ($sqlu1_r == 1) {

                                                                                if (isset($_POST['currentimage']) && count(array($_POST['currentimage'])) > 0) {


                                                                                    $sqlte = "SELECT id,urlphoto_big FROM products_files WHERE product_id='$product_id'";
                                                                                    $rest = $cls->consultListQuery($sqlte);
                                                                                    for ($j = 0; $j < count($rest); $j++) {
                                                                                        $idfile = "";
                                                                                        if (!in_array($rest[$j]->id, $_POST['currentimage'])) {
                                                                                            $idfile = $rest[$j]->id;
                                                                                            $sqldlimg = "DELETE FROM products_files WHERE product_id='$product_id' AND id='$idfile'";
                                                                                            $sqlu1_r = $cls->exeQuery($sqldlimg);
                                                                                            if ($sqlu1_r != 1) {
                                                                                                $CPASS = FALSE;
                                                                                                $type = "error";
                                                                                                $mens = "Hubo un error " . $sqlu1_r;
                                                                                                $url = "";
                                                                                            }

                                                                                            if ($rest[$j]->urlphoto_big != '') {
                                                                                                $namebig = $rest[$j]->urlphoto_big;
                                                                                                $urlphoto1 = "Others/Files_products/$product_id/$namebig";

                                                                                                if (file_exists($urlphoto1)) {
                                                                                                    unlink($urlphoto1);

                                                                                                }

                                                                                            }

                                                                                        }

                                                                                    }


                                                                                }

                                                                                if (isset($_POST['currentimage2'])) {

                                                                                    $sqlte2 = "SELECT img_slider_promo FROM products WHERE id='$product_id' and img_slider_promo<>''";
                                                                                    $rest3 = $cls->consultListQuery($sqlte2);
                                                                                    for ($j = 0; $j < count($rest3); $j++) {
                                                                                        if (isset($rest3[$j]->id) && !in_array($rest3[$j]->id, $_POST['currentimage2'])) {

                                                                                            $sqldlimg2 = "UPDATE products SET img_slider_promo='' WHERE  id='$product_id'";
                                                                                            $sqlu1_r2 = $cls->exeQuery($sqldlimg2);
                                                                                            if ($sqlu1_r2 != 1) {
                                                                                                $CPASS = FALSE;
                                                                                                $type = "error";
                                                                                                $mens = "Hubo un error " . $sqlu1_r2;
                                                                                                $url = "";
                                                                                            }
                                                                                            if ($rest3[$j]->img_slider_promo != '') {
                                                                                                $namebig2 = $rest3[$j]->img_slider_promo;

                                                                                                $urlphoto12 = "Others/Files_products/$product_id/$namebig2";

                                                                                                if (file_exists($urlphoto12)) {
                                                                                                    unlink($urlphoto12);

                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }

                                                                                }


                                                                                $module_file1 = "Others/Files_products/$product_id/";
                                                                                $carpeta = $module_file1;

                                                                                if (!file_exists($carpeta)) {
                                                                                    mkdir($carpeta, 0777);
                                                                                    chmod($carpeta, 0777);


                                                                                }

                                                                                if ($checkIMG1) {
                                                                                    foreach ($_FILES['uploadofile']["name"] as $file => $key) {

                                                                                        if (!empty($_FILES['uploadofile']["name"][$file])) {
                                                                                            $string = $cls->generate_string(30);

                                                                                            $filename_BIG = str_replace(" ", "_", $_FILES["uploadofile"]["name"][$file]); //Obtenemos el nombre original del archivo
                                                                                            $source1 = $_FILES["uploadofile"]["tmp_name"][$file]; //Obtenemos un fuente temporal del archivo
                                                                                            $newname_file_BIG = $string . "_big" . $filename_BIG;
                                                                                            $path1 = $module_file1 . $newname_file_BIG;
                                                                                            $filename_TUMB = str_replace(" ", "_", $_FILES["uploadofile"]["name"][$file]); //Obtenemos el nombre original del archivo


                                                                                            // Here we move images to their
                                                                                            if (!move_uploaded_file($source1, $path1)) {
                                                                                                $CPASS = FALSE;
                                                                                                $type = "error";
                                                                                                $mens = "Hubo un error al guardar imagen";
                                                                                                $url = "";
                                                                                            }


                                                                                            $ins1im = "INSERT INTO products_files(product_id,urlphoto_big)VALUES('$product_id','$newname_file_BIG')";
                                                                                            $resfil = $cls->exeQuery($ins1im);
                                                                                            if ($resfil != 1) {
                                                                                                $CPASS = FALSE;
                                                                                                $type = "error";
                                                                                                $mens = "Hubo un error " . $resfil;
                                                                                                $url = "";
                                                                                            }

                                                                                        }


                                                                                    }//FIN DEL FOR RECORRE FOTOS

                                                                                }
                                                                                if ($checkpor) {

                                                                                    foreach ($_FILES['uploadofile2']["name"] as $file => $key) {

                                                                                        if (!empty($_FILES['uploadofile2']["name"][$file])) {
                                                                                            $string = $cls->generate_string(30);


                                                                                            $filename_BIG2 = str_replace(" ", "_", $_FILES["uploadofile2"]["name"][$file]); //Obtenemos el nombre original del archivo
                                                                                            $source12 = $_FILES["uploadofile2"]["tmp_name"][$file]; //Obtenemos un fuente temporal del archivo
                                                                                            $newname_file_BIG2 = $string . "_big" . $filename_BIG2;
                                                                                            $path12 = $module_file1 . $newname_file_BIG2;


                                                                                            // Here we move images to their
                                                                                            if (!move_uploaded_file($source12, $path12)) {
                                                                                                $CPASS = FALSE;
                                                                                                $type = "error";
                                                                                                $mens = "Hubo un error al guardar imagen";
                                                                                                $url = "";
                                                                                            }

                                                                                            // Here we move images to their

                                                                                            $ins1im2 = "UPDATE products set img_slider_promo='$newname_file_BIG2' where id='$product_id' ";
                                                                                            $resfil2 = $cls->exeQuery($ins1im2);
                                                                                            if ($resfil2 != 1) {
                                                                                                $CPASS = FALSE;
                                                                                                $type = "error";
                                                                                                $mens = "Hubo un error " . $resfil2;
                                                                                                $url = "";
                                                                                            }

                                                                                        }


                                                                                    }//FIN DEL FOR RECORRE FOTOS

                                                                                }


                                                                                $type = "success";
                                                                                $mens = "";
                                                                                $url = "";


                                                                            } else {
                                                                                $CPASS = FALSE;
                                                                                $type = "error";
                                                                                $mens = "Hubo un error " . $sqlu1_r;
                                                                                $url = "";

                                                                            }//CONDICION


                                                                        } else {

                                                                            $type = "error";
                                                                            $mens = "El la cantidad disponible no es correcta debe ser mayor a cero.";
                                                                            $url = "";


                                                                        }

                                                                    } else {

                                                                        $type = "error";
                                                                        $mens = "El debe Producto debe tener talla especificada.";
                                                                        $url = "";

                                                                    }


                                                                } else {
                                                                    //accesorios
                                                                    if (isset($_POST['available']) && $_POST['available'] != '') {

                                                                        $available = $_POST['available'];
                                                                        if ($available >= 0) {
                                                                            $chekupd = TRUE;
                                                                            $sqlverif = "SELECT COUNT(*) FROM accesories_available WHERE product_id='$product_id' ";
                                                                            $resulrevif = $cls->consulQuery($sqlverif);
                                                                            if ($resulrevif[0] > 0) {
                                                                                $navailable = 0;
                                                                                $total = 0;
                                                                                $disactual = $cls->getAvailableAcesoriosGlobal($product_id);
                                                                                //  if($available>$disactual){
                                                                                //$navailable=$available-$disactual;
                                                                                $sqlgeten = "SELECT cantidad_entrada,cantidad_salida FROM accesories_available WHERE product_id='$product_id' limit 1";
                                                                                $resultgeten = $cls->consulQuery($sqlgeten);
                                                                                //$total=$navailable+$resultgeten[0];
                                                                                //$units)-($actualunits[0]['unit_entrada']-$actualunits[0]['unit_salida'])+$actualunits[0]['unit_entrada']
                                                                                $total = ($available) - ($resultgeten[0] - $resultgeten[1]) + $resultgeten[0];
                                                                                if ($total >= 0) {
                                                                                    $sqlupgeten = "UPDATE accesories_available SET cantidad_entrada='$total' WHERE product_id='$product_id'";
                                                                                    $sqlupgeten_r = $cls->exeQuery($sqlupgeten);
                                                                                    if ($sqlupgeten_r != 1) {
                                                                                        $chekupd = FALSE;
                                                                                        $CPASS = FALSE;
                                                                                        $type = "error";
                                                                                        $mens = "Hubo un error " . $sqlupgeten_r;
                                                                                        $url = "";

                                                                                    }
                                                                                } else {

                                                                                    $chekupd = FALSE;
                                                                                    $CPASS = FALSE;
                                                                                    $type = "error";
                                                                                    $mens = "Hubo un error al guardar disponibilidad el accesorio quedara en negativo";
                                                                                    $url = "";
                                                                                }
                                                                                //  }
                                                                                /*else{
                                                               if($available<$disactual){
                                                                            $navailable=$available-$disactual;
                                                                            if($navailable>=0){

                                                                               $sqlgeten="SELECT cantidad_entrada FROM accesories_available WHERE product_id='$product_id' limit 1";
                                                                               $resultgeten=$cls->consulQuery($sqlgeten);
                                                                               $sqlupgeten="UPDATE accesories_available SET cantidad_entrada='$total' WHERE product_id='$product_id' ";
                                                                               $sqlupgeten_r=$cls->exeQuery($sqlupgeten);
                                                                               if($sqlupgeten_r!=1){
                                                                                 $chekupd=FALSE;
                                                                                 $CPASS=FALSE;
                                                                                 $type="error";
                                                                                 $mens="Hubo un error ".$sqlupgeten_r;
                                                                                 $url="";
                                                                               }
                                                                            }

                                                                       }


                                                           }*/

                                                                            } else {

                                                                                $sqline = "INSERT INTO accesories_available (product_id,cantidad_entrada,entrada_date) VALUES('$product_id','$available','$datetime')";
                                                                                $sqline_r = $cls->exeQuery($sqline);
                                                                                if ($sqline_r != 1) {
                                                                                    $CPASS = FALSE;
                                                                                    $type = "error";
                                                                                    $mens = "Hubo un error " . $sqline_r;
                                                                                    $url = "";
                                                                                }

                                                                            }

                                                                            if ($chekupd) {


                                                                                $sqlu1_r = $cls->exeQuery($sqlu1);
                                                                                if ($sqlu1_r == 1) {

                                                                                    if (isset($_POST['currentimage']) && $_POST['currentimage'] != '' && count(array($_POST['currentimage'])) > 0) {

                                                                                        $sqlte = "SELECT id,urlphoto_big FROM products_files WHERE product_id='$product_id'";
                                                                                        $rest = $cls->consultListQuery($sqlte);
                                                                                        for ($j = 0; $j < count($rest); $j++) {
                                                                                            $idfile = "";
                                                                                            if (!in_array($rest[$j]->id, $_POST['currentimage'])) {
                                                                                                $idfile = $rest[$j]->id;
                                                                                                $sqldlimg = "DELETE FROM products_files WHERE product_id='$product_id' AND id='$idfile'";
                                                                                                $sqlu1_r = $cls->exeQuery($sqldlimg);
                                                                                                if ($sqlu1_r != 1) {
                                                                                                    $CPASS = FALSE;
                                                                                                    $type = "error";
                                                                                                    $mens = "Hubo un error " . $sqlu1_r;
                                                                                                    $url = "";
                                                                                                }
                                                                                                if ($rest[$j]->urlphoto_big != '') {
                                                                                                    $namebig = $rest[$j]->urlphoto_big;
                                                                                                    $urlphoto1 = "Others/Files_products/$product_id/$namebig";


                                                                                                    if (file_exists($urlphoto1)) {
                                                                                                        unlink($urlphoto1);

                                                                                                    }

                                                                                                }

                                                                                            }

                                                                                        }


                                                                                    }

                                                                                    if (isset($_POST['currentimage2'])) {

                                                                                        $sqlte2 = "SELECT img_slider_promo FROM products WHERE id='$product_id' and img_slider_promo<>''";
                                                                                        $rest3 = $cls->consultListQuery($sqlte2);
                                                                                        for ($j = 0; $j < count($rest3); $j++) {
                                                                                            if (isset($rest3[$j]->id) && !in_array($rest3[$j]->id, $_POST['currentimage2'])) {

                                                                                                $sqldlimg2 = "UPDATE products SET img_slider_promo='' WHERE  id='$product_id'";
                                                                                                $sqlu1_r2 = $cls->exeQuery($sqldlimg2);
                                                                                                if ($sqlu1_r2 != 1) {
                                                                                                    $CPASS = FALSE;
                                                                                                    $type = "error";
                                                                                                    $mens = "Hubo un error " . $sqlu1_r2;
                                                                                                    $url = "";
                                                                                                }
                                                                                                if ($rest3[$j]->img_slider_promo != '') {
                                                                                                    $namebig2 = $rest3[$j]->img_slider_promo;

                                                                                                    $urlphoto12 = "Others/Files_products/$product_id/$namebig2";

                                                                                                    if (file_exists($urlphoto12)) {
                                                                                                        unlink($urlphoto12);

                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }

                                                                                    }


                                                                                    $module_file1 = "Others/Files_products/$product_id/";
                                                                                    $carpeta = $module_file1;

                                                                                    if (!file_exists($carpeta)) {
                                                                                        mkdir($carpeta, 0777);
                                                                                        chmod($carpeta, 0777);
                                                                                    }

                                                                                    if ($checkIMG1) {
                                                                                        foreach ($_FILES['uploadofile']["name"] as $file => $key) {

                                                                                            if (!empty($_FILES['uploadofile']["name"][$file])) {
                                                                                                $string = $cls->generate_string(30);


                                                                                                $filename_BIG = str_replace(" ", "_", $_FILES["uploadofile"]["name"][$file]); //Obtenemos el nombre original del archivo
                                                                                                $source1 = $_FILES["uploadofile"]["tmp_name"][$file]; //Obtenemos un fuente temporal del archivo
                                                                                                $newname_file_BIG = $string . "_big" . $filename_BIG;


                                                                                                // Here we move images to their
                                                                                                if (!move_uploaded_file($source1, $module_file1 . $newname_file_BIG)) {
                                                                                                    $CPASS = FALSE;
                                                                                                    $type = "error";
                                                                                                    $mens = "Hubo un error al guardar imagen";
                                                                                                    $url = "";
                                                                                                }

                                                                                                // Here we move images to their


                                                                                                $ins1im = "INSERT INTO products_files(product_id,urlphoto_big)VALUES('$product_id','$newname_file_BIG')";

                                                                                                $resfil = $cls->exeQuery($ins1im);
                                                                                                if ($resfil != 1) {

                                                                                                    $CPASS = FALSE;
                                                                                                    $type = "error";
                                                                                                    $mens = "Hubo un error " . $resfil;
                                                                                                    $url = "";
                                                                                                }
                                                                                            }


                                                                                        }//FIN DEL FOR RECORRE FOTOS

                                                                                    }

                                                                                    if ($checkpor) {

                                                                                        foreach ($_FILES['uploadofile2']["name"] as $file => $key) {

                                                                                            if (!empty($_FILES['uploadofile2']["name"][$file])) {
                                                                                                $string = $cls->generate_string(30);


                                                                                                $filename_BIG2 = str_replace(" ", "_", $_FILES["uploadofile2"]["name"][$file]); //Obtenemos el nombre original del archivo
                                                                                                $source12 = $_FILES["uploadofile2"]["tmp_name"][$file]; //Obtenemos un fuente temporal del archivo
                                                                                                $newname_file_BIG2 = $string . "_big" . $filename_BIG2;
                                                                                                $path12 = $module_file1 . $newname_file_BIG2;


                                                                                                // Here we move images to their
                                                                                                if (!move_uploaded_file($source12, $path12)) {
                                                                                                    $CPASS = FALSE;
                                                                                                    $type = "error";
                                                                                                    $mens = "Hubo un error al guardar imagen";
                                                                                                    $url = "";
                                                                                                }

                                                                                                // Here we move images to their

                                                                                                $ins1im2 = "UPDATE products set img_slider_promo='$newname_file_BIG2' where id='$product_id' ";
                                                                                                $resfil2 = $cls->exeQuery($ins1im2);
                                                                                                if ($resfil2 != 1) {
                                                                                                    $CPASS = FALSE;
                                                                                                    $type = "error";
                                                                                                    $mens = "Hubo un error " . $resfil2;
                                                                                                    $url = "";
                                                                                                }

                                                                                            }


                                                                                        }//FIN DEL FOR RECORRE FOTOS

                                                                                    }

                                                                                    $type = "success";
                                                                                    $mens = "";
                                                                                    $url = "";


                                                                                } else {

                                                                                    $CPASS = FALSE;
                                                                                    $type = "error";
                                                                                    $mens = "Hubo un error " . $sqlu1_r;
                                                                                    $url = "";
                                                                                }//CONDICION


                                                                            } else {

                                                                                $type = "error";
                                                                                $mens = "Hubo un error al registrar disponibilidad";
                                                                                $url = "";


                                                                            }

                                                                        } else {

                                                                            $type = "error";
                                                                            $mens = "La disponibilidad debe ser mayor o igual a cero.";
                                                                            $url = "";
                                                                        }


                                                                    } else {

                                                                        $type = "error";
                                                                        $mens = "El debe especificar una disponibilidad.";
                                                                        $url = "";

                                                                    }

                                                                }

                                                            }


                                                        } else {

                                                            $type = "error";
                                                            $mens = "El precio debe ser mayor a cero.";
                                                            $url = "";

                                                        }


                                                    } else {

                                                        $type = "error";
                                                        $mens = "Debe agregar al menos una imagen.";
                                                        $url = "";

                                                    }

                                                } else {

                                                    $type = "error";
                                                    $mens = "El campo nombre de producto es requerido.";
                                                    $url = "";

                                                }


                                            } else {

                                                $type = "error";
                                                $mens = "Nose puede editar este producto porque esta activo en un carrito.";
                                                $url = "";

                                            }

                                        } else {

                                            $type = "error";
                                            $mens = "Nose encuentra este producto en el sistema.";
                                            $url = "";

                                        }


                                    } else {

                                        $type = "error";
                                        $mens = "Si el producto es pre-sale debe colocar fecha de llegada de mercancía";
                                        $url = "";

                                    }


                                } else {


                                    $type = "error";
                                    $mens = "El Pre-sale es requerido.";
                                    $url = "";
                                }


                            } else {
                                // code...

                                $type = "error";
                                $mens = "el itbms del producto es requerido.";
                                $url = "";
                            }


                        } else {

                            $type = "error";
                            $mens = "el precio del producto es requerido.";
                            $url = "";
                        }


                    } else {

                        $type = "error";
                        $mens = "el status del producto es requerido.";
                        $url = "";
                    }

                } else {

                    $type = "error";
                    $mens = "La categoria del producto es requerido.";
                    $url = "";
                }


            } else {

                $type = "error";
                $mens = "La descripcion del producto es requerido.";
                $url = "";

            }

        } else {

            $type = "error";
            $mens = "El nombre del producto es requerido.";
            $url = "";
        }

    } else {

        $type = "error";
        $mens = "El id del producto es requerido.";
        $url = "";
    }

    if ($CPASS) {
        $cls->commitSet();

    } else {
        // code...
        $cls->exeQuery('ROLLBACK');
        $cls->rollback();

    }


    $mensaje = array('type' => $type, 'mens' => $mens, 'url' => $url);
}

if (isset($_POST['a']) && $_POST['a'] == 'GET-CAT-TYPE') {
    $html = "";
    $sql2 = "";
    if (isset($_POST['value']) && $_POST['value'] != '') {
        $type = $_POST['value'];
        if ($type == 'PRODUCT' || $type == 'PESAS') {

            $sql2 = "SELECT * FROM products_category WHERE status='ACTIVE' and type ='$type'";
        } else {
            $sql2 = "SELECT * FROM accesories_category WHERE status='ACTIVE'";
        }

        $result_lis2 = $cls->consultListQuery($sql2);//query
        $html .= "<option value='' selected>-Seleccione-</option>";
        foreach ($result_lis2 as $item2) {

            $html .= "<option value=" . $item2->id . ">" . ucwords($item2->name) . "</option>";
        }
    }


    $mensaje = array('html' => $html);
}

if (isset($_POST['a']) && $_POST['a'] == 'CREATE-PRODUCT-INFO') {


    $CPASS = FALSE;
    $cls->autocommitF();

    if (isset($_POST['product_name']) && $_POST['product_name'] != '') {

        if (isset($_POST['typeproduct']) && $_POST['typeproduct'] != '') {

            if (isset($_POST['category_createpr']) && $_POST['category_createpr'] != '') {

                if (isset($_POST['descripcion']) && $_POST['descripcion'] != '') {

                    if (isset($_POST['price']) && $_POST['price'] != '' && $_POST['price'] > 0) {
                        $checkIMG1 = FALSE;
                        if (isset($_FILES)) {

                            foreach ($_FILES['uploadofile']["name"] as $file => $key) {
                                if (!empty($_FILES['uploadofile']["name"][$file])) {

                                    $checkIMG1 = TRUE;
                                }


                            }

                        } else {
                            $checkIMG1 = FALSE;

                        }


                        if ($checkIMG1) {
                            $name = $_POST['product_name'];
                            $description = $_POST['descripcion'];
                            $category = $_POST['category_createpr'];
                            $price = $_POST['price'];
                            $itbms = $_POST['itbms'];
                            $type = $_POST['typeproduct'];

                            $product_id = $cls->getId_autoincrement("products");

                            $CPASS = FALSE;
                            $eerorln = "";
                            // $cls->exeQuery('SET AUTOCOMMIT = 0');
                            //$cls->exeQuery('START TRANSACTION');
                            $cls->autocommitF();

                            $sqlin1 = "INSERT INTO products (id,name,description,rating,category,price,itbms,inserted,type,status) VALUES('$product_id', '$name','$description','5','$category','$price','$itbms','$datetime','$type','INACTIVE');";


                            $sqlin1_r = $cls->exeQuery($sqlin1);
                            if ($sqlin1_r == 1) {

                                $module_file1 = "Others/Files_products/$product_id/";
                                $carpeta = $module_file1;

                                if (!file_exists($carpeta)) {
                                    mkdir($carpeta, 0777);
                                    chmod($carpeta, 0777);

                                }


                                foreach ($_FILES['uploadofile']["name"] as $file => $key) {

                                    if (!empty($_FILES['uploadofile']["name"][$file])) {
                                        $string = $cls->generate_string(30);


                                        $filename_BIG = str_replace(" ", "_", $_FILES["uploadofile"]["name"][$file]); //Obtenemos el nombre original del archivo
                                        $source1 = $_FILES["uploadofile"]["tmp_name"][$file]; //Obtenemos un fuente temporal del archivo
                                        $newname_file_BIG = $string . "_big" . $filename_BIG;


                                        // Here we move images to their
                                        if (!move_uploaded_file($source1, $module_file1 . $newname_file_BIG)) {
                                            $CPASS = FALSE;
                                            $type = "error";
                                            $mens = "Hubo un error al guardar imagen";
                                            $url = "";
                                        }


                                        $ins1im = "INSERT INTO products_files(product_id,urlphoto_big)VALUES('$product_id','$newname_file_BIG');";
                                        $resFi = $cls->exeQuery($ins1im);
                                        if ($resFi == 1) {
                                            $CPASS = TRUE;

                                        } else {
                                            // code...
                                            $eerorln = $resFi;

                                        }


                                    }


                                }//FIN DEL FOR RECORRE FOTOS


                                if ($CPASS) {
                                    $cls->commitSet();

                                    $type = "success";
                                    $mens = "";
                                    $url = "./?view=products";

                                } else {
                                    // code...
                                    $cls->exeQuery('ROLLBACK');
                                    $cls->rollback();
                                    $type = "error";
                                    $mens = "Hubo un error " . $eerorln;
                                    $url = "";
                                }

                            } else {

                                $type = "error";
                                $mens = "Hubo un error " . $sqlin1_r;
                                $url = "";
                            }


                        } else {
                            $type = "error";
                            $mens = "El producto debe contener almenos una imagen";
                            $url = "";

                        }


                    } else {
                        $type = "error";
                        $mens = "El precio del producto debe ser mayor a cero";
                        $url = "";

                    }


                } else {
                    $type = "error";
                    $mens = "La Descripción es requerida";
                    $url = "";

                }


            } else {

                $type = "error";
                $mens = "La Categoria es requerida";
                $url = "";

            }


        } else {

            $type = "error";
            $mens = "Error el campo Typo es requerido";
            $url = "";

        }


    } else {

        $type = "error";
        $mens = "Error el campo Nombre es requerido";
        $url = "";

    }

    $mensaje = array('type' => $type, 'mens' => $mens, 'url' => $url);
}

if (isset($_POST['a']) && $_POST['a'] == 'UPDATE-CATEGORY-PRODUCT-INFO') {

    if (isset($_POST['category_name']) && $_POST['category_name'] != "" && isset($_POST['category_id']) && $_POST['category_id'] != '') {
        $category_id = $_POST['category_id'];

        if (isset($_POST['status']) && $_POST['status'] != "") {

            if (isset($_POST['typecategory']) && $_POST['typecategory'] != "") {

                $sql = "SELECT  COUNT(*) FROM products_category where id='$category_id'";
                $resul1 = $cls->consulQuery($sql);
                if ($resul1[0] > 0) {

                    $category_name = $_POST['category_name'];

                    $status = $_POST['status'];
                    $typecategory = $_POST['typecategory'];
                    $sql2 = "SELECT  status FROM products_category where id='$category_id'";
                    $resul2 = $cls->consulQuery($sql2);
                    $chek1 = TRUE;
                    if ($status != $resul2[0] || $typecategory == 'ACCESORIO' || $typecategory == 'PESAS') {
                        $sql3 = "SELECT  count(*) FROM products where category='$category_id' and  type <> 'ACCESORIO' ";
                        $resul3 = $cls->consulQuery($sql3);
                        if ($resul3[0] > 0) {

                            $chek1 = FALSE;
                        }
                    }

                    if ($chek1) {

                        $check3 = TRUE;
                        $cls->autocommitF();
                        $CPASS = TRUE;

                        if ($typecategory == 'ACCESORIO') {
                            $sql4 = "DELETE FROM products_category WHERE id='$category_id' ";
                            $sql4_r = $cls->exeQuery($sql4);
                            if ($sql4_r == 1) {
                                $sql5 = "SELECT COUNT(*) FROM accesories_category WHERE name='$category_name' ";
                                $resul5 = $cls->consulQuery($sql5);
                                if ($resul5[0] == 0) {
                                    $check3 = TRUE;
                                } else {
                                    $check3 = FALSE;
                                    $CPASS = FALSE;

                                    $type = "error";
                                    $mens = "Ya existe un categoria de accesorios con el nombre $category_name";
                                    $url = "";

                                }

                            } else {
                                $check3 = FALSE;
                                $CPASS = FALSE;
                                $type = "error";
                                $mens = "Ha ocurrido un error " . $sql4_r;
                                $url = "";
                            }

                        }

                        if ($check3 && $typecategory == 'ACCESORIO') {
                            $sql6 = "INSERT INTO accesories_category (name,status,inserted) VALUES ('$category_name','$status','$datetime')";
                            $sql6_r = $cls->exeQuery($sql6);
                            if ($sql6_r == 1) {
                                $type = "success";
                                $mens = "";
                                $url = "./?view=category-accesorios";
                            } else {

                                $CPASS = FALSE;
                                $type = "error";
                                $mens = "Ha ocurrido un error " . $sql6_r;
                                $url = "";
                            }

                        } else {

                            $sql5 = "SELECT COUNT(*) FROM products_category WHERE name='$category_name'  and id<>'$category_id' ";
                            $resul5 = $cls->consulQuery($sql5);
                            if ($resul5[0] == 0) {
                                $sql6 = "UPDATE products_category SET name='$category_name',status='$status',updated='$datetime',type='$typecategory' where id='$category_id'";
                                $sql6_r = $cls->exeQuery($sql6);
                                if ($sql6_r == 1) {
                                    $type = "success";
                                    $mens = "";
                                    $url = "./?view=category-products";
                                } else {
                                    $CPASS = FALSE;

                                    $type = "error";
                                    $mens = "Ha ocurrido un error " . $sql6_r;
                                    $url = "";
                                }

                            } else {
                                $type = "error";
                                $mens = "Ya existe un categoria de productos con el nombre $category_name";
                                $url = "";

                            }

                        }

                        if ($CPASS) {
                            $cls->commitSet();


                        } else {
                            // code...
                            $cls->exeQuery('ROLLBACK');
                            $cls->rollback();

                        }


                    } else {

                        $type = "error";
                        $mens = "Error no se puede cambiar de status/tipo porque esta relacionado a un producto";
                        $url = "";

                    }


                } else {
                    $type = "error";
                    $mens = "Error el id no pertenece a categoria de producto";
                    $url = "";
                }


            } else {

                $type = "error";
                $mens = "Error el campo Tipo es requerido";
                $url = "";

            }


        } else {

            $type = "error";
            $mens = "Error el campo Status es requerido";
            $url = "";
        }

    } else {

        $type = "error";
        $mens = "Error el campo Nombre es requerido";
        $url = "";

    }

    $mensaje = array('type' => $type, 'mens' => $mens, 'url' => $url);


}

if (isset($_POST['a']) && $_POST['a'] == 'CREATE-CATEGORY-INFO') {

    if (isset($_POST['category_name']) && $_POST['category_name'] != "") {

        if (isset($_POST['status']) && $_POST['status'] != "") {

            if (isset($_POST['typecategory']) && $_POST['typecategory'] != "") {
                $category_name = $_POST['category_name'];

                $status = $_POST['status'];
                $typecategory = $_POST['typecategory'];
                $check = TRUE;
                $CPASS = TRUE;
                $cls->autocommitF();

                if ($typecategory == 'PRODUCT' || $typecategory == 'PESAS') {

                    $sql5 = "SELECT COUNT(*) FROM products_category WHERE name='$category_name'  ";
                    $resul5 = $cls->consulQuery($sql5);
                    if ($resul5[0] == 0) {
                        $sqli = "INSERT INTO  products_category(name,status,inserted,type) VALUES('$category_name','$status','$datetime','$typecategory')";
                        $sqli_r = $cls->exeQuery($sqli);
                        if ($sqli_r == 1) {
                            $type = "success";
                            $mens = "";
                            $url = "./?view=category-products";
                        } else {
                            $CPASS = FALSE;
                            $type = "error";
                            $mens = "Ha ocurrido un error " . $sqli_r;
                            $url = "";

                        }

                    } else {
                        $type = "error";
                        $mens = "Ya existe un categoria de productos con el nombre $category_name";
                        $url = "";
                    }
                } else {

                    $sql5 = "SELECT COUNT(*) FROM accesories_category WHERE name='$category_name'   ";
                    $resul5 = $cls->consulQuery($sql5);
                    if ($resul5[0] == 0) {

                        $sqli = "INSERT INTO  accesories_category (name,status,inserted) VALUES('$category_name','$status','$datetime')";
                        $sqli_r = $cls->exeQuery($sqli);
                        if ($sqli_r == 1) {
                            $type = "success";
                            $mens = "";
                            $url = "./?view=category-accesorios";
                        } else {
                            $CPASS = FALSE;
                            $type = "error";
                            $mens = "Ha ocurrido un error " . $sqli_r;
                            $url = "";

                        }

                    } else {
                        $type = "error";
                        $mens = "Ya existe un categoria de productos con el nombre $category_name";
                        $url = "";
                    }

                }

                if ($CPASS) {
                    $cls->commitSet();


                } else {
                    // code...
                    $cls->exeQuery('ROLLBACK');
                    $cls->rollback();

                }

            } else {

                $type = "error";
                $mens = "Error el campo Tipo es requerido";
                $url = "";

            }


        } else {

            $type = "error";
            $mens = "Error el campo Status es requerido";
            $url = "";
        }

    } else {

        $type = "error";
        $mens = "Error el campo Nombre es requerido";
        $url = "";

    }

    $mensaje = array('type' => $type, 'mens' => $mens, 'url' => $url);

}


echo json_encode($mensaje);
exit;
