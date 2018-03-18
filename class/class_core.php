<?php
//if (!defined("BPA_CMS")) {
//    die("notugri");
//}

class Core
{
    private static  $instance;
    public static $id = null;
    public static $get = array();
    public static $post = array();
    public static $cookie = array();
    public static $files = array();
    public static $server = array();
    public static $msgs = array();
    public static $showMsg;
    public static $action = null;
    public static $paction = null;
    public static $maction = null;
    public static $do = null;
    private static $marker = array();
    public  $plugins = array();
    public $single_run_plugins = array('wysiwyg');
    private static  $filters;

    public function __construct()
    {
        $_GET = self::clean($_GET);
        $_POST = self::clean($_POST);
        $_COOKIE = self::clean($_COOKIE);
        $_FILES = self::clean($_FILES);
        $_SERVER = self::clean($_SERVER);

        self::$get = $_GET;
        self::$post = $_POST;
        self::$cookie = $_COOKIE;
        self::$files = $_FILES;
        self::$server = $_SERVER;

        self::getAction();
        self::getActionPost();
        self::getDo();
        self::$id = self::getId();
        $this->plugins = $this->getPluginAll();

    }
    public static function getInstance($install_mode=false) {
        if (self::$instance === null) {
            self::$instance = new self($install_mode);
        }
        return self::$instance;
    }


