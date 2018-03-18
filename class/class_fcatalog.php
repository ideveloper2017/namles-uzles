<?php

/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 12.02.2016
 * Time: 15:47
 */
class FCatalog
{
    private $inDB;
    private $inCore;
    protected $category;

    public function __construct()
    {
        $this->inDB = Registry::get("DataBase");
        $this->inCore = Registry::get("Core");
        $this->loadFCatalog();
    }

    private function loadFCatalog()
    {

        if (!empty(Core::$post['filter'])) {
            $where = " title like '%" . Core::$post['filter'] . "%'";
        }

        $fquery = $this->inDB->query("SELECT *"
            . "\n FROM files_cat"
            . "\n {$where}"
            . "\n ORDER BY parent_id, ordering");
        $pager = Registry::get("Paginator");
        $counter = $this->inDB->numrows($fquery);
        $pager->items_total = $counter;
        $pager->default_ipp = 50;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }

        $query = $this->inDB->query("SELECT *"
            . "\n FROM files_cat"
            . "\n {$where}"
            . "\n ORDER BY parent_id, ordering" . $pager->limit);


        $res = $this->inDB->numrows($query);
        while ($row = $this->inDB->fetch($query)) {
            $this->category[$row->id] = array(
                'id' => $row->id,
                'parent_id' => $row->parent_id,
                'ordering' => $row->ordering,
                'title' => $row->title,
                'description' => $row->description,
                'seolink' => $row->link,
                'icon' => $row->icon);
        }

