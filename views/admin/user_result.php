<?php
require_once(__DIR__ . '/../layouts/head.php');

if (!isLoggedIn() || !isAdmin()) {
    header('location:' . $_SESSION['ROOT_URL']);
    exit();
}

if (!isset($_GET["id"]) || !isset($_GET["user_id"]) || !getResults($_GET["id"], $_GET["user_id"])) {
    die('<strong style="color:red;"> Wrong URL <a href="' . $_SESSION['ROOT_URL'] . 'views/admin/results.php">Go back</a>. Or call the site adminstrator.</strong>');
}

require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/header.php');
?>


<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Exam: <?php echo getExamBy($_GET["id"])['name'] ?> </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php homeHref() ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Exam: <?php echo $_GET["id"] ?> for User: <?php echo $_GET["user_id"] ?></li>
                </ol>
            </nav>
        </div>
        <div class="row">

            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h2 class="card-title">Exam: <?php echo getExamBy($_GET["id"])['name'] ?></h2>


                        <div id="<?php echo $_GET["id"] ?>" class="container-fluid exam">

                            <?php
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
                                        $answers = getQuestionChoices($question[$i]['id']);
                                        for ($x = 0; $x < count($answers); $x++) {
                                        ?>

                                            <div class="col-md-4 mt-3 p-0">
                                                <div class="container-f-center min-h-70 m-tb-5">
                                                    <div class="container-f-center min-h-70 m-tb-5" style="flex-grow: 1">
                                                        <strong><?php echo $x + 1 . '-' ?></strong>
                                                    </div>
                                                    <div id="<?php echo $answers[$x]['id'] ?>" class="card container-f-center min-h-70 m-2" style="flex-grow: 500">
                                                        <p style="color:#b2dece !important;" class="p-2 w-100"> <?php echo $answers[$x]['value'] ?> </p>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                        }
                                        ?>
                                    </div>

                                    <br>
                                    <hr>

                                </div>

                            <?php
                            }
                            ?>
                        </div>

                        <div class="container-f-space-between">
                            <a href="<?php echo $_SESSION['ROOT_URL'] . 'views/admin/results.php' ?>" class="btn btn-primary" id="goBack">Go Back</a>
                            <div id="result"></div>
                        </div>
                        <hr>

                        <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/footer.php') ?>

                        <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/pureknob.js' ?>"></script>

                        <script>
                            $(document).ready(function() {
                                $("body, html").animate({
                                    scrollTop: $(document).height()
                                }, 200);

                                $.ajax({
                                    url: "<?php echo $_SESSION['ROOT_URL'] . 'controller/admin/user_result.php' ?>",
                                    dataType: 'json',
                                    type: "POST",
                                    data: {
                                        "exam_id": <?php echo $_GET["id"] ?>,
                                        "user_id": <?php echo $_GET["user_id"] ?>,
                                        "get_results": true
                                    },
                                    success: function(resultBack) {
                                        let total = $('.q-componant').length;
                                        let correctAnswers = 0;

                                        for (let result of resultBack) {

                                            if (result.answer_id == result.correct_answer) {
                                                correctAnswers += 1;
                                            }

                                            if (result.answer_id == result.correct_answer) {
                                                $(`#${result.answer_id}`).addClass('correct');
                                            } else {
                                                $(`#${result.correct_answer}`).addClass('correct');
                                                $(`#${result.answer_id}`).addClass('wrong');
                                            }

                                        }

                                        let percentage = Math.round(correctAnswers / total * 100);

                                        knobAnimation(percentage);

                                    },
                                    error: function(xhr, ajaxOptions, thrownerror) {
                                        console.log(thrownerror);
                                    }

                                })
                            })

                            var result = pureknob.createKnob(75, 75);
                            result.setProperty("readonly", true);
                            result.setProperty("valMin", 0);
                            result.setProperty("valMax", 100);
                            result.setProperty("val", 0);
                            result.setProperty("label", "result");
                            result.setProperty("textScale", 1.5);

                            var node = result.node();
                            var elem = document.getElementById('result');
                            elem.appendChild(node);

                            function sleep(ms) {
                                return new Promise(resolve => setTimeout(resolve, ms));
                            }

                            function perc2color(perc, min, max) {
                                var base = (max - min);

                                if (base == 0) {
                                    perc = 100;
                                } else {
                                    perc = (perc - min) / base * 100;
                                }
                                var r, g, b = 0;
                                if (perc < 50) {
                                    r = 255;
                                    g = Math.round(5.1 * perc);
                                } else {
                                    g = 255;
                                    r = Math.round(510 - 5.10 * perc);
                                }
                                var h = r * 0x10000 + g * 0x100 + b * 0x1;
                                return '#' + ('000000' + h.toString(16)).slice(-6);
                            }

                            async function knobAnimation(result2) {
                                for (let i = 0; i <= result2; i++) {
                                    result.setValue(i);
                                    result.setProperty("colorFG", perc2color(i, 0, 100));

                                    await sleep(20);
                                }
                            }
                        </script>

                        <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/bottom.php') ?>
                        