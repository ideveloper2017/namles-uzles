<?php
Error_Reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT);
define("BPA_CMS", true);
define("PATH", $_SERVER['DOCUMENT_ROOT']);
require_once("../init.php");
$GLOBALS['cp_page_body']="";
if (!$users->is_Admin())
    redirect_to("login.php");
$core->getloadModule();
include("template.php");
?>
