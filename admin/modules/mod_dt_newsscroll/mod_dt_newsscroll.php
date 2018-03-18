<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 29.09.2016
 * Time: 20:43
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
if (!$cfg['style']){$cfg['style']=3;}
if (!$cfg['substyle']){$cfg['substyle']=4;}
if (!$cfg['listwith']){$cfg['listwidth']=400;}
if (!$cfg['sliderheight']){$cfg['sliderheight']=300;}
if (!$cfg['noshadow']){$cfg['noshadow']=0;}
if (!$cfg['noimage']){$cfg['noimage']=0;}
if (!$cfg['newscount']){$cfg['newscount']=5;}
if (!$cfg['speed']){$cfg['speed']=500;}
if (!$cfg['duration']){$cfg['duration']=5000;}
if (!$cfg['pause']){$cfg['pause']=1;}
if (!$cfg['event']){$cfg['event']=1;}
if (!isset($cfg['subs'])){$cfg['subs']=1;}
if (!$cfg['ctitle']){$cfg['ctitle']=100;}
if (!$cfg['cdesc']){$cfg['cdesc']=300;}
if (!$cfg['anons']){$cfg['anons']=1;}
if (!$cfg['smallimg']){$cfg['smallimg']=1;}
if (!$cfg['fullimg']){$cfg['fullimg']=1;}
if (!isset($cfg['cat_id'])) { $cfg['cat_id'] = 1; }


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
                    <label class="col-sm-2 control-label">Количество материалов</label>

                    <div class="col-sm-2">
                        <input type="text" name="newscount" id="newscount" class="form-control" value="<?php echo $cfg['newscount'];?>"/>
                    </div>
                    шт
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Статьи из раздела</label>

                    <div class="col-sm-2">
                        <select id="cat_id" name="cat_id" class="form-control">
                            <?php Registry::get("Content")->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", $cfg['cat_id']?$cfg['cat_id']:1); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Статьи из раздела</label>

                    <div class="col-sm-2">
                        <select id="cat_id" name="cat_id" class="form-control">
                            <?php Registry::get("Content")->getCategoryDropList(0, 0, "&#166;&nbsp;&nbsp;", $cfg['cat_id']?$cfg['cat_id']:1); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Подробный вывод статей</label>

                    <div class="col-sm-2">
                        <input type="radio" name="showdate" id="showdate" value="1" <?php getChecked($cfg['showdate'],1)?> class="styled"/>Да
                        <input type="radio" name="showdate" id="showdate" value="0" <?php getChecked($cfg['showdate'],0)?> class="styled"/>Нет
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Показывать анонсы</label>

                    <div class="col-sm-2">
                        <input type="radio" name="showdesc" id="showdesc" value="1" <?php getChecked($cfg['showdesc'],1)?>  class="styled"/>Да
                        <input type="radio" name="showdesc" id="showdesc" value="0"  <?php getChecked($cfg['showdesc'],0)?> class="styled"/>Нет
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Вместе с подразделами</label>

                    <div class="col-sm-2">
                        <input type="radio" name="subs" id="subs" value="1"  <?php getChecked($cfg['subs'],1)?> class="styled"/>Да
                        <input type="radio" name="subs" id="subs" value="0"  <?php getChecked($cfg['subs'],0)?> class="styled"/>Нет
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Использовать пагинацию</label>

                    <div class="col-sm-2">
                        <input type="radio" name="is_pag" id="is_pag" value="1" <?php getChecked($cfg['is_pag'],1)?> class="styled"/>Да
                        <input type="radio" name="is_pag" id="is_pag" value="0" <?php getChecked($cfg['is_pag'],0)?> class="styled"/>Нет
                    </div>
                </div>

            </div>
        </div>
    </form>
<?php
?>