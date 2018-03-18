<div class="footer clearfix">
<!--    <div class="pull-left">&copy; 2013. Londinium Admin Template by <a href="http://themeforest.net/user/Kopyov">Eugene Kopyov</a></div>-->
<!--    <div class="pull-right icons-group">-->
<!--        <a href="#"><i class="icon-screen2"></i></a>-->
<!--        <a href="#"><i class="icon-balance"></i></a>-->
<!--        <a href="#"><i class="icon-cog3"></i></a>-->
<!--    </div>-->
</div>
<!-- /footer -->
<!--    <script type="text/javascript" src="js/"></script>-->

<!--    <script type="text/javascript" src="js/fullcalendar.js"></script>-->

<script type="text/javascript">
    // <![CDATA[
    $(document).ready(function () {
        $.Master({
            weekstart: 1,
//            contentPlugins: <?php //echo ($core->editor == 1) ? "{" . $editorPlugins . "}" : "[" . $editorPlugins . "]";?>//,
            editor: 2,
            editorCss: ["<?php echo SITEURL . '/admin/css/master_main.css';?>","<?php echo SITEURL . '/admin/css/master_main.css';?>"],
//            lang: {
//                button_text: "<?php //echo Lang::$word->_CHOOSE;?>//",
//                empty_text: "<?php //echo Lang::$word->_NOFILE;?>//",
//                monthsFull: [<?php //echo Core::monthList(false);?>//],
//                monthsShort: [<?php //echo Core::monthList(false, false);?>//],
//                weeksFull : [<?php //echo Core::weekList(false);?>//],
//                weeksShort : [<?php //echo Core::weekList(false, false);?>//],
//                today : "<?php //echo Lang::$word->_MN_TODAY;?>//",
//                clear : "<?php //echo Lang::$word->_CLEAR;?>//",
//                delMsg1: "<?php //echo Lang::$word->_DEL_CONFIRM;?>//",
//                delMsg2: "<?php //echo Lang::$word->_DEL_CONFIRM1;?>//",
//                working: "<?php //echo Lang::$word->_WORKING;?>//"
//            }
        });
    });
    // ]]>
</script>

</div>
</div>

</body>
</html>