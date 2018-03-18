<?php

if (isset($_REQUEST['opt'])) {    $opt = $_REQUEST['opt']; } else {     $opt = 'view'; }

if (isset($_REQUEST['id'])) {     $id = $_REQUEST['id']; } else {     $id = -1; }

$photos = Registry::get("Photos");
$pager = Registry::get("Paginator");
$db=Registry::get("DataBase");
$cfg=Registry::get("Config");

$albums= array();
$photo = array();

echo '<div class="panel panel-default">
    <div class="panel-body">
            <ul class="nav nav-pills" role="tablist">
                <li role="presentation"><a href="index.php?do=components&action=config&id=' . $id . '&opt=add_album">Новый альбом</a>
                </li>
                <li role="presentation"><a href="index.php?do=components&action=config&id=' . $id . '&opt=add_photo">Новый фотография</a></li>
                <li role="presentation"><a href="index.php?do=components&action=config&id=' . $id . '&opt=photoalbums"">Фотоалбомы</a></li>
                <li role="presentation"><a href="index.php?do=components&action=config&id=' . $id . '&opt=allPhotos">Все фотографии</a></li>
                <li role="presentation"><a href="index.php?do=components&action=config&id=' . $id . '&opt=multiPhotos">Массовий загрузка</a></li>
            </ul>

    </div>
</div>';
if (@$msg) {
    echo '<p class="success">' . $msg . '</p>';
}

$sql = "SELECT config FROM components WHERE link = 'photos'";
$result = $db->query($sql);
if ($db->numrows($result)) {
    $conf = $db->fetch($result,true);
    if ($conf) {
        $config = unserialize($conf['config']);
    }
}

if ($opt == 'saveconfig') {
    $cfg = array();
    $cfg['maxcols']=$_REQUEST['maxcols'];
    $cfg['showlat']=$_REQUEST['showlat'];
    $cfg['watermark']=$_REQUEST['watermark'];
    $cfg['showdate'] = $_REQUEST['showdate'];
    $cfg['preview'] = $_REQUEST['preview'];
    $cfg['link'] = $_REQUEST['link'];
    $cfg['view_type'] = $_REQUEST['view_type'];

//    $sql = "UPDATE components SET config = '" . serialize($cfg) . "' WHERE link = 'photos'";
    $db->update('components',array('config'=>serialize($cfg)),"link='photos'");

    $msg = 'Настройки сохранены.';
    $opt = 'config';
    header("Location:index.php?do=components&action=config&id=".$id);
}

if ($opt == 'view') {
?>
    <div class="panel panel-default">
<div class="panel-body">
        <form class="form-horizontal" action="index.php?do=components&amp;action=config&amp;id=<?=$id;?>" method="post" name="optform" target="_self"
              >

                <div class="form-group">
                    <label class="col-sm-2 control-label">Показывать дату публикации:</label>

                    <div class="col-sm-2">
                    <label>
                       <input name="showdate" class="styled" type="radio" value="1" checked="checked">
                        Да
                        </label>
                        <label>
                        <input name="showdate" class="styled" type="radio" value="0">
                        Нет
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ссылка на оригинал :</label>
                    <div class="col-sm-2">
                    <input class="styled" name="link" type="radio" value="1" checked="checked">
                        Да
                        <input  class="styled" name="link" type="radio" value="0">
                        Нет
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Количество колонок для вывода списка альбомов:</label>
                    <div class="col-sm-2"><input class="form-control" name="maxcols" type="text" id="maxcols" size="5" value="<?=@$config['maxcols'];?>"/>
                        </div>шт
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Просмотр альбома:</label>
                    <div class="col-sm-2">
                    <select class="form-control" name="view_type" id="view_type">
                            <option value="list" selected="">Список</option>
                            <option value="thumb">Галерея</option>
                        </select></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Показывать ссылки &quot;Последние загруженные&quot; и &quot;Лучшие фото&quot;:</label>
                    <div class="col-sm-2"><input class="styled" name="showlat" type="radio" value="1" <?php if (@$config['showlat']) { echo 'checked="checked"'; } ?>/>
                        Да
                        <input name="showlat" class="styled" type="radio" value="0" <?php if (@!$config['showlat']) { echo 'checked="checked"'; } ?>/>
                        Нет </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Наносить водяной знак:<br/><a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</label>
                    <div class="col-sm-2"><input name="watermark" class="styled" type="radio" value="1" <?php if (@$config['watermark']) { echo 'checked="checked"'; } ?>/>
                        Да
                        <input name="watermark" type="radio" class="styled" value="0" <?php if (@!$config['watermark']) { echo 'checked="checked"'; } ?>/>
                        Нет </div>
                </div>
            <div class="form-actions">
                <input name="opt" type="hidden" id="do" value="saveconfig">
                <input name="save"  type="submit" id="save" class="btn btn-danger" value="Сохранить">
                <input name="back" type="button" id="back" class="btn btn-primary" value="Отмена"
                       onclick="window.location.href='index.php?view=components';">
            </div>
        </form>
</div>

    </div>
<?php

}


