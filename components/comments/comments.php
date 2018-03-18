<?php
/**
 * Created by PhpStorm.
 * User: IDeveloper
 * Date: 20.10.2015
 * Time: 15:06
 */
function comments($target = '', $target_id = 0)
{
    $db = Registry::get("DataBase");
    $config = Registry::get("Config");
    $user = Registry::get("Users");
    $core = Registry::get("Core");
    $comment = Registry::get("Comments");
    $pager=Registry::get("Paginator");

    $cfg = Registry::get("Core")->getComponentConfig("comments");
    $comrows = $comment->getComments($target, $target_id);

    require_once(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_comments.php');

}

?>