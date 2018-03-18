<?php
session_start();
function mod_usermenu($id)
{
    $db = Registry::get("DataBase");
    $menu = Registry::get("Menus");
    $users = Registry::get("Users");
    $core = Registry::get("Core");
    $langID = Lang::getLangID();
    $config = Registry::get("Config");
    $cfg = $core->getModuleConfig($id);

    include(PATH . '/theme/' . Registry::get("Config")->template . '/mod_usermenu.php');
}

?>