<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 11.02.2016
 * Time: 21:12
 */
function mod_callme($module_id){
    $inCore = Registry::get("Core");
    $cfg = $inCore->getModuleConfig($module_id);
    if (!$cfg['to']) { $cfg['to']='mymail@dfgh.ru'; }

    $to = $cfg['to'];

    $_SESSION['nik'] = $to;

    ?>

    <script src="/modules/mod_callme/js/callme.js"></script>

    <?php
    return true;

}
?>
