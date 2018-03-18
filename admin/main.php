<?php
$new['content'] = (int)Registry::get("Content")->newContent('content','pubdate');
$new['photos'] = (int)Registry::get("Content")->newContent('photofiles','pubdate');

$new['comments']=(int)Registry::get("Content")->newContent('comments','date');
?>
<div class="clearfix">&nbsp;</div>
<hr/>
<ul class="info-blocks">
    <li class="bg-primary">
        <div class="top-info">
            <a href="index.php?do=content&action=addcontent">Добавить статья</a>
            <small>&nbsp;</small>
        </div>
        <a href="index.php?do=content&action=addcontent"><i class="icon-pencil"></i></a>
        <span class="bottom-info bg-danger"><?php echo Registry::get("DataBase")->numrows(Registry::get("DataBase")->query("select * from content"))?> шт статяь</span>
    </li>
    <li class="bg-success">
        <div class="top-info">
            <a href="#">Параметр сайта</a>
            <small>&nbsp;</small>
        </div>
        <a href="#"><i class="icon-cogs"></i></a>
        <span class="bottom-info bg-primary">нет обновление</span>
    </li>
    <li class="bg-danger">
        <div class="top-info">
            <a href="#">Статистика пользователь</a>
            <small>Посититель, Ползователь, Группа</small>
        </div>
        <a href="index.php?do=users"><i class="icon-users"></i></a>
        <span class="bottom-info bg-primary"><?php echo Registry::get("DataBase")->numrows(Registry::get("DataBase")->query("select * from users"));?> шт пользователь</span>
    </li>



</ul>


