<?php
$params = unserialize($inMod['params']);
?>
    <div class="widget <?php if ($inMod['css_prefix']) { ?><?php echo $inMod['css_prefix'] ?> <?php } ?>">

<?php
if ($inMod['showtitle']) {
    echo ' <h4 class="widget-title sidebar__widget-title">' . $inMod['title'] . '</h4>';
}

print $inMod['body'] . $inMod['jscode'];
print "</div>";


?>