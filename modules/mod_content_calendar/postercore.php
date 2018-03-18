<?php

function makeCal($start_date, $end_date, $separator = '-')
{
    $start_date_list = explode($separator, $start_date);
    $end_date_list = explode($separator, $end_date);

    if (!checkdate($start_date_list[1], $start_date_list[0], $start_date_list[2])) {
        return false;
    }
    if (!checkdate($end_date_list[1], $end_date_list[0], $end_date_list[2])) {
        return false;
    }

    $start_date_jd = GregorianToJD($start_date_list[1], $start_date_list[0], $start_date_list[2]);
    $end_date_jd = GregorianToJD($end_date_list[1], $end_date_list[0], $end_date_list[2]);

    $days = array();
    for ($i = $start_date_jd; $i <= $end_date_jd; $i++) {
        $day = JDToGregorian($i);
        $day_list = explode('/', $day);
        //прибавляем 0 перед днем и месяцем, если меньше 10
        if ($day_list[0] < 10) {
            $day_list[0] = '0' . $day_list[0];
        }
        if ($day_list[1] < 10) {
            $day_list[1] = '0' . $day_list[1];
        }
        $day_str = "{$day_list[1]}-{$day_list[0]}-{$day_list[2]}";
        $days[] = $day_str;
    }

    return $days;
}


function getCalendar($cid, $date = '', $start_date = '')
{
    //проверяем дату

    if (!$date && !$start_date) {
        $date = date('d-m-Y');
    }
    if ($start_date) {
        $date = date('d-m-Y', strtotime($start_date));
    }
    $date_timestap = strtotime($date);
    $date_arr = array();
    $date_arr = explode('-', $date);
    if (checkdate($date_arr[1], $date_arr[0], $date_arr[2]) != 1) {
        return false;
    }

    $calendar = array();
   
    //год
    $calendar['yahr'] = $date_arr[2];
    //название месяца
    $inCore = Registry::get("Core");
    $calendar['month']['title'] = getRusDate(strftime('%B', strtotime($date)));
    $calendar['month']['num'] = $date_arr[1];
    //дни недели
    $calendar['week_days'] = array(0 => 'Пн',
        1 => 'Вт',
        2 => 'Ср',
        3 => 'Чт',
        4 => 'Пт',
        5 => 'Сб',
        6 => 'Вс');
    //составляем календарь
    $calendar['days'] = array();

    // Вычисляем число дней в текущем месяце
    $dayofmonth = date('t', $date_timestap);
    // Счётчик для дней месяца
    $day_count = 1;

    // 1. Первая неделя

    $num = 0;
    for ($i = 0; $i < 7; $i++) {
        // Вычисляем номер дня недели для числа
        $dayofweek = date('w', mktime(0, 0, 0, date('m', $date_timestap), $day_count, date('Y', $date_timestap)));
        // Приводим к числа к формату 1 - понедельник, ..., 6 - суббота
        $dayofweek = $dayofweek - 1;
        if ($dayofweek == -1) $dayofweek = 6;

        if ($dayofweek == $i) {
            // Если дни недели совпадают,
            // заполняем массив $week
            // числами месяца
            $week[$num][$i]['num'] = $day_count;
            $week[$num][$i]['date'] = strtotime("{$day_count}-{$date_arr[1]}-{$date_arr[2]}");
            $day_count++;
        } else {
            $week[$num][$i] = "";
        }
    }

    // 2. Последующие недели месяца
    while (true) {
        $num++;
        for ($i = 0; $i < 7; $i++) {
            $week[$num][$i]['num'] = $day_count;
            $week[$num][$i]['date'] = strtotime("{$day_count}-{$date_arr[1]}-{$date_arr[2]}");
            $day_count++;
            // Если достигли конца месяца - выходим
            // из цикла
            if ($day_count > $dayofmonth) break;
        }
        // Если достигли конца месяца - выходим
        // из цикла
        if ($day_count > $dayofmonth) break;
    }


    $calendar['days'] = $week;
    //для ссылок следующего и предыдущего месяца
    $next_month = $date_arr[1] + 1;
    $next_yahr = $date_arr[2];
    if ($next_month > 12) {
        $next_yahr += 1;
        $next_month = 1;
    }
    $last_month = $date_arr[1] - 1;
    $last_yahr = $date_arr[2];
    if ($last_month < 1) {
        $last_yahr -= 1;
        $last_month = 12;
    }
    for ($i = 28; $i <= 31; $i++) {
        if (checkdate($last_month, $i, $last_yahr) == 1) $last_month_end = $i;
        if (checkdate($next_month, $i, $next_yahr) == 1) $next_month_end = $i;
    }
    $calendar['last_month_date'] = "1-{$last_month}-{$last_yahr}_{$last_month_end}-{$last_month}-{$last_yahr}";
    $calendar['next_month_date'] = "1-{$next_month}-{$next_yahr}_{$next_month_end}-{$next_month}-{$next_yahr}";
    $last_ya = $last_yahr - 1;
    $last_ya1 = $last_yahr - 2;

    $next_ya = $next_yahr + 1;
    $last_mo = $last_month + 1;
    $next_mo = $next_month - 1;
    $calendar['last_yahr_date1'] = "1-{$last_mo}-{$last_ya1}_{$last_month_end}-{$last_mo}-{$last_ya1}";

    $calendar['last_yahr_date'] = "1-{$last_mo}-{$last_ya}_{$last_month_end}-{$last_mo}-{$last_ya}";
    $calendar['next_yahr_date'] = "1-{$next_mo}-{$next_ya}_{$next_month_end}-{$next_mo}-{$next_ya}";
    $ya1 = $date_arr[2] - 1;
    $ya2 = $date_arr[2] - 2;
    $ya3 = $date_arr[2] + 1;
    $calendar['ya1'] = $ya1;
    $calendar['ya2'] = $ya2;
    $calendar['ya3'] = $ya3;
    $mo = $date_arr[1];
    $calendar['da'] = $date_arr[2];
    $ya = $date_arr[2];
    $calendar['daty'] = "{$ya}{$mo}";

    foreach ($calendar['days'] as $key => &$row) {
        foreach ($row as $x => &$day) {

            $daday = @$day['num'];
            if (@$day['num'] < 10) {
                $daday = '0' . @$day['num'];
            }

            $day['url'] = "/arhive/{$date_arr[2]}/{$date_arr[1]}/{$daday}";

            $todyd = "{$day['num']}-{$date_arr[1]}-{$date_arr[2]}";
            $con = getcontent($todyd, $cid);
            $day['cou'] = $con->num;
            $day['titl'] = 'статей';
            if ($day['cou'] == 2) {
                $day['titl'] = 'статьи';
            }
            if ($day['cou'] == 3) {
                $day['titl'] = 'статьи';
            }
            if ($day['cou'] == 4) {
                $day['titl'] = 'статьи';
            }

            if ($day['cou'] == 1) {
                $day['titl'] = 'статья';
            }


        }
    }
    return $calendar;
}

