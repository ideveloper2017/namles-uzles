<div class="col-md-8 blog__content mb-30">
    <div class="title-wrap">
    <h3 class="section-title">СЎНГГИ ЯНГИЛИКЛАР</h3>
    </div>
    <div class="row">

            <?php foreach ($articles as $content){?>
                <div class="col-lg-6">
                    <article class="entry">
                        <div class="entry__img-holder">
                            <a href="single-post.html">
                                <div class="thumb-container">
                                    <img data-src="<?php echo $content['first_image']?$content['first_image']:$content['image']; ?>" src="<?php echo $content['first_image']?$content['first_image']:$content['image']; ?>" class="entry__img lazyload" alt=""  style="width: 350px;height: 250px;"/>
                                </div>
                            </a>
                        </div>

                        <div class="entry__body">
                            <div class="entry__header">
                                <a href="<?php echo $content['cathref'];?>" class="entry__meta-category"><?php echo $content['cat'] ?></a>
                                <h2 class="entry__title">
                                    <a href="<?php echo $content['href']; ?>"><?php echo $content['title'] ?></a>
                                </h2>
                                <ul class="entry__meta">
                                    <li class="entry__meta-date">
                                        <i class="ui-date"></i>  <?php echo $content['date']?>
                                    </li>
                                    <li class="entry__meta-author">
                                        <i class="ui-comments"></i> <?php echo $content['hits'];?>

                                    </li>
                                </ul>
                            </div>
<!--                            <div class="entry__excerpt">-->
<!--                                <p>Point of Sale hardware, the till at a shop check out, has become very complex over the past ten years. Modern POS hardware includes the cash till, bar-code readers...</p>-->
<!--                            </div>-->
                        </div>
                    </article>
                </div>
            <?php }?>
    </div>
</div>
