<?php
//if (!defined("BPA_CMS")) die("Tug`ridan tug`ri ishlatish mumkin emas");
$BASEPATH = str_replace("init.php", "", realpath(__FILE__));
include($BASEPATH . "class/class_config.php");
include($BASEPATH . "class/class_db.php");
include($BASEPATH . "class/class_registry.php");
Registry::set("Config", new Config());
$config = Registry::get("Config");

if (ini_get('date.timezone'))
    date_default_timezone_set($config->time_zone);


Registry::set("DataBase", new DataBase($config->server, $config->user, $config->password, $config->database,$config->charset));
$db = Registry::get("DataBase");
$db->connect();

include("libs/functions.php");

include($BASEPATH . "class/class_core.php");
Registry::set("Core",new Core());
$core=Registry::get("Core");

include($BASEPATH."class/class_lang.php");
Registry::set("Lang",new Lang());
$lang=Registry::get("Lang");

include($BASEPATH."class/class_user.php");
Registry::set("Users",new Users());
$users=Registry::get("Users");

include($BASEPATH . "class/class_page.php");
Registry::set("Page",new Page());
$page=Registry::get("Page");

include($BASEPATH . "class/class_smarty.php");
Registry::set("SmartyTpl",new SmartyTpl());
$smarty=Registry::get("SmartyTpl");

include($BASEPATH."class/class_content.php");
Registry::set("Content",new Content());
$content=Registry::get("Content");

include($BASEPATH."class/class_menu.php");
Registry::set("Menus",new Menus());
$menus=Registry::get("Menus");

include($BASEPATH."class/class_upload.php");
Registry::set("Uploads",new Uploads());
$uploads=Registry::get("Uploads");

include($BASEPATH."class/class_photo.php");
Registry::set("Photos",new Photos());
$photos=Registry::get("Photos");

include($BASEPATH.'class/class_paginate.php');
Registry::set("Paginator",new Paginator());
$pager=Registry::get("Paginator");

include($BASEPATH.'class/class_comments.php');
Registry::set("Comments",new Comments());
$comments=Registry::get("Comments");

include($BASEPATH."class/class_dbtools.php");
Registry::set("dbTools",new dbTools());

include($BASEPATH."class/class_plugins.php");
Registry::set("Plugins",new Plugins());

include($BASEPATH."class/class_email.php");
Registry::set("PHPMailer",new PHPMailer());




if (isset($_SERVER['HTTPS'])) {
    $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
} else {
    $protocol = 'http';
}

$dir = (Registry::get("Core")->site_dir) ? '/' . Registry::get("Core")->site_dir : '';
$url = preg_replace("#/+#", "/", $_SERVER['HTTP_HOST'] . $dir);
$site_url = $protocol . "://" . $url;

define("SITEURL", $site_url);
define("ADMINURL", $site_url . "/admin");
define("UPLOADS", $BASEPATH . "uploads/");
define("UPLOADURL", SITEURL . "/uploads/");
define("MODPATH", $BASEPATH . "admin/modules/");
define("PLUGPATH", $BASEPATH . "admin/plugins/");
define("MODPATHF", $BASEPATH . "modules/");
define("PLUGPATHF", $BASEPATH . "plugins/");
define("MODURL", SITEURL . "/modules/");
define("PLUGURL", SITEURL . "/plugins/");


$pthemedir = ($config->template) ? $BASEPATH . "theme/" . $config->template : $BASEPATH . "theme/" . $core->theme;
$pthemeurl = ($config->template) ? SITEURL . "/theme/" .$config->template : SITEURL . "/theme/" . $core->theme;
$theme = ($content->theme) ? $content->theme : $core->theme;


define("THEMEURL", $pthemeurl);
define("THEMEDIR", $pthemedir);
define("THEME", $pthemedir);
define("CTHEME", 'theme/' . $theme);

?>