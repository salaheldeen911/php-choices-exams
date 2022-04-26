<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

if (isset($_POST['reset-password-submit'])) {
	changePassword();
} else{
    header('Location: ' . $_SESSION['ROOT_URL']);
}

function changePassword () {
    global $db, $errors, $username, $email;

    $selector = $_POST['selector'];
    $validator = $_POST['validator'];
    $password = $_POST['password_1'];
    $passwordConfirm = $_POST['password_2'];

    if (empty($password) || empty($passwordConfirm) || $password !== $passwordConfirm) {
        array_push($errors, "Password erorr");

        $err = json_encode($errors);

        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
    }

    $currentDate = date("U");

    $sql = "SELECT * FROM reset_password WHERE reset_password_selector = ? AND reset_password_expires >= ?";
    $stmt = mysqli_stmt_init($db);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        array_push($errors, "There was an error with your request");
        $err = json_encode($errors);
        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
        exit();

    } else {
        mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (!$row = mysqli_fetch_assoc($result)) {
            array_push($errors, "You need to re-submit your reset request");
            $err = json_encode($errors);
            header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
            exit();

        } else {
            $tokenBin = hex2bin($validator);
            $tokenCheck = password_verify($tokenBin, $row['reset_password_token']);

            if ($tokenCheck === false) {
                array_push($errors, "You need to re-submit your reset request");
                $err = json_encode($errors);
                header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
                exit();

            } elseif ($tokenCheck === true) {
                $tokenEmail = $row['reset_password_email'];

                $sql = "SELECT * FROM users WHERE email = ?;";
                $stmt = mysqli_stmt_init($db);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    array_push($errors, "There was an error with your request 1");
                    $err = json_encode($errors);
                    header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (!$row = mysqli_fetch_assoc($result)) {
                        array_push($errors, "There was an error with your request 2");
                        $err = json_encode($errors);
                        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
                        exit();
                    } else {

                        $sql = "UPDATE users SET password = ? WHERE email = ?;";
                        $stmt = mysqli_stmt_init($db);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            array_push($errors, "There was an error with your request 3");
                            $err = json_encode($errors);
                            header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
                            exit();
                        } else {
                            $newPasswordHash = md5($password);
                            mysqli_stmt_bind_param($stmt, "ss", $newPasswordHash, $tokenEmail);
                            mysqli_stmt_execute($stmt);


                            $sql = "DELETE FROM reset_password WHERE reset_password_email = ?;";
                            $stmt = mysqli_stmt_init($db);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                array_push($errors, "There was an error with your request 4");
                                $err = json_encode($errors);
                                header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
                                exit();
                            } else {
                                mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                mysqli_stmt_execute($stmt);

                                $msg = "Your password has been updated please login with your new password.";

                                mysqli_stmt_close($stmt);
                                header('location:' . $_SESSION['ROOT_URL'] . "?success=$msg");
                                exit();
                            }
                        }
                    }
                }
            }
        }
    }
}