<?php

$db = Registry::get("DataBase");
$core = Registry::get("Core");
if (isset($_REQUEST['id'])) {    $id = $_REQUEST['id'];} else {    $id = -1;}
$selected = array();
if (isset(Core::$post['opt'])) {
    $opt = Core::$post['opt'];
} else {
    $opt = 'view';
}

if (!Core::$id) {
    $core->id = 0;
} else {
    $id = Core::$id;
}

$mquery = $db->query("select * from modules where id='{$id}'");
if ($db->numrows($mquery)) {
    $conf = $db->first("select * from modules where id='{$id}'");

    $cfg = unserialize($conf->params);
}

if ($opt == 'view') {
    ?>
    <form action="index.php?do=modules&action=config&id=<?= $_REQUEST['id']; ?>" method="post" name="optform"
          target="_self" id="optform">
        <div class="dashboard_block">
            <table border="0" cellpadding="10" cellspacing="0" class="proptable" style="margin: 10px;">
                <tr>
                    <td valign="top"><b>Меню для отображения :</b></td>
                    <td valign="top">
                        <select class="form-control" name="menutype" id="menutype">
                            <?php
                            Registry::get("Menus")->getMenuType();
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td valign="top"><b>Колчество меню пункты:</b></td>

                    <td valign="top">
                        <input type="text" id="max_items" class="form-control" name="max_items"
                               value="<?php echo $cfg['max_items']; ?>" class="param-string" size="50">
                    </td>
                </tr>
                <tr>
                    <td valign="top"><b>Имя меню пункт:</b></td>

                    <td valign="top">
                        <input type="text" id="menu_item_name" class="form-control" name="menu_item_name"
                               value="<?php echo $cfg['menu_item_name']; ?>" size="50" class="param-string">
                    </td>
                </tr>
                <tr>
                    <td valign="top"><b>Показывать пункт «Главная»:</b></td>

                    <td valign="top">
                        <input name="show_home" type="checkbox" value="1"
                               <? if ($cfg['show_home']){ ?>checked="checked" <?
                        } ?>/>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><b>Использовать шаблон :</b></td>
                    <td valign="top">
                        <input type="text" id="cssclass" class="form-control" name="cssclass"
                               value="<?php echo $cfg['cssclass']; ?>" class="param-string" size="50"></td>
                </tr>

            </table>


        </div>
        <p>
            <input name="opt" type="hidden" id="do" value="save"/>
            <input name="save" class="button" type="submit" id="save" value="Сохранить"/>
            <input name="back" class="button" type="button" id="back" value="Назад"
                   onclick="window.location.href='index.php?view=modules';"/>
        </p>
    </form>

    <?
}

if ($opt == 'save') {
    $cfg = array();
    $cfg['menutype'] = $_REQUEST['menutype'];
    $cfg['max_items'] = $_REQUEST['max_items'];
    $cfg['show_home'] = $_REQUEST['show_home'];
    $cfg['menu_item_name'] = $_REQUEST['menu_item_name'];
    $cfg['cssclass'] = $_REQUEST['cssclass'];
    $conf = serialize($cfg);
//    print_r($conf);
    $data = array('params' => $conf);
//    print_r($data);
    $db->update("modules",$data, 'id='.$id);
//    $db->update("modules',set params='{$conf}' where id='{$id}'");
    header("Location:index.php?do=modules");
}
?>