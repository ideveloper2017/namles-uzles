<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 19.01.2016
 * Time: 14:07
 */
class p_ckeditor extends Plugins{

    public $config = array(
        'iswatermark'       => 0,
        'photo_width'       => 600,
        'photo_height'      => 600,
        'is_compatible'     => 1,
        'entermode'         => 'CKEDITOR.ENTER_P',
        'skin'              => 'bootstrapck',
        'upload_for_groups' => array(2)
    );

    public function __construct()
    {
        $this->info = array(
            'plugin'      => 'p_ckeditor',
            'title'       => 'CKEditor',
            'description' => "",
            'author'      => 'InstantCMS Team',
            'version'     => '4.4.5',
            'published'   => 1,
            'plugin_type' => 'wysiwyg'
        );

        $this->events = array(
            'INSERT_WYSIWYG'
        );

        parent::__construct();
    }

    public function execute($event = '', $item = array()) {

//        $access = (cmsUser::getInstance()->is_admin) ? 'admin' : 'user';
        $width  = (is_numeric($item['width']) ? $item['width'].'px' : $item['width']);
        $height = (is_numeric($item['height']) ? $item['height'].'px' : $item['height']);

        ob_start(); ?>

        <textarea class="ckeditor" id="<?php echo $item['name']; ?>" name="<?php echo $item['name']; ?>" style="width: <?php echo $width; ?>; height: <?php echo $height; ?>;"><?php echo htmlspecialchars($item['text']); ?></textarea>
        <script type="text/javascript">
            $(function (){
                if(typeof CKEDITOR == 'undefined') {
                    script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src  = '/plugins/p_ckeditor/editor/ckeditor.js';
                    $('head').append(script);
                }

                <?php echo ($this->config['is_compatible'] ? 'CKEDITOR.env.isCompatible = true;' : ''); ?>
                CKEDITOR.replace("<?php echo $item['name']; ?>",{
//                    customConfig : "/plugins/p_ckeditor/editor/config/admin_full.js",
                    skin: "<?php echo $this->config['skin']; ?>",
                    width: "<?php echo $width; ?>",
                    height: "<?php echo $height; ?>",
                    forcePasteAsPlainText: false,
                    extraPlugins: "slideshow",
//                    filebrowserBrowseUrl : '/plugins/p_ckeditor/editor/plugins/filemanager/index.html',
//                    filebrowserImageUploadUrl : '/plugins/p_ckeditor/editor/plugins/imgupload/imgupload.php',


                    filebrowserBrowseUrl : '/plugins/p_ckeditor/editor/kcfinder/browse.php?opener=ckeditor&type=files',
                    filebrowserImageBrowseUrl : '/plugins/p_ckeditor/editor/kcfinder/browse.php?opener=ckeditor&type=images',
                    filebrowserFlashBrowseUrl : '/plugins/p_ckeditor/editor/kcfinder/browse.php?opener=ckeditor&type=flash',
                    filebrowserUploadUrl : '/plugins/p_ckeditor/editor/kcfinder/upload.php?opener=ckeditor&type=files',
                    filebrowserImageUploadUrl : '/plugins/p_ckeditor/editor/kcfinder/upload.php?opener=ckeditor&type=images',
                    filebrowserFlashUploadUrl : '/plugins/p_ckeditor/editor/kcfinder/upload.php?opener=ckeditor&type=flash',
//                    filebrowserBrowseUrl : '/plugins/p_ckeditor/editor/kcfinder/browse.php?opener=ckeditor&type=files',
//                    filebrowserImageBrowseUrl : '/plugins/p_ckeditor/editor/kcfinder/browse.php?opener=ckeditor&type=images',
//                    filebrowserFlashBrowseUrl : '/plugins/p_ckeditor/editor/kcfinder/browse.php?opener=ckeditor&type=flash',
//                    filebrowserUploadUrl : '/plugins/p_ckeditor/editor/kcfinder/upload.php?opener=ckeditor&type=files',
//                    filebrowserImageUploadUrl : '/plugins/p_ckeditor/editor/kcfinder/upload.php?opener=ckeditor&type=images',
//                    filebrowserFlashUploadUrl : '/plugins/p_ckeditor/editor/kcfinder/upload.php?opener=ckeditor&type=flash',
                    locationMapPath: "<?php echo PATH; ?>/plugins/p_ckeditor/editor/plugins/locationmap/",
                    enterMode: <?php echo $this->config['entermode']; ?>,
                    language: "ru"
                });
            });
        </script>

        <?php return ob_get_clean();

    }
}
?>