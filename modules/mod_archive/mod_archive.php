                  <?php
function mod_archive($id){
    $cnf = Config::getInstance();
    $db = DB::getInstance();
    $ker = Engine::getInstance();
    include($_SERVER['DOCUMENT_ROOT'].'/include/calendar.php');
    $menuid = menuID();
    $cfg = getModuleConfig($id);

    calendar($cfg);

//    $smarty = $ker->smartyInitModule();
//    $smarty->assign('categories', $categories);
//    $smarty->assign('itms', $items);
//    $smarty->display('mod_calendar.tpl');

//    $month='';
//    $year='';
//    $months = Array("January","February","March","April","May","June","July","August","September","October","November","December");
//    $days = Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
//    define ('ADAY', (60*60*24));
//    $db=DB::getInstance();
//
//
//
//    if (isset($_GET['month']) && isset($_GET['year'])) {
//        $month = $_GET['month'];  // stating the obvious, but it makes it clear
//        $year = $_GET['year'];
//        $datearray = getdate(mktime(0,0,0,$month,1,$year)); // initialise calendar for today
//    } else {
//        $datearray = getdate(); // initialise calendar for today
//        $month = $datearray["mon"];
//        $year = $datearray["year"];
//    }
//
//    $start= mktime(0,0,0,$month,1,$year);
//    $firstdayarray = getdate($start);
//
//
//    $prev_month = $month - 1;
//    $prev_year = $year;
//    $next_month = $month + 1;
//    $next_year = $year;
//
//    if ($month==12) {
//        $prev_month = $month - 1;
//        $prev_year = $year;
//        $next_month = 1;
//        $next_year = $year + 1;
//    }
//    // start of year
//    if ($month==1) {
//        $prev_month = 12;
//        $prev_year = $year - 1;
//        $next_month = $month + 1;
//        $next_year = $year;
//    }
//    $url_next = $_SERVER["SELF"]."?month=".$next_month."&year=".$next_year;
//    $url_prev = $_SERVER["SELF"]."?month=".$prev_month."&year=".$prev_year;
//    print "<div class=\"l-block b-box-gray-border-wrap b-calendar\">";
//    print "<div class=\"b-box-gray-border\">
//        <i class=\"cor bl\"><i></i></i><i class=\"cor br\"><i></i></i>
//        <i class=\"cor tl\"><i></i></i><i class=\"cor tr\"><i></i></i>
//        <h3>Архив материалов</h3>";
//    print "<div class=\"calendar\" url=\"/football/\">";
//    print "<table width='100%' border='0' cellpadding='0' cellspacing='1'>\n";
//    print "<tr><td colspan='7' >\n";
//    print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>\n";
//    print "<td align='center'><span ><a href='".$url_prev."'><<</a></span></td>\n";
//    print "<td align='center'><span ><a href='".$_SERVER["PHP_SELF"]."?year=".$year."&month=".$month."'>".$datearray["month"]." ".$year."</a></span></td>\n";
//    print "<td align='left'><span ><a href='".$url_next."'>>></a></span></td>\n";
//    print "</tr></table>\n";
//    print "</td></tr></table>\n";
//
//    print "<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
//                <thead>
//                <tr>";
//
//           foreach($days as $day)
//    {
//        if ($day== date("D")){
//        print "  <th style='color:#fff;' align=\"center\">".$day."</th>\n";
//        }else{
//            print "  <th align=\"center\">".$day."</th>\n";
//        }
//
//    }
////                    <th class=\"first\">пн</th>
////                    <th>вт</th>
////                    <th>ср</th>
////                    <th>чт</th>
////                    <th>пт</th>
////                    <th>сб</th>
////                    <th class=\"last\">вс</th>
//    print "                </tr>
//                </thead>";
//
//    print "            <tbody>";
//    for( $count=0;$count<(6*7);$count++)
//    {
//        $dayarray = getdate($start);
//        if((($count) % 7) == 0) {
//            if($dayarray['mon'] != $datearray['mon'])
//                break;
//            print "</tr>\n<tr>\n";
//        }
//        if($count < $firstdayarray['wday'] || $dayarray['mon'] != $month) {
//            print "<td ><span></span></td>\n";
//        } else {
//
//            // if there are entries for the day make it a link and change the color
//            $d = $dayarray["mday"];
//            if (date('d')==$d) {
//                $date_url = $_SERVER["PHP_SELF"]."?year=".$dayarray["year"]."&month=".$dayarray["mon"]."&day=".$dayarray["mday"];
//                print "<td class=\"current \"><span><a href='".$date_url."'>".$dayarray["mday"]."</a></span></td>\n";
//            } else {
//                print "<td ><span><a href='".$date_url."'>".$dayarray["mday"]."</a></span></td>\n";
//            }
//            $start += ADAY;
//        }
//    }
////                <tr>
////                    <td><span></span></td>
////                    <td><span></span></td>
////                    <td><span></span></td>
////                    <td><a href=\"/football/2013-08-01.html\">1</a></td>
////                    <td><a href=\"/football/2013-08-02.html\">2</a></td>
////                    <td class=\"holiday\"><a href=\"/football/2013-08-03.html\">3</a></td>
////                    <td class=\"holiday\"><a href=\"/football/2013-08-04.html\">4</a></td></tr>
////                    <tr><td><a href=\"/football/2013-08-05.html\">5</a></td>
////                        <td><a href=\"/football/2013-08-06.html\">6</a></td>
////                        <td><a href=\"/football/2013-08-07.html\">7</a></td>
////                        <td><a href=\"/football/2013-08-08.html\">8</a></td>
////                        <td><a href=\"/football/2013-08-09.html\">9</a></td>
////                        <td class=\"holiday\"><a href=\"/football/2013-08-10.html\">10</a></td>
////                        <td class=\"holiday\"><a href=\"/football/2013-08-11.html\">11</a></td>
////                        </tr>
////                <tr><td><a href=\"/football/2013-08-12.html\">12</a></td><td><a href=\"/football/2013-08-13.html\">13</a></td>
////                    <td><a href=\"/football/2013-08-14.html\">14</a></td><td><a href=\"/football/2013-08-15.html\">15</a></td>
////                    <td><a href=\"/football/2013-08-16.html\">16</a></td><td class=\"holiday\"><a href=\"/football/2013-08-17.html\">17</a></td>
////                    <td class=\"holiday\"><a href=\"/football/2013-08-18.html\">18</a></td></tr><tr><td><a href=\"/football/2013-08-19.html\">19</a></td>
////                    <td><a href=\"/football/2013-08-20.html\">20</a></td><td><a href=\"/football/2013-08-21.html\">21</a></td>
////                    <td><a href=\"/football/2013-08-22.html\">22</a></td><td><a href=\"/football/2013-08-23.html\">23</a></td>
////                    <td class=\"holiday\"><a href=\"/football/2013-08-24.html\">24</a></td><td class=\"holiday\"><a href=\"/football/2013-08-25.html\">25</a></td></tr>
////
////                <tr><td><a href=\"/football/2013-08-26.html\">26</a></td><td class=\"current \"><a href=\"/football/2013-08-27.html\">27</a></td><td><span>28</span></td>
////                    <td><span>29</span></td><td><span>30</span></td><td><span>31</span></td><td><span></span></td></tr>
//
//               print " </tbody></table>";
//
////    foreach($days as $day)
////    {
////        print "  <th >".$day.\"</th>\n";
////
////    }
////    print "</tr>\n";
//
//
//    // draw days
////    for( $count=0;$count<(6*7);$count++)
////    {
////        $dayarray = getdate($start);
////        if((($count) % 7) == 0) {
////            if($dayarray['mon'] != $datearray['mon'])
////                break;
////            print "</tr>\n<tr>\n";
////        }
////
////        if($count < $firstdayarray['wday'] || $dayarray['mon'] != $month) {
////            print "<th > </th>\n";
////        } else {
////
////            // if there are entries for the day make it a link and change the color
////            $d = $dayarray["mday"];
////            if ($aBlogs[$d]["cBody"]!="") {
////                $date_url = $_SERVER["PHP_SELF"]."?year=".$dayarray["year"]."&month=".$dayarray["mon"]."&day=".$dayarray["mday"];
////                print "<th align='center'><span class='small'><a href='".$date_url."'>".$dayarray[mday]."</a></span></th>\n";
////            } else {
////                print "<th align='center' ><span class='small'>".$dayarray[mday]."</span></th>\n";
////            }
////            $start += ADAY;
////        }
////    }
//    // end the calendar table
//
//    print "     </div>
//    </div>
//    </div>
//";

return true;
}
?>                