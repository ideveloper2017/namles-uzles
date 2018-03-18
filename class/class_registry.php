<?php

class Registry
{

    public static $object = array();

    public static function set($name,$object)
    {
        return self::$object[$name] = $object;
    }

    public static function get($name)
    {
        return isset(self::$object[$name]) ? self::$object[$name] : null;
    }

}


?>