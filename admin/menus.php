<?php
/**
 * Created by PhpStorm.
 * User: IDeveloper
 * Date: 16.10.2015
 * Time: 21:45
 */
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

$menu = Registry::get("Menus");
$lang = Registry::get("Lang");
$config = Registry::get("Config");
$db = Registry::get("DataBase");
$pager = Registry::get("Paginator");


?>
<div class="page-header">
    <div class="page-title">
        <h3>Меню
            <small>управления</small>
        </h3>
    </div>

</div>
<div class="clearfix"></div>


<?php

if ($core->action == 'view') {
    ?>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="index.html">Главная</a></li>
            <li><a href="components.html">Меню</a></li>
            <!--            <li class="active">Header elements</li>-->
        </ul>
        <div class="visible-xs breadcrumb-toggle">
            <a class="btn btn-link btn-lg btn-icon" data-toggle="collapse" data-target="#breadcrumb-buttons"><i
                    class="icon-menu2"></i></a>
        </div>

        <ul class="breadcrumb-buttons collapse" id="breadcrumb-buttons">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-search3"></i>
                    <span>Search</span> <b class="caret"></b></a>

                <div class="popup dropdown-menu dropdown-menu-right">
                    <div class="popup-header">
                        <a href="#" class="pull-left"><i class="icon-paragraph-justify"></i></a>
                        <span>Quick search</span>
                        <a href="#" class="pull-right"><i class="icon-new-tab"></i></a>
                    </div>
                    <form action="#" class="breadcrumb-search">
                        <input type="text" placeholder="Type and hit enter..." name="search"
                               class="form-control autocomplete">

                        <div class="row">
                            <div class="col-xs-6">
                                <label class="radio">
                                    <input type="radio" name="search-option" class="styled" checked="checked">
                                    Everywhere
                                </label>
                                <label class="radio">
                                    <input type="radio" name="search-option" class="styled">
                                    Invoices
                                </label>
                            </div>

                            <div class="col-xs-6">
                                <label class="radio">
                                    <input type="radio" name="search-option" class="styled">
                                    Users
                                </label>
                                <label class="radio">
                                    <input type="radio" name="search-option" class="styled">
                                    Orders
                                </label>
                            </div>
                        </div>

                        <input type="submit" class="btn btn-block btn-success" value="Search">
                    </form>
                </div>
            </li>


        </ul>
    </div>

    <!--<div class="callout callout-info fade in" style="margin: 0;">-->
    <!--    <button type="button" class="close" data-dismiss="alert">×</button>-->
    <!--    <h5>Page header elements</h5>-->
    <!--    <p>2 breadcrumb positions - on top and after page title. Also custom set of different elements which can be used on the right side of page title area - buttons, progress bars, graphs, info's etc.</p>-->
    <!--</div>-->
    <form method="post" action="index.php?do=menus" name="selform">
        <div class="page-header">
            <div class="page-title">
                <h3>Пункты меню
                    <small>управление</small>
                </h3>
            </div>
            <div class="visible-xs header-element-toggle">
                <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                        class="icon-insert-template"></i></a>
            </div>

            <div class="header-buttons">
                <div class="collapse" id="header-buttons">
                    <div class="well">
                        <div class="btn-group">

                            <a class="btn btn-primary" href="index.php?do=menus&action=addmenuitem"><i
                                    class="icon-link"></i>Добавить пункт меню</a>
                            <a class="btn btn-primary" href="index.php?do=menus&action=addmenu"><i
                                    class="icon-box-add"></i>Создать меню</a>
                            <a  class="btn btn-primary" value="Редактировать"
                                   href="javascript:checkSel('index.php?do=menus&action=editmenuitem')">Редактировать</a>

                            <a  class="btn btn-danger"
                                   href="javascript:checkSel('index.php?do=menus&action=delete')" >Удалить</a>
                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=menus&action=active')"><i
                                    class="icon-eye"></i>Показать / Скрыть
                            </a>
                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=menus&action=homepage')"><i
                                    class="icon-star5"></i>Главный
                            </a>

                        </div>


                    </div>
                </div>
            </div>

        </div>
        <div class="panel panel-default">
            <!--        <div class="panel-heading" ><h6 class="panel-title"><i class="icon-checkbox-partial"></i> Table with checkboxes</h6>-->


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
            <div class="table-responsive">
                <table class="table table-bordered table-check">
                    <thead>

                    <tr>
                        <th><a href="javascript:" onclick="javascript:invert()">#</a></th>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Показ</th>
                        <th>Главный</th>
                        <th width="150">Порядок</th>
                        <th>Адрес ссылки</th>
                        <th>Тип меню</th>
                        <th>Язык</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $menu->getSortMenuListTable(1, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", 0); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-footer">

            <?php echo $pager->display_pages(); ?>

        </div>
    </form>

    <?php

}

if ($core->action == 'addmenu' || $core->action == 'editmenu') {
    ?>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="index.html">Главная</a></li>
            <li><a href="components.html">Меню</a></li>
            <li><a href="components.html">Добавить меню</a></li>
            <!--            <li class="active">Header elements</li>-->
        </ul>
        <div class="visible-xs breadcrumb-toggle">
            <a class="btn btn-link btn-lg btn-icon" data-toggle="collapse" data-target="#breadcrumb-buttons"><i
                    class="icon-menu2"></i></a>
        </div>
    </div>
    <form class="form-horizontal" method="post" name="addform">
        <div class="page-header">
            <div class="page-title">
                <h3>Добавить меню
                    <small>управление</small>
                </h3>
            </div>
            <div class="visible-xs header-element-toggle">
                <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                        class="icon-insert-template"></i></a>
            </div>

            <div class="header-buttons">
                <div class="collapse" id="header-buttons">
                    <div class="well">
                        <div class="btn-group">
                            <button class="btn btn-danger" type="submit">Сохранить</button>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-primary">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <input type="hidden" value="submitmenu" name="action">

        <div class="panel panel-default">
            <!--            <div class="panel-heading"><h6 class="panel-title"><i class="icon-bubble4"></i> Form elements</h6></div>-->
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="title">Название модуля меню: </label>

                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="title" id="title">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" form="menutype">Язык: </label>

                    <div class="col-sm-6">
                        <select class="form-control" name="lang" id="lang">

                            <?php $lang->getLangList(); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" form="menutype">Меню для показа: </label>

                    <div class="col-sm-6">
                        <select class="form-control" name="menutype" id="menutype">

                            <?php $menu->getMenuType(); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Позиция показа: </label>

                    <div class="col-sm-6">
                        <select id="position" name="position" class="form-control">
                            <?php
                            $pos = Registry::get("Core")->loadModulePosition();
                            foreach ($pos as $key => $value) {
                                if (@$modRows['position'] == $value) {
                                    echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                } else {
                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Публиковать меню? </label>

                    <div class="col-sm-6">


                        <label class="radio-inline radio-success">
                            <input type="radio" name="active" class="styled" id="active"
                                   value="0" <?php getChecked(0, 1); ?>>
                            Нет
                        </label>
                        <label class="radio-inline radio-success">
                            <input type="radio" name="active" class="styled" id="active"
                                   value="1" <?php getChecked(1, 1); ?>>
                            Да
                        </label>

                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">CSS префикс: </label>

                    <div class="col-sm-6">
                        <input type="text" name="css_prefix" class="form-control"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Доступ: </label>

                    <div class="col-sm-10">
                        <label class="checkbox-inline checkbox-success">
                            <input type="checkbox" class="styled" id="is_public" name="is_public"
                                   value="1" <?php getChecked(1, 1); ?> onclick="if(document.addForm.is_public.checked){
		$('select#allow_group').prop('disabled', true);
	} else {
		$('select#allow_group').prop('disabled', false);
	}">
                            Общий доступ
                        </label>


                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Показывать группам
                    </label>

                    <div class="col-md-6">
                        <select multiple="multiple" class="form-control groups" disabled='disabled' id="allow_group"
                                name="allow_group[]"
                                title="Click to Select a City">

                            <?php Registry::get("Users")->getGroups(); ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        JAVASCRIPT код
                    </label>

                    <div class="col-sm-6">
                        <textarea rows="5" cols="5" class="form-control" name="jscode"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php
}

if ($core->action == 'addmenuitem' || $core->action == 'editmenuitem') {
    $items=Core::$post['item'];
    if (is_array($items)){
        foreach($items as $item){
            $row = $db->first("select * from menus where id=" . $item);
        }
    }else{
        $row = $db->first("select * from menus where id=" . $core->id);
    }
     ?>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="index.html">Главная</a></li>
            <li><a href="components.html">Меню</a></li>
            <li><a href="components.html">Добавить пункт меню</a></li>
            <!--            <li class="active">Header elements</li>-->
        </ul>
        <div class="visible-xs breadcrumb-toggle">
            <a class="btn btn-link btn-lg btn-icon" data-toggle="collapse" data-target="#breadcrumb-buttons"><i
                    class="icon-menu2"></i></a>
        </div>
    </div>
    <div class="page-header">
        <div class="page-title">
            <h3>Добавить пункт меню
                <small>управление</small>
            </h3>
        </div>
        <div class="visible-xs header-element-toggle">
            <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                    class="icon-insert-template"></i></a>
        </div>

        <div class="header-buttons">
            <div class="collapse" id="header-buttons">
                <div class="well">

                    <!--                    <div class="btn-group">-->
                    <!--                        <button class="btn btn-success" type="submit" onclick="document.location.href='index.php?do=menus&action=addMenuitemSubmit'"><i class="icon-plus"></i>Создать и сохранить</button>-->
                    <!--                    </div>-->

                    <div class="btn-group">
                        <input class="btn btn-danger" type="submit" value="Сохранить" onclick="document.formname.submit()"/>
                    </div>

                    <div class="btn-group">
                        <button class="btn btn-primary" type="submit" onclick="javascript:window.history.back();">
                            Отмена
                        </button>
                    </div>


                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <form method="post" name="formname">
            <input type="hidden" name="action"
                   value="<?php if ($core->action == 'addmenuitem') { ?>submit<?php } else { ?>update<?php } ?>">
            <?php if ($core->action == 'editmenuitem') { ?>
                <input type="hidden" name="id" value="<?php echo $row->id; ?>">
            <?php } ?>
            <div class="col-md-8">
                <div class="row">
                    <div class="panel panel-default">
                        <!--			                <div class="panel-heading"><h6 class="panel-title"><i class="icon-bubble4"></i> Survey</h6></div>-->
                        <div class="panel-body">

                            <div class="form-group">
                                <label for="title">Заголовок пункта меню:</label>
                                <input type="text" class="form-control" id="title" name="title"
                                       value="<?php echo $row->title; ?>">
                            </div>


                            <div class="form-group">
                                <label for="menutype">Меню: </label>
                                <select data-placeholder="Choose a Country..." class="required select-full"
                                        name="menutype"
                                        tabindex="2">
                                    <?php $menu->getMenuType($row->menutype); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="parent_id">Родительский пункт:</label>
                                <select class="form-control" multiple="multiple" name="parent_id"
                                        tabindex="2">
                                    <?php $menu->getMenuDropList(0, 0, "&#166;&nbsp;&nbsp;&nbsp;&nbsp;", $row->parent_id ? $row->parent_id : 1); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="lang">Язык: </label>
                                <select class="required select-full" name="lang"
                                        tabindex="2">
                                    <?php $lang->getLangList($row->lang); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="linktype">Действие пункта меню: </label>
                                <select name="mode" id="linktype" style="width:99%" class="required select-full"
                                        onchange="showMenuTarget()">
                                    <option value="link" <?php if (@$row->linktype == 'link') {
                                        echo 'selected="selected"';
                                    } ?>>Открыть ссылку
                                    </option>
                                    <option value="content" <?php if (@$row->linktype == 'content') {
                                        echo 'selected="selected"';
                                    } ?>>Открыть статью
                                    </option>
                                    <option value="category" <?php if (@$row->linktype == 'category') {
                                        echo 'selected="selected"';
                                    } ?>>Открыть раздел (список статей)
                                    </option>
                                    <option value="component" <?php if (@$row->linktype == 'component') {
                                        echo 'selected="selected"';
                                    } ?>>Открыть компонент
                                    </option>
                                    <option value="photoalbum" <?php if (@$row->linktype == 'photoalbum') {
                                        echo 'selected="selected"';
                                    } ?>>Открыть альбом фотогалереи
                                    </option>
                                    <option value="media" <?php if (@$row->linktype=='media') { echo 'selected'; }?>>
                                        Открыть медиагалерею</option>
                                </select>
                            </div>

                            <div id="t_link" class="form-group menu_target"
                                 style="display:<?php if ($row->linktype == 'link' || $row->linktype == 'ext' || !$row->linktype) {
                                     echo 'block';
                                 } else {
                                     echo 'none';
                                 } ?>">
                                <div>
                                    <label>Адрес ссылки <span class="hinttext">&mdash;
                                            для внешних ссылок не забывайте префикс <b>http://</b></span></label>
                                </div>
                                <div>
                                    <input name="link" type="text" id="link" size="50" class="form-control"
                                           style="width:99%" <?php if (@$row->linktype == 'link' || @$row->linktype == 'ext') {
                                        echo 'value="' . $row->link . '"';
                                    } ?>/>
                                </div>
                            </div>

                            <div id="t_content" class="form-group menu_target"
                                 style="display:<?php if ($row->linktype == 'content') {
                                     echo 'block';
                                 } else {
                                     echo 'none';
                                 } ?>">
                                <div>
                                    <strong>Выберите статью</strong>

                                </div>
                                <div>
                                    <select name="content" id="content" style="width:99%" class="required select-full">
                                        <?php
                                        echo Registry::get("Content")->getContentList($row->linkid);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div id="t_category" class="field menu_target"
                                 style="display:<?php if ($row->linktype == 'category') {
                                     echo 'block';
                                 } else {
                                     echo 'none';
                                 } ?>">
                                <div>
                                    <label>Выберите раздел</label>
                                </div>
                                <div>
                                    <select style="width:99%;" name="category" id="category"
                                            class="required select-full">
                                        <?php

                                        Registry::get("Content")->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", $row->linkid);

                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div id="t_component" class="field menu_target"
                                 style="display:<?php if ($row->linktype == 'component') {
                                     echo 'block';
                                 } else {
                                     echo 'none';
                                 } ?>">
                                <div>
                                    <label>Выберите компонент</label>
                                </div>
                                <div>
                                    <select style="width:99%;" name="component" id="component"
                                            class="required select-full">
                                        <?php

                                        Registry::get("Core")->getComponentDropList($row->linkid);

                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div id="t_media" class="menu_target" style="display:<?php if ($mod['linktype']=='media') { echo  'block'; } else { echo 'none'; } ?>">
                                <div>
                                    <strong>Выберите медиагалерею</strong>
                                </div>
                                <div>
                                    <select name="media" id="pricecat" style="width:100%">
                                        <option value="video">Видеогалерея</option>
                                        <option value="audio">Аудиогалерея</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="form-actions text-center">
                                <input type="submit"
                                       value="<?php if ($core->action == 'addmenuitem') { ?>Создать пункт<?php } else { ?>Сохранить пункт<?php } ?>"
                                       class="btn btn-success"/>
                                <input type="reset" value="Отмена" class="btn btn-default"/>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <!--		                <div class="panel-heading"><h6 class="panel-title"><i class="icon-paragraph-justify"></i> Survey results</h6></div>-->
                    <div class="panel-body">

                        <div class="tabbable page-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#inside_panel" data-toggle="tab">Публикация</a></li>
                                <li><a href="#outside_panel" data-toggle="tab">Доступ</a></li>
                                <!--                        <li><a href="#custom_tables" data-toggle="tab"> Custom tables</a></li>-->

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active fade in" id="inside_panel">
                                    <div class="form-group">
                                        <div class="col-sm-7">
                                            <label class="form-label">Публиковать пункт меню</label>
                                        </div>

                                        <label class="radio-inline radio-success">
                                            <input type="radio" name="active" class="styled"
                                                   value="0" <?php getChecked($row->active, 0); ?> />
                                            Нет
                                        </label>
                                        <label class="radio-inline radio-success">
                                            <input type="radio" name="active" class="styled"
                                                   value="1" <?php getChecked($row->active, 1); ?>/>
                                            Да
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-7">
                                            <label class="form-label">Главний страница</label>
                                        </div>

                                        <label class="radio-inline radio-success">
                                            <input type="radio" name="home_page" class="styled"
                                                   value="0" <?php getChecked($row->home_page, 0); ?> />
                                            Нет
                                        </label>
                                        <label class="radio-inline radio-success">
                                            <input type="radio" name="home_page" class="styled"
                                                   value="1" <?php getChecked($row->home_page, 1); ?>/>
                                            Да
                                        </label>

                                    </div>


                                    <div class="form-group">

                                        <label class="control-label">Открывать пункт меню</label>


                                        <select name="target" id="target" style="width:90%"
                                                class="required select-full">
                                            <option value="_self" <?php if (@$row->target == '_self') {
                                                echo 'selected="selected"';
                                            } ?>>В этом же окне (self)
                                            </option>
                                            <option value="_parent">В родительском окне (parent)</option>
                                            <option value="_blank" <?php if (@$row->target == '_blank') {
                                                echo 'selected="selected"';
                                            } ?>>В новом окне (blank)
                                            </option>
                                            <option value="_top" <?php if (@$row->target == '_top') {
                                                echo 'selected="selected"';
                                            } ?>>Поверх всех окон (top)
                                            </option>
                                        </select>

                                    </div>

                                    <div class="form-group">

                                        <label class="control-label">Меню колонкы</label>
                                        <input type="text" id="spinner-default" role="spinbutton"
                                               class="form-control ui-spinner-input" name="cols"
                                               value="<?php echo $row->cols; ?>"/>
                                    </div>

                                    <div class="form-group">

                                        <label class="control-label">Css класс пункта меню</label>
                                        <input type="text" class="form-control" name="css_class"
                                               value="<?php echo $row->css_class; ?>"/>
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="outside_panel">
                                    <div class="field">
                                        <label class="form-label">Доступ:</label>

                                        <select name="mgroup" id="mgroup" class="required select-full">
                                            <option value="-1" <?php if (@$row->mgroup == -1 || !isset($row->mgroup)) {
                                                echo 'selected="selected"';
                                            } ?>>-- Все группы --
                                            </option>
                                            <?php
                                            Registry::get("Users")->getGroups($row->mgroup);
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>

    <?php

}

