<?php
$count = 0;
$rows=1;
foreach ($items as $item) {
    $count += 1;
    if ($count == 1) {
        ?>
        <div class="category_article_wrapper">
            <div class="row">
                <div class="col-md-6">
                    <div class="top_article_img">
                        <a href="<?php echo $item['slug']; ?>" target="_self">
                            <img class="img-responsive" src="<?php echo $item['first_image']?$item['first_image']:$item['image']; ?>" style="width: 360px;height: 309px;"
                                 alt="feature-top">
                        </a>
                    </div>

                </div>
                <div class="col-md-6">
<!--                    <span class="tag purple">--><?php //echo $item['cat']; ?><!--</span>-->
                    <div class="category_article_title">
                        <h2><a href="<?php echo $item['slug']; ?>" target="_self"><?php echo $item['title']; ?></a></h2>
                    </div>
                    <div class="category_article_date"></div>
                    <div class="category_article_content">
                        <?php echo $item['description']; ?>
                    </div>
                    <div class="media_social">
                        <span><a href="#"><i class="fa fa-calendar"></i><?php echo $item['pubdate'] ?> </a></span>
                        <span><i class="fa fa-eye"></i><a href="#"><?php echo $item['hits'] ?></a></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="category_article_wrapper">
        <div class="row">

    <?php } else {


        ?>

        <?php if ($rows==1) {?><div class="col-md-6"><?php }?>
        <div class="media">
                <div class="media-left">
                    <a href="#"><img class="media-object" src="<?php echo $item['first_image'] ?>"  alt="Generic placeholder image" style="width: 122px;height: 122px;"></a>
                </div>
                <div class="media-body">
<!--                    <span class="tag purple">--><?php //echo $item['cat']; ?><!--</span>-->

                    <h3 class="media-heading"><a href="/content/<?php echo $item['slug']; ?>" target="_self"><?php echo $item['title']; ?></a></h3>
                    <span class="media-date"></span>
                    <div class="media_social">
                        <span><a href="#"><i class="fa fa-calendar"></i><?php echo $item['pubdate'] ?> </a></span>
                        <span><i class="fa fa-eye"></i><a href="#"><?php echo $item['hits'] ?></a></span>
                    </div>
                </div>
            </div>

        <?php
        if ($rows==2){ echo '</div>';$rows=1;}else {$rows+=1;}
        ?>
<!--        <div class="article-big-block">-->
<!--            <div class="article-photo">-->
<!--										<span class="image-hover">-->
<!--											<span class="drop-icons">-->
<!---->
<!--												<span class="icon-block"><a href="/content/--><?php //echo $item['slug']; ?><!--"-->
<!--                                                                            title="Батафсил"-->
<!--                                                                            class="icon-link legatus-tooltip">&nbsp;</a></span>-->
<!--											</span>-->
<!--											<img src="/images/content/--><?php //echo $item['image'] ?><!--"-->
<!--                                                 class="setborder" alt=""/>-->
<!--										</span>-->
<!--            </div>-->
<!---->
<!--            <div class="article-header">-->
<!--                <h3><a href="/content/--><?php //echo $item['slug']; ?><!--">--><?php //echo $item['title'] ?><!--</a></h3>-->
<!--            </div>-->
<!---->
<!--            <div class="article-content">-->
<!--                --><?php //echo $item['description']; ?>
<!--            </div>-->
<!---->
<!--            <div class="article-links">-->
<!--                <a href="/content/--><?php //echo $item['slug']; ?><!--#comments" class="article-icon-link">-->
<!--                    <span class="meta-date"></span>--><?php //echo $item['pubdate'] ?><!--</a>-->
<!--                <a href="#" class="article-icon-link">-->
<!--                    <span class="view-meta"></span>--><?php //echo $item['hits'] ?><!--</a>-->
<!--                <a href="--><?php //echo $item['catseo']; ?><!--" class="article-icon-link">-->
<!--                    <span class="icon-text">&#59212;</span>--><?php //echo $item['cat']; ?><!--</a>-->
<!--            </div>-->
<!--           -->
<!--        </div>-->


    <?php }

}
?>

    </div>
    </div>
<?php if ($cfg['is_pag']) { ?>
    <div class="cp-pagination">
        <nav>
            <?php if ($pager->display_pages()) { ?>
                <?php echo $pager->display_pages(); ?>
            <?php } ?>
        </nav>
    </div>
<?php } ?>