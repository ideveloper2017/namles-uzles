<?php

class p_elrte extends Plugins {

    public function __construct(){
        
        parent::__construct();

        $this->info['plugin']           = 'p_elrte';
        $this->info['title']            = 'elRTE';
        $this->info['description']      = 'Визуальный редактор';
        $this->info['author']           = 'Studio 42  & ELDORADO.CMS';
        $this->info['version']          = '1.3';
        $this->info['type']             = 'wysiwyg';

        $this->events[]                 = 'INSERT_WYSIWYG';

    }



    public function execute($event, $item){

 
        parent::execute();

//        $inUser =Registry::get("Users");

        ob_start();
        if (!$item['width']) { $item['width'] = '100%'; }
        if (!$item['height']) { $item['height'] = '300px'; }
        
        if (!preg_match('/([^0-9])/i', $item['width'])) { $item['width'] = $item['width'].'%'; }
        if (!preg_match('/([^0-9])/i', $item['height'])) { $item['height'] = $item['height'].'px'; }
        
        $item['name'] = str_replace('[', '', $item['name']);
        $item['name'] = str_replace(']', '', $item['name']);

        echo '
       <link rel="stylesheet" href="/plugins/p_elrte/elrte/css/smoothness/jquery-ui-1.8.13.custom.css" type="text/css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="/plugins/p_elrte/elrte/css/elrte.min.css" type="text/css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="/plugins/p_elrte/elfinder/css/elfinder.css" type="text/css" media="screen" charset="utf-8">
        <script src="/plugins/p_elrte/elfinder/js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>        
        <script src="/plugins/p_elrte/elrte/js/elrte.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="/plugins/p_elrte/elrte/js/i18n/elrte.ru.js" type="text/javascript" charset="utf-8"></script>
        <script src="/plugins/p_elrte/elfinder/js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>
         ';
            echo '<script type="text/javascript">
                    $(document).ready(function(){
                       var opts = {
                            lang         : "ru",   // set your language
                            styleWithCSS : false,
                            height       : 400,
                            toolbar      : "maxi",
                            fmOpen : function(callback) {
                                $("<div id=\'myelfinder\'/>").elfinder({
                                    url : "/plugins/p_elrte/elfinder/connectors/php/connector.php",
                                    lang : "ru",
                                    dialog : { width : 900, modal : true, title : "Files" }, 
                                    closeOnEditorCallback : true, 
                                    editorCallback : callback 
                                })
                            }
                       };
                       $("#'.$item['name'].'").elrte(opts);
                   });
                </script>';
        echo '<textarea id="'.$item['name'].'" name="'.$item['name'].'" style="width: '.$item['width'].'; height: '.$item['height'].';">'.$item['text'].'</textarea>';
  
        return ob_get_clean();
        
    }


}

?>
