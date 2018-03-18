<div class="row">
    <div class="col-md-12">
        <div class="member-carousel">

            <?php foreach($items as $rowitem){?>

            <article class="member entry object">
                <div class="member-image">
                    <a href="<?php echo $rowitem['slug'];?>"><img src="<?php echo THEMEURL ?>/images/member/t7.png" width="255px" height="191px" alt="t8"></a>
                    <div class="member-links">
                        <div class="more-link">
                            <a href="<?php echo $rowitem['slug'];?>">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="member-detail">
                    <h3 class="member-name"><?php echo $rowitem['title'];?></h3>
                </div>
            </article>
<?php }?>



        </div>
    </div>
</div>
