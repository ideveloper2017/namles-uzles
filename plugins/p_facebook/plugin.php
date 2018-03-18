<?php
class p_facebook extends Plugins{
    public function __construct()
    {
        parent::__construct();
        $this->info['plugin']           = 'p_facebook';
        $this->info['title']            = 'Автопостинг facebook';
        $this->info['description']      = 'Автопостинг facebook';
        $this->info['author']           = 'deltas';
        $this->info['version']          = '1.10';

        $this->config['appId']     = '219398215135587';
        $this->config['secret']  = '02bea205121837f4e862875cdb5e6d71';

        $this->events[] = 'ADD_POST_DONE';
        $this->events[] = 'ADD_ARTICLE_DONE';
        $this->events[] = 'ADD_BOARD_DONE';

    }

    public function execute($event, $item){
        parent::execute();
          switch ($event){
            case 'ADD_ARTICLE_DONE':
                $post='';
                $seolink = $_SERVER['HTTP_HOST'] .'/articles/'. $item['seo'];
                $post.= $item['title']."\n";

                $post.='<img src="'.$_SERVER['HTTP_HOST'].'/images/photos/'. $item['images'].'"/>\n';
                $this->facebook($seolink, $post);
                break;
        }
        return $item;
    }

    private function facebook($seolink, $post){
        require_once(PATH."/plugins/p_facebook/facebook/facebook.php");
        $config = array();
        $config['appId'] = '1993299227560901';
        $config['secret'] = '096ad009cd2de25eeecbd406d0d3a77';
        $config['default_graph_version'] = 'v2.6';
        $config['fileUpload'] = false; // optional
        $fb = new Facebook($config);

        $params = array(
            // this is the main access token (facebook profile)
            "access_token" => "EAAXJc13mpmQBACBJ6wpGi2oWKZCs8xFlXqgw7J1DwClZAR86hh8t5AvmLPIeAfyGZCF2ueAZBx8UODBegZAq5JsZAj8TnPXZBn94YM1VTXiLDuRDrXr7v7fUcBWpsrvKUhZAAH7ui5ZByPnBWpbxSIoSHdiCxlXdxZAiuONWlUHli4EwZDZD",
            "message" => "Here is a blog post about auto posting on Facebook using PHP #php #facebook",
            "link" => "http://www.pontikis.net/blog/auto_post_on_facebook_with_php",
            "picture" => "http://i.imgur.com/lHkOsiH.png",
            "name" => "How to Auto Post on Facebook with PHP",
            "caption" => "www.pontikis.net",
            "description" => "Automatically post on Facebook with PHP using Facebook PHP SDK. How to create a Facebook app. Obtain and extend Facebook access tokens. Cron automation."
        );


        try {
            $ret = $fb->api('/me/feed', 'POST', $params);
            echo 'Successfully posted to Facebook Personal Profile';
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}