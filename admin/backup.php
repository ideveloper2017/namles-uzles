<?php


if (!Core::$action) {
    $core->action = 'view';
} else {
    $core->action = Core::$action;
}

if (!Core::$id) {
    $core->id = 0;
} else {
    $core->id = Core::$id;
}


if ($core->action=='view'){
    echo "aol";
    Registry::get("dbTools")->doBackup('',false);
    $dir='/backups/';
}
?>