<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login(){
	global $db, $email, $errors;

	// grap form values
	$email = e($_POST['email']);
	$password = e($_POST['password']);

	// make sure form is filled properly
	if (empty($email)) {
		array_push($errors, "email is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	if (!$errors) {
		$password = md5($password);
		$query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { 
			$_SESSION['user'] = mysqli_fetch_assoc($results);

			if ($_SESSION['user']['is_admin'] == 1) {
				header('location:' . $_SESSION['ROOT_URL'] . 'views/admin/home.php');
				exit();
			}else{
				header('location:' . $_SESSION['ROOT_URL'] . 'views/user/home.php');
				exit();
			}
		}else {
			array_push($errors, "Wrong username/password combination");

			$err = json_encode($errors);
			header('location: ' . $_SESSION['ROOT_URL'] . "?errors=$err");
			exit();
		}
	}else{
		$err = json_encode($errors);
        header('location: ' . $_SESSION['ROOT_URL'] . "?errors=$err");
		exit();
    }
}
