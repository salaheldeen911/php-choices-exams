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
            <h3 class="page-title"> Create Exam </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php homeHref() ?>"> Home </a></li>
                    <li class="breadcrumb-item active" aria-current="page"> Exam Preparation </li>
                </ol>
            </nav>
        </div>
        <div class="row">

            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">


                        <div class="title mb-4">
                            <h2>Exam Preparation</h2>
                        </div>
                        <form id="prepareExam" method="post" action="<?= $_SESSION['ROOT_URL'] . 'controller/admin/Create_exam.php' ?>">
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
                                <label for="materialName">Material Name</label>
                                <input type="text" name="materialName" class="form-control choise-textarea" id="materialName" data-spry='none' aria-describedby="materialName" placeholder="Material Name">
                            </div>
                            <div class="form-group">
                                <label for="examName">Exam Name</label>
                                <input type="text" name="examName" class="form-control choise-textarea" id="examName" aria-describedby="examName" data-spry='none' placeholder="Exam Name">
                            </div>
                            <div class="form-group">
                                <label for="questionsNumber">Number Of Questions</label>
                                <input type="number" name="questionsNumber" class="form-control choise-textarea" id="questionsNumber" data-spry='integer' placeholder="Number Of Questions">
                            </div>
                            <input type="text" name="teacherId" style="visibility:hidden; height:0; display:block;" value="<?php echo $_SESSION["user"]["id"]  ?>">
                            <hr>
                            <input type="submit" class="btn btn-primary" name="prepareExam" value="Continue">
                            <hr>
                        </form>

                        <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/footer.php') ?>

                        <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/bootstrap.bundle.min.js' ?>"></script>

                        <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/spryValidation.min.js' ?>"></script>
                        <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/spryValidator-V1.js' ?>"></script>

                        <script>
                            $("#prepareExam").spryValidator({
                                none: {
                                    isRequired: true,
                                },
                                integer: {
                                    isRequired: true,
                                },
                            })
                        </script>

                        <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/bottom.php') ?>
                        