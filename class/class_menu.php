<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 29.08.13
 * Time: 15:27
 * To change this template use File | Settings | File Templates.
 */
class Menus
{
    protected $menu_struct;
    protected $menulist;
//    private $menutree = array();
    private static $db;
    private static $core;
    public $slug = null;
    public $homeslug = null;
    public $_url;


    public function __construct()
    {
        self::$db = Registry::get("DataBase");
        $this->loadMenuStruct();
        self::$core = Registry::get("Core");
        $this->loadSlug();


    }

    public static function getMenuTree($menutype)
    {
        $langID = Lang::getLangID();
        $query = self::$db->query("SELECT *"
            . "\n FROM menus"
            . "\n WHERE active = 1"
            . "\n and lang='{$langID}'"
            . "\n ORDER BY parent_id,ordering");
        $res = self::$db->numrows($query);
        while ($row = self::$db->fetch($query)) {
            if ($row->menutype == $menutype) {
                $menulist[$row->id] = array(
                    'id' => $row->id,
                    'title' => $row->title,
                    'active' => $row->active,
                    'pslug' => $row->alias,
                    'home' => $row->home_page,
                    'menutype' => $row->menutype,
                    'linktype' => $row->linktype,
                    'link' => $row->link,
                    'linkid' => $row->linkid,
                    'ordering' => $row->ordering,
                    'parent_id' => $row->parent_id,
                    'css_class' => $row->css_class
                );
            }
        }
        return ($res) ? $menulist : 0;
    }

    private function loadSlug(){
        $this->slug=$_REQUEST['seo'];
    }
    public function getMenuStruct()
    {
        return $this->menu_struct;
    }


    private function loadMenuStruct()
    {
        $langID = Lang::getLangID();
        $result = self::$db->query("SELECT *"
            . "\n FROM menus"
            . "\n ORDER BY parent_id,ordering");
        while ($item = self::$db->fetch($result)) {
            $this->menu_struct[$item->id] = array(
                           'id'=>$item->id,
                           'title'=>$item->title,
                           'parent_id'=>$item->parent_id,
                           'pslug' => $item->alias,
                           'link' => $item->link,
                           'menutype' => $item->menutype,
                           'linktype' => $item->linktype,
                           'linkid' => $item->linkid,
                           'home_page' =>$item->home_page,
                           'active' => $item->active,
                           'target' => $item->target,
                           'icon' => $item->icon,
                           'cols' => $item->cols,
                           'lang'=>$item->lang,
                           );
        }

    }

    private function getMenuList()
    {

        if (!empty(Core::$post['filter'])){$where=" where title like '%".Core::$post['filter']."%'";}

        $fquery=self::$db->query("SELECT *"
            . "\n FROM menus"
            . "\n {$where}"
            . "\n ORDER BY parent_id, ordering");
        $pager = Registry::get("Paginator");
        $counter = self::$db->numrows($fquery);
        $pager->items_total = $counter;
        $pager->default_ipp = 50;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }

        $query = self::$db->query("SELECT *"
            . "\n FROM menus"
            . "\n  {$where}"
            . "\n ORDER BY parent_id, ordering"  . $pager->limit);

        $res = self::$db->numrows($query);
        while ($row = self::$db->fetch($query)) {
            $this->menulist[$row->id] =array(
                'id'=>$row->id,
                'title'=>$row->title,
                'parent_id'=>$row->parent_id,
                'link' => $row->link,
                'menutype' => $row->menutype,
                'ordering' => $row->ordering,
                'linktype' => $row->linktype,
                'linkid' => $row->linkid,
                'home_page' =>$row->home_page,
                'active' => $row->active,
                'target' => $row->target,
                'icon' => $row->icon,
                'cols' => $row->cols,
                'lang'=>$row->lang);
        }

