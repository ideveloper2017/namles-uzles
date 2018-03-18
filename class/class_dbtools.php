<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 28.12.2015
 * Time: 19:42
 */
define('nl', "\r\n");
class dbTools{
    private $tables = array();
    const suffix = "d-M-Y_H-i-s";
    const nl = "\r\n";

    public function doBackup($fname = '', $gzip = true)
    {
        if (!($sql = $this->fetch())) {
            return false;
        } else {
            $fname =  '../backups/';
            $fname .= date(self::suffix);
            $fname .= ($gzip ? '.sql.gz' : '.sql');

            $this->save($fname, $sql, $gzip);
            echo $fname;
//            $ext = ($gzip ? '.sql.gz' : '.sql');
//            $data['backup'] = date(self::suffix) . $ext;
//            Registry::get("DataBase")->update("content", $data);

//            if (Registry::get("DataBase")->affected())
//                redirect_to("index.php?do=backup&backupok=1");
        }
    }

    public function doRestore($fname)
    {
        $link = Registry::get("DataBase")->getLink();
        $filename = '/backups/' . trim($fname);
        $templine = '';
        $lines = file($filename);
        foreach ($lines as $line_num => $line) {
            if (substr($line, 0, 2) != '--' && $line != '') {
                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    if (!Registry::get("DataBase")->query($templine)) {
                        Core::msgError(mysqli_errno($link) . " " . mysqli_error($link) . "' during the following query:
						  <div>{$templine}</div>");
                    }
                    $templine = '';
                }
            }
        }
        return true;
    }

    private function getTables()
    {
        $value = array();
        if (!($result = Registry::get("DataBase")->query('SHOW TABLES'))) {
            return false;
        }
        while ($row = Registry::get("DataBase")->fetchrow($result)) {
            if (empty($this->tables) or in_array($row[0], $this->tables)) {
                $value[] = $row[0];
            }
        }
        if (!sizeof($value)) {
            Core::msgError("<span>Error!</span>No tables found in DataBase");
            return false;
        }
        return $value;
    }

    private function dumpTable($table)
    {
        $damp = '';
        Registry::get("DataBase")->query('LOCK TABLES ' . $table . ' WRITE');

        $damp .= '-- --------------------------------------------------' . self::nl;
        $damp .= '# -- Table structure for table `' . $table . '`' . self::nl;
        $damp .= '-- --------------------------------------------------' . self::nl;
        $damp .= 'DROP TABLE IF EXISTS `' . $table . '`;' . self::nl;

        if (!($result = Registry::get("DataBase")->query('SHOW CREATE TABLE ' . $table))) {
            return false;
        }
        $row = Registry::get("DataBase")->fetch($result, true);
        $damp .= str_replace("\n", self::nl, $row['Create Table']) . ';';
        $damp .= self::nl . self::nl;
        $damp .= '-- --------------------------------------------------' . self::nl;
        $damp .= '# Dumping data for table `' . $table . '`' . self::nl;
        $damp .= '-- --------------------------------------------------' . self::nl . self::nl;
        $damp .= $this->insert($table);
        $damp .= self::nl . self::nl;
        Registry::get("DataBase")->query('UNLOCK TABLES');
        return $damp;
    }

    private function insert($table)
    {
        $output = '';
        if (!$query = Registry::get("DataBase")->fetch_all("SELECT * FROM `" . $table . "`", true)) {
            return false;
        }
        foreach ($query as $result) {
            $fields = '';

            foreach (array_keys($result) as $value) {
                $fields .= '`' . $value . '`, ';
            }
            $values = '';

            foreach (array_values($result) as $value) {
                $value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
                $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
                $value = str_replace('\\', '\\\\', $value);
                $value = str_replace('\'', '\\\'', $value);
                $value = str_replace('\\\n', '\n', $value);
                $value = str_replace('\\\r', '\r', $value);
                $value = str_replace('\\\t', '\t', $value);

                $values .= '\'' . $value . '\', ';
            }

            $output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
        }
        return $output;
    }

