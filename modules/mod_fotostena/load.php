<?php

 //  if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }

	session_start();

//	if (!isset($_REQUEST['item_id'])) { die(); }


//	define("VALID_CMS", 1);
    define('PATH', $_SERVER['DOCUMENT_ROOT']);

	include(PATH.'/init.php');

    $inCore = Registry::get("Core");

//    define('HOST', 'http://' . $inCore->getHost());
//    $cfg   = $inCore->loadComponentConfig('desire');
//
//    $inCore->loadClass('user');
//   $inCore->loadClass('db');
//
    $inUser = Registry::get('Users');
    $inDB   = Registry::get("DataBase");


    $item_id = $_REQUEST['item_id'];


 if ($item_id) {
    $sql = "SELECT f.*,u.username FROM photofiles f LEFT JOIN users u ON f.user_id = u.id WHERE f.id = {$item_id}";
     $result  = $inDB->query($sql);
  $foto = $inDB->fetch($result,true);

  // $foto = $inDB->get_fields('cms_photo_files',"id={$item_id}", 'pubdate,hits'); 
   $foto['pubdate'] = $foto['pubdate'];
//   $foto['comments'] =  $inCore->getCommentsCount('photo', $foto['id']);
 
 $text = '<b>'.$foto['title'].'</b> | '.$foto['pubdate'].' | <a href="/users/'.$foto['username'].'">'.$foto['username'].'</a> |
 <img src="/modules/mod_fotostena/hits.png"> '.$foto['hits'].' | <img src="/modules/mod_fotostena/com.png"> '.$foto['comments'].'
 <a href="/photos/photo'.$foto['id'].'.html" style="color:#fff">Комментировать</a>';


 }

    
  	echo $text;  

    
?>