if ($opt == 'add_album' || $opt == 'edit_album') {
    if ($opt == 'add_album') {
        echo '  <div class="panel panel-default"><div class="panel-heading"><h6 class="panel-title">Добавить фотоальбом</h6></div>';
    } else {
        if (isset($_REQUEST['itemid'])) {
            $itemid = $_REQUEST['itemid'];
            $sql = "SELECT * FROM photoalbums WHERE id = $itemid LIMIT 1";
                    $result = $db->query($sql);
            if ($db->numrows($result)) {
                $mod = $db->fetch($result,true);
            }
        }
    }

    if (!isset($mod['thumb1'])) { $mod['thumb1'] = 96; }
    if (!isset($mod['thumb2'])) { $mod['thumb2'] = 450; }
    if (!isset($mod['thumbsqr'])) { $mod['thumbsqr'] = 1; }
    if (!isset($mod['maxcols'])) { $mod['maxcols'] = 4; }
    if (!isset($mod['showtype'])) { $mod['showtype'] = 'lightbox'; }
    if (!isset($mod['perpage'])) { $mod['perpage'] = '20'; }
    if (!isset($mod['uplimit'])) { $mod['uplimit'] = 20; }
    if (!isset($mod['published'])) { $mod['published'] = 1; }
    ?>

        <div class="panel-body">
        <form id="addform" name="addform" method="post" class="form-horizontal" action="index.php?do=components&amp;action=config&amp;id=<?=$id?>">
                <div class="form-group">
                   <label class="col-sm-2 control-label">Название альбома:</label>
                 <div class="col-sm-5">
                    <input name="title" type="text" class="form-control" id="title" size="30" value="<?=$mod['title']?>">
                    </div>
                </div>

                <div class="form-group">
                 <label class="col-sm-2 control-label">Родительский альбом:</label>
                             <div class="col-sm-5">
                    <select name="parent_id" size="8" class="form-control" id="parent_id" style="width:250px">
                            <?php
                                $photos->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", (int)$mod['parent_id'] ? $mod['parent_id'] : 1);
                            ?>
                        </select>

                </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Публиковать альбом?</label>
                    <div class="col-sm-5">
                    <div class="block-inner">
                    <label class="checkbox-inline checkbox-success">
                    <input name="published"  class="styled" type="radio" value="1" <?php if (@$mod['published']) { echo 'checked="checked"'; } ?> />
                        Да
                        </label>
                        <label class="checkbox-inline checkbox-success">
                            <input name="published" class="styled" type="radio" value="0" <?php if (@!$mod['published']) { echo 'checked="checked"'; } ?>>
                            Нет</label></div>
                            </div>

                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Показывать даты и количество комментариев? </label>
                    <div class="col-sm-5">
                    <div class="block-inner">
                    <label class="checkbox-inline checkbox-success">
                    <input name="showdate" type="radio" class="styled"  value="1" checked="checked" <?php if (@$mod['showdate']) { echo 'checked="checked"'; } ?> />
                        Да
                        </label>
                        <label>
                            <input name="showdate" class="styled" type="radio" value="0"  <?php if (@!$mod['showdate']) { echo 'checked="checked"'; } ?> />
                            Нет</label></div>
                </div>
                </div>

  <div class="form-group">
                    <label class="col-sm-2 control-label">Показывать теги фото: </label>
                    <div class="col-sm-5">
                    <div class="block-inner">
                    <input name="showtags" type="radio" value="1" class="styled" checked="checked" <?php if (@$mod['showtags']) { echo 'checked="checked"'; } ?> />
                        Да
                        <label>
                            <input name="showtags" type="radio" class="styled" value="0"  <?php if (@!$mod['showtags']) { echo 'checked="checked"'; } ?> />
                            Нет</label></div>
                </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">Сортировать фото: </label>
                    <div class="col-sm-10">
                    <div class="row">
                    <div class="col-sm-2">
                    <select name="orderby" id="orderby" class="form-control">
                            <option value="title" <?php if(@$mod['orderby']=='title') { echo 'selected'; } ?>>По алфавиту</option>
                            <option value="pubdate" <?php if(@$mod['orderby']=='pubdate') { echo 'selected'; } ?>>По дате</option>
                            <option value="rating" <?php if(@$mod['orderby']=='rating') { echo 'selected'; } ?>>По рейтингу</option>
                            <option value="hits" <?php if(@$mod['orderby']=='hits') { echo 'selected'; } ?>>По просмотрам</option>
                        </select>
                        </div>
                        <div class="col-sm-2">
                        <select name="orderto" id="orderto" class="form-control">
                            <option value="desc" <?php if(@$mod['orderto']=='desc') { echo 'selected'; } ?>>по убыванию</option>
                            <option value="asc" <?php if(@$mod['orderto']=='asc') { echo 'selected'; } ?>>по возрастанию</option>
                        </select>
                        </div>
                        </div>
                </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Вывод фотографий: </label>
                    <div class="col-sm-2">
                    <select name="showtype" class="form-control" id="showtype">
                            <option value="list" <?php if(@$mod['showtype']=='list') { echo 'selected'; } ?>>Таблица (список)</option>
                            <option value="thumb" <?php if(@$mod['showtype']=='thumb') { echo 'selected'; } ?>>Галерея</option>
                            <option value="lightbox" <?php if(@$mod['showtype']=='lightbox') { echo 'selected'; } ?>>Галерея (лайтбокс)</option>
                            <option value="simple" <?php if(@$mod['showtype']=='simple') { echo 'selected'; } ?>>Галерея (простая)</option>
                            <option value="fast" <?php if(@$mod['showtype']=='fast') { echo 'selected'; } ?>>Галерея (быстрая)</option>
                        </select></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Число колонок для вывода:</label>
                    <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-1">
                    <input name="maxcols" class="form-control" type="text" id="maxcols" size="5" value="<?=@$mod['maxcols'];?>"/>
                    </div>
                    <div class="col-sm-1">
                        шт</div>

                        </div>
                </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Добавление фото пользователями: </label>
                    <div class="col-sm-2"><select name="public" id="select" class="form-control">
                            <option value="0" <?php if(@$mod['public']=='0') { echo 'selected'; } ?>>Запрещено</option>
                            <option value="1" <?php if(@$mod['public']=='1') { echo 'selected'; } ?>>Разрешено с премодерацией</option>
                            <option value="2" <?php if(@$mod['public']=='2') { echo 'selected'; } ?>>Разрешено без модерации</option>
                        </select></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Форма сортировки:  </label>
                    <div class="col-sm-5 ">
                    <div class="block-inner">
                    <label>
                    <input name="orderform" class="styled" type="radio" value="1" checked="checked" <?php if (@$mod['orderform']) { echo 'checked="checked"'; } ?> />
                        Показать
                        </label>
                        <label>
                            <input name="orderform" type="radio" value="0"  class="styled" <?php if (@!$mod['orderform']) { echo 'checked="checked"'; } ?> />
                            Скрыть		        </label></div>
                </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Максимум загрузок от одного пользователя в сутки: </label>
                    <div class="col-sm-4">
                    <div class="row">
                    <div class="col-sm-2">
                    <input class="form-control" name="uplimit" type="text" id="uplimit" size="5" value="<?=@$mod['uplimit'];?>"/>
                    </div>
                    <div class="col-sm-2">
                        шт</div>
                        </div>
                        </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">CSS-префикс фотографий: </label>
                    <div class="col-sm-2"><input name="cssprefix" type="text" id="cssprefix" class="form-control" value="<?=@$mod['cssprefix'];?>"/></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Фотографий на странице: </label>
                    <div class="col-sm-1"><input class="form-control" name="perpage" type="text" id="perpage" size="5" value="<?=@$mod['perpage'];?>"/>
                        </div>шт
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ширина маленькой копии: </label>
                    <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-1">
                        <input class="form-control" name="thumb1" type="text" id="thumb1" size="5" value="<?=@$mod['thumb1'];?>"/>

                        </div>
<div class="col-sm-1">
пикс.
</div>
                        <div class="col-sm-3">
                        <table border="0" cellspacing="0" cellpadding="1">
                            <tr>
                                <td width="100" valign="middle">
                                    </td>
                                <td width="100" align="center" valign="middle" style="background-color:#EBEBEB">Квадратные:</td>
                                <td width="115" align="center" valign="middle" style="background-color:#EBEBEB">
                                <input name="thumbsqr" type="radio" value="1" checked="checked" <?php if (@$mod['thumbsqr']) { echo 'checked="checked"'; } ?> />Да
                                    <label>
                                        <input name="thumbsqr" type="radio" value="0"  <?php if (@!$mod['thumbsqr']) { echo 'checked="checked"'; } ?> />Нет</label></td>
                            </tr>
                        </table>
                        </div>
                    </div>



                        </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ширина средней копии: </label>
                    <div class="col-sm-1"><input class="form-control" name="thumb2" type="text" id="thumb2"  value="<?=@$mod['thumb2'];?>"/>  </div> пикс.
                </div>



                <div class="form-group">
                    <label class="col-sm-2 control-label">Описание альбома:</label>

                        <div class="col-sm-10">
                            <?php
                             Core::loadEditor("description", "99%", "300", $mod['album_desc']);
                            ?>
                        </div>

                </div>

            <div class="form-actions text-right">
                <input name="opt" type="hidden" id="opt" <?php if ($opt=='add_album') { echo 'value="submit_album"'; } else { echo 'value="update_album"'; } ?> />


                    <input name="add_mod" class="btn btn-danger" type="submit" id="add_mod" <?php if ($opt=='add_album') { echo 'value="Создать альбом"'; } else { echo 'value="Сохранить альбом"'; } ?> />
                    <input class="btn btn-primary" name="back2" type="button" id="back2" value="Отмена"
                           onclick="window.location.href='index.php?do=components&action=config&id=<?php echo $id; ?>&opt=photoalbums'">
                <?php
                if ($opt=='edit_album'){
                    echo '<input name="itemid" type="hidden" value="'.$mod['id'].'" />';
                }
                ?>
            </div>


        </form>
            </div>

<?php
}

