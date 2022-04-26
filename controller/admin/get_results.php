<?php
session_start();
require_once($_SESSION['ROOT_SERVER'] . 'includes/helpers.php');

use Ozdemir\Datatables\DataTables;
use Ozdemir\Datatables\DB\MySQL;

$config = [ 'host'     => 'localhost',
            'port'     => '3306',
            'username' => 'root',
            'password' => '',
            'database' => 'exams' 
        ];

$dt = new Datatables( new MySQL($config) );

$dt->query('SELECT id, user_id, exam_id, result FROM results');

$dt->edit('id', function ($data){
    $id = $data['id'];
    return makeTdContainer("<span> $id </span>", false);
});

$dt->edit('user_id', function ($data){
    $id = $data['id'];
    $user_id = $data['user_id'];
    return makeTdContainer("<span data-id='$id'> $user_id </span>", false);
});

$dt->edit('exam_id', function ($data){
    $id = $data['id'];
    $exam_id = $data['exam_id'];
    return makeTdContainer("<span data-id='$id'> $exam_id </span>", false);
});

$dt->edit('result', function ($data){
    $id = $data['id'];
    $result = $data['result'];
    return makeTdContainer("<span data-id='$id'> $result </span>", false);
});

$dt->add('actions', function ($data) {
    $id = $data['exam_id'];
    $userId = $data['user_id'];

    $resultId = $data['id'];
    return makeTdContainer("<a href='" . $_SESSION['ROOT_URL'] . "views/admin/user_result.php?id=$id&user_id=$userId'" . "> view </a>
                            <a href='javascript:;' class='delete-exam' data-id='$id'> delete </a> 
                            <form class='none' method='post' id='$id' action='" . $_SESSION['ROOT_URL'] . "controller/admin/delete_result.php'> 
                                <input type='hidden' value=$resultId name='id'> 
                            </form>", true);
});



echo $dt->generate();

function makeTdContainer ($content, $actions) {
    if($actions){
        return "<span class='actions'>" . $content . "</span>";
    }
    return "<span class='td-container'>" . $content . "</span>";
}