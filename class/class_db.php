<?php
class DataBase {
    public $link;
    public static $instance = null;
    private $server = "";
    private $user = "";
    private $password = "";
    private $database = "";
    private $error = "";
    private $errrno = 0;
    private $charsets = "";
    protected $affected_row = 0;
    protected $query_counter = 0;
    protected $link_id = 0;
    protected $query_id = 0;
    protected $query_show;

    public function __construct($server, $user, $password, $database, $charsets){
        $this->server = $server;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->charsets = $charsets;
    }

    public function connect(){
        $this->link = mysqli_connect($this->server, $this->user,$this->password);
        if (!$this->link) {
            header("Location: /html/error.html");
            exit;
        }
        if (!mysqli_select_db($this->link,$this->database)) {
            $this->error();
        }
        mysqli_set_charset($this->link,$this->charsets);
//        $this->setTimezone();
    }


    public function query($sql)
    {
        if (trim($sql != "")) {
            $this->query_counter++;
            $this->query_show .= stripslashes($sql) . "<hr size='1' />";
            $this->query_id = mysqli_query($this->link, $sql);
//            $this->last_query = $sql . '<br />';
        }
        if (!$this->query_id)
//            $this->error("mySQL Error on Query : " . $sql);
            return $this->query_id;
    }


    public function setTimezone(){
        $config=Registry::get("Config");
        // Пробуем установить временную зону по имени ...
        $is_tz_set = $this->query("SET `time_zone` = '%s'", $config->time_zone, true);

        // ... если по имени не удалось, устанавливаем по смещению
        if (!$is_tz_set) { $this->query("SET `time_zone` = '%s'", date('P')); }
    }
    public function first($string, $type = false)
    {
        $query_id = $this->query($string);
        $record = $this->fetch($query_id, $type);
        $this->free($query_id);
        return $record;
    }


    public function fetch($query_id, $type = false)
    {
        if ($query_id)
            $this->query_id = $query_id;

        if (isset($this->query_id)) {
            $record = ($type) ? mysqli_fetch_array($this->query_id, MYSQLI_ASSOC) : mysqli_fetch_object($this->query_id);
        } else
            $this->error("Invalid query_id: <b>" . $this->query_id . "</b>. Records could not be fetched.");

        return $record;
    }

    public function fetch_array($query_id)
    {
        if ($query_id)
            $this->query_id = $query_id;

        if (isset($this->query_id)) {
            $record =  mysqli_fetch_array($this->query_id, MYSQLI_ASSOC);
        } else
            $this->error("Invalid query_id: <b>" . $this->query_id . "</b>. Records could not be fetched.");

        return $record;
    }



    public function fetch_all($sql, $type = false)
    {
        $record = array();
        $query_id = $this->query($sql);
        while ($row = $this->fetch($query_id, $type)) :
            $record[] = $row;
        endwhile;
        $this->free($query_id);
        return $record;
    }



    private function free($query_id)
    {
        if ($query_id)
            $this->query_id = $query_id;

        return mysqli_free_result($this->query_id);
    }


    public function close()
    {
        return mysqli_close();
    }



    public function numrows($query_id)
    {
        if ($query_id)
            $this->query_id = $query_id;

        $this->num_rows = mysqli_num_rows($this->query_id);
        return $this->num_rows;
    }

    public function fetchrow($query_id)
    {
        if ($query_id)
            $this->query_id = $query_id;

        $this->fetch_row = mysqli_fetch_row($this->query_id);
        return $this->fetch_row;
    }

    public function insert($table = null, $data)
    {
        if ($table === null or empty($data) or !is_array($data)) {
            $this->error("Invalid array for table: <b>".$table."</b>.");
            return false;
        }
        $q = "INSERT INTO `" . $table . "` ";
        $v = '';
        $k = '';

        foreach ($data as $key => $val) :
            $k .= "`$key`, ";
            if (@strtolower($val) == 'null')
                $v .= "NULL, ";
            elseif (@strtolower($val) == 'now()')
                $v .= "NOW(), ";
            else
                $v .= "'" . $this->escape($val) . "', ";
        endforeach;

        $q .= "(" . rtrim($k, ', ') . ") VALUES (" . rtrim($v, ', ') . ");";

        if ($this->query($q)) {
            return $this->insertid();
        } else
            return false;
    }


    public function getValueById($what, $table, $id)
    {
        $sql = "SELECT $what FROM $table WHERE id = $id";
        $row = $this->first($sql);
        return ($row) ? $row->$what : '';
    }

