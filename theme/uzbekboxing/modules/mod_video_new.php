<div class="panel-content">
    <div class="video-blocks">
        <?php
        $count=0;
        foreach ($massiv as $vid){
            $count++;

//            if ($count==1){
            ?>
        <div class="video-left">
            <div class="video-large">
                <div class="video-image">
                    <a href="post.html"><span class="hover-icon icon-text">&#127916;</span><img class="setborder" src="images/photos/image-23.jpg" alt="" /></a>
                </div>
                <h2><a href="post.html">Sit libris delectus anu doctus delenit epicuri vel dolorem dissentiunt ne</a></h2>
            </div>
        </div>

        <div class="video-right">
            <div class="video-small">
                <div class="video-image">
                    <a href="post.html"><span class="hover-icon icon-text">&#127916;</span><img class="setborder" src="images/photos/image-24.jpg" alt="" /></a>
                </div>
                <h2><a href="post.html">Lorem ipsum dolor sit amet et dolor adolescens</a></h2>
            </div>

            <div class="video-small">
                <div class="video-image">
                    <a href="post.html"><span class="hover-icon icon-text">&#127916;</span><img class="setborder" src="images/photos/image-25.jpg" alt="" /></a>
                </div>
                <h2><a href="post.html">Qui reque euismod albuciu graeco tritani epicurei</a></h2>
            </div>

            <div class="video-small">
                <div class="video-image">
                    <a href="post.html"><span class="hover-icon icon-text">&#127916;</span><img class="setborder" src="images/photos/image-26.jpg" alt="" /></a>
                </div>
                <h2><a href="post.html">Lorem ipsum dolor sit amet et dolor adolescens</a></h2>
            </div>

            <div class="video-small">
                <div class="video-image">
                    <a href="post.html"><span class="hover-icon icon-text">&#127916;</span><img class="setborder" src="images/photos/image-27.jpg" alt="" /></a>
                </div>
                <h2><a href="post.html">Qui reque euismod albuciu graeco tritani epicurei</a></h2>
            </div>
        </div>
        <?php } ?>

        <div class="clear-float"></div>

    </div>

</div>

    <div class="video_new_div">
        <a href="/video/<?php echo $vid->seolink?>"><img src="/uploads/media/movie/big/<?php echo $vid->img;?>" class="video_new_img" alt="<?php echo $vid->title;?>" title="<?php echo $vid->title;?>"/></a>

        <div class="video_new_play">
            <a onclick="window.open('/video.php?id={$vid.video_id}','','Toolbar=0,Location=0,Directories=0,Status=0,Menubar=0,Scrollbars=0,Resizable=0,Width=600,Height=370');" class="video_but_plat_video"><img src="/images/video/play_index.png" /> Play</a>
            <div class="video_hits_new">Хит: <?php echo $vid->hits;?></div>
        </div>
    </div>
