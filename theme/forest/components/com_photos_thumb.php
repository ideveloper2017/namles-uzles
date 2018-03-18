<link rel="stylesheet" type="text/css" href="/components/photos/css/jquery.ad-gallery.css">
<script type="text/javascript" src="/components/photos/js/jquery.ad-gallery.js?rand=995"></script>

<script type="text/javascript">
    $(function() {
        var galleries = $('.ad-gallery').adGallery();
    });
</script>

<style type="text/css">
    #gallery {
        padding: 30px;
        background: #e1eef5;
    }
</style>

<div class="grid6_5">
    <div id="gallery" class="ad-gallery">
        <div class="ad-image-wrapper">
        </div>
        <div class="ad-controls">
        </div>
        <div class="ad-nav">
            <div class="ad-thumbs">
                <ul>
                    <?php foreach($photos as $photo) { ?>
                    <li>
                        <a href="/images/photos/<?php echo $photo['files']; ?>">
                            <img src="/images/photos/small/<?php echo $photo['files']?>" title="<?php echo $photo['title']?>" longdesc="<?php echo $photo['title']?>" class="image1">
                        </a>
                    </li>
                   <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>