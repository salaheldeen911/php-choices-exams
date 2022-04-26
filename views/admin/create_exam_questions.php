<?php
require_once(__DIR__ . '/../layouts/head.php');

if (!isLoggedIn() || !isAdmin()) {
   header('location:' . $_SESSION['ROOT_URL']);
   exit();
}

if (!isset($_SESSION["lastPreparedExam"])) {
   array_push($errors, "Something went wrong with The preparation!. Please prepare another exam, or call the administrator.");

   $err = json_encode($errors);
   header('location:' . $_SESSION['ROOT_URL'] . "views/admin/create_exam.php?errors=$err");
   exit();
}

require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/header.php');
?>

<div class="main-panel">
   <div class="content-wrapper">
      <div class="page-header">
         <h3 class="page-title"> Create exam questions </h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php homeHref() ?>"> Home </a></li>
               <li class="breadcrumb-item active" aria-current="page"> <a href="./create_exam.php"> Exam preparation </a></li>
               <li class="breadcrumb-item active" aria-current="page"> Create exam questions </li>

            </ol>
         </nav>
      </div>
      <div class="row">

         <div class="col-lg-12 stretch-card">
            <div class="card">
               <div class="card-body">

                  <div style="display:none;" id="preloader"></div>

                  <div class="title mb-4">
                     <h2>Create Exam Questions</h2>
                  </div>

                  <div id="examContainer" class="mt-2">

                     <?php

                     if (isset($_GET['success'])) {
                        show_success_msg($_GET['success']);
                     }

                     $exam = $_SESSION["lastPreparedExam"];

                     for ($i = 0; $i < $exam["q_num"]; $i++) {
                     ?>

                        <div class="row q-componant mt-3" id="<?php echo $i + 1 ?>">

                           <div class="q-container container-fluid">
                              <div class="row">
                                 <div class="question">
                                    <div class=" mt-1 mr-2">
                                       <strong>Q-<?php echo $i + 1 ?></strong>
                                    </div>
                                    <div style="flex-grow: 5;">
                                       <!-- <input type="text" data-spry="none" data-type="question" class="form-control"> -->
                                       <textarea class="form-control choise-textarea" style="color:#6963a4 !important;" data-type="question" data-spry="textarea"></textarea>

                                    </div>
                                 </div>


                              </div>
                           </div>


                           <div class="a-container" data-spry="checkbox">
                              <div class="row">

                                 <div class="col-md-4 mt-3">
                                    <div class="row">
                                       <div class="choise">
                                          <div class="mt-2">
                                             <strong>1.</strong>
                                          </div>
                                          <div style="flex-grow: 5;">
                                             <textarea class="form-control choise-textarea" data-num="1" data-type="answer" data-spry="textarea"></textarea>
                                          </div>
                                          <div class="form-check form-check-success">
                                             <input type="checkbox" type="checkbox" value="1" class="form-check-input choise-checkbox">
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-md-4 mt-3">
                                    <div class="row">
                                       <div class="choise">
                                          <div class="mt-2">
                                             <strong>2.</strong>
                                          </div>
                                          <div style="flex-grow: 5;">
                                             <textarea class="form-control choise-textarea" data-num="2" data-type="answer" data-spry="textarea"></textarea>
                                          </div>
                                          <div class="form-check form-check-success">
                                             <input type="checkbox" type="checkbox" value="2" class="form-check-input choise-checkbox">
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-md-4 mt-3">
                                    <div class="row">
                                       <div class="choise">
                                          <div class="mt-2">
                                             <strong>3.</strong>
                                          </div>
                                          <div style="flex-grow: 5;">
                                             <textarea class="form-control choise-textarea" data-num="3" data-type="answer" data-spry="textarea"></textarea>
                                          </div>
                                          <div class="form-check form-check-success">
                                             <input type="checkbox" type="checkbox" value="3" class="form-check-input choise-checkbox">
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-md-4 mt-3">
                                    <div class="row">
                                       <div class="choise">
                                          <div class="mt-2">
                                             <strong>4.</strong>
                                          </div>
                                          <div style="flex-grow: 5;">
                                             <textarea class="form-control choise-textarea" data-num="4" data-type="answer" data-spry="textarea"></textarea>
                                          </div>
                                          <div class="form-check form-check-success">
                                             <input type="checkbox" type="checkbox" value="4" class="form-check-input choise-checkbox">
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-md-4 mt-3">
                                    <div class="row">
                                       <div class="choise">
                                          <div class="mt-2">
                                             <strong>5.</strong>
                                          </div>
                                          <div style="flex-grow: 5;">
                                             <textarea class="form-control choise-textarea" data-num="5" data-type="answer" data-spry="textarea"></textarea>
                                          </div>
                                          <div class="form-check form-check-success">
                                             <input type="checkbox" type="checkbox" value="5" class="form-check-input choise-checkbox">
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                              </div>
                           </div>
                        </div>

                        <hr>

                     <?php
                     }
                     ?>

                     <input type="submit" value="Submit" class="btn btn-primary" id="examSubmit">

                     <hr>

                  </div>

                  <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/footer.php') ?>

                  <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/bootstrap.bundle.min.js' ?>"></script>

                  <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/spryValidation.min.js' ?>"></script>
                  <script src="<?php echo $_SESSION['ROOT_URL'] .  'resources/js/spryValidator-V1.js' ?>"></script>

                  <script>
                     let exam = {};

                     $("#examContainer").spryValidator({
                        submitBtn: "#examSubmit",
                        textarea: {
                           isRequired: true,
                        },
                        checkbox: {
                           isRequired: true,
                           maxSelections: 1,
                        },
                        onSuccess: function() {
                           $("#preloader").css("display", "block")
                           $("body").css("pointer-events", "none")

                           exam.createExam = true;

                           let questions = exam.questions = []

                           $(".q-componant").each(function() {
                              let that = this;
                              let question = {}
                              let choices = question.choices = []

                              let qValue = $(this).find('textarea[data-type="question"]').first().val();
                              let correct = $(this).find('input:checked').val();

                              question.value = qValue;
                              question.number = this.id;
                              question.correct_a = correct;

                              $(that).find('textarea[data-type="answer"]').each(function() {
                                 let choice = {}
                                 let is_correct = 0;
                                 let choiceValue = $(this).val();

                                 if ($(this).parents('div.row').first().find('input[type="checkbox"]').is(':checked')) {
                                    is_correct = 1;
                                 }

                                 choice.value = choiceValue;
                                 choice.is_correct = is_correct;

                                 choices.push(choice);

                              });

                              questions.push(question);
                           });

                           $.ajax({
                              url: "<?php echo $_SESSION['ROOT_URL'] . 'controller/admin/Create_exam.php' ?>",
                              dataType: 'json',
                              type: "POST",
                              data: {
                                 "createExam": true,
                                 "exam": exam,

                              },
                              success: function(response) {
                                 console.log(response);
                                 window.location.href = "./create_exam.php?success=New exam has been created successfully !!.";
                              },
                              error: function(xhr, ajaxOptions, thrownerror) {
                                 console.log(thrownerror);
                              }

                           })
                        }
                     })
                  </script>

                  <?php require_once($_SESSION['ROOT_SERVER'] . 'views/layouts/bottom.php') ?>
                  