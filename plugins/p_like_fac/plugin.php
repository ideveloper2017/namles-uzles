<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 11.02.2016
 * Time: 20:34
 */
class p_like_fac extends Plugins{

    public function __construct()
    {
        parent::__construct();
        $this->info['plugin']           = 'p_like_fac';
        $this->info['title']            = 'Like button to my Facebook';
        $this->info['description']      = 'Вставляет скрипт кнопки МНЕ НРАВИТСЯ от Фейсбука в статьи и блоги';
        $this->info['author']           = 'dimik';
        $this->info['version']          = '0.1';
        $this->events[]                 = 'GET_POST';
        $this->events[]                 = 'GET_ARTICLE';
    }

    public function execute($event, $item){
        parent::execute();
        switch ($event){
            case 'GET_POST': $item = $this->eventGetBlogPost($item); break;
            case 'GET_ARTICLE': $item = $this->eventGetArticle($item); break;
        }
        return $item;
    }

    private function eventGetBlogPost($item) {
        $item->content= '<p><iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fexample.com%2Fpage%2Fto%2Flike&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe></p>';
        return $item;
    }
    private function eventGetArticle($item) {
        $item->content= '<p><iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fexample.com%2Fpage%2Fto%2Flike&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe></p>';
        return $item;
    }
}