if ($core->action == 'submit') {

    $menu->proccessMenuItem();
    header("Location:index.php?do=menus");
}
if ($core->action == 'addMenuitemSubmit') {

    $menu->proccessMenuItem();
    header("Location:index.php?do=menus&action=addmenuitem");
}

if ($core->action == 'update') {
    $menu->proccessMenuItem();
    header("Location:index.php?do=menus");
}


if ($core->action == 'submitmenu') {

    $params = serialize(array(
        'showtitle' => 1,
        'menutype' => Core::$post['menutype'],
        'cssclass' => Core::$post['css_prefix']));

    if (!Core::$post['is_public']) {
//        $access_list
    }
    $data = array(
        'position' => Core::$post['position'],
        'title' => Core::$post['title'],
        'active' => Core::$post['active'],
        'jscode' => Core::$post['jscode'],
        'css_class' => Core::$post['css_class'],
        'params' => $params,
        'lang' => Core::$post['lang'],
        'access' => Core::$post['access']
    );
    $menu->proccessSubmitMenu($data);
    header("Location:index.php?do=modules");
}

if ($core->action == 'updatemenu') {
    $menu->proccessMenu();
    header("Location:index.php?do=menus");
}


if ($core->action == 'move_up') {
    $order = Core::$get['order'] - 1;
    $ordid = Core::$id;
    $data = array(
        'ordering' => $order

    );
    $db->update("menus", $data, 'id=' . $ordid);
    header("Location:index.php?do=menus");
}


