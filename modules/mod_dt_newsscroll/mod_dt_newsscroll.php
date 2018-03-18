<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 29.09.2016
 * Time: 19:33
 */
function mod_dt_newsscroll($module_id){
    $db = Registry::get("DataBase");
    $menu = Registry::get("Menus");
    $user = Registry::get("Users");
    $core = Registry::get("Core");
    $content = Registry::get("Content");
    $langID = Lang::getLangID();
    $config = Registry::get("Config");
    $cfg = $core->getModuleConfig($module_id);
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
    $slidercont = array();
    $langId=Lang::getLangID();
    $sql = "SELECT c.id,c.category_id,c.title,c.images,c.introtext,c.seo,DATE_FORMAT(c.created_at,'%H:%i %d/%m/%Y ') as pubdate,c.hits,ct.cname as category FROM content c left join categories ct on ct.id=c.category_id
                                WHERE c.active=1 and c.lang='{$langId}' ORDER BY c.id desc LIMIT ".$cfg['newscount'];

    $result = $db->query($sql);
    if ($db->numrows($result)) {
        while ($item = $db->fetch($result)) {
            $index=sizeOf($slidercont);
            $slidercont[$index]['id'] = $item->id;
            $slidercont[$index]['title'] = $item->title;
            $slidercont[$index]['date'] = $core->cmsRusDate($item->pubdate);
            $slidercont[$index]['image'] = file_exists(PATH.'/images/content/'.$item->images)?$item->images:'';
//            $slidercont[$index]['comments'] = $item['comments'];
            $slidercont[$index]['url'] = $item->seo;
            $slidercont[$index]['category'] = $item->category;


            $slidercont[$index]['hits'] = $item->hits;
            $slidercont[$index]['desc'] = $item->introtext;
        }
    }
    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_dt_newsscroll.php');
}