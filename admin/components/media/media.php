<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 29.12.2015
 * Time: 12:02
 */
include(PATH . "/admin/components/media/class_media.php");
Registry::set("Media", new Media());
if (isset($_REQUEST['opt'])) {
    $opt = $_REQUEST['opt'];
} else {
    $opt = 'config';
}
$media = Registry::get("Media");
$pager = Registry::get("Paginator");
$cfg = Registry::get("Core")->getComponentConfig('media');

if ($opt == 'list_movie' || $opt == 'list_mp3' || $opt == 'config') {
    ?>
    <div class="page-header">
        <div class="page-title">
            <h3>Медиагалерия
                <small>Видео и музыка</small>
            </h3>
        </div>

        <div class="visible-xs header-element-toggle">
            <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-info-buttons"><i
                    class="icon-info"></i></a>
        </div>

        <div class="header-info-buttons">
            <div class="collapse" id="header-info-buttons">
                <div class="well">
                    <ul class="info-buttons">
                        <li><a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=add_mp3"><i
                                    class="icon-upload"></i> <span>Новая аудиозапись</span> </a></li>
                        <li><a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=list_mp3"><i
                                    class="icon-music3"></i> <span>Все аудиозаписи</span> </a></li>
                        <li><a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=add_movie"><i
                                    class="icon-upload3"></i> <span>Новая видеозапись</span> </a></li>
                        <li>
                            <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=list_movie"><i
                                    class="icon-movie"></i> <span>Все видеозаписи</span> </a></li>
                        <li><a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=config"><i
                                    class="icon-support"></i> <span>Настройки</span> </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <?php
}
if ($opt == 'list_mp3') {
    ?>
    <form method="post" name="selform">
        <div class="page-header">
            <div class="page-title">
                <h3>Все видоезаписи</h3>
            </div>

            <div class="visible-xs header-element-toggle">
                <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                        class="icon-insert-template"></i></a>
            </div>

            <div class="header-buttons">
                <div class="collapse" id="header-buttons">
                    <div class="well">
                        <div class="btn-group">

                            <a class="btn btn-primary"
                               href="index.php?do=components&action=config&id=7&opt=edit_mp3"><i
                                    class="icon-pencil"></i>Добавить музыку
                            </a>
                            <a class="btn btn-primary"
                               href="index.php?do=components&action=config&id=7&opt=edit_mp3"><i
                                    class="icon-pencil"></i>Редактировать
                            </a>

                            <a class="btn btn-danger"
                               href="javascript:checkSel('index.php?do=components&action=config&id=7&opt=delete_mp3')"><i
                                    class="icon-remove"></i>Удалить
                            </a>

                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=components&action=config&id=7&opt=show_mp3')"><i
                                    class="icon-eye"></i>Показать
                            </a>

                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=components&action=config&id=7&opt=hide_mp3')"><i
                                    class="icon-eye-blocked"></i>Скрыть
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body">

                <div class="col-md-12">
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
                                <th>Дата</th>
                                <th>Название песни</th>
                                <th>Исполнитель</th>
                                <th>Альбом</th>
                                <th>Жанр</th>
                                <th>Показ</th>


                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0;
                            foreach ($media->getMusic() as $mp3) {
                                $count++;
                                ?>
                                <tr>
                                    <th scope="row"><input type="checkbox" name='item[]' id='item[]' class="styled"
                                                           value="<?php echo $mp3['id']; ?>"/></th>
                                    <!--                        <th scope="row">--><?php //echo $count; ?><!--</th>-->
                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_mp3&item_id=<?php echo $mp3['id']; ?>"><?php echo $mp3['id']; ?></a>
                                    </td>
                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_mp3&item_id=<?php echo $mp3['id']; ?>"><?php echo $mp3['pubdate']; ?></a>
                                    </td>
                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_mp3&item_id=<?php echo $mp3['id']; ?>"><?php echo $mp3['name']; ?></a>
                                    </td>
                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_mp3&item_id=<?php echo $mp3['id']; ?>"><?php echo $mp3['singer']; ?></a>
                                    </td>
                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_mp3&item_id=<?php echo $mp3['id']; ?>"><?php echo $mp3['album']; ?></a>
                                    </td>
                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_mp3&item_id=<?php echo $mp3['id']; ?>"><?php echo $mp3['genre']; ?></a>
                                    </td>


                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_mp3&item_id=<?php echo $mp3['id']; ?>">
                                            <div class="state iradio_line-blue checked">
                                                <div
                                                    class="icheck_line-icon"><?php echo $mp3['published'] ? "<i class='icon-checkmark-circle2'></i>" : "<i class='icon-cancel-circle2'></i>"; ?></div>
                                            </div>
                                        </a>
                                    </td>


                                    <td>
                                        <div class="table-controls">
                                            <a href="#" class="btn btn-default btn-icon btn-xs tip" title="Удалить"><i
                                                    class="icon-remove"></i></a>
                                            <a href="index.php?do=components&action=config&id=7&opt=edit_mp3&item_id=<?php echo $mp3->id; ?>"
                                               class="btn btn-default btn-icon btn-xs tip"
                                               title="Редактировать"><i
                                                    class="icon-pencil"></i></a>
                                            <a href="#" class="btn btn-default btn-icon btn-xs tip" title="Просмотр"><i
                                                    class="icon-search2"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="clearfix"></div>

                    </div>

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
            </div>
        </div>
    </form>
    <?php
}
if ($opt == 'list_movie') {
    ?>
    <form method="post" name="selform">
        <div class="page-header">
            <div class="page-title">
                <h3>Все видоезаписи</h3>
            </div>

            <div class="visible-xs header-element-toggle">
                <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                        class="icon-insert-template"></i></a>
            </div>

            <div class="header-buttons">
                <div class="collapse" id="header-buttons">
                    <div class="well">
                        <div class="btn-group">

                            <a class="btn btn-primary"
                               href="index.php?do=content&action=editcontent"><i
                                    class="icon-pencil"></i>Редактировать
                            </a>

                            <a class="btn btn-danger"
                               href="javascript:checkSel('index.php?do=content&action=delete')"><i
                                    class="icon-remove"></i>Удалить
                            </a>

                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=content&action=active')"><i
                                    class="icon-eye"></i>Показать
                            </a>

                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=content&action=unactive')"><i
                                    class="icon-eye-blocked"></i>Скрыть
                            </a>


                        </div>


                    </div>
                </div>
            </div>

        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body">

                <div class="col-md-12">
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
                                <th>Дата</th>
                                <th>Название</th>
                                <th>Показ</th>


                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0;
                            foreach ($media->getMovieList() as $movie) {
                                $count++;
                                ?>
                                <tr>
                                    <th scope="row"><input type="checkbox" name='item[]' id='item[]' class="styled"
                                                           value="<?php echo $movie->id; ?>"/></th>
                                    <!--                        <th scope="row">--><?php //echo $count; ?><!--</th>-->
                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_movie&item_id=<?php echo $movie->id; ?>"><?php echo $movie->id; ?></a>
                                    </td>
                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_movie&item_id=<?php echo $movie->id; ?>"><?php echo $movie->pubdate; ?></a>
                                    </td>
                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_movie&item_id=<?php echo $movie->id; ?>"><?php echo $movie->title; ?></a>
                                    </td>


                                    <td>
                                        <a href="index.php?do=components&action=config&id=<?php echo Core::$id; ?>&opt=edit_movie&item_id=<?php echo $movie->id; ?>">
                                            <div class="state iradio_line-blue checked">
                                                <div
                                                    class="icheck_line-icon"><?php echo $movie->published ? "<i class='icon-checkmark-circle2'></i>" : "<i class='icon-cancel-circle2'></i>"; ?></div>
                                            </div>
                                        </a>
                                    </td>


                                    <td>
                                        <div class="table-controls">
                                            <a href="#" class="btn btn-default btn-icon btn-xs tip" title="Удалить"><i
                                                    class="icon-remove"></i></a>
                                            <a href="#" class="btn btn-default btn-icon btn-xs tip"
                                               title="Редактировать"><i
                                                    class="icon-pencil"></i></a>
                                            <a href="#" class="btn btn-default btn-icon btn-xs tip" title="Просмотр"><i
                                                    class="icon-search2"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="clearfix"></div>

                    </div>

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
            </div>
        </div>
    </form>
    <?php
}

if ($opt == 'add_movie' || $opt == 'edit_movie') {

    $item_id = Core::$get['item_id'];
    $row = Registry::get("Core")->getRowById("video", $item_id);
    ?>
    <div class="page-header">
        <div class="page-title">
            <h3>Добавить аудиозапись
                <small>Музыка</small>
            </h3>
        </div>

        <div class="visible-xs header-element-toggle">
            <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-info-buttons"><i
                    class="icon-info"></i></a>
        </div>
        <div class="header-buttons">
            <div class="collapse" id="header-buttons">
                <div class="well">
                    <button type="button" class="btn btn-info">Save</button>
                    <button type="button" class="btn btn-danger">Cancel</button>
                </div>
            </div>
        </div>
        <div class="panel-heading">

        </div>

    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-horizontal" method="post"
                  action="index.php?do=components&action=config&id=<?php echo Core::$id; ?>" role="form" name="addform"
                  id="addform" enctype="multipart/form-data">
                <input type="hidden" name="opt"
                       value="<?php if ($opt == 'add_movie') { ?>submit_movie<?php } else { ?>update_movie<?php } ?>"/>
                <input type="hidden" name="item_id" value="<?php if ($opt == 'edit_movie') {
                    echo $row->id;
                } else {
                    echo "0";
                } ?>"/>

                <div class="block">
                    <!--                    <h6 class="heading-hr"><i class="icon-checkmark-circle"></i> Form validation</h6>-->

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Название видеозаписи: <span
                                class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" id="title"
                                   value="<?php echo $row->title ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Публиковать видеозапись?: <span class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="1" class="styled" name="published"
                                        <?php getChecked($row->published, 1) ?>/>
                                    Да
                                </label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" value="0" class="styled"
                                           name="published" <?php getChecked($row->published, 0) ?>/>
                                    Нет
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Загрузить видеозапись: <span class="mandatory">Допустимый на сервере размер загружаемого файла - <?php echo ini_get('upload_max_filesize'); ?></span></label>

                        <div class="col-sm-10">

                            <div id="my_tabView">
                                <div class="my_aTab" style="display:none;">
                                    <textarea type="text" class="form-control" id="fileurl" name="fileurl"
                                              style="font-size:12px;">
                                        <?php echo $row->fileurl; ?>
                                        </textarea>
                                </div>
                                <div class="my_aTab" style="display:none;">
                                    <input type="file" id="video" name="video" class="styled" size="40"
                                           style="font-size:12px;">
                                </div>
                            </div>


                                                        <input type="file" class="form-control" name="video" id="video">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Картинка превью: <span class="mandatory">Допустимый на сервере размер загружаемого файла - <?php echo ini_get('upload_max_filesize'); ?></span></label>

                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="image" id="image"/>
                        </div>
                    </div>
                    <?php if ($row->provider == 'local') { ?>
                        <?php if ($row->file) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                </label>
                                <script type='text/javascript' src='/components/media/js/swfobject.js'></script>
                                <div id='mediaspace'>This text will be replaced</div>
                                <div class="col-sm-10">

                                    <script type='text/javascript'>
                                        var so = new SWFObject('/components/media/player/player.swf', 'ply', '470', '320', '9', '#000000');
                                        so.addParam('allowfullscreen', 'true');
                                        so.addParam('allowscriptaccess', 'always');
                                        so.addParam('wmode', 'opaque');
                                        so.addVariable('file', '/uploads/media/movie/<?php echo $row->file ?>');
                                        so.addVariable('image', '/uploads/media/movie/big/<?php echo $row->img ?>');
                                        so.write('mediaspace');
                                    </script>

                                </div>
                            </div>
                        <?php }
                    } else {
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                            </label>

                            <div class="col-sm-10">

                                <?php echo $row->fileurl; ?>

                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Описание видеозаписи: <span
                                class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <?php Core::loadEditor("description", "99%", "300", $row->description); ?>

                        </div>
                    </div>

                    <div class="form-actions text-right">
                        <input type="submit" value="Добавить видеозапись" class="btn btn-primary">
                    </div>

                </div>
            </form>

        </div>
    </div>

    <?php
}


if ($opt == 'add_mp3' || $opt == 'edit_mp3') {

    $item_id = Core::$get['item_id'];
    $row = $media->getMus($item_id);
    ?>



        <div class="page-header">


            <div class="visible-xs header-element-toggle">
                <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-info-buttons"><i
                        class="icon-info"></i></a>
            </div>
            <div class="header-buttons">
                <div class="collapse">

                    <input type="submit" class="btn btn-info"
                           value="<?php if ($opt == 'add_mp3') { ?>Добавить аудиозапись<?php } else { ?> Сохранить аудиозапись <?php } ?>"/>
                    <button type="button" class="btn btn-danger" onclick="javascript:window.history.back();">Отмена
                    </button>

                </div>
            </div>
            <div class="panel-heading">

            </div>

        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div id="download1">
                    <p>Загрузить песню на сервер | <a href="#"
                                                      onClick="document.getElementById('download1').style.display='none';document.getElementById('download2').style.display='';return false;">Указать
                            прямую ссылку на песню.</a></p>
                    <HR>
            <form class="form-horizontal" method="post"
          action="index.php?do=components&action=config&id=<?php echo Core::$id; ?>" name="addform"
          id="addform" enctype="multipart/form-data">
                <input type="hidden" name="opt"
                       value="<?php if ($opt == 'add_mp3') { ?>submit_mp3<?php } else { ?>update_mp3<?php } ?>"/>
                <?php if ($opt == 'edit_mp3') {?>
                <input type="hidden" name="item_id" value="<?php echo $row->id;?>"/>
                <?php }  ?>
                <div class="block">

<!--                    <h6 class="heading-hr"><i class="icon-checkmark-circle"></i> Добавить аудиозапись</h6>-->

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Название Песни: <span
                                class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name"
                                   value="<?php echo $row->name ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Исполнитель: <span
                                class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="singer" id="singer"
                                   value="<?php echo $row->singer ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Альбом: <span
                                class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="album" id="album"
                                   value="<?php echo $row->album ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Жанр: <span
                                class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <select name="genre_id" class="form-control">
                                <?php foreach ($media->getGenre() as $genre) {
                                    if ($row->genre_id==$genre['id']){
                                        $sel="selected='selected'";
                                    }else{
                                        $sel="";
                                    }
                                    ?>
                                    <option <?php echo $sel;?> value="<?php echo $genre['id']; ?>"><?php echo $genre['genre_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Публиковать аудиозапись?: <span class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="1" class="styled" name="published"
                                        <?php getChecked($row->published, 1) ?>/>
                                    Да
                                </label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" value="0" class="styled"
                                           name="published" <?php getChecked($row->published, 0) ?>/>
                                    Нет
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">

                                <label class="col-sm-2 control-label">Выберите Песню (mp3): <span class="mandatory">Допустимый на сервере размер загружаемого файла - <?php echo ini_get('upload_max_filesize'); ?></span></label>

                                <div class="col-sm-10">
                                    <input type="file" name="upmp3" class="styled" id="upmp3"/>
                                     <input type="hidden" name="upload" id="upload" value="1"/>
                                    </div>
               </div>
                    <?php if ($row->music_url) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                            </label>
                            <script type='text/javascript' src='/components/media/js/swfobject.js'></script>
                            <div id='mediaspace<?php echo $row->id;?>'>This text will be replaced</div>
                            <div class="col-sm-10">
                                <script type='text/javascript'>
                                    var so<?php echo $row->id;?> = new SWFObject('/components/media/player/player.swf', 'ply', '470', '24', '9', '#000000');
                                    so<?php echo $row->id;?>.addParam('allowfullscreen', 'true');
                                    so<?php echo $row->id;?>.addParam('allowscriptaccess', 'always');
                                    so<?php echo $row->id;?>.addParam('wmode', 'opaque');
                                    so<?php echo $row->id;?>.addVariable('file', '<?php echo @$row->music_url ?>');
                                    so<?php echo $row->id;?>.addVariable('duration', '33');
                                    so<?php echo $row->id;?>.write('mediaspace<?php echo $row->id;?>');
                                </script>
                            </div>
                        </div>
                    <?php } ?>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Описание аудиозаписи: <span
                                class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <?php Core::loadEditor("description", "99%", "300", $row->description); ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Теги: <span
                                class="mandatory">*</span></label>

                        <div class="col-sm-10">
                            <input type="text" id="tags" name="tags" class="tags" data-role="tagsinput"
                                   style="font-size:12px;" value="<?php echo $row->tags; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right">
                    <input type="submit" value="Добавить" class="btn btn-primary">
                </div>
</form>
                    </div>

                <div id="download2" style="display:none">


                    <p><a href="#"
                          onClick="document.getElementById('download2').style.display='none';document.getElementById('download1').style.display='none';document.getElementById('download1').style.display='';return false;">Загрузить
                            песню на сервер</a> | Указать прямую ссылку на песню.</p>
                    <HR>
                    <form class="form-horizontal" method="post"
                          action="index.php?do=components&action=config&id=<?php echo Core::$id; ?>" name="addform2"
                          id="addform2" enctype="multipart/form-data">
                        <input type="hidden" name="opt"
                               value="<?php if ($opt == 'add_mp3') { ?>submit_mp3<?php } else { ?>update_mp3<?php } ?>"/>
                        <?php if ($opt == 'edit_mp3') {?>
                            <input type="hidden" name="item_id" value="<?php echo $row->id;?>"/>
                        <?php }  ?>
                        <div class="block">

                            <!--                    <h6 class="heading-hr"><i class="icon-checkmark-circle"></i> Добавить аудиозапись</h6>-->

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Название Песни: <span
                                        class="mandatory">*</span></label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="<?php echo $row->name ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Исполнитель: <span
                                        class="mandatory">*</span></label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="singer" id="singer"
                                           value="<?php echo $row->singer ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Альбом: <span
                                        class="mandatory">*</span></label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="album" id="album"
                                           value="<?php echo $row->album ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Жанр: <span
                                        class="mandatory">*</span></label>

                                <div class="col-sm-10">
                                    <select name="genre_id" class="form-control">
                                        <?php foreach ($media->getGenre() as $genre) { ?>
                                            <?php
                                            if ($row->genre_id==$genre['id']){
                                                $sel="selected='selected'";
                                            }else{
                                                $sel="";
                                            }
                                            ?>
                                            <option <?php echo $sel;?> value="<?php echo $genre['id']; ?>"><?php echo $genre['genre_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Публиковать аудиозапись?: <span class="mandatory">*</span></label>

                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" value="1" class="styled" name="published"
                                                <?php getChecked($row->published, 1) ?>/>
                                            Да
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" value="0" class="styled"
                                                   name="published" <?php getChecked($row->published, 0) ?>/>
                                            Нет
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-2 control-label">Выберите Песню (mp3): <span class="mandatory">Допустимый на сервере размер загружаемого файла - <?php echo ini_get('upload_max_filesize'); ?></span></label>

                                <div class="col-sm-10">
                                    <input type="text" name="upmp3" class="form-control" id="upmp3" value="<?php echo $row->music_url?>"/>
                                </div>


                            </div>
                            <?php if ($row->music_url) { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                    </label>
                                    <script type='text/javascript' src='/components/media/js/swfobject.js'></script>
                                    <div id='mediaspace<?php echo $row->id;?>2'>This text will be replaced</div>
                                    <div class="col-sm-10">
                                        <script type='text/javascript'>
                                            var so<?php echo $row->id;?>2 = new SWFObject('/components/media/player/player.swf', 'ply', '470', '24', '9', '#000000');
                                            so<?php echo $row->id;?>2.addParam('allowfullscreen', 'true');
                                            so<?php echo $row->id;?>2.addParam('allowscriptaccess', 'always');
                                            so<?php echo $row->id;?>2.addParam('wmode', 'opaque');
                                            so<?php echo $row->id;?>2.addVariable('file', '<?php echo @$row->music_url ?>');
                                            so<?php echo $row->id;?>2.addVariable('duration', '33');
                                            so<?php echo $row->id;?>2.write('mediaspace<?php echo $row->id;?>2');
                                        </script>
                                    </div>
                                </div>
                            <?php } ?>


                            <div class="form-group">
                                <label class="col-sm-2 control-label">Описание аудиозаписи: <span
                                        class="mandatory">*</span></label>

                                <div class="col-sm-10">
                                    <?php Core::loadEditor("description2", "99%", "300", $row->description); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Теги: <span
                                        class="mandatory">*</span></label>

                                <div class="col-sm-10">
                                    <input type="text" id="tags" name="tags" class="tags" data-role="tagsinput"
                                           style="font-size:12px;" value="<?php echo $row->tags; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right">
                            <input type="submit" value="Отправить" class="btn btn-primary">
                        </div>
                    </form>


                </div>

            </div>

        </div>

    <?php
}

if ($opt == 'config') {

    if (@$msg) {
        echo '<p class="success">' . $msg . '</p>';
    }

    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title">Медиагалерия</h6></div>

        <div class="panel-body">
            <form class="form-horizontal"
                  action="index.php?do=components&amp;action=config&amp;id=<?php echo Core::$id; ?>" method="post"
                  enctype="multipart/form-data" name="optform">

                <div class="form-group">
                    <label class="col-sm-2 control-label">Ширина окна видеоплеера:</label>

                    <div class="col-sm-1"><input class="form-control" name="pweight" type="text"
                                                 value="<?php echo @$cfg['pweight'] ?>"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Высота окна видеоплеера:</label>

                    <div class="col-sm-1"><input class="form-control" name="phight" type="text" size="4"
                                                 value="<?php echo @$cfg['phight'] ?>"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ширина аудио проигрывателя:</label>

                    <div class="col-sm-1"><input class="form-control" name="waudio" type="text" size="4"
                                                 value="<?php echo @$cfg['waudio'] ?>"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ширина маленькой копии картинки превью:</label>

                    <div class="col-sm-1"><input class="form-control" name="minimgw" type="text" size="4"
                                                 value="<?php echo @$cfg['minimgw'] ?>"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Количество аудиозаписей на странице:<br/><span
                            class="hinttext">Если ноль, то выводятся все аудиозаписи на странице</span>
                    </label>

                    <div class="col-sm-1"><input name="aperpage" class="form-control" type="text" size="4"
                                                 value="<?php echo @$cfg['aperpage'] ?>"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Количество видеозаписей на странице:<br/><span
                            class="hinttext">Если ноль, то выводятся все аудиозаписи на странице</span>
                    </label>

                    <div class="col-sm-1"><input name="vperpage" class="form-control" type="text" size="4"
                                                 value="<?php echo @$cfg['vperpage'] ?>"/></div>
                </div>

                <div class="form-actions">
                    <input name="opt" type="hidden" value="saveconfig"/>
                    <input name="save" class="btn btn-success" type="submit" id="save" value="Сохранить"/>
                </div>
            </form>
        </div>
    </div>
    <?php
}

if ($opt == 'delete_mp3') {
    $items = Core::$post['item'];
    if (is_array($items)) {
        foreach ($items as $item) {
            Registry::get("DataBase")->delete('audio', 'id=' . $item);

        }
    } else {
        Registry::get("DataBase")->delete('audio', 'id=' . Core::$get['item_id']);
    }

    header("location:index.php?do=components&action=config&id=" . Core::$id . "&opt=list_mp3");
}

if ($opt == 'show_mp3') {
    $items = Core::$post['item'];
    if (is_array($items)) {
        foreach ($items as $item) {
            $data = array('published' => '1');
            Registry::get("DataBase")->update('audio', $data, 'id=' . $item);
        }
    } else {
        $data = array('published' => '1');
        Registry::get("DataBase")->update('audio', $data, 'id=' . Core::$get['item_id']);
    }
    header("location:index.php?do=components&action=config&id=" . Core::$id . "&opt=list_mp3");
}

if ($opt == 'hide_mp3') {
    $items = Core::$post['item'];


    if (is_array($items)) {
        foreach ($items as $item) {

            $data = array('published' => '0');
            Registry::get("DataBase")->update('audio', $data, 'id=' . $item);
        }
    } else {
        $data = array('published' => '0');
        Registry::get("DataBase")->update('audio', $data, 'id=' . Core::$get['item_id']);
    }
    header("location:index.php?do=components&action=config&id=" . Core::$id . "&opt=list_mp3");
}

if ($opt == 'submit_movie') {

    $item_id = Core::$post['item_id'];
    if (!empty($_REQUEST['tags'])) {
        $tags = $_REQUEST['tags'];
    } else {
        $tags = Registry::get("Content")->generateTag(Core::$post['title']);
    }

    $data = array(
        'title' => Core::$post['title'],
        'description' => Core::$post['description'],
        'seolink' => Core::doSEO(Core::$post['title']),
        'tags' => $tags,
        'pubdate' => 'now()',
        'published' => Core::$post['published']);

    $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/media/';
    $ext = substr($_FILES['video']['name'], strrpos($_FILES['video']['name'], '.') + 1);

    $realimg = $_FILES['image']['name'];
    $realflv = $_FILES['video']['name'];
    $flv = md5($realflv . time()) . '.' . $ext;
    $img = md5($realimg . time()) . '.jpg';
    $uploadimg = $uploaddir . $realimg;
    $uploadflv = $uploaddir . $realflv;

    $uploadphoto = $uploaddir . $img;
    $uploadvideo = $uploaddir . '/movie/' . $flv;

    $uploadthumb = $uploaddir . '/movie/small/' . $img;
    $uploadthumb2 = $uploaddir . '/movie/big/' . $img;


    if (@move_uploaded_file($_FILES['image']['tmp_name'], $uploadphoto)) {
        Registry::get("Uploads")->img_resize($uploadphoto, $uploadthumb, 100, 100, true);
        Registry::get("Uploads")->img_resize($uploadphoto, $uploadthumb2, 470, 320, false);

        $data['img'] = $img;

    }

    if (@move_uploaded_file($_FILES['video']['tmp_name'], $uploadvideo)) {
        $data['file'] = $flv;
        $data['provider'] = 'local';
    }
    if (!empty(Core::$post['fileurl'])) {
        $data['provider'] = 'link';
        $data['fileurl'] = Core::$post['fileurl'];

    }

    $media->proccessMovie($data);
    $itemid = Registry::get("DataBase")->insertid();
    Registry::get("Content")->insertTags('video', Registry::get("Content")->generateTag($tags), $itemid);

    if (Registry::get("DataBase")->affected()) {
        echo "Ok";

    } else {
        echo 'Ошибка загрузки данных!';
    }
}

if ($opt == 'update_movie') {
    $item_id = Core::$post['item_id'];
    if (!empty($_REQUEST['tags'])) {
        $tags = $_REQUEST['tags'];
    } else {
        $tags = Registry::get("Content")->generateTag(Core::$post['title']);
    }
    $data = array(
        'title' => Core::$post['title'],
        'description' => Core::$post['description'],
        'seolink' => Core::doSEO(Core::$post['title']),
        'tags' => $tags,
        'pubdate' => 'now()',
        'published' => Core::$post['published']);

    $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/media/';
    $ext = substr($_FILES['video']['name'], strrpos($_FILES['video']['name'], '.') + 1);

    $realimg = $_FILES['image']['name'];
    $realflv = $_FILES['video']['name'];
    $flv = md5($realflv . time()) . '.' . $ext;
    $img = md5($realimg . time()) . '.jpg';
    $uploadimg = $uploaddir . $realimg;
    $uploadflv = $uploaddir . $realflv;

    $uploadphoto = $uploaddir . $img;
    $uploadvideo = $uploaddir . '/movie/' . $flv;

    $uploadthumb = $uploaddir . '/movie/small/' . $img;
    $uploadthumb2 = $uploaddir . '/movie/big/' . $img;

    if (!empty($_FILES['video']['tmp_name'])) {
        if ($file = Registry::get("DataBase")->getFieldById("file", "video", "id=" . $item_id)) {
            @unlink($uploaddir . '/movie/' . $file);
        }
        @move_uploaded_file($_FILES['video']['tmp_name'], $uploadvideo);
        $data['file'] = $flv;
    }

    if (!empty($_FILES['image']['tmp_name'])) {
        if ($image = Registry::get("DataBase")->getFieldById("img", "video", "id=" . $item_id)) {
            @unlink($uploaddir . '/movie/small/' . $image);
            @unlink($uploaddir . '/movie/big/' . $image);
        }


        if (@move_uploaded_file($_FILES['image']['tmp_name'], $uploadphoto)) {
            Registry::get("Uploads")->img_resize($uploadphoto, $uploadthumb, 100, 100, true);
            Registry::get("Uploads")->img_resize($uploadphoto, $uploadthumb2, 470, 320, false);
            $data['img'] = $img;
        }
    }


    if (!empty(Core::$post['fileurl'])) {

        $data['provider'] = 'link';
        $data['fileurl'] = Core::$post['fileurl'];

    }

    $media->proccessUpdateMovie($data);

    Registry::get("Content")->insertTags('video', Registry::get("Content")->generateTag($tags), $item_id);
    if (Registry::get("DataBase")->affected()) {
        echo "Ok";
        print_r(($data));

    } else {
        echo 'Ошибка загрузки данных!';
        print_r(($data));
    }
}


if ($opt == 'submit_mp3') {

//    $item_id = Core::$post['item_id'];
    $name = Core::$post['name'];
    $published = Core::$post['published'];
    $singer = Core::$post['singer'];
    $album = Core::$post['album'];
    $genre_id = Core::$post['genre_id'];
    $upload = Core::$post['upload'];
    $user_id = Registry::get("Users")->userid;

    if (!empty(Core::$post['tags'])) {
        $tags = Core::$post['tags'];
    } else {
        $tags = Registry::get("Content")->generateTag($name);
    }

    if (!$album) {
        $album = 'Без Альбома';
    }

    $album_id = $media->getAlbumId($album);
    if (!$album_id) {
        $album_id = $media->addAlbum($album, $user_id);
    }

    $singer_id = $media->getSingerId($singer);
    if (!$singer_id) {
        $singer_id = $media->addSinger($singer, $user_id);
    }


    if ($upload) {
        $realfile = $_FILES['upmp3']['name'];
        $path_parts = pathinfo($realfile);
        $ext = strtolower($path_parts['extension']);
        if ($ext == 'mp3'){
            $upload_dir = PATH .'/uploads/media/mp3/';
            @mkdir($upload_dir);

            $filename = md5($realfile .'-'. $user_id .'-'. time()) .'.mp3';
            $uploadfile = $upload_dir .'/'. $filename;
            $source = $_FILES['upmp3']['tmp_name'];
            $errorCode = $_FILES['upmp3']['error'];

            if (Uploads::moveUploadedFile($source, $uploadfile, $errorCode)){
                $music_url = '/uploads/media/mp3/'. $filename;
            }else{
                echo 'Ошибка Загрузки Файла!';

            }
        }else{
           echo 'Недопустимый Формат Файла!';

        }

        $media->addMusic(array('user_id'=>$user_id,
                                           'name'=>$name,
                                           'album_id'=>$album_id,
                                           'singer_id' => $singer_id,
                                           'genre_id' => $genre_id,
                                           'music_url' => $music_url,
                                           'pubdate' => date('Y-m-d H:i:s'),
                                           'tags'=>$tags,
                                           'published'=>$published));


    } else {
        $music_url = Core::$post['upmp3'];
        $media->addMusic(array('user_id'=>$user_id,
            'name'=>$name,
            'album_id'=>$album_id,
            'singer_id' => $singer_id,
            'genre_id' => $genre_id,
            'music_url' => $music_url,
            'pubdate' => date('Y-m-d H:i:s'),
            'description' =>$description2 ,
            'tags'=>$tags,
            'published'=>$published));

        $itemid = Registry::get("DataBase")->insertid();
        Registry::get("Content")->insertTags('audio', Registry::get("Content")->generateTag($tags), $itemid);

    }

    $itemid = Registry::get("DataBase")->insertid();
    Registry::get("Content")->insertTags('audio', Registry::get("Content")->generateTag($tags), $itemid);

    if ((Registry::get("DataBase")->affected())){
        echo 'Ok';
//        cmsCore::addSessionMessage('Песня успешно добавлена!', 'success');
    }else{
        echo 'Ощибка добавления песни!';
    }
//

//
//    $data = array('title' => Core::$post['title'],
//        'description' => Core::$post['description'],
//        'seolink' => Core::doSEO(Core::$post['title']),
//        'pubdate' => 'now()',
//        'published' => Core::$post['published']);
//
//
//    if (!empty(Core::$files['mp3']['name'])) {
//        $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/media/mp3/';
//        $realmp3 = Core::$files['mp3']['name'];
//        $mp3 = md5($realmp3 . time()) . '.mp3';
//        $uploadaudio = $uploaddir . $mp3;
//        @move_uploaded_file(Core::$files['mp3']['tmp_name'], $uploadaudio);
//        $data['file'] = $mp3;
//        $data['provider'] = 'local';
//    }
//
//    if (!empty(Core::$post['fileurl'])){
//          $data['fileurl']=Core::$post['fileurl'];
//          $data['provider'] = 'link';
//    }
//
//
//    $media->proccessMp3($data);
//    if (Registry::get("DataBase")->affected()) {
//        echo "Ok";
//
//    } else {
//        echo 'Ошибка загрузки данных!';
//    }
}

if ($opt == 'update_mp3') {

    $item_id = Core::$post['item_id'];
    $name = Core::$post['name'];
    $published = Core::$post['published'];
    $description = Core::$post['description'];
    $description2 = Core::$post['description2'];
    $singer = Core::$post['singer'];
    $album = Core::$post['album'];
    $genre_id = Core::$post['genre_id'];
    $upload = Core::$post['upload'];
    $user_id = Registry::get("Users")->userid;

    if (!empty(Core::$post['tags'])) {
        $tags = Core::$post['tags'];
    } else {
        $tags = Registry::get("Content")->generateTag($name);
    }

    if (!$album) {
        $album = 'Без Альбома';
    }

    $album_id = $media->getAlbumId($album);
    if (!$album_id) {
        $album_id = $media->addAlbum($album, $user_id);
    }

    $singer_id = $media->getSingerId($singer);
    if (!$singer_id) {
        $singer_id = $media->addSinger($singer, $user_id);
    }


    if ($upload) {
        $realfile = $_FILES['upmp3']['name'];
        $path_parts = pathinfo($realfile);
        $ext = strtolower($path_parts['extension']);

        if ($ext == 'mp3'){
            $upload_dir = PATH .'/uploads/media/mp3/';
            @mkdir($upload_dir);
            $old_file=Registry::get("DataBase")->getFieldById("music_url","audio","id=".$item_id);
            if ($old_file){
                unlink(PATH.$old_file);
            }

            $filename = md5($realfile .'-'. $user_id .'-'. time()) .'.mp3';
            $uploadfile = $upload_dir .'/'. $filename;
            $source = $_FILES['upmp3']['tmp_name'];
            $errorCode = $_FILES['upmp3']['error'];

            if (Uploads::moveUploadedFile($source, $uploadfile, $errorCode)){
                $music_url = '/uploads/media/mp3/'. $filename;
            }else{
                echo 'Ошибка Загрузки Файла!';

            }
        }else{
            echo 'Недопустимый Формат Файла!';

        }

        $media->updateMusic(array('user_id'=>$user_id,
            'name'=>$name,
            'album_id'=>$album_id,
            'singer_id' => $singer_id,
            'genre_id' => $genre_id,
            'music_url' => $music_url,
            'pubdate' => date('Y-m-d H:i:s'),
            'tags'=>$tags,
            'published'=>$published),$item_id);


    } else {
        $music_url = Core::$post['upmp3'];
        $media->updateMusic(array('user_id'=>$user_id,
            'name'=>$name,
            'album_id'=>$album_id,
            'singer_id' => $singer_id,
            'genre_id' => $genre_id,
            'music_url' => $music_url,
            'pubdate' => date('Y-m-d H:i:s'),
            'tags'=>$tags,
            'published'=>$published),$item_id);
    }
    Registry::get("Content")->insertTags('audio', Registry::get("Content")->generateTag($tags), $item_id);
    if ((Registry::get("DataBase")->affected())){
        echo 'Ok';
//        cmsCore::addSessionMessage('Песня успешно добавлена!', 'success');
    }else{
        echo 'Ощибка добавления песни!';
    }
//    $data = array('title' => Core::$post['title'],
//        'description' => Core::$post['description'],
//        'seolink' => Core::doSEO(Core::$post['title']),
//        'pubdate' => 'now()',
//        'published' => Core::$post['published']);
//
//
//    if (!empty(Core::$files['mp3']['name'])) {
//        $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/media/mp3/';
//        $realmp3 = Core::$files['mp3']['name'];
//        $mp3 = md5($realmp3 . time()) . '.mp3';
//        $uploadaudio = $uploaddir . $mp3;
//        if ($file = Registry::get("DataBase")->getFieldById("file", "audio", "id=" . $item_id)) {
//            @unlink($uploaddir . $file);
//        }
//        @move_uploaded_file(Core::$files['mp3']['tmp_name'], $uploadaudio);
//        $data['file'] = $mp3;
//    }
//
//    $media->proccessUpdateMp3($data);
//    if (Registry::get("DataBase")->affected()) {
//        echo "Ok";
//    } else {
//        echo 'Ошибка загрузки данных!';
//    }


}

if ($opt == 'saveconfig') {

    if (@$msg) {
        echo '<p class="success">' . $msg . '</p>';
    }
    $cfg = array();
    $cfg['pweight'] = Core::$post['pweight'] ? Core::$post['pweight'] : 470;
    $cfg['waudio'] = Core::$post['waudio'] ? Core::$post['waudio'] : 400;
    $cfg['phight'] = Core::$post['phight'] ? Core::$post['phight'] : 320;
    $cfg['minimgw'] = Core::$post['minimgw'] ? Core::$post['minimgw'] : 100;
    $cfg['aperpage'] = Core::$post['aperpage'] ? Core::$post['aperpage'] : 10;
    $cfg['vperpage'] = Core::$post['vperpage'] ? Core::$post['vperpage'] : 10;
    $conf = serialize($cfg);
    print_r($cfg);
    Registry::get("DataBase")->query("update components set config='{$conf}' where id=" . Core::$id);

    $msg = 'Настройки сохранены.';
    $opt = 'config';
    header('location:?view=components&do=config&id=' . $_REQUEST['id'] . '&opt=config');
}


?>

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


