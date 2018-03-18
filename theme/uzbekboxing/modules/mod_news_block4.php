<!--<div class="top-news float-width">-->
<!--        <div class="float-width sec-cont">-->
<!--        <h3 class="sec-title">--><?php //echo $category->cname;?><!--</h3>-->

<?php $cols = 1; ?>
<?php foreach ($articles as $content) { ?>
    <?php if ($cols == 1) { ?>

        <ul class="business_catgnav wow fadeInDown">
            <li>
                <figure class="bsbig_fig">
                    <a href="<?php echo $content['href']; ?>" class="featured_img">
                        <img alt="<?php echo $content['title'] ?>" src="<?php echo $content['image']; ?>">
                        <span class="overlay"></span>
                    </a>
                    <figcaption><a href="<?php echo $content['href'];?>"><?php echo $content['title'] ?></a>

                    </figcaption>
                    <div style="margin:10px;">
                        <i class="fa fa-calendar"></i> <?php echo $content['date']?> <i class="fa fa-eye"></i> <?php echo $content['hits'];?>
                    </div>
                    <?php echo $content['description']; ?>
                </figure>
            </li>
        </ul>
        <!--             <div class="top-big-two">-->
        <!--            <div class="big-two-1 blocky boxgrid3 caption">-->
        <!--                <img alt="--><?php //echo $content['title'] ?><!--" src="--><?php //echo $content['image'];?><!--" width="367" height="269"/>-->
        <!--                <div class="cover boxcaption3">-->
        <!--                    <h3><a href="/content/--><?php //echo $content['href'];?><!--">--><?php //echo $content['title'] ?><!--</a></h3>-->
        <!--                    <p class="artcl-time-1">-->
        <!--                        <span><i class="fa fa-calendar"></i>--><?php //echo $content['date']?><!--</span>-->
        <!--                        <span><i class="fa fa-eye"></i>--><?php //echo $content['hits'];?><!--</span>-->
        <!--                    </p>-->
        <!--                    --><?php //echo $content['description'];?>
        <!--                </div>-->
        <!--            </div>-->
        <!--                    --><?php //} else if ($cols==2){?>
        <!--                    <div class="big-two-2 blocky boxgrid3 caption">-->
        <!--                        <img alt="--><?php //echo $content['title'] ?><!--" src="--><?php //echo $content['image'];?><!--" width="367" height="269" />-->
        <!--                        <div class="cover boxcaption3">-->
        <!--                            <h3><a href="/content/--><?php //echo $content['href'];?><!--">--><?php //echo $content['title'] ?><!--</a></h3>-->
        <!--                            <p class="artcl-time-1">-->
        <!--                                <span><i class="fa fa-calendar"></i>--><?php //echo $content['date']?><!--</span>-->
        <!--                                <span><i class="fa fa-eye"></i>--><?php //echo $content['hits'];?><!--</span>-->
        <!--                            </p>-->
        <!--                            --><?php //echo $content['description'];?>
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                    </div>-->
        <!--        </div>-->
        <ul class="spost_nav">
    <?php } else { ?>

            <li>
                <div class="media wow fadeInDown"><a href="<?php echo $content['href']; ?>" class="media-left"> <img alt=""
                                                                                                            src="<?php echo $content['image']; ?>">
                    </a>

                    <div class="media-body"><a href="<?php echo $content['href']; ?>" class="catg_title"> <?php echo $content['title'] ?></a>
                        <div style="margin:10px;">
                            <i class="fa fa-calendar"></i> <?php echo $content['date']?> <i class="fa fa-eye"></i> <?php echo $content['hits'];?>
                        </div>
                    </div>
                </div>
            </li>


        <!--        <div class="tn-small-1 blocky">-->
        <!--            <a href="/content/--><?php //echo $content['href']; ?><!--"><img alt="" class="lefty"-->
        <!--                                                                    src="--><?php //echo $content['image']; ?><!--" width="107"-->
        <!--                                                                    height="85"/></a>-->
        <!--            <a href="/content/--><?php //echo $content['href']; ?><!--"><h4 class="lefty">--><?php //echo $content['title'] ?><!--</h4>-->
        <!--            </a>-->
        <!--            <!--                        <a class="lefty cat-a cat-label4" href="#"></a>-->
        <!--            <p class="lefty" style="padding-left: 10px;"><span><i-->
        <!--                        class="fa fa-calendar"></i>--><?php //echo $content['date'] ?><!--</span></p>-->
        <!--        </div>-->
    <?php }

    if ($cols == 5) {
        $cols = 1;
    } else {
        $cols++;
    } ?>
<?php } ?>
        </ul>

<!--    <div class="tn-small-1 blocky">-->
<!--        <a href="#"><img alt="" class="lefty" src="img/samples/e2.jpg" /></a>-->
<!--        <h4 class="lefty">USA Games Studio will release sandbox new game</h4>-->
<!--        <a class="lefty cat-a cat-label2" href="#">GAMES</a>-->
<!--        <p class="righty"><span><i class="fa fa-clock-o"></i>20 Jan 2014</span></p>-->
<!--    </div>-->
<!--    <div class="tn-small-1 blocky">-->
<!--        <a href="#"><img alt="" class="lefty" src="img/samples/e3.jpg" /></a>-->
<!--        <h4 class="lefty">The best place to see in Winter season this year</h4>-->
<!--        <a class="lefty cat-a cat-label3" href="#">TOURISM</a>-->
<!--        <p class="righty"><span><i class="fa fa-clock-o"></i>20 Jan 2014</span></p>-->
<!--    </div>-->
<!--    <div class="tn-small-1 blocky">-->
<!--        <a href="#"><img alt="" class="lefty" src="img/samples/e4.jpg" /></a>-->
<!--        <h4 class="lefty">New phone - people love it and sales are closed</h4>-->
<!--        <a class="lefty cat-a cat-label5" href="#">tech</a>-->
<!--        <p class="righty"><span><i class="fa fa-clock-o"></i>20 Jan 2014</span></p>-->
<!--    </div>-->
