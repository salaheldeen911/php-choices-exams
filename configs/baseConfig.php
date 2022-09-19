<?php
session_start();

$array = explode('\\', __dir__);
array_pop($array);
$root = $array[count($array) - 1];
$str = str_replace("\\", "/", __dir__);
$str2 = str_replace($_SERVER['DOCUMENT_ROOT'], "", $str);

$root_url = str_replace("/configs", "", $str2);
$root_server = str_replace("$root\configs", "", __dir__) . $root . "\\";

$_SESSION['ROOT_SERVER'] = $root_server;
$_SESSION['ROOT_URL'] = "/" . $root_url . "/";