if ($core->action == 'move_down') {
    $order = Core::$get['order'] + 1;
    $ordid = Core::$id;
    $data = array(
        'ordering' => $order

    );
    $db->update("menus", $data, 'id=' . $ordid);
    header("Location:index.php?do=menus");
}

if ($core->action == 'delete') {
    if (is_array(Core::$post['item'])) {
        $check = Core::$post['item'];
        foreach ($check as $key => $item) {
            $item_id = $item;
            Registry::get("DataBase")->delete("menus", "id=" . $item_id);
        }
    } else {
        Registry::get("DataBase")->delete("menus", "id=" . Core::$id);
    }
    header("Location:index.php?do=menus");
}

if ($core->action=='homepage'){
    if (is_array(Core::$post['item'])) {
        $check = Core::$post['item'];
        foreach ($check as $key => $item) {

            $item_id = $item;
            $activity = Registry::get("DataBase")->getValueById('active', 'menus', $item_id);
            if ($activity == 0) {
                $data = array('active' => '1');
                Registry::get("DataBase")->update("menus", $data, "id=" . $item_id);
            }else{
                $data = array('active' => '0');
                Registry::get("DataBase")->update("menus", $data, "id=" . $item_id);
            }
        }
    } else {
        $activity = Registry::get("DataBase")->getValueById('active', 'menus', Core::$id);
        if ($activity==0){
            $data = array('active' => '1');
            Registry::get("DataBase")->update("menus", $data, "id=" . Core::$id);
        }else{
            $data = array('active' => '0');
            Registry::get("DataBase")->update("menus", $data, "id=" . Core::$id);
        }

    }
    header("Location:index.php?do=menus");
}

if ($core->action == 'active') {
    if (is_array(Core::$post['item'])) {
        $check = Core::$post['item'];
        foreach ($check as $key => $item) {

            $item_id = $item;
            $activity = Registry::get("DataBase")->getValueById('active', 'menus', $item_id);
            if ($activity == 0) {
                $data = array('active' => '1');
                Registry::get("DataBase")->update("menus", $data, "id=" . $item_id);
            }else{
                $data = array('active' => '0');
                Registry::get("DataBase")->update("menus", $data, "id=" . $item_id);
            }
        }
    } else {
        $activity = Registry::get("DataBase")->getValueById('active', 'menus', Core::$id);
        if ($activity==0){
            $data = array('active' => '1');
            Registry::get("DataBase")->update("menus", $data, "id=" . Core::$id);
        }else{
            $data = array('active' => '0');
            Registry::get("DataBase")->update("menus", $data, "id=" . Core::$id);
        }

    }
    header("Location:index.php?do=menus");
}

?>