if ($opt=='photoalbums'){
?>
    <div  class="panel panel-default">
    <div class="panel-heading"><h6 class="panel-title"><i class="icon-table2"></i>Фотоалбомы</h6></div>
    <div class="panel-body">
    <table class="table table-striped table-bordered">

        <thead>
        <tr>
        <th align="center"><input type="checkbox" id="all_boxes" class="menu_checkbox"  name='all_boxes' onClick='changeall(contForm);'></th>
        <th align="center">ID</th>
        <th align="center">Название</th>
        <th align="center">Показ</th>
        <th align="center" >Действия</th>
</tr>
</thead>
<tbody>
        <?
    $query=$db->query("select * from photoalbums where parent_id>0 ");
    if ($db->numrows($query)){
    while ($rows=$db->fetch($query,true)){
        $publ=$rows['published']?"<i class='icon-checkmark-circle2'></i>":"<i class='icon-cancel-circle2'></i>";
        ?>
        <tr>
            <td align="left"><input type="checkbox" value="<?php echo $rows['id'] ?>" id="board_check[]" name="board_check[]">
            </td>
            <td align="left"><a
                    href="index.php?view=components&do=config&id=<?php echo $id ?>&opt=edit_album&itemid=<?php echo $rows['id']; ?>"><?php echo $rows['id'] ?></a>
            </td>
            <td align="left"><a
                    href="index.php?view=components&do=config&id=<?php echo $id ?>&opt=edit_album&itemid=<?php echo $rows['id']; ?>"><?php echo $rows['title'] ?></a>
            </td>
            <td align="center"><a href="index.php?view=components&do=config&id=<?php echo $id ?>&opt=published_album&pub=<?php echo $rows['published'] ?>&itemid=<?php echo $rows['id']; ?>"><?php echo $publ; ?><a/></td>
           <td align="center">
                <a  class="btn btn-default btn-icon btn-xs tip" href="index.php?do=components&action=config&id=<?php echo $id ?>&opt=edit_album&itemid=<?php echo $rows['id'] ?>"> <i class="icon-pencil"></i></a>
                <a class="btn btn-default btn-icon btn-xs tip" href="index.php?do=components&action=config&id=<?php echo $id ?>&opt=delete_album&itemid=<?php echo $rows['id'] ?>"><i class="icon-remove"></i></a>
            </td>
            </td>
        </tr>
        <?
    }

    }else{
        ?>
        <tr>
            <td  colspan="5" align="center"><?php echo 'Нет отоброжения албом';?></td>
        </tr>
        <?

    }
    ?>
    </table>
    </tbody>
        </div>
        </div>
        <?
    }

