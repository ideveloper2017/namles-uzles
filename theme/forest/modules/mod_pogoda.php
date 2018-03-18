<div style="width:100%;position:relative;">
    <div class="threePicBlock">
        <div class="threePicItem threePicItem1">
            <a href="javascript:void(0)" onClick="show_pinf()" title="Погода на завтра"></a>
            <span id="pogoda" style="font-size: 26px;color:#B3B3B3;"><?php echo $pogoda['temperature_s']?></span>
        </div>
        <div class="threePicItem threePicItem2">
            <a href="javascript:void(0)" onClick="show_vinf()" title="Курс валют"></a>
            <span id="valuta" style="font-size: 26px;color:#B3B3B3;"><?php echo $valuta['USD'] ?></span>
        </div>
        <div class="threePicItem threePicItem3">
            <a href="javascript:void(0)" onClick="show_vinf()" title="Курс валют"></a>
            <span id="valuta" style="font-size: 26px;color:#B3B3B3;"><?php echo $valuta['EUR'] ?></span>
        </div>
    </div>
    <div id="p_inf"
         style="display:none;padding:5px;position:fixed;z-index:101;background:#fff;border: 1px solid rgba(0, 0, 0, 0.15);box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.176);">
        <strong>Погода на завтра:</strong>
        <a href="javascript:void(0)" style="float:right;color:red;" onClick="$('#p_inf').hide()">[x]</a>
        <hr>
        <div style="float:left:margin:3px;">
            <strong>Утром:</strong> <br>
            <span style="font-size: 26px;color:#B3B3B3;"><?php echo $pogoda['morning']?></span>,<?php echo $pogoda['alt1']?>  <br>
            <img src="http://yandex.st/weather/1.1.78/i/icons/48x48/<?php echo $pogoda['img1']?>.png" width="48" height="48"
                 title="{$pogoda.alt1}"/>
        </div>

        <div style="float:left:margin:3px;">
            <strong>Днем:</strong> <br>
            <span style="font-size: 26px;color:#B3B3B3;"><?php echo $pogoda['temperature']?></span>, <?php echo $pogoda['alt2']?> <br>
            <img src="http://yandex.st/weather/1.1.78/i/icons/48x48/<?php echo $pogoda['img2']?>.png" width="48" height="48"
                 title="{$pogoda.alt2}"/>
        </div>

        <div style="float:left:margin:3px;">
            <strong>Вечером:</strong> <br>
            <span style="font-size: 26px;color:#B3B3B3;"><?php echo $pogoda['evening']?></span>,<?php echo $pogoda['alt3']?> <br>
            <img src="http://yandex.st/weather/1.1.78/i/icons/48x48/<?php echo $pogoda['img3']?>.png" width="48" height="48"
                 title="{$pogoda.alt3}"/>
        </div>

    </div>


    <div id="v_inf"
         style="display:none;padding:5px;position:fixed;z-index:101;background:#fff;border: 1px solid rgba(0, 0, 0, 0.15);box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.176);">
        <strong>Курс валют: </strong> <a href="javascript:void(0)" style="float:right;color:red;"
                                         onClick="$('#v_inf').hide()">[x]</a>
        <hr>
<span style="font-size: 26px;color:#B3B3B3;">
<img src="/modules/mod_pogoda/dollar.png">USD <?php echo $valuta['USD'] ?></span><br>
<span style="font-size: 26px;color:#B3B3B3;">
<img src="/modules/mod_pogoda/euro.png">EUR <?php echo $valuta['EUR'] ?></span><br>
<span style="font-size: 26px;color:#B3B3B3;">
<img src="/modules/mod_pogoda/pound.png">GBP <?php echo $valuta['GBP'] ?> </span><br>
<span style="font-size: 26px;color:#B3B3B3;">
<img src="/modules/mod_pogoda/yen.png">JPY <?php echo $valuta['JPY'] ?></span><br>
<span style="font-size: 26px;color:#B3B3B3;">
<img src="/modules/mod_pogoda/rupee.png">INR <?php echo $valuta['INR'] ?></span><br>
    </div>
</div>


<script>
    function show_pinf() {
        $('#v_inf').hide();
        $('#p_inf').toggle();
    }
    function show_vinf() {
        $('#p_inf').hide();
        $('#v_inf').toggle();
    }
</script>
