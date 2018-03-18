<?php
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

$menu = Registry::get("content");
$lang = Registry::get("Lang");
$config = Registry::get("Config");
$db = Registry::get("DataBase");
$content = Registry::get("Content");
$user = Registry::get("Users");
$pager = Registry::get("Paginator");
$imgconfog = array(
    'readdesc' => 0,
    'is_url_cyrillic' => 0,
    'rating' => 1,
    'perpage' => 15,
    'pt_show' => 1,
    'pt_disp' => 1,
    'pt_hide' => 1,
    'autokeys' => 1,
    'img_small_w' => 100,
    'img_big_w' => 200,
    'img_sqr' => 1,
    'img_users' => 1,
    'hide_root' => 0,
    'watermark' => 1
);
?>

<div class="page-header">
    <div class="page-title">
        <h3>Разделы и статьи
            <small>управления</small>
        </h3>
    </div>

</div>
<div class="clearfix"></div>

<?php
if ($core->action == 'submit') {

    $dataconfig = serialize(array(
        'showdesc' => (int)Core::$post['showdesc'],
        'showdate' => (int)Core::$post['showdate'],
        'showcomm' => (int)Core::$post['showcomm'],
        'showtags' => (int)Core::$post['showtags'],
        'showrss' => (int)Core::$post['showrss'],

    ));


    if (Core::$post['parent_id']!=1){
    $rowcat=$db->first("select * from categories c where id=".Core::$post['parent_id']);
    }

    $data = array('parent_id' => Core::$post['parent_id'],
        'cname' => Core::$post['cname'],
        'active' => Core::$post['active'],
//        'slug' => Core::doSEO(Core::$post['cname']),
        'url' => Core::doSEO(Core::$post['cname']),
        'cdescription' => Core::$post['cdescription'],
        'orderby' => Core::$post['orderby'],
        'orderto' => Core::$post['orderto'],
        'maxcols' => Core::$post['maxcols'],
        'lang' => Core::$post['lang'],
        'mgroup_id' => Core::$post['modgrp_id'],
        'tpl' => Core::$post['tpl'],
        'config' => $dataconfig);

    if ($rowcat) {
        $data['slug'] = $rowcat->slug.'/'.Core::doSEO(Core::$post['cname']);
    }else{
        $data['slug'] =Core::doSEO(Core::$post['cname']);
    }

    $content->proccessCats($data);
    header('Location:index.php?do=content&action=cats');
}

if ($core->action == 'delete') {
    $items = Core::$post['item'];
    if (is_array($items)) {
        foreach ($items as $item) {
            $db->delete("content", "id=" . $item);
        }
    } else {
        $db->delete("content", "id=" . Core::$id);
    }
    header("Location:index.php?do=content");
}

if ($core->action == 'deletecats') {
    $items = Core::$post['item'];
    if (is_array($items)) {
        foreach ($items as $item) {
            $db->delete("categories", "id=" . $item);
            $db->delete("content", "category_id=" . $item);
        }
    } else {
        $db->delete("categories", "id=" . Core::$id);
        $db->delete("content", "category_id=" . Core::$id);
    }
    header("Location:index.php?do=content&action=cats");
}

if ($core->action == 'active') {
    $items = Core::$post['item'];

    if (is_array($items)) {
        foreach ($items as $item) {
            $activity = Registry::get("DataBase")->getValueById('active', 'content', $item);
            if ($activity == 0) {
                $data = array('active' => '1');
                $db->update("content", $data, "id=" . $item);
            } else {
                $data = array('active' => '0');
                $db->update("content", $data, "id=" . $item);
            }

        }
    } else {
        $activity = Registry::get("DataBase")->getValueById('active', 'content', Core::$id);
        if ($activity == 0) {
            $data = array('active' => '1');
            $db->update("content", $data, "id=" . Core::$id);
        } else {
            $data = array('active' => '0');
            $db->update("content", $data, "id=" . Core::$id);
        }
    }

    header("Location:index.php?do=content");
}

if ($core->action == 'feature') {
    $items = Core::$post['item'];

    if (is_array($items)) {
        foreach ($items as $item) {
            $featured = Registry::get("DataBase")->getValueById('featured', 'content', $item);
            if ($featured == 0) {
                $data = array('featured' => '1');
                $db->update("content", $data, "id=" . $item);
            } else {
                $data = array('featured' => '0');
                $db->update("content", $data, "id=" . $item);
            }
        }
    } else {
        $featured = Registry::get("DataBase")->getValueById('featured', 'content', Core::$id);
        if ($featured == 0) {
            $data = array('featured' => '1');
            $db->update("content", $data, "id=" . Core::$id);
        } else {
            $data = array('featured' => '0');
            $db->update("content", $data, "id=" . Core::$id);
        }

    }

    $opt = Core::$get['opt'];
    if ($opt == 1) {
        $data = array('featured' => '0');
        $db->update("content", $data, "id=" . Core::$id);
    } else {
        $data = array('featured' => '1');
        $db->update("content", $data, "id=" . Core::$id);
    }

    header("Location:index.php?do=content");
}


