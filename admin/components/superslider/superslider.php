<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.04.2017
 * Time: 21:23
 */
define('SS_BACKEND_PATH', PATH . '/admin/components/superslider');
define('SS_BACKEND_URL', '?do=components&action=config&id='.$_REQUEST['id']);

$db=Registry::get("DataBase");
$core = Registry::get("Core");

if (isset($_REQUEST['opt'])) { $opt = $_REQUEST['opt']; } else { $opt = 'list_sliders'; }
$sql = "SELECT config FROM components WHERE link = 'superslider'";
$result = $db->query($sql) ;

if ($db->numrows($result)){
    $conf = $db->fetch($result,true);
    if ($conf){
        $cfg = unserialize($conf['config']);
    }
}
$core->loadClass('superslider');
$model=new model_superslider();

$GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/admin/components/superslider/js/common.js"></script>';
$GLOBALS['cp_page_head'][] = '<link type="text/css" rel="stylesheet" href="/admin/components/superslider/css/styles.css">';

if ($opt == 'list_sliders'){

    $toolmenu = array(
        array(
            'icon' => 'newphotomulti.gif',
            'title' => 'Новый слайдер',
            'link' => SS_BACKEND_URL . '&opt=add'
        )
    );

    $sliders = $model->getSliders();

    include(SS_BACKEND_PATH.'/slider_list.tpl.php');
}

if ($opt == 'add' || $opt == 'edit') {
    $is_submitted = $_REQUEST['submit'];
    $id = $_REQUEST['item_id'];


    if (!$is_submitted){
        $toolmenu = array(
            array(
                'icon' => 'save.gif',
                'title' => 'Сохранить',
                'link' => 'javascript:document.addform.submit();'
            ),
            array(
                'icon' => 'cancel.gif',
                'title' => 'Отмена',
                'link' => SS_BACKEND_URL
            ),
        );
        if ($opt == 'edit'){
            $slider = $model->getSlider($id);
        }
//        print_r($slider);
//        echo $id;
        include(SS_BACKEND_PATH.'/slider_add.tpl.php');
        return;
    }

    $slider = array(
        'title' => $_REQUEST['title'],
        'width' => $_REQUEST['width'],
        'height' => $_REQUEST['height'],
        'is_external' => $_REQUEST['is_external'],
        'options' => $_REQUEST['options']
    );

    if ($opt == 'add'){ $model->addSlider($slider); }
    if ($opt == 'edit'){ $model->updateSlider($id, $slider); }

    header('Location: index.php' . SS_BACKEND_URL);

}


if ($opt == 'slider'){

    $id = $_REQUEST['item_id'];

    $toolmenu = array(
        array(
            'icon' => 'newphoto.gif',
            'title' => 'Новый слайд',
            'link' => SS_BACKEND_URL . '&opt=add_slide&item_id=' . $id
        ),
        array(
            'icon' => 'reorder.gif',
            'title' => 'Сохранить порядок слайдов',
            'link' => 'javascript:document.reorderform.submit()'
        ),
        array(
            'icon' => 'cancel.gif',
            'title' => 'Отмена',
            'link' => SS_BACKEND_URL
        ),
    );

    $slider = $model->getSlider($id);
    $slides = $model->getSlides($id);

//    cpAddPathway($slider['title'], SS_BACKEND_URL . "&opt=slider&item_id={$id}");

    include(SS_BACKEND_PATH.'/slider_slides.tpl.php');

}

if ($opt == 'add_slide' || $opt == 'edit_slide'){

    $is_submitted = $_REQUEST['struct'];
    $slider_id = $_REQUEST['item_id'];
    $id = $_REQUEST['slide_id'];

    $slider = $model->getSlider($slider_id);

//    cpAddPathway($slider['title'], SS_BACKEND_URL . "&opt=slider&item_id={$slider_id}");

    if (!$is_submitted){

//        if (version_compare(CORE_VERSION, '1.10.3', '<')){
//            $GLOBALS['cp_page_head'][] = '<link type="text/css" rel="stylesheet" href="/admin/components/superslider/css/jquery-ui.css">';
//            $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/admin/components/superslider/js/jquery-ui.js"></script>';
//        }
        $GLOBALS['cp_page_head'][] = '<link type="text/css" rel="stylesheet" href="/includes/jqueryui/css/smoothness/jquery-ui.min.css">';
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/includes/jquery-ui/jquery-ui-1.11.2.min.js"></script>';

        $GLOBALS['cp_page_head'][] = '<link type="text/css" rel="stylesheet" href="/admin/components/superslider/css/colorpicker.css">';
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/admin/components/superslider/js/colorpicker.js"></script>';
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/admin/components/superslider/js/fileuploader.js"></script>';
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/admin/components/superslider/js/slide.js"></script>';

        $toolmenu = array(
            array(
                'icon' => 'save.gif',
                'title' => 'Сохранить',
                'link' => 'javascript:ssSave()',
            ),
            array(
                'icon' => 'cancel.gif',
                'title' => 'Отмена',
                'link' => SS_BACKEND_URL . '&opt=slider&item_id=' . $slider_id
            ),
        );

        if ($opt == 'edit_slide'){
            $slide = $model->getSlide($id);
        }

        include(SS_BACKEND_PATH.'/slide_add.tpl.php');
        return;

    }

    $slide = array(
        'slider_id' => $slider_id,
        'title' => $_REQUEST['title'],
        'bg_image' => $_REQUEST['bg_image'],
        'bg_color' => $_REQUEST['bg_color'],
        'struct' => $_REQUEST['struct'],
    );

    if ($opt == 'add_slide'){ $model->addSlide($slide); }
    if ($opt == 'edit_slide'){ $model->updateSlide($id, $slide); }

    header('Location: index.php' . SS_BACKEND_URL . '&opt=slider&item_id=' . $slider_id);

    return;

}



