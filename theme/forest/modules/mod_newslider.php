<section class="featured-posts-slider">

    <div class="container">
        <div id="owl-featured-posts" class="owl-carousel owl-theme">

    <?php foreach ($slidercont as $slider) { ?>
        <div class="featured-posts-slider__slide">
            <article class="featured-posts-slider__entry entry">
                <a href="<?php echo $slider['seo'] ?>">
                    <div class="thumb-container">
                        <img src="<?php echo $slider['first_image']?$slider['first_image']:$slider['image']; ?>" alt="" style="width: 206px;height: 203px;">
                    </div>
                </a>
                <div class="featured-posts-slider__text-holder">
                    <h2 class="featured-posts-slider__entry-title">
                        <a href="<?php echo $slider['seo'] ?>""><?php echo $slider['title'] ?></a>
                    </h2>
                </div>
            </article>
        </div>


<!--    <div class="single_iteam"> <a href="--><?php //echo $slider['seo'] ?><!--"> <img src="/images/content/--><?php //echo $slider['image'] ?><!--" alt=""></a>-->
<!--        <div class="slider_article">-->
<!--            <h2><a class="slider_tittle" href="--><?php //echo $slider['seo'] ?><!--">--><?php //echo $slider['title'] ?><!--</a></h2>-->
<!--        </div>-->
<!--    </div>-->
    <?php } ?>

</div>
</div>
</section>



<!--<div class="slider-container">-->
<!--    <div class="slider-content">-->
<!--        <ul>-->
<!--            --><?php //foreach ($slidercont as $slider) { ?>
<!--                <li>-->
<!--                    <a href="/content/--><?php //echo $slider['seo'] ?><!--">-->
<!--                        <span class="slider-info">--><?php //echo $slider['title'] ?><!--</span>-->
<!--                        <img src="/images/content/--><?php //echo $slider['image'] ?><!--" width="680" height="400"-->
<!--                             class="setborder" alt="--><?php //echo $slider['title'] ?><!--"/>-->
<!--                    </a>-->
<!--                </li>-->
<!--            --><?php //} ?>
<!--        </ul>-->
<!--    </div>-->
<!--    <ul class="slider-controls"></ul>-->
<!--</div>-->

   