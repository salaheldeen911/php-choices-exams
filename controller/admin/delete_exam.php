<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');
if(isset($_POST['id'])){
    $id = $_POST['id'];
    $query = "DELETE FROM exams WHERE id=$id";
    if (mysqli_query($db, $query)) {
        header('location:' . $_SESSION['ROOT_URL'] . 'views/admin/home.php');
		exit();
    }
    die("Somethin went wrong!!"); 
}



