<link rel="stylesheet" href="/modules/mod_clock/css/monthly.css">
<link href="/modules/mod_clock/css/style.css" rel="stylesheet" type="text/css" media="all" />
    <div class="w3layouts-top">
        <div class="w3agile-clock">
            <div class="w3ls-wrapper">
                <div class="clock" id="clock"></div>
                <div class="dmy">
                    <div class="date">
                        <script type="text/javascript">
                            var mydate=new Date()
                            var year=mydate.getYear()
                            if(year<1000)
                                year+=1900
                            var day=mydate.getDay()
                            var month=mydate.getMonth()
                            var daym=mydate.getDate()
                            if(daym<10)
                                daym="0"+daym
                            var dayarray=new Array("Якшанба","Душанба","Сешанба","Чоршанба","Пайшанба","Жума","Шанба")
                            var montharray=new Array("Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь")
                            document.write(""+daym+", "+year+"<br> "+dayarray[day]+" , "+montharray[month]+" ")
                        </script>
                    </div>
                </div>

            </div>
        </div>
        <div class="clear"> </div>
    </div>

<!-- calendar -->
<script type="text/javascript" src="/modules/mod_clock/js/monthly.js"></script>
<script type="text/javascript">
    $(window).load( function() {

        $('#mycalendar').monthly({
            mode: 'event',
        });

        $('#mycalendar2').monthly({
            mode: 'picker',
            target: '#mytarget',
            setWidth: '250px',
            startHidden: true,
            showTrigger: '#mytarget',
            stylePast: true,
            disablePast: true
        });

        switch(window.location.protocol) {
            case 'http:':
            case 'https:':
                break;
            case 'file:':
                alert('Just a heads-up, events will not work when run locally.');
        }

    });
</script>
<script type="text/javascript" src="/modules/mod_clock/js/clock-1.1.0.js"></script>
<script>
    var clock = $("#clock").clock(),
        data = clock.data('clock');
    // data.pause();
    // data.start();
    // data.setTime(new Date());
    var d = new Date();
    d.setHours(9);
    d.setMinutes(51);
    d.setSeconds(20);
</script>
