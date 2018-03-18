<?php
function mod_namaz($module_id){
	$inCore = Registry::get("Core");
	$inDB = Registry::get("DataBase");
	$namaz_time_title = array('Тонг','Қуёш','Пешин','Аср','Шом','Хуфтон');
	$week = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	$content=file_get_contents('http://www.islamicfinder.org/prayerDetail.php?country=uzbekistan&city=Namangan_Shahri&state=06&id=6056');
	$pos = strpos($content, 'Prayer Schedule');
	$content = substr($content, $pos);
	$pos = strpos($content, 'Monthly/Annual Schedule');
	$content = substr($content, 0, $pos);
	///////////////////////////////////////
	foreach ($week as $vol) {
		$pos = strpos($content, ">".$vol."<");
		$cont[$vol] = substr($content, $pos);
		$pos = strpos($cont[$vol], "</tr>");
		$cont[$vol] = strip_tags(substr($cont[$vol], 4, $pos));
	}

//////////////////////////////////////////
///////////////////////////////////////////

	$q[]="";
	$q[]="января";
	$q[]="февраля";
	$q[]="марта";
	$q[]="апреля";
	$q[]="мая";
	$q[]="июня";
	$q[]="июля";
	$q[]="августа";
	$q[]="сентября";
	$q[]="октября";
	$q[]="ноября";
	$q[]="декабря";


	// ---- считываем месяц
	$m=date('m');
	if ($m=="01") $m=1;
	if ($m=="02") $m=2;
	if ($m=="03") $m=3;
	if ($m=="04") $m=4;
	if ($m=="05") $m=5;
	if ($m=="06") $m=6;
	if ($m=="07") $m=7;
	if ($m=="08") $m=8;
	if ($m=="09") $m=9;

	$year=date('Y');

	$chislo=date('j');

	$mesyac = $q[$m];

	$just_data = ucfirst(strftime("%A", time()));

	switch ($just_data) {
		case 'Monday':
			$just_data = 'Понедельник';
			break;
		case 'Tuesday':
			$just_data = 'Вторник';
			break;
		case 'Wednesday':
			$just_data = 'Среда';
			break;
		case 'Thursday':
			$just_data = 'Четверг';
			break;
		case 'Friday':
			$just_data = 'Пятница';
			break;
		case 'Saturday':
			$just_data = 'Суббота';
			break;
		case 'Sunday':
			$just_data = 'Воскресенье';
			break;
	}

	$today = $just_data.', '.$chislo.' '.$mesyac.' '.$year.' года';

/////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////

	$den = date(D);

	$tomorrow = time();
//	$tomorrow = $inCore->DateAdd('w',1, $tomorrow);
	$tomorrow = strftime('%a', $tomorrow);

	switch ($tomorrow) {
		case 'Пн':
			$tomorrow='Mon';
			break;
		case 'Вт':
			$tomorrow='Tue';
			break;
		case 'Ср':
			$tomorrow='Wed';
			break;
		case 'Чт':
			$tomorrow='Thu';
			break;
		case 'Пт':
			$tomorrow='Fri';
			break;
		case 'Сб':
			$tomorrow='Sat';
			break;
		case 'Вс':
			$tomorrow='Sun';
			break;
	}

	$namaz_today = explode(' ', trim($cont[$den]));
	$namaz_tomorrow = explode(' ', trim($cont[$tomorrow]));


	foreach ($namaz_time_title as $id=>$vol) {


		$namaz[$vol] = $namaz_today[$id];

	}


	foreach ($namaz_time_title as $id=>$vol) {

		$namaz_next[$vol] = $namaz_tomorrow[$id];

	}
	include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_namaz.php');
	return true;
}
?>