<?php

/**
 * Created by PhpStorm.
 * User: IDeveloper
 * Date: 25.10.2015
 * Time: 19:05
 */
class Photos
{

    public static $db;
    public $photolist = array();

    public function __construct()
    {
        self::$db = Registry::get("DataBase");
        $this->loadPhotos();
    }


    private function loadPhotos()
    {
        $sql = "select * from photoalbums  order by ordering,parent_id";
        $query = self::$db->query($sql);
        if (self::$db->numrows($query)) {
            while ($row = self::$db->fetch($query)) {
                $this->photolist[$row->id] = array(
                    'id' => $row->id,
                    'parent_id' => $row->parent_id,
                    'title' => $row->title,
                    'description' => $row->title,
                    'published' => $row->published,
                    'showdate' => $row->showdate,
                    'showtags' => $row->showtags,
                    'ordering' => $row->ordering,
                    'orderby' => $row->orderby,
                    'orderto' => $row->orderto,
                    'showtype' => $row->showtype,
                    'public' => $row->public,
                    'maxcols' => $row->maxcols,
                    'cssprefix' => $row->cssprefix,
                    'perpage' => $row->perpage,
                    'thumb1' => $row->thumb1,
                    'thumb2' => $row->thumb2,
                    'thumbsqr' => $row->thumbsqr,
                    'uplimit' => $row->uplimit,
                    'config' => $row->config
                );
            }
        }
    }

    public function getCategoryDropList($parent_id, $level = 0, $spacer, $selected = false)
    {
        if ($this->photolist) {
            foreach ($this->photolist as $key => $item) {
                $sel = ($item['id'] == $selected) ? " selected=\"selected\"" : "";
                if ($parent_id == $item['parent_id']) {
                    print "<option value=\"" . $item['id'] . "\"" . $sel . ">";
                    for ($i = 0; $i < $level; $i++)
                        print $spacer;
                    print $item['title'] . "</option>\n";
                    $level++;
                    $this->getCategoryDropList($key, $level, $spacer, $selected);
                    $level--;
                }
            }
            unset($item);
        }
    }


    public function img_resize($src, $dest, $maxwidth, $maxheight = 160, $is_square = false, $watermark = false, $rgb = 0xFFFFFF, $quality = 95)
    {
        if (!file_exists($src)) return false;
        $upload_dir = dirname($dest);
        if (!is_writable($upload_dir)) {
            @chmod($upload_dir, 0777);
        }
        $size = getimagesize($src);
        if ($size === false) return false;
        $new_width = $size[0];
        $new_height = $size[1];

        $formats = array(
            1 => 'gif',
            2 => 'jpeg',
            3 => 'png',
            6 => 'wbmp',
            15 => 'wbmp'
        );
        $format = @$formats[$size[2]];
        $icfunc = "imagecreatefrom" . $format;
        if (!function_exists($icfunc)) return false;

        $isrc = $icfunc($src);

        if (($new_height <= $maxheight) && ($new_width <= $maxwidth)) {
            if ($watermark) {
                self::img_watermark($isrc, $new_width, $new_height);
                imagejpeg($isrc, $dest, $quality);
            } else {
                @copy($src, $dest);
            }
            return true;
        }

        if ($is_square) {

            $idest = imagecreatetruecolor($maxwidth, $maxwidth);
            imagefill($idest, 0, 0, $rgb);
            if ($new_width > $new_height)
                imagecopyresampled($idest, $isrc, 0, 0, round((max($new_width, $new_height) - min($new_width, $new_height)) / 2), 0, $maxwidth, $maxwidth, min($new_width, $new_height), min($new_width, $new_height));
            if ($new_width < $new_height)
                imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $maxwidth, $maxwidth, min($new_width, $new_height), min($new_width, $new_height));
            if ($new_width == $new_height)
                imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $maxwidth, $maxwidth, $new_width, $new_width);

        } else {

            if ($new_width > $maxwidth) {

                $wscale = $maxwidth / $new_width;
                $new_width *= $wscale;
                $new_height *= $wscale;

            }
            if ($new_height > $maxheight) {

                $hscale = $maxheight / $new_height;
                $new_width *= $hscale;
                $new_height *= $hscale;

            }

            $idest = imagecreatetruecolor($new_width, $new_height);
            imagefill($idest, 0, 0, $rgb);
            imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);

        }

        if ($watermark) {
            self::img_watermark($idest, $new_width, $new_height);
        }

        imageinterlace($idest, 1);

        // вывод картинки и очистка памяти
        imagejpeg($idest, $dest, $quality);
        imagedestroy($isrc);
        imagedestroy($idest);

        return true;

    }

    public function img_add_watermark($src)
    {
        $size = getimagesize($src);
        if ($size === false) return false;
        $format = mb_strtolower(mb_substr($size['mime'], mb_strpos($size['mime'], '/') + 1));
        $icfunc = "imagecreatefrom" . $format;
        if (!function_exists($icfunc)) return false;
        $isrc = $icfunc($src);
        self::img_watermark($isrc, $size[0], $size[1]);
        imagejpeg($isrc, $src, 95);
    }

    public function img_watermark(&$img, $w, $h)
    {

        $inConf = Registry::get("Config");
        if (!$inConf->wmark) {
            return;
        }
        $wm_file = PATH . '/images/' . $inConf->wmark;
        if (!file_exists($wm_file)) {
            return;
        }
        $size = getimagesize($wm_file);
        $wm = imagecreatefrompng($wm_file);
        $wm_w = $size[0];
        $wm_h = $size[1];
        $wm_x = $w - $wm_w;
        $wm_y = $h - $wm_h;
        imagecopyresampled($img, $wm, $wm_x, $wm_y, 0, 0, $wm_w, $wm_h, $wm_w, $wm_h);
    }

    public function getAllPhotos()
    {
        $photos = array();
        if (!empty(Core::$post['filter'])) {
            $where = " where phf.title like '%" . Core::$post['filter'] . "%'";
        }
        $query = self::$db->query("select phf.*,phm.title as album from photofiles phf left join photoalbums phm on phm.id=phf.photoalbumid {$where} ");

        $pager = Registry::get("Paginator");
        $counter = self::$db->numrows($query);
        $pager->items_total = $counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }
        $query2 = self::$db->query("select phf.*,phm.title as album from photofiles phf left join photoalbums phm on phm.id=phf.photoalbumid {$where} " . $pager->limit);
        if (self::$db->numrows($query)) {
            while ($rows = self::$db->fetch($query, true)) {
                $photos[] = $rows;
            }
        }
        return $photos;
    }

    public function getPhotos($show_all = false, $is_comments_count = false)
    {
        $pub_where = ($show_all ? '1=1' : 'f.published = 1');
        $sql = "SELECT f.*,a.title as cat_title
                        FROM photofiles f
                        INNER JOIN photoalbums a ON a.id = f.photoalbumid AND a.published = 1
                        where {$pub_where} ";

        $result = self::$db->query($sql);
        if (!self::$db->numrows($result)) {
            return false;
        }
        $photos = array();
        while ($photo = self::$db->fetch($result, true)) {
            if ($is_comments_count) {
//                $photo['comments'] = cmsCore::getCommentsCount(($photo['owner']=='photos' ? 'photo' : 'club_photo'), $photo['id']);

            }
            $photo['pubdate'] = $photo['pubdate'];
            $photos[] = $photo;

        }
    return $photos;
    }
}