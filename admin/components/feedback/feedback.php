<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 18.02.2016
 * Time: 17:33
 */

if (isset($_REQUEST['opt'])) {
    $opt = $_REQUEST['opt'];
} else {
    $opt = 'view';
}

$db = Registry::get("DataBase");
$fc = Registry::get("FCatalog");
$pager = Registry::get("Paginator");
$core = Registry::get("Core");
$cfg=$core->getComponentConfig("feedback");

if ($opt=='saveconfig'){
    $config=array();
    $config['myemail']=Core::$post['myemail'];
    $config['allowattach']=Core::$post['allowattach'];
    $data=array('config'=>serialize($config));
    $db->update('components',$data,'id='.Core::$id);
    header("Location:index.php?do=components&action=config&id=".Core::$id);
}
if ($opt=='view'){

    if (!$cfg['myemail']) {$cfg['myemail']='mymail@dfgh.ru';}
    if (!$cfg['allowattach']) {$cfg['allowattach']=2;}
    ?>
    <div class="page-header">
        <div class="visible-xs header-element-toggle">
            <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-info-buttons"><i
                    class="icon-info"></i></a>
        </div>
        <div class="header-info-buttons">
            <div class="collapse" id="header-info-buttons">
                <div class="well">
                    <ul class="info-buttons">
                        <li><a href="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=add"><i
                                    class="icon-link"></i> <span>Сохранить</span> </a></li>
                        <li><a href="index.php?do=components&action=config&id=<?php echo $_REQUEST['id']; ?>&opt=add"><i
                                    class="icon-cancel"></i> <span>Отмена</span> </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <form action="" class="form-horizontal" method="POST" id="addform" name="addform" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong>
                            Введите Ваш Email:</strong><br>
                        <br></label>

                    <div class="col-sm-2"><input type="text" class="form-control" id="myemail" name="myemail"
                                                 style="font-size:12px;" value="<?php echo $cfg['myemail']; ?>"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong>
                            Количество прикрепляемых файлов:</strong><br>
                        <br></label>

                    <div class="col-sm-2"><input type="text" class="form-control" id="allowattach" name="allowattach"
                                                 style="font-size:12px;" value="<?php echo $cfg['allowattach']; ?>"></div>
                </div>





                <div class="form-actions text-right">
                    <?php if ($opt=='edit_item') {?>
                        <input type="hidden" name="f_id" value="<?php echo $filerow['id']; ?>">
                    <?php }?>
                    <input type="hidden" name="opt" value="saveconfig"/>
                    <input type="submit" class="btn btn-danger" value="Сохранить изменения">
                    <input type="button" class="btn btn-default" value="Отмена"
                           onclick="window.document.location.href='/fcatalog/';">
                </div>
            </div>
        </div>



    </form>


<?php

}

?>