<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 29.12.2015
 * Time: 12:49
 */
function media(){
    include(PATH . "/admin/components/media/class_media.php");
    Registry::set("Media", new Media());
    $media = Registry::get("Media");
    $core = Registry::get('Core');
    $inPage = Registry::get("Page");
    $inDB   = Registry::get("DataBase");

    $cfg    = $core->getComponentConfig('media');

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

    $inPage->addHeadJS('components/media/css/musics.js');
    $pleer=array(
        'music_perpage' => 10,
        'music_order_by' => 'm.pubdate',
        'music_order_to' => 'desc',
        'singers_perpage' => 10,
        'albums_perpage' => 10,
        'one_player' => array(
            'pl' => '/components/media/uppod/uppod.swf',
            'st' => '/components/media/uppod/audioview.txt'
        ),
        'player' => array(
            'pl' => '/components/media/uppod/uppod.swf',
            'st' => '/components/media/uppod/audio58-1087.txt'
        )
    );

   if ($core->action=='view'){
       $musics = $media->getMusic();

        include(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_music_index.php');
   }

//    $inPage->addHeadCSS('/components/media/css/m_styles.css');

    if ($core->action=='audio_view'){
        $sql = "SELECT *
				FROM audio
				WHERE published = 1
				ORDER BY pubdate DESC";
        $result = $inDB->query($sql);

        if ($inDB->numrows($result)){
            $items = array();
            while($con = $inDB->fetch($result,true)){
                $items[] = $con;
            }
            include(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_audio_view.php');
            $is_items = true;
        } else {
            $is_items = false;
        }
    }

    if ($core->action=='view_singers'){
        $signers=$media->getSingers(1,10);

        $sql = "SELECT id FROM audio_singer";
        $total = Registry::get("DataBase")->numrows(Registry::get("DataBase")->query($sql));
        include(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_music_list.php');
    }

    if ($core->action=='view_singer') {
        $singer_id = $_REQUEST['singer_index']?$_REQUEST['singer_index']:0;
        $singer = $media->getSinger($singer_id);
        $musics = $media->getMusic();



        $sql = "SELECT id FROM music WHERE singer_id = '{$singer_id}'";
        $total = $inDB->numrows($inDB->query($sql));

        include(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_music_singer.php');
    }
}
?>