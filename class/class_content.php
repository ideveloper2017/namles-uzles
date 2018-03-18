<?php

class Content
{
    protected $category_struct;
    public static $db;

    public function __construct()
    {
        self::$db = Registry::get("DataBase");
        $this->loadCategories();

    }

    private function loadCategories()
    {
        if (is_array($this->category_struct)) {
            return;
        }
        $item = self::$db->fetch_all("SELECT * FROM categories  order by id asc");
        foreach ($item as $items):
            $this->category_struct[$items->id] = $items;
        endforeach;
    }

    private function getCategoriesBind($item_id){
        $bind_sql = "SELECT * FROM categories_bind WHERE item_id = " . $item_id;
        $bind_res = Registry::get("DataBase")->query($bind_sql);
        $bind = array();

        while ($r = Registry::get("DataBase")->fetch($bind_res)) {
            $bind[] = $r->category_id;
        }

        return $bind;
    }

    public function getContentCategory($cat_id){
        if ($cat_id) {
            $category = self::$db->getFieldsById('*', 'categories', "id=$cat_id");
        }
        return $category;
    }

    public function getContentCategoryhref($cat_id){
        if ($cat_id) {
            $category = self::$db->getFieldsById('*', 'categories', "id=$cat_id");
        }
        $url=''.self::getCategoryURL(null,$category->slug).'-'.$category->id;
        return $url;
    }

    public function newContent($table,$field){

        $new =self::$db->getFieldById('COUNT(id)', "DATE_FORMAT({$field}, '%d-%m-%Y') = DATE_FORMAT(NOW(), '%d-%m-%Y')",$table );
        return $new;
    }

