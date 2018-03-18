<?php

/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 24.12.2015
 * Time: 14:20
 */
class SmartyTpl
{
    private $smarty = false;

    public function loadSmarty()
    {
        require PATH . '/includes/smarty/libs/Smarty.class.php';
        $this->smarty = new Smarty();
        $this->smarty->compile_dir = PATH . '/cache';
    }

    function smartyInitModule()
    {
        $tpl_folder = 'modules';
        $tpl_file = '';
        $inConf = Registry::get("Config");
        if (!$this->smarty) {
            $this->loadSmarty();
        }
        if (file_exists(PATH . '/theme/' . $inConf->template . '/modules/modules.tpl')) {
            $this->smarty->template_dir = PATH . '/theme/' . $inConf->template . '/modules/';
        } else {
            $this->smarty->template_dir = PATH . '/theme/' . $inConf->template . '/modules/';
        }
        return $this->smarty;
    }

    function smartyInitComponent()
    {
        $inConf = Registry::get("Config");
        if (!$this->smarty) {
            $this->loadSmarty();
        }
        $this->smarty->template_dir = PATH . '/theme/' . $inConf->template . '/component/';
        return $this->smarty;

    }


}

?>