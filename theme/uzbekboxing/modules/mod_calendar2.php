<link rel="stylesheet" type="text/css" href="/modules/mod_calendar/style.css"/>
<div class="sidebar-bix-1">
    <div id="cal-wrap-small">
        <?php Registry::get("Calendar")->renderCalendar('small'); ?>
    </div>
</div>
<script type="text/javascript">
    // <![CDATA[
    function loadList() {
        $.ajax({
            url: "/modules/mod_calendar/calendar.php",
            cache: false,
            success: function (html) {
                $("#cal-wrap-small").html(html);
            }
        });
    }
    $(document).ready(function () {
        $("#cal-wrap-small").on("click", "a.changedate", function () {
            $("#cal-wrap-small").addClass('loader');
            var month = $(this).data('m');
            var year = $(this).data('y');
            $.ajax({
                type: "post",
                url: "/modules/mod_calendar/calendar.php",
                data: {
                    'year': year,
                    'month': month
                },
                success: function (data, status) {
                    $("#cal-wrap-small").fadeIn("fast", function () {
                        $(this).html(data);
                    });
                    $("#cal-wrap-small").removeClass('loader');
                }
            });
            return false;
        });

        $("#cal-wrap-small").on("click", "a.view-events", function () {
            var d = $(this).data('d');
            var m = $(this).data('m');
            var y = $(this).data('y');
            var caption = $(this).data('title');
            Messi.load('/modules/mod_calendar/ajax/ajax_calendar.php', {
                loadEvent: 1,
                d: d,
                m: m,
                y: y
            }, {
                title: caption
            });
        });
    });
    // ]]>
</script>