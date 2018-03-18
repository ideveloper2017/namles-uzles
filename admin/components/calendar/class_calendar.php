<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 02.05.2016
 * Time: 9:18
 */

class Calendar{

    public $weekDayNameLength;
    public $monthNameLength;
    private $arrWeekDays = array();
    private $arrMonths = array();
    private $pars = array();
    private $today = array();
    private $prevYear = array();
    private $nextYear = array();
    private $prevMonth = array();
    private $nextMonth = array();
    public $eventMonth;
    public $daterange;

    private static $db;

    function __construct()
    {
        self::$db = Registry::get("DataBase");
        $this->weekStartedDay = 1;
        $this->weekDayNameLength = "long";
        $this->monthNameLength = "long";
        $this->init();
        $this->eventMonth = $this->getCalDataMonth();
    }

    private function init()
    {
        $year = (isset($_POST['year']) && $this->checkYear($_POST['year'])) ? intval($_POST['year']) : date("Y");
        $month = (isset($_POST['month']) && $this->checkMonth($_POST['month'])) ? intval($_POST['month']) : date("m");
        $day = (isset($_POST['day']) && $this->checkDay($_POST['day'])) ? intval($_POST['day']) : date("d");
        $ldim = $this->calcDays($month, $day);

        if($day > $ldim) {
            $day = $ldim;
        }

        $cdate = getdate(mktime(0, 0, 0, $month, $day, $year));

        $this->pars["year"] = $cdate['year'];
        $this->pars["month"] = $this->toDecimal($cdate['mon']);
        $this->pars["nmonth"] = $cdate['mon'];
        $this->pars["month_full_name"] = $cdate['month'];
        $this->pars["day"] = $day;
        $this->today = getdate();

        $this->prevYear = getdate(mktime(0, 0, 0, $this->pars['month'], $this->pars["day"], $this->pars['year'] - 1));
        $this->nextYear = getdate(mktime(0, 0, 0, $this->pars['month'], $this->pars["day"], $this->pars['year'] + 1));
        $this->prevMonth = getdate(mktime(0, 0, 0, $this->pars['month'] - 1, $this->calcDays($this->pars['month']-1,$this->pars["day"]), $this->pars['year']));
        $this->nextMonth = getdate(mktime(0, 0, 0, $this->pars['month'] + 1, $this->calcDays($this->pars['month']+1,$this->pars["day"]), $this->pars['year']));

        $this->arrWeekDays[0] = array("mini" => Lang::$word->_SU, "short" => "Sun", "long" => Lang::$word->_SUNDAY);
        $this->arrWeekDays[1] = array("mini" => Lang::$word->_MO, "short" => "Mon", "long" => Lang::$word->_MONDAY);
        $this->arrWeekDays[2] = array("mini" => Lang::$word->_TU, "short" => "Tue", "long" => Lang::$word->_TUESDAY);
        $this->arrWeekDays[3] = array("mini" => Lang::$word->_WE, "short" => "Wed", "long" => Lang::$word->_WEDNESDAY);
        $this->arrWeekDays[4] = array("mini" => Lang::$word->_TH, "short" => "Thu", "long" => Lang::$word->_THURSDAY);
        $this->arrWeekDays[5] = array("mini" => Lang::$word->_FR, "short" => "Fri", "long" => Lang::$word->_FRIDAY);
        $this->arrWeekDays[6] = array("mini" => Lang::$word->_SA, "short" => "Sat", "long" => Lang::$word->_SATURDAY);

        $this->arrMonths[1] = array("short" => "Jan", "long" => "Январ");
        $this->arrMonths[2] = array("short" => "Feb", "long" => "Февраль");
        $this->arrMonths[3] = array("short" => "Mar", "long" => "Март");
        $this->arrMonths[4] = array("short" => "Apr", "long" => "Апрел");
        $this->arrMonths[5] = array("short" => "May", "long" => "Май");
        $this->arrMonths[6] = array("short" => Lang::$word->_JU_, "long" => "Июнь");
        $this->arrMonths[7] = array("short" => Lang::$word->_JU_, "long" => "Июль");
        $this->arrMonths[8] = array("short" => Lang::$word->_AU_, "long" => "Август");
        $this->arrMonths[9] = array("short" => Lang::$word->_SE_, "long" => "Сентябрь");
        $this->arrMonths[10] = array("short" => Lang::$word->_OC_, "long" => "Октябрь");
        $this->arrMonths[11] = array("short" => Lang::$word->_NO_, "long" => "Ноябрь");
        $this->arrMonths[12] = array("short" => Lang::$word->_DE_, "long" => "Декабрь");
    }
    public function toDecimal($number)
    {
        return (($number < 10) ? "0" : "") . $number;
    }
    private function calcDays($month, $day)
    {
        if ($day < 29) {
            return $day;
        } elseif ($day == 29) {
            return ((int)$month == 2) ? 28 : 29;
        } elseif ($day == 30) {
            return ((int)$month != 2) ? 30 : 28;
        } elseif ($day == 31) {
            return ((int)$month == 2 ? 28 : ((int)$month == 4 || (int)$month == 6 || (int)$month == 9 || (int)$month == 11 ? 30 : 31));
        } else {
            return 30;
        }

    }

