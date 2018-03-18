<?php
function viewVIDEO($link){
    $db = DB::getInstance();
    $ker = Engine::getInstance();
    $sql = 'SELECT * FROM video WHERE id = "'.$link.'"';
    $result = $db->query($sql) ;
    if ($db->num_rows($result)){
        while($item = $db->fetch_assoc($result)){
            $off = $item;
        }
    }

    return $off;
}

 function comNewVIDEO($limit){
     $db = DB::getInstance();
    $sql = "SELECT * FROM video ORDER BY RAND() LIMIT ".$limit;
    $result = $db->query($sql) ;
    if ($db->num_rows($result)){
        while($item = $db->fetch_assoc($result)){
            $off[] = $item;
        }
    }

    return $off;
}

function video(){
    $db = DB::getInstance();
    $ker = Engine::getInstance();
    $user=Users::getInstance();
    if (isset($_REQUEST['id'])) {$id = $_REQUEST['id'];} else {$id = 0; }
    if (isset($_REQUEST['do'])) {$do = $_REQUEST['do'];} else {$do = 'view';}
    if (isset($_REQUEST['page'])) {$page=$_REQUEST['page'];} else{$page=1;}

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

    if ($do=='view'){
        $perpage = $cfg['number'];
        $video=array();
        $sql = "SELECT v.*,vc.title as category,DATE_FORMAT(v.pubdate,'%d.%m.%Y') as pubdate FROM video v left join video_category vc on vc.id=v.category_id ORDER BY v.`id` DESC LIMIT ".(($page-1)*$perpage).", $perpage";
        $total=$db->num_rows($db->query("select * from video"));
        $result = $db->query($sql) ;
        if ($db->num_rows($result)){
            while ($item=$db->fetch_assoc($result)){
                $next=sizeof($video);
                $video[]=$item;
                $video[$next]['com']=getCommentCount('video',$item['id']);
//                $video[$next]['pubdate']=$ker->cmsRusDate($item['pubdate']);
            }
        }
        $smarty=$ker->smartyInitComponent();
        $smarty->assign("adm",$cfg);
        $smarty->assign('admin', $user->getUserIsAdmin($_SESSION['user']['id']));
        $smarty->assign("video",$video);
//        $smarty->assign("com",$comment);
        $smarty->assign('pagebar',  $ker->getPagebar($total, $page, $perpage, '/video/list%page%'));
        $smarty->display("com_video_view.tpl");
    }


    if ($do=='read'){
        $read=viewVIDEO($id);
        $cat = $db->dbGetFields('video_category', 'id = '.$read['category_id'], 'title,id');
        $usr = $db->dbGetFields('users', 'id = '.$read['user_id'], 'username,nickname');
        $add_video =comNewVIDEO($cfg['kolich']);

        if ($read['user_id'] != $user->getUserID($_SESSION['user']['id'])){
            $db->query("UPDATE video SET hits = hits + 1 WHERE id = '".$read['id']."'") ;
        }else{
            $db->query("UPDATE video SET hits = hits + 1 WHERE id = '".$read['id']."'") ;
        }

        if ($user->getUserID($_SESSION['user']['username'])!= 0){	?>
            <script type="text/javascript">
                $(document).ready(function() {
                    var starsAll = <?php echo 0;?>;//Всего звезд
                    var voteAll = <?php echo 0;?>;//Всего голосов
                    var idArticle = <?php echo $read['id'];?>;//id статьи
                    var starWidth = 17;//ширина одной звезды
                    var rating = (starsAll/voteAll); //Старый рейтинг
                    rating = Math.round(rating*100)/100;
                    if(isNaN(rating)){
                        rating = 0;
                    }
                    var ratingResCss = rating*starWidth; //старый рейтинг в пикселях

                    $("#ratDone").css("width", ratingResCss);
                    $("#ratStat").html("Рейтинг: <strong>"+rating+"</strong> Голосов: <strong>"+voteAll+"</strong>");

                    <?php
                    $used_ips = $sql["used_ips"]; // вытаскиваем все поле used_ips оно будет содеражать все ip адреса проголосовавших разделенные |
                    $ipsArray = explode("|",$used_ips);
                    $ip = $_SERVER['REMOTE_ADDR'];
                    if(!in_array($ip,$ipsArray)){ //Чтобы предотвратить повторное голосование после обновления, мы просто скрываем функции отвечаюшие за это
                    ?>
                    var coords;
                    var stars;	//кол-во звезд при наведении
                    var ratingNew;	//Новое количество звезд

                    $("#rating").mousemove(function(e){
                        var offset = $("#rating").offset();
                        coords = e.clientX - offset.left; //текушая координата
                        stars = Math.ceil(coords/starWidth);
                        starsCss = stars*starWidth;
                        $("#ratHover").css("width", starsCss).attr("title", stars+" из 10");
                    });
                    $("#rating").mouseout(function(){
                        $("#ratHover").css("width", 0);
                    });
                    $("#rating").click(function(){
                        starsNew = stars + starsAll; //новое количество звезд
                        voteAll += 1;
                        var ratingNew = starsNew/voteAll;
                        ratingNew = Math.round(ratingNew*100)/100;
                        var razn = Math.round((rating - ratingNew)*200);//вычислям разницу между новым и старым рейтингом для анимации
                        razn = Math.abs(razn);

                        var total = Math.round(ratingNew*starWidth);
                        $.ajax({
                            type: "GET",
                            url: "/component/video/rating.php",
                            data: {"id": idArticle, "rating": stars},
                            cache: false,
                            success: function(response){
                                if(response == 1){
                                    var newRat = response+"px";
                                    $("#ratHover").css("display", "none");
                                    $("#ratDone").animate({width: total},razn);
                                    $("#ratBlocks").show();
                                    $("#ratStat").html("Рейтинг: <strong>"+ratingNew+"</strong> Голосов: <strong>"+voteAll+"</strong>");
                                }else{
                                    $("#ratStat").text(response);
                                }
                            }
                        });
                        return false;
                    });
                    <?php
                    }
                    ?>
                });

            </script>


        <?php
        }
        $smarty=$ker->smartyInitComponent();
        $smarty->assign("read",$read);
        $smarty->assign('userid', $user->getUserID($_SESSION['user']['id']));
        $smarty->assign("cat",$cat);
        $smarty->assign("usr",$usr);
        $smarty->assign('width', $cfg['width']);
        $smarty->assign('height', $cfg['height']);
        $smarty->register_function("comments","SmartyComments");
        $smarty->assign("ad_v",$add_video);
        $smarty->display("com_video_read.tpl");
    }
}

?>