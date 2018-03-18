<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 19.02.2016
 * Time: 22:50
 */

function mod_media($module_id){
    $inCore = Registry::get("Core");
    $inDB = Registry::get("DataBase");
    $inPage = Registry::get("Page");
    $langID=Lang::getLangID();
    $config=Registry::get("Config");
    $cfg=$inCore->getModuleConfig($module_id);

    if ($cfg['media'] == 'video') {
        $sql = "SELECT *
					FROM video
					WHERE published = 1
					ORDER BY pubdate DESC
					LIMIT ".$cfg['newscount'];
        $result = $inDB->query($sql);

        if ($inDB->numrows($result)){
            while ($items=$inDB->fetch($result,true)){
                $item[]=$items;
            }
//            $inPage->addHeadCSS('/modules/mod_video_new/style_new_video.css');
            include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_vmlatest.php');
        }
    }


    if ($cfg['media'] == 'audio') {
        $sql = "SELECT *
					FROM audio
					WHERE published = 1
					ORDER BY pubdate DESC
					LIMIT ".$cfg['newscount'];
        $result = $inDB->query($sql);

        if ($inDB->numrows($result)){
            while ($items=$inDB->fetch($result,true)){
                $item[]=$items;
            }
            include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_amlatest.php');
//            $smarty = $inCore->initSmarty('modules', 'mod_vmlatest.tpl');
//            $smarty->assign('item', $item);
//            $smarty->assign('cfg', $cfg);
//            $smarty->display('mod_vmlatest.tpl');
        }
    }
}
?>