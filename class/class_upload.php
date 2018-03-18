<?php

/**
 * Created by PhpStorm.
 * User: IDeveloper
 * Date: 28.10.2015
 * Time: 21:59
 */
class Uploads{

    public $upload_path='';
    public $filename='';
    public $medium_path="medium/";
    public $small_path="small/";
    public $small_size_w  = 96;
    public $small_size_h  = '';
    public $medium_size_w = 480;
    public $medium_size_h = '';
    public $thumbsqr      = true;
    public $is_watermark  = true;
    public $is_saveorig   = 1;
    public $only_medium   = false;
    public $input_name='Filedata';
    public static $core;
    private $realfile;
    private $allowext=array('jpg','jpeg','gif','png','bmp');
    private $uploadphoto;
    private $uploadthumb=array();
    private $uploadErrors;
    private $max_size;
    private $file=array();
    public static $ufile=null;


    public static $width = 80; //new image width
    public static $height = 80; //new image height
    const proportional = false; //keep image proportional, default is no
    public static $output = "file"; //name of the new file (include path if needed)
    public static $delete_original = false; //if true the original image will be deleted
    const use_linux_commands = false; //if set to true will use "rm" to delete the image, if false will use PHP unlink
    public static $quality = 100; // enter 1-100 (100 is best quality) default is 100

    public function __construct()
    {
        self::$core=Registry::get("Core");
        $this->max_size=ini_get('upload_max_filesize');
        $this->max_size = str_ireplace(array('M','K'), array('Mb','Kb'), $this->max_size);
        $this->uploadErrors = array(
            UPLOAD_ERR_OK => 'Файл успешно загружен',
            UPLOAD_ERR_INI_SIZE => 'Размер файла превышает допустимый'.' &mdash; '.$this->max_size,
            UPLOAD_ERR_FORM_SIZE => 'Размер файла превышает допустимый',
            UPLOAD_ERR_PARTIAL => 'Файл был загружен не полностью.',
            UPLOAD_ERR_NO_FILE =>  'Файл не был загружен',
            UPLOAD_ERR_NO_TMP_DIR => 'Не найдена папка для временных файлов на сервере',
            UPLOAD_ERR_CANT_WRITE => 'Ошибка записи файла на диск',
            UPLOAD_ERR_EXTENSION => 'Загрузка файла была прервана PHP-расширением'
        );
    }

    public function upload($old_file=false){

        if (!$this->upload_path) return false;
        if (!empty($_FILES[$this->input_name]['name'])){

            $input_name=preg_replace('/[^a-zA-Zа-яёЁА-Я0-9\.\-_ ]/ui', '',mb_substr(basename(strval($_FILES[$this->input_name]['name'])), 0, 160));
            $ext=mb_strtolower(pathinfo($input_name,PATHINFO_EXTENSION));

            $realfile = str_replace('.'.$ext, '', $input_name);

            if (!in_array($ext,array('jpg','jpeg','gif','png','bmp'))){return false;}
            $this->filename=md5($realfile).'.'.$ext;
//                $this->filename?:md5(time().$realfile).'.'.$ext;

//            echo $this->filename." ".md5(time().$realfile).'.'.$ext;


            $this->uploadphoto=$this->upload_path. $this->filename;
            $this->uploadthumb['small']=$this->upload_path. $this->small_path.$this->filename;
            $this->uploadthumb['medium']=$this->upload_path. $this->medium_path.$this->filename;

            $this->uploadphoto = $this->upload_path . $this->filename;
//            echo $this->uploadphoto;
            $source=  $_FILES[$this->input_name]['tmp_name'];
            $error=   $_FILES[$this->input_name]['error'];

            if ($this->moveUploadedFile($source,$this->uploadphoto,$error)){


                if (!$this->small_size_h) { $this->small_size_h = $this->small_size_w; }
                if (!$this->medium_size_h) { $this->medium_size_h = $this->medium_size_w; }

                if(!$this->only_medium){
                    if(!is_dir($this->upload_path . $this->small_path)) { @mkdir($this->upload_path . $this->small_path); }
                    @self::img_resize($this->uploadphoto, $this->uploadthumb['small'], $this->small_size_w, $this->small_size_h, $this->thumbsqr);
                }
                if(!is_dir($this->upload_path . $this->medium_path)) { @mkdir($this->upload_path . $this->medium_path); }
                @self::img_resize($this->uploadphoto, $this->uploadthumb['medium'], $this->medium_size_w, $this->medium_size_h, false, false);

                if($this->is_watermark) { @self::img_add_watermark($this->uploadthumb['medium']); }
                if(!$this->is_saveorig) { if($this->is_watermark) { @self::img_add_watermark($this->uploadphoto); }}
                $file['filename'] = $this->filename;
                $file['realfile'] = $this->realfile;
            }else{
                return false;
            }
        }else{
            return false;
        }
        return $file;
    }

