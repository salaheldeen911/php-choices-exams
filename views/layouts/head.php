<?php
require_once(__DIR__ . '/../../configs/baseConfig.php');
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php title(basename($_SERVER['PHP_SELF']));  ?></title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['ROOT_URL'] .  'resources/css/spryValidator-V1.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['ROOT_URL'] .  'resources/icons/css/materialdesignicons.min.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['ROOT_URL'] .  'resources/css/vendor.bundle.base.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['ROOT_URL'] .  'resources/css/general_style.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['ROOT_URL'] .  'resources/css/style.css' ?>">

</head>
