<javascript src="/components/media/uppod/uppod_player.js"> </javascript>
<javascript src="/components/media/uppod/swfobject.js"></javascript>

<h1 class="con_heading"><?php echo $singer->singer_name; ?></h1>

<div style="border-bottom: 1px solid #cccccc; margin-bottom: 10px;">

    <div>
        <a href="/music">Музыка</a> | <a href="/music/albums">Альбомы</a> | <a href="/music/singers">Исполнители</a>
    </div>
</div>
<!---->
<!--{if $is_admin}-->
<!--<div align="right">-->
<!--    <a href="/music/singeredit{$singer.id}">Редактировать исполнителя</a>-->
<!--</div>-->
<!--{/if}-->

<?php if ($singer->photo) {?>
<div align="center"><img src="/images/music/singer/<?php echo $singer->photo?>"></div><?php }?>
<div><?php echo  $singer->description?></div>
<HR>

<?php if ($musics) { ?>
    <?php foreach ($musics as $music) { ?>
        <div id="music{$music.id}">
            <table width="100%" CELLPADDING=5>
                <tr>
                    <td width="100">
                        <object id="audioplayer{$music.id}" type="application/x-shockwave-flash"
                                data="<?php echo $pleer['player']['pl']; ?>" width="100" height="35">
                            <param name="allowScriptAccess" value="always"/>
                            <param name="wmode" value="transparent"/>
                            <param name="movie" value="<?php echo $pleer['player']['pl'];?>"/>
                            <param name="flashvars"
                                   value="uid=audioplayer<?php echo $music['id']?>&amp;st=<?php echo $pleer['player']['st']?>&amp;file=<?php echo $music['music_url'];?>"/>
                        </object>
                    </td>
                    <td>
                        <span
                            style="background:url(/components/media/css/ajax-loader.gif) no-repeat center;padding-right:75px;display:none;"
                            id="{$music.id}"></span>

                        <p><strong><a href="/music/musics<?php echo $music['id']?>"><?php echo $music['name']?></a></strong></p>

                        <p>
                            <small><img src="/components/media/css/disc-blue.png" title="Альбом"> <a
                                    href="/music/album{$music.album_id}"><?php echo $music['album'];?></a> | <img
                                    src="/components/media/css/music.png" title="Жанр"><?php echo $music['genre'];?>| <img
                                    src="/components/media/css/calendar-select.png" title="Дата загрузки">
                                <?php echo $music['pubdate'];?> | <img src="/components/media/css/headphone.png" title="Слушали">
                                <?php echo $music['listen'];?> | <img src="/components/media/css/speaker.png"
                                                       title="Слушали до конца"> <?php $music['listened'];?>
                            </small>
                        </p>
                    </td>


                </tr>
            </table>
            <HR>
        </div>
    <?php } ?>
<!--    {$pagebar}-->
<?php } else { ?>
    <strong><br/>Песни этого исполнителя были удалены с сайта.</strong>
<?php } ?>
