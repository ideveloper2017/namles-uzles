<script type='text/javascript' src='/components/media/js/swfobject.js'></script>
<?php foreach($items as $item){?>
<div class="audio">
    <h3 class="sec-title" align="center"><?php echo $item['title']?>.mp3</h3><br/>
    <div id='mediaspace<?php echo $item['id'];?>' align="center"><?php echo $item['title']?></div>
    <script type='text/javascript'>
        var s<?php echo $item['id']?> = new SWFObject('/components/media/player/player.swf','ply','{$cfg.waudio}','24','9','#000000');
        s<?php echo $item['id']?>.addParam('allowfullscreen','true');
        s<?php echo $item['id']?>.addParam('allowscriptaccess','always');
        s<?php echo $item['id']?>.addParam('wmode','opaque');
        s<?php echo $item['id']?>.addVariable('file','/uploads/media/mp3/<?php echo $item['file']?>');
        s<?php echo $item['id']?>.addVariable('duration','auto');
        s<?php echo $item['id']?>.write('mediaspace<?php echo $item['id']?>');
    </script>
    <p align="right"><a href="/uploads/media/mp3/<?php echo $item['file']?>">Скачать MP3</a></p>
</div>
<?php }?>
