<link rel="stylesheet" href="/components/fcatalog/css/voting.css" type="text/css" />
<link rel="stylesheet" href="/components/fcatalog/css/fcatalog.css" type="text/css" />
<script type="text/javascript" src="/components/fcatalog/js/voting.js"></script>
<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 14.02.2016
 * Time: 20:04
 */
include(PATH . '/class/class_fcatalog.php');
Registry::set("FCatalog", new FCatalog());

$db = Registry::get("DataBase");
$config = Registry::get("Config");
$users = Registry::get("Users");
$core = Registry::get("Core");
$fc=Registry::get("FCatalog");

if (!Core::$action) {
    $core->action = 'cats';
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

if ($core->action=='cats'){
    ?>
    <h3>Каталог файлов</h3>
<?php
    $cats = $fc->getCats(1);
    $count=  $db->rows_count('files',"published = 1");
    include(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_fcatalog_cats.php');
}

if ($core->action=='list_cat') {
    $seolink = $_REQUEST['seolink'];
    $cat_id = $db->getFieldById('id', 'files_cat', "seolink='{$seolink}'");
    $subcats_list = $fc->getCats($cat_id);

    $is_subcats = (bool)sizeof($subcats_list);


    $sql = "SELECT f.*, u.username FROM files f
                                  LEFT JOIN users u ON f.user_id = u.id
                                  WHERE f.cat_id='{$cat_id}' AND f.published = 1
                                  ORDER BY pubdate DESC, title ";
//        .(($page-1)*$perpage).", $perpage";

    $result = $db->query($sql);
    if ($db->numrows($result)) {
        while ($file = $db->fetch($result, true)) {
            $file['pubdate'] = $core->cmsRusDate($core->dodate($config->long_date, $file['pubdate']));
            $file['description'] = $file['description'];
            $file['votes'] = $fc->getVotingBlock($file['id']);
//            $file['comments']  = $inCore->getCommentsCount('file', $file[id]);
            $files[] = $file;
        }
    }else{
       $msg= '<p style="margin-top:20px;"><strong>В этой категории пока нет файлов...</strong><p>';
    }
    $total = $db->rows_count('files',"cat_id={$cat_id} and published = 1");
    $category =$fc->getFileCategory($cat_id);

    $reclama = $db->getFieldById('content','files_reclama','id=1');

    $reclama = explode('|', $reclama);

    require(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_fcatalog_list.php');
}

if ($core->action=='view'){
    $f_id = $_REQUEST['f_id'];
    $is_user=$users->userid;
    if ($f_id) {
        $db->query("UPDATE files SET hits = hits + 1 WHERE id='{$f_id}'");
        $file = $fc->getFileItem($f_id);
        $file=Registry::get("Core")->getCallEvent('GET_FCATALOG',$file);

        $file['pubdate'] =  $core->cmsRusDate($core->dodate($config->long_date, $file['pubdate']));
//        $file['description'] =$file['description'];
        $file['content'] =$file['description'].$file['fcatalog_file'];
        $file['votes'] = $fc->getVotingBlock($file['id']);
        $file['is_my'] = ($file['user_id']<>0 && $file['user_id'] == $users->userid || $users->is_Admin());
        $file['mb']	= $core->getSize($file['size']);

        $path_parts = pathinfo('/upload/fcatalog/'.$file['filename']);
        $ext        = strtolower($path_parts['extension']);

        $file['icon']= (file_exists(PATH.'/components/fcatalog/ftypes/'.$ext.'.png') ? '/components/fcatalog/ftypes/'.$ext.'.png' : '/components/fcatalog/ftypes/none.png');
        $file['muzic']=($ext == 'mp3');

        $tags = explode(',',$file['tags']);
        $file['tags']='';

        foreach($tags as $tag){
            $tag = '<a href="/fcatalog/search/'.urlencode(ltrim($tag)).'">'.$tag.'</a>, ';
            $file['tags'].=$tag;
        }



    }
    $category = $fc->getFileCategory($file['cat_id']);
    $count=  $db->rows_count('files',"published = 1");
    require(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_fcatalog_view.php');
}
if ($core->action=='search') {

    $query = $_REQUEST['query'];
    $query = urldecode($query);
    $query = trim($query);
    $query = str_replace('\'', '', $query);
    $query = str_replace('"', '', $query);
    $query = $db->escape($query);
    $query = htmlspecialchars($query);

    if ($query) {
        $_SESSION['fsearchquery'] = $query;
        $quer = $_SESSION['fsearchquery'];

        if ($quer) {
            if ($query && strlen($quer) < 3) {
                $total = 0;
                unset($_SESSION['fsearchquery']);
                $msg = '<p><strong>Ошибка:</strong> <span style="color:red">Строка для поиска не может быть меньше 3 букв!</span></p>';
            }else{

                $sql = "SELECT f.* FROM files f LEFT JOIN users u ON f.user_id = u.id WHERE f.published = 1 AND LOWER(f.title) LIKE '%".strtolower($quer)."%' OR LOWER(f.description) LIKE '%".strtolower($quer)."%' OR LOWER(f.tags) LIKE '%".strtolower($quer)."%' ORDER BY f.pubdate DESC, f.title  ";
                    //.(($page-1)*$perpage).", $perpage";

                $result = $db->query($sql);
                $find=$db->numrows($result);
                if ($find) {
                    while ($file = $db->fetch($result, true)) {
                        $file['pubdate'] =  $core->cmsRusDate($core->dodate($config->long_date, $file['pubdate']));
                        $file['mb'] = $core->getSize($file['size']);
                        $file['title'] = preg_replace('/(?i)' . $quer . '/', '<i style="background:#FFFF00">' . $quer . '</i>', $file['title']);
//                        $file['description'] = $inCore->parseSmiles($file[description], true);
                        $file['description'] = preg_replace('/(?i)' . $quer . '/', '<i style="background:#FFFF00">' . $quer . '</i>', $file['description']);
                        $file['category'] = $fc->getFileCategory($file['cat_id']);

                        $files[] = $file;
                    }
                    $total = $db->rows_count('files',"published = 1 AND LOWER(title) LIKE '%".strtolower($quer)."%' OR LOWER(description) LIKE '%".strtolower($quer)."%' OR LOWER(tags) LIKE '%".strtolower($quer)."%'");

                }else{
                    $total = 0;

                }
            }
        }
    }else {unset($_SESSION['fsearchquery']);}

    require(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_fcatalog_search.php');
}
if ($core->action=='download'){
    $user_id = $users->userid;
    $f_id = $_REQUEST['f_id'];
    $secret = $_REQUEST['secret'];
    $file = $fc->getFileItem($f_id);
    $file['downloads']=$file['downloads']+1;
    $data=array("downloads"=>$file['downloads'],
                "last"=>'NOW()');
    $db->update('files',$data, 'id='.$f_id);
     if ($file['filename']){ $fc->file_download(PATH."/uploads/fcatalog/".$file['filename']);
       } else  {
        echo "<h3>Переход по внешней ссылке</h3>
              <p>Внимание! Вы собираетесь перейти по внешней ссылке.
  Мы не несём ответственности за содержимое этой ссылки.  <br>
  Если Вы еще не передумали, нажмите на <a target='_blank' href='".$file->fileurl."'>".$file->fileurl."</a>
  <br>Если Вы не хотите рисковать безопасностью Вашего компьютера, нажмите <a href='#' onclick='history.back()'> отмена </a>
  ";
    }

}
?>