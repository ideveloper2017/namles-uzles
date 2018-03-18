<?php
//  if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }

define('PATH', $_SERVER['DOCUMENT_ROOT']);

$name = $_GET['file'];
if ($name) {

    $file_handle = fopen(PATH."/uploads/fcatalog/".$name, "r");
    while (!feof($file_handle)) {
        $line = fgets($file_handle);
        echo $line;
    }
    fclose($file_handle); }

?>