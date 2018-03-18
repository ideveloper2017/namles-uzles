<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.04.2017
 * Time: 9:38
 */
function mod_lang($module_id){

    $langs=Lang::getLanguageList();
    echo '<ul id="menu-top-menu" class="menu">';
    foreach($langs as $lang){
        echo '<li><a href="/lang/'.$lang->flag.'">'.$lang->name.'</a></li>';
    }
    echo '</ul>';
}