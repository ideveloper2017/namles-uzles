<?php
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
if (!isset($cfg['slider_id'])) { $cfg['slider_id'] = 1; }
if (!isset($cfg['subs'])) { $cfg['subs'] = 1; }
if (!isset($cfg['cat_id'])) { $cfg['cat_id'] = 1; }
if (!isset($cfg['delay'])) { $cfg['delay'] = 3500; }
if (!isset($cfg['speed'])) { $cfg['speed'] = 1000; }


if ($opt=='saveconfig'){
    $data=array('slider_id'=>$_REQUEST['slider_id'],
        'is_horizontal'=>$_REQUEST['is_horizontal'],
        'is_auto'=>$_REQUEST['is_auto'],
        'is_nav'=>$_REQUEST['is_nav'],
        'is_dots'=>$_REQUEST['is_dots'],
        'is_reverse'=>$_REQUEST['is_reverse'],
        'delay'=>$_REQUEST['delay'],
        'speed'=>$_REQUEST['speed'],
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
                        <select id="slider_id" name="slider_id" class="form-control">
                            <?php Registry::get("Content")->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", $cfg['cat_id']?$cfg['cat_id']:1); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Прокрутка слайдов</label>

                    <div class="col-sm-4">
                        <input type="radio" name="is_horizontal" id="is_horizontal" value="1" <?php getChecked($cfg['is_horizontal'],1)?> class="styled"/>Слева направо
                        <input type="radio" name="is_horizontal" id="is_horizontal" value="0" <?php getChecked($cfg['is_horizontal'],0)?> class="styled"/>Сверху вниз
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Автостарт</label>

                    <div class="col-sm-2">
                        <input type="radio" name="is_auto" id="is_auto" value="1" <?php getChecked($cfg['is_auto'],1)?>  class="styled"/>Да
                        <input type="radio" name="is_auto" id="is_auto" value="0"  <?php getChecked($cfg['is_auto'],0)?> class="styled"/>Нет
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Показывать стрелки влево/вправо</label>

                    <div class="col-sm-2">
                        <input type="radio" name="is_nav" id="is_nav" value="1"  <?php getChecked($cfg['is_nav'],1)?> class="styled"/>Да
                        <input type="radio" name="is_nav" id="is_nav" value="0"  <?php getChecked($cfg['is_nav'],0)?> class="styled"/>Нет
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Использовать пагинацию</label>

                    <div class="col-sm-2">
                        <input type="radio" name="is_dots" id="is_dots" value="1" <?php getChecked($cfg['is_dots'],1)?> class="styled"/>Да
                        <input type="radio" name="is_dots" id="is_dots" value="0" <?php getChecked($cfg['is_dots'],0)?> class="styled"/>Нет
                    </div>
                </div>
<div class="form-group">
                    <label class="col-sm-2 control-label">Обратная сортировка слайдов</label>

                    <div class="col-sm-2">
                        <input type="radio" name="is_reverse" id="is_reverse" value="1" <?php getChecked($cfg['is_reverse'],1)?> class="styled"/>Да
                        <input type="radio" name="is_reverse" id="is_reverse" value="0" <?php getChecked($cfg['is_reverse'],0)?> class="styled"/>Нет
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Количество материалов</label>

                    <div class="col-sm-2">
                        <input type="text" name="delay" id="delay" class="form-control" value="<?php echo $cfg['delay'];?>"/>
                    </div>
                    шт
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Скорость анимации</label>

                    <div class="col-sm-2">
                        <input type="text" name="speed" id="speed" class="form-control" value="<?php echo $cfg['speed'];?>"/>
                    </div>
                    1 секунда = 1000 миллисекунд
                </div>

            </div>
        </div>
    </form>
    <?php

}
?>


