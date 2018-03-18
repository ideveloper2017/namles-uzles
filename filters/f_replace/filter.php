<link rel="stylesheet" href="/components/fcatalog/css/voting.css" type="text/css"/>
<link rel="stylesheet" href="/components/fcatalog/css/fcatalog.css" type="text/css"/>
<!--<script type="text/javascript" src="/components/fcatalog/js/voting.js"></script>-->

<?php
//function insertForm($form_title){
//    cmsCore::loadClass('form');
//    return cmsForm::displayForm(trim($form_title), array(), false);
//}

//function PhotoLink($photo_title)
//{
//    $photo_title = cmsCore::strClear($photo_title);
//    $photo = Registry::get("DataBase")->getFieldsById('id, title', 'photo_files', "title LIKE '{$photo_title}'");
//    if ($photo) {
//        $link = '<a href="/photos/photos' . $photo->id . '.html" title="' . htmlspecialchars($photo->title) . '">' . $photo->title . '</a>';
//    } else {
//        $link = '';
//    }
//    return $link;
//}

//function AlbumLink($album_title)
//{
//
//    $album = Registry::get("DataBase")->getFieldsById('id, title', 'photo_albums', "title LIKE '%{$album_title}%'");
//    if ($album) {
//        $link = '<a href="/photos/' . $album->id . '" title="' . htmlspecialchars($album->title) . '">' . $album->title . '</a>';
//    } else {
//        $link = '';
//    }
//    return $link;
//}

function ContentDetail($content_title)
{
    $link = '';
    $langID=Lang::getLangID();
    $query = Registry::get("DataBase")->query("SELECT c.*,u.username as author,ct.cname as cattitle,ct.slug as cseo FROM content c left join users u on u.id=c.user_id
                                                                            left join categories_bind cb on cb.item_id=c.id
                                                                            left join categories ct on ct.id=cb.category_id
                                                                            where (c.title='{$content_title}')  and c.lang='{$langID}' and c.active=1 order by created_at desc ");
//
//    echo $content_title;

//    $content = Registry::get("DataBase")->getFieldsById('seo,title,introtext,`fulltext`,images', 'content', "title LIKE '%{$content_title}%'");
    $content = Registry::get("DataBase")->first($query);
//    $menu_id=Registry::get("DataBase")->getFieldById('id','menus',"alias='{$content->cseo}'");
    $menu_id=Registry::get("DataBase")->getFieldById('id','menus',"link='{$content->cseo}'");

    if ($content) {
        $url=!empty($content->seo)?Content::getArticleURL($menu_id, $content->seo):Content::getArticleURL($menu_id, $content->url);
        $images=file_exists(PATH.'/images/content/'.$content->images)?'/images/content/'.$content->images:'';
//        $link.='<div class="col-md-5">';
//        $link.='<img src="'.$images.'" alt="" width="460" height="480"> </div>';
//        $link.='<div class="col-md-7">';
//        $link.='<div class="cp-welcome-content">';
        $link.=''.$content->introtext.'';
        $link.='<a href="' . $url. '" class="button white">Батафсил</a>';
//        $link.='';
    } else {
        $link = '';
    }

    return $link;
}

function ContentLink($content_title)
{

    $content = Registry::get("DataBase")->getFieldsById('seo,title', 'content', "title LIKE '%{$content_title}%'");
    if ($content) {
        $link = '<a href="/content/' . $content->seo . '.html" style="color:#fff" title="' . htmlspecialchars($content->title) . '">' . $content->title . '</a>';
    } else {
        $link = '';
    }

    return $link;
}

function InsertMediaPlayer($file)
{
    $db = Registry::get("DataBase");
    $row = $db->fetch_all("select * from audio where title='{$file}'");
    $link = "<script type='text/javascript' src='/filters/f_mp3/swfobject.js'></script>";
    foreach ($row as $mp3) {
        if ($mp3->file) {
            $filefull = '/uploads/media/mp3/' . $mp3->file;
            $link .= "<div id='mediaspace{$mp3->id}'>This text will be replaced</div>";
            $link .= "<script type='text/javascript'>";
            $link .= " var s{$mp3->id} = new SWFObject('/filters/f_mp3/player.swf','ply','470','24','9','#ffffff');";
            $link .= "s{$mp3->id}.addParam('allowfullscreen','true');";
            $link .= "s{$mp3->id}.addParam('allowscriptaccess','always');";
            $link .= "s{$mp3->id}.addParam('wmode','opaque');";
            $link .= "s{$mp3->id}.addVariable('file','$filefull');";
            $link .= "s{$mp3->id}.addVariable('duration','auto');";
            $link .= "s{$mp3->id}.write('mediaspace{$mp3->id}');";
            $link .= "</script>";
            $link .= '<p align="right"><a href="' . $filefull . '">Скачать MP3</a></p>';
        } else {
            $link = 'Файл "' . $filefull . '" не найден!';
        }
    }
    return $link;
}

function getlinkTitle($title)
{
    include(PATH . '/class/class_fcatalog.php');
    Registry::set("FCatalog", new FCatalog());

    return Registry::get("FCatalog")->getlinkByTitle($title);
}

function replace_text($text, $phrase)
{
    $regex = '/{(' . $phrase['title'] . '=)\s*(.*?)}/ui';
    $matches = array();
    preg_match_all($regex, $text, $matches, PREG_SET_ORDER);
    foreach ($matches as $elm) {

        $elm[0] = str_replace(array('{', '}'), '', $elm[0]);

        mb_parse_str($elm[0], $args);
        $arg = @$args[$phrase['title']];

        if ($arg) {
            $output = call_user_func($phrase['function'], $arg);

        } else {
            $output = '';
        }
        $text = str_replace('{' . $phrase['title'] . '=' . $arg . '}', $output, $text);
    }
    return $text;
}

////////////////////////////////////////////////////////////////////////////////
function f_replace(&$text)
{
    $phrases = array('photos' => array('title' => 'ФОТО', 'function' => 'PhotoLink'),
        'album' => array('title' => 'АЛЬБОМ', 'function' => 'AlbumLink'),
        'mp3' => array('title' => 'MP3', 'function' => 'InsertMediaPlayer'),
        'fcatalog' => array('title' => 'КАТАЛОГФАЙЛ', 'function' => 'getlinkTitle'),
        'content' => array('title' => 'МАТЕРИАЛ', 'function' => 'ContentLink'),
        'contentdatail' => array('title' => 'МАТЕРИАЛИНФО', 'function' => 'ContentDetail'),

    );
    foreach ($phrases as $phrase) {
        if (mb_strpos($text, $phrase['title']) !== false) {
            $text = replace_text($text, $phrase);

        }

    }

    return true;
}