<script src="/component/video/player/uppod/uppod.js" type="text/javascript"></script>
<script src="/component/video/player/uppod/swfobject.js" type="text/javascript"></script>
<style>
    #SFvideo {
        position: absolute;
        top: 0;
        left: 0;
        overflow:hidden;
        width:100%;
        height:100%;
    }
</style>
<?php
define('PATH', $_SERVER['DOCUMENT_ROOT']);
include($_SERVER['DOCUMENT_ROOT'] . '/include/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/class/config.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/class/class_db.php');
include($_SERVER['DOCUMENT_ROOT'] . '/class/engine.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/class/users.class.php');
$db = DB::getInstance();
$ker = Engine::getInstance();
$user = Users::getInstance();

$cfg = getComponentConfig('video');

if (!$cfg['cat_ul']){$cfg['cat_ul'] = "ul"; }
if (!$cfg['cat_li']){ $cfg['cat_li'] = "li"; }
if (!$cfg['title_site']){$cfg['title_site'] = "Видео галерея"; }
if (!$cfg['number']){$cfg['number'] = 12; }
if (!$cfg['width']){$cfg['width'] = 480; }
if (!$cfg['height']){$cfg['height'] = 300; }
if (!$cfg['h1name']){ $cfg['h1name'] = "H1 название";   }
if (!$cfg['text']){$cfg['text'] = "Описание";    }
if (!$cfg['kolich']){$cfg['kolich'] = 5;    }
if (!$cfg['cat_ul_class']){$cfg['cat_ul_class'] = "menu_video";}

if (isset($_REQUEST['video_id'])){$video_id=$_REQUEST['video_id'];}
$sql="select * from video where id='{$video_id}'";
$result=$db->query($sql);
  if ($db->num_rows($result)){
      $video=$db->fetch_assoc($result);

      if ($video['is_video_type']=='url'){
?>
<div id="SFvideo">
    <script type="text/javascript">this.videoplayer = new Uppod({m:"video",comment:"<?php echo $video['title'];?>",uid:"SFvideo",file:"<?php echo $video['file']?>"});
    </script>


</div>
<?}
if ($video['is_video_type']=='file'){
 ?>
    <script type="text/javascript">this.videoplayer = new Uppod({m:"video",comment:"<?php echo $video['title'];?>",uid:"SFvideo",file:"/uploads/videofiles/flv/<?php echo $video['file']?>"});
    </script>
<?php }

if ($video['is_video_type']=='embed'){
    ?>
    <noindex><?php echo $video['file']?></noindex>
<?php }?>



<?php  }?>
