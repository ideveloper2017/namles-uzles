<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.05.2017
 * Time: 11:57
 */
function mod_content_carusel($module_id)
{
    $db = Registry::get("DataBase");
    $menu = Registry::get("Menus");
    $core = Registry::get("Core");
    $langID = Lang::getLangID();
    $config = Registry::get("Config");
    $cfg = $core->getModuleConfig($module_id);

    if (!isset($cfg['showrss'])) {
        $cfg['showrss'] = 1;
    }
    if (!isset($cfg['subs'])) {
        $cfg['subs'] = 1;
    }
    if (!isset($cfg['cat_id'])) {
        $cfg['cat_id'] = 1;
    }
    if (!isset($cfg['newscount'])) {
        $cfg['newscount'] = 3;
    }

    if ($cfg['cat_id'] != 1) {
        if (!$cfg['subs']) {
            $where = 'and c.category_id=' . $cfg['cat_id'];
        } else {
            $rootid = $db->getValueById('id', 'categories', $cfg['cat_id']);
            $where = 'and (ct.id=' . $rootid . ' or ct.parent_id=' . $rootid . ')';
        }
    } else {
        $where = '';
    }

    $categories = array();
    $items = array();
    $conrowss = array();

    $today = date("Y-m-d H:i:s");
    $endday = date('Y-d-m');
    $ii = 0;
    $is_items = false;
    $menu_id = $db->getFieldById('id', 'menus', "linkid='{$cfg['cat_id']}'");



    $news = $db->query("SELECT c.*,ct.cname as category FROM content c inner join categories_bind cb on cb.item_id=c.id
                                                                       inner join categories ct on ct.id=cb.category_id
                          where c.active=1 {$where} and c.created_at<='{$today}' and (c.is_end=0 or (c.is_end=1 and c.end_at>='{$today}'))
                          and c.lang='{$langID}'
                          order by c.created_at desc");


    $inc = 0;
    $aconrows = $db->fetch_all($news);
    foreach ($aconrows as $conrows) {
        $inc++;
        $menuid = $db->getFieldById('id', 'menus', "title='{$conrows->category}'");
        if ($menuid) {
            $menu_id = $menuid;
        } else {
            $menu_id = Registry::get("Menus")->menuId();
        }
        $items[$inc]['image'] = file_exists(PATH . '/images/content/' . $conrows->images) ? $conrows->images : '';
        $items[$inc]['title'] = $conrows->title;
        $items[$inc]['description'] = $conrows->introtext;
        $items[$inc]['slug'] = Content::getArticleURL($menu_id, $conrows->seo);
        $items[$inc]['pubdate'] = $core->cmsRusDate($core->dodate($config->long_date, $conrows->created_at));
        $items[$inc]['cat'] = $conrows->category;
        $items[$inc]['hits'] = $conrows->hits;
//        $items[$inc]['comments'] =$conrows->comments;
        $items[$inc]['id'] = $conrows->id;
        $items[$inc]['day'] = date('d', strtotime($conrows->created_at));
        $items[$inc]['year'] = date('Y', strtotime($conrows->created_at));
        $items[$inc]['time'] = date('H:i', strtotime($conrows->created_at));
        $items[$inc]['month'] = $core->cmsRusDate(date('F', strtotime($conrows->created_at)));


        $items[$inc]['menu_id'] = $menu_id;
    }
    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_content_carusel.php');


}

?>
