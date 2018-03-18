<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 17.02.2016
 * Time: 19:23
 */
function polls(){
    include(PATH . '/components/polls/class_polls.php');
    Registry::set("Polls", new Polls());

    $db = Registry::get("DataBase");
    $core = Registry::get("Core");
    $user=Registry::get("Users");

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
    $cfg = $core->getComponentConfig('polls');

if ($core->action=='view'){

}
}
?>