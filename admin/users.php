<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 14.12.2015
 * Time: 20:18
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
$users = Registry::get("Users");
$lang = Registry::get("Lang");
$config = Registry::get("Config");
$db = Registry::get("DataBase");
$pager = Registry::get("Paginator");
?>

    <div class="page-header">
        <div class="page-title">
            <h3>Пользователь
                <small>управления</small>
            </h3>
        </div>

    </div>
    <div class="clearfix"></div>


<?php
if ($core->action == 'view') {

    ?>
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
                               href="index.php?do=users&action=add"><i
                                    class="icon-user-plus2"></i>Создать
                            </a>
                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=users&action=edit');"><i
                                    class="icon-pencil"></i>Редактировать</a>
                            <a href="javascript:checkSel('index.php?do=users&action=delete');" class="btn btn-danger"><i
                                    class="icon-user-minus2"></i>Удалить</a>
                            <a class="btn btn-info"
                               href="index.php?do=users&action=usergroup"><i
                                    class="icon-users"></i>Группа</a>
                            <a class="btn btn-info"
                               href="javascript:checkSel('index.php?do=users&action=active');"><i
                                    class="icon-eye-blocked2"></i>Актировать</a>

                        </div>


                    </div>
                </div>
            </div>

        </div>
        <div class="panel panel-default">
            <!--        <div class="panel-heading" ><h6 class="panel-title"><i class="icon-checkbox-partial"></i> Table with checkboxes</h6>-->

            <div class="panel-body">
                <div class="table-responsive">
                    <div class="datatable-header">
                        <div class="dataTables_filter"><label><span>Фильтр:</span> <input name="filter"
                                                                                          type="search" class=""
                                                                                          value="<?php echo $_SESSION['filter']; ?>"
                                                                                          aria-controls="DataTables_Table_0"
                                                                                          placeholder="Фильтр..."></label>
                            <input type="submit" class="btn btn-primary" value="Фильтр"/>
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
                            <th>Логин</th>
                            <th>Названия</th>
                            <th>Дата регистрация</th>
                            <th>Последный вход</th>
                            <th>Группа</th>
                            <th>E-mail</th>
                            <th>Актировать</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $userlist = $users->getUserList();
                        foreach ($userlist as $user) {
                            ?>
                            <tr>
                                <td><input type="checkbox" name='item[]' id='item[]'
                                           value="<?php echo $user->uid; ?>"/></td>
                                <td><?php echo $user->uid; ?></td>
                                <td>
                                    <a href="index.php?do=users&action=edit&id=<?php echo $user->uid; ?>"> <?php echo $user->username; ?></a>
                                </td>
                                <td><?php echo $user->fname . ' ' . $user->lname; ?></td>
                                <td><?php echo $user->created_at; ?></td>
                                <td><?php echo $user->lastlogin; ?></td>
                                <td><?php echo $user->group; ?></td>
                                <td><?php echo $user->email; ?></td>
                                <td><?php echo Registry::get("Users")->getUserStatus($user->active); ?></td>
                                <td>
                                    <div class="table-controls">
                                        <a href="index.php?do=users&action=delete&id=<?php echo $user->uid; ?>"
                                           class="btn btn-default btn-icon btn-xs tip" title="Удалить"><i
                                                class="icon-remove"></i></a>
                                        <a href="index.php?do=users&action=edit&id=<?php echo $user->uid; ?>"
                                           class="btn btn-default btn-icon btn-xs tip" title="Редактировать"><i
                                                class="icon-pencil"></i></a>
                                        <a href="index.php?do=users&action=config&id=<?php echo $user->uid; ?>"
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
            </div>
            <div class="table-footer">

                <?php echo $pager->display_pages(); ?>

            </div>
        </div>
    </form>


    <?php
}

if ($core->action == 'add' || $core->action == 'edit') {

    $row = Users::getUserRowById(Core::$id);
    ?>
    <?php if (!empty(Core::$msgs)): ?>
        <div class="alert alert-danger fade in block-inner">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="icon-cancel-circle"></i> <?php echo Core::$showMsg; ?>
        </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" value="<?php if ($core->action == 'add') { ?>saveuser<?php } else { ?>updateuser<?php } ?>"
               name="action"/>
        <?php if (Core::$id) { ?>
            <input type="hidden" value="<?php echo $row->uid ?>" name="id"/>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading"><h6 class="panel-title"><i class="icon-pencil3"></i>Форма регистрации</h6></div>
            <div class="panel-body">
                <div class="block-inner text-danger">
                    <h6 class="heading-hr"><i class="icon-lock"></i> Информация о безопасности:</h6>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Имя пользователя:</label>
                            <input type="text" placeholder="Your name" class="form-control" name="username"
                                   value="<?php echo $row->username; ?>"/>
                        </div>

                        <div class="form-group">
                            <label>Пароль:</label>
                            <input type="text" class="form-control" name="password" id="password"/>
                        </div>

                        <?php if ($core->action=='add'){?>
                        <div class="form-group">
                            <label>Повторите пароль:</label>
                            <input type="text" class="form-control" name="password2">
                        </div>
                        <?php }?>

                        <div class="form-group">
                            <label>Веб сайта</label>
                            <input type="text" class="form-control" name="website" value="<?php echo $row->website; ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Аватар:</label>
                            <input type="file" name="avatar" class="styled form-control" id="report-screenshot">
                            <span class="help-block">Принимаемые форматы: gif, png, jpg.</span>
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            <input type="text" placeholder="Email" class="form-control" name="email"
                                   value="<?php echo $row->email; ?>"/>
                        </div>


                        <div class="form-group">
                            <label>Статус пользователь:</label>

                            <div>
                                <label class="radio-inline">
                                    <input type="radio" name="active" id="active"
                                           class="styled" <?php getChecked($row->active, 'y') ?> value="y">
                                    Активный
                                </label>

                                <label class="radio-inline">
                                    <input type="radio" name="active"
                                           class="styled" <?php getChecked($row->active, 'n') ?> value="n"/>
                                    Неактивен
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="active"
                                           class="styled" <?php getChecked($row->active, 'b') ?> value="b"/>
                                    Запрещено
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="active"
                                           class="styled" <?php getChecked($row->active, 't') ?> value="t"/>
                                    В ожидании Пользователь
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Группа:</label>
                            <select data-placeholder="Choose a Country..." class="select-full" tabindex="2"
                                    name="group">
                                <?php echo $users->getGroups($row->userlevel); ?>
                            </select>
                        </div>

                    </div>
                </div>


                <div class="block-inner text-danger">
                    <h6 class="heading-hr"><i class="icon-user"></i> Информация профиля:</h6>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Фамилия:</label>
                            <input type="text" class="form-control" name="fname" value="<?php echo $row->fname; ?>"/>
                        </div>

                        <div class="col-md-6">
                            <label>Имя:</label>
                            <input type="text" class="form-control" name="lname" value="<?php echo $row->lname; ?>"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Телефон #:</label>
                            <input type="text" placeholder="+99999-9999-9999" data-mask="+99999-999-99-99" name="phone"
                                   class="form-control" value="<?php echo $row->phone; ?>"/>
                        </div>

                        <div class="col-md-6">
                            <label>Дата рождения:</label>

                            <div class="row">
                                <div class="col-md-12">

                                    <input type="text" class="datepicker form-control hasDatepicker" name="birthdate"
                                           id="birthdate" value="<?php echo $row->birthdate; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">

                        <div class="col-md-6">
                            <label>Пол:</label>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender"
                                           class="styled" <?php getChecked($row->gender, 'm'); ?> value="m"/>
                                    Мужчина
                                </label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender"
                                           class="styled" <?php getChecked($row->gender, 'f'); ?> value="f"/>
                                    Женщина
                                </label>
                            </div>


                        </div>

                        <div class="col-md-6">
                            <label>Дополнительная информация:</label>
                            <textarea rows="5" cols="5" class="elastic form-control" name="info">
<?php echo $row->info; ?>
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right">
                    <input type="reset" value="Отмена" onclick="javascript:window.history.back();" class="btn btn-danger">
                    <input type="submit" value="Сохранить" class="btn btn-primary">
                </div>

            </div>
    </form>
    <?php
}

if ($core->action == 'usergroup') {
    ?>
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
                               href="index.php?do=users&action=addgroup" title="Создать группа"><i
                                    class="icon-user-plus2"></i>Создать
                            </a>
                            <a class="btn btn-success"
                               href="javascript:checkSel('index.php?do=users&action=editgroup');"><i
                                    class="icon-pencil"></i>Редактировать</a>
                            <a href="javascript:checkSel('index.php?do=users&action=delgroup');" class="btn btn-danger"><i
                                    class="icon-user-minus2"></i>Удалить</a>
                            <a class="btn btn-info"
                               href="index.php?do=users&action=usergroup"><i
                                    class="icon-users"></i>Отмена</a>
                        </div>


                    </div>
                </div>
            </div>

        </div>
        <div class="panel panel-default">
            <!--        <div class="panel-heading" ><h6 class="panel-title"><i class="icon-checkbox-partial"></i> Table with checkboxes</h6>-->

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
                            <!--                    <th><input type="checkbox" class="styled" name='all_boxes' onclick="changeall(menuCheck);"></th>-->
                            <th>ID</th>
                            <th>Названия</th>
                            <th>Псевдонем</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $grouplist = $users->getGroupList();
                        foreach ($grouplist as $group) {
                            ?>
                            <tr>
                                <td><input type="checkbox" name='item[]' id='item[]'
                                           value="<?php echo $group->group_id; ?>"/></td>
                                <td><?php echo $group->group_id; ?></td>
                                <td>
                                    <a href="index.php?do=users&action=editgroup&id=<?php echo $group->group_id; ?>"> <?php echo $group->title; ?></a>
                                </td>

                                <td><?php echo $group->name; ?></td>


                                <td>
                                    <div class="table-controls">
                                        <a href="index.php?do=users&action=delete&id=<?php echo $group->group_id; ?>"
                                           class="btn btn-default btn-icon btn-xs tip" title="Удалить"><i
                                                class="icon-remove"></i></a>
                                        <a href="index.php?do=users&action=edit&id=<?php echo $group->group_id; ?>"
                                           class="btn btn-default btn-icon btn-xs tip" title="Редактировать"><i
                                                class="icon-pencil"></i></a>
                                        <a href="index.php?do=users&action=config&id=<?php echo $group->group_id; ?>"
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
            </div>
            <div class="table-footer">

                <?php echo $pager->display_pages(); ?>

            </div>
        </div>
    </form>
    <?php
}

if ($core->action == 'addgroup' || $core->action == 'editgroup') {
    $grow = Users::getGroupRowById(Core::$id);
    ?>
    <form method="post">

        <input type="hidden" value="savegroup" name="action"/>
        <?php if ($core->action == 'editgroup') { ?>
            <input type="hidden" value="<?php echo Core::$id ?>" name="id"/>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading"><h6 class="panel-title"><i class="icon-pencil3"></i>Создать группу</h6></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label>Название группы:</label>
                            <input type="text" name="groupname" class="form-control"
                                   value="<?php echo $grow->title; ?>">
                        </div>

                        <div class="form-group">
                            <label>Псевдоним:</label>
                            <input type="text" name="alias" class="form-control" value="<?php echo $grow->name; ?>">
                        </div>
                        <div class="form-group">
                            <label>
                                Администраторы?:</label>
                            <label class="radio-inline">
                                <input type="radio" name="isadmin" class="styled" value="1"
                                       checked="checked" <?php getChecked($grow->isadmin, 1) ?> />
                                Да
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="isadmin" class="styled"
                                       value="0" <?php getChecked($grow->isadmin, 0) ?> />
                                Нет
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Описания:</label>
                            <textarea rows="5" cols="5" class="elastic form-control"
                                      name="description"><?php echo $grow->description; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">

                    </div>
                </div>

                <div class="form-actions text-right">
                    <input type="reset" value="Cancel" class="btn btn-danger"/>
                    <input type="submit" value="Submit form" class="btn btn-primary"/>
                </div>
            </div>
        </div>
    </form>

    <?php
}

if ($core->action == 'savegroup') {
    $data = array(
        'title' => Core::$post['groupname'],
        'name' => Core::$post['alias'],
        'description' => Core::$post['description'],
        'isadmin' => Core::$post['isadmin']
    );
    $users->proccessGroup($data);
    header("location:index.php?do=users&action=usergroup");
}

if ($core->action == 'updategroup') {
    $data = array(
        'title' => Core::$post['groupname'],
        'name' => Core::$post['alias'],
        'description' => Core::$post['description'],
        'isadmin' => Core::$post['isadmin']
    );
    $users->proccessGroup($data);
    header("location:index.php?do=users&action=usergroup");
}

if ($core->action == 'delgroup') {
    $items = Core::$post['item'];
    if (is_array($items)) {
        foreach ($items as $item) {
            $db->delete("user_groups", "group_id=" . $item);
        }
    } else {
        $db->delete("user_groups", "group_id=" . Core::$id);
    }
    header("location:index.php?do=users&action=usergroup");
}

if ($core->action == 'saveuser') {
    if (empty(Core::$files['avatar']['name'])) {
        $data = array(
            'fname' => Core::$post['fname'],
            'lname' => Core::$post['lname'],
            'gender' => Core::$post['gender'],
            'phone' => Core::$post['phone'],
            'email' => Core::$post['email'],
            'username' => Core::$post['username'],
            'group' => Core::$post['group'],
            'password' => Core::$post['password'],
            'website' => Core::$post['website'],
            'birthdate' => Core::$post['birthdate'],
            'active' => Core::$post['active']
        );
    } else {
        $data = array(
            'fname' => Core::$post['fname'],
            'lname' => Core::$post['lname'],
            'gender' => Core::$post['gender'],
            'phone' => Core::$post['phone'],
            'email' => Core::$post['email'],
            'username' => Core::$post['username'],
            'avatar' => md5(Core::$files['avatar']['name']),
            'group' => Core::$post['group'],
            'password' => Core::$post['password'],
            'website' => Core::$post['website'],
            'birthdate' => Core::$post['birthdate'],
            'active' => Core::$post['active']
        );

        $inUploadPhoto = Registry::get("Uploads");
        $inUploadPhoto->upload_path = PATH . '/uploads/avatars/';
        $inUploadPhoto->small_size_w = 100;
        $inUploadPhoto->medium_size_w = 200;
        $inUploadPhoto->thumbsqr = 1;
        $inUploadPhoto->is_watermark = 0;
        $inUploadPhoto->input_name = 'avatar';
        $inUploadPhoto->filename = Core::$files['avatar']['name'];
        $inUploadPhoto->upload();
    }
    $users->proccessUser($data);
    $user_id = Registry::get("DataBase")->insertid();
    $users->insertorupdateProfile($data, $user_id);
    header("location:index.php?do=users");
}

if ($core->action == 'updateuser') {
    $pass=Registry::get("DataBase")->getFieldById('password','users',"id='{$core->id}'");
    $password = !empty(Core::$post['password'])?md5(Core::$post['password']):$pass;

    if (empty(Core::$msgs)) {
        if (empty(Core::$files['avatar']['name'])) {
            $data = array(
                'fname' => Core::$post['fname'],
                'lname' => Core::$post['lname'],
                'gender' => Core::$post['gender'],
                'phone' => Core::$post['phone'],
                'email' => Core::$post['email'],
                'username' => Core::$post['username'],
                'group' => Core::$post['group'],
                'password' => $password,
                'website' => Core::$post['website'],
                'birthdate' => Core::$post['birthdate'],
                'active' => Core::$post['active']
            );
        } else {
            $data = array(
                'fname' => Core::$post['fname'],
                'lname' => Core::$post['lname'],
                'gender' => Core::$post['gender'],
                'phone' => Core::$post['phone'],
                'email' => Core::$post['email'],
                'username' => Core::$post['username'],
                'avatar' => Core::$files['avatar']['name'],
                'group' => Core::$post['group'],
                'password' => $password,
                'website' => Core::$post['website'],
                'birthdate' => Core::$post['birthdate'],
                'active' => Core::$post['active']
            );

            $inUploadPhoto = Registry::get("Uploads");
            $inUploadPhoto->upload_path = PATH . '/uploads/avatars/';
            $inUploadPhoto->small_size_w = 100;
            $inUploadPhoto->medium_size_w = 200;
            $inUploadPhoto->thumbsqr = 1;
            $inUploadPhoto->is_watermark = 0;
            $inUploadPhoto->input_name = 'avatar';
            $inUploadPhoto->filename = Core::$files['avatar']['name'];
            $inUploadPhoto->upload();

        }

        $users->proccessUser($data);
        $user_id = $core->id;
        $users->insertorupdateProfile($data, $user_id);
        header("location:index.php?do=users");
    } else {
        Core::msgStatus();
        header("location:index.php?do=users&action=edit&id=" . Core::$id);

//        Core::msgError("Пароль не совпадають");

    }

}


if ($core->action == 'delete') {
    $items = Core::$post['item'];
    if (is_array($items)) {
        foreach ($items as $item) {
            $db->delete("users", "id=" . $item);
            $db->delete("user_profiles", "user_id=" . $item);
        }
    } else {
        $db->delete("users", "id=" . Core::$id);
        $db->delete("user_profiles", "user_id=" . Core::$id);
    }
    header("location:index.php?do=users");
}
?>