<link rel="stylesheet" href="/components/fcatalog/css/voting.css" type="text/css" />
<link rel="stylesheet" href="/components/fcatalog/css/fcatalog.css" type="text/css" />
<link rel="stylesheet" href="/components/fcatalog/css/colorbox.css" type="text/css" />
<script type="text/javascript" src="/components/fcatalog/js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="/components/fcatalog/js/voting.js"></script>
<script>
    $(document).ready(function(){
        var $form = $("#abuse");
        $(".group1").colorbox({inline:true,href:$form});
    });
</script>

<div style="display:none">
    <div id="abuse"> <h3>Причина жалобы:</h3>
        <form action="" method="POST" name="abuseform">
            <textarea style="height:100px;width:300px;" name="message"></textarea><br>
            <input type="hidden" name="abusefile" value="<?php echo $file['id']?>" >
            <input type="submit" value="Отправить">
        </form>
    </div></div>

<div class="fileitem_cat" >
    <div class="fileitem_search_box">
        <form method="POST" action="/fcatalog/search.html" name="search">
            <input type="text" name="query" class="f_search" value="Поиск"  onblur="if ($(this).val()==''){ $(this).val('Поиск'); }" onclick="$(this).val('');">
            <a href="#" class="fileitem_search">
                <img src="/components/fcatalog/img/search.png" title="Искать" onclick="javascript: search.submit()">
            </a>
        </form>
    </div>
    <span><a href="/fcatalog/<?php echo $category->seolink?>"><?php echo $category->title?></a></span>
</div>
<div style="clear:both;display:block;height:10px;"></div>
<!--<a href="/fcatalog/add.html">-->
<!--    <img src="/components/fcatalog/img/add.png" alt="Добавить файл" title="Добавить файл" style="margin-bottom:5px; float:right;"></a>-->
<table width="100%" align=" center">
    <tr>  <td>

            <div class="fileitem_title">
                <img src="/components/fcatalog/img/drive.png"> <?php echo $file['title']?>
<!--                --><?php //if ($file['is_my']){ ?>
<!--                &nbsp; <img src="/images/actions/edit.gif"> <a href="/fcatalog/{$file.id}-edit.html">Редактировать</a>-->
<!--                <img src="/images/actions/off.gif"> <a href="/fcatalog/{$file.id}-delete.html">Удалить</a>-->
<!--                --><?php //}?>
                <span><img src="/components/fcatalog/img/folder.png"> Категория: <a href="/fcatalog/<?php echo $category->seolink?>"><?php echo $category->title;?></span></a>
            </div>
            <div class="fileitem_box">
                <div class="votebox" id="voting<?php echo $file['id']?>"><?php echo $file['votes'];?></div>
                <br>
                <br>
                <span><?php echo $file['content'];?></span><br>
                <?php if ($is_user) {?>
                <?php if ($file['muzic']) {?>Прослушать:
                <object type="application/x-shockwave-flash" data="/components/fcatalog/player.swf" id="audioplayer2" height="24" width="290">
                    <param name="movie" value="/components/fcatalog/player.swf">
                    <param name="FlashVars" value="playerID=audioplayer2&soundFile=/components/fcatalog/play.php?file=<?php echo $file['filename']?>">
                    <param name="quality" value="high">
                    <param name="menu" value="false">
                    <param name="wmode" value="transparent">
                </object><br>
                <?php }?>
                <a class="group1" href="#abuse"><img src="/components/fcatalog/img/abuse.png" title="Пожаловаться на файл!"></a> &nbsp;|<?php }?>
                <a href="/fcatalog/<?php echo $file['id'];?>-download.html"> <img src="<?php echo $file['icon'];?>">Скачать</a> |
                <?php if ($file['size']) {?> Размер файла: <?php echo $file['mb']; ?>  <?php }?>



            <div class="fileitem_bottom">
                <a href="/fcatalog/view_file-<?php echo $item['id']?>.html"><?php echo $item['title'];?></a>
<!--                добавил:<a href="/fcatalog/{$file.login}/files.html">{$file.nickname}</a> |-->
                <img src="/components/fcatalog/img/calendar.png"> <?php echo $file['pubdate']?>
                | просмотров (<?php echo $file['hits'];?>) | загрузок (<?php echo $file['downloads'];?>) |
                <img src="/components/fcatalog/img/tags.png"> теги:<?php echo $file['tags'];?>
                <?php $item['votes'];?>
            </div>
        </td>

    </tr>


</table> 

