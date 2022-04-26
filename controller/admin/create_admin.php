<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

// call the register() function if register_btn is clicked
if (isset($_POST['create_admin']) && isset($_POST['is_admin'])) {
	createAdmin();
}
function createAdmin()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $username, $email;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $username    =  e($_POST['username']);
    $email       =  e($_POST['email']);
    $password_1  =  e($_POST['password_1']);
    $password_2  =  e($_POST['password_2']);
    $is_admin    =  e($_POST['is_admin']);

    // form validation: ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Username is required.");
    }
    if (empty($email)) {
        array_push($errors, "Email is required.");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required.");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match.");
    }
    if (empty($is_admin) || (int)$is_admin !== 1) {
        array_push($errors, "failed");
    }

            
    if (count($errors) == 0) {
        
        $password = md5($password_1); 

        $query = "INSERT INTO users (username, email, password, is_admin) 
                    VALUES( ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sssi", $username, $email, $password, $is_admin);
        
        if($stmt->execute()){

            header('location:' . $_SESSION['ROOT_URL'] . 'views/admin/create_admin.php?success="New admin created successfully !!"');
            exit();
        } else {
            array_push($errors, "Failed to create new admin !!. Check if the email is already taken or contact the developers' team.");

            $err = json_encode($errors);
            header('location:'. $_SESSION['ROOT_URL'] . "views/admin/create_admin.php?errors=$err");
            exit();
        }
    }else{
		$err = json_encode($errors);
        header('location:'. $_SESSION['ROOT_URL'] . "views/admin/create_admin.php?errors=$err");
        exit();
    }
}

?>