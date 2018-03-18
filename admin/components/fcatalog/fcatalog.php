<link rel="stylesheet" href="/components/fcatalog/css/fcatalog.css" type="text/css"
      xmlns:javascript="http://www.w3.org/1999/xhtml"/>
<script type="text/javascript" src="/components/fcatalog/js/tabs.js"></script>
<style>
    .my_tabPane {
        height: 26px; /* Height of tabs */
    }

    .my_aTab {
        border: 1px solid #CDCDCD;
        padding: 5px;

    }

    .my_tabPane DIV {
        float: left;
        padding-left: 3px;
        vertical-align: middle;
        background-repeat: no-repeat;
        background-position: bottom left;
        cursor: pointer;
        position: relative;
        bottom: -1px;
        margin-left: 0px;
        margin-right: 0px;
    }

    .my_tabPane .tabActive {
        background-image: url('/components/fcatalog/img/tl_active.gif');
        margin-left: 0px;
        margin-right: 0px;
    }

    .my_tabPane .tabInactive {
        background-image: url('/components/fcatalog/img/tl_inactive.gif');
        margin-left: 0px;
        margin-right: 0px;
    }

    .my_tabPane .inactiveTabOver {
        margin-left: 0px;
        margin-right: 0px;
    }

    .my_tabPane span {
        font-family: tahoma;
        vertical-align: top;
        font-size: 11px;
        line-height: 26px;
        float: left;
    }

    .my_tabPane .tabActive span {
        padding-bottom: 0px;
        line-height: 26px;
    }

    .my_tabPane img {
        float: left;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 11.02.2016
 * Time: 23:01
 */

if (isset($_REQUEST['opt'])) {
    $opt = $_REQUEST['opt'];
} else {
    $opt = 'list_cat';
}
include(PATH . '/class/class_fcatalog.php');
Registry::set("FCatalog", new FCatalog());

$db = Registry::get("DataBase");
$fc = Registry::get("FCatalog");
$pager = Registry::get("Paginator");
$content = Registry::get("Content");
if ($opt == 'list') {
    ?>
    <form id="selform" name="selform" method="post">
    <div class="page-header">

        <div class="visible-xs header-element-toggle">
            <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-info-buttons"><i
                    class="icon-info"></i></a>
        </div>

        <div class="header-info-buttons">
            <div class="collapse" id="header-info-buttons">
                <div class="well">
                    <ul class="info-buttons">
                        <li><a href="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=add"><i
                                    class="icon-folder-plus2"></i> <span>Добавить категорию</span> </a></li>
                        <li>
                            <a href="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=list_cat"><i
                                    class="icon-folder8"></i> <span>Категории</span> </a></li>
                        <li>
                            <a href="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=list"><i
                                    class="icon-file4"></i> <span>Файлы</span> </a></li>
                        <li>
                            <a href="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=additem"><i
                                    class="icon-file-plus"></i> <span>Добавить файл</span> </a></li>
                        <li>
                            <a href="javascript:checkSel('index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=deleteitem')"><i
                                    class="icon-file-remove"></i> <span>Удалить</span> </a></li>
                        <li>
                            <a  href="javascript:checkSel('index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=published')"><i
                                    class="icon-eye7"></i> <span>Показать/Скрыть</span> </a></li>
                        <li><a href="#"><i class="icon-support"></i> <span>Настройки</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title">Категории файлов </h6></div>
        <div class="panel-body">
            <div class="table-responsive">
                <div class="datatable-header">
                    <div class="dataTables_filter"><label><span>Фильтр:</span> <input name="filter"
                                                                                      type="search" class=""
                                                                                      aria-controls="DataTables_Table_0"
                                                                                      placeholder="Фильтр..."></label>
                        <input type="submit" class="btn btn-primary" value="Фильтр">
                    </div>
                    <div class="dataTables_length"><label><span>Просмотр:</span>


                            <?php echo $pager->items_per_page(); ?></label></div>
                </div>
                <table class="table table-bordered table-check">
                    <thead>
                    <tr>
                        <th><a href="javascript:" onclick="javascript:invert()">#</a></th>

                        <th>ID</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Дата загрузки</th>
                        <th>Пользователь</th>
                        <th>Публиковать</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($fc->getFileItems() as $filteitems) {
                        ?>
                        <tr>
                            <td><input type="checkbox" name="item[]" id="item[]" value="<?php echo $filteitems->id ?>"
                                       class="styled"></td>
                            <td>
                                <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_item&f_id=<?php echo $filteitems->id ?>"><?php echo $filteitems->id; ?></a>
                            </td>
                            <td>
                                <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_item&f_id=<?php echo $filteitems->id ?>"><?php echo $filteitems->title; ?> </a>
                            </td>
                            <td><?php echo $filteitems->category ?> </td>
                            <td><?php echo $filteitems->pubdate ?> </td>
                            <td><?php echo $filteitems->username ?> </td>
                            <td align="center">
                                <div class="state iradio_line-blue checked">
                                    <div
                                        class="icheck_line-icon"><?php echo $filteitems->published ? "<i class='icon-checkmark-circle2'></i>" : "<i class='icon-cancel-circle2'></i>"; ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="table-controls">
                                    <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=deleteitem&f_id=<?php echo $filteitems->id ?>"
                                       class="btn btn-default btn-icon btn-xs tip" title="Удалить"><i
                                            class="icon-remove"></i></a>
                                    <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_item&f_id=<?php echo $filteitems->id ?>"
                                       class="btn btn-default btn-icon btn-xs tip" title="Радактировать"><i
                                            class="icon-pencil"></i></a>

                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
        </form>
    <?php
}

if ($opt == 'list_cat') {
    ?>
<form name="selform" id="selform" method="post">
    <div class="page-header">
        <div class="visible-xs header-element-toggle">
            <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-info-buttons"><i
                    class="icon-info"></i></a>
        </div>

        <div class="header-info-buttons">
            <div class="collapse" id="header-info-buttons">
                <div class="well">
                    <ul class="info-buttons">
                        <li><a href="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=add"><i
                                    class="icon-folder"></i> <span>Добавить категорию</span> </a></li>
                        <li>
                            <a href="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=list"><i
                                    class="icon-list"></i> <span>Все файлы</span> </a></li>
                        <li>
                            <a href="javascript:checkSel('index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=delcat')"><i
                                    class="icon-file-remove"></i> <span>Удалить</span> </a></li>
                        <li>
                            <a href="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=config"><i
                                    class="icon-support"></i> <span>Настройки</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title">Категории файлов </h6></div>
        <div class="panel-body">
            <div class="table-responsive">
                <div class="datatable-header">
                    <div class="dataTables_filter"><label><span>Фильтр:</span> <input name="filter"
                                                                                      type="search" class=""
                                                                                      aria-controls="DataTables_Table_0"
                                                                                      placeholder="Фильтр..."></label>
                        <input type="submit" class="btn btn-primary" value="Фильтр">
                    </div>
                    <div class="dataTables_length"><label><span>Просмотр:</span>


                            <?php echo $pager->items_per_page(); ?></label></div>
                </div>
                <table class="table table-bordered table-check">
                    <thead>
                    <tr>
                        <th><a href="javascript:" onclick="javascript:invert()">#</a></th>

                        <th>ID</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $fc->getFCatalogTreeList(1, 0, "|-&nbsp;", 0); ?>
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
</form>
    <?php

}

if ($opt == 'submit') {
    $title = Core::$post['title'];
    $parent_id = Core::$post['parent_id'];
    $description = Core::$post['description'];
    $seolink = $fc->getCatSeoLink(array('id' => '', 'title' => $title));

    if ($_FILES['icon']['size']) {
        $uploaddir = PATH . '/images/fcatalog/';

        if (!is_dir($uploaddir)) {
            @mkdir($uploaddir);
            file_put_contents($uploaddir . '/index.html', '');
        }

        $realfile = $_FILES['icon']['name'];
        $path_parts = pathinfo($realfile);
        $ext = strtolower($path_parts['extension']);

        if ($ext != 'jpg' && $ext != 'jpeg' && $ext != 'gif' && $ext != 'png' && $ext != 'bmp') {
            die('тип файла неверный');
        }

        $realfile = substr($realfile, 0, strrpos($realfile, '.'));
        $realfile = preg_replace('/[^a-zA-Z0-9]/i', '', $realfile);

        $filename = md5($realfile . time()) . '.' . $ext;
        $uploadfile = $uploaddir . $filename;

        if (@move_uploaded_file($_FILES['icon']['tmp_name'], $uploadfile)) {
            $icon = $filename;
        } else {
            $msg .= 'Ошибка загрузки изображения или файл не загружен!<br>';
        }
    }

    if (!Core::$post['is_public']) {
        $showfor = $_REQUEST['showfor'];
        if (sizeof($showfor) > 0 && !Core::$post['is_public']) {
            $fc->setFileAccess($id, $showfor);
        }
    }

    $data = array("title" => $title,
        "parent_id" => $parent_id,
        "description" => $description,
        "icon" => $icon,
        "seolink" => $seolink);

    $fc->proccessFCatalog($data);
    header("Location:index.php?do=components&action=config&id=" . Core::$id);
}


if ($opt == 'update') {
    if (isset($_REQUEST['item_id'])) {
        $id = Core::$post['item_id'];
        $parent_id = Core::$post['parent_id'];
        $title = Core::$post['title'];
        $description = Core::$post['description'];
        $seolink = $fc->getCatSeoLink(array('id' => $id, 'title' => $title));
        if ($_FILES['icon']['size']) {
            $uploaddir = PATH . '/images/fcatalog/';

            if (!is_dir($uploaddir)) {
                @mkdir($uploaddir);
                file_put_contents($uploaddir . '/index.html', '');
            }

            $realfile = $_FILES['icon']['name'];
            $path_parts = pathinfo($realfile);
            $ext = strtolower($path_parts['extension']);

            if ($ext != 'jpg' && $ext != 'jpeg' && $ext != 'gif' && $ext != 'png' && $ext != 'bmp') {
                die('тип файла неверный');
            }

            $realfile = substr($realfile, 0, strrpos($realfile, '.'));
            $realfile = preg_replace('/[^a-zA-Z0-9]/i', '', $realfile);

            $filename = md5($realfile . time()) . '.' . $ext;
            $uploadfile = $uploaddir . $filename;

            if (@move_uploaded_file($_FILES['icon']['tmp_name'], $uploadfile)) {
                $icon = $filename;
            } else {
                $msg .= 'Ошибка загрузки изображения или файл не загружен!<br>';
            }
        }
        if ($icon) {
            $addicon = ", icon = '$icon'";
        }
        if ($_REQUEST['del_icon']) {
            $to_del = $db->getFieldById('icon', 'files_cat', "id=$id");
            @unlink(PATH . "/images/fcatalog/$to_del");
            $addicon = ", icon = '$icon'";
        }


        if (!$_REQUEST['is_public']) {
            $showfor = $_REQUEST['showfor'];
            $fc->setFileAccess($id, $showfor);
        } else {
            $fc->clearFileAccess($id);
        }
        $data = array("title" => $title,
            "parent_id" => $parent_id,
            "description" => $description,
            "icon" => $icon,
            "seolink" => $seolink);

        $fc->proccessFCatalog($data);
        header('location:?do=components&action=config&id=' . $_REQUEST['id'] . '&opt=list_cat');
    }
}
if ($opt=='deleteitem'){
    $item=Core::$post['item'];
    if (is_array($item)){
        foreach($item as $key=>$row){
            $db->delete("files",'id='.$item[$key]);
        }
    }else{
        $f_id=Core::$get['f_id'];
        $db->delete("files",'id='.$f_id);
    }
    header("Location:index.php?do=components&action=config&id=".Core::$id."&opt=list");

}

if ($opt=='delcat'){
    $item=Core::$post['item'];
    if (is_array($item)){
        foreach($item as $key=>$row){
            $db->delete("files_cat",'id='.$item[$key]);
        }
    }else{
        $item_id=Core::$get['item_id'];
        $db->delete("files_cat",'id='.$item_id);
    }
    header("Location:index.php?do=components&action=config&id=".Core::$id."&opt=list_cat");

}



if ($opt=='published'){
    $item=Core::$post['item'];
    if (is_array($item)){
        foreach($item as $key=>$row){

             $pub=$db->getValueById('published','files',$item[$key]);
            echo $pub;
            if ($pub==0){
                $data=array('published'=>'1');
                $db->update("files",$data,'id='.$item[$key]);
            }else{
                $data=array('published'=>'0');
                $db->update("files",$data,'id='.$item[$key]);
            }

        }
    }else{
        $f_id=Core::$get['f_id'];
        $pub=$db->getValueById('published','files',$f_id);
        if ($pub==0){
            $data=array('published'=>'1');
            $db->update("files",$data,'id='.$f_id);
        }else{
            $data=array('published'=>'0');
            $db->update("files",$data,'id='.$f_id);
        }

    }
    header("Location:index.php?do=components&action=config&id=".Core::$id."&opt=list");
}

if ($opt == 'submititem') {

    $title = $_REQUEST['title'];
    $description = $_REQUEST['description'];
    if (!empty($_REQUEST['tags'])) {
        $tags = $_REQUEST['tags'];
    } else {
        $tags = $content->generateTag($title);
    }

    $user_id = Registry::get("Users")->userid;
    $cat_id = $_REQUEST['cat_id'];
    $fileurl = $_REQUEST['fileurl'];
    if ($_FILES['filename']['size']) {
        $size_mb = 0;
        $upload_dir = PATH . '/uploads/fcatalog/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir);
            file_put_contents($upload_dir . '/index.html', '');
        }
        $tmp_name = $_FILES['filename']["tmp_name"];
        $name = $_FILES['filename']["name"];
        $size = $_FILES['filename']["size"];
        $size_mb += round(($size / 1024) / 1024, 2);

        $types = $cfg['filestype'] ? $cfg['filestype'] : 'jpeg,gif,png,jpg,bmp,zip,rar,tar,xls,doc,mp3,avi,mp4,avi,mkv';
        $maytypes = explode(',', str_replace(' ', '', $types));
        $path_parts = pathinfo($name);
        $ext = strtolower($path_parts['extension']);
        $may = in_array($ext, $maytypes);
//        if(!$may) { cmsCore::addSessionMessage('Недопустимый тип файла! Разрешенные типы: '.$types, 'error'); $inCore->redirectBack(); }

        $name = mb_substr($name, 0, strrpos($name, '.'));
        $name = Core::doSEO($name);
        $name .= '.' . $ext;
//        $name = cleanOut($name);
//        if ($size_mb > 5) {
//            cmsCore::addSessionMessage('Выбранный вами файл превышает максимальный размер'.' ('.$cfg['maxsize'].' mb) '.$_LANG['IS_OVER_LIMIT'].'<br>'.$_LANG['FOR_NEW_FILE_DEL_OLD'], 'error');  $inCore->redirectBack();  }

        if (@move_uploaded_file($tmp_name, PATH . "/uploads/fcatalog/" . $name)) {
            $newname = $name;
        }
//        }
    }
    $data = array('cat_id' => $cat_id,
        'user_id' => $user_id,
        'title' => $title,
        'description' => $description,
        'tags' => $tags,
        'filename' => $name,
        'fileurl' => $fileurl,
        'pubdate' => 'NOW()',
        'size' => $size,
        'published' => 1,
        'last' => 'NOW()');

    $fc->proccessItemFile($data);
    $itemid = $db->insertid();
    $content->insertTags('files', $content->generateTag($tags), $itemid);


//        $sql = "INSERT files(cat_id,user_id, title, description, tags, filename, fileurl, pubdate, size, published,last)
//            VALUES ('$cat_id', '$user_id', '$title','$description','$tags','$name','$fileurl',NOW(),'$size', '$cfg[premod]',NOW())";
//        $inDB->query($sql) or die("Ошибка при создании записи!");
//        $f_id = $inDB->get_field('cms_files', "filename='$name'", 'id');
//        if (!$cfg[premod]) {
//            $inCore->redirect("/fcatalog/moderation.html");
//        } else {
//            $a_id = $inDB->get_field('cms_actions', "name='add_file'", 'id');
//            $a_sql = "INSERT INTO cms_actions_log VALUES ('','{$a_id}',NOW(),'{$user_id}','файл','/fcatalog/view_file-{$f_id}.html','{$f_id}','файл','/fcatalog/view_file-{$f_id}.html','0','{$title}','0','0')";
//            $a_result = $inDB->query($a_sql);
//            $inCore->redirect("/fcatalog/view_file-" . $f_id . ".html");
//        }
//        }
    header("Location:index.php?do=components&action=config&id=" . Core::$id."&opt=list");

}

if ($opt=='updateitem'){
    $title = $_REQUEST['title'];
    $description = $_REQUEST['description'];
    if (!empty($_REQUEST['tags'])) {
        $tags = $_REQUEST['tags'];
    } else {
        $tags = $content->generateTag($title);
    }

    $user_id = Registry::get("Users")->userid;
    $cat_id = $_REQUEST['cat_id'];
    $fileurl = $_REQUEST['fileurl'];
    if (!empty($_FILES['filename']["name"]) && $_FILES['filename']['size']) {
        $size_mb = 0;
        $upload_dir = PATH . '/uploads/fcatalog/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir);
            file_put_contents($upload_dir . '/index.html', '');
        }
        $tmp_name = $_FILES['filename']["tmp_name"];
        $name = $_FILES['filename']["name"];
        $size = $_FILES['filename']["size"];
        $size_mb += round(($size / 1024) / 1024, 2);

        $types = $cfg['filestype'] ? $cfg['filestype'] : 'jpeg,gif,png,jpg,bmp,zip,rar,tar,xls,doc';
        $maytypes = explode(',', str_replace(' ', '', $types));
        $path_parts = pathinfo($name);
        $ext = strtolower($path_parts['extension']);
        $may = in_array($ext, $maytypes);

        $name = mb_substr($name, 0, strrpos($name, '.'));
        $name = Core::doSEO($name);
        $name .= '.' . $ext;
        if (@move_uploaded_file($tmp_name, PATH . "/uploads/fcatalog/" . $name)) {
            $newname = $name;
        }
    }

    $data = array('cat_id' => $cat_id,
        'user_id' => $user_id,
        'title' => $title,
        'description' => $description,
        'tags' => $tags,
        'filename' => $name,
        'fileurl' => $fileurl,
        'pubdate' => 'NOW()',
        'size' => $size,
        'published' => 1,
        'last' => 'NOW()');

    $fc->proccessItemFile($data);
    $itemid = $db->insertid();
    $content->insertTags('files', $content->generateTag($tags), $itemid);

}

