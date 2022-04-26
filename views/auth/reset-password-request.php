<?php

require_once('../../configs/baseConfig.php');
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

if (isLoggedIn()) {
	if (isAdmin()) {
		header('location:' . $_SESSION['ROOT_URL'] . 'views/admin/home.php');
		exit();
	}

	header('location:' . $_SESSION['ROOT_URL'] . 'views/user/home.php');
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Password Reset Request</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

	<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['ROOT_URL'] .  'resources/css/login.css' ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['ROOT_URL'] .  'resources/css/style.css' ?>">
</head>

<body>

	<div class="limiter">
		<h1 style="color:blue;background:black;text-align:center;padding-top:30px;min-height: 5vh;">Exams</h1>
		<div class="container-login100">
			<div class="wrap-login100" style="width:550px">
				<form id="login" class="login100-form validate-form" method="POST" action="<?= $_SESSION['ROOT_URL'] ?>controller/authentication/reset-password-request.php">

					<?php
					if (isset($_GET['errors'])) {
						$errors = json_decode($_GET['errors']);
						display_error();
					}

					if (isset($_GET['success'])) {
						show_success_msg($_GET['success']);
					}
					?>

					<span class="login100-form-title p-b-26 pb-2">
						Reset Password Via Email
					</span>
					<span class="login100-form-title p-b-48">
						<i class="zmdi zmdi-font"></i>
					</span>

					<div class="wrap-input100 validate-input" style="margin-bottom:10px" data-validate="Valid email is: a@b.co">
						<input class="input100" type="text" name="email">
						<span class="focus-input100" data-placeholder="Email"></span>
					</div>

					<div class="text-center p-t-115">
						<span class="txt1">
							You Will Receive An Email To Reset Your Password
						</span>

					</div>


					<div class="container-login100-form-btn" style="margin-bottom:10px">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button type="submit" class="login100-form-btn" style="letter-spacing: 2px;" name="reset-password-submit">
								Reset Password Via Email
							</button>
						</div>
					</div>

					<div class="text-center p-t-115">
						<span class="txt1">
							Don’t have an account?
						</span>

						<a class="txt2" href="<?= $_SESSION['ROOT_URL'] . 'views/auth/register.php' ?>">
							Sign Up
						</a>
						<hr>
						<span class="txt1">
							Already have an account?
						</span>

						<a class="txt2" href="<?= $_SESSION['ROOT_URL'] ?>">
							Login
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/jquery.3.5.1.min.js' ?>"></script>

	<script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/bootstrap.bundle.min.js' ?>"></script>

	<script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>

	<script>
		$(document).ready(function() {
			$('.input100').each(function() {
				if ($(this).val() !== "") {
					$(this).addClass('has-val');
				}
			})
		});

		$('.input100').each(function() {
			$(this).on('blur', function() {
				if ($(this).val().trim() != "") {
					$(this).addClass('has-val');
				} else {
					$(this).removeClass('has-val');
				}
			})
		})

		var showPass = 0;
		$('.btn-show-pass').on('click', function() {
			if (showPass == 0) {
				$(this).next('input').attr('type', 'text');
				$(this).find('i').removeClass('zmdi-eye');
				$(this).find('i').addClass('zmdi-eye-off');
				showPass = 1;
			} else {
				$(this).next('input').attr('type', 'password');
				$(this).find('i').addClass('zmdi-eye');
				$(this).find('i').removeClass('zmdi-eye-off');
				showPass = 0;
			}

		});

		var input = $('#login .input100');

		$('#login').on('submit', function() {
			var check = true;

			for (var i = 0; i < input.length; i++) {
				if (validate(input[i]) == false) {
					showValidate(input[i]);
					check = false;
				}
			}

			return check;
		});

		$('#login .input100').each(function() {
			$(this).focus(function() {
				hideValidate(this);
			});
		});

		function validate(input) {
			if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
				if (!$(input).val().trim().match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
					return false;
				}
			} else {
				if ($(input).val().trim() == '') {
					return false;
				}
			}
		}

		function showValidate(input) {
			var thisAlert = $(input).parent();

			$(thisAlert).addClass('alert-validate');
		}

		function hideValidate(input) {
			var thisAlert = $(input).parent();

			$(thisAlert).removeClass('alert-validate');
		}
	</script>
</body>

</html>