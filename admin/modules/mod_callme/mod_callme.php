<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 11.02.2016
 * Time: 21:19
 */


$db=Registry::get("DataBase");
$sql="select params from mmodules where id=".Core::$id;
$row=$db->first($sql);
$cfg=unserialize($row->params);

if (isset($_REQUEST['opt'])) {$opt = $_REQUEST['opt'];} else { $opt = 'view';}

if ($opt == 'view') {
    ?>


<?php

}


?>