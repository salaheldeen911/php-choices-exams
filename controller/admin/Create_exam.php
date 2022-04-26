<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

if (!isLoggedIn()) {
    header('location:' . $_SESSION['ROOT_URL'] . 'views/auth/login.php');
    exit();
 }
 if (!isAdmin()) {
    header('location:' . $_SESSION['ROOT_URL'] . 'index.php');
    exit();
 }

// call the register() function if register_btn is clicked
if (isset($_POST['prepareExam'])) {
    prepareExam();
}

if (isset($_POST['createExam'])) {
    createExam();
}


function prepareExam()
{
    global $errors;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $examName          =  e($_POST['examName']);
    $examQuestionsNum  =  e($_POST['questionsNumber']);
    $examMaterialName  =  e($_POST['materialName']);
    $examCreatorId     =  e($_POST['teacherId']);

    // form validation: ensure that the form is correctly filled
    if (empty($examName)) {
        array_push($errors, "Exam name is required");
    }
    if (empty($examQuestionsNum)) {
        array_push($errors, "Questions number is required");
    }
    if (empty($examMaterialName)) {
        array_push($errors, "Material name is required");
    }
    if (empty($examCreatorId)) {
        array_push($errors, "something went wrong!!");
    }

    if (count($errors) == 0) {

        $_SESSION['lastPreparedExam'] = [
            "name" => $examName,
            "q_num" => $examQuestionsNum,
            "creator_id" => $examCreatorId,
            "material" => $examMaterialName,
        ];

        $url = $_SESSION['ROOT_URL'] . "views/admin/create_exam.php";
        $name = $_SESSION['lastPreparedExam']['name'];
        $q_num = $_SESSION['lastPreparedExam']['q_num'];
        

        header("location:" . $_SESSION['ROOT_URL'] . "views/admin/create_exam_questions.php?success=Exam <strong> $name </strong> has been prepared with <strong> $q_num </strong> questions successfully!. If this preparation is not correct, you can <strong><a href='$url'>Prepare</a></strong> another one.");
        exit();
    }else{
		$err = json_encode($errors);
        header('location: ' . $_SESSION['ROOT_URL'] . "views/admin/create_exam.php?errors=$err");
		exit();
    }
}

function createExam()
{
    global $db;

    if (isset($_SESSION['lastPreparedExam']) && isset($_POST['exam'])) {

        $examName = $_SESSION['lastPreparedExam']["name"];
        $examQuestionsNum = $_SESSION['lastPreparedExam']["q_num"];
        $examCreatorId = $_SESSION['lastPreparedExam']["creator_id"];
        $examMaterialName = $_SESSION['lastPreparedExam']["material"];

        $query = "INSERT INTO exams (name, questions_number, creator_id, material) 
        VALUES(?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->bind_param("siis", $examName, $examQuestionsNum, $examCreatorId, $examMaterialName);

        if ($stmt->execute()) {

            $last_exam_id = mysqli_insert_id($db);

            $questions = $_POST['exam']['questions'];

            if($questions){
                foreach($questions as $question) {
                    $question['last_exam_id'] = $last_exam_id;
                    createQuestion($question);
                }
            }

            unset($_SESSION['lastPreparedExam']);
            die(true);
        }
    }
}


function createQuestion($question)
{
    global $db;

    if ($question) {
        $examId = trim($question['last_exam_id']);
        $questionValue = trim($question['value']);

        $query = "INSERT INTO questions (value, exam_id) VALUES('$questionValue', '$examId')";

        if (mysqli_query($db, $query)) {

            $last_question_id = mysqli_insert_id($db);
            $choices = $question['choices'];

            if($choices){
                foreach($choices as $choice) {
                    $choice['last_question_id'] = $last_question_id;
                    createChoice($choice);
                }
            }
        }
    }
}

function createChoice($choice)
{
    global $db;

    if ($choice) {

        $questionId     = trim($choice['last_question_id']);
        $choiceValue    = trim($choice['value']);
        $isCorrect      = trim($choice['is_correct']);

        $query = "INSERT INTO choices (value, question_id, is_correct) 
        VALUES('$choiceValue', '$questionId', '$isCorrect')";

        mysqli_query($db, $query);
    }
}