if ($core->action == 'saveitem') {
    $attribs = array(
        'showtitle' => Core::$post['showtitle'],
        'showdate' => Core::$post['showdate'],
        'showcomm' => Core::$post['showcomm'],
        'showlatest' => Core::$post['showlatest'],
        'canrate' => Core::$post['canrate'],
    );

    $date = explode('-', substr(Core::$post['create_at'], 0, 10));
    $time = explode(':', substr(Core::$post['create_at'], 11, 10));
    $pubdate = $date[2] . '-' . $date[1] . '-' . $date[0] . ' ' . $time[0] . ':' . $time[1] . ':' . $time[2];

    $dateend = explode('.', substr(Core::$post['enddate'], 0, 10));
    $timeend = explode(':', substr(Core::$post['enddate'], 11, 10));
    $enddate = $dateend[2] . '-' . $dateend[1] . '-' . $dateend[0] . ' ' . $timeend[0] . ':' . $timeend[1] . ':' . $timeend[2];

    $articles = array();
    $articles['title'] = sanitize(Core::$post['title']);
    $articles['category_id'] = Core::$post['categories_id'];
    $articles['introtext'] = Registry::get("DataBase")->escape(Core::$post['introtext']);
    $articles['fulltext'] = Registry::get("DataBase")->escape(Core::$post['content']);
    $articles['user_id'] = $user->uid;
    $articles['created_at'] = Core::$post['create_at'];
    $articles['end_at'] = Core::$post['enddate'];
    $articles['is_end'] = Core::$post['is_end'];
    $articles['meta_keywords'] = $content->generateTag($articles['title']);
    $articles['meta_desc'] = Core::$post['introtext'];
    $articles['pagetitle'] = Core::$post['pagetitle'];
    $articles['seo'] = $content->getSeoLink($articles);
    $articles['url'] = empty(Core::$post['url']) ? Core::$post['url'] : Core::doSEO(Core::$post['title']);
    $articles['jscode'] = Core::$post['jscode'];
    $articles['lang'] = Core::$post['lang'];
    $articles['tpl'] = Core::$post['tpl'];

    if (!empty(Core::$files['picture']['name'])) {
        $realfile = Core::$files['picture']['name'];
        $path_parts = pathinfo($realfile);
        $ext = strtolower($path_parts['extension']);
        $realfile = substr($realfile, 0, strrpos($realfile, '.'));
        $filename = md5($realfile) . '.' . $ext;
        //echo $filename;
        $articles['images'] =$filename;
    }
    $articles['featured'] = Core::$post['featured'];
    $articles['attribs'] = serialize($attribs);
    $articles['active'] = Core::$post['active'];
    if (!empty(Core::$post['tags'])) {
        $articles['tags'] = Core::$post['tags'];
    } else {
        $articles['tags'] = Core::$post['title'];
    }

    $itemid=$content->proccessContItem($articles);
//    $itemid = $db->insertid();
    $content->insertTags('article', $content->generateTag($articles['tags']), $itemid);
    $articles = Registry::get("Core")->getCallEvent("ADD_ARTICLE_DONE", $articles);

    $inUploadPhoto = Registry::get("Uploads");
    $inUploadPhoto->upload_path = PATH . '/images/content/';
    $inUploadPhoto->small_size_w = 100;
    $inUploadPhoto->medium_size_w = 200;
    $inUploadPhoto->thumbsqr = 1;
    $inUploadPhoto->is_watermark = 0;
    $inUploadPhoto->input_name = 'picture';
    $inUploadPhoto->filename = Core::$files['picture']['name'];
    $inUploadPhoto->upload();
    header("Location:index.php?do=content");
}

