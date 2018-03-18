<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 04.03.2016
 * Time: 21:45
 */

function mod_video_new($module_id){

    $inCore = Registry::get("Core"); // подключаем ядро
    $inDB   = Registry::get("DataBase"); // подключаем базу
    $inUser = Registry::get("Users"); //подключаем пользователей
    $inPage = Registry::get("Page");

    $cfg = $inCore->getModuleConfig($module_id); // подключаем настройки
//    $sql = selectVideoRolic($cfg);

    $inPage->addHeadCSS('/modules/mod_video_new/style_new_video.css');

    $sort = "";

//    if($cfg['sort'] == "rating"){
//        $sort = "ORDER BY total_value DESC";
//    }

    if($cfg['sort'] == "comments_count"){
        $sort = "ORDER BY date DESC";
    }

    if($cfg['sort'] == "pubdate"){
        $sort = "ORDER BY hits DESC";
    }

    if($cfg['cat_id']){
        $cat = "AND cat_id =".$cfg['cat_id'].' ';
    }

    $sql = "SELECT * FROM video WHERE published = 1 ".$cat."".$sort;
    $result = $inDB->query($sql) ;
    if ($inDB->numrows($result)){
        while($item = $inDB->fetch($result)){
            $massiv[] = $item;
        }
    }


    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_video_new.php');
}
?>