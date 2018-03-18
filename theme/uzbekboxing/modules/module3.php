<?php
$params = unserialize($inMod['params']);
?>

    <div class="panel-header">


    <?php
    if ($inMod['showtitle']) {
        ?>
        <b><span class="icon-text">&#9871;</span><?php echo $inMod['title'];?></b>

    <?php

    }
    print "</div>";
    print $inMod['body'] . $inMod['jscode'];
    ?>