if ($core->action == 'updateitem') {

    $attribs = array(
        'showtitle' => Core::$post['showtitle'],
        'showdate' => Core::$post['showdate'],
        'showcomm' => Core::$post['showcomm'],
        'showlatest' => Core::$post['showlatest'],
        'canrate' => Core::$post['canrate'],

    );
    $date = explode('-', substr(Core::$post['create_at'], 0, 10));
    $time = explode(':', substr(Core::$post['create_at'], 11, 10));
    $pubdate = $date[2] . '-' . $date[1] . '-' . $date[0] . ' ' . $time[0] . ':' . $time[1] . ':' . $time[2];

    $dateend = explode('.', substr($_REQUEST['enddate'], 0, 10));
    $timeend = explode(':', substr($_REQUEST['enddate'], 11, 10));
    $enddate = $dateend[2] . '-' . $dateend[1] . '-' . $dateend[0] . ' ' . $timeend[0] . ':' . $timeend[1] . ':' . $timeend[2];

    $articles = array();
    $articles['title'] = sanitize(Core::$post['title']);
    $articles['category_id'] = Core::$post['categories_id'];
    $articles['introtext'] = Registry::get("DataBase")->escape(Core::$post['introtext']);
    $articles['fulltext'] = Registry::get("DataBase")->escape(Core::$post['content']);
    $articles['user_id'] = $user->uid;
    $articles['created_at'] = Core::$post['create_at'];
    $articles['update_at'] = 'now()';
    $articles['end_at'] = Core::$post['enddate'];
    $articles['is_end'] = Core::$post['is_end'];
    $articles['meta_keywords'] = $content->generateTag($articles['title']);
    $articles['meta_desc'] = Core::$post['introtext'];
    $articles['pagetitle'] = Core::$post['pagetitle'];
    $articles['seo'] = $content->getSeoLink($articles);
    $articles['url'] = empty(Core::$post['url']) ? Core::$post['url'] : Core::doSEO(Core::$post['title']);
    $articles['jscode'] = Core::$post['jscode'];
    $articles['lang'] = Core::$post['lang'];
    $articles['tpl'] = Core::$post['tpl'];

    if (!empty(Core::$files['picture']['name'])) {
        $realfile = Core::$files['picture']['name'];
        $path_parts = pathinfo($realfile);
        $ext = strtolower($path_parts['extension']);
        $realfile = substr($realfile, 0, strrpos($realfile, '.'));
        $filename = md5($realfile) . '.' . $ext;
//        echo $filename;
//        $articles['images'] = !empty(Core::$files['picture']['name']) ? $filename : $db->getValueById('images', 'content', Core::$id);
        $articles['images'] = $filename;
    } else{
        $articles['images']=$db->getValueById('images', 'content', Core::$id);
    }

    $articles['featured'] = Core::$post['featured'];
    $articles['attribs'] = serialize($attribs);
    $articles['active'] = Core::$post['active'];
    if (!empty(Core::$post['tags'])) {
        $articles['tags'] = Core::$post['tags'];
    } else {
        $articles['tags'] = Core::$post['title'];
    }


    $category_id = Registry::get("Content")->getArticle($core->id)->id;
    Registry::get("DataBase")->delete("categories_bind", "item_id='{$category_id}'");


    $content->proccessContItem($articles);
    $itemid = $core->id;
    $content->insertTags('article', $content->generateTag($articles['tags']), $itemid);
    $row = $content->getArticle($itemid);
    if (Core::$post['delete_image']) {
        @unlink(PATH . "/images/content/$row->images");
        @unlink(PATH . "/images/content/small/$row->images");
        @unlink(PATH . "/images/content/medium/$row->images");
    }

    if (Core::$files['picture']['name'] != '') {
        $inUploadPhoto = Registry::get("Uploads");
        $inUploadPhoto->upload_path = PATH . '/images/content/';
        $inUploadPhoto->small_size_w = 100;
        $inUploadPhoto->medium_size_w = 200;
        $inUploadPhoto->thumbsqr = 1;
        $inUploadPhoto->is_watermark = 0;
        $inUploadPhoto->input_name = 'picture';
        $inUploadPhoto->filename = Core::$files['picture']['name'];
        $inUploadPhoto->upload();
    }

    header("Location:index.php?do=content");
}