    public function getCategoryList(){
        if (!empty(Core::$post['filter'])){$where=" and cname like '%".Core::$post['filter']."%'";}
        $pager=Registry::get("Paginator");
        $counter=self::$db->numrows(self::$db->query("select * from categories where parent_id=1 {$where}"));
        $pager->items_total=$counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }
        $sql="select * from categories where parent_id=1 {$where}".$pager->limit;
        $rows=self::$db->fetch_all($sql);
        return ($rows)?$rows:0;
    }

    public function getSortCategoryListTable($parent_id, $level = 0, $spacer, $selected = false)
    {
        $submenu = false;
//        $rows = self::$db->query("select * from categories where parent_id='{$parent_id}'");
//        = self::$db->fetch($rows)

        foreach ($this->category_struct as $key=> $row) {
            if ($parent_id == $row->parent_id) {
                $publ = $row->active ? "<i class='icon-checkmark-circle2'></i>" : "<i class='icon-cancel-circle2'></i>";
                print "<tr>";
                print "<td><input type=\"checkbox\" name='item[]' id='item[]' value=\"{$row->id}\" class=\"styled\"></td>";
                print "<td><a href='index.php?do=content&action=editcats&id={$row->id}'>" . $row->id . "</a></td>";
                print "<td><a href='index.php?do=content&action=editcats&id={$row->id}'>";
                for ($i = 0; $i < $level; $i++) echo $spacer;
                print $row->cname . "</a></td>";
                print "<td>" . $row->slug . "</td>";
                print "<td>" . $publ . "</td>";
                print "<td>" . $row->lang . "</td>";
                print "<td><div class=\"table-controls\">
				        <a href=\"index.php?do=content&action=delete&id=$row->id\" class=\"btn btn-default btn-icon btn-xs tip\" title=\"Удалить\"><i class=\"icon-remove\"></i></a>
				        <a href=\"index.php?do=content&action=editmenuitem&id=$row->id\" class=\"btn btn-default btn-icon btn-xs tip\" title=\"Радактировать\"><i class=\"icon-pencil\"></i></a>
				        <a href=\"index.php?do=content&action=priview&id=$row->id\" class=\"btn btn-default btn-icon btn-xs tip\" title=\"Просморт\"><i class=\"icon-search2\"></i></a>
			    </div></td>";
                print "</tr>";
                $level++;
                $this->getSortCategoryListTable($key, $level, $spacer, $selected);
                $level--;
            }
        }
        unset($row);
    }

    public function getContentList($selected = false)
    {
        $html = '';
        $lang = Lang::getLangID();
        $sql = "SELECT * FROM content ORDER BY created_at desc";
        $rows = self::$db->fetch_all($sql);
        foreach ($rows as $row) {
            $sel = ($selected == $row->id) ? "selected='selected'" : "";
            $html.= "<option value=\"" . $row->id . "\"" . $sel . ">";
            $html.= $row->title . "</option>\n";
        }
        return $html;
    }

    public function getArticleList($cat_id){

        $where='';

        if (!empty(Core::$post['filter'])){$where=" con.title like '%".Core::$post['filter']."%' and ";}

        if (isset($cat_id) ) {
            if ($cat_id != 1) {
                $where = '(ct.id=' . $cat_id . ' or ct.parent_id='.$cat_id. ') and ';
            }
        }
        $def_order  = $cat_id ? 'con.ordering' : 'con.created_at';
        $only_hidden=(int)Core::$get['only_hidden'];
        $orderby=Core::$get['orderby']?Core::$get['orderby']:$def_order;
        $orderto=Core::$get['orderto'];

        if ($only_hidden){
            $where=('con.active = 0');
        }

        $pager=Registry::get("Paginator");
        $counter=self::$db->numrows(self::$db->query("select con.*,l.name as lang,u.username as author from content con left join users u on u.id=con.user_id
                                        left join categories_bind cb on cb.item_id=con.id
                                        left join categories ct on ct.id=cb.category_id
                                        left join languages l on l.flag=con.lang
                                        where {$where} con.is_archive=0  order by con.id desc"));
        $pager->items_total=$counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }
        $sql="select con.*,l.name as lang,u.username as author from content con left join users u on u.id=con.user_id
                                        left join categories_bind cb on cb.item_id=con.id
                                        left join categories ct on ct.id=cb.category_id
                                        left join languages l on l.flag=con.lang
                                        where {$where}con.is_archive=0  order by con.id desc ".$pager->limit;
//        echo $sql;
        $rows=self::$db->fetch_all($sql);
        return ($rows)?$rows:0;
    }

    public function getArticle($id){
        $sql="select c.* from content c  where  c.id=".(int)$id;
        $rows=self::$db->first($sql);
        return ($rows)?$rows:0;
    }
    public function getCategoryDropList($parent_id, $level = 0, $spacer, $selected = false)
    {
        if ($this->category_struct) {
            foreach ($this->category_struct as $key => $row) {
                $sel = ($row->id == $selected) ? " selected=\"selected\"" : "";
                if ($parent_id == $row->parent_id) {
                    print "<option value=\"" . $row->id . "\"" . $sel . ">";
                    for ($i = 0; $i < $level; $i++)
                        print $spacer;

                    print $row->cname . "</option>\n";
                    $level++;
                    $this->getCategoryDropList($key, $level, $spacer, $selected);
                    $level--;
                }
            }
            unset($row);
        }
    }

    public function getSortedCategoryList($parent_id = 1)
    {
        $submenu = false;
        $class = ($parent_id == 1) ? "class='sortMenu'" : "child";
        if ($this->category_struct) {
            foreach ($this->category_struct as $key => $row) {
                if ($parent_id == $row->parent_id) {
                    if ($submenu === false) {
                        $submenu = true;
                        print "<ul class=\"sortMenu\">\n";
                    }
                    print '<li class="dd-item" id="list_' . $row->id . '">'
                        . '<div class="dd-handle"><a data-id="' . $row->id . '" data-name="' . $row->cname . '"'
                        . '  data-option="deleteMenu" class="delete">'
                        . '<i class="icon red remove sign"></i></a><i class="icon reorder"></i>'
                        . '<a href="index.php?do=content&amp;catid=' . $row->id . '" class="' . $class . '">' . $row->cname . '</a></div>';
                    $this->getSortedCategoryList($key);
                    print "</li>\n";
                    print "</li>\n";
                }
            }
            unset($row);
            if ($submenu === true)
                print "</ul>\n";

        }
    }



    public function getCategoryHtmlList($parent_id, $level = 0,$item_id, $spacer, $selected = false)
    {
        if ($this->category_struct) {
            foreach ($this->category_struct as $key => $row) {
                if (Core::$action=='editcontent') {
                    if (in_array($key, $this->getCategoriesBind($item_id))) {
                        $row->selected = true;
                        $sel = ($row->selected) ? " checked=\"checked\"" : "";
                    }else{
                        $sel = ($row->id==$selected) ? " checked=\"checked\"" : "";
                    }
                }

                if ($parent_id == $row->parent_id) {
                    print '<tr><td width="20" height="25">';
                    print '<input type="checkbox" name="categories_id[]" class="styled" id="'.$row->id.'"
                                                               value="'.$row->id.'" '.$sel.'
                                                               /></td>';
                    print '<td><label for="cid'.$row->id.'">';
                    for ($i = 0; $i < $level; $i++)
                        print $spacer;
                    print $row->cname .'</label></td>';

                    print  "</tr>\n";
                    $level++;
                    $this->getCategoryHtmlList($key, $level,$item_id, $spacer, $selected);
                    $level--;
                } else {

                }
            }
            unset($row);
        }
    }

    public function proccessCats($cats)
    {
        $data = array('parent_id' => $cats['parent_id'],
            'cname' => $cats['cname'],
            'active' => $cats['active'],
            'slug' => $cats['slug'],
            'url' => $cats['url'],
            'cdescription' => $cats['cdescription'],
            'orderby' => $cats['orderby'],
            'orderto' => $cats['orderto'],
            'maxcols' => $cats['maxcols'],
            'lang' => $cats['lang'],
            'mgroup_id' => $cats['mgroup_id'],
            'tpl' => $cats['tpl'],
            'config' => $cats['config']);

        (Core::$id) ? self::$db->update("categories", $data, "id=" . Core::$id) : self::$db->insert("categories", $data);
    }



    public function proccessContItem($items)
    {
        if (Core::$id) {
            $data = array(
                'title' => $items['title'],
//                'category_id' => $items['category_id'],
                'introtext' => $items['introtext'],
                'fulltext' => $items['fulltext'],
                'user_id' => $items['user_id'],
                'created_at' => $items['created_at'],
                'end_at' => $items['end_at'],
                'update_at' => 'now()',
                'is_end' => $items['is_end'],
                'pagetitle' => $items['pagetitle'],
                'meta_keywords' => $items['meta_keywords'],
                'meta_desc' => $items['meta_desc'],
                'seo' => $items['seo'],
                'url' => $items['url'],
                'jscode' => $items['jscode'],
                'lang' => $items['lang'],
                'images' => $items['images'],
                'featured' => $items['featured'],
                'attribs' => $items['attribs'],
                'tpl' => $items['tpl'],
                'active' => $items['active'],
            );
        }else{
            $data = array(
                'title' => $items['title'],
//                'category_id' => $items['category_id'],
                'introtext' => $items['introtext'],
                'fulltext' => $items['fulltext'],
                'user_id' => $items['user_id'],
                'created_at' => $items['created_at'],
                'update_at' => $items['update_at'],
                'end_at' => $items['end_at'],
                'is_end' => $items['is_end'],
                'pagetitle' => $items['pagetitle'],
                'meta_keywords' => $items['meta_keywords'],
                'meta_desc' => $items['meta_desc'],
                'seo' => $items['seo'],
                'url' => $items['url'],
                'jscode' => $items['jscode'],
                'lang' => $items['lang'],
                'images' => $items['images'],
                'featured' => $items['featured'],
                'attribs' => $items['attribs'],
                'tpl' => $items['tpl'],
                'active' => $items['active'],
            );
        }


        (Core::$id) ? self::$db->update("content", $data, "id=" . Core::$id) : self::$db->insert("content", $data);
        $lastq = "SELECT LAST_INSERT_ID() as lastid FROM content";
        $lastrow = Registry::get("DataBase")->first($lastq);
        $lastid = $lastrow->lastid;

        $categories=$items['category_id'];
//        $this->getSeoLink($items);
//        print_r($categories);
        if (is_array($categories)) {
            if (sizeof($categories) > 0) {
                foreach ($categories as $key => $value) {
                    if ($value=='') { unset($categories[$key]); }
                    else {
                        self::$db->insert("categories_bind", array('category_id' => $value, 'item_id' => $lastid ? $lastid : Core::$id));
                    }
                }
            }
        }
        return $lastid;
    }

    public function proccessContItemAccess($item_id){
        if (!isset($_REQUEST['is_public'])) {
            $showfor = $_REQUEST['showfor'];
            if (sizeof($showfor) > 0 && !isset($_REQUEST['is_public'])) {
                foreach ($showfor as $key => $value) {
                    $data=array(
                        'contentid'=>$item_id,
                        'contenttype'=>'material',
                        'groupid'=>$value
                    );
                    $item_id ? self::$db->update("content", $data, "id=" . Core::$id) : self::$db->insert("content", $data);
                }
            }
        }
    }

    public function proccessContentPhotos($items){
        (Core::$id) ? self::$db->update("content_photos", $items, "id=" . Core::$id) : self::$db->insert("content_photos", $items);
    }


    public function generateTag($tags)
    {
        $keywoords = array();
        if ($tags != '') {
            $tags = preg_replace("/\s+/ums", " ", $tags);
            $tags = preg_replace("/([[:punct:]]|[[:digit:]]|(\s)+)/ui", ",", $tags);
            $arr = explode(",", $tags);
            for ($i = 0; $i < count($arr); $i++) {
                if (strlen($arr[$i]) > 3) {
                    $arr[$i] = trim($arr[$i]);
                    $keywoords[] = $arr[$i];
                }

            }
            if (sizeof($keywoords) != 0) {
                $keywords = array_unique($keywoords);
                shuffle($keywords);
                $keywords = array_slice($keywoords, 0, 15);
                $tag = implode(',', $keywoords);
                $tag = trim($tag);
            }
        }
        return $tag;
    }

    public function getTagLine($target, $item_id, $links = true, $selected = '')
    {

        $sql = "SELECT tag_name
			FROM tags
			WHERE target='{$target}' and item_id='{$item_id}'
			ORDER BY tag_name DESC";

        $rs = self::$db->query($sql);
        $html = '';
        $tags = self::$db->numrows($rs);
        if ($tags) {
            $t = 1;
            while ($tag = self::$db->fetch($rs)) {
                if ($links){
                    if ($selected==$tag->tag){
                        $html .= '<a  href="/search/tag/'.urlencode($tag->tag_name).'">'.$tag->tag_name.'</a>';
                    }else{
                        $html .= '<a href="/search/tag/'.urlencode($tag->tag_name).'">'.$tag->tag_name.'</a>';
                    }
                }else{
                    $html .= $tag->tag_name;
                }
                if ($t < $tags) { $html .= '  '; $t++; }
            }
        }else{
            $html = '';
        }
        return $html;
    }


    public function getTagItemLink($target, $item_id){
        $inDB   = Registry::get("DataBase");
        $inCore = Registry::get("Core");
        $link = '';
        switch ($target){
            case 'article': $today = date("Y-m-d H:i:s");
                $sql = "SELECT i.title as title, c.cname as cat, i.seo as seolink, c.slug as cat_seolink,i.images,i.introtext,i.created_at as pubdate,i.hits
								FROM content i
								INNER JOIN categories_bind cb on cb.item_id=i.id
								INNER JOIN categories c ON c.id = cb.category_id
								WHERE i.id = '$item_id' AND i.active = 1  AND i.is_archive = 0 AND i.created_at <= '$today' AND (i.is_end=0 OR (i.is_end=1 AND i.end_at >= '$today')) order by i.created_at desc LIMIT 1";

                $rs = $inDB->query($sql) ;
                if ($inDB->numrows($rs)){
                    $item = $inDB->fetch($rs);
                    $link='<div class="cs-col cs-col-6-of-12"><div class="cs-post-block-layout-3"><div class="cs-post-item">
                                        <div class="cs-post-thumb">
                                            <div class="cs-post-category-border cs-clearfix">
                                                <a href="/content/'.$item->cat_seolink.'.html">'.$item->cat.'</a>
                                            </div>

                                            <a href="/content/'.$item->seolink.'.html"><img src="/images/content/'.$item->images.'" alt="UniqMag" style="width:333px;height:204px;"></a>
                                        </div>

                                         <div class="cs-post-inner">
                                            <h3><a href="/content/'.$item->seolink.'.html">'.$item->title.'</a></h3>
                                            <div class="cs-post-meta cs-clearfix">
                                                <span class="cs-post-meta-date"><i class="fa fa-calendar"></i>  '. $item->pubdate.' &nbsp;&nbsp;<i class="fa fa-eye"></i> '. $item->hits.' </span>

                                            </div>
                                        </div>
    					</div></div></div>';

                }
                break;
        }
        return $link;
    }
    public function insertTags($target,$tagstr, $item_id){
        self::$db->query("DELETE FROM tags WHERE  item_id = $item_id");
        if ($tagstr){
            $tagstr = str_replace(' ', ',', $tagstr);
            $tagstr = str_replace(' ', ',', $tagstr);
            $tags = explode(',', $tagstr);

            foreach ($tags as $key=>$tag){
                if(strlen($tag)>1){
                    if (strlen($tag>15) && !(strstr($tag, ' ') || strstr($tag, '-'))) { $tag = substr($tag, 0, 15); }
                    $tag = str_replace("\\", '', $tag);
                    $tag = str_replace('"', '', $tag);
                    $tag = str_replace("'", '', $tag);
                    $tag = str_replace("&", '', $tag);
                    $data=array(
                        'target'=>$target,
                        'tag_name'=>$tag,
                        'item_id'=>$item_id
                    );


                    self::$db->insert("tags",$data);
//                    $sql = "INSERT INTO tags (target,tag_name, item_id) VALUES ('{$target}','{$tag}', '{$item_id}')";
//                    self::$db->query($sql);
                }
            }
        }
    }
    public static function getArticleURL($menuid, $seolink, $page=1){

        if((is_numeric($page) && $page>1) || is_string($page)){
            $page_section = '/page-'.$page;
        } else {
            $page_section = '';
        }
        $url='/'.$menuid.'/'.$seolink.$page_section.'.html';

        return $url;

    }

    public static function getCategoryURL($menuid, $seolink, $page=1, $pagetag = false){

        if (!$pagetag){
            $page_section = ($page>1 ? '/page-'.$page : '');
        } else {
            $page_section = '/page-%page%';
        }

        $url= $seolink.$page_section;
        return $url;

    }

    public function getCategory($cat_id_or_link=0){

        if(is_numeric($cat_id_or_link)){ // если пришла цифра, считаем ее cat_id
            $where = "id = '{$cat_id_or_link}'";
        } else {

            $where = "slug = '{$cat_id_or_link}'";

        }
//        echo $where;
        $cat=self::$db->getFieldsById("*","categories",$where);
//        print_r($cat);
        return $cat;
    }

    public function getNsCategoryPath($table, $left_key, $right_key, $fields='*', $only_nested = false) {
        $nested_sql = $only_nested ? '' : '=';

        $path = self::$db->get_table($table, "id $nested_sql $left_key or parent_id $nested_sql $right_key AND parent_id > 0  ORDER BY id", $fields);
        return $path;
    }

    public function getSeoLink($article){
        $seolink = '';
        $cat=$this->getCategory($article['category_id'][0]);

//        echo $cat->parent_id;
//        $path_list = self::$db->get_table("categories", " id>=$cat->parent_id and parent_id<=$cat->id order by id", "*");
        $path_list=$this->getNsCategoryPath("categories",$cat->id,$cat->id,'*');
//        $path_list[count($path_list)-1] = array_merge($path_list[count($path_list)-1], $article);

        if ($path_list){
            foreach($path_list as $pcat){
                $seolink= $pcat->slug . '/';
            }
        }
//        echo $article;
        $seolink.= Core::doSEO($article['title']);
        $seolink = rtrim($seolink, '/');

//        echo $seolink;
//        print_r($path);
        return $seolink;
    }
}

?>