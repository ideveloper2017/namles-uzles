<?php
define("BPA_CMS", true);
define("PATH", $_SERVER['DOCUMENT_ROOT']);
require("../init.php");
if ($users->logged_in){
    $users->logout();
    redirect_to("index.php");
}

?>