    public function moveUploadedFile($source, $destination, $errorCode){
//        if ($errorCode!==UPLOAD_ERR_OK && isset($this->uploadErrors[$errorCode])){
//
//            return false;
//        }else{
            $upload_dir=dirname($destination);

            if (!is_writable($upload_dir)){ @chmod($upload_dir,0777);}
            $pi=pathinfo($destination);
                while (mb_strpos($pi['basename'],'htm') || mb_strpos($pi['basename'],'php') || mb_strpos($pi['basename'],'ht')){
                    $pi['basename'] = str_ireplace(array('htm','php','html'), '', $pi['basename']);
                }
            $destination = $pi['dirname'] .DIRECTORY_SEPARATOR. $pi['basename'];
            return @move_uploaded_file($source, $destination);
//        }

    }

    public function img_resize($src, $dest, $maxwidth, $maxheight=160, $is_square=false, $watermark=false, $rgb=0xFFFFFF, $quality=100){
        if (!file_exists($src)) return false;
        $upload_dir = dirname($dest);
        if (!is_writable($upload_dir)){ @chmod($upload_dir, 0777); }
        $size = getimagesize($src);
        if ($size === false) return false;
        $new_width   = $size[0];
        $new_height  = $size[1];

        $formats = array(
            1  => 'gif',
            2  => 'jpeg',
            3  => 'png',
            6  => 'wbmp',
            15 => 'wbmp'
        );
        $format = @$formats[$size[2]];
        $icfunc = "imagecreatefrom" . $format;
        if (!function_exists($icfunc)) return false;

        $isrc = $icfunc($src);

        if (($new_height <= $maxheight) && ($new_width <= $maxwidth)){
            if ($watermark) {
                self::img_watermark($isrc, $new_width, $new_height);
                imagejpeg($isrc,$dest,$quality);
            } else {
                @copy($src, $dest);
            }
            return true;
        }

        if($is_square){

            $idest = imagecreatetruecolor($maxwidth,$maxwidth);
            imagefill($idest, 0, 0, $rgb);
           if ($new_width>$new_height)
                imagecopyresampled($idest, $isrc, 0, 0, round((max($new_width,$new_height)-min($new_width,$new_height))/2), 0, $maxwidth, $maxwidth, min($new_width,$new_height), min($new_width,$new_height));
           if ($new_width<$new_height)
                imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $maxwidth, $maxwidth, min($new_width,$new_height), min($new_width,$new_height));
           if ($new_width==$new_height)
                imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $maxwidth, $maxwidth, $new_width, $new_width);

        } else {

            if($new_width>$maxwidth){

                $wscale = $maxwidth/$new_width;
                $new_width  *= $wscale;
                $new_height *= $wscale;

            }
            if($new_height>$maxheight){

                $hscale = $maxheight/$new_height;
                $new_width  *= $hscale;
                $new_height *= $hscale;

            }

            $idest = imagecreatetruecolor($new_width, $new_height);
            imagefill($idest, 0, 0, $rgb);
            imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);

        }

        if ($watermark) { self::img_watermark($idest, $new_width, $new_height); }

        imageinterlace($idest,1);

        // вывод картинки и очистка памяти
        imagejpeg($idest,$dest,$quality);
        imagedestroy($isrc);
        imagedestroy($idest);

        return true;

    }

    public function img_add_watermark($src){
        $size = getimagesize($src);
        if ($size === false) return false;
        $format = mb_strtolower(mb_substr($size['mime'], mb_strpos($size['mime'], '/')+1));
        $icfunc = "imagecreatefrom" . $format;
        if (!function_exists($icfunc)) return false;
        $isrc = $icfunc($src);
        self::img_watermark($isrc, $size[0], $size[1]);
        imagejpeg($isrc,$src,95);
    }

    public function img_watermark(&$img, $w, $h){

        $inConf = Registry::get("Config");
        if (!$inConf->wmark) { return; }
        $wm_file = 	PATH.'/images/'.$inConf->wmark;
        if (!file_exists($wm_file)) { return; }
        $size = getimagesize($wm_file);
        $wm = imagecreatefrompng($wm_file);
        $wm_w = $size[0];
        $wm_h = $size[1];
        $wm_x = $w - $wm_w;
        $wm_y = $h - $wm_h;
        imagecopyresampled($img, $wm, $wm_x, $wm_y, 0, 0, $wm_w, $wm_h, $wm_w, $wm_h);

    }

    public function rotateImg($file='',$ext)
    {
        if (!($file && $this->upload_dir && $ext)) { return false; }
        $image = imagecreatefromstring(file_get_contents($file));
        $exif = exif_read_data($file);
        if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 8:
                    $image = imagerotate($image,90,0);
                    break;
                case 3:
                    $image = imagerotate($image,180,0);
                    break;
                case 6:
                    $image = imagerotate($image,-90,0);
                    break;
                default:
                    return false;
            }

            switch($ext){
                case "jpg":
                case "jpeg":
                    imagejpeg($image,$file,100);
                    break;
                case "png":
                    imagepng($image,$file,100);
                    break;
                case "gif":
                    imagegif($image,$file);
                    break;
                default:
                    return false;
            }
            return true;
        }
        return false;
    }

    public static function doResize()
    {

        if (self::$height <= 0 && self::$width <= 0)
            return false;

        // Setting defaults and meta
        $info = getimagesize(self::$ufile);
        $image = '';
        $final_width = 0;
        $final_height = 0;
        list($width_old, $height_old) = $info;

        // Calculating proportionality
        if (self::proportional) {
            if (self::$width == 0)
                $factor = self::$height / $height_old;
            elseif (self::$height == 0)
                $factor = self::$width / $width_old;
            else
                $factor = min(self::$width / $width_old, self::$height / $height_old);

            $final_width = round($width_old * $factor);
            $final_height = round($height_old * $factor);
        } else {
            $final_width = (self::$width <= 0) ? $width_old : self::$width;
            $final_height = (self::$height <= 0) ? $height_old : self::$height;
        }

        // Loading image to memory according to type
        switch ($info[2]) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif(self::$ufile);
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg(self::$ufile);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng(self::$ufile);
                break;
            default:
                return false;
        }


        // This is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor($final_width, $final_height);
        if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
            $transparency = imagecolortransparent($image);

            if ($transparency >= 0) {
                $transparent_color = imagecolorsforindex($image, $trnprt_indx);
                $transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
                imagefill($image_resized, 0, 0, $transparency);
                imagecolortransparent($image_resized, $transparency);
            } elseif ($info[2] == IMAGETYPE_PNG) {
                imagealphablending($image_resized, false);
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
                imagefill($image_resized, 0, 0, $color);
                imagesavealpha($image_resized, true);
            }
        }
        imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);

        // Taking care of original, if needed
        if (self::$delete_original) {
            if (self::use_linux_commands)
                exec('rm ' . self::$ufile);
            else
                @unlink(self::$ufile);
        }

        switch (strtolower(self::$output)) {
            case 'browser':
                $mime = image_type_to_mime_type($info[2]);
                header("Content-type: $mime");
                self::$output = null;
                break;
            case 'file':
                self::$output = self::$ufile;
                break;
            case 'return':
                return $image_resized;
                break;
            default:
                break;
        }

        switch ($info[2]) {
            case IMAGETYPE_GIF:
                imagegif($image_resized, self::$output);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($image_resized, self::$output, self::$quality);
                break;
            case IMAGETYPE_PNG:
                self::$quality = 9 - (int)((0.9 * self::$quality) / 10.0);
                imagepng($image_resized, self::$output, self::$quality);
                break;
            default:
                return false;
        }
        return true;
    }


}

?>