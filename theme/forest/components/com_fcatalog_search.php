<link rel="stylesheet" href="/components/fcatalog/css/voting.css" type="text/css" />
<link rel="stylesheet" href="/components/fcatalog/css/fcatalog.css" type="text/css" />
<script type="text/javascript" src="/components/fcatalog/js/voting.js"></script>
<div class="fileitem_cat" >
    <span style="font-size:18px;color:#0066CC;">Поиск файлов</span>
</div>
<center>
    <form method="POST" action="/fcatalog/search.html" name="search">
        <input type="text" name="query" class="f_search" value="Поиск"  onblur="if ($(this).val()==''){ $(this).val('Поиск'); }" onclick="$(this).val('');">
        <a href="#" class="fileitem_search">
            <img src="/components/fcatalog/img/search.png" title="Искать" onclick="javascript: search.submit()">
        </a>
    </form>
</center>
<div style="clear:both;display:block;height:10px;"></div>
<?php echo $msg?>

<?php if ($files) {?>
<span>По запросу <b><?php echo $query; ?></b> найдено: <b><?php echo $total;?></b> файлов</span>
<br>
<table width="100%" align="center">
    <tr>
        <td>
            <?php foreach($files as  $item) ?>
            <div class="fileitem_bottom">
                <div class="fileitem_box">
                    <a href="/fcatalog/view_file-<?php echo $item['id']?>.html"><?php echo $item['title'];?></a><br>
                    <span><?php echo $item['description'];?></span>
                </div>
                <div class="fileitem_bottom">
                    <img src="/components/fcatalog/img/folder.png"> Категория: <a href="/fcatalog/<?php echo $item['category']->seolink;?>"><?php echo $item['category']->title;?></a>
                    | <img src="/components/fcatalog/img/calendar.png"> <?php echo $item['pubdate'];?>
                    | просмотров (<?php echo $item['hits']?>) | загрузок (<?php echo $item['downloads'];?>) |
                    комментариев(<a href="/fcatalog/view_file-<?php echo $item['id'];?>.html#c"><?php echo $item['comments'];?></a>)
                    <a href="/fcatalog/view_file-<?php echo $item['id'];?>.html">Подробнее...</a>
                </div>
            </div>
            <br>

            {/foreach}
        </td>
    </tr>
</table>

{$pagebar}
<?php }?>