    public function getFieldById($what,$table,$where){
        $sql = "SELECT $what FROM $table WHERE $where";
//        echo $sql;
        $row = $this->first($sql);
        return ($row) ? $row->$what : '';
    }

    public function getFieldsById($what,$table,$where){
        $sql = "SELECT $what FROM $table WHERE $where";
//        echo $sql;
        $row = $this->first($sql);
//        print_r($row);
        return ($row) ? $row : '';
    }

    public function getValuesById($what, $table, $id)
    {
        $sql = "SELECT $what FROM $table WHERE id = $id";

        $row = $this->first($sql);
        return ($row) ? $row : 0;
    }

    public function rows_count($table, $where, $limit=0){

        $sql = "SELECT 1 FROM $table WHERE $where";
        if ($limit) { $sql .= " LIMIT ".(int)$limit; }
        $result = $this->query($sql);

        return $this->numrows($result);

    }

    public function get_table($table, $where='', $fields='*')
    {

        $list = array();

        $sql = "SELECT $fields FROM $table";
        if ($where) {
            $sql .= ' WHERE ' . $where;
        }
//        echo $sql;
        $result = $this->query($sql);

        if ($this->numrows($result)) {
            while ($data = $this->fetch($result)) {
                $list[] = $data;

            }

            return $list;

        } else {
            return false;
        }

    }


    public function update($table = null, $data, $where = '1')
    {
        if ($table === null or empty($data) or !is_array($data)) {
            $this->error("Invalid array for table: <b>" . $table . "</b>.");
            return false;
        }

        $q = "UPDATE `" . $table . "` SET ";
        foreach ($data as $key => $val) :
            if (strtolower($val) == 'null')
                $q .= "`$key` = NULL, ";
            elseif (strtolower($val) == 'now()')
                $q .= "`$key` = NOW(), ";
            elseif (strtolower($val) == 'default()')
                $q .= "`$key` = DEFAULT($val), ";
            elseif(preg_match("/^inc\((\-?[\d\.]+)\)$/i",$val,$m))
                $q.= "`$key` = `$key` + $m[1], ";
            else
                $q .= "`$key`='" . $this->escape($val) . "', ";

        endforeach;

        $q = rtrim($q, ', ') . ' WHERE ' . $where . ';';
//        echo $q;
        return $this->query($q);
    }

    public function delete($table, $where = '')
    {
        $q = !$where ? 'DELETE FROM ' . $table : 'DELETE FROM ' . $table . ' WHERE ' . $where;

        return $this->query($q);
    }

    public function insertid()
    {
        return mysqli_insert_id($this->link);
    }

    public function affected() {
        return mysqli_affected_rows($this->link);
    }

    public function escape($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $value) :
                $string[$key] = $this->escape_($value);
            endforeach;
        } else
            $string = $this->escape_($string);

        return $string;
    }

    private function escape_($string)
    {
        $string = strtr($string, array(
            '\r\n' => "",
            '\r' => "",
            '\n' => ""));
        $string = html_entity_decode(stripcslashes($string), ENT_QUOTES, 'UTF-8');
        $string = str_replace('<br>', '<br />', $string);
        $string = str_replace('"\"', '', $string);
        return mysqli_real_escape_string($this->link,$string);
    }




    public function error($msg = '')
    {
        if (!is_resource($this->link)) {
            $this->error_desc = mysqli_error($this->link);
            $this->error_no = mysqli_errno($this->link);
        } else {
            $this->error_desc = mysqli_error($this->link);
            $this->error_no = mysqli_errno($this->link);
        }

        $the_error = "<div style=\"background-color:#FFF; border: 3px solid #999; padding:10px\">";
        $the_error .= "<b>mySQL WARNING!</b><br />";
        $the_error .= "DB Error: $msg <br /> More Information: <br />";
        $the_error .= "<ul>";
        $the_error .= "<li> Mysql Error : " . $this->error_no . "</li>";
        $the_error .= "<li> Mysql Error no # : " . $this->error_desc . "</li>";
        $the_error .= "<li> Date : " . date("F j, Y, g:i a") . "</li>";
        $the_error .= "<li> Referer: " . isset($_SERVER['HTTP_REFERER']) . "</li>";
        $the_error .= "<li> Script: " . $_SERVER['REQUEST_URI'] . "</li>";
        $the_error .= '</ul>';
        $the_error .= '</div>';
        if (DEBUG)
            echo $the_error;
        die();
    }

    public function getLink()
    {
        return $this->link_id;
    }

    public function getDB()
    {
        return $this->database;
    }


    public function getServer()
    {
        return $this->server;
    }
}

?>