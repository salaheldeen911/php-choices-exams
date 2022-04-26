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

if (isset($_GET['id'])) {
?>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Basic Tables </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php homeHref() ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Exam: <?php echo getExamBy($_GET["id"])['id']  ?></li>
                    </ol>
                </nav>
            </div>
            <div class="row">

                <div class="col-lg-12 stretch-card">
                    <div class="card">
                        <div class="card-body">

                            <div class="title">
                                <h2 style="width:fit-content" class="mt-5 pb-2 border-bottom border-primary">Exam: <?php echo getExamBy($_GET["id"])['name'] ?></h2>
                            </div>

                            <br>
                            <hr>

                            <div id="<?php echo $_GET["id"] ?>" class="container-fluid exam">

                                <?php
                                if (getQuestions($_GET["id"])) {
                                    $question = getQuestions($_GET["id"]);
                                    for ($i = 0; $i < count($question); $i++) {
                                ?>

                                        <div class="q-componant">
                                            <div class="row q-container" id="<?php echo $question[$i]['id'] ?>">
                                                <div class="col-md-12">
                                                    <strong style="color:#6963a4 !important;">
                                                        <?php echo $i + 1 . ' - ' . $question[$i]['value'] ?>
                                                    </strong>
                                                </div>
                                            </div>

                                            <div class="row a-container" data-spry="checkbox">

                                                <?php
                                                if (getQuestionChoices($question[$i]['id'])) {
                                                    $choices = getQuestionChoices($question[$i]['id']);
                                                    for ($x = 0; $x < count($choices); $x++) {
                                                ?>

                                                        <div class="col-md-4 mt-3 p-0">
                                                            <div class="container-f-center plr-5 min-h-70 m-tb-5">
                                                                <div class="container-f-center plr-5 min-h-70 m-tb-5" style="flex-grow: 1">
                                                                    <strong><?php echo $x + 1 . '-' ?></strong>
                                                                </div>
                                                                <div id="<?php echo $choices[$x]['id'] ?>" class="card container-f-center plr-5 min-h-70 m-tb-5 <?php echo isCorrect($choices[$x]['id']) ? 'correct' : '' ?>" style="flex-grow: 500;">
                                                                    <p class="p-2 w-100 m-0" style="color:#b2dece !important;"> <?php echo $choices[$x]['value'] ?> </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>

                                            <br>
                                            <hr>

                                        </div>

                            <?php
                                    }
                                }
                            }
                            ?>
                            </div>



                            <div class="container-f-space-between">
                                <a href="<?php echo $_SESSION['ROOT_URL'] . 'views/admin/home.php' ?>" class="btn btn-primary" id="goBack">Go Back</a>
                                <div id="result"></div>
                            </div>
                            <hr>

                            <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/footer.php') ?>

                            <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/bottom.php') ?>
                            