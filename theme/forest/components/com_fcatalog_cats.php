
<link rel="stylesheet" href="/components/fcatalog/css/voting.css" type="text/css" />
<link rel="stylesheet" href="/components/fcatalog/css/fcatalog.css" type="text/css" />
<script type="text/javascript" src="/components/fcatalog/js/voting.js"></script>

<div class="fileitem_cat" >
    <div class="fileitem_search_box">
        <form method="POST" action="/fcatalog/search.html" name="search">
            <input type="text" name="query" class="f_search" value="Поиск" {literal} onblur="if ($(this).val()==''){ $(this).val('Поиск'); }" onclick="$(this).val('');"{/literal}>
            <a href="#" class="fileitem_search">
                <img src="/components/fcatalog/img/search.png" title="Искать" onclick="javascript: search.submit()">
            </a>
        </form>
    </div>
<span><a href="/fcatalog" style="font-size:18px">Каталог файлов</a>
<span>всего:<strong> <?php echo $count;?></strong> файлов</span>
</span>
</div>

<div style="clear:both;display:block;height:10px;"></div>
<!--<a href="/fcatalog/add.html">-->
<!--    <img src="/components/fcatalog/img/add.png" alt="Добавить файл" title="Добавить файл" style="margin-bottom:-10px; float:right;"></a>-->
<div style="clear:both;display:block;height:10px;"></div>



<table width="100%" align="center">
    <?php $i=1;?>
    <?php foreach($cats as $item) {?>
    <?php if ($i==1) {?> <tr> <?php } ?> <td  align="center" valign="top">
            <a href="/fcatalog/<?php echo $item['seolink'];?>" style="text-decoration:none;"><img src="<?php echo $item['icon']?>"><h5><?php echo  $item['title'];?></a></h5>
            <span><?php echo  $item['description'];?></span> </td>
        <?php if ($i==4) {?></tr><tr height="10"> </tr> <?php $i=1;} else { $i++;}?>

    <?php }?>
</table> 

