<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 05.02.2016
 * Time: 20:20
 */
function mod_content_cat($module_id){
   $inCore=Registry::get("Core");
   $inDB=Registry::get("DataBase");
    $cfg=$inCore->getModuleConfig($module_id);
    $parent_id=$cfg['parent_id'];
    $cats       = array();
    $pub_where  = 'AND active=1';

    if (!$parent_id) {
        $parent_where = 'parent_id > 0';
    }

    if ($parent_id) {
        $parent = $inDB->getValueById('id','categories', "id={$parent_id}");
        $parent_where = "parent_id={$parent}";
    }

    $sql  = "SELECT *
             FROM categories
             WHERE {$parent_where} {$pub_where}
             ORDER BY id";

    $res=$inDB->query($sql);
    if ($inDB->numrows($res)){
            while($cat=$inDB->fetch($res,true)){
                $cats[]=$cat;
            }
    }

    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_content_cat.php');
}
?>