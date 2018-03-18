<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 02.02.2016
 * Time: 3:00
 */
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

$config=Registry::get("Config");
if ($core->action=='view'){
    ?>
    <div class="page-header">
        <div class="page-title">
            <h3>Конфигурация сайта
                <small>управления</small>
            </h3>
        </div>

    </div>
    <div class="clearfix"></div>
<?php
}
?>