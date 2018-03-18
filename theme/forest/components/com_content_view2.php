<?php if ($items_found) { ?>
<section class="section-iconbox">
    <div class="container">
        <div class="row">
            <div class="title-section">
                <h2 class="title"><?php echo $cattitle;?></h2>
<!--                <p class="desc-title">Don’t miss out on these limited time savings to keep your car healthy.</p>-->
            </div>
            <div class="two-columns">
            <?php
            $count = 0;
            foreach ($viewContent as $content) {
                $count++;
                ?>
                <div class="flat-iconbox object">
                    <div class="icon">
                        <i class="fa fa-check"></i>
                    </div>
                    <div class="content">
                        <a class="img-post" href="<?php echo $content['slug']; ?>"><h4><?php echo $content['title']; ?></h4></a>
<!--                        <p>-->
<!--                            Nunc lobortis duime neque, quis accumsan dolor. Aenean aliquet dignissim semper. Maecenas ullamcorper est vitae sem ornare interdum nascetur ridiculus mus.-->
<!---->
<!--                        </p>-->
                    </div>
                </div>
<!--                <article class="entry">-->
<!--                    --><?php //if ($content['image']){?>
<!--                        <div class="feature-post object ">-->
<!--                            <a class="img-post" href="--><?php //echo $content['slug']; ?><!--">-->
<!--                                <img src="/images/content/--><?php //echo $content['image']; ?><!--" alt="image">-->
<!--                            </a>-->
<!--                        </div>-->
<!---->
<!--                    --><?php //}?>
<!--                    <div class="main-post object">-->
<!--                        <h3 class="entry-title"><a href="--><?php //echo $content['slug']; ?><!--">--><?php //echo $content['title']; ?><!--</a></h3>-->
<!--                        <div class="entry-meta">-->
<!--                        <span class="entry-time">                           <i class="fa fa-calendar"></i> --><?php //echo $content['date']; ?><!--  <i class="fa fa-eye"></i> --><?php //echo $content['hits']; ?><!--  <i class="fa fa-folder"></i>  --><?php //echo $content['cat']; ?>
<!--</span>-->
<!--                        </div>-->
<!--                        <div class="entry-content">--><?php //echo $content['desc'];?>
<!---->
<!--                        </div>-->
<!--                      -->
<!--                    </div>-->
<!--                </article>-->

                <?php
            } ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>



