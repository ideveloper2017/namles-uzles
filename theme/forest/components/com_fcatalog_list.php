<link rel="stylesheet" href="/components/fcatalog/css/voting.css" type="text/css" />
<link rel="stylesheet" href="/components/fcatalog/css/fcatalog.css" type="text/css" />
<script type="text/javascript" src="/components/fcatalog/js/voting.js"></script>
<table width="100%" align="center">
    <?php $i=1;?>
    <?php foreach($subcats_list as $item): ?>
    <?php if ($i==1){ ?> <tr> <?php } ?> <td  align="center" valign="top">
            <a href="/fcatalog/<?php echo $item['seolink'] ?>" style="text-decoration:none;"><img src="<?php echo $item['icon']?>"><h5><?php echo $item['title'] ?></a></h5>
            <span><?php echo $item['description'] ?></span> </td>
            <?php if ($i==4) {?>
             </tr><tr height="10"> </tr>
             <?php $i=1;} else {?>
            <?php $i++;?>
            <?php } ?>

    <?php endforeach;?>
</table>

<div class="fileitem_cat" >
    <div class="fileitem_search_box">
        <form method="POST" action="/fcatalog/search.html" name="search">
            <input type="text" name="query" class="f_search" value="Поиск"  onblur="if ($(this).val()==''){ $(this).val('Поиск'); }" onclick="$(this).val('');">
            <a href="#" class="fileitem_search">
                <img src="/components/fcatalog/img/search.png" title="Искать" onclick="javascript: search.submit()">
            </a>
        </form>
    </div>
    <span><a href="/fcatalog">&larr;Все категории</a> | <a href="/fcatalog/<?php echo $category->seolink?>"><?php echo $category->title;?></a> (<?php echo $total;?> файлов)</span>
</div>
<div style="clear:both;display:block;height:10px;"></div>
<!--<a href="/fcatalog/add.html">-->
<!--    <img src="/components/fcatalog/img/add.png" alt="Добавить файл" title="Добавить файл" style="margin-bottom:5px; float:right;"></a>-->

<?php echo $msg;?>

<table width="100%" align="center">
     <?php $q=1;?>
    <?php $r=0;?>
    <?php foreach($files as $item):?>

    <tr>
        <td>
            <div class="fileitem_title">
                <a href="/fcatalog/view_file-<?php echo $item['id']?>.html"><?php echo $item['title']?></a>
            </div>
            <div class="fileitem_box">
                <span><?php echo $item['description']?></span>
                <span style="float:right;"><a href="/fcatalog/view_file-<?php echo $item['id']?>.html">Подробно</a></span>
            </div>

            <div class="fileitem_bottom">
<!--                <a href="/fcatalog/view_file-{$item.id}.html">{$item.title}</a>-->
<!--                |-->
                <img src="/components/fcatalog/img/calendar.png"> <?php echo $item['pubdate'];?>
                | просмотров (<?php echo $item['hits']?>) | загрузок (<?php echo $item['downloads'];?>) |
                комментариев(<a href="/fcatalog/view_file-<?php echo $item['id']?>.html#c"><?php echo $item['comments']?></a>)
                <div class="votebox" id="voting<?php echo $item['id']?>"><?php echo $item['votes'];?></div>
            </div>
            <br>
        </td>
    </tr>
    <?php if ($q==1 && $reclama[$r]) {?>
    <tr>
        <td>
<!--            <div class="fileitem_title"> <br>-->
<!--            </div>-->
            <div class="fileitem_box"><?php echo $reclama[$r]?>
            </div>
<!--            <div class="fileitem_bottom"> <br>-->
<!--            </div> <br>-->
        </td>
    </tr>
        <?php $q=1; $r++;}else{ $q++;}?>

    <?php endforeach;?>
</table>

<!--{$pagebar}-->