if ($opt == 'additem' || $opt == 'edit_item') {

    if ($opt == 'edit_item') {
        $f_id = (int)$_REQUEST['f_id'];
        $filerow = $fc->getFileItem($f_id);
    }

    ?>

    <form action="" class="form-horizontal" method="POST" id="addform" name="addform" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-1 control-label"><strong>
                            Название:</strong><br>
                        <br></label>

                    <div class="col-sm-4"><input type="text" class="form-control" id="title" name="title"
                                                 style="font-size:12px;" value="<?php echo $filerow['title']; ?>"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label"><strong>
                            Категория:</strong><br>
                        <br></label>

                    <div class="col-sm-4"><select id="cat_id" name="cat_id" class="form-control"
                                                  style="font-size:12px;width:100%;">
                            <?php $fc->getFCatalogDropList(0, 0, '--', $filerow->cat_id ? $filerow['cat_id'] : 1); ?>

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">Описание:</label>

                    <div class="col-sm-10">
                        <?php Core::loadEditor("description", "99%", "300", $filerow['description']); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-1 control-label"><strong>Ключевые слова:</strong>
                    </label>

                    <div class="col-sm-4"><input type="text" id="tags" name="tags" class="tags" data-role="tagsinput"
                                                 style="font-size:12px;" value="<?php echo $filerow['tags']; ?>"></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-1 control-label"><strong>Файл:</strong>
                    </label>

                    <div class="col-sm-10">
                        <div id="my_tabView">
                            <div class="my_aTab" style="display:none;">
                                <input type="text" class="form-control" id="fileurl" name="fileurl"
                                       style="font-size:12px;" value="<?php echo $filerow['fileurl']?>">
                            </div>
                            <div class="my_aTab" style="display:none;">
                                <input type="file" id="filename" name="filename" class="styled" size="40"
                                       style="font-size:12px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions text-right">
                    <?php if ($opt=='edit_item') {?>
                    <input type="hidden" name="f_id" value="<?php echo $filerow['id']; ?>">
                    <?php }?>
                    <input type="hidden" name="opt" value="<?php if ($opt=='additem') { ?>submititem<?php } else { ?>updateitem<?php } ?>"/>
                    <input type="submit" class="btn btn-danger" value="Сохранить изменения">
                    <input type="submit" class="btn btn-info" value="Добавить файл">
                    <input type="button" class="btn btn-default" value="Отмена"
                           onclick="window.document.location.href='/fcatalog/';">
                </div>
            </div>
        </div>

        <script type="text/javascript">
            initTabs('my_tabView', Array('Ссылка', 'Загрузить'), 0, '100%');
        </script>

        <script>

            $("#addform").submit(function () {
                tit = $('#title').val();
                if (tit.length > 4) {
                    return true;
                }
                else {
                    alert('Название файла должно быть не менее 10-ти символов!');
                    return false;
                }
            });

        </script>

    </form>
    <?php

}