if ($opt == 'reorder_slides'){

    $slider_id = $_REQUEST['item_id'];
    $ord = $_REQUEST['ord'];

    if (!$ord) { header('Location: ' . $_SERVER['HTTP_REFERER']); }

    $model->reorderSlides($ord);

    header('Location: index.php' . SS_BACKEND_URL . '&opt=slider&item_id=' . $slider_id);

}


if ($opt == 'upload_image'){

    if (!isset($_GET['qqfile'])) { exit; }

    $file = $_GET['qqfile'];

    $slider_id = $_REQUEST['slider_id'];

    $slider = $model->getSlider($slider_id);

    if (!$slider) { exit; }

    $destination_url = '/images/slider/' . $slider_id;
    $destination_folder = PATH . $destination_url;
    mkdir($destination_folder);

    $token = mb_substr(md5(implode('::', array(microtime(true), $file, session_id()))), 0, 8);

    $destination_file = '/bg-' . $token . '.' . pathinfo($file, PATHINFO_EXTENSION);
    $destination_file_cropped = '/bg-' . $token . '.jpg';
    $destination = $destination_folder . $destination_file;
    $destination_cropped = $destination_folder . $destination_file_cropped;

    $input = fopen("php://input", "r");
    $temp = tmpfile();
    stream_copy_to_stream($input, $temp);
    fclose($input);

    $target = fopen($destination, "w");
    fseek($temp, 0, SEEK_SET);
    stream_copy_to_stream($temp, $target);
    fclose($target);

    @resize_image($destination, $destination_cropped, $slider['width'], false);

    $result = array(
        'success' => true,
        'src' => $destination_url . $destination_file_cropped
    );

    echo json_encode($result); die();

}

if ($opt == 'upload_layer_image'){

    if (!isset($_GET['qqfile'])) { exit; }

    $file = $_GET['qqfile'];

    $slider_id = $_REQUEST['slider_id'];
    $layer_id = $_REQUEST['layer_id'];

    $slider = $model->getSlider($slider_id);

    if (!$slider) { exit; }

    $destination_url = '/images/slider/' . $slider_id;
    $destination_folder = PATH . $destination_url;
    mkdir($destination_folder);

    $token = mb_substr(md5(implode('::', array($layer_id, microtime(true), $file, session_id()))), 0, 8);

    $destination_file = '/bg-' . $token . '.' . pathinfo($file, PATHINFO_EXTENSION);
    $destination = $destination_folder . $destination_file;

    $input = fopen("php://input", "r");
    $temp = tmpfile();
    stream_copy_to_stream($input, $temp);
    fclose($input);

    $target = fopen($destination, "w");
    fseek($temp, 0, SEEK_SET);
    stream_copy_to_stream($temp, $target);
    fclose($target);

    list($source_width, $source_height, $source_type) = getimagesize($destination);

    $result = array(
        'success' => true,
        'layer_id' => $layer_id,
        'width' => $source_width,
        'height' => $source_height,
        'src' => $destination_url . $destination_file
    );

    echo json_encode($result); die();

}

if ($opt == 'delete'){

    $id = $_REQUEST['item_id'];

    $model->deleteSlider($id);

    header('Location: index.php' . SS_BACKEND_URL);

}

if ($opt == 'delete_slide'){

    $id = $_REQUEST['item_id'];

    $model->deleteSlide($id);

    header('Location: ' . $_SERVER['HTTP_REFERER']);

}

//============================================================================//

function resize_image($file, $destination, $w, $h) {
    //Get the original image dimensions + type
    list($source_width, $source_height, $source_type) = getimagesize($file);

    //Figure out if we need to create a new JPG, PNG or GIF
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($ext == "jpg" || $ext == "jpeg") {
        $source_gdim=imagecreatefromjpeg($file);
    } elseif ($ext == "png") {
        $source_gdim=imagecreatefrompng($file);
    } elseif ($ext == "gif") {
        $source_gdim=imagecreatefromgif($file);
    } else {
        //Invalid file type? Return.
        return;
    }

    //If a width is supplied, but height is false, then we need to resize by width instead of cropping
    if ($w && !$h) {
        $ratio = $w / $source_width;
        $temp_width = $w;
        $temp_height = $source_height * $ratio;

        $desired_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
            $desired_gdim,
            $source_gdim,
            0, 0,
            0, 0,
            $temp_width, $temp_height,
            $source_width, $source_height
        );
    } else {
        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $w / $h;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $temp_height = $h;
            $temp_width = ( int ) ($h * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $w;
            $temp_height = ( int ) ($w / $source_aspect_ratio);
        }

        /*
         * Resize the image into a temporary GD image
         */

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
            $temp_gdim,
            $source_gdim,
            0, 0,
            0, 0,
            $temp_width, $temp_height,
            $source_width, $source_height
        );

        /*
         * Copy cropped region from temporary image into the desired GD image
         */

        $x0 = ($temp_width - $w) / 2;
        $y0 = ($temp_height - $h) / 2;
        $desired_gdim = imagecreatetruecolor($w, $h);
        imagecopy(
            $desired_gdim,
            $temp_gdim,
            0, 0,
            $x0, $y0,
            $w, $h
        );
    }

    /*
     * Render the image
     * Alternatively, you can save the image in file-system or database
     */

    if ($ext == "jpg" || $ext == "jpeg") {
        ImageJpeg($desired_gdim,$destination,100);
    } elseif ($ext == "png") {
        ImagePng($desired_gdim,$destination);
    } elseif ($ext == "gif") {
        ImageGif($desired_gdim,$destination);
    } else {
        return;
    }

    ImageDestroy ($desired_gdim);
}
?>