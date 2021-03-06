$(function() {

// ========================================================================== //
//перетаскиваемые элементы

 $( ".uidrag" ).draggable({});
//умолчания см. http://api.jqueryui.com/draggable/

// ========================================================================== //
//элементы контейнеры для перетаскиваемых элементов

//$( ".uidrop" ).droppable({
//умолчания см. http://api.jqueryui.com/droppable/

// ========================================================================== //
//авторесайз элементы

//$( ".autores" ).resizable({});
//умолчания см. http://api.jqueryui.com/resizable/

// ========================================================================== //
//выборки

//$( ".uiselect" ).selectable({
//умолчания см. http://api.jqueryui.com/selectable/

// ========================================================================== //
//сортируемые элементы

//$(".uisort").sortable();
//умолчания см. http://api.jqueryui.com/sortable/

//$( ".uisort" ).disableSelection();

//ВИДЖЕТЫ:
// ========================================================================== //
//Аккордеон

 $( ".uiacc" ).accordion({});
//умолчания см. http://api.jqueryui.com/accordion/

// ========================================================================== //
//Автоподстановка значений

//$( ".autocomp" ).autocomplete({});
//умолчания см. http://api.jqueryui.com/autocomplete/

// ========================================================================== //
//Кнопки, тулбары и т.п.

 $( ".uibtn" ).button({});
//умолчания см. http://api.jqueryui.com/button/

//доступны те же опции
 $( ".uibtnset" ).buttonset({});

// ========================================================================== //
//Установка дат

 $( "#pubdate, #enddate, #answerdate,#create_at,#enddate" ).datetimepicker({
  dateFormat: "yy-mm-dd",
  timeFormat: 'HH:mm:ss',
  showOn: "both",
  changeMonth: true,
  changeYear: true,
  buttonImage: "/admin/images/icons/calendar.png",
  buttonImageOnly: true
 });
//$( "#enddate" ).datepicker("option", "dateFormat", "yy-mm-dd");
// ========================================================================== //
//Диалоги, модалки

 $( ".uidialog" ).dialog({});
//умолчания см. http://api.jqueryui.com/dialog/

// ========================================================================== //
//Меню

 $( ".uimenu" ).menu({});
//умолчания см. http://api.jqueryui.com/menu/

// ========================================================================== //
//Прогрессбар

 $( ".uipbar" ).progressbar({});
//умолчания см. http://api.jqueryui.com/progressbar/

// ========================================================================== //
//Слайдеры

 $( ".uisl" ).slider({});
//умолчания см. http://api.jqueryui.com/slider/

// ========================================================================== //
//Спиннеры

 $( '.uispin').spinner();
//умолчания см. http://api.jqueryui.com/spinner/

// ========================================================================== //
//Табы

 $( ".uitabs" ).tabs({});
//умолчания см. http://api.jqueryui.com/tabs/

// ========================================================================== //
//Тултипы

 $( '.uittip' ).tooltip({});
//умолчания см. http://api.jqueryui.com/tooltip/


//подхватываем от lightbox
//$( '.lightbox-enabled' ).colorbox({ transition: "none", width: "90%", height: "90%"});

});