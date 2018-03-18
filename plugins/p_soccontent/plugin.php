<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 20.01.2016
 * Time: 18:45
 */
class p_soccontent extends Plugins{

    public function __construct()
    {
        parent::__construct();
        $this->info['plugin']           = 'p_soccontent';
        $this->info['title']            = 'Социальные закладки';
        $this->info['description']      = 'Добавляет кнопки социальных закладок в блоги и статьи.';
        $this->info['author']           = 'Ilekor';
        $this->info['version']          = '1.0';

        $this->events[]                 = 'GET_POST';
        $this->events[]                 = 'GET_ARTICLE';
        $this->events[]                 = 'GET_FCATALOG';

        $this->config['v_stati']        = 1;
        $this->config['v_blogi']        = 1;
        $this->config['v_fcatalog']        = 1;

    }

    public function execute($event, $item){
        parent::execute();

        switch ($event){
            case 'GET_POST': $item = $this->eventGetBlog($item); break;
            case 'GET_ARTICLE': $item = $this->eventGetArticle($item); break;
            case 'GET_FCATALOG': $item = $this->eventGetFCatalog($item); break;
        }

        return $item;
    }

    private function eventGetBlog($item) {

        if($this->config['v_blogi'] == 1) {
            $item->content_html .= '<script src="/plugins/p_soccontent/bookmarks.js" type="text/javascript"></script>';
        }
        return $item;
    }

    private function eventGetArticle($item) {

//        $item->content.='<div class="article-share-bottom">
//								
//								<b>Share</b>
//
//								<span class="social-icon">
//									<a href="#" class="social-button" style="background:#495fbd;"><span class="icon-text">&#62220;</span><font>Share</font></a>
//									<span class="social-count">293<span class="social-arrow">&nbsp;</span></span>
//								</span>
//
//								<span class="social-icon">
//									<a href="#" class="social-button" style="background:#43bedd;"><span class="icon-text">&#62217;</span><font>Tweet</font></a>
//									<span class="social-count">34<span class="social-arrow">&nbsp;</span></span>
//								</span>
//
//								<span class="social-icon">
//									<a href="#" class="social-button" style="background:#df6149;"><span class="icon-text">&#62223;</span><font>+1</font></a>
//									<span class="social-count">29<span class="social-arrow">&nbsp;</span></span>
//								</span>
//
//								<span class="social-icon">
//									<a href="#" class="social-button" style="background:#d23131;"><span class="icon-text">&#62226;</span><font>Share</font></a>
//									<span class="social-count">18<span class="social-arrow">&nbsp;</span></span>
//								</span>
//
//								<span class="social-icon">
//									<a href="#" class="social-button" style="background:#264c84;"><span class="icon-text">&#62232;</span><font>Share</font></a>
//									<span class="social-count">170<span class="social-arrow">&nbsp;</span></span>
//								</span>
//
//								<div class="clear-float"></div>
//
//							</div>';
//        if($this->config['v_stati'] == 1) {
            $item->content .= '<script src="/plugins/p_soccontent/bookmarks.js" type="text/javascript"></script>';
//         }
        return $item;
    }
    private function eventGetFCatalog($file){
        $file['fcatalog_file']='<script src="/plugins/p_soccontent/bookmarks.js" type="text/javascript"></script>';
        return $file;
    }
}
?>