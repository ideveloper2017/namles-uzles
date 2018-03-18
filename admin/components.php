<?php
/**
 * Created by PhpStorm.
 * User: IDeveloper
 * Date: 20.10.2015
 * Time: 13:19
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

?>
    <div class="page-header">
        <div class="page-title">
            <h3>Компоненты
                <small>управления</small>
            </h3>
        </div>

    </div>
    <div class="clearfix"></div>
<?php
if ($core->action == 'view') {
    ?>
    <div class="page-header">
        <div class="page-title">
            <!--            <h3>Components <small>Bootstrap and custom components</small></h3>-->
        </div>

        <div class="visible-xs header-element-toggle">
            <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i
                    class="icon-insert-template"></i></a>
        </div>

        <div class="header-buttons">
            <div class="collapse" id="header-buttons">
                <div class="well">
                    <div class="btn-group">
                        <button class="btn btn-primary">Action</button>
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span
                                class="caret caret-split"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right icons-right">
                            <li><a href="#"><i class="icon-cogs"></i> This is</a></li>
                            <li><a href="#"><i class="icon-grid3"></i> Dropdown</a></li>
                            <li><a href="#"><i class="icon-spinner7"></i> With right</a></li>
                            <li><a href="#"><i class="icon-link"></i> Aligned icons</a></li>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action <span
                                class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right icons-right">
                            <li><a href="#"><i class="icon-cogs"></i> This is</a></li>
                            <li><a href="#"><i class="icon-grid3"></i> Dropdown</a></li>
                            <li><a href="#"><i class="icon-spinner7"></i> With right</a></li>
                            <li><a href="#"><i class="icon-link"></i> Aligned icons</a></li>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-grid"></i>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right icons-right">
                            <li><a href="#"><i class="icon-cogs"></i> This is</a></li>
                            <li><a href="#"><i class="icon-grid3"></i> Dropdown</a></li>
                            <li><a href="#"><i class="icon-spinner7"></i> With right</a></li>
                            <li><a href="#"><i class="icon-link"></i> Aligned icons</a></li>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-info">Save</button>

                    <button type="button" class="btn btn-danger">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title"><i class="icon-checkbox-partial"></i> Table with checkboxes
            </h6></div>
        <div class="table-responsive">
            <table class="table table-bordered table-check">
                <thead>
                <tr>
                <tr>
                    <th><input type="checkbox" class="styled"></th>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Версия</th>
                    <th>Включен</th>
                    <th>Ссылка</th>
                    <th>Действия</th>
                </tr>
                </tr>
                </thead>
                <tbody>
                <?php foreach (Core::getComponentList() as $items) { ?>
                    <tr>
                        <th scope="row"><input type="checkbox" class="styled" value="<?php $items['id']; ?>"></th>
                        <td><a
                                href="index.php?do=components&action=config&id=<?php echo $items['id'] ?>"><?php echo $items['id']; ?></a></td>
                        <td><a
                                href="index.php?do=components&action=config&id=<?php echo $items['id'] ?>"><?php echo $items['title']; ?></a></td>
                        <td><?php echo $items['ver']; ?></td>
                        <td><?php echo $items['active'] == 1 ? '<div class="notice-icon"><i class="fa fa-check"></i></div>' : '<input tabindex="5" type="radio" id="minimal-radio-1" class="icheck-minimal-red" checked>'; ?></td>
                        <td><?php echo $items['link']; ?></td>
                        <td>
                            <div class="fa-hover col-md-4 col-sm-6 col-xs-12"><span data-class="pencil-square-o"><i
                                        class="fa fa-pencil-square-o"></i></span> <span data-class="cog"><i
                                        class="fa fa-gear"></i></span></div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
}
if ($core->action == 'config') {

    $editRow = $db->first("select * from components where id='{$core->id}'",true);
    $file = 'components/' . $editRow['link'] . '/' . $editRow['link'] . '.php';

    if (file_exists($file)) {
        include($file);

    } else {
        echo 'Не Найдена компоненть';
    }

}
?>