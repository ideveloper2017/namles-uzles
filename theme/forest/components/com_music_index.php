<javascript src="/components/media/uppod/uppod_player.js"> </javascript>
<javascript src="/components/media/uppod/swfobject.js"></javascript>

<h1 class="con_heading" align="center">Музыкальный Альбом</h1>

<div style="border-bottom: 1px solid #cccccc; margin-bottom: 10px;">

    <div>
        <strong>Музыка:</strong> | <a href="/music/albums">Альбомы</a> | <a href="/music/singers">Исполнители</a>
    </div>
</div>

<?php if ($musics) { ?>
<?php foreach($musics as $music){?>

<div id="music{$music.id}">
    <table width="100%" CELLPADDING=5>
        <tr align="left">
            <td width="100">
                <object id="audioplayer<?php echo  $music['id'];?>" type="application/x-shockwave-flash" data="<?php echo $pleer['player']['pl'];?>" width="100" height="35">
                    <param name="allowScriptAccess" value="always" />
                    <param name="wmode" value="transparent" />
                    <param name="movie" value="<?php echo $pleer['player']['pl'];?>" />
                    <param name="flashvars" value="uid=audioplayer<?php echo $music['id']?>&amp;st=<?php echo $pleer['player']['st']?>&amp;file=<?php echo $music['music_url'];?>" />
                </object>
            </td>
            <td width="80%">
                <span style="background:url(/components/media/css/ajax-loader.gif) no-repeat center;padding-right:75px;display:none;" id="<?php echo $music['id'];?>"></span>
                <p>
                    <strong>
                        <a href="/media/singer<?php echo $music['singer_id']?>"><?php echo $music['singer'];?></a> - <a href="/media/musics<?php echo $music['id']?>"><?php echo $music['name'];?></a>
                    </strong>
                </p>
                <p>
                    <small>
                        <img src="/components/media/css/disc-blue.png" title="Альбом">
                        <a href="/music/album<?php echo $music['album_id'];?>"><?php echo $music['album'];?></a> |
                        <img src="/components/media/css/music.png" title="Жанр"><?php echo $music['genre'];?> |
                        <img src="/components/media/css/calendar-select.png" title="Дата загрузки"> <?php echo $music['pubdate'];?> |
                        <img src="/components/media/css/headphone.png" title="Слушали"><?php echo $music['listen'];?> |
                        <img src="/components/media/css/speaker.png" title="Слушали до конца"><?php echo $music['listened'];?>
                    </small>
                </p>
            </td>

</tr>
</table>
<HR>
</div>
<?php }
} else {
?>


<strong><br/>На сайте пока нету ни одной песни, чтобы добавить свою воспользуйтесь кнопкой: <a href="/music/add.html">Добавить музыку</a><br/><br/></strong>
<?php } ?>