        return ($res) ? $this->menulist:0;
    }


    public function getMenu($array, $parent_id = 1, $menuid = 'menu-main-menu', $class = ' class="main-menu"')
    {
        if (is_array($array) && count($array) > 0) {
            $submenu = false;

            $attr = ($parent_id==1) ?  " class='".$class."'"  : ' class="nav__dropdown-menu"';
            $attr2 = ($parent_id==1) ? ' class="nav__dropdown" ':' ' ;

//            $attr3= ($parent_id!=1)? ' data-toggle="dropdown" class="dropdown-toggle"':'';
            foreach ($array as $key => $row) {
//                echo $row['id'].'  '. $row['parent_id'];

                if ($row['parent_id'] == $parent_id) {
                    if ($submenu === false) {
                        $submenu = true;
                        $selarrov= "";

                        print "<ul" . $attr . ">\n";
                    }
                    $active = ($row['pslug'] == $this->slug) ? ' class="current-menu-item"' : '';
                    $mactive = ($row['pslug']) ? "active" : "normal";
                    $cssclass = $row['css_class'];
                    $name = ($parent_id == 1)  ? '<strong>' . $row['title'] . '</strong>' : $row['title'];
                    $homeactive = (preg_match('/index.php/', $_SERVER['PHP_SELF'])) ? ' ' : "normal";

                    $home = ($row['home']) ? ' class="current-menu-item"' : "";
                    $icon = ($row['icon']) ? '<i class="' . $row['icon'] . '"></i>' : "";
                    $caption = ($row['title']) ? '' . $row['title'] . $selarrov.'' : null;
                    $cols = ($row['cols'] > 1) ? ' data-cols="' . numberToWords($row['cols']) . ' cols"' : null;

                    switch ($row['linktype']) {
                        case 'component':
//                            $murl =  Url::Photo($row['slug']).'.html';
                            $murl = $row['linkid'];
                            $murl2 = $row['home'] ? '/' : $murl;
                            $link = '<a href="/' . $murl . '" class="' . $cssclass . '">' . $icon . $caption . '</a>';
                            break;

                        case 'content':
                            $murl = $row['id'];
                            $murl2 = $row['link'] ? '/' : $murl;
                            $link = '<a href="'. $row['link']  . '" class="' . $cssclass . '"'.$attr3.'>' . $icon . $caption . '</a>';
                            break;

                        case 'module':
                            $murl = $row['slug'] . '/';
                            $murl2 = $row['home'] ? '/' : $murl . ".html";
                            $link = '<a href="' . $murl2 . '" class="' . $cssclass . '">' . $icon . $caption . '</a>';
                            break;

                        case 'category':
                            $url = '/'.$row['id'].'/'.$row['link'];
                            if ($row['home']) {
                                $link = '<a href="/" class="' . $homeactive . $cssclass . '"'.$attr3.'>' . $icon . $caption . '</a>';
                            } else {
                                $link = '<a href="' . $url . '" class="' . $active . $cssclass . '"'.$attr3.'>' . $icon . $caption . '</a>';
                            }
                            break;

                        case 'link':
                            $wlink = ($row['link'] == "#") ? '#' : $row['link'];
                            $wtarget = ($row['link'] == "#") ? null : ' target="' . $row['target'] . '"';
                            $link = '<a href="' . $wlink . '"' . $wtarget . '>' . $icon . $caption . '</a>';

                            if ($row['home']) {

                                $link = '<a href="' .$wlink . '" >' . $icon . $caption . ' </span></a>';
                            }else{
                                $link = '<a href="'.$wlink.'"  >' . $icon . $caption . ' </span></a>';
                            }
                            break;



                    }

                    print '<li' . $attr2.$active.$homeactive. '>';

                    print $link;
                    $this->getMenu($array, $key);
                    print "</li>\n";

                }
            }
            unset($row);
            if ($submenu === true) {
               print "</ul>\n";
            }
        }

    }

    public function getMenuDropList($parent_id, $level = 0, $spacer, $selected = false)
    {
        if ($this->menu_struct) {
            foreach ($this->menu_struct as $key => $row) {
                $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "";
                if ($parent_id == $row['parent_id']) {
                    print "<option value=\"" . $row['id'] . "\"" . $sel . ">";

                    for ($i = 0; $i < $level; $i++)
                        print $spacer;

                    print $row['title'] . "</option>\n";
                    $level++;
                    $this->getMenuDropList($key, $level, $spacer, $selected);
                    $level--;
                }
            }
            unset($row);
        }
    }

    public function getMenuDropListFront($parent_id, $level = 0, $spacer, $selected = false)
    {
        if ($this->menu_struct) {
            foreach ($this->menu_struct as $key => $row) {
                if ($row['active'] == 1) {
                    $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "";
                    if ($parent_id == $row['parent_id']) {
                        print "<option value=\"" . $row['pslug'] . "\"" . $sel . ">";

                        for ($i = 0; $i < $level; $i++)
                            print $spacer;

                        print $row['title'] . "</option>\n";
                        $level++;
                        $this->getMenuDropList($key, $level, $spacer, $selected);
                        $level--;
                    }
                }
            }
            unset($row);
        }
    }
    public function getMenuType($selected = false)
    {

        $sql = "select * from menus_type ";
        $rows = self::$db->fetch_all($sql);
        foreach ($rows as $row) {
            $sel = ($row->menutype == $selected) ? " selected=\"selected\"" : "";
            print "<option value=\"" . $row->menutype . "\"" . $sel . ">";
            print $row->title . "</option>\n";
        }

    }

    public function getSortMenuList($parent_id = 0)
    {
        $submenu = false;
        $class = ($parent_id == 0) ? "class='uk-nestable'" : "";

        foreach ($this->menu_struct as $key => $row) {
            if ($row->parent_id == $parent_id) {
                if ($submenu === false) {
                    $submenu = true;
                    print "<ul id=\"nestableList-1\" $class data-uk-nestable>\n";
                }

                print '<li data-item="Item  ' . $row->id . '" data-item-id="' . $row->id . '">
                                                        <div class="uk-nestable-item">
                                                            <div class="uk-nestable-handle"></div>
                                                            <div data-nestable-action="toggle"></div>
                                                            <div class="list-label"><a href="index.php?do=menus&action=editmenuitem&amp;id=' . $row->id . '">' . $row->title . '</a></div>
                                                        </div>
                                                    ';
                $this->getSortMenuList($key);
                print "</li>\n";
            }
        }
        unset($row);

        if ($submenu === true)
            print "</ul>\n";
    }

    public function getSortMenuListTable($parent_id, $level = 0, $spacer, $selected = false)
    {
         foreach (self::getMenuList() as $key=>$row) {

            if ($parent_id == $row['parent_id']) {
                $publ = $row['active'] ? "<i class='icon-checkmark-circle2'></i>" : "<i class='icon-cancel-circle2'></i>";
                $home = $row['home_page'] ? "<i class='icon-star6'></i>" : "<i class='icon-star4'></i>";
                print "<tr>";
                print "<td><input type=\"checkbox\" name='item[]' id='item[]' class=\"styled\" value=" . $row['id'] . "></td>";
                print "<td>" . $row['id'] . "</td>";
                print "<td><a href='index.php?do=menus&action=editmenuitem&id={$row['id']}'>";
                for ($i = 0; $i < $level; $i++)
                    print $spacer;
                print $row['title'] . "</a></td>";
                print "<td align='center'>" . $publ . "</td>";
                print "<td align='center'>" . $home . "</td>";
                print "<td align='center'>
                            <div class=\"col-md-1\"><a href=\"index.php?do=menus&action=move_up&order=".$row['ordering']."&id=".$row['id']."\"> <i class=\"icon-arrow-up4\"></i></a></div>
                            <div class=\"col-md-7\"><input style='text-align: center;' class='form-control'
                                                         value='".$row['ordering']."'/></div>
                            <div class=\"col-md-0\"><a href=\"index.php?do=menus&action=move_down&order=".$row['ordering']."&id=".$row['id']."\"> <i class=\"icon-arrow-down4\"></i></a></div>
                        </td>";
                print "<td>" . $row['link'] . "</td>";
                print "<td>" . $row['menutype'] . "</td>";
                print "<td>" . $row['lang'] . "</td>";
                print "<td><div class=\"table-controls\">
				                                    <a href=\"index.php?do=menus&action=delete&id=".$row['id']."\" class=\"btn btn-default btn-icon btn-xs tip\" title=\"Удалить\"><i class=\"icon-remove\"></i></a>
				                                    <a href=\"index.php?do=menus&action=editmenuitem&id=".$row['id']."\" class=\"btn btn-default btn-icon btn-xs tip\" title=\"Радактировать\"><i class=\"icon-pencil\"></i></a>
				                                    </div></td>";
                print "</tr>";

                $level++;
                $this->getSortMenuListTable($key, $level, $spacer, $selected);
                $level--;

            }
        }
        unset($row);
    }


    public function proccessMenuItem()
    {
        if (Core::$id) {
            $mid=Core::$id;
            $row = self::$db->first("select m.ordering as maxorder from menus m where m.id=".$mid." order by m.ordering desc limit 1");
            $maxorder = $row->maxorder;
        } else{
            $row = self::$db->first("select m.ordering as maxorder from menus m  order by m.ordering desc limit 1");
            $maxorder=$row->maxorder+1;
        }

        $this->slug = Core::doSEO(Core::$post['title']);
        $data = array(
            'title' => Core::$post['title'],
            'parent_id' => Core::$post['parent_id'],
            'menutype' => Core::$post['menutype'],
            'lang' => Core::$post['lang'],
            'link' => Registry::get("Core")->getMenuLink(Core::$id,Core::$post['mode'],Core::$post[Core::$post['mode']]),
            'linktype' => Core::$post['mode'],
            'linkid' => Core::$post[Core::$post['mode']],
            'target' => Core::$post['target'],
            'active' => Core::$post['active'],
            'cols' => Core::$post['cols'],
            'ordering'=>$maxorder,
            'css_class' => Core::$post['css_class'],
            'mgroup' => Core::$post['mgroup'],
            'home_page' => Core::$post['home_page'],
            'alias' => $this->slug,

        );
        (Core::$id) ? self::$db->update("menus", $data, "id=" . Core::$id) : self::$db->insert("menus", $data);
    }

    public function proccessMenu()
    {
        $data = array('title' => Core::$post['title'],
            'menutype' => Core::$post['menutype'],
            'description' => Core::$post['description']);
        (Core::$id) ? self::$db->update("menus_type", $data, "id=" . Core::$id) : self::$db->insert("menus_type", $data);
    }

    public function proccessSubmitMenu($fdata)
    {
        $row = self::$db->first("select m.order as maxorder from modules m order by m.order desc limit 1");
        $maxorder = $row->maxorder + 1;

        $data = array(
            'position' => $fdata['position'],
            'title' => $fdata['title'],
            'is_external' => 1,
            'module' => 'mod_menu',
            'ordering' => $maxorder,
            'system' => '1.00',
            'hasconfig' => '1',
            'active' => $fdata['active'],
            'created' => 'now()',
            'jscode' => $fdata['jscode'],
            'params' => $fdata['params'],
            'lang' => $fdata['lang'],
            'css_prefix' => $fdata['css_prefix'],
            'access' => $fdata['access']
        );
        self::$db->insert("modules", $data);

    }

    public function menuId()
    {
        if (isset($_REQUEST['menuid'])) {
            if (is_numeric($_REQUEST['menuid'])) {
                $menuid = $_REQUEST['menuid'];
            } else {
                $menuid = 1;
            }
        }

        else {

            if (isset($_REQUEST['do'])) {
                $menuid = 0;
            } else {
                $menuid = 1;
            }
        }
        return $menuid;
    }
}
