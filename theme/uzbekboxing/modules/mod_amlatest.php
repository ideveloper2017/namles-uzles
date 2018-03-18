<script type='text/javascript' src='/components/media/js/swfobject.js'></script>
        <ul>
            <?php foreach($item as $mp3) {?>
            <li><?php echo $mp3['title']?><br/>
                <div id='mediaspacem<?php echo $mp3['id']?>' align="center"><?php echo $mp3['title']?></div>
                <script type='text/javascript'>
                    var sm<?php echo $mp3['id']?> = new SWFObject('/components/media/player/player.swf','ply','200','24','9','#000000');
                    sm<?php echo $mp3['id']?>.addParam('allowfullscreen','true');
                    sm<?php echo $mp3['id']?>.addParam('allowscriptaccess','always');
                    sm<?php echo $mp3['id']?>.addParam('wmode','opaque');
                    sm<?php echo $mp3['id']?>.addVariable('file','/uploads/media/mp3/<?php echo $mp3['file']?>');
                    sm<?php echo $mp3['id']?>.addVariable('duration','auto');
                    sm<?php echo $mp3['id']?>.addVariable('backcolor','FFFF00');
                    sm<?php echo $mp3['id']?>.addVariable('frontcolor','009900');
                    sm<?php echo $mp3['id']?>.addVariable('lightcolor','009900');
                    sm<?php echo $mp3['id']?>.addVariable('screencolor','003300');
                    sm<?php echo $mp3['id']?>.write('mediaspacem<?php echo $mp3['id']?>');
                </script>

            </li>
            <?php }?>
            <?php if ($cfg['back']){?>
            <p align="right"><a href="/media/audio">Всё аудио..</a></p>
            <?php }?>
</ul>