    private function fetch()
    {
        $dump = '';

        $DataBase = Registry::get("DataBase")->getDB();
        $server = Registry::get("DataBase")->getServer();
        $link = Registry::get("DataBase")->getLink();

        $dump .= '-- --------------------------------------------------------------------------------' . self::nl;
        $dump .= '-- ' . self::nl;
        $dump .= '-- @version: ' . $DataBase . '.sql ' . date('M j, Y') . ' ' . date('H:i') . ' gewa' . self::nl;
        $dump .= '-- @package wojo:cms' . self::nl;
        $dump .= '-- @author wojoscripts.com.' . self::nl;
        $dump .= '-- @copyright 2014' . self::nl;
        $dump .= '-- ' . self::nl;
        $dump .= '-- --------------------------------------------------------------------------------' . self::nl;
        $dump .= '-- Host: ' . $server . self::nl;
        $dump .= '-- DataBase: ' . $DataBase . self::nl;
        $dump .= '-- Time: ' . date('M j, Y') . '-' . date('H:i') . self::nl;
        $dump .= '-- MySQL version: ' . mysqli_get_server_info($link) . self::nl;
        $dump .= '-- PHP version: ' . phpversion() . self::nl;
        $dump .= '-- --------------------------------------------------------------------------------' . self::nl . self::nl;

        $DataBase = Registry::get("DataBase")->getDB();
        if (!empty($DataBase)) {
            $dump .= '#' . self::nl;
            $dump .= '# DataBase: `' . $DataBase . '`' . self::nl;
        }
        $dump .= '#' . self::nl . self::nl . self::nl;

        if (!($tables = $this->getTables())) {
            return false;
        }
        foreach ($tables as $table) {
            if (!($table_dump = $this->dumpTable($table))) {
                Core::msgError("mySQL Error : ");
                return false;
            }
            $dump .= $table_dump;
        }
        return $dump;
    }


    private function save($fname, $sql, $gzip)
    {
        if ($gzip) {
            if (!($zf = gzopen($fname, 'w9'))) {
                Core::msgError("Can not write to " . $fname);
                return false;
            }
            gzwrite($zf, $sql);
            gzclose($zf);
        } else {
            if (!($f = fopen($fname, 'w'))) {
                Core::msgError("Can not write to " . $fname);
                return false;
            }
            fwrite($f, $sql);
            fclose($f);
        }
        return true;
    }

  
    private function showTables($dbtable)
    {
        $DataBase = Registry::get("DataBase")->getDB();

        $sql = "SHOW TABLES FROM `" . $DataBase . "`";
        $result = Registry::get("DataBase")->query($sql);
        $show = '';

        while ($row = Registry::get("DataBase")->fetchrow($result)):
            $selected = ($row[0] == $dbtable) ? " selected=\"selected\"" : "";
            $show .= "<option value=\"" . $row[0] . "\"" . $selected . ">" . $row[0] . "</option>\n";
        endwhile;

        Registry::get("DataBase")->free($result);

        return ($show);
    }

    public static function optimizeDb()
    {
        $display = '';
        $display .= '<table class="wojo table">';
        $display .= '<thead><tr>';
        $display .= '<th colspan="2">' . Lang::$word->_SYS_DBREPAIRING . '... </th>';
        $display .= '<th colspan="2">' . Lang::$word->_SYS_DBOPTIMIZING . '... </th>';
        $display .= '</tr></thead><tbody>';

        $sql = "SHOW TABLES FROM `" . Registry::get("DataBase")->getDB() . "`";
        $result2 = Registry::get("DataBase")->query($sql);
        while ($row = Registry::get("DataBase")->fetchrow($result2)) {
            $table = $row[0];
            $display .= '<tr>';
            $display .= '<td>' . $table . '</td>';
            $display .= '<td>';

            $sql = "REPAIR TABLE `" . $table . "`";
            $result = Registry::get("DataBase")->query($sql);
            if (!$result) {
                Core::error("mySQL Error on Query : " . $sql);
            } else {
                $display .= Lang::$word->_SYS_DBSTATUS . ' <i class="green icon check" data-content="' . Lang::$word->_SYS_DBTABLE . ' ' . $table . ' ' . Lang::$word->_SYS_DBREPAIRED . '"></i>';
            }
            $display .= '</td>';
            $display .= '<td>' . $table . '</td>';
            $display .= '<td>';

            $sql = "OPTIMIZE TABLE `" . $table . "`";
            $result = Registry::get("DataBase")->query($sql);
            if (!$result) {
                Core::error("mySQL Error on Query : " . $sql);
            } else {
                $display .= Lang::$word->_SYS_DBSTATUS . ' <i class="green icon check" data-content="' . Lang::$word->_SYS_DBTABLE . ' ' . $table . ' ' . Lang::$word->_SYS_DBOPTIMIZED . '"></i>';
            }

            $display .= '</td></tr>';
        }
        $display .= '</tbody></table>';

        return $display;
    }
}
?>