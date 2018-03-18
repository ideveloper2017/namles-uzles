<!--<link rel="stylesheet" type="text/css" href="/components/photos/fancybox/jquery.fancybox.css" media="screen"/>-->
<!--<script type="text/javascript" src="/components/photos/fancybox/jquery-1.3.2.min.js"></script>-->
<!--<script type="text/javascript" src="/components/photos/fancybox/jquery.easing.1.3.js"></script>-->
<!--<script type="text/javascript" src="/components/photos/fancybox/jquery.fancybox-1.2.1.pack.js"></script>-->
<!--<style>-->
<!--    a {-->
<!--        outline: none;-->
<!--    }-->
<!---->
<!--    div#wrap {-->
<!--        width: 600px;-->
<!--        margin: 10px auto;-->
<!--    }-->
<!---->
<!--    .img {-->
<!--        border: 1px solid #CCC;-->
<!--        padding: 2px;-->
<!--        margin: 10px 5px 10px 0;-->
<!--    }-->
<!---->
<!--    .green {-->
<!--        color: #060;-->
<!--        font-size: 14px-->
<!--    }-->
<!--</style>-->
<!--<script type="text/javascript">-->
<!--    $(document).ready(function () {-->
<!--        $("a.gallery, a.iframe").fancybox();-->
<!--//        url = $("a.modalbox").attr('href').replace("for_spider","content2");-->
<!--//        $("a.modalbox").attr("href", url);-->
<!--//        $("a.modalbox").fancybox(-->
<!--//                {-->
<!--//                    "frameWidth" : 400,-->
<!--//                    "frameHeight" : 400-->
<!--//                });-->
<!---->
<!--        $("a.gallery2").fancybox(-->
<!--            {-->
<!--                "padding": 20, // отступ контента от краев окна-->
<!--                "imageScale": false, // Принимает значение true - контент(изображения) масштабируется по размеру окна, или false - окно вытягивается по размеру контента. По умолчанию - TRUE-->
<!--                "zoomOpacity": false, // изменение прозрачности контента во время анимации (по умолчанию false)-->
<!--                "zoomSpeedIn": 1000, // скорость анимации в мс при увеличении фото (по умолчанию 0)-->
<!--                "zoomSpeedOut": 1000, // скорость анимации в мс при уменьшении фото (по умолчанию 0)-->
<!--                "zoomSpeedChange": 1000, // скорость анимации в мс при смене фото (по умолчанию 0)-->
<!--                "frameWidth": 700, // ширина окна, px (425px - по умолчанию)-->
<!--                "frameHeight": 600, // высота окна, px(355px - по умолчанию)-->
<!--                "overlayShow": true, // если true затеняят страницу под всплывающим окном. (по умолчанию true). Цвет задается в jquery.fancybox.css - div#fancy_overlay-->
<!--                "overlayOpacity": 0.8, // Прозрачность затенения 	(0.3 по умолчанию)-->
<!--                "hideOnContentClick": false, // Если TRUE  закрывает окно по клику по любой его точке (кроме элементов навигации). Поумолчанию TRUE-->
<!--                "centerOnScroll": false // Если TRUE окно центрируется на экране, когда пользователь прокручивает страницу-->
<!---->
<!--            });-->
<!---->
<!--        $("#menu a, .anim").hover(function () {-->
<!--                $(this).animate({"paddingLeft": "10px"}, 300)-->
<!--            },-->
<!--            function () {-->
<!--                $(this).animate({"paddingLeft": "0"}, 300);-->
<!--            });-->
<!---->
<!--        $("a.iframe").fancybox(-->
<!--            {-->
<!--                "frameWidth": 800, // ширина окна, px (425px - по умолчанию)-->
<!--                "frameHeight": 600 // высота окна, px(355px - по умолчанию)-->
<!---->
<!--            });-->
<!--    });-->
<!--</script>-->

