<?php
require_once("../../../init.php");
include($_SERVER['DOCUMENT_ROOT']."/admin/components/calendar/class_calendar.php");
Registry::set('Calendar',new Calendar());
$day = isset($_POST['d']) ? sanitize($_POST['d'],2) : 0;
$month = isset($_POST['m']) ? sanitize($_POST['m'],2) : 0;
$year = isset($_POST['y']) ? sanitize($_POST['y'],4) : 0;
$eventrow = Registry::get("Calendar")->getAllEvents($year, $month, $day)
?>
<?php

if (isset($_POST['loadEvent'])):
    $html = '<div id="event-wrap">';
    if ($eventrow):
        foreach($eventrow as $row):
            $html .= '
		  <div class="wojo message">
			<div class="content">
			  <div class="header"> ' . $row->title . ' </div>
			  <div class="wojo breadcrumb"><i class="icon time"></i>

				<div class="divider"></div>
				<div class="section">' . $row->created_at . '</div>
				<div class="divider"></div>';

            if ($row->introtext):
                $html .= '<div class="divider">  </div>
				<div class="section">' . $row->introtext . '</div>';
            endif;
            $html .= ' </div>
			</div>
		  </div>';
//            $html .= cleanOut($row->{'body'});
//            $html .= '<div class="wojo divider"></div>';
//            $html .= '<h4 class="wojo header">' . Lang::$word->_MOD_EM_CONTACT . '</h4>';
//            $html .= '<div class="wojo celled list">';
//            $html .= '<div class="item"><i class="icon user"></i> ' . $row->contact_person . '</div>';
//            $html .= '<div class="item"><i class="icon mail"></i> ' . $row->contact_email . '</div>';
//            $html .= '<div class="item"><i class="icon phone"></i> ' . $row->contact_phone . '</div>';
//            $html .= '</div>';
        endforeach;
    else:
        $html .= Core::msgSingleAlert(Lang::$word->_MOD_EM_EVENT_ERR);
    endif;
    $html .= '</div>';
    print $html;
endif;

?>