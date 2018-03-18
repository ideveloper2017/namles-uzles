<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 15.02.2016
 * Time: 0:00
 */
function mod_menu_mobile($id)
{
    $db=Registry::get("DataBase");
    $menu=Registry::get("Menus");
    $core=Registry::get("Core");
    $cfg=$core->getModuleConfig($id);
    $menuid=$menu->menuId();


    if (!isset($cfg['menutype'])) { $menutype = 'mainmenu'; } else { $menutype = $cfg['menutype']; }
    if (!isset($cfg['cssclass'])) { $cssclass = 'sidenav__menu'; } else { $cssclass = $cfg['cssclass']; }
    if (!isset($conf['show_home'])) { $conf['show_home'] = 1; }
    if (!isset($conf['is_sub_menu'])) { $conf['is_sub_menu'] = 0; }


    $mainmenu= $menu->getMenuTree($menutype);

    $menu->getMenu($mainmenu, 1,"sidenav__menu",$cssclass);
}
?>