<?php
function loadConfig(){
    $db = DB::getInstance();
    //LOAD CURRENT CONFIG
    $sql = "SELECT config FROM components WHERE link = 'photos'";
    $result = $db->query($sql) or die($db->error());

    if ($db->num_rows($result)){
        $conf = $db->fetch_assoc($result);
        if ($conf){
            $cfg = unserialize($conf['config']);
            //		print_r($cfg);
        }
    }

    return $cfg;

}
function photos()
{
    $db = Registry::get("DataBase");
    $core = Registry::get("Core");
    $user=Registry::get("Users");

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
    $cfg = $core->getComponentConfig('photos');

    if ($core->action == 'view') {
        echo '<h1>Фотогалерея</h1>';
        $albums = array();
        $photos = array();

        $sql = "SELECT f.*, IF(DATE_FORMAT(f.pubdate, '%d-%m-%Y')=DATE_FORMAT(NOW(), '%d-%m-%Y'), DATE_FORMAT(f.pubdate, '<strong>Бугун</strong>, %H:%i'), DATE_FORMAT(f.pubdate, '%d-%m-%Y'))  as fpubdate, a.id as photoalbumid, a.title as album
			FROM photofiles f, photoalbums a
			WHERE f.published = 1 AND f.photoalbumid = a.id
			ORDER BY id asc
			LIMIT 24";

        $query = $db->query($sql);
        if ($db->numrows($query)){
            while ($row=$db->fetch($query,true)){
                    $photos[]=$row;
            }
        }

        $sqlAlbumsresult= $db->query("select * from  photoalbums ct where ct.parent_id>0 and ct.published=1");
        $ii=0;
        $is_items=false;
        if ($db->numrows($sqlAlbumsresult)){
            $is_items=true;
            $acategory = $db->fetch_all($sqlAlbumsresult,true);
            foreach ($acategory as $category) {
                $albums[]= $category;
            }
        }
        if ($cfg['view_type'] == 'thumb') {
            require_once(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_photos_thumb.php');
//            $smarty->display("com_photos_thumb.tpl");
        }
        if ($cfg['view_type'] == 'list'){
            require_once(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_photos_list.php');
        }
//            $smarty->assign("cfg",$cfg);
//
//            $smarty->display("com_photos_list.tpl");
    }

    if ($core->action=='viewphoto'){
        $sql = "SELECT f.*, DATE_FORMAT(f.pubdate, '%d-%m-%Y') pubdate,
					a.id cat_id, a.title cat_title, a.public public, a.showtype a_type, a.showtags a_tags
			FROM photofiles f, photoalbums a
			WHERE f.id = '{$core->id}' AND f.photoalbumid = a.id";
        echo $sql;
        $result=$db->query($sql);
        if ($db->numrows($result)>0){
            $photo=$db->fetch($result,true);
            $GLOBALS['page_title'] = $photo['cat_title'];
            $hits=$photo['hits']+1;
            $db->query("update photofiles set hits='{$hits}' where id='{$core->id}'");
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/images/photos/'.$photo['files'])){
                $file=$photo['files'];
            }

            $usr =$db->getValuesById('id, nickname','users', $photo['user_id']);

//            if ($photos['a_type'] != 'simple'){
//                echo '<div class="photo_bar">';
//                echo '<table width="" cellspacing="0" cellpadding="4" align="center"><tr>';
//                echo '<td width=""><strong>Добавлена:</strong> '.$photos['pubdate'].'</td>';
//                if ($photos['public']){
//                    $usr =$db->dbGetFields('users', 'id='.$photos['user_id'], 'id, nickname');
//                    if ($usr['id']){
//                        echo '<td width="16"><img src="/components/photos/images/user.gif" border"0"/></td>';
//                        echo '<td><a href="/users/0/'.$usr['id'].'/profile.html">'.$usr['nickname'].'</a></td>';
//                    }
//                }
//
////                $karma = cmsKarma('photos', $photos['id']);
//
//                echo '<td width=""><strong>Просмотров: </strong> '.$photos['hits'].'</td>';
////                echo '<td width=""><strong>Рейтинг: </strong><span id="karmapoints">'.cmsKarmaFormatSmall($karma['points']).'</span></td>';
//
////                echo '<td width="">'.cmsKarmaButtons('photos', $photos['id']).'</td>';
//
//                if($cfg['link']){
//                    $file = $_SERVER['DOCUMENT_ROOT'].'/images/photos/'.$photos['file'];
//                    if (file_exists($file)){
//                        echo '<td><a href="/images/photos/'.$photos['file'].'" target="_blank">Открыть оригинал</a></td>';
//                    }
//                }
//                if ($photos['public']){
//                    $usr = $db->dbGetFields('users', 'id='.$photos['user_id'], 'id, nickname');
//                    if ($usr['id']){
//                        echo '<td width="16"><img src="/component/photos/images/user.gif" border"0"/></td>';
//                        echo '<td><a href="/users/0/'.$usr['id'].'/profile.html">'.$usr['nickname'].'</a></td>';
//                    }
//                }
//
//                if($photos['public'] && isset($_SESSION['user']['id'])){
//                    if ($usr['id']==$_SESSION['user']['id'] || $user->getUserIsAdmin($_SESSION['user']['id'])){
//                        echo '<td><a href="/photos/editphoto'.$photos['id'].'.html" title="Редактировать"><img src="/images/icons/edit.gif" border="0"/></a></td>';
//                        if ($user->getUserIsAdmin($_SESSION['user']['id'])){
//                            echo '<td><a href="/photos/movephoto'.$photos['id'].'.html" title="Переместить"><img src="/images/icons/move.gif" border="0"/></a></td>';
//                        }
//                        echo '<td><a href="/photos/delphoto'.$photos['id'].'.html" title="Удалить"><img src="/images/icons/delete.gif" border="0"/></a></td>';
//                    }
//                }
//                echo '</tr></table>';
//                echo '</div>';
//
                if($photo['a_tags']){
                    if(!function_exists('tagLine')){
                        include($_SERVER['DOCUMENT_ROOT'].'/include/lib_tags.php');
                    }
//                    $tags=tagLine($photo['id']);
                }

        }

//        $smarty=$ker->smartyInitComponent();
//        $smarty->assign("photos",$photo);
//        $smarty->assign("usr",$usr);
//        $smarty->assign("cfg",$cfg);
//        $smarty->assign("file",$file);
//        $smarty->assign("tags",$tags);
//        $smarty->display("com_photo_view.tpl");
    }
}

?>