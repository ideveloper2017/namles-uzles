<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 23.12.2015
 * Time: 21:12
 */
function mod_newslider($id){
    $db = Registry::get("DataBase");
    $menu = Registry::get("Menus");
    $user = Registry::get("Users");
    $core = Registry::get("Core");
    $content = Registry::get("Content");
    $langID = Lang::getLangID();
    $config = Registry::get("Config");
    $cfg = $core->getModuleConfig($id);

    if (!$cfg['style']){$cfg['style']=3;}
    if (!$cfg['substyle']){$cfg['substyle']=4;}
    if (!$cfg['listwith']){$cfg['listwidth']=400;}
    if (!$cfg['sliderheight']){$cfg['sliderheight']=300;}
    if (!$cfg['noshadow']){$cfg['noshadow']=0;}
    if (!$cfg['noimage']){$cfg['noimage']=0;}
    if (!$cfg['newscount']){$cfg['newscount']=5;}
    if (!$cfg['speed']){$cfg['speed']=500;}
    if (!$cfg['duration']){$cfg['duration']=5000;}
    if (!$cfg['pause']){$cfg['pause']=1;}
    if (!$cfg['event']){$cfg['event']=1;}
    if (!isset($cfg['subs'])){$cfg['subs']=1;}
    if (!$cfg['ctitle']){$cfg['ctitle']=100;}
    if (!$cfg['cdesc']){$cfg['cdesc']=300;}
    if (!$cfg['anons']){$cfg['anons']=1;}
    if (!$cfg['smallimg']){$cfg['smallimg']=1;}
    if (!$cfg['fullimg']){$cfg['fullimg']=1;}
    if (!isset($cfg['cat_id'])) { $cfg['cat_id'] = 1; }
    if (!isset($cfg['first_img'])) { $cfg['first_img'] = 1; }
    $slidercont = array();
    $langId=Lang::getLangID();

    $sql = "select c.id,c.category_id,c.title,c.images,c.introtext,c.fulltext,c.seo,DATE_FORMAT(c.created_at,'%H:%i %d.%M %Y ') as pubdate,
                  DATE_FORMAT(c.created_at,'%M') as mnth,DATE_FORMAT(c.created_at,'%d') as dy,c.hits,ct.cname as category,ct.slug as cseo,ct.id as catid 
                  FROM content c left join categories_bind cb on cb.item_id=c.id 
                                 left join categories ct on ct.id=cb.category_id
                  where c.active=1 and c.featured=1 and c.lang='{$langId}' ORDER BY c.id desc LIMIT ".$cfg['newscount'];

    $result = $db->query($sql);
    if ($db->numrows($result)) {
        $aitem = $db->fetch_all($result);
        foreach ($aitem as $item) {
            $index=sizeOf($slidercont);
            $menuid=$db->getFieldById('id','menus',"linkid='{$item->catid}'");
            if ($menuid){
                $menu_id=$menuid;
            }else{
                $menu_id=Registry::get("Menus")->menuId();
            }

            $slidercont[$index]['id'] = $item->id;
            $slidercont[$index]['title'] = $item->title;
            $slidercont[$index]['date'] = $core->cmsRusDate($item->pubdate);
            $slidercont[$index]['month'] = $core->cmsRusDateShort($item->mnth);
            $slidercont[$index]['day'] = $core->cmsRusDateShort($item->dy);
            if ($cfg['first_img']){
                $in_desc = preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $item->introtext, $matches);
                if (!$in_desc) { preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $item->fulltext, $matches); }

                if ($matches[1]){
                    $test_out = $matches[1];
                    if (preg_match('/http|https/', $test_out)){
                        $law_img = imagecreatefromstring(file_get_contents($test_out));
                        if ($law_img) {
                            $slidercont[$index]['first_image'] = $test_out;
                        }
                    }
                    else if (file_exists(PATH.$matches[1])) {
                        $slidercont[$index]['first_image'] = $matches[1];
                    }
                } else {
                    $images=!empty($item->images)?'/images/content/'. $item->images:'';
                    $images_medium=!empty($item->images)?'/images/content/medium/'. $item->images:'';
                    $images_small=!empty($item->images)?'/images/content/small/'. $item->images:'';
                    $slidercont[$index]['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
                    $slidercont[$index]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                    $slidercont[$index]['image_small'] = (file_exists(PATH.$images_small) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                }
            } else {
                $images=!empty($item->images)?'/images/content/'. $item->images:'';
                $images_medium=!empty($item->images)?'/images/content/medium/'. $item->images:'';
                $images_small=!empty($item->images)?'/images/content/small/'. $item->images:'';
                $slidercont[$index]['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
                $slidercont[$index]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                $slidercont[$index]['image_small'] = (file_exists(PATH.$images_small) ? $images_small : '/modules/mod_news_block3/nopic.jpg');
            }
//            $slidercont[$index]['image'] = file_exists(PATH.'/images/content/'.$item->images)?$item->images:'';
//            $slidercont[$index]['comments'] = $item['comments'];
            $slidercont[$index]['seo'] = $item->seo;
            $slidercont[$index]['slug']=!empty($item->seo)?Content::getArticleURL($menu_id, $item->seo):Content::getArticleURL($menu_id, $item->seo);
            $slidercont[$index]['category'] = $item->category;

            $slidercont[$index]['hits'] = $item->hits;
            $slidercont[$index]['desc'] = $item->introtext;
        }
    }
    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_newslider.php');
}
?>