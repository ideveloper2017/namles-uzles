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
                    <a class="btn btn-success" href="<?php echo SS_BACKEND_URL . '&opt=add_slide&item_id=' . $id?>"><i class="icon-newspaper"></i>Новый слайд
                    </a>
                    <a class="btn btn-info" href="javascript:document.reorderform.submit()" onclick="javascript:document.reorderform.submit();"><i class="icon-sort2"></i>Сохранить порядок слайдов
                    </a>
                    <a class="btn btn-danger"><i class="icon-cancel"></i>Отмена
                    </a>
                </div>


            </div>
        </div>
    </div>

</div>

<div class="panel panel-default">
    <div class="panel-heading">
<h3 class="panel-title">Слайдер: <span><?php echo $slider['title']; ?></span></h3>
    </div>
<form id="reorderform" name="reorderform" action="<?php echo SS_BACKEND_URL; ?>&opt=reorder_slides&item_id=<?php echo $slider['id']; ?>" method="post">
<table id="listTable" class="table table-bordered" cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th class="lt_header" width="25">id</th>
            <th class="lt_header" width="">Название слайда</th>
            <th class="lt_header" width="100">Порядок</th>
            <th class="lt_header" align="center" width="100">Действия</th>
        </tr>
    </thead>
    <?php if ($slides){ ?>
        <tbody>
            <?php foreach($slides as $slide){ ?>
                <tr id="<?php echo $slide['id']; ?>" class="item_tr">
                    <td><?php echo $slide['id']; ?></td>
                    <td>
                        <a href="<?php echo SS_BACKEND_URL; ?>&opt=edit_slide&slide_id=<?php echo $slide['id']; ?>&item_id=<?php echo $slider['id']; ?>">
                            <?php echo $slide['title']; ?>
                        </a>
                    </td>
                    <td>
                        <input type="text" name="ord[<?php echo $slide['id']; ?>]" value="<?php echo $slide['ordering']; ?>" style="width:40px"  class="form-control"/>
                    </td>
                    <td align="right">
                        <div style="padding-right: 8px;">
                            <a title="Редактировать слайд" class="btn btn-default btn-icon btn-xs tip" href="<?php echo SS_BACKEND_URL; ?>&opt=edit_slide&slide_id=<?php echo $slide['id']; ?>&item_id=<?php echo $slider['id']; ?>">
<!--                                <img border="0" hspace="2" alt="Редактировать слайд" src="images/actions/edit.gif"/>-->
                                <i
                                        class="icon-pencil"></i>
                            </a>
                            <a title="Удалить слайд" class="btn btn-default btn-icon btn-xs tip" onclick="jsmsg('Удалить слайд <?php echo htmlspecialchars($slide['title']); ?>?', '<?php echo SS_BACKEND_URL; ?>&opt=delete_slide&item_id=<?php echo $slide['id']; ?>')" href="#">
<!--                                <img border="0" hspace="2" alt="Удалить слайд" src="images/actions/delete.gif"/>-->
                                <i
                                        class="icon-remove"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    <?php } else { ?>
        <tbody>
            <td colspan="3" style="padding-left:5px"><div style="padding:15px;padding-left:0px">Нет слайдов в этом слайдере</div></td>
        </tbody>
    <?php } ?>
</table>    
</form>
</div>