    private function getCalDataMonth()
    {

        $sql = "SELECT e.*, e.id as event_id, ed.item_id as eid, DAY(e.created_at) as sday, title as etitle, DAY(e.end_at) as eday"
            . "\n FROM content as e"
            . "\n LEFT JOIN categories_bind as ed ON ed.item_id = e.id"
            . "\n WHERE YEAR(e.created_at) = " . $this->pars['year']
            . "\n AND MONTH(e.created_at) = " . $this->pars['month']
            . "\n AND active = 1"
            . "\n";

        $row = self::$db->fetch_all($sql);

        return ($row) ? $row : 0;

    }


    public function getAllEvents($year, $month, $day)
    {

        $sql = "SELECT e.*, e.id as event_id, ed.item_id as eid, DAY(e.created_at) as sday, title as etitle, DAY(e.end_at) as eda"
            . "\n FROM content as e"
            . "\n LEFT JOIN categories_bind as ed ON ed.item_id = e.id"
            . "\n WHERE YEAR(e.created_at)  = " . (int)$year
            . "\n AND MONTH(e.created_at) = " . (int)$month
            . "\n AND DAY(e.created_at) = " . (int)$day
            . "\n AND active = 1"
            . "\n";

        $row = self::$db->fetch_all($sql);

        return ($row) ? $row : 0;

    }

    public function renderCalendar($type = 'large')
    {

        ($type == 'large') ? $this->drawMonth() : $this->drawMonthSmall();
    }


    private function drawMonth()
    {

        $is_day = 0;
        $first_day = getdate(mktime(0, 0, 0, $this->pars['month'], 1, $this->pars['year']));
        $last_day = getdate(mktime(0, 0, 0, $this->pars['month'] + 1, 0, $this->pars['year']));

        echo "<div class=\"calnav clearfix\">";
        echo "<h3><span class=\"month\">" . $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength] . "</span><span class=\"year\">" . $this->pars['year'] . "</span></h3>";
        echo "<nav>";
        echo "<a data-id=\"" . $this->toDecimal($this->prevMonth['mon']) . ":" . $this->prevMonth['year'] . "\" class=\"icon-arrow-left6\"><i class=\"icon-arrow-right5\"></i></a>";
        echo "<a data-id=\"" . $this->toDecimal($this->nextMonth['mon']) . ":" . $this->nextMonth['year'] . "\" class=\"icon-arrow-right5\"><i class=\"icon-arrow-left6\"></i></a>";
        echo "</nav>";
        echo "</div>";

        echo "<div class=\"calheader clearfix\">";
        for ($w = $this->weekStartedDay - 1; $w < $this->weekStartedDay + 6; $w++) {
            echo "<div>" . $this->arrWeekDays[($w % 7)][$this->weekDayNameLength] . "</div>";
        }
        echo "</div>";
        echo "<div class=\"calbody clearfix\">";

        if ($first_day['wday'] == 0) {
            $first_day['wday'] = 7;
        }

        $max_days = $first_day['wday'] - ($this->weekStartedDay - 1);

