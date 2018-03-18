<link rel="stylesheet" href="/modules/mod_dt_newsscroll/css/style11/newsscroll.css" type="text/css" />

<div id="dt_newsscrollwrap<?php echo $module_id ?>" class="dt_newsscrollwrap style11" style="z-index: 100; width: 940px;">
<div class="dt_newsscroll_inner">
    <ul class="dt_newsscroll_list">
        <?php foreach($slidercont as $article) {?>

        <li class="dt_newsscroll_item">
          <span class="dt_newsscroll_date"><?php echo $article['date'];?></span>
<!--            <span class="dt_newsscroll_title">--><?php //echo $article['title'];?><!--</span>-->
            <a class="dt_newsscroll_title" href="/content/<?php echo $article['url']; ?>"><?php echo $article['title'];?></a>
        </li>
        <?php }?>
    </ul>

</div>
    <div class="dt_newsscroll_infobox">
        <?php foreach($slidercont as $article1) {?>
        <div class="dt_newsscroll_info">
            <div class="dt_newsscroll_info_inner">
                <span class="dt_newsscroll_info_title"><?php echo $article1['title'];?></span>
                <span class="dt_newsscroll_info_date"><?php echo $article1['date'];?></span>
                <?php  if ($article1['image']) {?>
                <div class="dt_newsscroll_info_img">
                    <img src="/images/content/small/<?php echo $article1['image'];?>" alt="{$info.title|escape:'html'}"/>
                </div>
                <?php }?>
                <div class="dt_newsscroll_info_desc">
                    <?php echo $article1['desc'];?>
                </div>
                <a class="dt_newsscroll_info_more" href="/content/<?php echo $article1['url'];?>">Батафсил</a>
            </div>
        </div>
        <?php }?>
    </div>

</div>
<script type="text/javascript">
    jQuery.fn.NewsScroll<?php echo $module_id?> = function() {
        return this.each(function(){
            speed<?php echo $module_id?>  = 0.05;
            newslist<?php echo $module_id?>  = jQuery(this);
            newslistW<?php echo $module_id?>  = 0;
            parentW<?php echo $module_id?> = $('#dt_newsscrollwrap<?php echo $module_id?>').parent().width();
            $('#dt_newsscrollwrap<?php echo $module_id?>').width(parentW<?php echo $module_id?>);
            wrapW<?php echo $module_id?>  = $('#dt_newsscrollwrap<?php echo $module_id?>').width();
            newslist<?php echo $module_id?>.find("li").each(function(i){
                newslistW<?php echo $module_id?>  += jQuery(this, i).width()+5;
            });

            newslist<?php echo $module_id?>.width(newslistW<?php echo $module_id?>);
            aTiming<?php echo $module_id?>  = newslistW<?php echo $module_id?>/speed<?php echo $module_id?>;
            totalS<?php echo $module_id?>  = newslistW<?php echo $module_id?>+wrapW<?php echo $module_id?>;

            function scrollnews<?php echo $module_id?>(spacing<?php echo $module_id?>, temp<?php echo $module_id?>){
                newslist<?php echo $module_id?>.animate({left: '-='+ spacing<?php echo $module_id?>}, temp<?php echo $module_id?>, "linear", function(){newslist<?php echo $module_id?>.css("left", wrapW<?php echo $module_id?> ); scrollnews<?php echo $module_id?>(totalS<?php echo $module_id?>, aTiming<?php echo $module_id?>);});
            }

            $(window).resize(function() {
                newslist<?php echo $module_id?>.stop();
                newslist<?php echo $module_id?>.hide();
                $('#dt_newsscrollwrap<?php echo $module_id?>').css('width', 'auto');
                $('#dt_newsscrollwrap<?php echo $module_id?>').css('width', $('#dt_newsscrollwrap<?php echo $module_id?>').parent().width());
                newslist<?php echo $module_id?>.show();
                newslist<?php echo $module_id?>.width(newslistW<?php echo $module_id?>);
                aTiming<?php echo $module_id?>  = newslistW<?php echo $module_id?> /speed<?php echo $module_id?>;
                totalS<?php echo $module_id?>  = newslistW<?php echo $module_id?>+wrapW<?php echo $module_id?>;
                scrollnews<?php echo $module_id?>(totalS<?php echo $module_id?> , aTiming<?php echo $module_id?>);
            });

            scrollnews<?php echo $module_id?>(totalS<?php echo $module_id?> , aTiming<?php echo $module_id?>);

            $('#dt_newsscrollwrap<?php echo $module_id?>').hover(function(){
                newslist<?php echo $module_id?>.stop();}, function(){
                offset<?php echo $module_id?> = newslist<?php echo $module_id?>.offset();
                rSpace<?php echo $module_id?> = offset<?php echo $module_id?>.left + newslistW<?php echo $module_id?>;
                rTime<?php echo $module_id?> = rSpace<?php echo $module_id?> /speed<?php echo $module_id?>;
                scrollnews<?php echo $module_id?> (rSpace<?php echo $module_id?> , rTime<?php echo $module_id?>);
            });
        });
    };


    $('#dt_newsscrollwrap<?php echo $module_id?> .dt_newsscroll_item').mouseenter(function(e){
        $('#dt_newsscrollwrap<?php echo $module_id?> .dt_newsscroll_info').stop().css({'left':'auto',right:'auto'}).hide();
        windowW<?php echo $module_id?> = $(window).width();
        infoBoxW<?php echo $module_id?> = $('#dt_newsscrollwrap<?php echo $module_id?> .dt_newsscroll_info:first').innerWidth();
        mouseX<?php echo $module_id?> = (e.pageX);
        moduleOffset<?php echo $module_id?> = $('#dt_newsscrollwrap<?php echo $module_id?>').offset().left;
        itemIndex<?php echo $module_id?> = $(this).index();
        if(windowW<?php echo $module_id?> - mouseX<?php echo $module_id?> > infoBoxW<?php echo $module_id?>){
            $('#dt_newsscrollwrap<?php echo $module_id?> .dt_newsscroll_info').eq(itemIndex<?php echo $module_id?>)
                .css({'left':mouseX<?php echo $module_id?>-moduleOffset<?php echo $module_id?>-20})
                .fadeIn(300);
        }else{
            $('#dt_newsscrollwrap<?php echo $module_id?> .dt_newsscroll_info').eq(itemIndex<?php echo $module_id?>)
                .css({'right':0})
                .fadeIn(300);
        }
    }),$('#dt_newsscrollwrap<?php echo $module_id?>').mouseleave(function(){
        $('#dt_newsscrollwrap<?php echo $module_id?> .dt_newsscroll_info').hide();
    });


    $(function(){
        $('#dt_newsscrollwrap<?php echo $module_id?> .dt_newsscroll_list').NewsScroll<?php echo $module_id?>();
    });
</script>


