<?php
$params = unserialize($inMod['params']);
?>

<!--    <section class="section-wrap --><?php //if ($inMod['css_prefix']) { ?><!----><?php //echo $inMod['css_prefix'] ?><!-- --><?php //} ?><!--">-->
<!--        <div class="container">-->
<!--            <div class="row">-->
<?php
if ($inMod['showtitle']) {
    echo '<h3 class="section-title">' . $inMod['title'] . '</h3>';
}

print $inMod['body'] . $inMod['jscode'];
//print "</div>";
//print "</div>";
//print '</section>';
?>