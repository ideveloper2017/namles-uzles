<?php
/**
 * Created by PhpStorm.
 * User: Bahrom
 * Date: 15.10.2014
 * Time: 21:23
 */
function auto(){
    if (isset($_REQUEST['do'])){$do=$_REQUEST['do'];}else{$do='view';}
    if (isset($_REQUEST['id'])){$id=$_REQUEST['id'];}else{$id=-1;}
    $db=DB::getInstance();
    $ker=Engine::getInstance();
    $user=Users::getInstance();
    $photos=Photos::getInstance();

    $cfg=getComponentConfig("auto");

    if ($do=='view'){
?>

<?
    }

}

?>