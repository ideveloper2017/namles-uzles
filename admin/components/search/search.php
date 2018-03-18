<?php

$db = Registry::get("DataBase");
//$img=Photos::getInstance();
if (isset($_REQUEST['opt'])) {
    $opt = $_REQUEST['opt'];
} else {
    $opt = 'view';
}
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
} else {
    $id = -1;
}

$sql = "SELECT config FROM components WHERE link = 'search'";
$result = $db->query($sql);
if ($db->numrows($result)) {
    $conf = $db->first($result,true);
    if ($conf) {
        $cfg = unserialize($conf['config']);
    }
}

if ($opt == 'save') {
    $cfg = array();
    $cfg['perpage'] = $_REQUEST['perpage'];
    $cfg['comp'] = $_REQUEST['comp'];
    $sql = "UPDATE components SET config = '" . serialize($cfg) . "' WHERE link = 'search'";
    $db->query($sql);

    $msg = 'Настройки сохранены.';
    $opt = 'config';
}

if ($opt=='view'){


?>

<form action="index.php?do=components&action=config&id=<?=$_REQUEST['id'];?>" name="optform" method="post" target="_self">
    <table border="0" cellpadding="10" cellspacing="0" class="proptable">
        <tr>
            <td width="150"><b>Результатов на странице : </b></td>
            <td width="215"><input name="perpage" type="text" id="perpage" value="<?=@$cfg['perpage'];?>" size="6" /></td>
        </tr>
        <tr>
            <td valign="top" width="150"><strong>Поиск по компонентам:</strong> </td>
            <td valign="top" width="215">
                <?php
                    $components=$db->fetch_all("select * from components ");
                echo '<table border="0" cellpadding="2" cellspacing="0" width="100%">';
                foreach($components as $component){
                    echo '<tr>';
                    $checked = '';
                    if (in_array($component->link, $cfg['comp'])){
                        $checked = 'checked="checked"';
                    }
                    echo '<td><input name="comp[]" id="'.$component->link.'" type="checkbox" value="'.$component->link.'" '.$checked.'/></td><td><label for="'.$component->link.'">'.$component->title.'</label></td>';
                    echo '</tr>';
                }
                echo '</table>';
                ?></td>
        </tr>
    </table>
    <p>
        <input name="opt" class="button" type="hidden" id="do" value="save" />
        <input name="save" class="button" type="submit" id="save" value="Сохранить" />
        <input name="back" class="button" type="button" id="back" value="Отмена" onclick="window.location.href='/admin/components.php';"/>
    </p>
</form>
<?}?>