if ($opt=='add_photo' || $opt=='edit_photo'){
    if ($opt=='add_photo'){
        echo '<div class="panel panel-default"><div class="panel-heading"><h6 class="panel-title">Добавить фотоаграфии</h6></div>';
    } else {
        echo '<div class="panel panel-default"><div class="panel-heading"><h6 class="panel-title">Редактировать фотоаграфии</h6></div>';
        if (isset($_REQUEST['itemid'])){
            $itemid=$_REQUEST['itemid'];

            $sql = "SELECT * FROM photofiles WHERE id = $itemid LIMIT 1";
            $result = $db->query($sql) ;
            if ($db->numrows($result)){
                $mod = $db->fetch($result,true);
            }
        }
    }
    ?>
    <div  class="panel-body" >
        <form class="form-horizontal"  method="post" enctype="multipart/form-data" name="addform" id="addform">
            <div class="form-group">
                    <label class="col-sm-2 control-label">Название фотографии:</label>
                    <div class="col-sm-2">

                    <input name="title"  class="form-control" type="text" id="title"  value="<?=$mod['title'];?>"/>
                    </div>
            </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Фотоальбом:</label>
                    <div class="col-sm-2"><select class="form-control" name="albumid" id="albumid">
                            <?php
                            $photos->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", (int)$mod['photoalbumid'] ? $mod['photoalbumid'] : 1);
                            ?>
                        </select>
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Файл фотографии: </label>

                    <div class="col-sm-3">

                    <?php if (@$mod['files']) { echo '<a href="/images/photos/'.$mod['files'].'" title="Посмотреть фото">'.$mod['files'].'</a>'; } ?>
                            <input name="picture" class="styled" type="file" id="picture" size="30" />
                        </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Публиковать фотографию?</label>
                    <div class="col-sm-2">
                    <label>
                    <input class="styled" name="published" type="radio" value="1" checked="checked" <?php if (@$mod['published']) { echo 'checked="checked"'; } ?> />

                        Да
                        </label>
                        <label>
                            <input name="published" class="styled" type="radio" value="0"  <?php if (@!$mod['published']) { echo 'checked="checked"'; } ?> />
                            Нет</label></div>

                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Показывать дату? </label>
                    <div class="col-sm-2"><input name="showdate" class="styled" type="radio" value="1" checked="checked" <?php if (@$mod['showdate']) { echo 'checked="checked"'; } ?> />
                        Да
                        <label>
                            <input name="showdate" class="styled" type="radio" value="0"  <?php if (@!$mod['showdate']) { echo 'checked="checked"'; } ?> />
                            Нет</label></div>
                    <td>&nbsp;</td>
                </div>

        <div class="form-group">
                    <label class="col-sm-2 control-label">Описание фотографии:

                        </label>
                        <div class="col-sm-10">
                          <?php
                            Core::loadEditor("description", "99%", "300", $mod['album_desc']);
                            ?>
                            </div>
                </div>

            <div class="form-actions text-right">
                <input name="add_mod" type="submit" class="btn btn-danger" id="add_mod" <?php if ($opt=='add_photo') { echo 'value="Загрузить фото"'; } else { echo 'value="Сохранить фото"'; } ?> />
                <input name="back3" type="button" class="btn btn-primary" id="back3" value="Отмена" onclick="window.location.href='index.php?do=components';"/>
                <input name="opt" type="hidden" id="opt" <?php if ($opt=='add_photo') { echo 'value="submit_photo"'; } else { echo 'value="update_photo"'; } ?> />
                <?php
                if ($opt=='edit_photo'){
                    echo '<input name="itemid" type="hidden" value="'.$mod['id'].'" />';
                }
                ?>
            </div>
        </form>
    </div>
    </div>
    <?
}

