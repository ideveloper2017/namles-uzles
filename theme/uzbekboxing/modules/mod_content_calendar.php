<!--<link type="text/css" href="/modules/mod_content_calendar/css/calendar.css" rel="stylesheet">-->
<script type="text/javascript" src="/modules/mod_content_calendar/js/calendar.js"></script>
<div id="cal_box">
    <table id="wp-calendar">
        <caption>
            <?php echo $calendar['month']['title'] . ' ' . $calendar['yahr']; ?>
        </caption>
        <thead>
        <tr>
        <?php
        $n = 0;
        foreach ($calendar['week_days'] as $week_day) {
            ?>
            <th class="active"  >

                <span class="nameday   <?php if ($n == 5 || $n == 6) { ?>
                    holyday
                <?php } else { ?>
                    workday
                <?php } ?>">
                    <?php echo $week_day; ?>
                </span>

            </th>
            <?php
            $n++;
        } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $y = 0;
        foreach ($calendar['days'] as $row) {
            ?>
            <tr>
                <?php
                $x = 0;
                foreach ($row as $day) {
                    ?>
                    <td <?php if ($x == 5 || $x == 6) { ?> style="color: #dc6039"  <?php } ?>
                        class="<?php if ($day['date'] == $date) { ?>nextda <?php } else { if ($day['cou'] > '0') { ?>today <?php } else { ?><?php } }?>">

                        <?php if ($day['cou'] > '0') { ?>
                        <span>
                        <a href="<?php echo $day['url'] ?>" style="font-size:12px;"
                           title="<?php echo $day['cou'] ?> <?php echo $day['titl'] ?>">

                            <?php } ?>
                            <?php if ($day['date'] == $date) {?>
                                <span class="gr1">
                <?php echo $day['num'] ?>
             </span>
                            <?php } else { ?>
                                <?php echo $day['num'] ?>
                            <?php }?>
                            <?php if ($day['cou'] > '0') { ?>
                        </a>
                            </span>
                    <?php } ?>

                    </td>
                    <?php
                    $x++;
                } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>