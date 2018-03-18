<?php

 define('PATH', $_SERVER['DOCUMENT_ROOT']);
	include(PATH.'/init.php');

    $inDB   = Registry::get("DataBase");
    $module_id	= $_REQUEST['module_id'];

	$cfg = Registry::get("Core")->getModuleConfig($module_id);
 

   include(PATH.'/modules/mod_content_calendar/postercore.php');
   
   //проверяем входные параметры
   $period = (int)$_REQUEST['period'];
   if(!$period){ return false; }

   $period_arr = explode('_', $period);
   $start_date = $period_arr[0];

   $calendar = getCalendar($cfg['cat_id'], '', $start_date);//array
      		$today = date("Ym");
   		$tody = date("Y");

         include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_content_calendar.php');

   return true;

?>