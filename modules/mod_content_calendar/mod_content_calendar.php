<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 20.01.2016
 * Time: 0:13
 */
function mod_content_calendar($id){
    $inCore = Registry::get("Core");
    $inDB   = Registry::get("DataBase");
    $inUser = Registry::get("Users");
    $cfg = $inCore->getModuleConfig($id);
    include(PATH."/modules/mod_content_calendar/postercore.php");
    if (!$cfg['cat_id']){$cfg['cat_id']=1;}
    $calendar = getCalendar($cfg['cat_id'], date('d-m-Y'));
    $module_id=$id;

    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_content_calendar.php');

}
?>