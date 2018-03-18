<!--<script>-->
<!--    $(document).ready(function () {-->
<!--        $('.navbar-default .navbar-nav > li.dropdown').hover(function () {-->
<!--            $('ul.dropdown-menu', this).stop(true, true).slideDown('fast');-->
<!--            $(this).addClass('open');-->
<!--        }, function () {-->
<!--            $('ul.dropdown-menu', this).stop(true, true).slideUp('fast');-->
<!--            $(this).removeClass('open');-->
<!--        });-->
<!--    });-->
<!---->
<!--</script>-->
<?php
/**
 * Created by PhpStorm.
 * User: IDeveloper
 * Date: 02.11.2015
 * Time: 17:23
 */
function mod_menu($id){
    $db=Registry::get("DataBase");
    $menu=Registry::get("Menus");
    $core=Registry::get("Core");
    $cfg=$core->getModuleConfig($id);
    $menuid=$menu->menuId();


    if (!isset($cfg['menutype'])) { $menutype = 'mainmenu'; } else { $menutype = $cfg['menutype']; }
    if (!isset($cfg['cssclass'])) { $cssclass = ''; } else { $cssclass = $cfg['cssclass']; }
    if (!isset($conf['show_home'])) { $conf['show_home'] = 1; }
    if (!isset($conf['is_sub_menu'])) { $conf['is_sub_menu'] = 0; }


    $mainmenu= $menu->getMenuTree($menutype);

    $menu->getMenu($mainmenu, 1,"clearfix",$cssclass);


}
?>