//устанавливает дату на русском . Если нет русской локали.
function getRusDate($datestr)
{
    $datestr = str_replace('January', 'Январь', $datestr);
    $datestr = str_replace('February', 'Февраль', $datestr);
    $datestr = str_replace('March', 'Март', $datestr);
    $datestr = str_replace('April', 'Апрель', $datestr);
    $datestr = str_replace('May', 'Май', $datestr);
    $datestr = str_replace('June', 'Июнь', $datestr);
    $datestr = str_replace('July', 'Июль', $datestr);
    $datestr = str_replace('August', 'Август', $datestr);
    $datestr = str_replace('September', 'Сентябрь', $datestr);
    $datestr = str_replace('October', 'Октябрь', $datestr);
    $datestr = str_replace('November', 'Ноябрь', $datestr);
    $datestr = str_replace('December', 'Декабрь', $datestr);

    //заменяем дни недели
    $datestr = str_replace('Mon', 'Пн', $datestr);
    $datestr = str_replace('Tue', 'Вт', $datestr);
    $datestr = str_replace('Wed', 'Ср', $datestr);
    $datestr = str_replace('Thu', 'Чтв', $datestr);
    $datestr = str_replace('Fri', 'Пт', $datestr);
    $datestr = str_replace('Sat', 'Сб', $datestr);
    $datestr = str_replace('Sun', 'Вск', $datestr);

    // Замена чисел 01 02 на 1 2
    $day_int = array(
        '01', '02', '03',
        '04', '05', '06',
        '07', '08', '09'

    );
    $day_norm = array(
        '1', '2', '3',
        '4', '5', '6',
        '7', '8', '9'

    );

    $datestr = str_replace($day_int, $day_norm, $datestr);

    return $datestr;
}

function getcontent($dat, $catid)
{
    $inCore = Registry::get("Core");
    $inDB = Registry::get("DataBase");
    $dat = date('d-m-Y', strtotime($dat));

    $rootcat = $inDB->getFieldsById('*','categories', "id='{$catid}'");
    if (!$rootcat) {
        return false;
    }
    $catsql = "AND (cat.parent_id<>0)";
    $sql = "SELECT
            COUNT( a.id ) num
            FROM content a
            LEFT JOIN categories cat ON cat.id = a.category_id
    WHERE DATE_FORMAT( a.created_at, '%d-%m-%Y' )= '$dat' AND a.active = 1 " . $catsql;

    $result = $inDB->query($sql);
    $con = $inDB->fetch($result);
    return $con;


}

?>