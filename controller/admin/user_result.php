<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

if (isset($_POST['get_results']) && isset($_POST['user_id'])) {
    ajaxResults($_POST['exam_id'], $_POST['user_id']);
}

function ajaxResults ($examId, $userId) {
    
    if (getResults($examId, $userId)){
        $results = json_encode(getResults($examId, $userId));
        echo $results;
    }
}
