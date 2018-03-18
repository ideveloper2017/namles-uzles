<?php
if (isset($_REQUEST['opt'])) { $opt = $_REQUEST['opt']; } else { $opt = 'config'; }
$db = Registry::get("DataBase");
$core = Registry::get("Core");
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
} else {
    $id = -1;
}

$cfg=$core->getModuleConfig($id);
if ($opt=='save'){
    $cfg=array();
    $cfg['media'] = $_REQUEST['media'];
    $cfg['newscount'] = $_REQUEST['newscount'];
    $cfg['back'] = $_REQUEST['back'];
    $data=array('params'=>serialize($cfg));
    $db->update("modules",$data,'id='.$id);
}
?>

<form class="form-horizontal" action="index.php?do=modules&action=config&id=<?php echo $_REQUEST['id'];?>" method="post" name="optform" target="_self" id="optform">

    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title">M</h6></div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-2 control-label">Тип медиазаписей: </label>
                <div class="col-sm-2"><select name="media" class="form-control">
                        <option selected value="video">Видео</option>
                        <option value="audio">Аудио</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><strong>Количество медиазаписей : </strong></label>
                <div class="col-sm-2"><input class="form-control" name="newscount" type="text" id="newscount" value="<?php if (@$cfg) { echo $cfg['newscount']; } ?>" size="5" />
                </div>шт.
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><strong>Показывать ссылку на медиагалерею: </strong></label>
                <div class="col-sm-2"><input class="styled" name="back" type="checkbox" value="1" <?php if (@$cfg['back']) { echo 'checked="checked"'; } ?>/>
                </div>
            </div>
            <div class="form-actions">

                <input name="opt" type="hidden" id="do" value="save" />

                <input name="save" type="submit" id="save" value="Сохранить" />

                <input name="back" type="button" id="back" value="Назад" onclick="window.location.href='index.php?view=modules';"/>
            </div>


        </div>

    </div>
</form>