if ($opt == 'add' || $opt == 'edit_cat') {

    if ($opt == 'edit_cat') {
        $id = (int)$_REQUEST['item_id'];
        $sql = "SELECT * FROM files_cat WHERE id = $id LIMIT 1";
        $res = $db->query($sql);
        if ($db->numrows($res)) {
            $mod = $db->fetch($res, true);

        }
    }
    ?>

    <div class="page-header">
        <div class="page-title">
            <h3>Добавить категорию</h3>
        </div>

        <div class="visible-xs header-element-toggle">
            <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-info-buttons"><i
                    class="icon-info"></i></a>
        </div>

        <div class="header-info-buttons">
            <div class="collapse" id="header-info-buttons">
                <div class="well">
                    <ul class="info-buttons">
                        <li><a href="#"><i class="icon-disk"></i> <span>Сохранить</span> </a></li>
                        <li><a href="#"><i class="icon-cancel"></i> <span>Отмена</span> </a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <form class="form-horizontal" id="addform" name="addform" method="post"
          action="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>"
          enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Название</label>

                    <div class="col-sm-2"><input name="title" type="text" id="title" class="form-control"
                                                 value="<?php echo @$mod['title']; ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Родительская категория: </label>

                    <div class="col-sm-2">
                        <select name="parent_id" id="parent_id" class="form-control">
                            <?php
                            $fc->getFCatalogDropList(0, 0, '--', $mod['parent_id'] ? $mod['parent_id'] : 1);

                            ?>
                        </select></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"> Изображение </label>

                    <div class="col-sm-2"><input name="icon" id="icon" type="file" class="styled"/>
                    </div>
                </div>
                <?php if ($mod['icon']) { ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-2">

                            <img src="/images/fcatalog/<?php echo @$mod['icon']; ?>">
                            <input class="styled" type="checkbox"
                                   name="del_icon"
                                   id="del_icon" value="1"/>
                            удалить изображение
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="is_public" class="col-sm-2 control-label">
                        <?php
                        $sql = "SELECT * FROM user_groups";
                        $result = $db->query($sql);

                        $style = 'disabled="disabled"';
                        $public = 'checked="checked"';

                        if ($opt == 'edit_cat') {

                            $sql2 = "SELECT * FROM files_access WHERE c_id = $mod[id]";
                            $result2 = $db->query($sql2);
                            $ord = array();

                            if ($db->numrows($result2)) {
                                $public = '';
                                $style = '';
                                while ($r = $db->fetch($result2, true)) {
                                    $ord[] = $r['group_id'];
                                }
                            }
                        }
                        ?>
                        Общий доступ</label>
                    <br/>

                    <div class="col-sm-2">
                        <input name="is_public" class="styled" type="checkbox" id="is_public" onclick="chkGroupList()"
                               value="1" <?php echo $public ?> />
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Доступ группам:
                    </label>

                    <div class="col-sm-2">

                        <?php
                        echo '<select style="width:400px;" class="form-control" name="showfor[]" id="showin" size="6" multiple="multiple" ' . $style . '>';

                        if ($db->numrows($result)) {
                            while ($item = $db->fetch($result, true)) {
                                echo '<option value="' . $item['group_id'] . '"';
                                if ($opt == 'edit_cat') {
                                    if (inArray($ord, $item['group_id'])) {
                                        echo 'selected';
                                    }
                                }

                                echo '>';
                                echo $item['title'] . '</option>';
                            }
                        }

                        echo '</select>';
                        ?>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Описание категории
                    </label>

                    <div class="col-sm-10">
                        <?php Core::loadEditor("description", "99%", "300", $mod['description']); ?>
                    </div>
                </div>


                <input name="opt" type="hidden" id="opt" <?php if ($opt == 'add') {
                    echo 'value="submit"';
                } else {
                    echo 'value="update"';
                } ?> />
                <?php
                if ($opt == 'edit_cat') {
                    echo '<input name="item_id" type="hidden" value="' . $mod['id'] . '" />';
                }
                ?>
                <div class="form-actions text-center">
                    <input name="save" class="btn btn-danger" type="submit" id="save" value="Сохранить"/>
                    <input name="back" type="button" id="back" class="btn btn-primary" value="Отмена"
                           onclick="window.location.href='index.php?do=components&action=config&id=<?php echo $_REQUEST['id'] ?>&opt=list_cat';"/>
                </div>
            </div>
        </div>
    </form>
    <script>
        function chkGroupList() {

            if ($('#is_public').attr('checked')) {
                $('#showin').attr('disabled', 'disabled');
            } else {
                $('#showin').attr('disabled', '');
            }

        }
    </script>

    <?php
}
?>