    public static function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);

                $data[self::clean($key)] = self::clean($value);
            }
        } else {
            if (ini_get('magic_quotes_gpc')) {
                $data = stripslashes($data);
            } else {
                $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            }
        }
        return $data;
    }

    private static function getAction()
    {
        if (isset(self::$get['action'])) {
            self::$action = ((string)self::$get['action']) ? self::$get['action'] : false;
            if (self::$action == false) {
                self::error("You have selected an Invalid Action Method", "Filter::getAction()");
            } else
                return self::$action;
        }
    }

    public static function error($msg, $source)
    {
        if (DEBUG == true) {
            $the_error = "<div class=\"alert alert-block\">";
            $the_error .= "<span>System ERROR!</span><br />";
            $the_error .= "DB Error: " . $msg . " <br /> More Information: <br />";
            $the_error .= "<ul class=\"error\">";
            $the_error .= "<li> Date : " . date("F j, Y, g:i a") . "</li>";
            $the_error .= "<li> Function: " . $source . "</li>";
            $the_error .= "<li> Script: " . $_SERVER['REQUEST_URI'] . "</li>";
            $the_error .= "<li>&lsaquo; <a href=\"javascript:history.go(-1)\"><strong>Go Back to previous page</strong></a></li>";
            $the_error .= '</ul>';
            $the_error .= '</div>';
        } else {
            $the_error = "<div class=\"msgError\" style=\"color:#444;width:400px;margin-left:auto;margin-right:auto;border:1px solid #C3C3C3;font-family:Arial, Helvetica, sans-serif;font-size:13px;padding:10px;background:#f2f2f2;border-radius:5px;text-shadow:1px 1px 0 #fff\">";
            $the_error .= "<h4 style=\"font-size:18px;margin:0;padding:0\">Oops!!!</h4>";
            $the_error .= "<p>Something went wrong. Looks like the page you're looking for was moved or never existed. Make sure you typed the correct URL or followed a valid link.</p>";
            //$the_error .= "<p>&lsaquo; <a href=\"javascript:history.go(-1)\" style=\"color:#0084FF;\"><strong>Go Back to previous page</strong></a></p>";
            $the_error .= '</div>';
        }
        print $the_error;
//        die();
    }

    private static function getActionPost()
    {
        if (isset(self::$post['action'])) {
            self::$action = ((string)self::$post['action']) ? self::$post['action'] : false;
            if (self::$action == false) {
                self::error("You have selected an Invalid Action Method", "Filter::getAction()");
            } else
                return self::$action;
        }
    }

    private static function getDo()
    {
        if (isset($_REQUEST['do'])) {
            $do = ((string)$_REQUEST['do']) ? (string)$_REQUEST['do'] : false;
            if ($do == false) {
                self::error("You have selected an Invalid Do Method", "Filter::getDo()");
            } else
                return self::$do = $do;
        }
    }

    private static function getId()
    {
        if (isset($_REQUEST['id'])) {
            self::$id = (is_numeric($_REQUEST['id']) && $_REQUEST['id'] > -1) ? intval($_REQUEST['id']) : false;

            if (self::$id == false) {
                DEBUG == true ? self::error("You have selected an Invalid Id", "Filter::getId()") : self::ooops();
            } else
                return self::$id;
        }
    }

    public static function ooops()
    {
        $the_error = "<div class=\"msgError\" style=\"color:#444;width:400px;margin-left:auto;margin-right:auto;border:1px solid #C3C3C3;font-family:Arial, Helvetica, sans-serif;font-size:13px;padding:10px;background:#f2f2f2;border-radius:5px;text-shadow:1px 1px 0 #fff\">";
        $the_error .= "<h4 style=\"font-size:18px;margin:0;padding:0\">Oops!!!</h4>";
        $the_error .= "<p>Something went wrong. Looks like the page you're looking for was moved or never existed. Make sure you typed the correct URL or followed a valid link.</p>";
        $the_error .= "<p>&lsaquo; <a href=\"javascript:history.go(-1)\" style=\"color:#0084FF;\"><strong>Go Back to previous page</strong></a></p>";
        $the_error .= '</div>';
        print $the_error;
        die();
    }

    public static function loadModule($position)
    {
        $modules = self::loadModuleItems();
        $modulebody = array();
        if (!isset($modules[$position])) {
            return false;
        }

        foreach ($modules[$position] as $inMod) {
            $modulefile = PATH . '/modules/' . $inMod['module'] . '/' . $inMod['module'] . '.php';

            if (!$inMod['is_external']) {
                $inMod['module']=  Core::processFilters($inMod['module']);
                $inMod['body'] =$inMod['module'] ;
            }
            if ($inMod['is_external']) {
                if (file_exists($modulefile)) {
                    require_once($modulefile);
                    ob_start();
                    $modulebody = $inMod['module']($inMod['id']);
                    $modulebody = ob_get_clean();
                    $inMod['body'] = $modulebody;
                }
                $inMod['body'] = $modulebody;
            }
            $tpl=$inMod['tpl']?$inMod['tpl']:'module.php';
            include(PATH . "/theme/" . Registry::get("Config")->template . "/modules/".$tpl);
        }
        return true;
    }

    public static function loadModuleItems()
    {
        $user = Registry::get("Users");
        $db = Registry::get("DataBase");
        $langID = Lang::getLangID();
        $modules = array();
        $menuid = Registry::get("Menus")->menuId();
        if ($user->userlevel) {
            $gid = $user->userlevel;
        } else {
            $gid = $user->guestGroup();
        }
        $sql = "SELECT m.*,mn.position as mb_position FROM modules m left join modules_menu mn on mn.mod_id=m.id WHERE  m.active=1  and (mn.menu_id={$menuid} or mn.menu_id=0)
                                                                                                                  and ((m.lang='{$langID}') or (m.lang='*'))
                                                                                                                  and ((m.modgroup=0) or (m.modgroup='{$gid}'))
                                                                                                                 ORDER BY ordering asc";
        $result = $db->query($sql);
        while ($mod = $db->fetch_array($result)) {
            $modules[$mod['mb_position']][] = $mod;
        }
        return $modules;
    }

    public static function msgStatus()
    {
        self::$showMsg = "<div class=\"alert alert-warning\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button><ul class=\"wojo list\">";
        $i = count(self::$showMsg);
        foreach (self::$msgs as $msg) {
            self::$showMsg .= "<li><h4>" . $msg . "</h4></li>\n";
        }
        self::$showMsg .= "</ul></div>";

        return self::$showMsg;
    }

    public static function checkPost($index, $msg)
    {

        if (empty($_POST[$index]))
            self::$msgs[$index] = $msg;

    }

    public static function msgSingleAlert($msg, $print = true)
    {
        self::$showMsg = "<div class='span12'><div class=\"alert alert-success\"><i class=\"attention icon\"></i> " . $msg . "</div></div>";
        if ($print == true) {
            print self::$showMsg;
        } else {
            return self::$showMsg;
        }
    }

    public static function msgInfo($msg, $print = true, $fader = false, $altholder = false)
    {
        self::$showMsg = "<div class='span12'><div class=\"alert alert-warning\"><i class=\"flag icon\"></i><i class=\"close icon\"></i>
        <div class=\"content\">" . $msg . "</div></div></div>";
        if ($fader == true)
            self::$showMsg .= "<script type=\"text/javascript\">

			setTimeout(function() {
			  $(\".msgInfo\").fadeOut(\"slow\",
			  function() {
				$(\".msgInfo\").remove();
			  });
			},
			4000);
		   </script>";
        if ($print == true) {
            print ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
        } else {
            return ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
        }
    }

    public static function msgError($msg, $print = true, $fader = false, $altholder = false)
    {
        self::$showMsg = "<div class='span12'><div class=\"alert alert-error\"><i class=\"flag icon\"></i>
                          <i class=\"close icon\"></i><div class=\"content\"><div class=\"header\">Error</div><p>" . $msg .
            "</p></div></div></div>";
        if ($fader == true)
            self::$showMsg .= "<script type=\"text/javascript\">

			setTimeout(function() {
			  $(\".msgError\").fadeOut(\"slow\",
			  function() {
				$(\".msgError\").remove();
			  });
			},
			4000);

		  </script>";
        if ($print == true) {
            return ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
        } else {
            return ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
        }
    }

    public static function msgAlert($msg, $print = true, $fader = false, $altholder = false)
    {
        self::$showMsg = "<div class='span12'><div class=\"alert alert-warning\"><i class=\"flag icon\"></i><i class=\"close icon\"></i><div class=\"content\">" . $msg . "</div></div></div>";
        if ($fader == true)
            self::$showMsg .= "<script type=\"text/javascript\">
		    setTimeout(function() {
			  $(\".msgAlert\").fadeOut(\"slow\",
			  function() {
				$(\".msgAlert\").remove();
			  });
			},
			4000);
		   </script>";
        if ($print == true) {
            print ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
        } else {
            return ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
        }
    }

    public static function msgSingleError($msg, $print = true)
    {
        self::$showMsg = "<div class=\"span12\"><div class=\"alert alert-danger\"><i class=\"ban circle icon\"></i> " . $msg . "</div></div>";

        if ($print == true) {
            print self::$showMsg;
        } else {
            return self::$showMsg;
        }
    }

    public static function doSEO($content)

    {


        $transA = array('А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Ґ' => 'g', 'Д' => 'd', 'Е' => 'e', 'Є' => 'e', 'Ё' => 'yo', 'Ж' => 'zh', 'З' => 'z', 'И' => 'i', 'І' => 'i', 'Й' => 'y', 'Ї' => 'y', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u', 'Ў' => 'u', 'Ф' => 'f', 'ҳ' => 'h','Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch', 'Ъ' => '', 'Ы' => 'y', 'Ь' => '', 'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya');
        $transB = array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'і' => 'i', 'й' => 'y', 'ї' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'ҳ' => 'h', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', '&quot;' => '', '&amp;' => '', 'µ' => 'u', '№' => '');
        $content = trim($content);
        $content = strtr($content, $transA);
        $content = strtr($content, $transB);
        $content = preg_replace("/\s+/ums", "_", $content);
        $content = preg_replace('/[\-]+/ui', '-', $content);
        $content = preg_replace('/[\.]+/u', '_', $content);
        $content = preg_replace("/[^a-z0-9\_\-\.]+/umi", "", $content);
        $content = str_replace("/[_]+/u", "_", $content);
        return $content;

    }

    public static function getRowById($table, $id, $and = false, $is_admin = true)
    {

        if ($and) {
            $sql = "SELECT * FROM " . (string )$table . " WHERE id = '" . Registry::get("DataBase")->escape((int)$id) . "' AND " . Registry::get("Database")->escape($and) . "";
        } else
            $sql = "SELECT * FROM " . (string )$table . " WHERE id = '" . Registry::get("DataBase")->escape((int)$id) . "'";

        $row = Registry::get("DataBase")->first($sql);

        if ($row) {
            return $row;
        }
//        else {
//            if ($is_admin)
//                Filter::error("You have selected an Invalid Id - #" . $id, "Core::getRowById()");
//        }
    }

    public function getComponentConfig($id)
    {
        $query = Registry::get("DataBase")->query("select config from components where link='{$id}'");
        if (Registry::get("DataBase")->numrows($query)) {
            $cfg = Registry::get("DataBase")->first($query);
            return unserialize($cfg->config);
        } else {
            echo 'Нет конфигурация';
        }
    }

    public function loadModuleCount($position)
    {
//        $db = Registry::get("DataBase");
//        $menuid =Registry::get("Menus")-> menuId();
//        $langID = Lang::getLangID();
        $modules = $this->loadModuleItems();
        if (!isset($modules[$position])) {
            return 0;
        }
        return sizeof($modules[$position]);

    }

    public function getModuleConfig($id)
    {
//        $cfg=array();
//        $sql = "SELECT * FROM modules WHERE id = '" . $id . "'";
//        $row = Registry::get("DataBase")->first($sql);
//
//        if ($row){
//            $cfg[$row->id]=array('params'=>$row->params);
//            print unserialize($row->params);
//             return unserialize($row->params);
//        }else{
//            echo 'Нет конфигурация';
//        }

        $sql = "select params " .
            "\n from modules where id='{$id}'";
        $query = Registry::get("DataBase")->query($sql);
        if (Registry::get("DataBase")->numrows($query)) {
            $cfg = Registry::get("DataBase")->first($query);
            return unserialize($cfg->params);
        } else {
            echo 'Нет конфигурация';
        }
    }

    public function renderComponent()
    {
        ob_start();
        if (isset(Core::$do)) {
            $component = htmlentities(Core::$do, ENT_QUOTES);
        }

        if (!$component) { return false; }

        if (!preg_match("/^([a-z0-9])+$/u", $component)){ self::error404(); }

        if (file_exists(PATH . '/components/' . $component . '/' . $component . '.php')) {
            include(PATH . '/components/' . $component . '/' . $component . '.php');
            call_user_func($component);
        }

        Registry::get("Page")->page_body=Core::getCallEvent('AFTER_COMPONENT_'.strtoupper($component), ob_get_clean());

        return;
    }

    public static function includeFile($file)
    {
        if (file_exists(PATH . '/theme/' . Registry::get("Config")->template . '/' . $file)) {
            include_once PATH . '/theme/' . Registry::get("Config")->template . '/' . $file;
            return true;
        } else {
            return false;
        }
    }

    public function getloadModule()
    {
        ob_start();
        (Core::$do && file_exists(Core::$do . ".php")) ? include(Core::$do . ".php") : include("main.php");
        $GLOBALS['cp_page_body'] = ob_get_clean();
    }

    public function getBody()
    {
        echo $GLOBALS['cp_page_body'];
        return;
    }

    public function getRow($table, $where, $what)
    {
        $sql = "SELECT * FROM " . (string )$table . " WHERE $where = '" . $what . "'";
        $row = Registry::get("DataBase")->first($sql);

        if ($row) {
            return $row;
        }
//        else {
//            if ($is_admin)
//                Filter::error("You have selected an Invalid Value - #" . $what, "Core::getRow()");
//        }
    }


    public function getComponentList($selected = 0)
    {
        $result = Registry::get("DataBase")->query("select * from components");
//        $sql="select * from components";
        $data = array();
        while ($items = Registry::get("DataBase")->fetch($result)) {
            $data[$items->id] = array(
                'id' => $items->id,
                'title' => $items->title,
                'description' => $items->description,
                'alt_class' => $items->alt_class,
                'show_title' => $items->show_title,
                'link' => $items->link,
                'ver' => $items->ver,
                'config' => $items->config,
                'jscode' => $items->jscode,
                'active' => $items->active
            );
        }
        return $data;
    }

    public function getComponentDropList($selected = 0)

    {
        $sql = "SELECT * FROM components"
            . "\n  ORDER BY title";
        $sqldata = Registry::get("DataBase")->fetch_all($sql);

        $data = '';
        $data .= "<option value=\"0\">--- No Module Assigned ---</option>\n";
        foreach ($sqldata as $val) {
            if ($val->link == $selected) {
                $data .= "<option selected=\"selected\" value=\"" . $val->id . "\">" . $val->title . "</option>\n";
            } else
                $data .= "<option value=\"" . $val->link . "\">" . $val->title . "</option>\n";
        }
        unset($val);
        $data .= "</select>";
        print $data;
    }

    public function getMenuLink($menuid,$linktype, $linkid)
    {
        $db = Registry::get("DataBase");
        $menulink = '';
        if ($linktype == 'link') {
            $menulink = $linkid;
        }
        if ($linktype == 'component') {
            $menulink = '/' . $linkid;
        }

        if ($linktype == 'content' || $linktype == 'category') {
            switch ($linktype) {
                case 'category':
                    $slug = $db->getValueById('slug', 'categories', $linkid);
                    $menulink = Content::getCategoryURL($menuid, $slug);
                    break;
                case 'content':
                    $seo = $db->getValueById("seo", 'content', $linkid);
                    $menulink = Content::getArticleURL($menuid, $seo);
                    break;
            }
        }

        return $menulink;

    }

    public function mp3list(){
        $db=Registry::get("DataBase");
        $sql="select* from audio where published=1";
        $row=$db->fetch_all($sql);
        $html="";
        foreach($row as $mp3){
            $html.= "<option value=".$mp3->file.">".$mp3->title."</option>";
        }
        return $html;
    }

    public function fclist(){
        $db=Registry::get("DataBase");
        $sql="SELECT fc.title as category,f.* FROM files f
                     LEFT JOIN files_cat fc on fc.id=f.cat_id";
        $row=$db->fetch_all($sql);
        $html="";
        foreach($row as $file){
            $html.= "<option value=".$file->file.">".$file->title."</option>";
        }
        return $html;
    }

    public function insertPanel()
    {


        echo '<table width="100%" border="0" cellspacing="0" cellpadding="8" class="proptable"><tr><td>';
        echo '<table width="800" border="0" cellspacing="0" cellpadding="2">';
        echo '<tr>';
        echo '<td width="10">';
        echo '<strong>Вставить:</strong> ';
        echo '</td>';
        echo '<td width="570">';
        echo '<select name="ins" id="ins" style="border:solid 1px black"  onChange="showIns()">
					<option value="material">ссылка на материал</option>
					<option value="materialdetail">материал на информация</option>
					<option value="photos">ссылка на фотографию</option>
					<option value="album">ссылка на фотоальбом</option>
					<option value="mp3">ссылка на Аудио (Mp3)</option>
					<option value="price">ссылка на категорию прайса</option>
					<option value="fcatalog">ссылка на каталог файлов</option>
					<option value="frm">форма для отправки</option>
					<option value="blank">форма без заголовка</option>
					<option value="include">внешний скрипт</option>
					<option value="filelink">ссылка "Скачать файл"</option>
					<option value="page">-- разрыв страницы --</option>
				  </select>';

        echo '</td>';

        echo '</tr>';

        echo '<tr>';

        echo '<td colspan="2">';


        echo '<div id="material" style="float:left">

				<strong>Материал:</strong> <select name="m"  style="border:1px solid black;width:500px;">'.Registry::get("Content")->getContentList().'</select>

			  </div>';
        echo '<div id="materialdetail" style="float:left">

				<strong>Материал:</strong> <select name="md"  style="border:1px solid black;width:500px;">'.Registry::get("Content")->getContentList().'</select>

			  </div>';

        echo '<div id="photos" style="float:left; display: none">

				<strong>Фото:</strong>

				<select name="f" style="border:solid 1px black"></select>

			  </div>';

        echo '<div id="mp3" style="float:left; display: none">

				<strong>Аудио файл:</strong>

				<select name="mp3" style="border:solid 1px black">'.$this->mp3list().'</select>

			  </div>';

        echo '<div id="album" style="float:left; display: none">
				<strong>Альбом:</strong> <select name="a" style="border:solid 1px black"></select>
			  </div>';

        echo '<div id="price" style="float:left; display: none">
				<strong>Категория:</strong> <select name="p" style="border:solid 1px black"></select>
			  </div>';

        echo '<div id="fcatalog" style="float:left; display: none">
				<strong>Категория:</strong> <select name="fc" style="border:solid 1px black">'.$this->fclist().'</select>
			  </div>';

        echo '<div id="frm" style="float:left; display: none">
				<strong>Форма:</strong> <select name="fm" style="border:solid 1px black"></select>
			  </div>';
        echo '<div id="blank" style="float:left; display: none">
				<strong>Бланк:</strong> <select name="b" style="border:solid 1px black"></select>
			  </div>';
        echo '<div id="include" style="float:left; display: none">
				<strong>Файл:</strong> /includes/myphp/<input name="i" type="text" size="30" value="myscript.php"/>
			  </div>';
        echo '<div id="filelink" style="float:left; display: none">
				<strong>Файл</strong> (начиная с "/"): <input name="fl" type="text" size="30" value="/files/myfile.rar"/>
			  </div>';
        echo ' <input type="button" value="Вставить" onClick="insertTag(document.addform.ins.options[document.addform.ins.selectedIndex].value);">';
        echo '</td>';


        echo '</tr>';

        echo '</table>';


        echo '</td></tr></table>';



        echo '<script type="text/javascript">showIns();</script>';
    }
    public function getModules()
    {

        if (!empty(Core::$post['filter'])) {
            $where = "where m.title like '%" . Core::$post['filter'] . "%'";
        }
        $result = Registry::get("DataBase")->query("SELECT m.*,l.name  FROM modules m left join languages l on l.flag=m.lang"
            . "\n {$where} ORDER BY m.ordering");

        $counter = Registry::get("DataBase")->numrows($result);
        $pager = Registry::get("Paginator");
        $pager->items_total = $counter;
        $pager->default_ipp = 15;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }

        $sql = "SELECT m.*,(CASE m.lang WHEN '*' then 'Всё' else l.name END ) as name FROM modules m left JOIN languages l on l.flag=m.lang"
            . "\n {$where} ORDER BY m.ordering" . $pager->limit;
        $row = Registry::get("DataBase")->fetch_all($sql);

        return ($row) ? $row : 0;

    }

    public function getModuleList($sel = false)
    {
        $sql = "SELECT * FROM modules"
            . "\n  ORDER BY title";
        $sqldata = Registry::get("DataBase")->fetch_all($sql);

        $data = '';
        $data .= "<option value=\"0\">--- No Module Assigned ---</option>\n";
        foreach ($sqldata as $val) {
            if ($val->id == $sel) {
                $data .= "<option selected=\"selected\" value=\"" . $val->id . "\">" . $val->title . "</option>\n";
            } else
                $data .= "<option value=\"" . $val->id . "\">" . $val->title . "</option>\n";
        }
        unset($val);
        $data .= "</select>";
        print $data;
    }

    public function proccessModule($mdata)
    {
        $data = array();
        $files = $mdata['filename'] . '.php';
        $dir = PATH . '/modules/' . $mdata['filename'] . '/';
        if (!empty($modulename)) {
            if (is_dir($dir)) {
                echo 'Bor';
            } else {
                if (chmod(PATH . '/modules', 0777)) {
                    mkdir($dir, 0777, true);
                    if (!is_file($dir . $files)) {
                        $fp = fopen($dir . $files, 'w');
                        fwrite($fp, trim($mdata['sourcecode']));
                        fclose($fp);
                    }
                } else {
                    echo 'Ruhsat yoq';
                }
            }
        }

        $sql1 = "SELECT MAX(ordering) max_o FROM modules ";
        $row = Registry::get("DataBase")->first($sql1);
        $maxorder = $row->max_o + 1;


        $data = array(
            'title' => Registry::get("DataBase")->escape($mdata['title']),
            'position' => $mdata['position'],
            'lang' => $mdata['lang'],
            'active' => $mdata['active'],
            'showtitle' => $mdata['showtitle'],
            'modgroup' => $mdata['modgroup'],
            'jscode' => $mdata['jscode'],
            'css_prefix' => $mdata['css_prefix'],
            'tpl' => $mdata['tpl'],
            'created' => 'now()',
            'hasconfig' => '1',
            'system' => '0'

        );

        if (!Core::$id) {
            $data['ordering'] = $maxorder;
        }

        if (isset($mdata['content'])){
            $data['module']=$mdata['content'];
        }


        if ($mdata['operate'] == 'user') {
            $is_user = 2;
            $is_external = 1;
            $data['is_external']=$is_external;
            $data['is_user']=$is_user;
            $data['module']=$mdata['filename'];
        }

        if ($mdata['operate'] == 'html') {
            $is_user = 1;
            $is_external = 0;
            $data['is_external']=$is_external;
            $data['is_user']=$is_user;
            $data['module']=$mdata['content'];
        }

        if ($mdata['operate'] == 'clone') {
            $is_user = 3;
            $is_external = 1;
            $mod_id = (int)$_REQUEST['clone_id'];
            $csql = "select * from modules where id=$mod_id LIMIT 1";
            $orginal = Registry::get("DataBase")->first($csql);
            $data['title']=$orginal->title;
            $data['module']=$orginal->module;
            $data['is_external']=$is_external;
            $data['is_user']=$is_user;
        }

        (Core::$id) ? Registry::get("DataBase")->update("modules", $data, "id=" . Core::$id) : Registry::get("DataBase")->insert("modules", $data);
        $lastq = "SELECT LAST_INSERT_ID() as lastid FROM modules";
        $lastrow = Registry::get("DataBase")->first($lastq);
        $lastid = $lastrow->lastid;

        if (Core::$post['show_all']) {
            Registry::get("DataBase")->insert("modules_menu", array('menu_id' => '0', 'mod_id' => $lastid ? $lastid : Core::$id, 'position' => $mdata['position']));
        } else {

            $showin = $mdata['showin'];
            $showpos = $mdata['showpos'];
            if (sizeof($showin) > 0) {
                foreach ($showin as $key => $value) {
                    Registry::get("DataBase")->insert("modules_menu", array('menu_id' => $value, 'mod_id' => $lastid ? $lastid : Core::$id, 'position' => $showpos[$value]));
                }
            }
        }


    }
    public function loadClass($component){
        if (file_exists(PATH.'/components/'.preg_replace('/[^a-z_]/iu', '', $component).'/model_'.$component.'.php')){
            include PATH.'/components/'.preg_replace('/[^a-z_]/iu', '', $component).'/model_'.$component.'.php';
            return true;
        } else {
            return false;
        }
//        include('components/'.preg_replace('/[^a-z_]/iu', '', $component).'/model_superslider.php');
    }

    public function loadModulePosition()
    {
        $post = array();
        $conf = Registry::get("Config");
        $file = PATH . "/theme/" . $conf->template . '/position.txt';

        if (file_exists($file)) {
            $f = fopen($file, 'r');
            while (!feof($f)) {
                $data = fgets($f);
                $data = str_replace("\n", '', $data);
                $data = str_replace("\r", '', $data);
//            if (!strstr($data,'#') && strlen($data)>1){
//                $post[]=$data;
//            }
                $post[] = $data;
            }

            fclose($f);
            return $post;
        } else {
            echo 'Нет файла';
        }
    }

    public static function loadModuleTemplate(){
        $templates  = array();
        $conf = Registry::get("Config");
        $file = is_dir(PATH . "/theme/" . $conf->template . '/modules/')?PATH . "/theme/" . $conf->template . '/modules/':'';
        $pdir       = opendir($file);

        while ($nextfile = readdir($pdir)){
            if (
                ($nextfile != '.')  &&
                ($nextfile != '..') &&
                !is_dir($file.'/'.$nextfile) &&
                ($nextfile!='.svn') &&
                (mb_substr($nextfile, 0, 6)=='module')
            ) {
                $templates[$nextfile] = $nextfile;
            }
        }

        if (!sizeof($templates)){ return false; }

        return $templates;
    }

    public static function loadComponentTemplate(){
        $templates  = array();
        $conf = Registry::get("Config");
        $file = is_dir(PATH . "/theme/" . $conf->template . '/components/')?PATH . "/theme/" . $conf->template . '/components/':'';
        $pdir       = opendir($file);

        while ($nextfile = readdir($pdir)){
            if (
                ($nextfile != '.')  &&
                ($nextfile != '..') &&
                !is_dir($file.'/'.$nextfile) &&
                ($nextfile!='.svn') &&
                (mb_substr($nextfile, 0, 4)=='com_')
            ) {
                $templates[$nextfile] = $nextfile;
            }
        }

        if (!sizeof($templates)){ return false; }

        return $templates;
    }

    public function loadEditor($name, $width = "100%", $height = "450", $text = '')
    {
        $editor=self::getCallEvent("INSERT_WYSIWYG",array(
            'name'=>$name,
            'text'=>$text,
            'height'=>$height,
            'width'=>$width
        ));


        if (!is_array($editor)){ echo $editor; return; }

        echo '<p>
                <div>Визуальный редактор не найден либо не включен.</div>
                <div>Если редактор установлен, включите его в админке (меню <em>Дополнения</em> &rarr; <em>Плагины</em>).</div>
              </p>';

    }


    public static function dodate($format, $date)
    {

        return utf8_encode(strftime($format, strtotime($date)));
    }

    public function randName($i = 6)
    {
        $code = '';
        for ($x = 0; $x < $i; $x++) {
            $code .= '-' . substr(strtoupper(sha1(rand(0, 999999999999999))), 2, 6);
        }
        $code = substr($code, 1);
        return $code;
    }


    function cmsRusDate($datestr)
    {
        $datestr = str_replace('January', 'Январь', $datestr);
        $datestr = str_replace('February', 'Февраль', $datestr);
        $datestr = str_replace('March', 'Март', $datestr);
        $datestr = str_replace('April', 'Апрель', $datestr);
        $datestr = str_replace('May', 'Май', $datestr);
        $datestr = str_replace('June', 'Июнь', $datestr);
        $datestr = str_replace('July', 'Июль', $datestr);
        $datestr = str_replace('August', 'Август', $datestr);
        $datestr = str_replace('September', 'Сентябрь', $datestr);
        $datestr = str_replace('October', 'Октябрь', $datestr);
        $datestr = str_replace('November', 'Ноябрь', $datestr);
        $datestr = str_replace('December', 'Декабрь', $datestr);
        return $datestr;
    }

    function cmsRusDateShort($datestr)
    {
        $datestr = str_replace('January', 'Январь', $datestr);
        $datestr = str_replace('February', 'Февраль', $datestr);
        $datestr = str_replace('March', 'Март', $datestr);
        $datestr = str_replace('April', 'Апрель', $datestr);
        $datestr = str_replace('May', 'Май', $datestr);
        $datestr = str_replace('June', 'Июнь', $datestr);
        $datestr = str_replace('July', 'Июль', $datestr);
        $datestr = str_replace('August', 'Август', $datestr);
        $datestr = str_replace('September', 'Сентябрь', $datestr);
        $datestr = str_replace('October', 'Октябрь', $datestr);
        $datestr = str_replace('November', 'Ноябрь', $datestr);
        $datestr = str_replace('December', 'Декабрь', $datestr);

        //заменяем дни недели
        $datestr = str_replace('Mon', 'Пн', $datestr);
        $datestr = str_replace('Tue', 'Вт', $datestr);
        $datestr = str_replace('Wed', 'Ср', $datestr);
        $datestr = str_replace('Thu', 'Чт', $datestr);
        $datestr = str_replace('Fri', 'Пт', $datestr);
        $datestr = str_replace('Sat', 'Сб', $datestr);
        $datestr = str_replace('Sun', 'Вс', $datestr);

        // Замена чисел 01 02 на 1 2
        $day_int = array(
            '01', '02', '03',
            '04', '05', '06',
            '07', '08', '09'

        );
        $day_norm = array(
            '1', '2', '3',
            '4', '5', '6',
            '7', '8', '9'

        );

        $datestr = str_replace($day_int, $day_norm, $datestr);

        return $datestr;
    }

    public static function getShortDate($selected = false)
    {

        $format = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? "%#d" : "%e";

        $arr = array(
            '%m-%d-%Y' => strftime('%m-%d-%Y') . ' (MM-DD-YYYY)',
            $format . '-%m-%Y' => strftime($format . '-%m-%Y') . ' (D-MM-YYYY)',
            '%m-' . $format . '-%y' => strftime('%m-' . $format . '-%y') . ' (MM-D-YY)',
            $format . '-%m-%y' => strftime($format . '-%m-%y') . ' (D-MMM-YY)',
            '%d %b %Y' => strftime('%d %b %Y')
        );

        $shortdate = '';
        foreach ($arr as $key => $val) {
            if ($key == $selected) {
                $shortdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
            } else
                $shortdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
        }
        unset($val);
        return $shortdate;
    }

    public static function getLongDate($selected = false)
    {
        $format = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? "%#d" : "%e";
        $arr = array(
            '%B %d, %Y %I:%M %p' => strftime('%B %d, %Y %I:%M %p'),
            '%d %B %Y %I:%M %p' => strftime('%d %B %Y %I:%M %p'),
            '%B %d, %Y' => strftime('%B %d, %Y'),
            '%d %B, %Y' => strftime('%d %B, %Y'),
            '%A %d %B %Y' => strftime('%A %d %B %Y'),
            '%A %d %B %Y %H:%M' => strftime('%A %d %B %Y %H:%M'),
            '%a %d, %B' => strftime('%a %d, %B'));

        $html = '';
        foreach ($arr as $key => $val) {
            if ($key == $selected) {
                $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
            } else
                $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
        }
        unset($val);
        return $html;
    }

    public static function monthList($list = true, $long = true, $selected = false)
    {
        $selected = is_null(get('month')) ? strftime('%m') : get('month');

        if ($long) {
            $arr = array(
                '01' => 'January',
                '02' => 'February',
                '03' => 'March',
                '04' => 'April',
                '05' => 'May',
                '06' => 'June',
                '07' => 'July',
                '08' => 'August',
                '09' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December');
        } else {
            $arr = array(
                '01' => 'Jan',
                '02' => 'Feb',
                '03' => 'Mar',
                '04' => 'Apr',
                '05' => 'May',
                '06' => 'Jun',
                '07' => 'Jul',
                '08' => 'Aug',
                '09' => 'Sep',
                '10' => 'Oct',
                '11' => 'Nov',
                '12' => 'Dec');
        }
        $html = '';
        if ($list) {
            foreach ($arr as $key => $val) {
                $html .= "<option value=\"$key\"";
                $html .= ($key == $selected) ? ' selected="selected"' : '';
                $html .= ">$val</option>\n";
            }
        } else {
            $html .= '"' . implode('","', $arr) . '"';
        }
        unset($val);
        return $html;
    }

    public static function getTimeFormat($selected = false)
    {
        $arr = array(
            '%I:%M %p' => strftime('%I:%M %p'),
            '%I:%M %P' => strftime('%I:%M %P'),
            '%H:%M' => strftime('%H:%M'),
            '%k' => strftime('%k'),
        );

        $longdate = '';
        foreach ($arr as $key => $val) {
            if ($key == $selected) {
                $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
            } else
                $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
        }
        unset($val);
        return $longdate;
    }


    public static function weekList($list = true, $long = true, $selected = false)
    {
        if ($long) {
            $arr = array(
                '1' => Lang::$word->_SUNDAY,
                '2' => Lang::$word->_MONDAY,
                '3' => Lang::$word->_TUESDAY,
                '4' => Lang::$word->_WEDNESDAY,
                '5' => Lang::$word->_THURSDAY,
                '6' => Lang::$word->_FRIDAY,
                '7' => Lang::$word->_SATURDAY);
        } else {
            $arr = array(
                '1' => Lang::$word->_SUN,
                '2' => Lang::$word->_MON,
                '3' => Lang::$word->_TUE,
                '4' => Lang::$word->_WED,
                '5' => Lang::$word->_THU,
                '6' => Lang::$word->_FRI,
                '7' => Lang::$word->_SAT);
        }

        $html = '';
        if ($list) {
            foreach ($arr as $key => $val) {
                $html .= "<option value=\"$key\"";
                $html .= ($key == $selected) ? ' selected="selected"' : '';
                $html .= ">$val</option>\n";
            }
        } else {
            $html .= '"' . implode('","', $arr) . '"';
        }

        unset($val);
        return $html;
    }

    function yearList($start_year, $end_year)
    {
        $selected = is_null(get('year')) ? date('Y') : get('year');
        $r = range($start_year, $end_year);

        $select = '';
        foreach ($r as $year) {
            $select .= "<option value=\"$year\"";
            $select .= ($year == $selected) ? ' selected="selected"' : '';
            $select .= ">$year</option>\n";
        }
        return $select;
    }

    public function getSize($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function loadPluginConfig($plugin_name)
    {
        $config = array();
        foreach ($this->plugins as $plugin) {
            if ($plugin->plug_name == $plugin_name) {
                unserialize($plugin->config);
                $config = unserialize($plugin->config);
            }
        }

        return $config;

    }

    public static function getCallEvent($event,$item){

        $plugs = self::getInstance()->getEventPlugins($event);
        if (!$plugs) { return $item; }
        foreach($plugs as $plugin_name){
            $plugin = self::loadPlugin($plugin_name);
            if ($plugin!==false){
                $item = $plugin->execute($event, $item);
                self::unloadPlugin($plugin);

                if(isset($plugin->info['type'])){
                    if (in_array($plugin->info['type'],self::getInstance()->single_run_plugins)) {
                        return $item;
                    }
                }
            }
        }
        return $item;
    }

    public function getEventPlugins($event)
    {
        $plugins_list = array();

        foreach ($this->plugins as $plugin) {
            if($plugin->event == $event){
                $plugins_list[] = $plugin->plug_name;
            }
        }
        return $plugins_list;
    }

    public function getPluginAll()
    {
        if ($this->plugins && is_array($this->plugins)) {
            return $this->plugins;
        }
        $this->plugins = Registry::get("DataBase")->get_table("plugins p,plugins_event e", "p.active = 1 and e.plugin_id = p.id ", 'p.id, p.plug_name, p.config,e.event');
        if (!$this->plugins) {
            $this->plugins = array();
        }
        return $this->plugins;
    }


    public static function loadPlugin($plugin) {

        $plugin_file =PATH. '/plugins/'.$plugin.'/plugin.php';

        if (file_exists($plugin_file)){
            include_once($plugin_file);
            $plugin_obj = new $plugin();

            return $plugin_obj;
        }
        return false;
    }
    public static function unloadPlugin($plugin_obj) {
        unset($plugin_obj);
        return true;
    }

    public static function getFilters(){
        if (isset(self::$filters)){return  self::$filters;}
        $inDB=Registry::get("DataBase");
        $sql="select * from filters WHERE published = 1 ORDER BY id ASC";
        $result=$inDB->query($sql);
        if ($inDB->numrows($result)){
            while ($f=$inDB->fetch($result,true)){
                $filters[$f['id']]=$f;
            }
        }
        self::$filters=$filters;
        return $filters;

    }

    public function processFilters($content){


        $filters = self::getFilters();
        if ($filters){
            foreach($filters as $id=>$_filter){
                if(file_exists(PATH.'/filters/'. $_filter['link'].'/filter.php')){
                    include_once(PATH.'/filters/'. $_filter['link'].'/filter.php');
                    $_filter['link']($content);
                }
            }
        }
        return $content;
    }

    public static function error404(){

//        self::loadClass('page');

        header("HTTP/1.0 404 Not Found");
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");

        if (file_exists(THEMEURL.'error404.php')){
            include(THEMEURL.'error404.php');
            echo '<h1>404</h1>';
        }


        self::halt();

    }

    public static function halt($message=''){
        die((string)$message);
    }

    public static function cpHead(){
        if ($GLOBALS['cp_page_title']){
            echo '<title>'.$GLOBALS['cp_page_title'].' - Панель управления </title>';
        } else {
            echo '<title>Панель управления</title>';
        }

//        echo '<script language="JavaScript" type="text/javascript" src="js/common.js"></script>' ."\n";
        if (@$GLOBALS['cp_jquery']){
            echo '<script language="JavaScript" type="text/javascript" src="'.$GLOBALS['cp_jquery'].'"></script>' ."\n";
        } else {
            echo '<script language="JavaScript" type="text/javascript" src="/includes/jquery/jquery.js"></script>' ."\n";
        }

        foreach($GLOBALS['cp_page_head'] as $key=>$value) {
            echo $GLOBALS['cp_page_head'][$key] ."\n";
            unset ($GLOBALS['cp_page_head'][$key]);
        }

        return;
    }

    public static function getBackURL($is_request = true){
        $back = '/';
        if(isset($_REQUEST['back']) && $is_request){
            $back = $_REQUEST['back'];
        } elseif(!empty($_SERVER['HTTP_REFERER'])) {
            $refer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            if($refer_host == $_SERVER['HTTP_HOST']){
                $back = strip_tags($_SERVER['HTTP_REFERER']);
            }

        }
        return $back;
    }

    public static function redirectBack(){
        self::redirect(self::getBackURL(false));
    }

    public static function redirect($url, $code='303'){
        if ($code == '301'){
            header('HTTP/1.1 301 Moved Permanently');
        } else {
            header('HTTP/1.1 303 See Other');
        }
        header('Location:'.$url);
        self::halt();
    }

}

?>
