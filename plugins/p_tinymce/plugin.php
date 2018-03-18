<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 01.05.2016
 * Time: 20:37
 */
class p_tinymce extends Plugins{


    public function __construct(){

        parent::__construct();

        $this->info['plugin']           = 'p_tinymce';
        $this->info['title']            = 'TinyMCE';
        $this->info['description']      = 'Визуальный редактор';
        $this->info['author']           = 'Moxiecode Systems AB';
        $this->info['version']          = '3.64';
        $this->info['type']             = 'wysiwyg';

        $this->events[]                 = 'INSERT_WYSIWYG';

    }


    public function execute($event = '', $item = array()){
        parent::execute();
        $inUser = Registry::get("Users");
        ob_start();
        if (!$item['width']) { $item['width'] = '100%'; }
        if (!$item['height']) { $item['height'] = '300px'; }
        if (!preg_match('/([^0-9])/i', $item['width'])) { $item['width'] = $item['width'].'%'; }
        if (!preg_match('/([^0-9])/i', $item['height'])) { $item['height'] = $item['height'].'px'; }
        $item['name'] = str_replace('[', '', $item['name']);
        $item['name'] = str_replace(']', '', $item['name']);
        echo '<script type="text/javascript" src="/plugins/p_tinymce/tiny_mce/tiny_mce.js"></script>';

            echo '<script type="text/javascript">
                    $(document).ready(function(){

                        tinyMCE.init({
                            // Основные настройки
                            mode: "exact",
                            elements : "'.$item['name'].'",
                            theme : "advanced",
                            language : "ru",
                            plugins : "pastehtml,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
                            relative_urls : false,
                            
                            // Настройки шаблона
                            theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
                            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,pastehtml,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
                            theme_advanced_toolbar_location : "top",
                            theme_advanced_toolbar_align : "left",
                            theme_advanced_statusbar_location : "bottom",
                            theme_advanced_resizing : true
                        });
                    });
                </script>';


        echo '<textarea id="'.$item['name'].'" name="'.$item['name'].'" style="width:100%;">'.$item['text'].'</textarea>';

        return ob_get_clean();

    }
}