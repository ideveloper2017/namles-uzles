<div class="col-md-8 blog__content mb-30">
    <article class="entry">
        <div class="single-post__entry-header  entry__header">
        <?php if ($article['config']['showtitle']) { ?>
            <h1 class="single-post__entry-title"><?php echo $article['title'] ?></h1>

        <?php } ?>

            <ul class="single-post__entry-meta entry__meta">

                <li class="entry__meta-date">
                   <i class="ui-date"></i> <?php echo $article['time']; ?> <?php echo $article['day'] . ' ' . $article['month'] ?> <?php echo $article['year']; ?>
                </li>
                <li>
                    <i class="ui-comments"></i>
                    <?php echo $article['hits'] ?>
                </li>
                <li>
                    <i class="ui-star"></i>
                    <a href="/<?php echo $article['menu_id'] ?>/<?php echo $article['catseo'] ?>" class="entry__meta-category"><?php echo $article['cat']; ?></a>
                </li>
            </ul>
    </div>


    <div class="entry__img-holder">
        <?php if ($article['image']) { ?>
            <img class="entry__img" src="<?php echo $article['image']; ?>" />
<!--            <figcaption>A photo collection samples</figcaption>-->
        <?php } ?>
    </div>

 <div class="entry__article-holder">
     <div class="entry__share">
         <div class="entry__share-inner">
             <div class="socials">
                 <a href="#" class="social-facebook entry__share-social" aria-label="facebook"><i class="ui-facebook"></i></a>
                 <a href="#" class="social-twitter entry__share-social" aria-label="twitter"><i class="ui-twitter"></i></a>
                 <a href="#" class="social-google-plus entry__share-social" aria-label="google+"><i class="ui-google"></i></a>
                 <a href="#" class="social-instagram entry__share-social" aria-label="instagram"><i class="ui-instagram"></i></a>
             </div>
         </div>
     </div> <!-- share -->
     <div class="entry__article">
     <?php
        echo $article['content']; ?>
     </div>
    </div>

        <div class="entry__tags">
            Tags:
            <?php if ($article['tags']) { ?>
            <?php echo $article['tags']; ?>
            <?php } ?>
<!--            <span class="blank"><a href="#">Tech</a></span>-->
<!--            <span class="blank"><a href="#">Transport</a></span>-->
<!--            <span class="blank"><a href="#">Mobile</a></span>-->
<!--            <span class="blank"><a href="#">Gadgets</a></span>-->
        </div>
        <!-- entity_tag -->

<!--        <div class="entity_social">-->
<!--            <span><i class="fa fa-share-alt"></i>424 <a href="#">Shares</a> </span>-->
<!--            <span><i class="fa fa-comments-o"></i>4 <a href="#">Comments</a> </span>-->
<!--        </div>-->
        <!-- entity_social -->


    <!--    <article class="entry">-->
    <!--        --><?php //if ($article['image']) { ?>
    <!--            <div class="feature-post popup">-->
    <!--                <img src="/images/content/-->
    <?php //echo $article['image']; ?><!--" width="680" class="setborder"-->
    <!--                     alt=""/>-->
    <!--            </div>-->
    <!--        --><?php //} ?>
    <!---->
    <!---->
    <!--        <div class="main-post">-->
    <!--            -->
    <!--            <div class="entry-meta">-->
    <!--                <div class="post_commentbox">-->
    <!--                    <i class="fa fa-calendar"></i> --><?php //echo $article['time']; ?><!-- -->
    <?php //echo $article['day'] . ' ' . $article['month'] ?><!-- --><?php //echo $article['year']; ?>
    <!--                    <i class="fa fa-folder"></i><a-->
    <!--                            href="/--><?php //echo $article['menu_id'] ?><!--/-->
    <?php //echo $article['catseo'] ?><!--">&nbsp;--><?php //echo $article['cat']; ?><!--</a>-->
    <!--                    <i class="fa fa-eye"></i>--><?php //echo $article['hits'] ?>
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="entry-content">-->
    <!--                --><?php //echo $article['content'] ?>
    <!--            </div>-->
    <!--        </div>-->


    <!--        <div class="main-nosplit">-->
    <!--            <div class="article-share-bottom">-->
    <!--                <b>Тег:</b>-->
    <!--                <div class="tag-block">-->
    <!--                    --><?php //if ($article['tags']) { ?>
    <!--                        --><?php //echo $article['tags']; ?>
    <!--                    --><?php //} ?>
    <!--                </div>-->
    <!--                <div class="clear-float"></div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </article>-->
    </article>
</div>