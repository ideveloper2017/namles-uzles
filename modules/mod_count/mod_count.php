<?php
/*For InstantCMS*/


function mod_count($module_id){
    $inCore = Registry::get("Core");
    $inDB = Registry::get("DataBase");

    ?>

    <html>
    <head>
        <style type="text/css">
            <!--
            #cd {
                margin: auto;
                height: 30px;
                width: 230px;
                font-family: "Courier New", Courier, mono;
                font-size: 16pt;
                color: yellow;
                text-align: center;
                font-weight: bold;
                background-image: url(/modules/mod_count/back.jpg);
                vertical-align: middle;
            }
            -->
        </style>
    </head>

    <body>
    <table cellpadding="0" cellspacing="0" border="0" align="center"><tr><td align="center" valign="middle">
                <h3 align="center"> До Нового года осталось </h3>
                <SCRIPT language="JavaScript" SRC="/modules/mod_count/countdown3.php?timezone=Europe/Moscow&countto=2017-01-01 00:00:00&do=t&data=С Новым Годом!!!"></SCRIPT>
                <!--Тайм-зона http://www.php.net/manual/en/timezones.php
                do=t&data= - текст, который будет написан вместо таймера по наступлению часа Х
                do=r&data= Когда наступит час X, отправим посетителя на какую-нибудь страницу, например : http://google.com
                -->
                <p>&nbsp;</p>
                <a></a>
            </td></tr></table>
    </body>
    </html>


    <?php

    return true;

}
?>