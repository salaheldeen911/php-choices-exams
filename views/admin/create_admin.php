<?php
require_once(__DIR__ . '/../layouts/head.php');

if (!isLoggedIn() || !isAdmin()) {
    header('location:' . $_SESSION['ROOT_URL'] . 'views/auth/login.php');
    exit();
}
if (!isAdmin()) {
    header('location:' . $_SESSION['ROOT_URL'] . 'views/auth/login.php');
    exit();
}

require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/header.php');
?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Create Admin </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php homeHref() ?>"> Home </a></li>
                    <li class="breadcrumb-item active" aria-current="page"> Create admin </li>
                </ol>
            </nav>
        </div>
        <div class="row">

            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">

                        <form id="create_admin" method="POST" action="<?= $_SESSION['ROOT_URL'] . 'controller/admin/create_admin.php' ?>">
                            <?php
                            if (isset($_GET['errors'])) {
                                $errors = json_decode($_GET['errors']);

                                display_error();
                            }

                            if (isset($_GET['success'])) {
                                show_success_msg($_GET['success']);
                            }
                            ?>
                            <div class="form-group">
                                <label for="materialName">Username</label>
                                <input type="text" name="username" class="form-control choise-textarea" data-spry='username' aria-describedby="Admin name" placeholder="Admin Name">
                            </div>
                            <div class="form-group">
                                <label for="examName">Email</label>
                                <input type="text" name="email" class="form-control choise-textarea" aria-describedby="email" data-spry='email' placeholder="Email">
                            </div>

                            <div class="form-group position-relative">
                                <label for="examName">Password</label>
                                <i role="button" class="btn-show mdi mdi-eye position-absolute"></i>
                                <input type="password" name="password_1" class="form-control choise-textarea" id="password" aria-describedby="password" data-spry='password' placeholder="Password">
                            </div>

                            <div class="form-group position-relative">
                                <label for="examName">Password Confirmation</label>
                                <i role="button" class="btn-show mdi mdi-eye position-absolute"></i>
                                <input type="password" name="password_2" class="form-control choise-textarea" aria-describedby="Password Confirmation" data-spry='confirm' placeholder="Password Confirmation">
                            </div>

                            <div class="none">
                                <input type="hidden" value="1" name="is_admin">
                            </div>
                            <hr>
                            <input type="submit" class="btn btn-primary" name="create_admin" value="Create Admin">
                            <hr>
                        </form>

                        <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/footer.php') ?>

                        <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/bootstrap.bundle.min.js' ?>"></script>

                        <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/spryValidation.min.js' ?>"></script>
                        <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/spryValidator-V1.js' ?>"></script>

                        <script>
                            $("#create_admin").spryValidator({
                                username: {
                                    isRequired: true,
                                },
                                password: {
                                    isRequired: true,
                                },
                                email: {
                                    isRequired: true,
                                },
                                confirm: {
                                    isRequired: true,
                                }
                            })

                            $('.btn-show').on('click', function() {
                                $(this).toggleClass("show-active");
                                if ($(this).hasClass("show-active")) {
                                    console.log($(this).next().children("input"));
                                    $(this).next().children("input").attr("type", "text");
                                } else {
                                    $(this).next().children("input").attr("type", "password");
                                }
                            });
                        </script>

                        <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/bottom.php') ?>
                        