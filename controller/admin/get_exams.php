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

$dt->query('Select id, material, name, questions_number, creator_id, created_at from exams');

$dt->edit('id', function ($data){
    $id = $data['id'];
    return makeTdContainer("<span> $id </span>", false);
});

$dt->edit('material', function ($data){
    $id = $data['id'];
    $material = $data['material'];
    return makeTdContainer("<span data-id='$id'> $material </span>", false);
});

$dt->edit('name', function ($data){
    $id = $data['id'];
    $name = $data['name'];
    return makeTdContainer("<span data-id='$id'> $name </span>", false);
});

$dt->edit('questions_number', function ($data){
    $id = $data['id'];
    $number = $data['questions_number'];
    return makeTdContainer("<span data-id='$id'> $number </span>", false);
});

$dt->edit('creator_id', function ($data){
    $name = getUserName($data['creator_id']);
    $id = $data['id'];
    return makeTdContainer("<span data-id='$id' href='#$id'> $name </span>", false);
});

$dt->edit('created_at', function ($data){
    $id = $data['id'];
    $dateCreated = date_create($data['created_at']);
    $date = date_format($dateCreated,"d-M-y");

    return makeTdContainer("<span data-id='$id'> $date </span>", false);
});

$dt->add('actions', function ($data) {
    $id = $data['id'];
    return makeTdContainer("<a href='" . $_SESSION['ROOT_URL'] . "views/admin/edit_exam.php?id=$id'" . "> edit </a>
                            <a href='" . $_SESSION['ROOT_URL'] . "views/admin/questions.php?id=$id'" . "> view </a>
                            <a href='javascript:;' class='delete-exam' data-id='$id'> delete </a> 
                            <form class='none' method='post' id='$id' action='" . $_SESSION['ROOT_URL'] . "controller/admin/delete_exam.php'> 
                                <input type='hidden' value=$id name='id'> 
                            </form>", true);
});



echo $dt->generate();

function makeTdContainer ($content, $actions) {
    if($actions){
        return "<span class='actions'>" . $content . "</span>";
    }
    return "<span class='td-container'>" . $content . "</span>";
}