<?php
session_start();
define('PATH', $_SERVER['DOCUMENT_ROOT']);
include($_SERVER['DOCUMENT_ROOT'] . '/include/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/class/config.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/class/class_db.php');
include($_SERVER['DOCUMENT_ROOT'] . '/class/engine.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/class/users.class.php');
$ker = Engine::getInstance();
$user = Users::getInstance();
    $inDB   = DB::getInstance();
    $cat    = $_REQUEST['value'];
	$sel    = $_REQUEST['sel'];
	$vendor_text = '';
	 $sql = "SELECT * FROM auto_models WHERE vendor ={$cat}";
					 $result = $inDB->query($sql) ;
					 if ($inDB->num_rows($result)){
						 while($cat = $inDB->fetch_assoc($result)){
						if ($cat['id'] == $sel) {$k= 'selected="selected"';}  else {$k='';}
						$vendor_text .='<option value="'.$cat['id'].'" '.$k.'> '.$cat['title'].'</option>';
							 $cats[] = $cat;}
						}
echo $vendor_text;
return;
?>