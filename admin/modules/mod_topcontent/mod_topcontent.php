<?php
$db=Registry::get("DataBase");
$sql="select params from mmodules where id=".Core::$id;
$row=$db->first($sql);
$cfg=unserialize($row->params);

if (isset($_REQUEST['opt'])) {$opt = $_REQUEST['opt'];} else { $opt = 'view';}



?>

<form class="form-horizontal" method="post">
    <input name="opt" type="hidden" id="opt" value="saveconfig"/>
<div class="page-header">
    <div class="page-title">
        <h3>Конфигурация <small>изминения модуль конфигурация</small></h3>
    </div>
<!--    <div class="header-buttons">-->
<!--    <div class="collapse" id="header-buttons">-->
<!--        <div class="well">-->
<!--           <button type="button" class="btn btn-info">Сохранить</button>-->
<!--            <button type="button" class="btn btn-danger">Отмена</button>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
    <div class="visible-xs header-element-toggle">
        <a class="btn btn-primary btn-icon" data-toggle="collapse" data-target="#header-select"><i class="icon-menu3"></i></a>
    </div>
</div>

    <div class="block">
<!--        <h6 class="heading-hr"><i class="icon-paragraph-right2"></i> Horizontal form outside panel</h6>-->

        <div class="form-group">
            <label class="col-sm-2 control-label">Сартировка:</label>
            <div class="col-sm-4">
                <select name="sorting" id="sorting">
                    <option value="hits">Хит</option>
                    <option value="created_at">Дата</option>
                </select>
<!--                <select data-placeholder="Choose a Country..." class="select" tabindex="2" name="sort" id="sort">-->
                <!---->
                <!--                    <option value="hits">Хити</option>-->
                <!--                    <option value="created_at">Дата</option>-->
                <!--<!--                    <option value="Canada">Canada</option>-->
                <!--<!--                    <option value="Cape Verde">Cape Verde</option>-->
                <!--                </select>-->
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Количество материал:</label>
            <div class="col-sm-2">
                <input type="text" name="limit" class="form-control">
            </div>
        </div>
<!---->
<!--        <div class="form-group">-->
<!--            <label class="col-sm-2 control-label">With placeholder:</label>-->
<!--            <div class="col-sm-10">-->
<!--                <input type="text" class="form-control" placeholder="placeholder">-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="form-group">-->
<!--            <label class="col-sm-2 control-label">Block helpers:</label>-->
<!--            <div class="col-sm-10">-->
<!--                <div class="row">-->
<!--                    <div class="col-sm-4">-->
<!--                        <input type="text" class="form-control">-->
<!--                        <span class="help-block">Left aligned helper</span>-->
<!--                    </div>-->
<!--                    <div class="col-sm-4">-->
<!--                        <input type="text" class="form-control">-->
<!--                        <span class="help-block text-center">Centered helper</span>-->
<!--                    </div>-->
<!--                    <div class="col-sm-4">-->
<!--                        <input type="text" class="form-control">-->
<!--                        <span class="help-block text-right">Right aligned helper</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="form-group">-->
<!--            <label class="col-sm-2 control-label">Textarea:</label>-->
<!--            <div class="col-sm-10">-->
<!--                <textarea rows="5" cols="5" class="form-control"></textarea>-->
<!--            </div>-->
<!--        </div>-->

        <div class="form-actions text-right">
            <input type="submit" value="Submit form" class="btn btn-primary">
        </div>

    </div>
</form>

<?php
if ($opt=='saveconfig'){
    $data=array();
    $data['sort']=Core::$post['sorting'];
    $data['limit']=Core::$post['limit'];
    $ser=serialize($data);
    $db->query("update modules set params='{$ser}' where id=".Core::$id);
    header("location:index.php?do=modules");
}

?>