<?php //$col=1; ?>
<!--<div class="grid6_5">-->
<!--    --><?php //foreach($albums as $album){?>
<!--    <div class="con_heading">--><?php //echo $album['title'];?><!--</div>-->
<!--    <table cellspacing="2" border="0" width="100%">-->
<!--        --><?php //foreach($photos as $con){?>
<!--        --><?php //if ($col==1) {?>
<!--        <tr>-->
<!--            --><?php //} ?>
<!--            <td align="center" valign="middle" class="mod_lp_photo" width="25%">-->
<!--                <table width="100%" cellspacing="0" cellpadding="0">-->
<!--                    <tr>-->
<!--                        <td align="center">-->
<!--                            <div class="mod_lp_titlelink"><a href="/photos/photo--><?php //echo $con['id'];?><!--.html" title="{$con.title}">--><?php //$con['title'];?><!--</a>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td valign="middle" align="center">-->
<!--                            <a href="/photos/photo--><?php //echo $con['id'];?><!--.html" title="--><?php //echo $con['title'];?><!--">-->
<!--                                <img class="photo_thumb_img" src="/images/photos/small/--><?php //echo $con['files'];?><!--" alt="--><?php //$con['title'];?><!--"-->
<!--                                     border="0"/>-->
<!--                            </a>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="center">-->
<!--                            <div class="mod_lp_albumlink"><a href="/photos/{$menuid}/{$con.album_id}"-->
<!--                                                             title="{$con.album}">--><?php //$con['album'];?><!--</a></div>-->
<!--                            <div class="mod_lp_details">-->
<!--                                <table cellpadding="2" cellspacing="2" align="center" border="0">-->
<!--                                    <tr>-->
<!--                                        <td><img src="/theme/qbk/images/icons/calendar.png" border="0"/></td>-->
<!--                                        <td>--><?php //echo $con['fpubdate'];?><!--</td>-->
<!--                                        <td><img src="/theme/qbk/images/icons/comment.png" border="0"/></td>-->
<!--                                        <td><a href="/photos/photo{$con.id}'.html#c">0</td>-->
<!--                                        <td><img src="/theme/qbk/images/icons/eye.png" border="0"/></td>-->
<!--                                        <td>--><?php //$con['hits'];?><!--</td>-->
<!--                                    </tr>-->
<!--                                </table>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!---->
<!--                </table>-->
<!--            </td>-->
<!--            --><?php //if ($col>$cfg['maxcols']){ ?>
<!--        </tr>-->
<!--                --><?php //$col=1;} else {?>
<!--                --><?php //$col++; ?>
<!--                    --><?php //}?>
<!--        --><?php //} ?>
<!---->
<!--    </table>-->
<!--    --><?php //}?>
<!---->
<!---->
<!--</div>-->

<div class="flat-project flat-animation" data-animation="flipInY" data-animation-delay="0" data-animation-offset="75%">
<div class="controlnav-folio">
    <ul class="project-filter">
        <li class="active"><a data-filter="*" href="#"><?php echo (Lang::getLangID()=='ru')?'Все':'Ҳаммаси';?></a></li>

        <?php foreach($albums as $album){?>
        <li><a data-filter=".carwash" href="#"><?php echo $album['title'];?></a></li>
<!--        <li><a data-filter=".carglassing" href="#">Car Glassing</a></li>-->
<!--        <li><a data-filter=".carpolishing" href="#">Car Polishing</a></li>-->
        <?php  }?>
    </ul>
</div>
<div class="project-wrap three-columns ">
    <?php foreach($photos as $con){?>
    <div class="object project-item entry carwash">
        <div class="item-wrap ">
            <div class="project-thumb">
                <a href="#">
                    <img src="/images/photos/<?php echo $con['files'];?>" alt="image">
                </a>
            </div>
            <div class="project-info">
                <h3 class="project-title">
                    <a class="" href="#"><?php echo $con['title'];?></a>
                </h3>
                <ul class="project-categories">
                    <li><a href="#"><?php echo $con['album'];?></a></li>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>
</div>
</div>