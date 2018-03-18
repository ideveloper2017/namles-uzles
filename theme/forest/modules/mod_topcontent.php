<ul class="widget-popular-posts__list">
<?php
$counter=0;
foreach ($hitcontents as $content) {
        ?>
        <li>
            <article class="clearfix">
                <div class="widget-popular-posts__img-holder">
                    <span class="widget-popular-posts__number"><?php echo $counter+=1;?></span>
                    <div class="thumb-container">
                        <a href="<?php echo $content['slug']; ?>">
                            <img data-src="<?php echo $content['first_image']?$content['first_image']:$content['image'] ?>" src="<?php echo $content['first_image']?$content['first_image']:$content['image'] ?>" alt="" class="lazyload">
                        </a>
                    </div>
                </div>
                <div class="widget-popular-posts__entry">
                    <h3 class="widget-popular-posts__entry-title">
                        <a href="<?php echo $content['slug']; ?>"><?php echo $content['title'] ?></a>
                    </h3>
                </div>
            </article>
        </li>



    <!--            <div class="media">-->
<!--                <div class="media-left">-->
<!--                    <a href="--><?php //echo $content['slug']; ?><!--"><img class="media-object"-->
<!--                                     src="--><?php //echo $content['first_image']?$content['first_image']:$content['image'] ?><!--"-->
<!--                                     alt="--><?php //echo $content['title'] ?><!--" style="width: 100px;height: 100px;"></a>-->
<!--                </div>-->
<!--                <div class="media-body">-->
<!--                    <h3 class="media-heading">-->
<!--                        <a href="--><?php //echo $content['slug']; ?><!--" target="_self">--><?php //echo $content['title'] ?><!--</a>-->
<!--                    </h3>-->
<!---->
<!--                    <div class="widget_article_social">-->
<!--                    <span>-->
<!--                    <a href="--><?php //echo $content['slug']; ?><!--" target="_self"> <i class="fa fa-calendar"></i>--><?php //echo $content['date']?><!-- </a>-->
<!--                    </span>-->
<!--                    <span>-->
<!--                    <a href="--><?php //echo $content['slug']; ?><!--" target="_self"><i class="fa fa-eye"></i>--><?php //echo $content['hits'];?><!--</a>-->
<!--                     </span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <li>-->
<!--                <div class="media wow fadeInDown" style="border-bottom:1px dashed #000;">-->
<!--                    --><?php //if ($content['image']) {?>
<!--                    <a href="--><?php //echo $content['slug']; ?><!--" class="media-left">-->
<!--                        <img alt="" src="/images/content/--><?php //echo $content['image'] ?><!--"> </a>-->
<!--                    --><?php //}?>
<!--                    <div class="media-body">-->
<!--                        <a href="--><?php //echo $content['slug']; ?><!--" class="catg_title">--><?php //echo $content['title'] ?><!--</a><br/>-->
<!--                        <div style="margin:10px;">-->
<!--                            <i class="fa fa-calendar"></i> --><?php //echo $content['date']?><!-- <i class="fa fa-eye"></i> --><?php //echo $content['hits'];?>
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                </div>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="/content/--><?php //echo $content['slug']; ?><!--">--><?php //echo $content['title'] ?><!--</a>-->
<!--                <a href="/content/--><?php //echo $content['slug']; ?><!--#comments" class="comment-icon"><span class="meta-date"></span>--><?php //echo $content['date'];?><!-- <span class="view-meta"></span>--><?php //echo $content['hits'];?><!--</a>-->
<!--            </li>-->

<?php }?>
   </ul>

