<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 04.02.2016
 * Time: 16:39
 */
$db = Registry::get("DataBase");
$core = Registry::get("Core");
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
} else {
    $id = -1;
}
$selected = array();
if (isset($_REQUEST['opt'])) {
    $opt = $_REQUEST['opt'];
} else {
    $opt = 'view';
}

$cfg=$core->getModuleConfig($id);
if (!isset($cfg['cat_id'])) { $cfg['cat_id'] = 1; }
if (!isset($cfg['newscount'])) { $cfg['newscount'] = 10; }


if ($opt=='saveconfig'){
    $data=array('cat_id'=>$_REQUEST['cat_id'],
        'newscount'=>$_REQUEST['newscount'],
        );
    $db->update("modules",array('params'=>serialize($data)),"id=".$id);
    header("location:index.php?do=modules");
}
if ($opt == 'view') {
    ?>
    <form class="form-horizontal" method="post">
        <input name="opt" value="saveconfig" type="hidden"/>
        <div class="page-header">
            <div class="page-title"></div>
            <div class="header-buttons">

                <div class="collapse" id="header-buttons">
                    <div class="well">
                        <button type="submit" class="btn btn-info">Сохранить</button>
                        <button type="submit" class="btn btn-danger">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h6 class="panel-title">Конфигурация</h6></div>
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label">Статьи из раздела</label>

                    <div class="col-sm-2">
                        <select id="cat_id" name="cat_id" class="form-control">
                            <?php Registry::get("Content")->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", $cfg['cat_id']?$cfg['cat_id']:1); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Количество материалов</label>

                    <div class="col-sm-2">
                        <input type="text" name="newscount" id="newscount" class="form-control" value="<?php echo $cfg['newscount'];?>"/>
                    </div>
                    шт
                </div>


            </div>
        </div>
    </form>
    <?php

}
?>