if ($opt == 'submit_album') {

    $albums['parent_id']=$_REQUEST['parent_id'];
    $albums['pubdate']=date('Y-m-d H:i:s');
    $albums['title'] = $_REQUEST['title'];
    $albums['description'] = $_REQUEST['description'];
    $albums['published'] = intval($_REQUEST['published']);
    $albums['showdate'] = intval($_REQUEST['showdate']);
    $albums['showtype'] = $_REQUEST['showtype'];
    $albums['public']= $_REQUEST['public'];
    $albums['orderby'] = $_REQUEST['orderby'];
    $albums['orderto'] = $_REQUEST['orderto'];
    $albums['perpage'] = $_REQUEST['perpage'];
    $albums['thumb1'] = intval($_REQUEST['thumb1']);
    $albums['thumb2'] = intval($_REQUEST['thumb2']);
    $albums['thumbsqr'] = $_REQUEST['thumbsqr'];
    $albums['cssprefix'] = $_REQUEST['cssprefix'];
    $albums['uplimit'] = $_REQUEST['uplimit'];
    $albums['maxcols'] = $_REQUEST['maxcols'];
    $albums['orderform'] = $_REQUEST['orderform'];
    $albums['showtags'] = $_REQUEST['showtags'];

    $db->insert("photoalbums", $albums);

    $msg = 'Успещно сохранени фото албомь.';
    $opt = 'add';
    header('location:index.php?do=components&action=config&id='.$id.'&opt=photoalbums');
}

