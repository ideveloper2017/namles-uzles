<?php if ($items_found) { ?>
    <div class="col-md-8 blog__content mb-30">
        <h1 class="page-title"><?php echo $cate_name;?></h1>
        <div class="row mt-30">
        <?php
        $count = 0;
        foreach ($viewContent as $content) {
            $count++;
            ?>

            <div class="col-md-6">
                <article class="entry">
                    <div class="entry__img-holder">
                        <a href="<?php echo $content['slug']; ?>">
                            <div class="thumb-container">
                                <img data-src="<?php echo $content['first_image']?$content['first_image']:$content['image']; ?>" src="<?php echo $content['first_image']?$content['first_image']:$content['image']; ?>" class="entry__img lazyload" alt="" />
                            </div>
                        </a>
                    </div>

                    <div class="entry__body">
                        <div class="entry__header">
                            <a href="/<?php echo $content['menu_id'];?>/<?php echo $content['cseo'];?>" class="entry__meta-category"><?php echo $content['cat'];?></a>
                            <h2 class="entry__title">
                                <a href="<?php echo $content['slug']; ?>"><?php echo $content['title']; ?></a>
                            </h2>
                            <ul class="entry__meta">
                                <li class="entry__meta-date">
                                    <i class="ui-date"></i> <?php echo $content['date']; ?>
                                </li>
                                <li>
                                    <i class="ui-comments"></i> <?php echo $content['hits']; ?>
                                </li>
                            </ul>
                        </div>
                        <div class="entry__excerpt">
                            <?php echo $content['desc'];?>
                        </div>
                    </div>
                </article>
            </div>

            <?php
        } ?>

    </div>
    </div>
    <div class="main-nosplit">
        <div class="page-pager">
            <?php if ($pager->display_pages()) { ?>
                <?php echo $pager->display_pages(); ?>
            <?php } ?>
        </div>
    </div>
<?php } ?>



