
function getMonthCalendar(period, module_id){
    $(document).ready(function(){
       $.post('/modules/mod_content_calendar/ajax/get_calendar.php',{'module_id':module_id, 'period': period}, function(data){
             $('#cal_box').html(data);
          });
    });
}