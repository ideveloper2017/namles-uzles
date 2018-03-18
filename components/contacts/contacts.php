<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 06.02.2016
 * Time: 5:39
 */
function contacts(){
    $db = Registry::get("DataBase");
    $config = Registry::get("Config");
    $users = Registry::get("Users");
    $core = Registry::get("Core");
    if (!Core::$action) {
        $core->action = 'view';
    } else {
        $core->action = Core::$action;
    }

    if (!Core::$id) {
        $core->id = 0;
    } else {
        $core->id = Core::$id;
    }

    $langID =Lang::getLangID();
    $cfg = $core->getComponentConfig("contacts");

    if ($core->action=='view'){
        require_once(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_contacts.php');
    }

    if ($core->action=='send'){

    }


}
?>