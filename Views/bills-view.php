<?php
require_once 'Config/Functions.php';
$cls = new Functions;  //llamando al objeto
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Panama');

$VAR_SESSION = Session::getInstance();
if ($VAR_SESSION->username == "" || $VAR_SESSION->loggedin != true) {

    header("Location:./");
}

$sales = "active";
$facturas = "active-sublink";

if (isset($_POST['page'])) {

    $page = $_POST['page'];

} else {
    $page = 1;
}
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
    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="Views/assets_login/images/favicon-01-ol.ico">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Factura | Cafeteria
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport'/>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="Views/assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet"/>
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="Views/assets/demo/demo.css" rel="stylesheet"/>
    <style>
        #ht-preloader {
            background: #ffffff;
            bottom: 0;
            height: 100%;
            left: 0;
            overflow: hidden !important;
            position: fixed;
            right: 0;
            text-align: center;
            top: 0;
            width: 100%;
            z-index: 99999;
        }

        .clear-loader {
            transform: translateX(-50%) translateY(-50%);
            -webkit-transform: translateX(-50%) translateY(-50%);
            -o-transform: translateX(-50%) translateY(-50%);
            -ms-transform: translateX(-50%) translateY(-50%);
            -moz-transform: translateX(-50%) translateY(-50%);
            z-index: 999;
            box-sizing: border-box;
            display: inline-block;
            left: 50%;
            position: absolute;
            text-align: center;
            top: 50%;
        }

        .loader {
            position: absolute;
            top: 50%;
            left: 50%;
            margin: auto;
            text-align: center;
            transform: translateX(-50%) translateY(-50%);
            -webkit-transform: translateX(-50%) translateY(-50%);
            -o-transform: translateX(-50%) translateY(-50%);
            -ms-transform: translateX(-50%) translateY(-50%);
            -moz-transform: translateX(-50%) translateY(-50%);
        }

        .loader span {
            width: 20px;
            height: 20px;
            background-color: #f85438;
            border-radius: 50%;
            display: inline-block;
            animation: motion 3s ease-in-out infinite;
        }

        .loader p {
            color: #fe4c1c;
            margin-top: 5px;
            font-size: 30px;
            animation: shake 5s ease-in-out infinite;
        }


        img.img-fluid.simple-text.logo-normal {
            height: 80px !important;
            width: 88% !important;
        }

        .sidebar .logo .simple-text {

            padding: 0px 0px !important;
        }

        .sidebar[data-color="purple"] li.active > a {
            background-color: #fbca08 !important;

        }

        .sidebar .nav li.active > [data-toggle="collapse"] i {
            color: #fff !important;

        }

        .sidebar .nav li.active > a, .sidebar .nav li.active > a i {
            color: #fff !important;
        }

        .btn.btn-primary {
            background-color: #fbca08 !important;
            border-color: #fbca08 !important;
        }

        .btn.btn-primary:focus, .btn.btn-primary.focus, .btn.btn-primary:hover {

            background-color: #fbca08 !important;
            border-color: #fbca08 !important;
        }

        li.nav-item.active-sublink {
            background-color: rgba(200, 200, 200, 0.2);
        }

        .btn.btn-primary.btn-link {
            color: #fff !important;
        }


        .logo {
            background: #fff !important;
        }

        .sidebar-wrapper {
            background: #fff !important;
        }

    </style>
    <script>
        if (window.history.replaceState) { // verificamos disponibilidad
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
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

        <?php include "menu.php"; ?>

    </div>
    <div class="main-panel">
        <!-- Navbar -->

        <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <a class="navbar-brand" href="javascript:;">Factura</a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
                        aria-expanded="false" aria-label="Toggle navigation">
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

                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Factura</h4>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-7">
                                        <form class="navbar-form col-md-5" action="#" method="post">
                                  <span class="bmd-form-group"><div class="input-group no-border">

                                        <input type="text" name="search"
                                               value="<?php if (isset($_POST['search']) && $_POST['search'] != "") {
                                                   echo $_POST['search'];
                                               } ?>" class="form-control" placeholder="Consultar por id...">
                                        <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                          <i class="material-icons">search</i>
                                          <div class="ripple-container"></div>
                                        </button>
                                      </div>
                                  </span>
                                        </form>
                                    </div>
                                    <div class="col-md-5">

                                        <a href="./?view=bills-create" class="btn btn-primary">
                                            Registrar Factura
                                        </a>
                                    </div>

                                </div>


                                <div class="table-responsive">
                                    <table class="table /* table-responsive*/">
                                        <thead class=" text-primary">
                                        <tr>

                                            <th>ID</th>
                                            <th>Cotización</th>
                                            <th>Fecha</th>
                                            <th>Cliente</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $filter1 = "";
                                        if (isset($_POST['search']) && $_POST['search'] != "") {
                                            $search = $_POST['search'];
                                            $filter1 = " AND t1.id = '%$search%'";
                                        }
                                        $sqlcn = "SELECT count(*) FROM bills WHERE 1=1  $filter1";

                                        $num_total_rows = $cls->consulQuery($sqlcn);

                                        $limitPageTable = 15;

                                        $num_pages = ceil($num_total_rows[0] / $limitPageTable);
                                        $from = (($page * $limitPageTable) - $limitPageTable);


                                        $sql = "SELECT t1.id, t1.quote, t1.created_at as date FROM bills t1 WHERE 1=1 $filter1 order by t1.created_at desc limit " . $from . "," . $limitPageTable;


                                        $result_lis = $cls->consultListQuery($sql);//query
                                        $rows = 0;
                                        foreach ($result_lis as $item) {
                                            $rows++; ?>

                                            <tr>


                                                <td><?php echo $item->id; ?></td>
                                                <td><?php echo $item->quote; ?></td>


                                                <td><?php echo $item->date; ?></td>


                                                <td class="td-actions">


                                                    <a href="./?view=purchase-order-edit&purchase_order_id=<?php echo $item->id; ?>"
                                                       class="btn btn-primary btn-link btn-sm" data-dismiss="fileinput"><i
                                                            class="material-icons">edit</i>
                                                        <div class="ripple-container"></div>
                                                    </a>

                                                </td>
                                            </tr>

                                        <?php } ?>

                                        <?php
                                        if ($rows == 0) {
                                            ?>
                                            <tr>
                                                <td colspan="6">
                                                    No se encontraron resultados!.

                                                </td>

                                            </tr>
                                        <?php } ?>

                                        <tr>
                                            <td colspan="6">
                                                <form id="form-filter" method="post">
                                                    <input type="hidden" name="page" value="<?php echo $page; ?>"
                                                           id="page">
                                                    <nav aria-label="Page navigation" class="mt-8">
                                                        <ul class="pagination">
                                                            <li class="page-item"><a
                                                                    class="page-link  <?php if ($num_pages > 1 && $page > 1) {
                                                                        echo "prev-page";
                                                                    } ?>" href="#">Previous</a></li>
                                                            <?php

                                                            $limitleft = 0;
                                                            $limitright1 = $num_pages - 3;
                                                            $center = 0;

                                                            for ($i = $page; $i <= $num_pages; $i++) {

                                                                if ($i == $page && $page >= ($num_pages - 3) && $num_pages >= 5) {
                                                                    ?>
                                                                    <li class="page-item"><a class="page-link" href="#">1</a>
                                                                    </li>
                                                                    <li class="page-item"><a class="page-link" href="#">... </a>
                                                                    </li>
                                                                <?php }
                                                                if ($i == $page) {
                                                                    ?>

                                                                    <li class="page-item active"><a class="page-link"
                                                                                                    href="#"><?php echo $page ?></a>
                                                                    </li>

                                                                <?php }

                                                                if ($limitleft < 3 && $i > $page) {
                                                                    $limitleft++; ?>
                                                                    <li class="page-item"><a class="page-link"
                                                                                             href="#"><?php echo $i; ?></a>
                                                                    </li>
                                                                <?php }


                                                                if ($i == $num_pages && $page < ($num_pages - 3)) {
                                                                    ?>
                                                                    <li class="page-item"><a class="page-link" href="#">... </a>
                                                                    </li>
                                                                    <li class="page-item"><a class="page-link"
                                                                                             href="#"><?php echo $num_pages; ?></a>
                                                                    </li>

                                                                <?php }


                                                            } ?>


                                                            <li class="page-item"><a
                                                                    class="page-link  <?php if ($num_pages > 1 && $page >= 1 && $page <= $num_pages) {
                                                                        echo "next-page";
                                                                    } ?>" href="#">Next</a></li>

                                                        </ul>
                                                    </nav>
                                                </form>
                                                <!--paginacion-->

                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
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
                    Cafeteria CRUV Todos los derechos reservados.
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
<script src="Views/assets/js/admin-js.js"></script>


