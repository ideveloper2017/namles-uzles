<?php
//    if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
    $title = $opt == 'add_slide' ? 'Новый слайд' : 'Редактировать слайд';
    if (!isset($slide)) {
        $slide = array(
            'title' => '',
            'bg_image' => '',
            'bg_color' => '#CCCCCC',
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
                    <a class="btn btn-success" href="javascript:ssSave()" onclick="javascript:ssSave();"><i class="icon-storage"></i>Сохранить
                    </a>
                    <a class="btn btn-danger" href="<?php echo SS_BACKEND_URL . '&opt=slider&item_id=' . $slider_id?>"><i class="icon-cancel"></i>Отмена
                    </a>
                </div>


            </div>
        </div>
    </div>

</div>
<div class="panel panel-default">
    <div class="panel-heading">
<h3 class="panel-title"><?php echo $title; ?></h3>
    </div>
    <div class="panel-body">
<table id="ss-builder">

    <tr>
        <td width="200" valign="top">

            <div id="ss-slide-options"
                 data-upload-url="<?php echo SS_BACKEND_URL; ?>&opt=upload_image&slider_id=<?php echo $slider['id'];  ?>"
                 data-upload-layer-url="<?php echo SS_BACKEND_URL; ?>&opt=upload_layer_image&slider_id=<?php echo $slider['id'];  ?>"
                 >
                <form id="addform" name="addform" action="" method="post">
                    <input type="text" id="slide-title" class="input-text" name="title" value="<?php echo $slide['title']; ?>" placeholder="Название слайда">
                    <input type="text" id="slide-color" class="input-text" name="bg_color" value="<?php echo $slide['bg_color']; ?>" placeholder="Цвет фона">
                    <input type="hidden" name="bg_image" value="<?php echo $slide['bg_image']; ?>"/>
                    <input type="hidden" name="struct" value=""/>
                    <div id="ss-upload-widget">
                        <div id="file-uploader"></div>
                        <div class="loading" style="display:none">Загрузка...</div>
                    </div>
                </form>
            </div>

            <div id="layers">
                <ul>
                </ul>
                <a id="btn-add-layer" href="javascript:">Добавить слой</a>
                <a id="btn-del-layer" href="javascript:">Удалить слой</a>
            </div>

        </td>
        <td id="preview" valign="middle">

            <div id="slider" style="width: <?php echo $slider['width']; ?>px; height: <?php echo $slider['height']; ?>px; background-image:url('<?php echo $slide['bg_image']; ?>'); background-color:<?php echo $slide['bg_color']; ?>;">
            </div>

        </td>
    </tr>

    <tr>

        <td colspan="2" id="props" style="display: none">

            <div class="prop-group">
                <div class="prop">
                    <label>Текст:</label>
                    <input type="text" id="prop-text" class="input-text" placeholder="Введите текст"/>
                </div>
                <div class="prop">
                    <label>Ссылка:</label>
                    <input type="text" id="prop-href" class="input-text" placeholder="http://example.com" />
                </div>
            </div>
            <div class="prop-group">
                <div class="prop">
                    <label>Шрифт:</label>
                    <select id="prop-font-family">
                        <option value="Arial">Arial</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Helvetica">Helvetica</option>
                        <option value="Impact">Impact</option>
                        <option value="Tahoma">Tahoma</option>
                        <option value="Times New Roman">Times New Roman</option>
                        <option value="Trebuchet MS">Trebuchet MS</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Monospace">Monospace</option>
                    </select>
                </div>
                <div class="prop">
                    <?php $fsizes = array(10, 11, 12, 13, 14, 15, 16, 18, 20, 22, 24, 26, 28, 32, 36, 40); ?>
                    <label>Размер шрифта:</label>
                    <select id="prop-font-size">
                        <?php foreach($fsizes as $size){ ?>
                            <option value="<?php echo $size; ?>px"><?php echo $size; ?></option>
                        <?php } ?>
                    </select>
                    <label style="display:inline-block"><input type="checkbox" id="prop-font-weight" class="input-checkbox" value="bold"> <b>Ж</b></label>
                    <label style="display:inline-block"><input type="checkbox" id="prop-font-style" class="input-checkbox" value="italic"> <i>К</i></label>
                </div>
            </div>
            <div class="prop-group">
                <div class="prop">
                    <label>Цвет текста:</label>
                    <input type="text" id="prop-color" class="input-color" placeholder="#RRGGBB" />
                </div>
                <div class="prop">
                    <label>Цвет фона:</label>
                    <input type="text" id="prop-background-color" class="input-color" placeholder="#RRGGBB" />
                </div>
            </div>
            <div class="prop-group">
                <div class="prop">
                    <label>Поля, в пикселях:</label>
                    <input type="text" id="prop-padding" class="input-text" />
                </div>
                <div class="prop">
                    <label>Размеры, в пикселях:</label>
                    <input type="text" id="prop-width" class="input-size" /> x
                    <input type="text" id="prop-height" class="input-size" />
                </div>
            </div>
            <div class="prop-group">
                <div class="prop">
                    <label>Изображение:</label>
                    <div id="ss-layer-upload-widget">
                        <div id="file-layer-uploader"></div>
                        <div class="loading" style="display:none">Загрузка...</div>
                    </div>
                    <input type="hidden" id="prop-background-image" value="">
                </div>
                <div class="prop">
                    <label>Координаты:</label>
                    X <input type="text" id="prop-left" class="input-size" />
                    Y <input type="text" id="prop-top" class="input-size" />
                </div>
            </div>

        </td>

    </tr>

</table>

<?php if ($opt=='edit_slide'){ ?>
<script type="text/javascript">
    var load_struct = '<?php echo $slide['struct']; ?>';
</script>
<?php } ?>
</div>
</div>
