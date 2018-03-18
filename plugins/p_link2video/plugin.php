<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 02.02.2016
 * Time: 5:01
 */
class p_link2video extends Plugins{

    public function __construct()
    {
        parent::__construct();
        $this->info['plugin']           = 'p_link2video';
        $this->info['title']            = 'Замена Youtube url на видео';
        $this->info['description']      = 'Ссылки на Youtube заменяются на код плеера с этим видео';
        $this->info['author']			= '<a href="http://www.instantcms.ru/users/Maximov">Maximov</a>, mod:<a href="http://www.instantcms.ru/users/GDV">Денис Васильевич</a>';
        $this->info['version']          = '0.4.2015';

        $this->events[]                 = 'GET_COMMENTS';
        $this->events[]                 = 'GET_WALL_POSTS';
        $this->events[]                 = 'GET_FORUM_POSTS';
        $this->events[]                 = 'GET_POSTS';
        $this->events[]                 = 'GET_POST';
        $this->events[]                 = 'GET_ARTICLE';

        $this->config['Ширина']="600";
        $this->config['Высота']="400";
    }

    public function execute($event, $item)
    {
        parent::execute();
        if ($event=='GET_COMMENTS' or $event=='GET_WALL_POSTS' or $event=='GET_ARTICLE') {$content='content';}
        else {$content='content_html';}
        $preg = '~
        # Match non-linked youtube URL in the wild. (Rev:20130823)
        https?://         # Required scheme. Either http or https.
        (?:[0-9A-Z-]+\.)? # Optional subdomain.
        (?:               # Group host alternatives.
          youtu\.be/      # Either youtu.be,
        | youtube         # or youtube.com or
          (?:-nocookie)?  # youtube-nocookie.com
          \.com           # followed by
          \S*             # Allow anything up to VIDEO_ID,
          [^\w\s-]       # but char before ID is non-ID char.
        )                 # End host alternatives.
        ([\w-]{11})      # $1: VIDEO_ID is exactly 11 chars.
        (?=[^\w-]|$)     # Assert next char is non-ID or EOS.
        (?!               # Assert URL is not pre-linked.
          [?=&+%\w.-]*    # Allow URL (query) remainder.
          (?:             # Group pre-linked alternatives.

            [\'"][^<>]*>  # Either inside a start tag,
          | 	 (<a(.*)href="\/go\/url=[^>](.*)>)(\S*</a>)         # or inside <a> element text contents.
          )               # End recognized pre-linked alts.

        )                 # End negative lookahead assertion.
        [?=&+%\w.-]*        # Consume any URL (query) remainder.
        ~ix';
        $play='<br><iframe width="'.$this->config['Ширина'].'" height="'.$this->config['Высота'].'" src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe><br>';
        if ($event=='GET_POST' or $event=='GET_ARTICLE') {
            $item->content=preg_replace($preg, $play, $item->content);
        }else{
            for ($i=0; $i<count($item);$i++ ){
                $item->content=preg_replace($preg, $play, $item->content);
            }
        }

        return $item;
    }
}