<!-- Chartist JS -->
<script src="Views/assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="Views/assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="Views/assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="Views/assets/demo/demo.js"></script>

<script>
    $(document).ready(function () {
        $().ready(function () {
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

            $('.fixed-plugin a').click(function (event) {
                // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
                if ($(this).hasClass('switch-trigger')) {
                    if (event.stopPropagation) {
                        event.stopPropagation();
                    } else if (window.event) {
                        window.event.cancelBubble = true;
                    }
                }
            });

            $('.fixed-plugin .active-color span').click(function () {
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

            $('.fixed-plugin .background-color .badge').click(function () {
                $(this).siblings().removeClass('active');
                $(this).addClass('active');

                var new_color = $(this).data('background-color');

                if ($sidebar.length != 0) {
                    $sidebar.attr('data-background-color', new_color);
                }
            });

            $('.fixed-plugin .img-holder').click(function () {
                $full_page_background = $('.full-page-background');

                $(this).parent('li').siblings().removeClass('active');
                $(this).parent('li').addClass('active');


                var new_image = $(this).find("img").attr('src');

                if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                    $sidebar_img_container.fadeOut('fast', function () {
                        $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                        $sidebar_img_container.fadeIn('fast');
                    });
                }

                if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                    var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

                    $full_page_background.fadeOut('fast', function () {
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

            $('.switch-sidebar-image input').change(function () {
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

            $('.switch-sidebar-mini input').change(function () {
                $body = $('body');

                $input = $(this);

                if (md.misc.sidebar_mini_active == true) {
                    $('body').removeClass('sidebar-mini');
                    md.misc.sidebar_mini_active = false;

                    $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

                } else {

                    $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

                    setTimeout(function () {
                        $('body').addClass('sidebar-mini');

                        md.misc.sidebar_mini_active = true;
                    }, 300);
                }

                // we simulate the window Resize so the charts will get updated in realtime.
                var simulateWindowResize = setInterval(function () {
                    window.dispatchEvent(new Event('resize'));
                }, 180);

                // we stop the simulation of Window Resize after the animations are completed
                setTimeout(function () {
                    clearInterval(simulateWindowResize);
                }, 1000);

            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Javascript method's body can be found in assets/js/demos.js
        md.initDashboardPageCharts();

    });
    $(window).on('load', function () {
        preloader();

    });

    function preloader() {
        $('#ht-preloader').fadeOut();
    };


</script>
</body>

</html>
