<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 02.05.2016
 * Time: 9:10
 */
function mod_calendar($module_id)
{

    $inCore = Registry::get("Core");
    $inDB   = Registry::get("DataBase");
    $inUser = Registry::get("Users");
    $cfg = $inCore->getModuleConfig($module_id);
    include(PATH . "/admin/components/calendar/class_calendar.php");
    Registry::set("Calendar", new Calendar());
    Registry::get("Calendar")->weekDayNameLength = "short";
    if (!$cfg['cat_id']){$cfg['cat_id']=1;}
//    $calendar = getCalendar($cfg['cat_id'], date('d-m-Y'));
//    $module_id=$module_id;

    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_calendar2.php');
    
    
}
?>