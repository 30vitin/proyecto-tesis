

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Cafeteria | CRUV</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->

    <?php include "favicon.php"; ?>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="Views/assets_login/vendor/bootstrap/css/bootstrap.min.css">

	<link rel="stylesheet" type="text/css" href="Views/assets_login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="Views/assets_login/css/util.css">
	<link rel="stylesheet" type="text/css" href="Views/assets_login/css/main.css?v=2">
<!--===============================================================================================-->
<style>
    #ht-preloader { background: #ffffff; bottom: 0; height: 100%; left: 0; overflow: hidden !important; position: fixed; right: 0; text-align: center; top: 0; width: 100%; z-index: 99999; }
.clear-loader { transform: translateX(-50%) translateY(-50%); -webkit-transform: translateX(-50%) translateY(-50%); -o-transform: translateX(-50%) translateY(-50%); -ms-transform: translateX(-50%) translateY(-50%); -moz-transform: translateX(-50%) translateY(-50%); z-index: 999; box-sizing: border-box; display: inline-block; left: 50%; position: absolute; text-align: center; top: 50%; }
.loader { position: absolute; top: 50%; left: 50%; margin: auto; text-align: center; transform: translateX(-50%) translateY(-50%); -webkit-transform: translateX(-50%) translateY(-50%); -o-transform: translateX(-50%) translateY(-50%); -ms-transform: translateX(-50%) translateY(-50%); -moz-transform: translateX(-50%) translateY(-50%); }
.loader span { width: 20px; height: 20px; background-color: #f85438; border-radius: 50%; display: inline-block; animation: motion 3s ease-in-out infinite; }
.loader p { color: #fe4c1c; margin-top: 5px; font-size: 30px; animation: shake 5s ease-in-out infinite; }

.login100-form-btn{
        background: #1F618D !important;
    -webkit-linear-gradient(left, #fbca08, #fbca08 , #fbca08 ) !important;
}


</style>
</head>
<body>

<?php include 'loader.php'; ?>

	<div class="limiter">
		<div class="container-login100" >
			<div class="wrap-login100 p-t-30 p-b-50">
				<span class="login100-form-title p-3">
					Gestión de Pedidos - Cafeteria CRUV
				</span>
				<form class="login100-form validate-form p-b-33 p-t-5" id="formlogin"  >

					<div class="wrap-input100 ">
						<input class="input100" type="text" name="username" placeholder="Usuario" id="username">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

					<div class="wrap-input100 " >
						<input class="input100" type="password" name="pass" placeholder="Contraseña" id="password">
						<span class="focus-input100" data-placeholder="&#xe80f;"></span>
					</div>

                    <div class="container-login100-form-btn m-t-32" >

						<span style="color:red;" class="error-show"></span>
					</div>


					<div class="container-login100-form-btn m-t-32">
						<button class="login100-form-btn bnt-login" >
							Acceder
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>


	<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
	<script src="Views/assets_login/vendor/jquery/jquery-3.2.1.min.js"></script>

<!--===============================================================================================-->
	<script src="Views/assets_login/vendor/bootstrap/js/popper.js"></script>
	<script src="Views/assets_login/vendor/bootstrap/js/bootstrap.min.js"></script>

<!--===============================================================================================-->
	<script src="Views/assets_login/js/main.js?id=23"></script>
	<script>
	    $(window).on('load', function() {
         preloader();

        });
        function preloader() {
           $('#ht-preloader').fadeOut();
        };

	</script>

</body>
</html>
