<?php
Error_Reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT);
setlocale(LC_ALL, 'ru_RU.CP1251');
header('Content-Type: text/html; charset=utf-8');
session_start();
define("BPA_CMS", true);
define("PATH", $_SERVER['DOCUMENT_ROOT']);
require ("init.php");
$GLOBALS['page_body']='';
$core->renderComponent();
if ($config->offline==1 && !$users->is_Admin() && !preg_match("#admin/#", $_SERVER['REQUEST_URI'])){
    require_once (PATH.'/theme/'.$config->template."/offline.php");
    exit;
}else{
    include(THEMEDIR."/template.php");
}
?>