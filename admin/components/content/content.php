<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Bahrom
 * Date: 25.10.13
 * Time: 22:59
 * To change this template use File | Settings | File Templates.
 */

$db=Registry::get("DataBase");
if (isset($_REQUEST['opt'])) { $opt = $_REQUEST['opt']; } else { $opt = 'list'; }
$sql = "SELECT config FROM components WHERE link = 'content'";
$result = $db->query($sql) ;

if ($db->numrows($result)){
    $conf = $db->fetch($result,true);
    if ($conf){
        $cfg = unserialize($conf['config']);
    }
}

if($opt=='saveconfig'){
    $cfg = array();
    $cfg['showtitle'] = $_REQUEST['showtitle'];
    $cfg['showdesc'] =  $_REQUEST['showdesc'];
    $cfg['autokeys']=  $_REQUEST['autokeys'];
    $cfg['pt_show'] =  $_REQUEST['pt_show'];
    $cfg['published'] =  $_REQUEST['published'];
    $cfg['pt_disp'] =  $_REQUEST['pt_disp'];
    $cfg['pt_hide'] =  $_REQUEST['pt_hide'];
    $cfg['pt_morecontent'] = $_REQUEST['pt_morecontent'];

    $data=array("config"=>serialize($cfg));
    $db->update("components",$data,"link='content'");
//    $sql = "UPDATE components SET config = '".serialize($cfg)."' WHERE link = 'content'";
//    dbQuery($sql) ;

    $msg = 'Настройки сохранены.';

}

if (@$msg) { echo '<p class="success">'.$msg.'</p>'; }
?>

<?php
if ($opt=='list'){
    ?>
<div class="panel panel-default">
    <div class="panel-heading"> <h6 class="panel-title"><i class="icon-table2"></i>Каталог статей</h6></div>
    <div class="panel-body">
    <form class="form-horizontal" action="index.php?do=components&action=config&id=<?=$_REQUEST['id'];?>" method="post" name="optform" target="_self" id="form1">

            <div class="form-group">
                <label class="col-sm-2 control-label"><b>Показывать заголовки статей:</label>
                <div class="col-sm-2"><input  class="styled" name="showtitle" type="radio" value="1" <?php if (@$cfg['showtitle']) { echo 'checked="checked"'; } ?>/>
                    Да
                    <input name="showtitle" class="styled" type="radio" value="0" <?php if (@!$cfg['showtitle']) { echo 'checked="checked"'; } ?>/>
                    Нет </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Показывать анонсы статей (блог):</label>
                <div class="col-sm-2"><input name="showdesc" class="styled" type="radio" value="1" <?php if (@$cfg['showdesc']) { echo 'checked="checked"'; } ?>/>
                    Да
                    <input name="showdesc" type="radio" class="styled" value="0" <?php if (@!$cfg['showdesc']) { echo 'checked="checked"'; } ?>/>
                    Нет </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    Автоматически генерировать<br />ключевые слова и описания для статей:
                </label>
                <div class="col-sm-2">
                    <input name="autokeys" type="radio" value="1" class="styled" <?php if (@$cfg['autokeys']) { echo 'checked="checked"'; } ?>/> Да
                    <input name="autokeys" type="radio" value="0" class="styled" <?php if (@!$cfg['autokeys']) { echo 'checked="checked"'; } ?>/> Нет
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Показывать содержание статей:</label>
                <div class="col-sm-2"><input name="pt_show" type="radio" class="styled" value="1" <?php if (@$cfg['pt_show']) { echo 'checked="checked"'; } ?>/>
                    Да
                    <input name="pt_show" type="radio" value="0" class="styled" <?php if (@!$cfg['pt_show']) { echo 'checked="checked"'; } ?>/>
                    Нет </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Показывать при модерация:</label>
                <div class="col-sm-2"><input name="published" type="radio" class="styled" value="1" <?php if (@$cfg['published']) { echo 'checked="checked"'; } ?>/>
                    Да
                    <input name="published" type="radio" value="0" class="styled" <?php if (@!$cfg['published']) { echo 'checked="checked"'; } ?>/>
                    Нет </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Разворачивать содержание:</label>
                <div class="col-sm-2"><input name="pt_disp" type="radio" class="styled" value="1" <?php if (@$cfg['pt_disp']) { echo 'checked="checked"'; } ?>/>
                    Да
                    <input name="pt_disp" type="radio" class="styled" value="0" <?php if (@!$cfg['pt_disp']) { echo 'checked="checked"'; } ?>/>
                    Нет </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Ссылка &quot;Скрыть содержание&quot;:</label>
                <div class="col-sm-2"><input name="pt_hide" class="styled" type="radio" value="1" <?php if (@$cfg['pt_hide']) { echo 'checked="checked"'; } ?>/>
                    Да
                    <input name="pt_hide" type="radio" class="styled" value="0" <?php if (@!$cfg['pt_hide']) { echo 'checked="checked"'; } ?>/>
                    Нет </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Похоже материаль&quot;:</label>
                <div class="col-sm-2"><input name="pt_morecontent" class="styled" type="radio" value="1" <?php if (@$cfg['pt_morecontent']) { echo 'checked="checked"'; } ?>/>
                    Да
                    <input name="pt_morecontent" type="radio" class="styled" value="0" <?php if (@!$cfg['pt_morecontent']) { echo 'checked="checked"'; } ?>/>
                    Нет </div>
            </div>

        <div class="form-actions">
            <input name="opt" type="hidden" class="button" value="saveconfig" />
            <input name="save" type="submit" class="btn btn-danger" id="save" value="Сохранить" />
            <input name="back" type="button" class="btn btn-primary" id="back" value="Отмена" onclick="window.location.href='index.php?do=components';"/>
        </div>
    </form>
    </div>
<?}?>

