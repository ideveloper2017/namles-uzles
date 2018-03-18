<ul class="post-list-small">
<?php
$count = 0;
foreach ($items as $item) {
    $count += 1;

    ?>

        <li class="post-list-small__item">
            <article class="post-list-small__entry clearfix">
                <div class="post-list-small__img-holder">
                    <div class="thumb-container">
                        <a href="<?php echo $item['slug']; ?>">
                            <img data-src="<?php echo $item['first_image']?$item['first_image']:$item['image']; ?>" src="<?php echo $item['first_image']?$item['first_image']:$item['image']; ?>" alt="" class=" lazyloaded">
                        </a>
                    </div>
                </div>
                <div class="post-list-small__body">
                    <h3 class="post-list-small__entry-title">
                        <a href="<?php echo $item['slug']; ?>"><?php echo $item['title']; ?></a>
                    </h3>
                    <ul class="entry__meta">
                        <li class="entry__meta-date">
                            <i class="ui-date"></i>
                            <?php echo $item['pubdate'] ?>
                        </li>
                    </ul>
                </div>
            </article>
        </li>


<!--    <div class="category_article_wrapper">-->
<!--        <div class="row">-->
<!--            <div class="col-md-3">-->
<!--                <div class="top_article_img">-->
<!--                    <a href="--><?php //echo $item['slug']; ?><!--" target="_self">-->
<!--                        <img class="img-responsive" src="--><?php //echo $item['first_image']?$item['first_image']:$item['image']; ?><!--" alt="feature-top" style="width:295px;height:175px;">-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-9">-->
<!--                <span class="tag orange">--><?php //echo $item['cat']; ?><!--</span>-->
<!--                <div class="category_article_title">-->
<!--                    <h2><a href="--><?php //echo $item['slug']; ?><!--" target="_self">--><?php //echo $item['title']; ?><!--</a></h2>-->
<!--                </div>-->
<!--                <div class="article_date">-->
<!--                    <span><a href="#"><i class="fa fa-calendar"></i> --><?php //echo $item['pubdate'] ?><!--</a> </span>-->
<!--                    <span><i class="fa fa-eye"></i><a href="#">  --><?php //echo $item['hits'] ?><!--</a> </span>-->
<!--                </div>-->
<!--                <div class="category_article_content">-->
<!--                    --><?php //echo $item['description']; ?>
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

<!--    <article class="entry object">-->
<!--        <div class="feature-post ">-->
<!--            <a class="img-post" href="--><?php //echo $item['slug']; ?><!--">-->
<!--                <img src="/images/content/--><?php //echo $item['image']; ?><!--" alt="image"-->
<!--                     style="width: 350px;height: 262px;">-->
<!--            </a>-->
<!--        </div>-->
<!--        <div class="main-post">-->
<!--            <h3 class="entry-title"><a href="--><?php //echo $item['slug']; ?><!--">--><?php //echo $item['title']; ?><!--</a>-->
<!--            </h3>-->
<!--            <div class="entry-meta">-->
<!--                <p class="entry-time">--><?php //echo $item['pubdate'] ?><!--</p>-->
<!--            </div>-->
<!--            <div class="entry-content">--><?php //echo mb_substr($item['description'], 0, 200); ?>
<!--            </div>-->
<!--            <a href="--><?php //echo $item['slug']; ?><!--" class="button white">Батафсил</a>-->
<!--        </div>-->
<!--    </article>-->

<?php } ?>

</ul>

<!----article_title------>






<?php //if ($cfg['is_page']){?>
<!--<div class="cp-pagination">-->
<!--    <nav>-->
<!--        --><?php //if ($pager->display_pages()) { ?>
<!--            --><?php //echo $pager->display_pages(); ?>
<!--        --><?php //} ?>
<!--    </nav>-->
<!--</div>-->
<?php //}?>
