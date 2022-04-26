<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

// call the register() function if register_btn is clicked
if (isset($_POST['answers'])) {
    results();
}

if (isset($_POST['get_results'])) {
    ajaxResults($_POST['exam_id'], $_POST['user_id']);
}

$examErrors = array();

function results()
{
    global $db;

    if (isset($_POST['exam'])) {
        $return = array();
        $exam = $_POST['exam'];
        $examErrors = array();

        $examId = trim($exam['examId']);
        $userId = $_SESSION['user']['id'];

        $query = "SELECT questions_number FROM exams WHERE id = $examId";
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);

        $examQuestionsNumber = $row['questions_number'];
        $totalCorrect = 0;

        $questions = $exam['questions'];
        if ($questions) {
            foreach ($questions as $question) {
                $questionId = $question['questionId'];
                $answerId = $question['answerId'];

                $subQuery = "SELECT id FROM choices WHERE question_id = $questionId AND is_correct = 1";
                $result = mysqli_query($db, $subQuery);
                $row = mysqli_fetch_assoc($result);

                if($row['id'] == $answerId){
                    $totalCorrect = $totalCorrect + 1;
                }

                $questionArray = array("questionId" => $questionId, "currentAnswer" => $answerId, "correctAnswer" => $row['id']);
                array_push($return, $questionArray);
                
                $answersQuery = "INSERT INTO answers (user_id, exam_id, question_id, answer_id, correct_answer) 
                VALUES(?, ?, ?, ?, ?)";

                $stmt = $db->prepare($answersQuery);
                $stmt->bind_param("iiiii", $userId, $examId, $questionId, $answerId, $row['id']);
                $stmt->execute();
            }
        }
        $return['result'] = round($totalCorrect / $examQuestionsNumber * 100);

        $resultQuery = "INSERT INTO results (user_id, exam_id, result) 
                VALUES(?, ?, ?)";

                $stmt = $db->prepare($resultQuery);
                $stmt->bind_param("iii", $userId, $examId, $return['result']);
                $stmt->execute();

        echo json_encode($return);
        
    } else {
        array_push($examErrors, "Something went wrong!!. Go back");
    }
}

function ajaxResults ($examId, $userId) {
    if (getResults($examId, $userId)){
        $results = json_encode(getResults($examId, $userId));
        echo $results;
    }
}
