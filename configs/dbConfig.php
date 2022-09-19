<?php

$db = mysqli_connect('localhost', 'root', '', 'exams');

if (!$db) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
