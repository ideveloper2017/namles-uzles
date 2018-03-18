<?php //if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); } ?>

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
<table id="listTable" class="table table-bordered">
    <thead>
    <tr>
        <th class="lt_header" width="25">id</th>
        <th class="lt_header" width="">Название</th>
        <th class="lt_header" width="150">Размеры</th>
        <th class="lt_header" width="100">Слайдов</th>
        <th class="lt_header" align="center" width="105">Действия</th>
    </tr>
    </thead>
    <?php if ($sliders){ ?>
        <tbody>
        <?php foreach($sliders as $slider){ ?>
            <tr id="<?php echo $slider['id']; ?>" class="item_tr">
                <td><?php echo $slider['id']; ?></td>
                <td>
                    <a href="<?php echo SS_BACKEND_URL; ?>&opt=slider&item_id=<?php echo $slider['id']; ?>">
                        <?php echo $slider['title']; ?>
                    </a>
                </td>
                <td><?php echo $slider['width']; ?> x <?php echo $slider['height']; ?></td>
                <td><?php echo $slider['slides_count']; ?></td>
                <td align="right">
                    <div style="padding-right: 8px;">
                        <a title="Редактировать" href="<?php echo SS_BACKEND_URL; ?>&opt=edit&item_id=<?php echo $slider['id']; ?>" class="btn btn-default btn-icon btn-xs tip">
<!--                            <img border="0" hspace="2" alt="Редактировать" src="images/actions/edit.gif"/>-->
                            <i
                                class="icon-pencil"></i>
                        </a>
                        <a class="btn btn-default btn-icon btn-xs tip" title="Удалить" onclick="jsmsg('Удалить слайдер <?php echo htmlspecialchars($slider['title']); ?>?', '<?php echo SS_BACKEND_URL; ?>&opt=delete&item_id=<?php echo $slider['id']; ?>')" href="#">
                            <i
                                class="icon-remove"></i>
                            <!--                            <img border="0" hspace="2" alt="Удалить" src="images/actions/delete.gif"/>-->
                        </a>
                    </div>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    <?php } else { ?>
        <tbody>
        <td colspan="5" style="padding-left:5px"><div style="padding:15px;padding-left:0px">Нет слайдеров</div></td>
        </tbody>
    <?php } ?>
</table>
