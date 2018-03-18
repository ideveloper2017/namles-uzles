<?php
$params = unserialize($inMod['params']);
?>
    <?php
    if ($inMod['showtitle']) {
        echo '<h2><span>' . $inMod['title'] . '</span></h2>';
    }
    print $inMod['body'] . $inMod['jscode'];
    ?>

