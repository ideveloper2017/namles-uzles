<div class="row">
<!--    <strong> Сегодня: --><?php //echo $today; ?><!--</strong>-->
    <ul>
       <?php
        $key=0;
        foreach($namaz as $item):

        ?>
        <li class="col-md-2 col-sm-4">
            <div class="namaz">
                <h2><?php echo $namaz_time_title[$key]; ?></h2>
                <strong> <span><?php echo $item; ?></span> </strong></div>
        </li>
            <?php
            $key++;
        endforeach;?>
<!--        <li class="col-md-2 col-sm-4">-->
<!--            <div class="namaz">-->
<!--                <h2>Sunrise</h2>-->
<!--                <strong>Sunrise Time <span>6:36 AM</span> </strong></div>-->
<!--        </li>-->
<!--        <li class="col-md-2 col-sm-4">-->
<!--            <div class="namaz">-->
<!--                <h2>Dhuhr</h2>-->
<!--                <strong>Noon Prayer <span>11:52 AM</span> </strong></div>-->
<!--        </li>-->
<!--        <li class="col-md-2 col-sm-4">-->
<!--            <div class="namaz">-->
<!--                <h2>Asar</h2>-->
<!--                <strong>Afternoon Prayer <span>2:46 PM</span> </strong></div>-->
<!--        </li>-->
<!--        <li class="col-md-2 col-sm-4">-->
<!--            <div class="namaz">-->
<!--                <h2>Magrib</h2>-->
<!--                <strong>Sunset Prayer <span>5:07 PM</span> </strong></div>-->
<!--        </li>-->
<!--        <li class="col-md-2 col-sm-4">-->
<!--            <div class="namaz">-->
<!--                <h2>Isha</h2>-->
<!--                <strong>Evening Prayer <span>6:33 PM</span> </strong></div>-->
<!--        </li>-->
    </ul>
</div>

<!--<div style="font-size:8pt;">-->
<!--    <p style="text-align:right; font-size:8pt;">Сегодня: --><?php //echo $today; ?><!--</p><br />-->
<!--    <table cellspacing="5">-->
<!--        <tr>-->
<!---->
<!--            <td>-->
<!---->
<!--                <table cellspacing="5">-->
<!---->
<!--                    <tr><td colspan="3"><u><strong>На сегодня:</strong></u></td></tr>-->
<!--                    --><?php
//                    $key=0;
//                    foreach($namaz as $item):
//
//                        ?>
<!---->
<!--                    <tr><td>--><?php //echo $key ?><!--</td><td> - </td><td align="right">--><?php //echo $namaz_time_title[$key]; ?><!--</td><td align="right">--><?php //echo $item; ?><!--</td></tr>-->
<!--                    --><?php
//                        $key++;
//                    endforeach;?>
<!---->
<!--                </table>-->
<!---->
<!--            </td>-->

<!--            <td>-->
<!---->
<!--                <table cellspacing="5">-->
<!---->
<!--                    <tr><td colspan="3"><u><strong>На завтра:</strong></u></td></tr>-->
<!--                    --><?php
//                    $key=0;
//                    foreach($namaz_next as $item):
//                    $key++;
//                    ?>
<!---->
<!--                        <tr><td>--><?php //echo $key ?><!--</td><td> - </td><td align="right">--><?php //echo $item; ?><!--</td></tr>-->
<!--                    --><?php //endforeach;?>
<!---->
<!--                </table>-->
<!---->
<!--            </td>-->

<!--        </tr>-->
<!--    </table>-->
<!--</div>-->