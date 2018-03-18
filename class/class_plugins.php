<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 19.01.2016
 * Time: 14:03
 */
class Plugins {
    protected $inDB;
    protected $inCore;
    protected $inPage;

    public $info;
    public $events;
    public $config;

    public function __construct()
    {
        $this->inDB=Registry::get("DataBase");
        $this->inCore=Registry::get("Core");
        $this->inPage=Registry::get("Page");
    }

    public function __clone() {}

    public function execute() {
         $this->config = $this->inCore->loadPluginConfig( $this->info['plugin'] );
    }
}
?>