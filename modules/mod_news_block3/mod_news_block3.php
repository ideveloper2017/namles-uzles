<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 05.02.2016
 * Time: 21:02
 */
function mod_news_block3($module_id){
    $inCore = Registry::get("Core");
    $inDB = Registry::get("DataBase");
    $inConf = Registry::get("Config");
    $content=Registry::get("Content");
    $cfg=$inCore->getModuleConfig($module_id);


    if (!isset($cfg['subs'])) {
        $cfg['subs'] = 1;
    }
    if (!isset($cfg['cat_id'])) {
        $cfg['cat_id'] = 1;
    }
    if (!isset($cfg['tpl'])) {
        $cfg['tpl'] = 'mod_news_block3.php';
    }
    if (!isset($cfg['first_img'])) { $cfg['first_img'] = 1; }


    $cssclass=explode(',',$cfg['cssclass']);
    $today = date("Y-m-d H:i:s");

    if ($cfg['cat_id'] != 1) {
        if (!$cfg['subs']) {
            $catsql = ' AND cb.category_id = ' . $cfg['cat_id'];
        } else {
            $rootcat = $inDB->getValuesById('*','categories',  $cfg['cat_id']);
            $catsql = "AND (cb.category_id= {$rootcat->id})";
        }
    } else {
        $catsql = '';
    }

    $cat_id = $inDB->getFieldById('id', 'categories', "id='{$cfg['cat_id']}'");



    $sql = "
    SELECT
        c.*,
        c.created_at as fpubdate,
        c.id as bid,
        cat.cname as category,cat.slug,
        up.fname  as author,
        u.username as author_login
    FROM content c
    inner JOIN categories_bind cb on cb.item_id=c.id
    inner JOIN categories cat ON cat.id = cb.category_id
    inner JOIN users u ON u.id = c.user_id
    inner JOIN user_profiles up ON up.user_id=u.id
    WHERE c.active = 1 AND (c.is_end=0 OR (c.is_end=1 AND c.end_at >= '$today' AND c.created_at <= '$today'))
    " . $catsql . "
    ORDER BY fpubdate DESC";

    $sql .= "\n" . "LIMIT " . $cfg['newscount'];


    $result = $inDB->query($sql);

    if ($inDB->numrows($result)) {
        $articles = array();
        $acon = $inDB->fetch_all($result,true);
        foreach ($acon as $con) {
            $next = sizeof($articles);
            $text = strip_tags($con['title']);
            $menuid = $inDB->getFieldById('id', 'menus', "link='{$con['slug']}'");
            if ($menuid){
                $menu_id=$menuid;
            }else{
                $menu_id=Registry::get("Menus")->menuId();
            }

            $articles[$next]['author'] = $con['author'];
//          $articles[$next]['authorhref'] = cmsUser::getProfileURL($con['author_login']);
            $articles[$next]['description'] = $con['introtext'];
            $articles[$next]['title'] = $text;
            $articles[$next]['href'] =!empty($con['seo'])? Content::getArticleURL($menu_id, $con['seo']):Content::getArticleURL(null, $con['url']);
            if ($cfg['first_img']){
                $in_desc = preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $con['introtext'], $matches);
                if (!$in_desc) { preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $con['fulltext'], $matches); }

                if ($matches[1]){
                    $test_out = $matches[1];
                    if (preg_match('/http|https/', $test_out)){
                        $law_img = imagecreatefromstring(file_get_contents($test_out));
                        if ($law_img) {
                            $articles[$next]['first_image'] = $test_out;
                        }
                    }
                    else if (file_exists(PATH.$matches[1])) {
                        $articles[$next]['first_image'] = $matches[1];
                    }
                } else {
                    $images=!empty($con['images'])?'/images/content/'. $con['images']:'';
                    $images_medium=!empty($con['images'])?'/images/content/medium/'. $con['images']:'';
                    $images_small=!empty($con['images'])?'/images/content/small/'. $con['images']:'';
                    $articles[$next]['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
                    $articles[$next]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                    $articles[$next]['image_small'] = (file_exists(PATH.$images_small) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                }
            } else {
                $images=!empty($con['images'])?'/images/content/'. $con['images']:'';
                $images_medium=!empty($con['images'])?'/images/content/medium/'. $con['images']:'';
                $images_small=!empty($con['images'])?'/images/content/small/'. $con['images']:'';
                $articles[$next]['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
                $articles[$next]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                $articles[$next]['image_small'] = (file_exists(PATH.$images_small) ? $images_small : '/modules/mod_news_block3/nopic.jpg');
            }
            //$images=!empty($con['images'])?'/images/content/'. $con['images']:'';
            // $articles[$next]['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
            $articles[$next]['hits'] = $con['hits'];
//          $articles[$next]['comments'] = $inCore->getCommentsCount('article', $con['bid']);
            $articles[$next]['cathref'] = Content::getCategoryURL(null,$con['slug']);
            $articles[$next]['cat'] = $con['category'];
            $articles[$next]['photo'] = strstr($con['fulltext'], '<img');
            $articles[$next]['video'] = (strstr($con['fulltext'], '<embed') || strstr($con['content'], '<iframe'));
            $articles[$next]['date'] = date("d.m.Y", strtotime($con['fpubdate']));
            $articles[$next]['day']= date('d', strtotime($con['created_at']));
            $articles[$next]['year']= date('Y', strtotime($con['created_at']));
            $articles[$next]['time']= date('H:i', strtotime($con['created_at']));
            $articles[$next]['month']= Registry::get("Core")->cmsRusDate(date('F', strtotime($con['created_at'])));



            $articles[$next]['menuid']= $menu_id;
//
        }
    }
    $category=$content->getContentCategory($cat_id);
    $categoryhref=$content->getContentCategoryhref($cat_id);

    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/'.$cfg['tpl']);
}
?>