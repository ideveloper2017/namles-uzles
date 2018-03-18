<?php

/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 29.12.2015
 * Time: 12:04
 */
class Media
{

    private $inDB;

    public function __construct()
    {
        $this->inDB = Registry::get("DataBase");
    }

    public function getAudioList()
    {
        if (!empty(Core::$post['filter'])){$where=" and cname like '%".Core::$post['filter']."%'";}
        $pager=Registry::get("Paginator");
        $counter=$this->inDB->numrows($this->inDB->query("select * from audio order by pubdate DESC {$where}"));
        $pager->items_total=$counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }
        $sql = "select * from audio order by pubdate DESC".$pager->limit;
        $rows = $this->inDB->fetch_all($sql);
        return $rows ? $rows : 0;
    }

    public function getGenre(){
        $results = $this->inDB->query("SELECT * FROM audio_genre WHERE 1=1 ORDER BY genre_name ASC");

        if (!$this->inDB->numrows($results)){ return false; }

        $items = array();
        while ($item = $this->inDB->fetch($results)){
            $items[$item->id] = array('id'=>$item->id,
                                      'genre_name'=>$item->genre_name);
        }
        return $items;
    }

    public function getAlbumId($name){
        $item_id = $this->inDB->getFieldById('id','audio_album', "album_name LIKE '%". $name ."%'");
        return empty($item_id) ? 0 : $item_id;
    }

    public function addAlbum($album, $user_id){
        return $this->inDB->insert('audio_album', array('album_name' =>$album, 'user_id' => $user_id, 'pubdate' => date('Y-m-d H:i:s')));
    }

    public function addSinger($singer, $user_id){
        return $this->inDB->insert('audio_singer', array('singer_name' => $singer, 'user_id' => $user_id, 'pubdate' => date('Y-m-d H:i:s')));
    }

    public function getSingerId($singer){
        $item_id = $this->inDB->getFieldById('id','audio_singer', "singer_name LIKE '%". $singer ."%'");
        return empty($item_id) ? 0 : $item_id;
    }

    public function getSinger($singer_id){
        $item = $this->inDB->getFieldsById('*','audio_singer', "id='". $singer_id ."'");
        if ($item){
            $item->pubdate = Core::cmsRusDate($item->pubdate);
        }
        return $item;
    }

    public function addMusic($data){
        return $this->inDB->insert('audio', $data);
    }

    public function updateMusic($data,$id){
        return $this->inDB->update('audio', $data,'id='.$id);
    }

    public function getMus($music_id){
        $result = $this->inDB->query("
            SELECT m.*, a.album_name as album, s.singer_name as singer, g.genre_name as genre, pl.id as in_pl
                FROM audio m
                LEFT JOIN audio_album a ON a.id=m.album_id
                INNER JOIN audio_singer s ON s.id=m.singer_id
                LEFT JOIN audio_genre g ON g.id=m.genre_id
                LEFT JOIN audio_pl pl ON pl.music_id=m.id
                WHERE m.id='". $music_id ."' LIMIT 1");

        if (!$this->inDB->numrows($result)) { return false; }

        $music = $this->inDB->fetch($result);

//        $music['pubdate'] = cmsCore::dateFormat($music['pubdate']);
//        $music['music_url'] = HOST . $music['music_url'];
//        $music['music_del_url'] = PATH . $music['music_url'];

        return $music;
    }

    public function getMusic(){
        if (!empty(Core::$post['filter'])){$where=" and m.name like '%".Core::$post['filter']."%'";}
        $pager=Registry::get("Paginator");
        $counter=$this->inDB->numrows($this->inDB->query("
            SELECT m.*, a.album_name as album, s.singer_name as singer, g.genre_name as genre, pl.id as in_pl
                FROM audio m
                LEFT JOIN audio_album a ON a.id=m.album_id
                INNER JOIN audio_singer s ON s.id=m.singer_id
                LEFT JOIN audio_genre g ON g.id=m.genre_id
                LEFT JOIN audio_pl pl ON pl.music_id=m.id
               where 1=1 {$where}" ));
        $pager->items_total=$counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }

        $results = $this->inDB->query("
            SELECT m.*, a.album_name as album, s.singer_name as singer, g.genre_name as genre, pl.id as in_pl
                FROM audio m
                LEFT JOIN audio_album a ON a.id=m.album_id
                INNER JOIN audio_singer s ON s.id=m.singer_id
                LEFT JOIN audio_genre g ON g.id=m.genre_id
                LEFT JOIN audio_pl pl ON pl.music_id=m.id
               where 1=1 {$where} ".$pager->limit);



        if (!$this->inDB->numrows($results)){ return false; }

        $items = array();
        while ($item = $this->inDB->fetch($results,true)){
            $item['pubdate'] = Core::cmsRusDate($item['pubdate']);

            $item['music_url'] = "http://".$_SERVER['HTTP_HOST'] . $item['music_url'];
            $item['music_del_url'] = PATH . $item['music_url'];

            $item['in_pl'] = empty($item['in_pl']) ? false : true;

            $items[] = $item;
        }

        return $items;
    }


    public function getSingers($page, $perpage){
        $results = $this->inDB->query("SELECT * FROM audio_singer WHERE 1=1 ORDER BY pubdate DESC LIMIT ". (($page-1)*$perpage) .", ". $perpage);

        if (!$this->inDB->numrows($results)){ return false; }

        $items = array();
        while ($item = $this->inDB->fetch($results,true)){
            $item['pubdate'] = Core::cmsRusDate($item['pubdate']);
            $item['mus'] = $this->inDB->rows_count('audio', "singer_id='". $item['id'] ."'");
            $items[] = $item;
        }
        return $items;
    }

    public function getMovieList()
    {
        if (!empty(Core::$post['filter'])){$where=" and cname like '%".Core::$post['filter']."%'";}
        $pager=Registry::get("Paginator");
        $counter=$this->inDB->numrows($this->inDB->query("select * from video ordery by pubdate DESC {$where}"));

        $pager->items_total=$counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }
        $sql = "select * from video order by pubdate DESC {$where}".$pager->limit;
        $rows = $this->inDB->fetch_all($sql);
        return $rows ? $rows : 0;
    }

    public function proccessMovie($item){
//        $data=array(
//            'title'=>$item['title'],
//            'description'=>$item['description'],
//            'file'=>$item['file'],
//            'img'=>$item['img'],
//            'seolink'=>$item['seolink'],
//            'pubdate'=>$item['pubdate'],
//            'published'=>$item['published']
//        );
        $this->inDB->insert("video",$item);
    }

    public function proccessUpdateMovie($item){
        $item_id=Core::$post['item_id'];
//        $data=array(
//            'title'=>$item['title'],
//            'description'=>$item['description'],
//            'file'=>$item['file'],
//            'img'=>$item['img'],
//            'seolink'=>$item['seolink'],
//            'pubdate'=>$item['pubdate'],
//            'published'=>$item['published']
//        );
        $this->inDB->update("video",$item,"id=".$item_id);
    }


    public function proccessUpdateMp3($item){
        $item_id=Core::$post['item_id'];
        $data=array(
            'title'=>$item['title'],
            'description'=>$item['description'],

            'seolink'=>$item['seolink'],
            'pubdate'=>$item['pubdate'],
            'published'=>$item['published']
        );
        if ($item['file']){
            $data['file']=$item['file'];
        }
        $this->inDB->update("audio",$data,"id=".$item_id);
    }
}

?>