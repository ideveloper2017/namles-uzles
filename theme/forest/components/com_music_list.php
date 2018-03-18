<!--{if $do == 'view_albums'}-->
<!--<h1 class="con_heading" align="center">Альбомы</h1>-->
<!---->
<!--<div style="border-bottom: 1px solid #cccccc; margin-bottom: 10px;">-->
<!--    {include file='com_music_menu.tpl'}-->
<!--    <div>-->
<!--        <a href="/music">Музыка</a> | <strong>Альбомы:</strong> | <a href="/music/singers">Исполнители</a>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--{if $albums}-->
<!--<table>-->
<!--    {foreach item=album from=$albums}-->
<!--    <tr>-->
<!--        <td>-->
<!--            {if $album.photo}-->
<!--            <img src="/images/music/album/mini/{$album.photo}">-->
<!--            {else}-->
<!--            <img src="/images/music/album/mini/nophoto.jpg">-->
<!--            {/if}-->
<!--        </td>-->
<!--        <td>-->
<!--            <p>Название Альбома - <b><a href="/music/album{$album.id}">{$album.album_name}</a></b></p>-->
<!--            <p>Количество песен: <b>{$album.mus}</b></p>-->
<!--            {if $is_admin}<p><b><a href="/music/albumedit{$album.id}">Редактировать Альбом</a></b></p>{/if}-->
<!--        </td>-->
<!--    </tr>-->
<!--    {/foreach}-->
<!--</table>-->
<!--{/if}-->
<!--{/if}-->


<h1 class="con_heading" align="center">Исполнители</h1>

<div style="border-bottom: 1px solid #cccccc; margin-bottom: 10px;">

    <div>
        <a href="/music">Музыка</a> | <a href="/music/albums">Альбомы</a> | <strong>Исполнители:</strong>
    </div>
</div>

<?php
if ($singers) {?>
<table>
    <?php foreach ($singers as $singer) {?>
    <tr>
        <td>
            <?php if ($singer['photo']) {?>
            <img src="/images/music/singer/mini/<?php echo $singer['photo']?>">
            <?php }else{ ?>
            <img src="/images/music/singer/mini/nophoto.jpg">
            <?php }?>
        </td>
        <td>
            <p>Исполнитель - <b><a href="/media/singer{$singer.id}"><?php echo $singer['singer_name'];?></a></b></p>
            <p>Количество песен: <b>{$singer.mus}</b></p>
            {if $is_admin}<p><b><a href="/media/singeredit{$singer.id}">Редактировать Исполнителя</a></b></p>{/if}
        </td>
    </tr>
  <?php }?>
</table>
<?php }?>

<!--{$pagebar}-->

<!--{if !$albums and !$singers}-->
<!--На сайте еще нету ни одной песни, вы всегда можете добавить песню перейдя по <a href="/music/add.html">этой ссылке</a>-->
<!--{/if}-->
