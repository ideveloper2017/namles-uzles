<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 22.08.2015
 * Time: 15:45
 */

class Config{

    public function __construct(){
        include($_SERVER['DOCUMENT_ROOT']."/includes/config.ini.php");
        foreach($config as $key=>$value){
            $this->{$key}=$value;
        }
    }
}
?>