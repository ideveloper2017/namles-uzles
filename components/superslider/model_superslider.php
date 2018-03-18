<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.04.2017
 * Time: 21:35
 */
class model_superslider{

    private $db;
    public function __construct(){
        $this->db = Registry::get("DataBase");
    }
    public function getSliders(){

        $sql = "SELECT *
                FROM superslider_sliders
                ORDER BY id";

        $result = $this->db->query($sql);

        if (!$this->db->numrows($result)){ return false; }

        $sliders = array();

        while($slider = $this->db->fetch($result,true)){
            $sliders[] = $slider;
        }

        return $sliders;

    }

    public function getSlider($id, $field='id'){

        $sql = "SELECT *
                FROM superslider_sliders
                WHERE {$field} = '{$id}'
                LIMIT 1";


        $result = $this->db->query($sql);

        if (!$this->db->numrows($result)){ return false; }

        $slider = $this->db->fetch($result,true);

        $slider['options'] = unserialize($slider['options']);

        return $slider;

    }

    public function addSlider($slider){

        $slider['options'] = serialize($slider['options']);

        foreach ($slider as $field => $value){ $slider[$field] = $this->db->escape($value); }

        $this->db->insert('superslider_sliders',$slider);
//        $sql = "INSERT INTO superslider_sliders (title, width, height, is_external, options)
//                VALUE ('{$slider['title']}', '{$slider['width']}', '{$slider['height']}', '{$slider['is_external']}', '{$slider['options']}')";

//        $this->db->query($sql);

//        $id = $this->db->get_last_id('cms_superslider_sliders');
        $id = $this->db->insertid();

        return $id;

    }


    public function getSlides($slider_id, $is_reverse = false){

        $order = $is_reverse ? 'DESC' : 'ASC';

        $sql = "SELECT *
                FROM superslider_slides
                WHERE slider_id = '{$slider_id}'
                ORDER BY ordering {$order}";

        $result = $this->db->query($sql);

        if (!$this->db->numrows($result)){ return false; }

        $slides = array();

        while($slide = $this->db->fetch($result,true)){
            $slides[$slide['id']] = $slide;
        }

        return $slides;

    }

    public function getSlide($id){

        $sql = "SELECT *
                FROM superslider_slides
                WHERE id = '{$id}'
                LIMIT 1";

        $result = $this->db->query($sql);

        if (!$this->db->numrows($result)){ return false; }

        $slide = $this->db->fetch($result,true);

        return $slide;

    }


    public function reorderSlides($ordering){

        foreach($ordering as $slide_id => $pos){
            $sql = "UPDATE superslider_slides SET ordering = '{$pos}' WHERE id = '{$slide_id}'";
            $this->db->query($sql);
        }

    }

    public function restoreOrderings($slider_id){

        $slides = $this->getSlides($slider_id);

        $index = 1;

        foreach($slides as $slide){
            $sql = "UPDATE superslider_slides SET ordering = '{$index}' WHERE id = '{$slide['id']}'";
            $this->db->query($sql);
            $index++;
        }

    }


    public function updateSlider($id, $slider){

        if (isset($slider['options'])) { $slider['options'] = serialize($slider['options']); }

        $set = array();
        foreach ($slider as $field => $value){ $value = $this->db->escape($value); $set[] = "{$field} = '{$value}'"; }
        $set = implode(', ', $set);

        $sql = "UPDATE superslider_sliders
                SET {$set}
                WHERE id = '{$id}'";

        $this->db->query($sql);

        return true;

    }

    public function updateSlide($id, $slide){

        $set = array();
        foreach ($slide as $field => $value){ $value = $this->db->escape($value); $set[] = "{$field} = '{$value}'"; }
        $set = implode(', ', $set);

        $sql = "UPDATE superslider_slides
                SET {$set}
                WHERE id = '{$id}'";

        $this->db->query($sql);

        return true;

    }


