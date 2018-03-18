<link href="/modules/mod_superslider/css/superslider.css" rel="stylesheet" type="text/css" />
<script src="/modules/mod_superslider/js/superslider.js" type="text/javascript"></script>

<div class="superslider" id="superslider-<?php echo $id; ?>">
    <ul class="slider<?php if ($cfg['is_horizontal']) {?> horizontal<?php } ?>" style="<?php echo $size_css;?>">
        <?php
//        print_r($slides);
        $slide_index=1;
        foreach($slides as $slide){
        ?>
<!--        {assign var="slide_index" value=1}-->
<!--        {foreach key=slide_id item=slide from=$slides}-->
        <li class="slide slide-<?php echo $slide_index; ?>" data-id="<?php echo $slide_index; ?>" style="<?php echo $size_css; if ($slide['bg_image']){?>background-image:url('<?php echo $slide['bg_image'];?>');<?php } ?>
            <?php if ($slide['bg_color']){ ?>background-color:<?php echo $slide['bg_color']; }?>">

            <?php
//            print_r($slides_tag[$slide['slide_id']]);
            foreach($slides_tag[$slide['id']] as $tag){?>
<!--            {foreach item=tag from=$slides_tags[$slide_id]}-->
            <?php echo $tag;?>
            <?php }?>
        </li>
<!--        {math equation="x + 1" x=$slide_index assign="slide_index"}-->
        <?php
        $slide_index++;
        } ?>
        <?php if ($cfg['is_nav']) {?>
        <?php if ($direction == 'left'){?>
        <a class="nav-left-right nav-left" href="#"></a>
        <a class="nav-left-right nav-right" href="#"></a>
        <?php }?>
        <?php if ($direction == 'top') {?>
        <a class="nav-up-down nav-up" href="#"></a>
        <a class="nav-up-down nav-down" href="#"></a>
        <?php }?>
        <?php }?>

    </ul>
    <?php if ($cfg['is_dots']) {?>
    <div class="dots-wrap">
        <ul class="dots-nav">
            <?php
            for($sid=0;$sid<$slides_count; $sid++){?>
            <li><a class="dot<?php if ($sid==0) {?> active <?php } ?>" href="#" data-id="<?php echo $sid+1?>"></a></li>
            <?php }?>
        </ul>
    </div>
    <?php }?>
</div>

<script type="text/javascript">
    var options = {
        slides: <?php echo $slides_count; ?>,
        width: <?php echo $slider['width'];?>,
        height:<?php echo $slider['height']; ?>,
        speed: <?php echo $cfg['speed'];?>,
        direction: '<?php echo $direction; ?>',
        delay: <?php echo $cfg['delay'];?>,
        auto: <?php echo $cfg['is_auto'];?>};
    var slider<?php echo $id?> = new SuperSlider(<?php echo $id; ?>, options);
</script>

