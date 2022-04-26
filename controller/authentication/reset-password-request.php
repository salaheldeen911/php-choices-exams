<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

require_once($_SESSION['ROOT_SERVER'] . 'controller/mail/Exception.php');
require_once($_SESSION['ROOT_SERVER'] . 'controller/mail/PHPMailer.php');
require_once($_SESSION['ROOT_SERVER'] . 'controller/mail/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// call the register() function if register_btn is clicked
if (isset($_POST['reset-password-submit'])) {
    send_email();
} else {
    header('Location: ' . $_SESSION['ROOT_URL']);
}
function send_email()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $email;

    $email =  e($_POST['email']);

    if (empty($email)) {
        array_push($errors, "Email is required");
        $err = json_encode($errors);

        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
        exit();
    }

    $q = "SELECT email FROM users WHERE email = '$email'";

    if (mysqli_query($db, $q)->num_rows == 0) {
        array_push($errors, "This email does not exist in our database");

        $err = json_encode($errors);

        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
        exit();
    }

    $selector  = bin2hex(random_bytes(8));
    $token  = random_bytes(32);

    $url  = 'localhost/php-for-job2/views/auth/create-new-password.php?selector=' . $selector . "&validator=" . bin2hex($token);

    $expires = date("U") + 1800;


    $sql = "DELETE FROM reset_password WHERE reset_password_email = ?";
    $stmt = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        array_push($errors, "There was an error with your request");

        $err = json_encode($errors);

        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
    }

    $sql = "INSERT INTO reset_password (reset_password_email, reset_password_selector, reset_password_token, reset_password_expires) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        array_push($errors, "There was an error with your request");

        $err = json_encode($errors);

        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
        exit();
    } else {
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $email, $selector, $hashedToken, $expires);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "salah.eldeen.mail@gmail.com";
    $mail->Password = "YOUR_PASSWORD";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->Subject = "Reset your password for the Exam website";
    $mail->setFrom($email);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Body = "<p>We received a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email</p>";
    $mail->Body .= "<p>Here is your password reset link: </br>";
    $mail->Body .= '<a href="' . $url . '">Change Password</a></p>';

    if (!$mail->send()) {
        array_push($errors, "There was an error sending your email");

        $err = json_encode($errors);

        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?errors=$err");
        exit();
    } else {
        $mail->smtpClose();

        $msg = "Reset password email has been sent successfully check your mail inbox now !!";
        header('location:' . $_SESSION['ROOT_URL'] . "views/auth/reset-password-request.php?success=$msg");
        exit();
    }

}
