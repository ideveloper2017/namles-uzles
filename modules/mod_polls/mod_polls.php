<link rel="stylesheet" href="/modules/mod_polls/css/polls.css">
<?php



function mod_polls($id){

?>
    <div id="pollcontainer"></div>
    <script type="text/javascript">
        $(function () {
            var pollcontainer = $('#pollcontainer');
            $.get('/modules/mod_polls/poll.php', '', function (data, status) {
                pollcontainer.html(data);
                pollcontainer.find('#viewresult').click(function () {
                    $.get('/modules/mod_polls/poll.php', 'result=1', function (data, status) {
                        pollcontainer.fadeIn("fast", function () {
                            $(this).html(data);
                        });
                    });
                    return false;
                }).end().find('#pollform .votenow').click(function () {
                    var selected_val = $("#pollform").find('input[name=poll]:checked').val();
                    if (selected_val != undefined) {
                        $.post('/modules/mod_polls/poll.php', $("#pollform").serialize(), function (data, status) {
                            $('#formcontainer').fadeIn(100, function () {
                                $("#pollform").html(data);
                            });
                        });
                    }
                    return false;
                });
            });
        });
    </script>
<?php
return true;
}


?>                        