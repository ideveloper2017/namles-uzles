<?php

final class Lang
{
    const langdir = "lang/";
    public static $language;
    public static $word = array();
    public static $lang;

    public static function fetchLanguage()
    {
        $lang_array = '';
        $directory = BASEPATH . Lang::langdir;
        if (!is_dir($directory)) {
            return false;
        } else {
            $lang_array = glob($directory . "*" . GLOB_ONLYDIR);
            $lang_array = str_replace($directory, "", $lang_array);
        }
        return $lang_array;
    }

    public static function getLangID()
    {
        $db = Registry::get("DataBase");
        $config = Registry::get("Config");
        if (isset($_REQUEST['lang'])) {
            $lang = $_REQUEST['lang'];
            $_SESSION['lang'] = $lang;
        } elseif (isset($_SESSION['lang'])) {
            $lang = $_SESSION['lang'];
        }
        else {
            $lang = $config->lang;
        }


        $rowsID = $db->first("select * from languages where flag='{$lang}'");
//        echo $lang;

        return $rowsID->flag;
    }

    public static function getLangList($selected = false)
    {
        global $db, $config;
        $rowsID = $db->fetch_all("select * from languages ");
        print "<option value='*'>Все</option>";
        foreach ($rowsID as $row) {
            $sel = ($row->flag == $selected) ? " selected=\"selected\"" : "";
            print "<option value=\"" . $row->flag . "\"" . $sel . ">";
            print $row->name . "</option>\n";
        }
    }

    public static function getLanguageList(){
        global $db, $config;
        $lang=array();
        $rowsID = $db->fetch_all("select * from languages ");
        foreach ($rowsID as $row) {
            $lang[]=$row;
        }
        return $lang;
    }
}

?>