    public function deleteSlide($id){

        $slide = $this->getSlide($id);

        $sql = "DELETE FROM superslider_slides WHERE id = '{$id}'";
        $this->db->query($sql);

        $sql = "UPDATE superslider_sliders SET slides_count = slides_count - 1 WHERE id = '{$slide['slider_id']}'";
        $this->db->query($sql);

        $this->restoreOrderings($slide['slider_id']);

        return true;

    }

    public function deleteSlider($id){

        $sql = "DELETE FROM superslider_sliders WHERE id = '{$id}'";
        $this->db->query($sql);

        $sql = "DELETE FROM superslider_slides WHERE slider_id = '{$id}'";
        $this->db->query($sql);

        return true;

    }

    public function getExternalSlides($slider){

        $template_slides = $this->getSlides($slider['id']);
        $template_slide = array_shift($template_slides);

        if (!$template_slide) { return false; }

        $table = $slider['options']['table'];
        $limit = $slider['options']['limit'];
        $order = $slider['options']['orderby'] ? $slider['options']['orderby'] . ' ' . $slider['options']['orderto'] : false;
        $where = $slider['options']['where'] ? $slider['options']['where'] : false;

        $pattern_image = $slider['options']['image'] ? $slider['options']['image'] : false;
        $pattern_url = $slider['options']['url'] ? $slider['options']['url'] : false;

        $sql = "SELECT * FROM {$table}\n";
        if ($where) { $sql .= "WHERE {$where}\n"; }
        if ($order) { $sql .= "ORDER BY {$order}\n"; }
        $sql .= "LIMIT {$limit}";
//    echo $sql;
        $result = $this->db->query($sql);

        if (!$this->db->numrows($result)){ return false; }

        $items = array();

        while($item = $this->db->fetch($result,true)){

            $item['_image'] = $pattern_image ? $this->replaceFieldTags($pattern_image, $item) : false;
            $item['_url'] = $pattern_url ? $this->replaceFieldTags($pattern_url, $item) : false;

            $items[] = $item;

        }

        $slides = array();

        $struct = json_decode($template_slide['struct']);
        $ordering = 1;

        foreach($items as $item){

            $struct = json_decode($template_slide['struct'], true);

//            echo '<pre>' ; print_r($struct); die();

            foreach($struct as $index=>$layer){

                if ($layer['text'] != '{image}' && $layer['text'] != '{created_at}'){
                    $layer['text'] = $this->replaceFieldTags($layer['text'], $item);
                }

                if ($layer['text'] == '{created_at}'){
//                    $layer['text'] = Core::dodate('Y.m.d', $item['created_at']);
                    $layer['text']=Registry::get("Core")->cmsRusDate(Core::dodate(Registry::get("Config")->long_date, $item['created_at']));
                }

                if ($layer['text'] == '{image}' && $item['_image']){
                    $layer['text'] = '';
                    $layer['styles']['background-image'] = "url({$item['_image']})";
                    $layer['styles']['background-position'] = "center";
                    $layer['styles']['background-repeat'] = "no-repeat";
                }

                if ($layer['href'] != '{url}'){
                    $layer['href'] = $this->replaceFieldTags($layer['href'], $item);
                }

                if ($layer['href'] == '{url}' && $item['_url']){
                    $layer['href'] = $item['_url'];
                }

                $struct[$index] = $layer;

            }

            $new_slide = $template_slide;
            $new_slide['id'] = $item['id'];
            $new_slide['struct'] = json_encode($struct);
            $new_slide['ordering'] = $ordering;

            $ordering++;

            $slides[$new_slide['id']] = $new_slide;

        }

//        echo '<pre>' ; print_r($items); die();

        return $slides;

    }

    public function replaceFieldTags($string, $fields){

        if (empty($string)) { return $string; }

        $matches_count = preg_match_all('/{([a-zA-Z0-9_]+)}/i', $string, $matches);

        if ($matches_count){

            for($i=0; $i<$matches_count; $i++){

                $tag = $matches[0][$i];
                $field = $matches[1][$i];

                if (isset($fields[$field])){
                    $string = str_replace($tag, $fields[$field], $string);
                }

            }

        }

        return $string;

    }
}

