<?php
$params = unserialize($inMod['params']);
?>

<?php
if ($inMod['showtitle']) {
    echo '<div class="widget_title widget_black">
   <h2><a href="#">' . $inMod['title'] . '<a/></h2>
   </div>';
}
print $inMod['body'] . $inMod['jscode'];
?>