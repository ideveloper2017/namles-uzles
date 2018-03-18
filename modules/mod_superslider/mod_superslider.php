<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.04.2017
 * Time: 0:14
 */
function mod_superslider($id){

    $core = Registry::get("Core");

    $core->loadClass('superslider');
    $model=new model_superslider();
    $cfg=$core->getModuleConfig($id);


    if (!isset($cfg['slider_id'])){ return false; }

//    $slider_id = $cfg['slider_id'];
    $slider_id = 3;

    if (!isset($cfg['delay'])){ $cfg['delay'] = 3500; }
    if (!isset($cfg['speed'])) { $cfg['speed'] = 1000; }
    if (!isset($cfg['is_auto'])) { $cfg['is_auto'] = true; }
    if (!isset($cfg['is_nav'])) { $cfg['is_nav'] = false; }
    if (!isset($cfg['is_dots'])) { $cfg['is_dots'] = false; }
    if (!isset($cfg['is_horizontal'])) { $cfg['is_horizontal'] = false; }
    if (!isset($cfg['is_reverse'])) { $cfg['is_reverse'] = false; }

    $slider = $model->getSlider($slider_id);
//    print_r($slider);
//
//
//    if (!$slider['is_external']){
//        $slides = $model->getSlides($slider_id, $cfg['is_reverse']);
//    } else {
        $slides = $model->getExternalSlides($slider);
//    }
//print_r($slides);
    if (!$slider || !$slides) { return false; }

    $slides_tags = array();

//    print_r($slides);
    foreach($slides as $slide){
        $struct = json_decode($slide['struct'], true);
        foreach($struct as $layer){

            $styles = array();

            foreach($layer['styles'] as $rule=>$value){
                if ($value=='' || $value=='normal' || $value=='auto') { continue; }
                $styles[] = "{$rule}:{$value}";
            }

            $text = htmlspecialchars($layer['text']);

            $href = empty($layer['href'])? '' : $layer['href'];
            $tag_pattern = empty($layer['href'])? ' <div style="%s">%s</div>' : '<a href="%3$s" style="%1$s">%2$s</a>';

            $tag = sprintf($tag_pattern, implode(';', $styles), $text, $href);
            $slides_tags[$slide['id']][] = $tag;
//        print_r($slides_tags[$slide['id']]);
        }
    }

    $size_css = "width:{$slider['width']}px;height:{$slider['height']}px;";
//echo $size_css;
    $slides=$slides;
    $slider=$slider;
    $slides_tags= $slides_tags;
    $slides_count=sizeof($slides);
    $slides_tag=$slides_tags;
    $direction=$cfg['is_horizontal'] ? 'left' : 'top';
    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_superslider.php');
}
?>