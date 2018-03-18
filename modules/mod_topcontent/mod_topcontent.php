<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 22.12.2015
 * Time: 17:38
 */
function mod_topcontent($id){
    $db=Registry::get("DataBase");
    $menu=Registry::get("Menus");
    $core=Registry::get("Core");
    $langID=Lang::getLangID();
    $cfg=$core->getModuleConfig($id);
    if (!$cfg['limit']) { $limit=10;} else {$limit=$cfg['limit'];}
    if (!$cfg['sort']) {$cfg['sort'];}
    if (!isset($cfg['first_img'])) { $cfg['first_img'] = 1; }

    if ($cfg['sort']=='hits') {
        $sql = "SELECT c.*,DATE_FORMAT(c.created_at,'%H:%i %d.%M %Y') as pubdate,
                        ct.cname as category FROM content c
                        inner JOIN categories_bind cb on cb.item_id=c.id
                        inner JOIN categories ct ON ct.id = cb.category_id
                        where c.active=1 and is_archive=0 and is_end=0 and c.lang='{$langID}' and c.hits<>0 order by c.{$cfg['sort']} desc LIMIT " . $limit;
    }

    if ($cfg['sort']=='created_at'){
        $sql = "SELECT c.*,DATE_FORMAT(c.created_at,' %d.%M %Y') as pubdate, ct.cname as category FROM content c
                                  inner JOIN categories_bind cb on cb.item_id=c.id
                                 inner JOIN categories ct ON ct.id = cb.category_id
                                  where c.active=1 and is_archive=0 and is_end=0 and c.lang='{$langID}'  order by c.{$cfg['sort']} desc LIMIT " . $limit;
    }


    $result=$db->query($sql);
    $is_tems=false;
    $hitcontents=array();
    $inc=0;
    if ($db->numrows($result)>0){
        $is_tems=true;
        $arows=$db->fetch_all($result);
        foreach ($arows as $rows){
            $inc++;
            $menuid = $db->getFieldById('id', 'menus', "title='{$rows->category}'");

            if ($menuid){
                $menu_id=$menuid;
            }else{
                $menu_id=Registry::get("Menus")->menuId();
            }
            $hitcontents[$inc]['menu_id']=$menu_id;
//            $images=file_exists(PATH."/images/content/medium/".$rows->images)?$rows->images:'';
            if ($cfg['first_img']){
                $in_desc = preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $rows->introtext, $matches);
                if (!$in_desc) { preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $rows->fulltext, $matches); }

                if ($matches[1]){
                    $test_out = $matches[1];
                    if (preg_match('/http|https/', $test_out)){
                        // $hitcontents[$inc]['first_image'] = $test_out;
                        $law_img = imagecreatefromstring(file_get_contents($test_out));
                        if ($law_img) {
                            $hitcontents[$inc]['first_image'] = $test_out;
                        }
                    }
                    else if (file_exists(PATH.$matches[1])) {
                        $hitcontents[$inc]['first_image'] = $matches[1];
                    }

                } else {
                    $images=!empty($rows->images)?'/images/content/'. $rows->images:'';
                    $images_medium=!empty($rows->images)?'/images/content/medium/'. $rows->images:'';
                    $images_small=!empty($rows->images)?'/images/content/small/'. $rows->images:'';
                    $hitcontents[$inc]['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
                    $hitcontents[$inc]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                    $hitcontents[$inc]['image_small'] = (file_exists(PATH.$images_small) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                }
            } else {
                $images=!empty($rows->images)?'/images/content/'. $rows->images:'';
                $images_medium=!empty($rows->images)?'/images/content/medium/'. $rows->images:'';
                $images_small=!empty($con['images'])?'/images/content/small/'. $rows->images:'';
                $hitcontents[$inc]['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
                $hitcontents[$inc]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                $hitcontents[$inc]['image_small'] = (file_exists(PATH.$images_small) ? $images_small : '/modules/mod_news_block3/nopic.jpg');
            }
            $hitcontents[$inc]['id']=$rows->id;
            $hitcontents[$inc]['title']=$rows->title;
//            $hitcontents[$inc]['date']=$core->cmsRusDate($rows->pubdate);
            $hitcontents[$inc]['date'] = date("d.m.Y", strtotime($rows->created_at));
            $hitcontents[$inc]['day']         = date('d', strtotime($rows->created_at));
            $hitcontents[$inc]['year']        = date('Y', strtotime($rows->created_at));
            $hitcontents[$inc]['time']        = date('H:i', strtotime($rows->created_at));
            $hitcontents[$inc]['month']       = Registry::get("Core")->cmsRusDate(date('F', strtotime($rows->created_at)));
            $hitcontents[$inc]['desc']=$rows->introtext;
            $hitcontents[$inc]['category']=$rows->category;
            $hitcontents[$inc]['slug']=Content::getArticleURL($menuid, $rows->seo);
            //$hitcontents[$inc]['image']=$images;
//            $hitcontents[$inc]['comments']=intval($rows->comments']);
            $hitcontents[$inc]['hits']=intval($rows->hits);
//            echo $rows->category;

//          $hitcontents[$inc]['image']=$images
        }
        include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_topcontent.php');
    }

}
?>