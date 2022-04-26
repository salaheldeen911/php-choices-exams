<?php
require_once(__DIR__ . '/../configs/dbConfig.php');
require_once($_SESSION['ROOT_SERVER'] . 'vendor/autoload.php');

// variable declaration
$username = "";
$email = "";
$errors = array();

function homeHref()
{
    if (isLoggedIn()) {
        if (isAdmin()) {
            echo $_SESSION['ROOT_URL'] . 'views/admin/home.php';
        } else {
            echo $_SESSION['ROOT_URL'] . 'views/user/home.php';
        }
    } else {
        echo $_SESSION['ROOT_URL'];
    }
}

// return user array from their id
function getUserById($id)
{
    global $db;
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($db, $query);

    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        return false;
    }
    return $user;
}

function getUserName($id)
{
    global $db;

    $query = "SELECT username FROM users WHERE id = $id";
    $result = mysqli_query($db, $query);

    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        return false;
    }
    return $user['username'];
}

function getExamBy($id)
{
    global $db;
    $query = "SELECT * FROM exams WHERE id = $id";
    $result = mysqli_query($db, $query);

    $exam = mysqli_fetch_assoc($result);
    if (!$exam) {
        return false;
    }
    return $exam;
}

function getQuestionById($id)
{
    global $db;
    $query = "SELECT * FROM questions WHERE id = $id";
    $result = mysqli_query($db, $query);

    $question = mysqli_fetch_assoc($result);
    if (!$question) {
        return false;
    }
    return $question;
}

function getAnswerById($id)
{
    global $db;
    $query = "SELECT * FROM answers WHERE id = $id";
    $result = mysqli_query($db, $query);

    $answer = mysqli_fetch_assoc($result);
    if (!$answer) {
        return false;
    }
    return $answer;
}

function getAvilableExams()
{
    global $db;
    $id = $_SESSION['user']['id'];
    $query = "SELECT exams.id, exams.name, exams.questions_number, exams.creator_id, exams.material, exams.created_at 
                FROM exams
                LEFT JOIN results 
                ON exams.id = results.exam_id
                AND results.user_id = $id
                WHERE results.id IS null";

    $result = mysqli_query($db, $query);
    $exams = $result->fetch_all(MYSQLI_ASSOC);
    if (!$exams) {
        return false;
    }
    return $exams;
}

function getDoneExams()
{
    global $db;
    $id = $_SESSION['user']['id'];
    $query = "SELECT exams.id, exams.name, exams.questions_number, exams.creator_id, exams.material, exams.created_at 
                FROM exams 
                INNER JOIN results 
                ON results.exam_id = exams.id 
                AND results.user_id = $id";

    $result = mysqli_query($db, $query);
    $exams = $result->fetch_all(MYSQLI_ASSOC);
    if (!$exams) {
        return false;
    }
    return $exams;
}


function getQuestions($id)
{
    global $db;
    $query = "SELECT * FROM questions WHERE exam_id = $id";

    $result = mysqli_query($db, $query);

    $questions = $result->fetch_all(MYSQLI_ASSOC);
    if (!$questions) {
        return false;
    }
    return $questions;
}

function getQuestionChoices($id)
{
    global $db;
    $query = "SELECT * FROM choices WHERE question_id = $id";

    $sql = mysqli_query($db, $query);

    $result = $sql->fetch_all(MYSQLI_ASSOC);

    if (!$result) {
        return false;
    }
    return $result;
}

function getQuestionAnswer($id)
{
    global $db;
    $query = "SELECT id FROM choices WHERE question_id = $id AND is_correct = 1";

    $sql = mysqli_query($db, $query);

    $result = mysqli_fetch_assoc($sql);

    if (!$result) {
        return false;
    }
    return $result;
}

function isCorrect($id)
{
    global $db;
    $query = "SELECT is_correct FROM choices WHERE id = $id LIMIT 1";

    $sql = mysqli_query($db, $query);

    $result = mysqli_fetch_assoc($sql);
    if ($result['is_correct'] == 1) {
        return true;
    }
    return false;
}

function getResults($examId, $userId)
{
    global $db;
    $query = "SELECT answer_id, correct_answer FROM answers WHERE user_id=$userId AND exam_id=$examId";

    $sql = mysqli_query($db, $query);

    $result = $sql->fetch_all(MYSQLI_ASSOC);

    if (!$result) {
        return false;
    }
    return $result;
}

// escape string
function e($val)
{
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

function display_error()
{
    global $errors;

    if (count($errors) > 0) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo    '<ul class="errors">';

        foreach ($errors as $error) {
            echo        '<li class="error">';
            echo            $error;
            echo        '</li>';
        }
        echo    '</ul>';

        echo    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        echo        '<span aria-hidden="true">&times;</span>';
        echo    '</button>';
        echo '</div>';
    }
}

function show_success_msg($msg)
{

    if ($msg) {

        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        echo     '<div class="success">';
        echo        $msg;
        echo     '</div>';
        echo     '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        echo        '<span aria-hidden="true">&times;</span>';
        echo     '</button>';
        echo  '</div>';

    }
}

function title($page)
{
    switch ($page) {
        case 'index.php':
            echo 'EXAMS - Login';
            break;
        case 'home.php':
            echo 'EXAMS - Home';
            break;
        case 'results.php':
            echo 'EXAMS - Results';
            break;
            // ADMIN
        case 'create_admin.php':
            echo 'EXAMS - Create Admin';
            break;
        case 'create_exam_question.php':
            echo 'EXAMS - Create Exam Questions';
            break;
        case 'create_exam.php':
            echo 'EXAMS - Create Exam';
            break;
        case 'questions.php':
            echo 'EXAMS - Exam View';
            break;
        case 'user_result.php':
            echo 'EXAMS - User Result';
            break;

            // USER
        case 'exam.php':
            echo 'Exam';
            break;
        case 'my_exams.php':
            echo 'My Exams';
            break;
        case 'create-new-password.php':
            echo 'Create New Password';
            break;
        case 'register.php':
            echo 'Register';
            break;
        case 'reset-password-request.php':
            echo 'Reset Password Request';
            break;
    }
}

function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

function isAdmin()
{
    if (isset($_SESSION['user']) && $_SESSION['user']['is_admin'] == 1) {
        return true;
    } else {
        return false;
    }
}


function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
