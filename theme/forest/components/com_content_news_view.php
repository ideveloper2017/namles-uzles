<?php if ($items_found) { ?>
    <div class="post-wrap two-columns">
        <?php
        $count = 0;
        foreach ($viewContent as $content) {
            $count++;
            ?>
            <article class="entry">
                <?php if (!empty($content['image'])) { ?>
                    <div class="feature-post object">
                        <a class="img-post" href="<?php echo $content['slug']; ?>">
                            <img src="<?php echo $content['image']; ?>" alt="image" style="width:393px;height:293px;">
                        </a>
                    </div>

                <?php } ?>

                <div class="main-post object">
                    <h3 class="entry-title"><a
                                href="<?php echo $content['slug']; ?>"><?php echo $content['title']; ?></a></h3>
                    <div class="entry-meta">
                        <span class="entry-time">                           <i
                                    class="fa fa-calendar"></i> <?php echo $content['date']; ?> <i
                                    class="fa fa-eye"></i> <?php echo $content['hits']; ?> <i
                                    class="fa fa-folder"></i> <?php echo $content['cat']; ?>
</span>
                    </div>
                    <div class="entry-content"><?php echo $content['desc']; ?>

                    </div>
                    <a href="<?php echo $content['slug']; ?>" class="button white">Батафсил</a>
                </div><!-- /.main-post -->
            </article><!-- /.entry -->
            <!--            <li>-->
            <!--                <div class="media wow fadeInDown">-->
            <!--                                        --><?php //if ($content['image']){?>
            <!--                    <a href="--><?php //echo $content['slug']; ?><!--" class="media-left">-->
            <!--                        <img alt="" src="--><?php //echo $content['image']; ?><!--">-->
            <!--                    </a>-->
            <!--                    --><?php //}?>
            <!--                    <div class="media-body"> <h4><a href="--><?php //echo $content['slug']; ?><!--" class="catg_title">--><?php //echo $content['title']; ?><!--</a></h4>-->
            <!---->
            <!--                        <div style="margin:10px;">-->
            <!--                            <i class="fa fa-calendar"></i> --><?php //echo $content['date']; ?><!-- <i class="fa fa-eye"></i> | --><?php //echo $content['hits']; ?><!-- | <i class="fa fa-folder"></i> --><?php //echo $content['cat']; ?>
            <!--                        </div>-->
            <!--                        --><?php //echo $content['desc'];?>
            <!--                    </div>-->
            <!---->
            <!--                </div>-->
            <!---->
            <!--            </li>-->
            <?php
        } ?>
    </div>
            <?php if ($pager->display_pages()) { ?>
                <?php echo $pager->display_pages(); ?>
            <?php } ?>
   <?php } ?>