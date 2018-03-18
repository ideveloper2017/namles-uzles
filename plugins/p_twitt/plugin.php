<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 26.01.2016
 * Time: 23:47
 */
class p_twitt extends Plugins{


    public function __construct()
    {
        parent::__construct();
        $this->info['plugin']           = 'p_twitt';
        $this->info['title']            = 'Автопостинг Twitter';
        $this->info['description']      = 'Автопостинг Twitter';
        $this->info['author']           = 'deltas';
        $this->info['version']          = '1.10';

        // Настройки по-умолчанию
        $this->config['CONS_KEY']     = '7zbIMevbvPu5krU23db9QjvYL';
        $this->config['CONS_SECRET']  = 'UQz5BZ4ZBfh4NStBbGCY4MZTSKYx8H5WzARvMLgAHuIw2PunXH';
        $this->config['O_TOKEN']      = '701798888710213634-gPAIYPUmmh2xyUXOSJHTsQhi25X5GXW';
        $this->config['O_SECRET']     = 'FLtEvLInMFMuqgx9O1oKgkOSZF2uekVsTuG3aiceufNst';


        // События, которые будут отлавливаться плагином
        $this->events[] = 'ADD_POST_DONE';
        $this->events[] = 'ADD_ARTICLE_DONE';
        $this->events[] = 'ADD_BOARD_DONE';

    }

    public function execute($event, $item){

        parent::execute();
        switch ($event){
            case 'ADD_ARTICLE_DONE':
                $post='';
                $seolink = $_SERVER['HTTP_HOST'] .'/content/'. $item['seo'];
                $post.= $item['title'];
//                $post.= $item['introtext'];
//                $post.='<img src="'.$_SERVER['HTTP_HOST'].'/images/photos/'. $item['images'].'"/>';
                $this->twitt($seolink, $post);
                break;
        }
        return $item;
    }

    private function twitt($seolink, $post){
        require_once(PATH.'/plugins/p_twitt/twitteroauth/twitteroauth.php');

        define("CONSUMER_KEY", $this->config['CONS_KEY']);
        define("CONSUMER_SECRET", $this->config['CONS_SECRET']);
        define("OAUTH_TOKEN", $this->config['O_TOKEN']);
        define("OAUTH_SECRET", $this->config['O_SECRET']);
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
        $content = $connection->get('account/verify_credentials');

        $text = $post.' - '.$seolink;

        $connection->post('statuses/update', array('status' => $text));
    }
}