<div class="container relative">
    <span class="trending-now__label">Қисқа сатрларда</span>
    <ul class="newsticker">
        <?php foreach($slidercont as $slider){?>
            <li class="newsticker__item"><a href="<?php echo $slider['seo']?>" class="newsticker__item-url"><?php echo $slider['title'] ?></a></li>
       <?php }?>
    </ul>
    <div class="newsticker-buttons">
        <button class="newsticker-button newsticker-button--prev" id="newsticker-button--prev" aria-label="next article"><i class="ui-arrow-left"></i></button>
        <button class="newsticker-button newsticker-button--next" id="newsticker-button--next" aria-label="previous article"><i class="ui-arrow-right"></i></button>
    </div>
</div>