if ($opt=='submit_photo'){
  $photo['photoalbumid']=intval($_REQUEST['albumid']);
  $photo['title']=$_REQUEST['title'];
  $photo['description']=$_REQUEST['description'];
  $photo['pubdate']=date('Y-m-d H:i:s');

  $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/images/photos/';
  $realfile = $_FILES['picture']['name'];
   $ext = substr($_FILES['picture']['name'], strrpos($_FILES['picture']['name'], '.') + 1);
  $filename = md5($realfile).'.'.$ext;
  $uploadfile = $uploaddir . $realfile;
  $uploadphoto = $uploaddir . $filename;
  $uploadthumborg = $uploaddir . $filename;
  $uploadthumb = $uploaddir . 'small/' . $filename;
  $uploadthumb2 = $uploaddir . 'medium/' . $filename;

  $photo['files']=$filename;
  $photo['published']=intval($_REQUEST['published']);
  $photo['hits']=0;
  $photo['comments']=0;
  $photo['showdate']=$_REQUEST['showdate'];

    $sql_album = "SELECT thumb1, thumb2, thumbsqr FROM photoalbums WHERE id = ".(int)$_REQUEST['albumid'];
    $rs_album = $db->query($sql_album) ;
    if ($db->numrows($rs_album)){ $album = $db->fetch($rs_album,true); }

    $db->insert('photofiles',$photo);

    $inUploadPhoto = Registry::get("Uploads");
    $inUploadPhoto->upload_path = PATH . '/images/photos/';
    $inUploadPhoto->small_size_w = 100;
    $inUploadPhoto->medium_size_w = 200;
    $inUploadPhoto->thumbsqr = 1;
    $inUploadPhoto->is_watermark = 0;
    $inUploadPhoto->input_name = 'picture';
    $inUploadPhoto->filename = $filename;
    $inUploadPhoto->upload();
    header('location:index.php?do=components&action=config&id='.$id.'&opt=allPhotos');
}

if ($opt=='update_photo'){


 $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/images/photos/';
  $realfile = $_FILES['picture']['name'];
   $ext = substr($_FILES['picture']['name'], strrpos($_FILES['picture']['name'], '.') + 1);
  $filename = md5($realfile).'.'.$ext;

    if (isset($_REQUEST['itemid'])){
        $itemid=$_REQUEST['itemid'];
        $photo['photoalbumid']=intval($_REQUEST['albumid']);
        $photo['title']=$_REQUEST['title'];
        $photo['description']=$_REQUEST['description'];
        $photo['pubdate']=date('Y-m-d H:i:s');
        $photo['published']=intval($_REQUEST['published']);
        $photo['hits']=0;
        $photo['comments']=0;
        $photo['showdate']=$_REQUEST['showdate'];


        if (empty($_FILES['picture']['name']) || $_FILES['picture']['name']!=''){
        $old_images=$db->getValuesById('files','photofiles',$itemid);
        unlink($uploaddir.$old_images->files);
        unlink($uploaddir.'/small/'.$old_images->files);
        unlink($uploaddir.'/medium/'.$old_images->files);

          $photo['files']=$filename;
          $inUploadPhoto = Registry::get("Uploads");
          $inUploadPhoto->upload_path = PATH . '/images/photos/';
          $inUploadPhoto->small_size_w = 100;
          $inUploadPhoto->medium_size_w = 200;
          $inUploadPhoto->thumbsqr = 1;
          $inUploadPhoto->is_watermark = 0;
          $inUploadPhoto->input_name = 'picture';
          $inUploadPhoto->filename = $filename;
          $inUploadPhoto->upload();
          }

          $db->update("photofiles",$photo,'id='.$itemid);

    }
//    header('location:index.php?do=components&action=config&id='.$id.'&opt=allPhotos');
}

if ($opt=='published_album'){
    if (isset($_REQUEST['pub'])){
        $pub=$_REQUEST['pub']?0:1;
        $itemid=$_REQUEST['itemid'];

        $db->query("update photoalbums set published='{$pub}' where id='{$itemid}'");
    }
    header('location:index.php?do=components&action=config&id='.$id.'&opt=photoalbums');
}

if ($opt=='delete_photo'){
    if (isset($_REQUEST['itemid'])){
        $itemid=$_REQUEST['itemid'];
        $db->delete("photofiles",'id='.$itemid);

    }
    header('location:index.php?do=components&action=config&id='.$id.'&opt=allPhotos');

}

if ($opt=='delete_album'){
    if (isset($_REQUEST['itemid'])){
        $itemid=$_REQUEST['itemid'];
        $db->delete("photoalbums", 'id='.$itemid);

    }
    header('location:index.php?do=components&action=config&id='.$id.'&opt=photoalbums');
}

