<?php
require_once("../../init.php");
include($_SERVER['DOCUMENT_ROOT']."/admin/components/calendar/class_calendar.php");

Registry::set('Calendar',new Calendar());
Registry::get("Calendar")->weekDayNameLength = "short";

?>
<?php Registry::get("Calendar")->renderCalendar('small');?>