        if ($max_days < 7) {
            echo "<section class=\"section clearfix\">";
            for ($j = 1; $j <= $max_days; $j++) {
                echo "<div class=\"empty\">&nbsp;</div>";
            }
            $is_day = 0;
            for ($k = $max_days + 1; $k <= 7; $k++) {
                $is_day++;
                $class = '';
                $tclass = '';
                $align = '';
                if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
                    $tclass = " today";
                    $display = $is_day;
                }
                $res = '';
                if ($this->checkEventsMonths($is_day)) {
                    $data = '';
                    foreach ($this->eventMonth as $row) {
                        if ($row->sday == $is_day) {
                            $res .= "<div><a data-title=\"" . Core::dodate("short_date", $row->created_at) . "\" data-id=\"" . $row->event_id . "\" style=\"color:" . $row->color . "\" class=\"loadevent\">" . truncate($row->etitle,15) . "</a></div>";
                        }
                    }
                    $display = $data . $is_day;
                    $class = " content";
                } else {
                    $display = $is_day;
                }
                if($this->weekStartedDay == 2) {
                    if($k == 7) {
                        $n = 0;
                    } else {
                        $n = $k;
                    }

                } else {
                    $n = $k-1;
                }

                $curweek = $this->arrWeekDays[$n][$this->weekDayNameLength];
                echo "<div class=\"caldata" . $class . $tclass . "\"><span class=\"date\">" . $display . "</span><span class=\"weekday\">" . $curweek . "</span>$res</div>";


            }
            echo "</section>";
        }

        $fullWeeks = floor(($last_day['mday'] - $is_day) / 7);

        for ($i = 0; $i < $fullWeeks; $i++) {
            echo "<section class=\"section clearfix\">";
            for ($j = 0; $j < 7; $j++) {
                $is_day++;
                $class = '';
                $tclass = '';
                $align = '';
                if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
                    $tclass = " today";
                    $display = $is_day;
                }
                $res = '';
                if ($this->checkEventsMonths($is_day)) {
                    $data = '';
                    foreach ($this->eventMonth as $row) {
                        if ($row->sday == $is_day) {
                            $res .= "<div><a data-title=\"" . Core::dodate("short_date", $row->date_start) . "\" data-id=\"" . $row->event_id . "\" style=\"color:" . $row->color . "\" class=\"loadevent\">" . truncate($row->etitle,15) . "</a></div>";
                        }
                    }
                    $display = $data . $is_day;
                    $class = " content";
                } else {
                    $display = $is_day;
                }
                if($this->weekStartedDay == 2) {
                    if($j == 6) {
                        $n = 0;
                    } else {
                        $n = $j+1;
                    }

                } else {
                    $n = $j;
                }

                $curweek = $this->arrWeekDays[($n)][$this->weekDayNameLength];
                echo "<div class=\"caldata" . $class . $tclass . "\"><span class=\"date\">" . $display . "</span><span class=\"weekday\">" . $curweek . "</span>$res</div>";
            }
            echo "</section>";
        }


        if ($is_day < $last_day['mday']) {
            echo "<section class=\"section clearfix\">";
            for ($i = 0; $i < 7; $i++) {
                $is_day++;
                $class = '';
                $tclass = '';
                $align = '';
                if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
                    $tclass = " today";
                    $display = $is_day;
                }

                $res = '';
                if ($this->checkEventsMonths($is_day)) {
                    $data = '';
                    foreach ($this->eventMonth as $row) {
                        if ($row->sday == $is_day) {
                            $res .= "<div><a data-title=\"" . Core::dodate("short_date", $row->created_at) . "\" data-id=\"" . $row->event_id . "\" class=\"loadevent\" style=\"color:" . $row->color . "\">" . truncate($row->etitle,15) . "</a></div>";
                        }

                    }
                    $display = $data . $is_day;
                    $class = " content";
                } else {
                    $display = $is_day;
                }
                if($this->weekStartedDay == 2) {
                    if($i == 6) {
                        $n = 0;
                    } else {
                        $n = $i+1;
                    }

                } else {
                    $n = $i;
                }
                $curweek = $this->arrWeekDays[$n][$this->weekDayNameLength];
                echo ($is_day <= $last_day['mday']) ? "<div class=\"caldata" . $class . $tclass . "\"><span class=\"date\">" . $display . "</span><span class=\"weekday\">$curweek</span>$res</div>" : "<div class=\"empty\">&nbsp;</div>";
            }
            echo "</section>";
        }

        echo "</div>";

    }


    private function drawMonthSmall()
    {

        $is_day = 0;
        $first_day = getdate(mktime(0, 0, 0, $this->pars['month'], 1, $this->pars['year']));
        $last_day = getdate(mktime(0, 0, 0, $this->pars['month'] + 1, 0, $this->pars['year']));

        echo "<table class=\"small-calendar\">";
        echo "<thead>";
        echo "<tr>";
        echo "<td><a data-m=\"" . $this->toDecimal($this->prevMonth['mon']) . "\" data-y=\"" . $this->prevMonth['year'] . "\" class=\"changedate prev\"></a></td>";
        echo "<td colspan=\"5\"><span class=\"year\">" . $this->pars['year'] . "</span><span class=\"month\">" . $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength] . "</span></td>";
        echo "<td><a data-m=\"" . $this->toDecimal($this->nextMonth['mon']) . "\" data-y=\"" . $this->nextMonth['year'] . "\" class=\"changedate next\"></a></td>";
        echo "</tr>";
        echo "<tr>";
        for ($i = $this->weekStartedDay - 1; $i < $this->weekStartedDay + 6; $i++) {
            echo "<th>" . $this->arrWeekDays[($i % 7)][$this->weekDayNameLength] . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        if ($first_day['wday'] == 0) {
            $first_day['wday'] = 7;
        }
        $max_days = $first_day['wday'] - ($this->weekStartedDay - 1);
        if ($max_days < 7) {
            echo "<tr>";
            for ($i = 1; $i <= $max_days; $i++) {
                echo "<td class=\"empty\">&nbsp;</td>";
            }
            $is_day = 0;
            for ($i = $max_days + 1; $i <= 7; $i++) {
                $is_day++;
                $class = '';
                $tclass = '';
                $data = '';
                if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
                    $tclass = " today";
                    $display = $is_day;
                }

                if ($this->checkEventsMonths($is_day)) {
                    $datamonth = $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength];
                    $m = $this->pars["month"];
                    $datayear = $this->pars['year'];
                    $fdate = $datayear . '-' .$m . '-' . $is_day;
                    $display = $data . "<a class=\"view-events\" data-y=\"$datayear\" data-m=\"$m\" data-d=\"$is_day\" data-title=\"" . Core::doDate("short_date", $fdate) . "\">" . $is_day . "</a>";
                    $class = " events";
                } else {
                    $display = $is_day;
                }

                echo "<td class=\"caldata" . $class . $tclass . "\"><span>" . $display . "</span></td>";
            }
            echo "</tr>";
        }

        $fullWeeks = floor(($last_day['mday'] - $is_day) / 7);

        for ($i = 0; $i < $fullWeeks; $i++) {
            echo "<tr>";
            for ($j = 0; $j < 7; $j++) {
                $is_day++;
                $class = '';
                $tclass = '';
                $data = '';
                if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
                    $tclass = " today";
                    $display = $is_day;
                }

                if ($this->checkEventsMonths($is_day)) {
                    $datamonth = $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength];
                    $m = $this->pars["month"];
                    $datayear = $this->pars['year'];
                    $fdate = $datayear . '-' .$m . '-' . $is_day;;
                    $display = $data . "<a class=\"view-events\" data-y=\"$datayear\" data-m=\"$m\" data-d=\"$is_day\" data-title=\"" . Core::doDate("short_date", $fdate) . "\">" . $is_day . "</a>";
                    $class = " events";
                } else {
                    $display = $is_day;
                }

                echo "<td class=\"caldata" . $class . $tclass . "\"><span>" . $display . "</span></td>";
            }
            echo "</tr>";
        }

        if ($is_day < $last_day['mday']) {
            echo "<tr>";
            for ($i = 0; $i < 7; $i++) {
                $is_day++;
                $class = '';
                $tclass = '';
                $align = '';
                $data = '';

                if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
                    $tclass = " today";
                    $display = $is_day;
                }

                if ($this->checkEventsMonths($is_day)) {
                    $datamonth = $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength];
                    $m = $this->pars["month"];
                    $datayear = $this->pars['year'];
                    $fdate = $datayear . '-' .$m . '-' . $is_day;
                    $display = $data . "<a class=\"view-events\" data-y=\"$datayear\" data-m=\"$m\" data-d=\"$is_day\" data-title=\"" . Core::doDate("short_date", $fdate) . "\">" . $is_day . "</a>";
                    $class = " events";
                } else {
                    $display = $is_day;
                }
                echo ($is_day <= $last_day['mday']) ? "<td class=\"caldata" . $class . $tclass . "\"><span>" . $display . "</span></td>" : "<td class=\"empty\">&nbsp;</td>";

            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

    }


    private function checkEventsMonths($day)
    {
        if ($this->eventMonth) {
            foreach ($this->eventMonth as $v) {
                if ($day == $v->sday) {
                    return true;
                }
            }

            return false;
        }
    }

    private function checkYear($year = "")
    {
        return (strlen($year) == 4 or ctype_digit($year)) ? true : false;
    }
    private function checkMonth($month = "")
    {
        return ((strlen($month) == 2) or ctype_digit($month) or ($month < 12)) ? true : false;
    }
    private function checkDay($day = "")
    {
        return ((strlen($day) == 2) or ctype_digit($day) or ($day < 31)) ? true : false;
    }
}