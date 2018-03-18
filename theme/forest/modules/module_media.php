<?php
$params = unserialize($inMod['params']);
?>
<div class="content-panel">

<?php
if ($inMod['showtitle']) {
    echo '<div class="panel-header">';
    echo '<b><span class="icon-text"></span>'. $inMod['title'].'</b>';
    echo '</div>';
}
print $inMod['body'] . $inMod['jscode'];
//print " <div class=\"clear_soft\"></div>";

?>
        </div>