        return ($res) ? $this->category : 0;
    }

    public function getFCatalogList()
    {

        $pager = Registry::get("Paginator");
        $counter = $this->inDB->numrows($this->inDB->query("select * from files_cat where parent_id=1 ORDER BY parent_id, ordering "));
        $pager->items_total = $counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }
        $sql = "select * from files_cat where parent_id=1 ORDER BY parent_id, ordering " . $pager->limit;
        $rows = $this->inDB->fetch_all($sql);
        foreach ($rows as $row) {
            if ($row->parent_id == 1) {
                $list[] = $row;
            }
        }
        return $list;
    }


    public function getFileItem($f_id)
    {
        $sql = "SELECT fc.title as category,f.*, u.username FROM files f
                LEFT JOIN users u ON f.user_id = u.id
                LEFT JOIN files_cat fc on fc.id=f.cat_id
                WHERE f.id='{$f_id}' ";

        $result = $this->inDB->query($sql);
        if ($this->inDB->numrows($result)) {
            $file = $this->inDB->fetch($result,true);
        }
        return $file;
    }

    public function getFileItems()
    {

        $sql = "SELECT fc.title as category,f.*, u.username FROM files f
                LEFT JOIN users u ON f.user_id = u.id
                LEFT JOIN files_cat fc on fc.id=f.cat_id";

        if (!empty(Core::$post['filter'])) {
            $where = " where f.title like '%" . Core::$post['filter'] . "%'";
        }
        $pager = Registry::get("Paginator");
        $counter = $this->inDB->numrows($sql . $where);
        $pager->items_total = $counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }


        $result = $this->inDB->query($sql . $where . $pager->limit);

        if ($this->inDB->numrows($result)) {

            $file = $this->inDB->fetch_all($result);
        }

        return $file;
    }

    public function getFCatalogTreeList($parent_id = 0, $level = 0, $spacer, $selected = false)
    {
        $id = Core::$id;
        $submenu = false;
        foreach ($this->category as $key => $row) {
            if ($parent_id == $row['parent_id']) {
                print "<tr>";
                print '<td><input type="checkbox" name="item[]" id="item[]" value="' . $row['id'] . '" class="styled"></td>';
                print "<td><a href='index.php?do=components&action=config&id={$id}&opt=edit_cat&item_id={$row['id']}'>" . $row['id'] . "</a></td>";
                print "<td><a href='index.php?do=components&action=config&id={$id}&opt=edit_cat&item_id={$row['id']}'>";
                for ($i = 0; $i < $level; $i++)
                    print $spacer;
                print $row['title'] . "</a></td>";
                print "<td>" . $row['description'] . "</td>";
                print '<td><div class="table-controls">
				        <a href="index.php?do=components&action=config&id=' . $id . '&opt=delete&item_id=' . $row['id'] . '" class="btn btn-default btn-icon btn-xs tip" title="Удалить"><i class="icon-remove"></i></a>
				        <a href="index.php?do=components&action=config&id=' . $id . '&opt=edit_cat&item_id=' . $row['id'] . '" class="btn btn-default btn-icon btn-xs tip" title="Радактировать"><i class="icon-pencil"></i></a>

			    </div></td>';
                print "</tr>";
                $level++;
                $this->getFCatalogTreeList($key, $level, $spacer, $selected);
                $level--;
            }
        }
        unset($row);
    }

    public function getFCatalogDropList($parent_id, $level = 0, $spacer, $selected = false)
    {
        if ($this->category) {


            foreach ($this->category as $key => $row) {
                $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "";
                if ($parent_id == $row['parent_id']) {
                    print "<option value=\"" . $row['id'] . "\"" . $sel . ">";
                    for ($i = 0; $i < $level; $i++)
                        print $spacer;

                    print $row['title'] . "</option>\n";
                    $level++;
                    $this->getFCatalogDropList($key, $level, $spacer, $selected);
                    $level--;
                }
            }
            unset($row);
        }
    }

    public function getCatSeoLink($cat)
    {

        $seolink = Registry::get("Core")->doSEO($cat['title']);

        if ($cat['id']) {
            $where = ' AND id<>' . $cat['id'];
        } else {
            $where = '';
        }

        $is_exists = $this->inDB->rows_count('files_cat', "seolink='{$seolink}'" . $where, 1);

        if ($is_exists) {
            $seolink .= '-' . $cat['id'];
        }

        return $seolink;
    }

    public function setFileAccess($id, $for_list)
    {

        if (!sizeof($for_list)) {
            return true;
        }

        $this->clearFileAccess($id);

        foreach ($for_list as $key => $value) {
            $sql = "INSERT INTO files_access (c_id, group_id)
                    VALUES ('$id', '$value')";
            $this->inDB->query($sql);
        }

        return true;
    }

    public function clearFileAccess($id)
    {

        $sql = "DELETE FROM files_access WHERE c_id = '$id'";

        $this->inDB->query($sql);

        return true;
    }

    public function reorder()
    {
        $sql = "SELECT * FROM files_cat ORDER BY id";
        $rs = $this->inDB->query($sql);
        if ($this->inDB->numrows($rs)) {
            $level = array();
            while ($item = $this->inDB->fetch($rs, true)) {
                if (isset($level[$item['NSLevel']])) {
                    $level[$item['NSLevel']] += 1;
                } else {
                    $level[] = 1;
                }
                dbQuery("UPDATE cms_files_cat SET ordering = " . $level[$item['NSLevel']] . " WHERE id=" . $item['id']);
            }
        }
    }

    public function getFileCategory($cat_id)
    {
        if ($cat_id) {
            $category = $this->inDB->getFieldsById('id,title,seolink,icon', 'files_cat', "id=$cat_id");
        }
        return $category;
    }


    public function proccessFCatalog($data)
    {
        $item_id = Core::$post['item_id'];
        ($item_id) ? $this->inDB->update("files_cat", $data, 'id=' . $item_id) : $this->inDB->insert("files_cat", $data);
    }

    public function proccessItemFile($data)
    {
        $f_id = Core::$post['f_id'];
        ($f_id) ? $this->inDB->update("files", $data, 'id=' . $f_id) : $this->inDB->insert("files", $data);
    }

    public function getlinkByTitle($title)
    {
        $result2 = $this->inDB->query("SELECT * FROM files WHERE title = '{$title}'");

        $link = '';
        if ($this->inDB->numrows($result2)) {
            $res = $this->inDB->fetch($result2);
            $path_parts = pathinfo('/uploads/fcatalog/' . $res->filename);
            $ext = strtolower($path_parts['extension']);
            $res->muzic = ($ext == 'mp3');
            $res->icon = (file_exists(PATH . '/components/fcatalog/ftypes/' . $ext . '.png') ? '/components/fcatalog/ftypes/' . $ext . '.png' : '/components/fcatalog/ftypes/none.png');
            $res->mb = $this->inCore->getSize($res->size);
            $link .= '<div class="fileitem_bottom">
              <a href="/fcatalog/download' . $res->id . '.html"><img src="' . $res->icon . '" border="0"/>Скачать</a>
             | загрузок (' . $res->downloads . ') | Размер файла: ' . $res->mb . '


             </div>';
//            $link = '<table border="0" cellpadding="2" cellspacing="0"><tr>';
//            $link .= '<td width="16"><img src="/images/icons/listfiles.png" border="0"</td>';
//            $link .= '<td width=""><a href="/load/url='.$res->filename.'" alt="Скачать">'.basename($res->filename).'</a></td>';
//            $link .= '<td width="">| '.$res->size.' Kб</td>';
////            $link .= '<td width="">| Скачан: '.$downloaded.' раз</td>';
//            $link .= '</tr></table>';
            return $link;
        }
    }

    public function file_download($filename, $mimetype = 'application/octet-stream')
    {
        if (file_exists($filename)) {
            header($_SERVER["SERVER_PROTOCOL"] . ' 200 OK');
            header('Content-Type: ' . $mimetype);
            header('Last-Modified: ' . gmdate('r', filemtime($filename)));
            header('ETag: ' . sprintf('%x-%x-%x', fileinode($filename), filesize($filename), filemtime($filename)));
            header('Content-Length: ' . (filesize($filename)));
            header('Connection: close');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '";');

            $f = fopen($filename, 'r');
            while (!feof($f)) {
                echo fread($f, 1024);
                flush();
            }
            fclose($f);
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
            header('Status: 404 Not Found');
        }
        exit;
    }

    public function getCats($id)
    {
        $sql = "SELECT * FROM files_cat WHERE parent_id='{$id}'";

        $result = $this->inDB->query($sql);
        if ($this->inDB->numrows($result)) {
            while ($cat = $this->inDB->fetch($result, true)) {
                if (!$cat['icon']) {
                    $cat['icon'] = "/components/fcatalog/img/noimage.jpg";
                } else {
                    $cat['icon'] = "/images/fcatalog/" . $cat['icon'];
                }
                $cats[] = $cat;
            }
        }
        return $cats;
    }

    public function getVotingBlock($f_id){
        $inUser = Registry::get("Users");
        $user_id = $inUser->userid;
//        $is_vote = $this->inDB->get_field('vote','files_rating',"f_id={$f_id} AND user_id={$user_id}");
        $votes = $this->inDB->rows_count('files_rating',"f_id='{$f_id}'");
        $total =	$this->inDB->query("SELECT SUM(vote) as votes FROM cms_files_rating WHERE f_id='{$f_id}'");
        $rat = $this->inDB->fetch($total,true);
        $rating = round( ($rat['votes'] /  $votes), 0 );
        $is_my = $this->inDB->getFieldById('user_id','files',"id='{$f_id}' AND user_id='{$user_id}'");
//        && !$is_vote
        if ($user_id  && $is_my) {

            $ratform = '<ul class="voting">
		<li class="one"><a href="#" title="плохо" onclick="javascript:doRate(1, '.$f_id.'); return false;" ';  if ($rating ==1) {$ratform .= 'class="cur"';}
            $ratform .='>1</a></li>
		<li class="two"><a href="#" title="приемлимо" onclick="javascript:doRate(2, '.$f_id.');return false;" '; if ($rating ==2) {$ratform .= 'class="cur"';}
            $ratform .= '>2</a></li>
		<li class="three"><a href="#" title="нормально" onclick="javascript:doRate(3, '.$f_id.');return false;" ';  if ($rating ==3) {$ratform .= 'class="cur"';}
            $ratform .= '>3</a></li>
		<li class="four"><a href="#" title="хорошо" onclick="javascript:doRate(4, '.$f_id.');return false;" ';  if ($rating ==4) {$ratform .= 'class="cur"';}
            $ratform .= '>4</a></li>
		<li class="five"><a href="#" title="отлично" onclick="javascript:doRate(5, '.$f_id.');return false;" '; if ($rating ==5) {$ratform .= 'class="cur"';}
            $ratform .= '>5</a></li>
	</ul> <span>('.$votes.' голосов)</span>';
        }
        else {
            $ratform = '<ul class="voting">
		<li class="one"><a href="#" title="плохо" onclick="return false;" ';  if ($rating ==1) {$ratform .= 'class="cur"';}
            $ratform .='>1</a></li>
		<li class="two"><a href="#" title="приемлимо" onclick="return false;" '; if ($rating ==2) {$ratform .= 'class="cur"';}
            $ratform .= '>2</a></li>
		<li class="three"><a href="#" title="нормально" onclick="return false;" ';  if ($rating ==3) {$ratform .= 'class="cur"';}
            $ratform .= '>3</a></li>
		<li class="four"><a href="#" title="хорошо" onclick="return false;" ';  if ($rating ==4) {$ratform .= 'class="cur"';}
            $ratform .= '>4</a></li>
		<li class="five"><a href="#" title="отлично" onclick="return false;" '; if ($rating ==5) {$ratform .= 'class="cur"';}
            $ratform .= '>5</a></li>
	</ul> <span>('.$votes.' голосов)</span>';
        }

        return $ratform;
    }
}