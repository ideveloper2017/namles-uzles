<?php
function mod_feature($id){
    $db=Registry::get("DataBase");
    $menu=Registry::get("Menus");
    $core=Registry::get("Core");
    $langID=Lang::getLangID();
    $config=Registry::get("Config");
    $cfg=$core->getModuleConfig($id);

    if (!isset($cfg['showrss'])) { $cfg['showrss'] = 1; }
    if (!isset($cfg['subs'])) { $cfg['subs'] = 1; }
    if (!isset($cfg['cat_id'])) { $cfg['cat_id'] = 1; }
    if (!isset($cfg['newscount'])) { $cfg['newscount'] = 3; }
    if (!isset($cfg['first_img'])) { $cfg['first_img'] = 1; }
    if($cfg['cat_id']!=1) {
        if (!$cfg['subs']){
            $where='and c.category_id='.$cfg['cat_id'];
        }else{
            $rootid= $db->getValueById('id','categories',$cfg['cat_id']);
            $where='and cb.category_id='.$rootid;
        }
    }else{
        $where='';
    }
    $categories = array();
    $items = array();
    $conrowss = array();
    $today = date("Y-m-d H:i:s");
    $endday = date('Y-d-m');
    $ii=0;
    $is_items=false;
    $result = Registry::get("DataBase")->query("SELECT c.*,ct.cname as category FROM content c inner join categories_bind cb on cb.item_id=c.id
                                                                       inner join categories ct on ct.id=cb.category_id
                          where c.active=1 and c.featured=1 {$where} and c.created_at<='{$today}' and (c.is_end=0 or (c.is_end=1 and c.end_at>='{$today}'))
                          and c.lang='{$langID}'
                          order by c.created_at desc");
    $counter = Registry::get("DataBase")->numrows($result);
    $pager = Registry::get("Paginator");
    $pager->items_total = $counter;
    $pager->default_ipp = $cfg['newscount'];
    $pager->paginate();
    if ($counter == 0) {
        $pager->limit = null;
    }
    $news = $db->query("SELECT c.*,ct.cname as category,ct.slug as catseo,DATE_FORMAT(c.created_at,'%d.%m %Y  %H:%i') as pubdate FROM content c inner join categories_bind cb on cb.item_id=c.id
                                                                       inner join categories ct on ct.id=cb.category_id
                          where c.active=1 and c.featured=1 {$where} and c.created_at<='{$today}' and (c.is_end=0 or (c.is_end=1 and c.end_at>='{$today}'))
                          and c.lang='{$langID}'
                          order by c.created_at desc limit 0,".$cfg['newscount']);
    $inc = 0;
    $aconrows=$db->fetch_all($news);
    foreach ($aconrows as $conrows){
        $inc++;
        $menuid = $db->getFieldById('id', 'menus', "title='{$conrows->category}'");
        if ($menuid){
            $menu_id=$menuid;
        }else{
            $menu_id=Registry::get("Menus")->menuId();
        }
        $items[$inc]['menu_id'] =$menu_id;
        //$items[$inc]['image'] = file_exists(PATH.'/images/content/' . $conrows->images)? $conrows->images:'';
        if ($cfg['first_img']){
            $in_desc = preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $conrows->introtext, $matches);
            if (!$in_desc) { preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $conrows->fulltext, $matches); }

            if ($matches[1]){
                $test_out = $matches[1];
                if (preg_match('/http|https/', $test_out)){
                    $law_img = imagecreatefromstring(file_get_contents($test_out));
                    if ($law_img) {
                        $items[$inc]['first_image'] = $test_out;
                    }
                }
                else if (file_exists(PATH.$matches[1])) {
                    $items[$inc]['first_image'] = $matches[1];
                }
            } else {
                $images=!empty($conrows->images)?'/images/content/'. $conrows->images:'';
                $images_medium=!empty($conrows->images)?'/images/content/medium/'. $conrows->images:'';
                $images_small=!empty($conrows->images)?'/images/content/small/'. $conrows->images:'';
                $items[$inc]['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
                $items[$inc]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                $items[$inc]['image_small'] = (file_exists(PATH.$images_small) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
            }
        } else {
            $images=!empty($conrows->images)?'/images/content/'. $conrows->images:'';
            $images_medium=!empty($conrows->images)?'/images/content/medium/'. $conrows->images:'';
            $images_small=!empty($conrows->images)?'/images/content/small/'. $conrows->images:'';
            $items[$inc]['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
            $items[$inc]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
            $items[$inc]['image_small'] = (file_exists(PATH.$images_small) ? $images_small : '/modules/mod_news_block3/nopic.jpg');
        }
        $items[$inc]['title'] =$conrows->title;
        $items[$inc]['description'] =$conrows->introtext;
        $items[$inc]['slug'] =!empty($conrows->seo)?Content::getArticleURL($menuid, $conrows->seo):$conrows->url;
        $items[$inc]['pubdate'] =  $conrows->pubdate;
        $items[$inc]['catseo'] =Registry::get("Content")->getCategoryURL(null,$conrows->catseo);
        $items[$inc]['cat'] =$conrows->category;
        $items[$inc]['hits'] =$conrows->hits;
//        $items[$inc]['comments'] =$conrows->comments;
        $items[$inc]['id'] =$conrows->id;
        $items[$inc]['day']         = date('d', strtotime($conrows->created_at));
        $items[$inc]['year']        = date('Y', strtotime($conrows->created_at));
        $items[$inc]['time']        = date('H:i', strtotime($conrows->created_at));
        $items[$inc]['month']       = $core->cmsRusDate(date('F', strtotime($conrows->created_at)));

    }

    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_feature.php');
}
?>