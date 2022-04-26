<?php
require_once(__DIR__ . '/../layouts/head.php');

if (!isLoggedIn() || isAdmin()) {
  header('location:' . $_SESSION['ROOT_URL']);
  exit();
}
if (isset($_GET["id"])) {
  if (getResults($_GET["id"], $_SESSION['user']['id'])) {

    $results = json_encode(getResults($_GET["id"], $_SESSION['user']['id']));
    $id = $_GET["id"];
    header("location:" . $_SESSION['ROOT_URL'] . "views/user/results.php?id=$id");
    exit();
  }

  require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/header.php');
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

              <h2 class="card-title">Exam: <?php echo getExamBy($_GET["id"])['name'] ?></h2>

              <div id="<?php echo $_GET["id"] ?>" class="container-fluid exam">

                <?php
                if (getQuestions($_GET["id"])) {
                  $question = getQuestions($_GET["id"]);
                  for ($i = 0; $i < count($question); $i++) {
                ?>
                    <div class="q-componant">
                      <div class="row q-container" id="<?php echo $question[$i]['id'] ?>">
                        <div class="col-md-12">
                          <strong>
                            <?php echo $i + 1 . ' - ' ?>
                          </strong>
                          <strong style="color: #6963a4">
                            <?php echo $question[$i]['value'] ?>
                          </strong>
                        </div>
                      </div>

                      <div class="row a-container" data-spry="checkbox">

                        <?php
                        if (getQuestionChoices($question[$i]['id'])) {
                          $answers = getQuestionChoices($question[$i]['id']);
                          for ($x = 0; $x < count($answers); $x++) {
                        ?>

                            <div class="col-md-4 mt-3 p-0">
                              <div class="container-f-center min-h-70 m-1">
                                <div class="container-f-center min-h-70" style="flex-grow: 1">
                                  <strong><?php echo $x + 1 . '-' ?></strong>
                                </div>
                                <div data-id="<?php echo $answers[$x]['id'] ?>" class="card container-f-center min-h-70 m-2" style="flex-grow: 500">
                                  <p style="color: #b2dece" class="p-2 w-100"> <?php echo $answers[$x]['value'] ?> </p>
                                </div>
                                <div class="container-f-center min-h-70 m-1" style="flex-grow: 1">
                                  <input id="<?php echo $answers[$x]['id'] ?>" type="checkbox">
                                </div>
                              </div>
                            </div>

                        <?php }
                        } ?>
                      </div>
                      <br>
                      <hr>

                    </div>

              <?php
                  }
                }
              } ?>

              <div class="container-f-space-between">
                <a href="<?php echo $_SESSION['ROOT_URL'] . 'index.php' ?>" class="none btn btn-primary" id="goBack">Go Back</a>
                <input type="submit" value="Submit" class="btn btn-primary" id="examSubmit">
                <div id="result"></div>
              </div>
              <hr>

              <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/footer.php') ?>

              <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/pureknob.js' ?>"></script>

              <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/spryValidation.min.js' ?>"></script>
              <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/spryValidator-V1.js' ?>"></script>

              <script>
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

                let exam = {};
                exam.examId = "";
                exam.userID = "";
                exam.questions = [];

                $(".q-componant").spryValidator({
                  submitBtn: "#examSubmit",
                  checkbox: {
                    isRequired: true,
                    maxSelections: 1,
                  },
                  onSuccess: function() {
                    console.log('D:');

                    exam.examId = <?php echo $_GET["id"] ?>;

                    $(".q-componant").each(function() {
                      let that = this;
                      let question = {};

                      let questionID = $(this).find('div.q-container').first().attr('id');
                      let choiceId = $(this).find('input:checked').attr('id');

                      exam.questions.push({
                        questionId: questionID,
                        answerId: choiceId
                      })

                    });

                    $.ajax({
                      url: "<?php echo $_SESSION['ROOT_URL'] . 'controller/user/results.php' ?>",
                      dataType: 'json',
                      type: "POST",
                      data: {
                        "exam": exam,
                        "answers": true
                      },
                      success: function(resultBack) {
                        for (let [k, v] of Object.entries(resultBack)) {
                          let qContainer = $(`#${v.questionId}`).parent();

                          if (v.currentAnswer == v.correctAnswer) {
                            qContainer.find(`div[data-id='${v.currentAnswer}']`).addClass('correct');
                          } else {
                            qContainer.find(`div[data-id='${v.currentAnswer}']`).addClass('wrong');
                            qContainer.find(`div[data-id='${v.correctAnswer}']`).addClass('correct');
                          }

                        }

                        knobAnimation(resultBack['result']);
                        $("input").attr("disabled", "disabled")
                        $('#examSubmit').fadeOut(0);
                        $('#goBack').fadeIn();
                      },
                      error: function(xhr, ajaxOptions, thrownerror) {
                        console.log(thrownerror);
                      }

                    })
                  }
                })
              </script>

              <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/bottom.php') ?>
              