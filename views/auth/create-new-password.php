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

$slector = $_GET['selector'];
$validator = $_GET['validator'];

if (empty($slector) || empty($validator)) {
    array_push($errors, "Sorry, your link is invalid");

    $err = json_encode($errors);

    header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
    exit();
} else {
    if (ctype_xdigit($slector) == false && ctype_xdigit($validator) == false) {
        array_push($errors, "Sorry, your link is invalid");

        $err = json_encode($errors);

        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create New Password</title>
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
                <form id="login" class="login100-form validate-form" method="POST" action="<?= $_SESSION['ROOT_URL'] ?>controller/authentication/reset-password.php">

                    <span class="login100-form-title p-b-26 pb-2">
                        Change Password
                    </span>
                    <span class="login100-form-title p-b-48">
                        <i class="zmdi zmdi-font"></i>
                    </span>

                    <input type="hidden" name="validator" value="<?php echo $validator ?>">

                    <input type="hidden" name="selector" value="<?php echo $slector ?>">

                    <div class="wrap-input100 validate-input" data-validate="Password is require">
                        <span class="btn-show-pass">
                            <span class="iconify" data-icon="zmdi:eye"></span>
                        </span>
                        <input class="input100" id="password" type="password" name="password_1" data-spry='password'>
                        <span class="focus-input100" data-placeholder="Password"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password confirmation faild">
                        <span class="btn-show-pass">
                            <span class="iconify" data-icon="zmdi:eye"></span>
                        </span>
                        <input class="input100" type="password" name="password_2" data-type='confirm'>
                        <span class="focus-input100" data-placeholder="Password Confirm"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button type="submit" class="login100-form-btn" name="reset-password-submit">
                                CHANGE PASSWORD
                            </button>
                        </div>
                    </div>

                    <div class="text-center p-t-115">
                        <span class="txt1">
                            You don't know why you are here?
                        </span>

                        <a class="txt2" href="<?= $_SESSION['ROOT_URL'] ?>">
                            Go Back
                        </a>

                        <br>
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
            if ($(input).val().trim() == '') {
                return false;
            } else if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
                if (!$(input).val().trim().match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
                    return false;
                }
            } else if ($(input).data('type') == 'confirm') {
                if ($(input).val() !== $('#password').val()) {
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