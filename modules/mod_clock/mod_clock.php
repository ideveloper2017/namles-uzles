<?php
    function mod_clock($module_id){
    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_clock.php');
    return true;
}