<?php

session_start();

/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 04.08.13
 * Time: 18:45
 * To change this template use File | Settings | File Templates.
 */

function content()

{
    $db = Registry::get("DataBase");
    $config = Registry::get("Config");
    $users = Registry::get("Users");
    $core = Registry::get("Core");

    $core->loadClass("content");
    $model=new model_content();


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

    $langID =Lang::getLangID();
    $cfg = $core->getComponentConfig("content");
    if (!isset($cfg['published'])) {
        $cfg['published'] = 0;
    }
    if (!isset($cfg['first_img'])) { $cfg['first_img'] = 1; }
    $content = array();
//    echo $core->action;
    if ($core->action == 'view') {

//        $art_id=((!empty(Core::$get['id']) AND preg_match('/^[a-z0-9A-Z0-9\-]+$/',Core::$get['id']))?Core::$get['id']:0);
        $slug=((!empty(Core::$get['seo']) AND preg_match("/^(.+?)$/ui",Core::$get['seo']))?Core::$get['seo']:'');
//        $slug=Core::$get['seo'];

        $catid=$db->getFieldById('id','categories',"slug='{$slug}'");

        $items_found = false;
        $pager=Registry::get("Paginator");
        $counter=$db->numrows($db->query("SELECT c.*,u.username as author,ct.cname as cattitle FROM content c left join users u on u.id=c.user_id
                                                                            left join categories_bind cb on cb.item_id=c.id
                                                                            left join categories ct on ct.id=cb.category_id
                                                                            where (cb.category_id='{$catid}' or ct.parent_id='{$catid}' or ct.slug='{$slug}') and c.lang='{$langID}' and c.active=1 order by created_at desc"));
        $pager->items_total=$counter;
        $pager->default_ipp = 20;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }
        $menuID = Registry::get("Menus")->menuId();
        $query = $db->query("SELECT c.*,u.username as author,ct.cname as cattitle,ct.slug as cseo,ct.tpl FROM content c left join users u on u.id=c.user_id
                                                                            left join categories_bind cb on cb.item_id=c.id
                                                                            left join categories ct on ct.id=cb.category_id
                                                                            where (cb.category_id='{$catid}' or ct.parent_id='{$catid}' or ct.slug='{$slug}') and c.lang='{$langID}' and c.active=1 order by created_at desc ".$pager->limit);
//                                                                          where (cb.category_id='{$art_id}' or ct.slug='{$slug}') and c.lang='{$langID}' and c.active=1 order by created_at desc ".$pager->limit;
        $viewContent = array();
        $cont = 0;
        if (@$db->numrows($query)) {
            $aviewCont = $db->fetch_all($query);
            foreach ($aviewCont as $viewCont) {
                $items_found = true;
                $cont++;
                $menu_id=$db->getFieldById('id','menus',"link='{$viewCont->cseo}'");
                $cate_name=$db->getFieldById('cname','categories',"slug='{$viewCont->cseo}'");
                $viewContent[$cont]['id'] = $viewCont->id;
                $viewContent[$cont]['author'] = $viewCont->author;
                $viewContent[$cont]['title'] = $viewCont->title;
                $viewContent[$cont]['date'] =$core->cmsRusDate($core->dodate($config->long_date, $viewCont->created_at));
                $viewContent[$cont]['desc'] = $viewCont->introtext;
                $viewContent[$cont]['slug'] = Content::getArticleURL($menu_id, $viewCont->seo);
//                $viewContent[$cont]['slug'] =  $viewCont->seo;
                $viewContent[$cont]['fulltext'] = $viewCont->fulltext;
                $viewContent[$cont]['hits'] = $viewCont->hits;
//                $viewContent[$cont]['comment'] = $viewCont['comments'];
//                $images=!empty($viewCont->images)?'/images/content/'. $viewCont->images:'';
//                $viewContent[$cont]['image'] = (file_exists(PATH.$images) ? $images : '../modules/mod_news_block3/nopic.jpg');
                if ($cfg['first_img']){
                    $in_desc = preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $viewCont->introtext, $matches);
                    if (!$in_desc) { preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $viewCont->fulltext, $matches); }
                    if ($matches[1]){
                        $test_out = $matches[1];
                        if (preg_match('/http|https/', $test_out)){
                            $law_img = imagecreatefromstring(file_get_contents($test_out));
                            if ($law_img) {
                                $viewContent[$cont]['first_image'] = $test_out;
                            }
                        }
                        else if (file_exists(PATH.$matches[1])) {
                            $viewContent[$cont]['first_image'] = $matches[1];
                        }
                    } else {
                        $images=!empty($viewCont->images)?'/images/content/'. $viewCont->images:'';
                        $images_medium=!empty($viewCont->images)?'/images/content/medium/'. $viewCont->images:'';
                        $images_small=!empty($viewCont->images)?'/images/content/small/'. $viewCont->images:'';
                        $viewContent[$cont]['image'] = (file_exists(PATH.$images) ? $images : '../modules/mod_news_block3/nopic.jpg');
                        $viewContent[$cont]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '../modules/mod_news_block3/nopic.jpg');
                        $viewContent[$cont]['image_small'] = (file_exists(PATH.$images_small) ? $images_small : '../modules/mod_news_block3/nopic.jpg');
                    }
                } else {
                    $images=!empty($viewCont->images)?'/images/content/'. $viewCont->images:'';
                    $images_medium=!empty($viewCont->images)?'/images/content/medium/'. $viewCont->images:'';
                    $images_small=!empty($viewCont->images)?'/images/content/small/'. $viewCont->images:'';
                    $viewContent[$cont]['image'] = (file_exists(PATH.$images) ? $images : '../modules/mod_news_block3/nopic.jpg');
                    $viewContent[$cont]['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '../modules/mod_news_block3/nopic.jpg');
                    $viewContent[$cont]['image_small'] = (file_exists(PATH.$images_small) ? $images_small : '../modules/mod_news_block3/nopic.jpg');
                }
//                $cat_parent_id=$db->getFieldById('parent_id','categories',"slug='{$viewCont->cseo}'");
//                $viewContent[$cont]['cseoprimary'] = $db->getFieldById('slug','categories','id='.$cat_parent_id);
                $viewContent[$cont]['cseo'] = $viewCont->cseo;
                $viewContent[$cont]['cat'] = $viewCont->cattitle;
                $viewContent[$cont]['day']         = date('d', strtotime($viewCont->created_at));
                $viewContent[$cont]['year']        = date('Y', strtotime($viewCont->created_at));
                $viewContent[$cont]['time']        = date('H:i', strtotime($viewCont->created_at));
                $viewContent[$cont]['month']       = $core->cmsRusDate(date('F', strtotime($viewCont->created_at)));
                $viewContent[$cont]['menu_id']       =$menu_id;
                $cattitle=$viewCont->cattitle;
                $tpl=$viewCont->tpl;
            }
        }else {
            $items_found=false;
        }
        require_once(PATH . '/theme/' . Registry::get("Config")->template . '/components/'.$tpl);
    }


    if ($core->action == 'read') {
        $article = array();
        $slug=((!empty(Core::$get['seo']) AND preg_match("/^(.+?)$/ui",Core::$get['seo']))?Core::$get['seo']:'');

//      $slug=Core::$get['seo'];
        $items_found = false;
//      echo $slug;
        $modarticle=$model->getArticle($slug);

        if (!empty($modarticle)) {
            $items_found=true;
            $modarticle = Registry::get("Core")->getCallEvent('GET_ARTICLE', $modarticle);
            $menu_id=$db->getFieldById('id','menus',"link='{$modarticle->catseo}'");

            $article['id'] = $modarticle->id;
            $article['cat'] = $modarticle->cat;
            $article['menu_id'] = $menu_id;
            $article['catseo'] = $modarticle->catseo;
            $article['title'] = $modarticle->title;
            $article['introtext'] = $modarticle->introtext;
            $article['fultext'] = $modarticle->fulltext;
            $article['seo'] = Content::getArticleURL($menu_id, $modarticle->seo);
            $article['meta_keywords'] = $modarticle->meta_keywords;
            $article['meta_desc'] = $modarticle->meta_desc;

            if ($cfg['first_img']){
                $in_desc = preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $modarticle->introtext, $matches);
                if (!$in_desc) { preg_match('/<img\s?[^>]*?\s?src="(.*?)"\s?[^>]*?\s?\/>/is', $modarticle->fulltext, $matches); }
                if ($matches[1]){
                    $test_out = $matches[1];
                    if (preg_match('/http|https/', $test_out)){
                        $law_img = imagecreatefromstring(file_get_contents($test_out));
                        if ($law_img) {
                            $article['first_image'] = $test_out;
                        }
                    }
                    else if (file_exists(PATH.$matches[1])) {
                        $article['first_image'] = $matches[1];
                    }
                } else {
                    $images=!empty($modarticle->images)?'/images/content/'. $modarticle->images:'';
                    $images_medium=!empty($modarticle->images)?'/images/content/medium/'. $modarticle->images:'';
                    $images_small=!empty($modarticle->images)?'/images/content/small/'. $modarticle->images:'';
                    $article['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
                    $article['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                    $article['image_small'] = (file_exists(PATH.$images_small) ? $images_small : '/modules/mod_news_block3/nopic.jpg');
                }
            } else {
                $images=!empty($modarticle->images)?'/images/content/'. $modarticle->images:'';
                $images_medium=!empty($modarticle->images)?'/images/content/medium/'. $modarticle->images:'';
                $images_small=!empty($modarticle->images)?'/images/content/small/'. $modarticle->images:'';
                $article['image'] = (file_exists(PATH.$images) ? $images : '/modules/mod_news_block3/nopic.jpg');
                $article['image_medium'] = (file_exists(PATH.$images_medium) ? $images_medium : '/modules/mod_news_block3/nopic.jpg');
                $article['image_small'] = (file_exists(PATH.$images_small) ? $images_small : '/modules/mod_news_block3/nopic.jpg');
            }

//            $article['image'] = (file_exists(PATH . '/images/content/' . $modarticle->images) ? $modarticle->images : '');
            $article['content'] = $modarticle->fulltext ."\n". $modarticle->jscode ."\n". $modarticle->content;
            $article['config'] = unserialize($modarticle->attribs);
            $article['author'] = $modarticle->author;
            $article['published'] = $core->cmsRusDate($core->dodate($config->long_date, $modarticle->created_at));
            $article['hits'] = $modarticle->hits;
            $article['tags'] = Registry::get("Content")->getTagLine('article', $modarticle->id, true);
            $article['day'] = date('d', strtotime($modarticle->created_at));
            $article['year'] = date('Y', strtotime($modarticle->created_at));
            $article['time'] = date('H:i', strtotime($modarticle->created_at));
            $article['month'] = $core->cmsRusDate(date('F', strtotime($modarticle->created_at)));
            $hits = $modarticle->hits + 1;
            $hit = intval($hits);
            $db->update("content", array('hits' => $hit), 'id=' . $modarticle->id);
            $article['content'] = $core->processFilters($article['content']);
            $tpl=$modarticle->tpl;

            $sit_url = $article['seo'];
            if ($sit_url) {
                $GLOBALS['site_url'] = $sit_url;
            }

            $article['first_image']?$GLOBALS['page_soc_image'] = 'http://' . $_SERVER['HTTP_HOST']  . $article['first_image']:
                $GLOBALS['page_soc_image'] = 'http://' . $_SERVER['HTTP_HOST']  . $article['image'];

            if ($article['title']) {
                $GLOBALS['page_title'] = $article['title'];
            } else {
                $GLOBALS['page_title'] = $config->sitename;
            }
            if ($article['meta_keywords']) {
                $GLOBALS['page_keys'] = $article['meta_keywords'];
            } else {
                $GLOBALS['page_keys'] = $config->keywords;
            }
            if ($article['meta_desc']) {
                $GLOBALS['page_desc'] = cleanOut(strip_tags($article['introtext']));
            } else {
                $GLOBALS['page_desc'] = $config->metadesc;
            }
        }else {
            $items_found=false;
        }
        require_once(PATH . '/theme/' . Registry::get("Config")->template . '/components/'.$tpl);
    }

    if ($core->action=='edit'){
        $article_id=Core::$get['id'];

    }

    if ($core->action == 'all') {
        if (isset($_REQUEST['cats'])) {
            $catid = $_REQUEST['cats'];
            $sqlwhere = "c.category_id=" . $catid . " and ";
        } else {
            $sqlwhere = ' ';
            $catid = -1;
        }
        $selectcurrent = '';
        $catquery = $db->query("SELECT * FROM  category ct  where ct.parent_id>0 and ct.lang={$langID}  ");
        $viewCategory = array();
        $viewContent = array();
        $cont = 0;
        $cc = 0;
        while ($viewCont = $db->fetch_assoc($catquery)) {
            $cont++;
            $viewCategory[$cont]['id'] = $viewCont['id'];
            $viewCategory[$cont]['cat'] = $viewCont['title'];
        }
        $dayName = $ker->get_Date(strtotime($db->getGroupField('content', 'pubdate')));
        $cquery = $db->query("SELECT c.*,u.nickname as author,ct.title as cattitle,ct.id as catid FROM content c left join Users u on u.id=c.userid
                                                                                                left join category ct on ct.id=c.category_id
                                                                            where  {$sqlwhere} c.lang={$langID}");
        $is_article = false;
        if ($db->num_rows($cquery)) {
            $is_article = true;
            while ($crows = $db->fetch_assoc($cquery)) {
                $cc++;
                $viewContent[$cc]['id'] = $crows['id'];
                $viewContent[$cc]['title'] = $crows['title'];
                $viewContent[$cc]['date'] = $ker->get_Date(strtotime($crows['pubdate']));
                $viewContent[$cc]['time'] = substr($crows['pubdate'], 10, 6);
                $viewContent[$cc]['desc'] = $crows['description'];
                $viewContent[$cc]['fulltext'] = $crows['content'];
                $viewContent[$cc]['image'] = '/images/photos/small/article' . $crows['id'] . '.jpg';
                $viewContent[$cc]['cat'] = $crows['cattitle'];
                $viewContent[$cc]['catid'] = $crows['catid'];
                $viewContent[$cc]['comment'] = $crows['comments'];
            }
        }
        $smarty = $ker->smartyInitComponent();
        $smarty->assign('items', $viewCategory);
        $smarty->assign('article', $viewContent);
        $smarty->assign('is_article', $is_article);
        $smarty->assign('cats', $catid);
        $smarty->display('com_content_all.tpl');
    }


    if ($core->action == 'articles') {
        if (isset($_REQUEST['artid'])) {
            $catid = $_REQUEST['artid'];
            $sqlwhere = "c.category_id=" . $catid . " and ";
        } else {
            $sqlwhere = ' ';
            $catid = -1;
        }
        $selectcurrent = '';
        $catquery = $db->query("SELECT * FROM  category ct  where ct.parent_id>0 and ct.lang={$langID}  ");
        $viewCategory = array();
        $viewContent = array();
        $cont = 0;
        $cc = 0;
        while ($viewCont = $db->fetch_assoc($catquery)) {
            $cont++;
            $viewCategory[$cont]['id'] = $viewCont['id'];
            $viewCategory[$cont]['cat'] = $viewCont['title'];
        }
        $dayName = $ker->get_Date(strtotime($db->getGroupField('content', 'pubdate')));
        $cquery = $db->query("SELECT c.*,u.nickname as author,ct.title as cattitle,ct.id as catid FROM content c left join Users u on u.id=c.userid
                                                                                               left join category ct on ct.id=c.category_id
                                                                            where  {$sqlwhere} c.lang={$langID}");
        $is_article = false;
        if ($db->num_rows($cquery)) {
            $is_article = true;
            while ($crows = $db->fetch_assoc($cquery)) {
                $cc++;
                $viewContent[$cc]['id'] = $crows['id'];
                $viewContent[$cc]['title'] = $crows['title'];
                $viewContent[$cc]['date'] = $ker->get_Date(strtotime($crows['pubdate']));
                $viewContent[$cc]['time'] = substr($crows['pubdate'], 10, 6);
                $viewContent[$cc]['desc'] = $crows['description'];
                $viewContent[$cc]['fulltext'] = $crows['content'];
                $viewContent[$cc]['image'] = '/images/photos/small/article' . $crows['id'] . '.jpg';
                $viewContent[$cc]['cat'] = $crows['cattitle'];
                $viewContent[$cc]['catid'] = $crows['catid'];
                $viewContent[$cc]['comment'] = $crows['comments'];
            }
        }
        $smarty = $ker->smartyInitComponent();
        $smarty->assign('items', $viewCategory);
        $smarty->assign('article', $viewContent);
        $smarty->assign('is_article', $is_article);
        $smarty->assign('artid', $catid);
        $smarty->display('com_content_article_all.tpl');

    }


    if ($core->action == 'addArticle') {
        if ($users->getUserAccess("content/add")) {
            if (!isset($_REQUEST['add_mod'])) {
                $messsage = isset($_SESSION['message']) ? $_SESSION['message'] : '';
                unset($_SESSION['message']);
                $smarty = $core->smartyInitComponent();
                $lang = LangList(0);
                $pubcats = CategoryList(1, 1, 0);
                $smarty->assign('pubcats', $pubcats);
                $smarty->assign("message", $messsage);
                $smarty->assign('lang', $lang);
                $smarty->register_function('wysiwyg', 'SmartyWysiwyg');
                $smarty->display('com_content_add.tpl');
            }
        } else {
            echo 'Доступ запрещен.';
        }
    }


    if ($core->action == 'saveArticle') {
        unset($_SESSION['message']);
        $title = mysqli_real_escape_string(stripcslashes($_REQUEST['title']));
        $content = mysqli_real_escape_string(stripcslashes($_REQUEST['content']));
        $description = mysqli_real_escape_string(stripcslashes($_REQUEST['description']));
        $pubdate = date('Y-m-d H:i:s');
        $lng = $_REQUEST['lang'];
        if ($user->getUserAccess('content/autoadd')) {
            $published = 1;
        } else {
            $published = 0;
        }
        $tags = $_REQUEST['tags'];
        $featured = 0;
        $userid = $user->getUserID($_SESSION['user']['username']);
        $category_id = (int)$_REQUEST['category_id'];
        $showtitle = 1;
        $showdate = 1;
        $url = $_REQUEST['url'];
        $isend = 0;
        $enddate = date('Y-m-d');
        $featured = intval($_REQUEST['featured']);
        if (!empty($_REQUEST['meta_desc'])) {
            $meta_desc = $_REQUEST['meta_desc'];
        } else {
            $meta_desc = $_REQUEST['title'];
        }
        if (!empty($_REQUEST['meta_keys'])) {
            $meta_keys = $_REQUEST['meta_keys'];
        } else {
            $meta_keys = mysqli_real_escape_string(strip_tags($ker->getKeywords($_REQUEST['content'])));
        }
        $savquery = $db->query("insert into content set category_id='{$category_id}',userid='{$userid}',title='{$title}',pubdate='{$pubdate}',
                                                        content='{$content}',description='{$description}',lang='{$lng}',
                                                        published='{$published}',showtitle='{$showtitle}',showdate='{$showdate}',source='{$url}',
                                                        meta_keywords='{$meta_keys}',featured='{$featured}',enddate='{$enddate}',meta_desc='{$meta_desc}'");
        if ($savquery) {
            $filename = 'article' . $db->insertID();
            $itemid = $db->insertID();
            if (!empty($tags)) {
                $tags = $_REQUEST['tags'];
            } else {
                $tags = $title;
            }
            if ($tags != '') {
                $keywords = array();
                $tags = preg_replace("/\s+/ums", " ", $tags);
                $tags = preg_replace("/([[:punct:]]|[[:digit:]]|(\s)+)/ui", " ", $tags);
                $arr = explode(" ", $tags);
                for ($i = 0; $i < count($arr); $i++) {
                    if (strlen($arr[$i]) > 3) {
                        $arr[$i] = trim($arr[$i]);
                        $keywords[] = $arr[$i];
                    }
                }
                if (sizeof($keywords) != 0) {
                    $keywords = array_unique($keywords);
                    shuffle($keywords);
                    $keywords = array_slice($keywords, 0, 15);
                    $tag = implode(',', $keywords);
                    $tag = trim($tag);
                }
            }
            insertTags('article', $tag, $itemid);
            //$extention = substr($_FILES['picture']['name'], strpos($_FILES['picture']['name'], '.'));
            $file = $filename . '.jpg';
            $path = PATH . '/images/photos/originals/' . $file . '';
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $path)) {
                $original_path = $path;
                $new_path = PATH . '/images/photos/small/' . $file;
                $photos->createThumbnail($original_path, $new_path, 125);
                $midle_path = PATH . '/images/photos/medium/' . $file;
                $photos->createThumbnail($original_path, $midle_path, 250);
            }
            $_SESSION['message'] = 'Маълумот мувафаккиятили сақланди !!!';
        } else {
            $_SESSION['message'] = 'Маълумот сақланмади !!!';
        }
        // header('location:/content/my.html');
        if (!$user->getUserAccess('content/autoadd')) {
            echo '<p>Ушбу мақола сайтнинг махсус гурух аъзолари текширувдан рухсат берилади.<br/>
                </p>';
        }
        echo '<p><a href="/">Давом эттириш</a></p>';
    }


    if ($core->action == 'del_img') {

        $image = $_REQUEST['image'] . '.jpg';
        $item_id = $_REQUEST['id'];
        $item = $db->dbGetFields('content', "id={$item_id}", 'id,images');
        $images = explode(',', $item['images']);
        foreach ($images as $img) {
            if ($img != $image && $img != '') {
                $img_list .= $img . ',';
            }
        }
        @unlink(PATH . "/images/content/" . $image);
        @unlink(PATH . "/images/content/small/" . $image);
        @unlink(PATH . "/images/content/medium/" . $image);
        $db->query("UPDATE content SET images='{$img_list}' WHERE id={$item['id']}");
        if (isset($_SERVER['HTTP_REFERER'])) {
            $back_url = strip_tags($_SERVER['HTTP_REFERER']);
        } else {
            $back_url = '/';
        }
        header('Location:' . $back_url);
    }


    if ($core->action == 'mread') {
        $article = array();
        $query = $db->query("SELECT c.*,u.nickname as author,ct.title as cat FROM content c left join users u on u.id=c.userid

                                                                            left join category ct on ct.id=c.category_id

                                                                             where c.id='{$id}' and c.lang='{$langID}'");

        if ($db->num_rows($query)) {
            $comp = $db->fetch_assoc($query);
            $title = $comp['title'];
            $image = (file_exists(PATH . '/images/photos/originals/article' . $comp['id'] . '.jpg') ? 'article' . $comp['id'] . '.jpg' : '');
            $desc = $comp['description'];
            $fultext = $comp['content'];
            $comments = intval($comp['comments']);
            $date = $core->get_Date(strtotime($comp['pubdate']));
            $hits = $comp['hits'] + 1;
            $author = $comp['author'];
            $source = $comp['source'];
            $hit = intval($hits);
            $cat = $comp['cat'];
            $article['images'] = explode(',', $comp['images']);
            $article['images'] = array_filter($article['images']);
            $db->query("update content set hits='{$hit}' where id='{$id}'");
            if ($comp['meta_keywords']) {
                $GLOBALS['page_keys'] = $comp['meta_keywords'];
            } else {
                $GLOBALS['page_keys'] = $config['keywords'];
            }
            if ($comp['meta_desc']) {
                $GLOBALS['page_desc'] = $comp['meta_desc'];
            } else {
                $GLOBALS['page_desc'] = $config['metadesc'];
            }
            if ($cfg['pt_morecontent']) {
                $a_id = $db->dbGetField('content', "id = '{$comp['id']}'", 'id');
                $query = $db->query("select * from tags where item_id= " . $a_id);
                while ($rows = $db->fetch_assoc($query)) {
                    $tags[] = $rows;
                }

                foreach ($tags as $tag) {

                    $sql = "SELECT item_id FROM tags WHERE tag_name = '$tag[tag_name]' AND item_id<>'$a_id'  LIMIT 5";


                    $res = $db->query($sql);

                    if ($db->num_rows($res)) {

                        while ($tag_id = $db->fetch_assoc($res)) {

                            $targets[] = $tag_id['item_id'];

                        }

                    }

                }

                if ($targets) {

                    $targets = array_unique($targets);

                    $targets = array_slice($targets, 0, 10);


                    foreach ($targets as $n) {


                        $more = $db->dbGetFields('content', "id = $n AND published = 1", 'id,title,description');

                        if ($more) {

                            $more['image'] = (file_exists(PATH . '/images/photos/small/article' . $more['id'] . '.jpg') ? '/images/photos/small/article' . $more['id'] . '.jpg' : '');

                            $morecontent[] = $more;

                        }

                    }

                }


            }

            $ker->filter_replace($fultext);
            $smarty = $core->smartyInitComponent();
            $smarty->assign('title', $title);
            $smarty->assign('desc', $desc);
            $smarty->assign('cat', $cat);
            $smarty->assign('fultext', $fultext);
            $smarty->assign('image', $image);
            $smarty->assign('comments', $comments);
            $smarty->assign('date', $date);
            $smarty->assign('hits', $hits);
            $smarty->assign('author', $author);
            $smarty->assign('source', $source);
            $smarty->assign('article', $article);
            $smarty->assign('morecontent', $morecontent);
            $smarty->assign('tags', tagLine($id, false));
            $smarty->display('com_mcontent_read.tpl');

//            include(PATH . '/component/comments/comments.php');

//            comments($id);

        } else {

            echo 'Нет материалов';

        }


    }

    if ($core->action == 'my') {
        $user_id = $users->uid;
        $pager=Registry::get("Paginator");
        $counter=$db->numrows($db->query("select con.*,cat.cname as category
                                        from content con left join categories cat on cat.id=con.category_id
                                                         left join languages lng on lng.flag=con.lang
                                         where con.user_id='{$user_id}' and con.lang='{$langID}'
                                          order by con.created_at DESC"));
        $pager->items_total=$counter;
        $pager->default_ipp = 15;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }

        $sql = "select con.*,cat.cname as category
                                        from content con left join categories cat on cat.id=con.category_id
                                                         left join languages lng on lng.flag=con.lang
                                         where con.user_id='{$user_id}' and con.lang='{$langID}'
                                          order by con.created_at DESC ".$pager->limit;

        $query = $db->query($sql);
        if (!$db->numrows($query)){
            echo '<p align="center">Сайтда сизни мақолангиз мавжуд эмас.<a href="/content/addArticle.html"> Мақола қўшиш</a></p>';
            return;
        }
        $articles = array();
        $found_article = false;
        if ($db->numrows($query)) {
            $found_article = true;
            while ($con = $db->fetch($query)) {
                $row = sizeof($articles);
                $articles[$row][] = $con;
                if ($row % 2) {
                    $articles[$row]['class'] = "search_row1";
                } else {
                    $articles[$row]['class'] = "search_row2";
                }
                $articles[$row]['id'] = $con->id;
                $articles[$row]['title'] = $con->title;
                $articles[$row]['published']=$con->active;
                $articles[$row]['seo']=$con->seo;
                $articles[$row]['pubdate'] = $core->cmsRusDate($core->dodate($config->long_date, $con->created_at));;
                $articles[$row]['category'] = $con->category;
//                $articles[$row]['comments'] = $con['comments'];
                $articles[$row]['status'] = $articles[$row]['published'] ? '<span style="color: green">Актив</span>' : '<span style="color: #cc0000">Актив эмас</span>';
            }

        }


        include(PATH . '/theme/' . Registry::get("Config")->template . '/com_content_my.php');
//        $smarty = $ker->smartyInitComponent();
//        $smarty->assign("total", $total);
//        $smarty->assign("articles", $articles);
//        $smarty->assign("found_article", $found_article);
//        $smarty->assign("pageBar", $ker->getPagebar($total, $page, $perpage, '/content/my%page%.html'));
//        $smarty->display("com_content_my.tpl");


    }


}

function getseobyid($seo)
{

    global $db;
    if (is_numeric($seo)) {
        $sqlwghere = "id='{$seo}'";
    } else {

        $sqlwghere = "alias='{$seo}'";
    }
    $query = $db->query("select * from menus where {$sqlwghere}");

    if ($db->numrows($query) != 0) {
        $rows = $db->first($query,true);
    }
    return ($rows['linkid']) ? $rows['linkid'] : 0;

}

?>