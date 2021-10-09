<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Panama');

$VAR_SESSION = Session::getInstance();
if($VAR_SESSION->email=="" || $VAR_SESSION->loggedin!=true){

    header("Location:./");
}
if(isset($_GET['username']) && $_GET['username']!=''){


}else{

}
$username=$_GET['username'];
$users="active";

?>


<!--
=========================================================
Material Dashboard - v2.1.2
=========================================================

Product Page: https://www.creative-tim.com/product/material-dashboard
Copyright 2020 Creative Tim (https://www.creative-tim.com)
Coded by Creative Tim

=========================================================
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="shortcut icon" href="Views/assets_login/images/favicon-01-ol.ico">

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Información de Usuario | Olimpoathletics
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="Views/assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="Views/assets/demo/demo.css" rel="stylesheet" />
  <style>

      #ht-preloader { background: #ffffff; bottom: 0; height: 100%; left: 0; overflow: hidden !important; position: fixed; right: 0; text-align: center; top: 0; width: 100%; z-index: 99999; }
.clear-loader { transform: translateX(-50%) translateY(-50%); -webkit-transform: translateX(-50%) translateY(-50%); -o-transform: translateX(-50%) translateY(-50%); -ms-transform: translateX(-50%) translateY(-50%); -moz-transform: translateX(-50%) translateY(-50%); z-index: 999; box-sizing: border-box; display: inline-block; left: 50%; position: absolute; text-align: center; top: 50%; }
.loader { position: absolute; top: 50%; left: 50%; margin: auto; text-align: center; transform: translateX(-50%) translateY(-50%); -webkit-transform: translateX(-50%) translateY(-50%); -o-transform: translateX(-50%) translateY(-50%); -ms-transform: translateX(-50%) translateY(-50%); -moz-transform: translateX(-50%) translateY(-50%); }
.loader span { width: 20px; height: 20px; background-color: #f85438; border-radius: 50%; display: inline-block; animation: motion 3s ease-in-out infinite; }
.loader p { color: #fe4c1c; margin-top: 5px; font-size: 30px; animation: shake 5s ease-in-out infinite; }


     img.img-fluid.simple-text.logo-normal {
        height: 80px !important;
        width: 88% !important;
    }
    .sidebar .logo .simple-text{

            padding: 0px 0px !important;
    }
    .sidebar[data-color="purple"] li.active>a{
            background-color: #fbca08 !important;

    }
       .btn.btn-primary{
      background-color: #fbca08 !important;
      border-color: #fbca08 !important;
    }
    .btn.btn-primary:focus, .btn.btn-primary.focus, .btn.btn-primary:hover{

       background-color: #fbca08 !important;
       border-color: #fbca08 !important;
    }
      .logo {
        background: #fff !important;
    }

    .sidebar-wrapper {
        background: #fff !important;
    }
  </style>
</head>

<body class="">
    <div id="ht-preloader">
      <div class="loader clear-loader">
        <img class="img-fluid" src="Views/assets_login/images/loader.gif" alt="">
      </div>
    </div>

  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo">

        <img class="img-fluid simple-text logo-normal" src="Others/Files_site/logo-v02.png" alt="">

        </div>

      <?php include "menu.php";?>

    </div>
    <div class="main-panel">
      <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Información de Usuario</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">

            <ul class="navbar-nav">


            </ul>
          </div>
        </div>
      </nav>

      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
          <div class="col-8 col-lg-1"></div>

           <div class="col-md-10">
            <?php
                $sqlp1="SELECT t1.name,t1.lastname,t1.phone,t1.company,t1.address,t1.towncity,t1.cities,t1.region,t1.profile,t1.cip,t2.email,t2.emailconfir FROM users_informations t1 join users_access t2 on t2.username=t1.username WHERE t1.username='$username'";
                $resulp1=$cls->consulQuery($sqlp1);

            ?>
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Información General</h4>

                </div>
                <div class="card-body">
                   <form action="#" method="post" id="formprofile" enctype="multipart/form-data" class="row">
     <input type="hidden" name="a" value="UPDATE-INFO" >

        <div class=" col-lg-7 col-md-12">
        <div class="row checkout-form box-shadow white-bg">

            <div class="col-md-6">
              <div class="form-group">
                <label>Nombre</label>
                <input type="text" id="fname" name="fname" class="form-control validate" placeholder="Nombre" value="
                <?php
                 if(isset($resulp1[0]) && $resulp1[0]!=''){echo $resulp1[0];}?>" readonly>
                <div class="help-block with-errors fname-error"></div>

              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Apellido</label>
                <input type="text" id="lname" name="lname" class="form-control validate" placeholder="Apellido" value="
                <?php
                 if(isset($resulp1[1]) && $resulp1[1]!=''){echo $resulp1[1];}?>" readonly>
                <div class="help-block with-errors lname-error"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>E-mail</label>
                <input type="text" id="email" name="email" class="form-control validate" placeholder="E-mail" value="<?php
                 if(isset($resulp1[10]) && $resulp1[10]!=''){echo $resulp1[10];}?>" readonly>
                <div class="help-block with-errors email-error"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Teléfono</label>
                <input type="text" id="phone" name="phone" class="form-control validate"  placeholder="Teléfono" value="<?php
                 if(isset($resulp1[2]) && $resulp1[2]!=''){echo $resulp1[2];}?>" readonly>
                <div class="help-block with-errors phone-error"></div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Email Confirmado</label>
                <input type="text" id="companyname" name="companyname" class="form-control" placeholder="Email Confirmado" value="<?php
                 if(isset($resulp1[11]) && $resulp1[11]!=''){echo $resulp1[11];}?>" readonly>
                <div class="help-block with-errors companyname-error"></div>
              </div>
            </div>

                <div class="col-md-12">
              <div class="form-group">
                <label>Cedula o Pasaporte (opcional)</label>
                <input type="text" id="cip" name="cip" class="form-control" placeholder="Cedula o Pasaporte (opcional)" value="<?php
                 if(isset($resulp1[9]) && $resulp1[9]!=''){echo $resulp1[9];}?>" readonly>
                <div class="help-block with-errors cip-error"></div>
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group">
                <label>Dirección</label>
                <input type="text" id="address" name="address" class="form-control validate" placeholder="Dirección" value="<?php
                 if(isset($resulp1[4]) && $resulp1[4]!=''){echo $resulp1[4];}?>" readonly>
                 <div class="help-block with-errors address-error"></div>
              </div>

            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Apartamento,suite,etc.</label>
                <input type="text" id="towncity" name="towncity" class="form-control validate" placeholder="Apartamento,suite,etc." value="<?php
                 if(isset($resulp1[5]) && $resulp1[5]!=''){echo $resulp1[5];}?>" readonly>
                 <div class="help-block with-errors towncity-error"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-md-0">
                <label>Ciudad</label>

                <select name="cities" id="cities" class="form-control validate" disabled="disabled">
                  <option value="">-Seleccione-</option>
              <?php
                $sqlc="SELECT * FROM cities WHERE status='ACTIVE'";
                $result_lisc= $cls->consultListQuery($sqlc);//query
        	    foreach($result_lisc as $itemc)
        	    {?>
        	         <option value="<?php echo $itemc->id;?>" <?php if(isset($resulp1[6]) && $resulp1[6]==$itemc->id){ echo "selected";}?>><?php echo $itemc->name;?></option>

        	    <?php }?>
                </select>
                <div class="help-block with-errors cities-error"></div>

              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-md-0">
                <label>Región</label>

                    <select name="region" id="region" class="form-control validate" disabled="disabled">
                  <option value="">-Seleccione-</option>
                      <?php
                        $sqlc="SELECT * FROM cities_region WHERE status='ACTIVE'";
                        $result_lisc= $cls->consultListQuery($sqlc);//query
                	    foreach($result_lisc as $itemc)
                	    {?>
                	         <option value="<?php echo $itemc->id;?>"  <?php if( isset($resulp1[6]) && $resulp1[7]==$itemc->id){ echo "selected";}?>><?php echo $itemc->name;?></option>

                	    <?php }?>
                </select>
                <div class="help-block with-errors region-error"></div>
              </div>
            </div>

        </div>

      </div>

     </form>
                </div>
              </div>
            </div>
          </div>



        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>

              </li>
              <li>

              </li>
              <li>

              </li>
              <li>

              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
              Copyright
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>
            olimpoathletics Todos los derechos reservados.
          </div>
        </div>
      </footer>
    </div>
  </div>

  <!--   Core JS Files   -->
  <script src="Views/assets/js/core/jquery.min.js"></script>
  <script src="Views/assets/js/core/popper.min.js"></script>
  <script src="Views/assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="Views/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="Views/assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="Views/assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="Views/assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="Views/assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="Views/assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="Views/assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <!--<script src="Views/assets/js/plugins/jquery.dataTables.min.js"></script>-->
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="Views/assets/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <!--<script src="Views/assets/js/plugins/jasny-bootstrap.min.js"></script>-->
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="Views/assets/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="Views/assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="Views/assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="Views/assets/js/plugins/arrive.min.js"></script>


  <!-- Chartist JS -->
  <script src="Views/assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="Views/assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="Views/assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="Views/assets/demo/demo.js"></script>
  <script src="Views/assets/js/admin-js.js"></script>
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();

    });
    	    $(window).on('load', function() {
         preloader();

        });
        function preloader() {
           $('#ht-preloader').fadeOut();
        };

  </script>
</body>

</html>
