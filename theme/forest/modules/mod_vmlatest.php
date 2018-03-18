<div class="panel-content">
    <div class="video-blocks">
        <?php
        $count=0;
        foreach ($item as $vid) {
            $count++;
            if ($count==1){
            ?>
        <div class="video-left">
            <div class="video-large">
                <div class="video-image">
                    <a href="post.html"><span class="hover-icon icon-text">&#127916;</span><img class="setborder" src="/uploads/media/movie/big/<?php echo $vid['img'];?>" width="330" height="242" alt="" /></a>
                </div>
                <h2><a href="post.html"><?php echo $vid['title'];?></a></h2>
            </div>
        </div>
                <?php } else {?>
        <div class="video-right">
            <div class="video-small">
                <div class="video-image">
                    <a href="post.html"><span class="hover-icon icon-text">&#127916;</span><img class="setborder" src="/uploads/media/movie/small/<?php echo $vid['img'];?>" alt="" /></a>
                </div>
                <h2><a href="post.html"><?php echo $vid['title'];?></a></h2>
            </div>


        </div>
                <?php }?>
<?php }?>
        <div class="clear-float"></div>

    </div>
</div>