if ($core->action == 'cats') {
    ?>
    <form method="post" name="selform">
        <div class="page-header">
            <div class="page-title">
                <h3>Все раздель</h3>
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
                               href="index.php?do=content&action=addcats"><i
                                    class="icon-box-add"></i>Создать раздел
                            </a>
                            <a class="btn btn-primary"
                               href="index.php?do=content&action=editcontent"><i
                                    class="icon-pencil"></i>Редактировать
                            </a>

                            <a class="btn btn-danger"
                               href="javascript:checkSel('index.php?do=content&action=deletecats')"><i
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
                <!--                    <h6 class="panel-title"><i class="icon-checkbox-partial"></i> Table with checkboxes-->
                <!--                    </h6>-->
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
                                <th>Категория</th>
                                <th>Алиас</th>
                                <th>Актив</th>
                                <th>Язык</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $content->getSortCategoryListTable(1, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", 0); ?>
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

if ($core->action == 'view') {
    if (Core::$get['catid']) {$catid=Core::$get['catid'];}

    $posrow = $content->getArticleList($catid);
    ?>
    <form method="post" name="selform">
        <div class="page-header">
            <div class="page-title">
                <h3>Все страницы</h3>
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
                               href="index.php?do=content&action=addcontent"><i
                                    class="icon-link"></i>Создать статью
                            </a>
                            <a class="btn btn-primary"
                               href="index.php?do=content&action=addcats"><i
                                    class="icon-box-add"></i>Создать раздел
                            </a>

                            <a class="btn btn-primary"
                               href="javascript:checkSel('index.php?do=content&action=copycontent');"><i
                                    class="icon-copy"></i>Копия
                            </a>
                            <a class="btn btn-primary"
                               href="javascript:checkSel('index.php?do=content&action=editcontent');"><i
                                    class="icon-pencil"></i>Редактировать
                            </a>

                            <a class="btn btn-danger"
                               href="javascript:checkSel('index.php?do=content&action=delete')"><i
                                    class="icon-remove"></i>Удалить
                            </a>

                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=content&action=active')"><i
                                    class="icon-eye"></i>Показать/Скрыть
                            </a>

                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=content&action=feature')"><i
                                    class="icon-star5"></i>Избранные
                            </a>

                        </div>


                    </div>
                </div>
            </div>

        </div>


        <div class="panel panel-default">

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-link"></i> Категория</h6>
                            </div>
                            <div class="form-actions">
                                <div style="margin: 15px;">
                                <a href="index.php?do=content&action=addcats" style="font-weight:bold"><span class="icon-plus"></span> Добавить раздел</a>
                                    </div>
                                </div>
                            <hr/>
                            <div class="form-actions">
                                    <div style="margin: 15px;">
                                        <div>
                                                <a href="index.php?do=content&orderby=pubdate&orderto=desc&only_hidden=1" style="font-weight:bold">На модерации</a>
                                            </div>
                                        <div><a href="index.php?do=content" style="font-weight:bold">Все страницы</a></div>
                                    </div>

                            </div>



                            <div id="menusort" class="ui-sortable">
                            <?php Registry::get("Content")->getSortedCategoryList(1); ?>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-link"></i> Все материалов</h6>
                            </div>
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
                                    <th width="400">Название</th>
                                    <th>Автор</th>
                                    <th width="100">Дата</th>
                                    <th width="100">Дата окончание</th>
                                    <th>Показ</th>
                                    <th>Избранные</th>
                                    <th>Язык</th>
                                    <th>Хит</th>
                                    <th width="100">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 0;
                                foreach ($posrow as $article) {
                                    $count++;
                                    ?>
                                    <tr>
                                        <th scope="row"><input type="checkbox" name='item[]' id='item[]' class="styled"
                                                               value="<?php echo $article->id; ?>"/></th>
                                        <!--                        <th scope="row">-->
                                        <?php //echo $count; ?><!--</th>-->
                                        <td>
                                            <a href="index.php?do=content&action=editcontent&id=<?php echo $article->id; ?>"><?php echo $article->id; ?></a>
                                        </td>
                                        <td>
                                            <a href="index.php?do=content&action=editcontent&id=<?php echo $article->id; ?>"><?php echo $article->title; ?></a>
                                        </td>
                                        <td>
                                            <a href="index.php?do=content&action=editcat&id=<?php echo $article->id; ?>"><?php echo $article->author; ?></a>
                                        </td>
                                        <td><?php echo $article->created_at; ?></td>
                                        <td><?php echo $article->end_at; ?></td>

                                        <td align="center">
                                            <a href="index.php?do=content&action=active&id=<?php echo $article->id; ?>&opt=<?php echo $article->active; ?>">
                                                <div class="state iradio_line-blue checked">
                                                    <div
                                                        class="icheck_line-icon"><?php echo $article->active ? "<i class='icon-checkmark-circle2'></i>" : "<i class='icon-cancel-circle2'></i>"; ?></div>
                                                </div>
                                            </a>
                                        </td>
                                        <td align="center">
                                            <a href="index.php?do=content&action=feature&id=<?php echo $article->id; ?>&opt=<?php echo $article->featured; ?>">
                                                <div class="state iradio_line-blue checked">
                                                    <div
                                                        class="icheck_line-icon"><?php echo $article->featured ? "<i class='icon-star6'></i>" : "<i class='icon-star4'></i>"; ?></div>
                                                </div>
                                            </a>
                                        </td>
                                        <td><?php echo $article->lang; ?></td>
                                        <td><?php echo $article->hits; ?></td>
                                        <td>
                                            <div class="table-controls">
                                                <a href="index.php?do=content&action=deletet&id=<?php echo $article->id; ?>"
                                                   class="btn btn-default btn-icon btn-xs tip" title="Удалить"><i
                                                        class="icon-remove"></i></a>
                                                <a href="index.php?do=content&action=editcontent&id=<?php echo $article->id; ?>"
                                                   class="btn btn-default btn-icon btn-xs tip"
                                                   title="Редактировать"><i
                                                        class="icon-pencil"></i></a>
                                                <!--                                            <a href="#" class="btn btn-default btn-icon btn-xs tip" title="Просмотр"><i-->
                                                <!--                                                    class="icon-search2"></i></a>-->
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
                                    <!--                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to 10 of-->
                                    <!--                        57 entries-->
                                    <!--                    </div>-->
                                </div>
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
if ($core->action == 'addcats' || $core->action == 'editcats') {
    $row = $db->first("select * from categories where id=" . $core->id);
    $param = unserialize($row->config);
    ?>
    <form id="general_validate" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="page-header">
            <div class="page-title">
                <h3>Создать раздел </h3>
            </div>

            <div class="visible-xs header-element-toggle">
                <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                        class="icon-insert-template"></i></a>
            </div>

            <div class="header-buttons">
                <div class="collapse" id="header-buttons">
                    <div class="well">
                        <button type="submit" class="btn btn-info">Сохранить</button>
                        <button type="submit" onclick="javascript:window.history.back()" class="btn btn-danger">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading"><h6 class="panel-title"><i class="icon-bubble4"></i> Добавить раздел</h6></div>
            <div class="panel-body">

                <input type="hidden" name="action" value="submit"/>

                <div class="col-md-7">
                    <div class="form-group">
                        <label class="form-label" for="cname">Название раздела</label>
                        <input type="text" class="form-control" id="cname" name="cname"
                               value="<?php echo $row->cname; ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="formfield2">Родительский раздел</label>
                        <select name="parent_id" id="s2example-21" rows="10" cols="10" class="form-control"
                                multiple="multiple">
                            <?php
                            Registry::get("Content")->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", (int)$row->parent_id ? $row->parent_id : 1);
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="formfield8">Описание раздела</label>

                        <div class="controls">
                            <?php Core::loadEditor("cdescription", "99%", "99%", $row->description); ?></td>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="tabbable page-tabs">
                                <ul class="nav nav-tabs primary">
                                    <li class="active">
                                        <a href="#home-1" data-toggle="tab">
                                            <i class="fa fa-home"></i> Публикация
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#profile-1" data-toggle="tab">
                                            <i class="fa fa-user"></i> Редакторы
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#messages-1" data-toggle="tab">
                                            <i class="fa fa-envelope"></i> Фото
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#settings-1" data-toggle="tab">
                                            <i class="fa fa-cog"></i> Доступ
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="home-1">
                                        <div class="form-group">

                                            <label class="checkbox-inline checkbox-success">
                                                <input type="checkbox" class="styled" name="active" value="1"
                                                       id="active" <?php if ($core->action == 'edit') {
                                                    getChecked($row->active, 1);
                                                } else {
                                                    getChecked(1, 1);
                                                } ?>>
                                                Публиковать раздел
                                            </label>

                                        </div>


                                        <div class="form-group">
                                            <label class="control-label" for="formfield5">Язык</label>
                                            <select name="lang" id="lang" class="form-control m-bot15"
                                                    style="width:100%">
                                                <?php
                                                $lang->getLangList($row->lang);
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="formfield5">Сортировка статей</label>
                                            <select name="orderby" id="orderby" class="form-control m-bot15"
                                                    style="width:100%">
                                                <option value="pubdate"
                                                        <?php if ($row->orderby == 'pubdate') { ?>selected="selected" <?php } ?> >
                                                    По дате
                                                </option>
                                                <option value="title"
                                                        <?php if ($row->orderby == 'title') { ?>selected="selected" <?php } ?>>
                                                    По заголовку
                                                </option>
                                                <option value="ordering"
                                                        <?php if ($row->orderby == 'ordering') { ?>selected="selected" <?php } ?>>
                                                    По порядку
                                                </option>
                                                <option value="hits"
                                                        <?php if ($row->orderby == 'hits') { ?>selected="selected" <?php } ?>>
                                                    По просмотрам
                                                </option>
                                            </select>
                                            <select name="orderto" id="orderto" class="form-control m-bot15"
                                                    style="width:100%">
                                                <option value="ASC"
                                                        <?php if ($row->orderto == 'ASC') { ?>selected="selected" <?php } ?>>
                                                    По возрастанию
                                                </option>
                                                <option value="DESC"
                                                        <?php if ($row->orderto == 'DESC') { ?>selected="selected" <?php } ?>>
                                                    По убыванию
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="maxcols">Шаблон раздела</label>
                                            <div class="controls">
                                                <?php
                                                $tpls = Core::loadComponentTemplate();
                                                ?>
                                                <select name="tpl" id="tpl"  class="form-control">
                                                    <?php
                                                    foreach($tpls as $tpl){
                                                        $selected = ($row->tpl==$tpl || (!$row->tpl && $tpl=='com_content_view.php' )) ? 'selected="selected"' : '';
                                                        echo '<option value="'.$tpl.'" '.$selected.'>'.$tpl.'</option>';
                                                    }
                                                    ?>
                                                </select>
<!--                                                <input type="text" class="form-control" id="tpl" name="tpl"-->
<!--                                                       value="--><?php //echo $row->tpl ? $row->tpl : 'com_content_view.php'; ?><!--">-->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="maxcols">Число колонок для вывода статей</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="maxcols" name="maxcols"
                                                       value="<?php echo $row->maxcols ? $row->maxcols : 1; ?>">
                                            </div>
                                        </div>

                                        <label class="form-label">
                                            Параметры публикации
                                        </label>

                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <label class="checkbox-inline checkbox-success">
                                                        <input tabindex="1" type="checkbox" id="showdesc" class="styled"
                                                               value="1"
                                                               name="showdesc" <?php if ($core->action == 'edit') {
                                                            getChecked($row->active, 1);
                                                        } else {
                                                            getChecked(1, 1);
                                                        } ?>>
                                                        Показывать
                                                        анонсы статей</label><br/><br/>

                                                    <label class="checkbox-inline checkbox-success" for="showdate">
                                                        <input tabindex="2" type="checkbox" id="showdate"
                                                               name="showdate"
                                                               class="styled"
                                                               value="1" <?php if ($core->action == 'edit') {
                                                            getChecked($row->active, 1);
                                                        } else {
                                                            getChecked(1, 1);
                                                        } ?>
                                                               class="skin-square-green">
                                                        Показывать даты статей</label><br/><br/>

                                                    <label class="checkbox-inline checkbox-success" for="showcomm">
                                                        <input tabindex="3" type="checkbox" id="showcomm"
                                                               name="showcomm"
                                                               value="1" <?php if ($core->action == 'edit') {
                                                            getChecked($row->active, 1);
                                                        } else {
                                                            getChecked(1, 1);
                                                        } ?>
                                                               class="styled">
                                                        Показывать число комментариев</label><br/><br/>

                                                    <label class="checkbox-inline checkbox-success" for="showtags">
                                                        <input tabindex="4" type="checkbox" id="showtags"
                                                               name="showtags"
                                                               value="1" <?php if ($core->action == 'edit') {
                                                            getChecked($row->active, 1);
                                                        } else {
                                                            getChecked(1, 1);
                                                        } ?>
                                                               class="styled">
                                                        Показывать
                                                        теги статей</label><br/><br/>

                                                    <label class="checkbox-inline checkbox-success"
                                                           for="square-checkbox-2">
                                                        <input tabindex="5" type="checkbox" id="square-checkbox-2"
                                                               name="showrss"
                                                               value="1" <?php if ($core->action == 'edit') {
                                                            getChecked($row->active, 1);
                                                        } else {
                                                            getChecked(1, 1);
                                                        } ?>
                                                               class="styled">
                                                        Показывать
                                                        иконку RSS</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="profile-1">


                                        <div class="form-group">
                                            <label class="form-label" for="modgrp_id">Редакторы раздела</label>
                                            <select name="modgrp_id" id="modgrp_id" class="form-control m-bot15"
                                                    style="width:100%">

                                                <?php
                                                Registry::get("Users")->getGroups(0);
                                                ?>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="messages-1">

                                        <p>Don't use data attributes from multiple plugins on the same element. For
                                            example,
                                            a button cannot both have a tooltip and toggle a modal. To accomplish this,
                                            use
                                            a wrapping element.</p>

                                    </div>

                                    <div class="tab-pane fade" id="settings-1">

                                        <div class="checkbox form-group ">
                                            <label for="is_public">
                                                <input type="checkbox" checked name="is_access" id="is_public"
                                                       class="skin-square-green"> Общие доступ
                                            </label><br/>
                                      <span class="desc">

                              Если отмечено, категория будет видна всем посетителям. Снимите галочку, чтобы вручную выбрать разрешенные группы пользователей.
                                         </span>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="modgrp_id">Показывать группам:</label><br/>
                                            <span class="desc">Можно выбрать несколько, удерживая CTRL.</span>

                                            <select style="width: 99%" name="showfor[]" id="showin" size="6"
                                                    class="form-control m-bot15" multiple="multiple">
                                                <?php Registry::get("Users")->getGroups(0);

                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="pull-right ">
                        <button type="submit"
                                    class="btn btn-success"><?php if ($core->action == 'addcats') { ?>Создать раздел<?php } else { ?>Сохранить раздел <?php } ?></button>
                        <button type="button" class="btn">Отмена</button>
                    </div>
                </div>


            </div>

    </form>
    <?php
}

if ($core->action == 'copycontent') {
    $items = Core::$post['item'];
    if (is_array($items)) {
        foreach ($items as $item) {
            $row = $content->getArticle(Core::$id);
            $data = array('');
        }
    }
}

if ($core->action == 'addcontent' || $core->action == 'editcontent') {
    $items = Core::$post['item'];
    if (is_array($items)) {
        foreach ($items as $item) {
            $row = $content->getArticle($item);
            $rcfg = unserialize($row->attribs);
        }
    } else {
        $row = $content->getArticle(Core::$id);
        $rcfg = unserialize($row->attribs);

    }
    ?>
    <form id="general_validate" method="post" id="addform" name="addform" enctype="multipart/form-data">
        <div class="page-header">
            <div class="page-title">
                <h3>Создать раздел </h3>
            </div>
            <div class="visible-xs header-element-toggle">
                <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                        class="icon-insert-template"></i></a>
            </div>

            <div class="header-buttons">
                <div class="collapse" id="header-buttons">
                    <div class="well">
                        <button type="submit" class="btn btn-info" onclick="document.addform.submit();">Сохранить</button>
                        <button type="submit" onclick="javascript:window:history.back();" class="btn btn-danger">
                            Отмана
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-md-8">

                <input type="hidden" name="action"
                       value="<?php if ($core->action == 'addcontent') { ?>saveitem<?php } else { ?>updateitem<?php } ?>"/>
                <?php if ($row->id) { ?>
                    <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                <?php } ?>
                <div class="panel panel-default">
                    <!--                <div class="panel-heading"><h6 class="panel-title"><i class="icon-bubble4"></i> Survey</h6></div>-->
                    <div class="panel-body">

                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="form-label" for="title">Название статьи</label>

                                <div class="controls">
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="<?php echo sanitize($row->title); ?>">
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="created_at">Шаблонь</label>

                                <div class="input-group">
                                    <?php
                                    $tpls = Core::loadComponentTemplate();
                                    ?>
                                    <select name="tpl" id="tpl"  class="form-control">
                                        <?php
                                        foreach($tpls as $tpl){
                                            $selected = ($row->tpl==$tpl || (!$row->tpl && $tpl=='com_content_read.php' )) ? 'selected="selected"' : '';
                                            echo '<option value="'.$tpl.'" '.$selected.'>'.$tpl.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div class="form-group">
                            <label class="form-label" for="introtext">Анонс статьи (не обязательно)</label>

                            <div class="controls">
                                <?php Core::loadEditor("introtext", "99%", "300", $row->introtext); ?>
                            </div>
                        </div>
                        <?php Registry::get("Core")->insertPanel(); ?>
                        <div class="form-group">
                            <label class="form-label" for="content">Полный текст статьи</label>

                            <div class="controls">

                                <?php Core::loadEditor("content", "99%", "400", $row->fulltext); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="jscode">JavaScript Код</label>

                            <div class="controls">
                            <textarea class="form-control" cols="5" id="jscode"
                                      name="jscode"><?php echo $row->jscode; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="tagsinput-1">Теги статьи</label>

                            <div class="controls">
                                <input type="text" class="tags" id="tags2" data-role="tagsinput"
                                       name="tags"
                                       value="<?php echo $content->getTagLine('article', (int)$row->id, false); ?>">
                            </div>
                        </div>


                        <div class="form-actions text-center">
                            <button type="submit"
                                    class="btn btn-success"><?php if ($core->action == 'addcontent') { ?>Создать материал<?php } else { ?>Сохранить материал<?php } ?></button>
                            <button type="button" class="btn">Отмена</button>

                        </div>

                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <!--            <div class="panel-heading"><h6 class="panel-title"><i class="icon-paragraph-justify"></i> Survey results</h6></div>-->
                    <div class="panel-body">
                        <div class="tabbable page-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#home-3" data-toggle="tab">
                                        Публикация
                                    </a>
                                </li>
                                <li>
                                    <a href="#photo" data-toggle="tab">
                                        Фото
                                    </a>
                                </li>
                                <li>
                                    <a href="#profile-3" data-toggle="tab">
                                        Срок
                                    </a>
                                </li>
                                <li>
                                    <a href="#messages-3" data-toggle="tab">
                                        SEO
                                    </a>
                                </li>


                                <li>
                                    <a href="#settings-3" data-toggle="tab">
                                        Доступ
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home-3">
                                    <div class="form-group">
                                        <label class="form-label" for="lang">Дата публикации</label>
                                        <input type="text" id="create_at" class="datetimepicker form-control"
                                               placeholder="Default datepicker"
                                               name="create_at"

                                               value="<?php if (!$row->created_at) {
                                                   echo date('Y-m-d H:i:s');
//                                               H:i:s
                                               } else {
                                                   echo $row->created_at;
                                               } ?>"
                                        />


                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="lang">Язык</label>

                                        <select id="s2example-5" class="required select-full" name="lang">

                                            <?php
                                            $lang->getLangList($row->lang);
                                            ?>
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <ul class="list-unstyled">
                                            <li>
                                                <input tabindex="5" type="checkbox" id="active" value="1"
                                                       class="styled"
                                                       class="skin-square-green" <?php if (($row->active) || ($core->action == 'addcontent')) { ?> checked="checked" <?php } ?>
                                                       name="active">
                                                <label class="icheck-label form-label" for="active">Публиковать
                                                    статью</label>
                                            </li>
                                            <li>
                                                <input tabindex="5" type="checkbox" id="featured" class="styled"
                                                       value="1" <?php if ($row->featured) { ?> checked="checked" <?php } ?>
                                                       class="skin-square-green" name="featured">

                                                <label class="form-label"
                                                       for="featured">Избранные</label>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="form-group" id="grp">
                                        <label class="form-label" for="categories_id">Категория</label>
                                        <?php
                                        if ($core->action == 'edit') {
                                            $bind_sql = "SELECT * FROM content_bind WHERE item_id = " . $row->id;
                                            $bind_res = Registry::get("DataBase")->query($bind_sql);
                                            $bind = array();

                                            while ($r = Registry::get("DataBase")->fetch($bind_res)) {
                                                $bind[] = $r->item_id;
                                            }
                                        }


                                        ?>

                                        <div
                                            style="height:300px;overflow: auto;border: solid 1px #999; padding:5px 10px; background: #FFF;">
                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                                   align="center">
                                                <tr>
                                                    <td colspan="2" height="25"><strong>Раздел сайта</strong></td>

                                                </tr>
                                                <?php Registry::get("Content")->getCategoryHtmlList(1, 0, $row->id, "&nbsp;", 1); ?>
                                            </table>

                                            <!--                                            <select multiple="multiple" size="10" style="width: 99%;height: 200px;"-->
                                            <!--                                                    id="categories_id" name="categories_id"-->
                                            <!--                                                    class="form-control">-->
                                            <!--                                                --><?php //Registry::get("Content")->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", $row->category_id ? $row->category_id : 1);
                                            ?>
                                            <!--                                            </select>-->
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="url">URL страницы</label>
                                        <span class="desc">Если не указан,генерируется из заголовка</span>

                                        <div class="controls">
                                            <input type="text" class="form-control" name="url"
                                                   id="url" <?php echo $row->url; ?>/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="user_id">Автор статьи</label>

                                        <div class="input-group primary">
                                            <select id="s2example-1" name="user_id" class="required select-full">
                                                <?php Users::getUsers($user->uid); ?>
                                            </select>
                                    <span class="input-group-addon" id="basic-addon1"><span class="arrow"></span><i
                                            class="icon-user"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="picture">Фотография</label>

                                        <div class="controls">
                                            <?php
                                            if ($core->action=='editcontent'){
                                                if (file_exists(PATH.'/images/content/medium/'.$row->images)){
                                        ?>
                                        <div style="margin-top:3px;margin-bottom:3px;padding:10px;text-align:center">
                                            <img src="/images/content/medium/<?php echo $row->images; ?>" border="0" />
                                        </div>
                                        <input type="checkbox" class="styled" id="delete_image" name="delete_image" value="1" /></td>
                                                <label for="delete_image">Удалить фото</label>

                                        <?php
                                                }
                                            }
                                            ?>
                                            <input type="file" class="styled" id="picture" name="picture">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="imgfile">Дополнительные фотографии</label>

                                        <div class="controls">
                                            <input type="hidden" id="default-id" value="2">
                                            <table name="fotos">
                                                <tr>
                                                    <th>
                                                        <input name="imgfile[]" type="file" class="form-control"
                                                               id="imgfile"
                                                               style="width:100%"/>
                                                        <a href="javascript:void()" onclick="addInput()"><b>+</b>Добавить
                                                            поле</a>
                                                    </th>
                                                </tr>
                                            </table>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="formfield11">Параметры публикации</label>

                                        <div class="controls">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <input tabindex="5" type="checkbox" id="showtitle" value="1"
                                                           name="showtitle"
                                                           class="styled"
                                                           name="showtitle" <?php if ($rcfg['showtitle'] || $core->action == 'addcontent') { ?> checked="checked" <?php } ?> />
                                                    <label class="icheck-label form-label" for="showtitle">Показывать
                                                        названия</label>
                                                </li>
                                                <li>
                                                    <input tabindex="5" type="checkbox" id="showdate"
                                                           name="showdate" value="1"
                                                           class="styled" <?php if ($rcfg['showdate'] || $core->action == 'addcontent') { ?> checked="checked" <?php } ?>>
                                                    <label class="icheck-label form-label" for="showdate">Показывать
                                                        дата</label>
                                                </li>
                                                <li>
                                                    <input tabindex="5" type="checkbox" id="showlatest"
                                                           name="showlatest"
                                                           value="1"
                                                           class="styled" <?php if ($rcfg['showlatest'] || $core->action == 'addcontent') { ?> checked="checked" <?php } ?> />
                                                    <label class="icheck-label form-label" for="showlatest">Показывать
                                                        в "новых статьях"</label>
                                                </li>
                                                <li>
                                                    <input tabindex="5" type="checkbox" id="showcomm"
                                                           name="showcomm" value="1"
                                                           class="styled" <?php if ($rcfg['showcomm'] || $core->action == 'addcontent') { ?> checked="checked" <?php } ?> />
                                                    <label class="icheck-label form-label" for="showcomm">Разрешить
                                                        комментарии</label>
                                                </li>
                                                <li>
                                                    <input tabindex="5" type="checkbox" id="canrate" name="canrate"
                                                           value="1"
                                                           class="styled" <?php if ($rcfg['canrate'] || $core->action == 'addcontent') { ?> checked="checked" <?php } ?>>
                                                    <label class="icheck-label form-label" for="canrate">Разрешить
                                                        рейтинг</label>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile-3">

                                    <div class="form-group">
                                        <label class="form-label" for="title">Срок показа статьи</label>
                                        <select name="is_end" id="is_end" style="width:99%"
                                                class="form-control m-bot15"
                                                onchange="if($(this).val() == 1){ $('#final_time').show(); }else {$('#final_time').hide();}">
                                            <option value="0" <?php if (!$row->is_end) {
                                                echo 'selected="selected"';
                                            } ?> >Не ограничен
                                            </option>
                                            <option value="1" <?php if ($row->is_end) {
                                                echo 'selected="selected"';
                                            } ?> >По дату окончания
                                            </option>
                                        </select>

                                    </div>

                                    <div class="form-group" id="final_time" <?php if (!$row->is_end) {
                                        echo 'style="display: none"';
                                    } ?>>
                                        <label class="form-label" for="title">Дата окончания:</label>

                                        <div class="input-group">
                                            <input type="text" class="form-control" name="enddate" id="enddate"

                                                   value="<?php if ($row->end_at) {
                                                       echo $row->end_at;
                                                   } else {
                                                       echo date('Y-m-d H:i:s');
                                                   } ?>">

                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="messages-3">
                                    <div class="form-group">
                                        <label class="form-label" for="meta_desc">Заголовок страницы:</label><br/>
                                        <span class="desc">Если не указан, будет совпадать с названием</span>

                                        <div class="controls">
                                            <input class="form-control" name="pagetitle"
                                                   value="<?php echo $row->pagetitle; ?>"
                                                   id="pagetitle"/></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="meta_desc">Описание:</label>
                                        <span class="desc">Не более 250 символов</span>

                                        <div class="controls">
                                            <textarea class="form-control" name="meta_desc" cols="45" rows="5"
                                                      id="meta_desc"><?php echo $row->meta_desc; ?></textarea></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="meta_keys">Ключевые слова:</label>
                                        <span class="desc">Через запятую, 10-15 слов</span>

                                        <div class="controls">
                                            <textarea class="form-control" cols="5" id="meta_keys" name="meta_keys"
                                                      cols="45" rows="5"><?php echo $row->meta_keywords; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="settings-3">
                                    <div class="form-group">
                                        <label class="form-label" for="field-7">Общий доступ:</label>
                                        <span class="desc">Если отмечено, материал виден всем посетителям</span>

                                        <div class="controls">
                                            <input tabindex="5" type="checkbox" id="is_public" value="1"
                                                   name="is_public"
                                                   class="skin-square-green" checked="checked"
                                                   onclick="$('.showform').slideToggle('fast');"/>
                                            <label class="icheck-label form-label" for="square-checkbox-2"
                                                   onclick="$('.showform').slideToggle('fast');">Разрешить
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group showform">
                                        <label class="form-label" for="showfor">Показывать группам:</label>

                                        <div class="controls">
                                            <select multiple="multiple" id="showfor" name="showfor[]" size="6"
                                                    class="form-control m-bot15">
                                                <?php Users::getGroups(); ?>
                                            </select>
                                        </div>
                                    </div>


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


?>
<script type="text/javascript">
    $.timepicker.regional['ru'] = {
        timeOnlyTitle: 'Выберите время',
        timeText: 'Время',
        hourText: 'Часы',
        minuteText: 'Минуты',
        secondText: 'Секунды',
        millisecText: 'Миллисекунды',
        timezoneText: 'Часовой пояс',
        currentText: 'Сейчас',
        closeText: 'Закрыть',
        timeFormat: 'HH:mm',
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        isRTL: false
    };
    $.timepicker.setDefaults($.timepicker.regional['ru']);
</script>
