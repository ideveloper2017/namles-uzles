<?php
//if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
$title = $opt == 'add' ? 'Новый слайдер' : 'Редактировать слайдер';
if (!isset($slider)) {
    $slider = array(
        'title' => '',
        'width' => 580,
        'height' => 240,
    );
}
?>


<?php //cpToolMenu($toolmenu); ?>

<div class="page-header">
    <div class="page-title">
        <h3>СуперСлайдер</h3>
    </div>

    <div class="visible-xs header-element-toggle">
        <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-buttons"><i class="icon-insert-template"></i></a>
    </div>

    <div class="header-buttons">
        <div class="collapse" id="header-buttons">
            <div class="well">
                <div class="btn-group">
                    <a class="btn btn-success" href="<?php echo SS_BACKEND_URL . '&opt=add'?>"><i class="icon-newspaper"></i>Новый слайдер
                    </a>
                </div>


            </div>
        </div>
    </div>

</div>

<div class="panel panel-default">
    <div class="panel-heading">
<h6 class="panel-title"><?php echo $title; ?></h6>
    </div>
<div class="panel-body">
<form id="addform" class="ss-form" name="addform" method="post" action="">

    <fieldset>

        <div class="field">
            <label><b>Название слайдера:</b></label>
            <input type="text" class="input-text form-control" name="title" value="<?php echo $slider['title']; ?>" />
        </div>

        <div class="field form-group">
            <label><b>Размеры слайдера:</b></label>
            <label class="col-sm-1 control-label">Ширина</label>
            <div class="col-sm-1">
            <input type="text" class="input-small form-control" name="width" value="<?php echo $slider['width']; ?> " />
            </div>
            <label class="col-sm-1 control-label">Висота</label>
            <div class="col-sm-1">
            <input type="text" class="input-small form-control" name="height" value="<?php echo $slider['height']; ?>" />
            </div>
        </div>

    </fieldset>
    <fieldset>

        <div class="field">
            <label><b>Тип слайдера:</b></label>
            <select name="is_external" id="select-external" class="input-select form-control">
                <option value="0"<?php if (!$slider['is_external']) {?> selected="selected"<?php } ?>>Ручной</option>
                <option value="1"<?php if ($slider['is_external']) {?> selected="selected"<?php } ?>>Автоматический</option>
            </select>
        </div>

    </fieldset>
    <fieldset id="fieldset-external"<?php if (!$slider['is_external']) {?> style="display: none"<?php } ?>>

        <div class="field">
            <div class="field">
                <label><b>Таблица:</b></label>
                <input type="text" class="input-text" name="options[table]" value="<?php echo $slider['options']['table']; ?>" />
            </div>
            <div class="field">
                <label><b>Количество записей:</b></label>
                <input type="text" class="input-small" name="options[limit]" value="<?php echo $slider['options']['limit']; ?>" />
            </div>
            <div class="field">
                <label><b>Сортировать по полю:</b></label>
                <input type="text" class="input-medium" name="options[orderby]" value="<?php echo $slider['options']['orderby']; ?>" />
                <select name="options[orderto]" class="input-select">
                    <option value="asc"<?php if ($slider['options']['orderto']=='asc') {?> selected="selected"<?php } ?>>по возрастанию</option>
                    <option value="desc"<?php if ($slider['options']['orderto']=='desc') {?> selected="selected"<?php } ?>>по убыванию</option>
                </select>
            </div>
            <div class="field">
                <label><b>Условия для выборки:</b></label>
                <input type="text" class="input-text" name="options[where]" value="<?php echo $slider['options']['where']; ?>" />
            </div>
            <div class="field">
                <label><b>Шаблон пути к изображению:</b></label>
                <input type="text" class="input-text" name="options[image]" value="<?php echo $slider['options']['image']; ?>" />
            </div>
            <div class="field">
                <label><b>Шаблон ссылки на запись:</b></label>
                <input type="text" class="input-text" name="options[url]" value="<?php echo $slider['options']['url']; ?>" />
            </div>
        </div>

    </fieldset>

    <div class="buttons">
        <input type="submit" name="submit" class="btn btn-info" value="Сохранить" />
    </div>

</form>
</div>
</div>