if ($opt=='allPhotos'){
    ?>
    <form method="post" name="addform" id="addform">
    <div  class="panel panel-default" >
    <div class="panel-heading"><h6 class="panel-title"><i class="icon-table2"></i> Все фотографии</h6></div>
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

    <table id="table1" class="table table-striped table-bordered">
        <thead>
        <tr>
        <th align="left"><input type="checkbox" id="all_boxes" class="menu_checkbox"  name='all_boxes' onClick='changeall(contForm);'></th>
        <th align="left">ID</th>
        <th align="left">Дата</th>
        <th align="left">Название</th>
        <th align="left">Показ</th>
        <th align="left">Посмотров</th>
        <th align="left">Албомь</th>
        <th align="center" >Действия</th>
        </tr>
        </thead>
        <tbody>
        <?
//        $query=$db->query("select phf.*,phm.title as album from photofiles phf left join photoalbums phm on phm.id=phf.photoalbumid");
//        if ($db->numrows($query)){
            foreach ($photos->getAllPhotos() as $rows){
                $publ=$rows['published']?"<i class='icon-checkmark-circle2'></i>":"<i class='icon-cancel-circle2'></i>";
                ?>
                <tr>
                    <td align="left"><input type="checkbox" value="<?php echo $rows['id'] ?>" id="board_check[]" name="board_check[]">
                    </td>
                    <td align="left"><a
                            href="index.php?do=components&action=config&id=<?=$id;?>&opt=edit_photo&itemid=<?php echo $rows['id'] ?>"><?php echo $rows['id'] ?></a>
                    </td>
                    <td align="left"><a
                            href="index.php?do=components&action=config&id=<?=$id;?>&opt=edit_photo&itemid=<?php echo $rows['id'] ?>"><?php echo $rows['pubdate'] ?></a>
                    </td>
                    <td align="left"><a
                            href="index.php?do=components&action=config&id=<?=$id;?>&opt=edit_photo&itemid=<?php echo $rows['id'] ?>"><?php echo $rows['title'] ?></a>
                    </td>

                    <td align="center"><a href="index.php?do=content&action=published&pub=<?php echo $rows['published'] ?>&id=<?php echo $rows['id']; ?>"><?php echo $publ; ?><a/></td>
                    <td align="center"><a
                            href="index.php?do=components&action=config&id=<?=$id;?>&opt=edit_photo&itemid=<?php echo $rows['id'] ?>"><?php echo $rows['hits'] ?></a>
                    </td>
                    <td align="c"><a
                            href="index.php?do=components&action=config&id=<?=$id;?>&opt=edit_photo&itemid=<?php echo $rows['id'] ?>"><?php echo $rows['album'] ?></a>
                    </td>
                    <td align="center"><a class="btn btn-default btn-icon btn-xs tip" href="index.php?do=components&action=config&id=<?=$id; ?>&opt=edit_photo&itemid=<?php echo $rows['id'] ?>"> <i class="icon-pencil"></i></a>
                        <a class="btn btn-default btn-icon btn-xs tip" href="index.php?do=components&action=config&id=<?=$id; ?>&opt=delete_photo&itemid=<?php echo $rows['id'] ?>" ><i class="icon-remove"></i></a>
                    </td>
                    </td>
                </tr>
                <?
            }


//        }else{
//            ?>
<!--            <tr>-->
<!--            <td colspan="7">--><?php //echo 'Нет отоброжения албом';?><!--</td>-->
<!--            </tr>-->
<!--            --><?//
//
//        }
        ?>
        </tbody>
    </table>
    <div class="table-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="dataTables_paginate paging_bootstrap" id="example2_paginate">
                                    <?php if ($pager->display_pages()) { ?>
                                        <?php echo $pager->display_pages(); ?>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>
                    </div>
</div>
</form>
<?

}

if ($opt == 'submit_photo_multi'){
    echo '<h3>Загрузка файлов завершена</h3>';
    $photo['photoalbumid']     = $_REQUEST['album_id'];
    $photo['published']    = $_REQUEST['published'];
    $photo['showdate']     = $_REQUEST['showdate'];
    $photo['pubdate']     = 'NOW()';
    $photo['hits']     = 0;
    $photo['comments']     = 0;
    $photo['user_id']     = Registry::get("Users")->userid;
    $uploaddir             = $_SERVER['DOCUMENT_ROOT'].'/images/photos/';
    $titlemode             = $_REQUEST['titlemode'];

    $loaded_files = array();
    $list_files = array();
    $sql_album = "SELECT thumb1, thumb2, thumbsqr FROM photoalbums WHERE id = ".$_REQUEST['album_id'];
    $rs_album = $db->query($sql_album);
    if ($db->numrows($rs_album)){ $album = $db->fetch($rs_album,true); }

    foreach($_FILES['upfile'] as $key=>$value) {
        foreach($value as $k=>$v) { $list_files['upfile'.$k][$key] = $v; }

    }
        foreach ($list_files as $key=>$data_array){
            $error = $data_array['error'];
            if ($error == UPLOAD_ERR_OK) {
                $realfile = $data_array['name'];
                $tmp_name = $data_array['tmp_name'];
                $filename = md5($realfile . '-'. time()).'.jpg';
                $uploadfile = $uploaddir . $realfile;
                $uploadphoto = $uploaddir .'/'. $filename;
                $uploadthumb = $uploaddir . 'small/' . $filename;
                $uploadthumb2 = $uploaddir . 'medium/' . $filename;
                $photo['files'] = $filename;
                if (move_uploaded_file($tmp_name, $uploadphoto)){
                    $loaded_files[] = $realfile;
                    if (!isset($conf->watermark)){$conf->watermark=0;}
                    $photos->img_resize($uploadphoto,$uploadthumb,$album['thumb1'],$album['thumb1'],$album['thumbsqr']);
                    $photos->img_resize($uploadphoto,$uploadthumb2,$album['thumb2'],$album['thumb2'],false,$cfg->wmark);
                    if ($conf->watermark){$photos->img_add_watermark($uploadphoto);}
                    if($titlemode == 'number'){
                        $photo['title'] = 'Фото #'.sizeof($loaded_files);

                    } else {
                        $photo['title'] = $realfile;
                    }
                    $db->insert("photofiles",$photo);
//                    $sql = "INSERT INTO photofiles(photoalbumid, title,description,  pubdate, files, published, showdate)
//                                           VALUES ('{$photo['album_id']}', '{$photo['title']}','{$photo['title']}', NOW(),'{$photo['filename']}', '{$photo['published']}','{$photo['showdate']}')";
//                    $db->query($sql);

                }

            }

 }




    echo '<div style="padding:20px">';
    if (count($loaded_files)){
        echo '<div><strong>Загруженные файлы:</strong></div>';
        echo '<ul>';
        foreach($loaded_files as $k=>$val){
            echo '<li>'.$val.'</li>';
        }
        echo '</ul>';
    } else {
        echo '<div style="color:red">Ни один файл не был загружен. Может файлы слишком большие?</div>';
        echo '<div style="color:red">Имена файлов не должны содержать пробелов и русских букв.</div>';
    }
    echo '<div><a href="/admin/index.php?do=components&action=config&opt=allPhotos&id='.$_REQUEST['id'].'">Продолжить</a> &rarr;</div>';
    echo '</div>';
}


if ($opt=='multiPhotos'){
    echo '<div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title"><i class="icon-table2"></i> Массовая загрузка фото</h6></div>';
    ?>
<script type="text/javascript" src="/includes/jquery/multifile/jquery.multifile.js"></script>

<script type="text/javascript">
    function startUpload(){
        $("#upload_btn").attr('disabled', 'true');
        $("#upload_btn").attr('value', 'Идет загрузка...');
        $("#cancel_btn").css('display', 'none');
        $("#loadergif").css('display', 'block');
        document.addform.submit();
    }
</script>
<div class="panel-body">
    <form class="form-horizontal" method="post" enctype="multipart/form-data" name="addform" id="addform">

         <div class="form-group">
             <label class="col-sm-2 control-label">Названия фотографий: </label>
             <div class="col-sm-2">
                     <select class="form-control" name="titlemode" id="titlemode">
                         <option value="number">Фото + номер</option>
                         <option value="original">Оригинальные названия файлов</option>
                     </select>
             </div>
         </div>
         <div class="form-group">
             <label class="col-sm-2 control-label">Фотоальбом:</label>
             <div class="col-sm-2"><select class="form-control" name="album_id" size="8" id="parent_id" style="width:250px">
                     <?php
                      $photos->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", 1);
                     ?>
             </select></div>
         </div>
         <div class="form-group">
             <label class="col-sm-2 control-label">Публиковать фотографии?</label>
             <div class="col-sm-2"><input class="styled" name="published" type="radio" value="1" checked="checked" <?php if (@$mod['published']) { echo 'checked="checked"'; } ?> />
                 Да
                 <label>
                     <input name="published" class="styled" type="radio" value="0"  <?php if (@!$mod['published']) { echo 'checked="checked"'; } ?> />
                     Нет</label></div>
         </div>
         <div class="form-group">
             <label class="col-sm-2 control-label">Показывать даты? </label>
             <div class="col-sm-2"><input name="showdate" class="styled" type="radio" value="1" checked="checked" <?php if (@$mod['showdate']) { echo 'checked="checked"'; } ?> />
                 Да
                 <label>
                     <input name="showdate" type="radio" class="styled" value="0"  <?php if (@!$mod['showdate']) { echo 'checked="checked"'; } ?> />
                     Нет</label></div>
         </div>


             <div class="form-group">
             <label class="col-sm-2 control-label">Файлы фотографий:
                 Выбирайте все фото по очереди </label>
             <div class="col-sm-3">
                 <input type="file" class="multi"  name="upfile[]" id="upfile" />
                 <div id="loadergif" style="display:none;float:left;margin:6px"><img src="/components/photos/img/loader.gif" border="0"/></div>
             </div>
             </div>

         <div class="form-actions">
             <input name="upload_btn" type="button" class="btn btn-danger" id="upload_btn" value="Загрузить фото" onclick="startUpload()"/>
             <input name="back3" type="button" class="btn btn-primary" id="cancel_btn" value="Отмена" onclick="window.location.href='index.php?do=components';"/>
             <input name="opt" type="hidden" id="opt" value="submit_photo_multi" />
         </div>
         </form>
         </div>
         </div>
<?php }
?>