<?php

/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 19.01.2016
 * Time: 15:18
 */
class p_morecontent extends Plugins
{


    public function __construct()
    {

        parent::__construct();
        $this->info['plugin'] = 'p_morecontent';
        $this->info['title'] = 'Похожие статьи';
        $this->info['description'] = 'Добавляет в конец каждой статьи список похожих статей.';
        $this->events[] = 'GET_ARTICLE';

        $this->config['limit'] = 5;
        $this->config['unsort'] = 1;

    }


    public function execute($event, $item)
    {
        parent::execute();

        switch ($event) {
            case 'GET_ARTICLE':
                $item = $this->eventGetArticle($item);
                break;
        }
        return $item;
    }

    private function eventGetArticle($item)
    {
        $inDB = Registry::get("DataBase");
        $item_id = $item->id;

        $tags = explode(',', Registry::get("Content")->getTagLine('article', $item_id, false));
        foreach ($tags as $tag) {
            $sql = "SELECT item_id FROM tags WHERE tag_name = '{$tag}' AND item_id<>'{$item_id}' AND target='article' LIMIT 1 ";
            $res = $inDB->query($sql);
            if ($inDB->numrows($res)) {
                while ($tag_id = $inDB->fetch($res)) {
                    $targets[] = $tag_id->item_id;
                }
            }
        }
        if (count($targets)) {
            $targets = array_unique($targets);
            $targets = array_slice($targets, 0, 5);
            if ($this->config['unsort']) shuffle($targets);
            $morecontent = '';
            foreach ($targets as $n) {
                $con = Registry::get("Content")->getTagItemLink('article', $n);
                if ($con) {
                    if ($con) {
                        $morecontent .= $con;
                    }
                }
            }
            if ($morecontent) {
                $item->content = '<div class="cs-single-related-aticles">
                <h4 class="cs-heading-subtitle">Мавзуга оид</h4>';
                $item->content.='<div class="cs-row">

                                ';
                $item->content.=$morecontent;
//                $item->content .= '<div class="comment-box">
//                                    <h3>Мавзуга оид</h3>';
//                $item->content.=$morecontent;
                $item->content.='</div>';
//                $item->content.='</div>';
                $item->content.='</div>';
            }
        }
        return $item;
    }
}