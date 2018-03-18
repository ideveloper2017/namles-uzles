<?php if ($items_found) { ?>
    <div class="post-wrap two-columns">
        <ul class="spost_nav">
            <?php
            $count = 0;
            foreach ($viewContent as $content) {
                $count++;
                ?>
                <article class="entry">
                    <?php if (!empty($content['image'])) { ?>
                        <div class="feature-post object ">
                            <a class="img-post" href="<?php echo $content['slug']; ?>">
                                <img src="<?php echo $content['image']; ?>" alt="image">
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
                        <!--<a href="--><?php //echo $content['slug']; ?><!--" class="button white">Read More</a>-->
                    </div>
                </article>
                <?php
            } ?>
        </ul>
    </div>
    <div class="main-nosplit">
        <div class="page-pager">
            <?php if ($pager->display_pages()) { ?>
                <?php echo $pager->display_pages(); ?>
            <?php } ?>
        </div>
    </div>
<?php } ?>



