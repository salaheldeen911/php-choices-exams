<?php
session_start();
if (isset($_GET['logout'])) {
	unset($_SESSION['user']);
	session_destroy();
	header('location:' . $_SESSION['ROOT_URL']);
}
