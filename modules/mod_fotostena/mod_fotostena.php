<?php

function mod_fotostena($module_id){

    $inPhoto = Registry::get('Photos');
    $inCore  = Registry::get('Core');
    $inDB    = Registry::get('DataBase');
    $cfg = $inCore->getModuleConfig($module_id);
    $default_cfg = array (
        'is_full' => 1,
        'showmore' => 1,
        'album_id' => 0,
        'whatphoto' => 'all',
        'shownum' => 15,
        'colnum'     => 4,
        'maxcols' => 2,
        'sort' => 'pubdate',
        'showclubs' => 0,
        'is_subs' => 1
    );
    $cfg = array_merge($default_cfg);
//    var_dump($cfg);
    // выбираем категории фото
//    $sql='select * from INNER JOIN photo_albums a ON a.id = f.album_id AND a.published = 1';
//    $inDB->addSelect('a.title as cat_title, a.NSDiffer');

    // если категория задана, выбираем из нее
//    if($cfg['album_id']){
//        if($cfg['is_subs']){
//            $album = $inDB->getNsCategory('cms_photo_albums', $cfg['album_id']);
//            if (!$album) { return false; }
//            $inPhoto->whereThisAndNestedCats($album['NSLeft'], $album['NSRight']);
//        } else {
//            $inPhoto->whereAlbumIs($cfg['album_id']);
//        }
//
//    }

//    if(!$cfg['showclubs']){
//        $inDB->where("f.owner = 'photos'");
//    }
//    $inPhoto->wherePeriodIs($cfg['whatphoto']);
//
//    $inDB->orderBy('f.'.$cfg['sort'], 'DESC');
//
//    //устанавливаем номер текущей страницы и кол-во фото на странице
//    $inDB->limit($cfg['shownum']);

    // получаем фото
    $photos = $inPhoto->getPhotos(false, $cfg['is_full']);
    if(!$photos) { return false; }
    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_fotostena.php');

//    $smarty = $inCore->initSmarty('modules', 'mod_fotostena.tpl');
//    $smarty->assign('photos', $photos);
//    $smarty->assign('cfg', $cfg);
//    $smarty->display('mod_fotostena.tpl');

    return true;

}
?>