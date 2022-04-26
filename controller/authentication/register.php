<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}
function register()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $username, $email;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $username    =  e($_POST['username']);
    $email       =  e($_POST['email']);
    $password_1  =  e($_POST['password_1']);
    $password_2  =  e($_POST['password_2']);

    // form validation: ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    // register user if there are no errors in the form
            
    if (count($errors) == 0) {
        
        $password = md5($password_1); //encrypt the password before saving in the database

        $query = "INSERT INTO users (username, email, password) 
                    VALUES(?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $username, $email, $password);
        
        if($stmt->execute()){

            $logged_in_user_id = mysqli_insert_id($db);
            $_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session

            header('location:'. $_SESSION['ROOT_URL'] . 'index.php');
            exit();
        } else {
            $url = $_SESSION['ROOT_URL'];
            array_push($errors, "Something went wrong!!. If you are current user please <strong><a href='$url'>Login</a></strong> insted.");

            $err = json_encode($errors);
            header('location:'. $_SESSION['ROOT_URL'] . "views/auth/register.php?errors=$err");
            exit();
        }
    }else{
		$err = json_encode($errors);
        header('location: ' . $_SESSION['ROOT_URL'] . "views/auth/register.php?errors=$err");
		exit();
    }
}

