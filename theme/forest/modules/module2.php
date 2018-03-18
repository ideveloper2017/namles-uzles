<?php
$params = unserialize($inMod['params']);
?>
<div class="panel">
    
    <?php
    if ($inMod['showtitle']) {
        echo '<h3>' . $inMod['title'] . '</h3>';
    }

    print $inMod['body'] . $inMod['jscode'];
    ?>
</div>



