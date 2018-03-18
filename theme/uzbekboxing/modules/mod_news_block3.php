<div class="col-md-8 blog__content mb-30">

    <div class="row">

        <?php $cols=1;?>
        <?php foreach ($articles as $content){?>

            <?php if ($cols==1){?>
        <div class="col-lg-6">
            <article class="entry">
                <div class="entry__img-holder">
                    <a href="single-post.html">
                        <div class="thumb-container">
                            <img data-src="<?php echo THEMEURL ?>/img/blog/grid_post_img_1.jpg" src="img/blog/grid_post_img_1.jpg" class="entry__img lazyload" alt="" />
                        </div>
                    </a>
                </div>

                <div class="entry__body">
                    <div class="entry__header">
                        <a href="categories.html" class="entry__meta-category">devices</a>
                        <h2 class="entry__title">
                            <a href="single-post.html">Satelite cost tens of millions or even hundreds of millions of dollars to build</a>
                        </h2>
                        <ul class="entry__meta">
                            <li class="entry__meta-date">
                                21 October, 2017
                            </li>
                            <li class="entry__meta-author">
                                by <a href="#">DeoThemes</a>
                            </li>
                        </ul>
                    </div>
                    <div class="entry__excerpt">
                        <p>Point of Sale hardware, the till at a shop check out, has become very complex over the past ten years. Modern POS hardware includes the cash till, bar-code readers...</p>
                    </div>
                </div>
            </article>
        </div>




         <div class="single_post_content_left">
                    <ul class="business_catgnav  wow fadeInDown">
                        <li>

                        </li>
                        <li>
                            <figure class="bsbig_fig"> <a href="<?php echo $content['href'];?>" class="featured_img"> <img alt="" src="<?php echo $content['image'];?>"> <span class="overlay"></span> </a>
                                <figcaption> <a href="<?php echo $content['href'];?>"><?php echo $content['title'] ?></a>

                                </figcaption>
                                <div style="margin:10px;">
                                    <i class="fa fa-calendar"></i> <?php echo $content['date']?> <i class="fa fa-eye"></i> <?php echo $content['hits'];?>
                                </div>
                                <?php echo $content['description'];?>
                            </figure>
                        </li>
                    </ul>
                </div>
                <div class="single_post_content_right">
                <ul class="spost_nav">
                <?php } else { ?>

                    <li>

                    </li>
                        <li>
                            <div class="media wow fadeInDown"> <a href="<?php echo $content['href'];?>" class="media-left"> <img alt="" src="<?php echo $content['image'];?>"> </a>

                                <div class="media-body"> <a href="<?php echo $content['href'];?>" class="catg_title"> <?php echo $content['title'] ?></a><br/>
                                    <div style="margin:10px;">
                                        <i class="fa fa-calendar"></i> <?php echo $content['date']?> <i class="fa fa-eye"></i> <?php echo $content['hits'];?>
                                    </div>
                                </div>

                            </div>
<!--                            <div class="post_commentbox"> <a href="#"><i class="fa fa-user"></i>Wpfreeware</a> <span><i class="fa fa-calendar"></i>6:49 AM</span> <a href="#"><i class="fa fa-tags"></i>Technology</a> </div>-->
<!--                            <div class="post_commentbox"> </div>-->
                        </li>



<!--        <div class="sec-1-sm">-->
<!--            <img alt="" class="blocky" src="--><?php //echo $content['image'];?><!--" width="107" height="85"/>-->
<!--            <div class="sec-1-sm-text blocky">-->
<!--               <a href="/content/--><?php //echo $content['href'];?><!--"> <h3>--><?php //echo $content['title'] ?><!--</h3></a>-->
<!--                <h6><span><i class="fa fa-calendar"></i>--><?php //echo $content['date']?><!--</span><span><i class="fa fa-eye"></i>--><?php //echo $content['hits'];?><!--</span></h6>-->
<!--<!--                --><?php ////echo mb_substr($content['description'],0,200).'...';?>
<!--            </div>-->
<!--        </div>-->
            <?php }

            if ($cols==5){ $cols=1;}else {$cols++;}?>
        <?php } ?>
                </ul>
                </div>
                </div>
                </div>
