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

$menu = Registry::get("Menus");
$lang = Registry::get("Lang");
$config = Registry::get("Config");
$db = Registry::get("DataBase");
$pager = Registry::get("Paginator");

?>
    <div class="page-header">
        <div class="page-title">
            <h3>Модули
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
            <li><a href="components.html">Модули</a></li>
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
    <form method="post" name="selform">
        <div class="page-header">
            <div class="visible-xs header-element-toggle">
                <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                        class="icon-insert-template"></i></a>
            </div>

            <div class="header-buttons">
                <div class="collapse" id="header-buttons">
                    <div class="well">
                        <?php if ($pager->display_pages()) { ?>
                            <?php echo $pager->items_per_page(); ?>
                        <?php } ?>


                        <div class="btn-group">
                            <a class="btn btn-success"
                                    href="index.php?do=modules&action=add"><i
                                    class="icon-link"></i>Создать модуль
                            </a>
                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=modules&action=edit');"><i
                                    class="icon-pencil"></i>Редактировать</a>
                            <a href="javascript:checkSel('index.php?do=modules&action=delete');" class="btn btn-danger"><i
                                    class="icon-remove"></i>Удалить</a>
                            <a class="btn btn-info"
                               href="javascript:checkSel('index.php?do=modules&action=active');"><i
                                    class="icon-eye"></i>Показать / Скрыть</a>

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
            <table class="table table-bordered table-check">
                <thead>
                <tr>
                    <th><a href="javascript:" onclick="javascript:invert()">#</a></th>
                    <!--                    <th><input type="checkbox" class="styled" name='all_boxes' onclick="changeall(menuCheck);"></th>-->
                    <th>ID</th>
                    <th>Название</th>
                    <th>Показ</th>
                    <th width="170">Порядок</th>
                    <th>Создание</th>
                    <th>Позиция</th>
                    <th>Язык</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $modules = Registry::get("Core")->getModules();
                foreach ($modules as $module) {
                    ?>
                    <tr>
                        <td><input type="checkbox" name='item[]' id='item[]'
                                   value="<?php echo $module->id; ?>"/></td>
                        <td><?php echo $module->id; ?></td>
                        <td>
                            <a href="index.php?do=modules&action=edit&id=<?php echo $module->id; ?>"> <?php echo $module->title; ?></a>
                        </td>
                        <td align="center"><?php echo $module->active ? "<i class='icon-checkmark-circle2'></i>" : "<i class='icon-cancel-circle2'></i>"; ?></td>
                        <td align='center'>
                            <div class="icons-group">
                                <div class="col-md-1"><a
                                        href="index.php?do=modules&action=move_up&order=<?php echo $module->ordering ?>&id=<?php echo $module->id; ?>">
                                        <i class="icon-arrow-up4"></i></a></div>
                                <div class="col-md-4"><input style='text-align: center; width: 40px;'
                                                             class='form-control'
                                                             value='<?php echo $module->ordering ?>'/></div>
                                <div class="col-md-1"><a
                                        href="index.php?do=modules&action=move_down&order=<?php echo $module->ordering ?>&id=<?php echo $module->id; ?>">
                                        <i class="icon-arrow-down4"></i></a></div>
                            </div>
                        </td>
                        <td><?php echo $module->created; ?></td>
                        <td><?php echo $module->position; ?></td>
                        <td><?php echo $module->name; ?></td>
                        <td>
                            <div class="table-controls">
                                <a href="index.php?do=modules&action=delete&id=<?php echo $module->id; ?>"
                                   class="btn btn-default btn-icon btn-xs tip" title="Удалить"><i
                                        class="icon-remove"></i></a>
                                <a href="index.php?do=modules&action=edit&id=<?php echo $module->id; ?>"
                                   class="btn btn-default btn-icon btn-xs tip" title="Редактировать"><i
                                        class="icon-pencil"></i></a>
                                <a href="index.php?do=modules&action=config&id=<?php echo $module->id; ?>"
                                   class="btn btn-default btn-icon btn-xs tip"><i
                                        class="icon-cogs"></i></a>
                            </div>
                        </td>
                    </tr>

                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="table-footer">

            <?php echo $pager->display_pages(); ?>

        </div>
    </form>
    <?php
}
if ($core->action == 'add' || $core->action == 'edit') {

    $_SESSION['editlist'] = Core::$post['item'];
    if (isset($_SESSION['editlist'])) {
        $item_id = array_shift($_SESSION['editlist']);
    } else {
        $item_id = Core::$id;
    }

    $editquery = Registry::get("DataBase")->query("select * from modules where id=" . $item_id);
    if (Registry::get("DataBase")->numrows($editquery)) {
        $modRows = Registry::get("DataBase")->first("select * from modules where id=" . $item_id);
    }

    $sql = "select * from modules_menu where mod_id={$item_id} and menu_id=0";
    $result = Registry::get("DataBase")->query($sql);
    if (Registry::get("DataBase")->numrows($result)) {
        $show_all = true;
    } else {
        $show_all = false;
    }

    ?>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="index.html">Главная</a></li>
            <li><a href="components.html">Модули</a></li>
            <!--                        <li class="active">Header elements</li>-->
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

    <div class="page-header">
        <div class="page-title">
            <h3><?php if ($core->action == 'edit') { ?>Редактировать модуль<?php } else { ?>Добавить модуль <?php } ?>
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
                        <button class="btn btn-danger" type="submit" onclick="document.addform.submit()">Сохранить</button>
                    </div>

                    <div class="btn-group">
                        <button class="btn btn-primary">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <form method="post" name="addform">
            <input type="hidden" name="action"
                   value="<?php if ($core->action == 'add') { ?>submit<?php } else { ?>update<?php } ?>">
            <?php if ($core->action == 'edit') { ?>
                <input type="hidden" name="id" value="<?php echo $modRows->id; ?>">
            <?php } ?>
            <div class="col-md-8">

                <div class="panel panel-default">
                    <!--			                <div class="panel-heading"><h6 class="panel-title"><i class="icon-bubble4"></i> Survey</h6></div>-->
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="title">Заголовок:</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="<?php echo $modRows->title; ?>">
                        </div>
                        <div class="form-group">
                            <label class=" control-label">Позиция показа: </label>
                            <select id="position" name="position" class="form-control">
                                <?php
                                $pos = Registry::get("Core")->loadModulePosition();
                                foreach ($pos as $key => $value) {
                                    if (@$modRows->position == $value) {
                                        echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                    } else {
                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lang">Язык: </label>
                            <select class="required select-full" name="lang"
                                    tabindex="2">
                                <?php $lang->getLangList($modRows->lang); ?>
                            </select>
                        </div>


                        <?php if (($core->action == 'add')) { ?>
                            <div id='file_div' class="form-group" style="display: none;">
                                <label class="form-label">
                                    Новая модуля <span class="hinttext">— Файлы из папки <b>modules/</b> названия которых начинаются на module</span>
                                </label>

                                <div>
                                    <input type="text" class="form-control" id="filename" name="filename"
                                           style="width:99%" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lang">Тип модуля: </label>
                                <select name="operate" id="operate" class="form-control" onchange="checkDiv()"
                                        style="width:100%">
                                    <option value="html" selected="selected">Пользовательский (html)</option>
                                    <option value="user">Пользовательский (новый)</option>
                                    <option value="clone">Дубликат (копия)</option>
                                </select>
                            </div>
                        <?php } ?>
                        <div id="user_div" style="display: none;">
                            <div>
                                <?php if ($core->action == 'edit' || ($modRows->is_user == 2)) { ?>
                                    <textarea id="sourcecode" name="sourcecode" class="form-control" rows="5" cols="5">
                                    <?php
                                    $file = PATH . '/modules/' . $modRows->module . '/' . $modRows->module . '.php';
                                    echo file_get_contents(ltrim($file, ' '));
                                    ?>
                                </textarea>
                                    <?php
                                } else {
                                    $html = "\r";
                                    ?>
                                    <textarea name="sourcecode" id="bodypost" style="height: 800px; width: 99%;">
                                    <?php

                                    $html .= '<?php' . "\r";
                                    $html .= 'function mod_/* MODULE NAME */($module_id){' . "\r";
                                    $html .= '$db=DB::getInstance();' . "\r";
                                    $html .= '$ker=Engine::getInstance();' . "\r";
                                    $html .= '$menuid = menuId();' . "\r";
                                    $html .= '$sql = "SELECT *';
                                    $html .= 'FROM' . "\r\t";;
                                    $html .= 'WHERE' . "\r";;
                                    $html .= 'ORDER BY ordering ASC";' . "\r";
                                    $html .= '$result = $db->query($sql) or die(mysql_error());' . "\r";
                                    $html .= 'if ($db->num_rows($result)){' . "\r";
                                    $html .= 'while ($item=$db->fetch_assoc($result)){' . "\r";
                                    $html .= '}' . "\r";
                                    $html .= '}' . "\r";
                                    $html .= 'return true;' . "\r";
                                    $html .= '}' . "\r";
                                    $html .= '?>' . "\r";
                                    echo $html;
                                    ?>
                                </textarea>
                                    <!--                                --><?php //Core::loadEditor("bodycontent");?>
                                <? } ?>
                            </div>
                        </div>
                        <div id="clone_div" class="form-group" style="display:none;">
                            <label class="form-label">
                                Скопировать модуль
                            </label>

                            <select name="clone_id" id="clone_id" class="required select-full" style="width:100%">
                                <?php
                                Registry::get("Core")->getModuleList();
                                ?>
                            </select>

                        </div>
                        <?php if (($core->action == 'add') || (($core->action == 'edit') && ($modRows->is_user == 1) )) { ?>
                            <div id="html_div" >
                                <div class="col-sm-12">
                                    <?php Registry::get("Core")->insertPanel(); ?>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Вставить (html) кодом</label>
                                </div>
                                <div class="col-sm-12">
                                    <?php Core::loadEditor("content", "99%", "300", $modRows->module); ?>
                                </div>

                            </div>
                        <? } ?>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="lang">JavaScript Код </label>
                            <div class="col-sm-12">
                            <textarea rows="5" cols="5" class="form-control" name="jscode" id="jscode"><?php echo $modRows->jscode; ?></textarea>
                             </div>
                        </div>



                    </div>
                    <div class="form-actions text-center">
                        <input type="submit"
                               value="<?php if ($core->action == 'addmenuitem') { ?>Создать пункт<?php } else { ?>Сохранить пункт<?php } ?>"
                               class="btn btn-success"/>
                        <input type="reset" value="Отмена" class="btn btn-default"/>

                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
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

                                        <label class="checkbox-inline checkbox-success form-label">
                                            <input type="checkbox" class="styled" name="showtitle" id="showtitle"
                                                   value="1" <?php if ($core->action == 'edit') {
                                                getChecked($modRows->showtitle, 1);
                                            } else {
                                                getChecked($modRows->showtitle, 0);
                                            } ?> />
                                            Показать модуль название
                                        </label>

                                    </div>
                                    <div class="form-group">

                                        <label class="checkbox-inline checkbox-success form-label">
                                            <input type="checkbox" class="styled" name="active" id="active"
                                                   value="1" <?php if ($core->action == 'edit') {
                                                getChecked($modRows->active, 1);
                                            } else {
                                                getChecked(1, 1);
                                            } ?> />
                                            Публиковать модуль
                                        </label>

                                    </div>


                                    <div class="form-group">

                                        <label class="checkbox-inline checkbox-success form-label">
                                            <input type="checkbox" class="styled" name="show_all" id="show_all"
                                                   value="1" <?php if ($show_all) {
                                                getChecked($show_all, 1);
                                            } ?> onclick="checkGroupList()"/>
                                            Показывать на всех страницах сайта
                                        </label>

                                    </div>


                                    <div class="clearfix"></div>

                                    <?php
                                    if ($core->action == 'edit') {
                                        $bind_sql = "SELECT * FROM modules_menu WHERE mod_id = " . $modRows->id;
                                        $bind_res = Registry::get("DataBase")->query($bind_sql);
                                        $bind = array();
                                        $bind_pos = array();
                                        while ($r = Registry::get("DataBase")->fetch($bind_res)) {
                                            $bind[] = $r->menu_id;
                                            $bind_pos[$r->menu_id] = $r->position;
                                        }
                                    }
                                    $menu_sql = "SELECT * FROM menus ORDER BY ordering";
                                    $menu_res = Registry::get("DataBase")->query($menu_sql);

                                    $menu_items = array();
                                    if (Registry::get("DataBase")->numrows($menu_res)) {
                                        while ($item = Registry::get("DataBase")->fetch($menu_res)) {
                                            if ($core->action == 'edit') {
                                                if (in_array($item->id, $bind)) {
                                                    $item->selected = true;
                                                    $item->position = $bind_pos[$item->id];
                                                }
                                            }
                                            $item->title = str_replace('-- Корневая страница --', 'Главная страница', $item->title);
                                            $menu_items[] = $item;
                                        }
                                    }


                                    ?>
                                    <div class="form-group" id="grp">
                                        <label class=" control-label">Где показывать модуль?</label>

                                        <div
                                            style="height:300px;overflow: auto;border: solid 1px #999; padding:5px 10px; background: #FFF;">
                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                                   align="center">
                                                <tr>
                                                    <td colspan="2" height="25"><strong>Раздел сайта</strong></td>
                                                    <td align="center" width="50"><strong>Позиция</strong></td>
                                                </tr>
                                                <?php
                                                foreach ($menu_items as $i) {
                                                    ?>
                                                    <tr>
                                                        <td width="20" height="25">
                                                            <input type="checkbox" name="showin[]" class="styled"
                                                                   id="mid<?php echo $i->id; ?>"
                                                                   value="<?php echo $i->id; ?>"
                                                                   <?php if ($i->selected){ ?>checked="checked"<?php } ?>
                                                                   onclick="$('#p<?php echo $i->id; ?>').toggle()"/>
                                                        </td>
                                                        <td>
                                                            <label class="form-label"
                                                                   for="mid<?php echo $i->id; ?>"><?php echo $i->title; ?></label>
                                                        </td>
                                                        <td align="center">
                                                            <select id="p<?php echo $i->id; ?>"
                                                                    name="showpos[<?php echo $i->id; ?>]"
                                                                    style="<?php if (!$i->selected) { ?>display:none<?php } ?>">
                                                                <?php foreach ($pos as $position) { ?>
                                                                    <option value="<?php echo $position; ?>"
                                                                            <?php if ($i->position == $position){ ?>selected="selected"<?php } ?>><?php echo $position; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </div>

                                    </div>


                                    <div class="form-group">

                                        <label class="control-label">Css класс пункта меню</label>
                                        <div >
                                        <input type="text" class="form-control" name="css_prefix"
                                               value="<?php echo $modRows->css_prefix; ?>"/>
                                        </div>
                                        </div>

                                    <div class="form-group">

                                        <label class="control-label">Модуль шаблонь</label>
                                        <span class="hinttext">&mdash; Файлы из папки <b>modules/</b> вашего шаблона, названия которых начинаются на module</span>
                                        <div >
                                            <?php
                                            $tpls = Core::loadModuleTemplate();
                                            ?>
                                            <select name="tpl" id="tpl"  class="form-control">
                                                <?php
                                                foreach($tpls as $tpl){
                                                    $selected = ($modRows->tpl==$tpl || (!$modRows->tpl && $tpl=='module.php' )) ? 'selected="selected"' : '';
                                                    echo '<option value="'.$tpl.'" '.$selected.'>'.$tpl.'</option>';
                                                }
                                                ?>
                                            </select>
<!--                                            <input type="text" class="form-control" name="tpl"-->
<!--                                                   value="--><?php //echo $modRows->tpl; ?><!--"/>-->
                                        </div>
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

if (Core::$action == 'move_up') {
    $order = Core::$get['order'];
    $order = $order - 1;
    $data = array('ordering' => $order);
    $db->update("modules", $data, "id=" . Core::$id);
    header("location:index.php?do=modules");
}

if (Core::$action == 'move_down') {
    $order = Core::$get['order'];
    $order = $order + 1;
    $data = array('ordering' => $order);
    $db->update("modules", $data, "id=" . Core::$id);
    header("location:index.php?do=modules");
}

if ($core->action == 'config') {
    $editM = "select * from modules where id=" . Core::$id;
    $editRow = $db->first($editM);
    $file = 'modules/' . $editRow->module . '/' . $editRow->module . '.php';

    if (file_exists($file)) {
        include($file);
    } else {
        echo 'Не найдена модуль';
    }
}

if (Core::$action == 'delete') {
    $_SESSION['editlist'] = Core::$post['item'];
    if (is_array($_SESSION['editlist'])) {
        foreach ($_SESSION['editlist'] as $item_id) {
            $db->delete("modules", "id=" . $item_id);
            $db->delete("modules_menu", "mod_id=" . $item_id);
        }
    }else{
        $db->delete("modules", "id=" . Core::$id);
        $db->delete("modules_menu", "mod_id=" . Core::$id);
    }
    header("location:index.php?do=modules");
}

if (Core::$action == 'active') {
    $_SESSION['editlist'] = Core::$post['item'];
    if (is_array($_SESSION['editlist'])) {
        foreach ($_SESSION['editlist'] as $item_id) {
            $activity = Registry::get("DataBase")->getValueById('active', 'modules', $item_id);
            if ($activity==0) {
                $data = array("active" => "1");
                $db->update("modules", $data, "id=" . $item_id);
            }else{
                $data = array("active" => "0");
                $db->update("modules", $data, "id=" . $item_id);
            }
        }
    }else {
        $activity = Registry::get("DataBase")->getValueById('active', 'modules', Core::$id);
        if ($activity == 0) {
            $data = array("active" => "1");
            $db->update("modules", $data, "id=" . Core::$id);
        } else {
            $data = array("active" => "0");
            $db->update("modules", $data, "id=" . Core::$id);
        }
    }
    header("location:index.php?do=modules");
}


if (Core::$action == 'submit') {

    $data = array(
        'title' => Core::$post['title'],
        'position' => Core::$post['position'],
        'lang' => Core::$post['lang'],
        'active' => Core::$post['active'],
        'content' => Core::$post['content'],
        'filename' => Core::$post['filename'],
        'sourcecode' => Core::$post['sourcecode'],
        'operate' => Core::$post['operate'],
        'jscode' => Core::$post['jscode'],
        'css_prefix' => Core::$post['css_prefix'],
        'tpl' => Core::$post['tpl'],
        'modgroup' => Core::$post['mgroup'],
        'show_all' => Core::$post['show_all'],
        'showin' => Core::$post['showin'],
        'showpos' => Core::$post['showpos'],
        'showtitle' =>Core::$post['showtitle']);
         Registry::get("Core")->proccessModule($data);
    header("location:index.php?do=modules");
}
if (Core::$action == 'update') {

    $data = array(
        'title' => Core::$post['title'],
        'position' => Core::$post['position'],
        'lang' => Core::$post['lang'],
        'active' => Core::$post['active'],
        'content' => Core::$post['content'],
        'filename' => Core::$post['filename'],
        'sourcecode' => Core::$post['sourcecode'],
        'jscode' => Core::$post['jscode'],
        'operate' => Core::$post['operate'],
        'mgroup' => Core::$post['mgroup'],
        'css_prefix' => Core::$post['css_prefix'],
        'tpl' => Core::$post['tpl'],
        'show_all' => Core::$post['show_all'],
        'showin' => Core::$post['showin'],
        'showpos' => Core::$post['showpos'],
        'showtitle' =>Core::$post['showtitle']);

    Registry::get("DataBase")->delete("modules_menu", "mod_id='{$core->id}'");
    Registry::get("Core")->proccessModule($data);
    header("location:index.php?do=modules");
}
?>