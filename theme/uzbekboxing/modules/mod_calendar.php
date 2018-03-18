<script type="text/javascript" src="/modules/mod_calendar/js/calendar.js" ></script>
<link rel="stylesheet" href="modules/mod_calendar/css/style.css" type="text/css" media="screen, projection"/>
<div class="head_calendar">
    <div  id="cal_box">
        <table class="head_cal_box">
            <tr>
                <!-- название месяца и стрелки перехода-->
                <td  align="center" >
                    {if $calendar.yahr==$tody}
                    <a href="#" onclick="getMonthCalendar('{$calendar.last_yahr_date1}', '{$module_id}'); return false;" class="yahr_ya"><b class="calendar__ya2"> {$calendar.ya2}</b></a>
                    {/if}
                    <a href="#" onclick="getMonthCalendar('{$calendar.last_yahr_date}', '{$module_id}'); return false;" class="yahr_date"><b class="calendar__ya1"> {$calendar.ya1}</b></a>


                    <a href="#" onclick="getMonthCalendar('{$calendar.last_month_date}', '{$module_id}'); return false;" title="{$LANG.LAST_MONTH}" style=" text-decoration:none; "><b>  <  </b></a>
                    <c class="month_yahr"> {$calendar.yahr}   {$calendar.month.title}</c>

                    {foreach key=y item=row from=$calendar.days}
                    {foreach key=x item=day from=$row}
                    {if $day.num}
                    {if $day.date <=$date}
                    <a class="day_date_date" href="#" onclick="getEvents('{$day.date}', '{$module_id}'); return false;">
                        {else}
                        <b class="day_num">
                            {/if}
                            {$day.num}
                            {if $day.date <=$date}

                    </a>{else}</b>
                    {/if}
                    {/if}
                    {/foreach}
                    {/foreach}

                    {if $calendar.daty<$today}
                    <a href="#" onclick="getMonthCalendar('{$calendar.next_month_date}', '{$module_id}'); return false;" title="{$LANG.NEXT_MONTH}" style="text-decoration:none;"> ></a>
                    {else} >
                    {/if}
                    {if $calendar.yahr<$tody}
                    <a href="#" onclick="getMonthCalendar('{$calendar.next_yahr_date}', '{$module_id}'); return false;" title="{$LANG.NEXT_MONTH}" style="text-decoration:none;">  {$calendar.ya3} </a>
                    {/if}
                </td>
            </tr><tr>
                <td >
                    <!--  перебираем недели  -->
                    {foreach key=y item=row from=$calendar.days}

                    <!-- дни недели -->
                    {foreach key=x item=day from=$row}
                    {if $day.num}

                    {if $day.date <=$date}
                    <a class="day_date" href="#" onclick="getEvents('{$day.date}', '{$module_id}'); return false;">
                        {else}<b class="date2">
                            {/if}
                            <div style="width:14px; text-align: center; float: left; margin-left:5px;">

                                {if $x == 0}ПН<br>{/if}
                                {if $x == 1}ВТ<br>{/if}
                                {if $x == 2}СР<br>{/if}
                                {if $x == 3}ЧТ<br>{/if}
                                {if $x == 4}ПТ<br>{/if}
                                {if $x == 5}СБ<br>{/if}
                                {if $x == 6}ВС<br>{/if}
                                {$day.num}
                            </div>
                            {if $day.date <=$date}

                    </a>{else}</b>
                    {/if}
                    {/if}
                    {/foreach}


                    {/foreach}
                </td></tr>
        </table>
        <div id="events_popup" >
            <script type="text/javascript" src="/modules/mod_calendar/js/jquery.dropdown.js" ></script>

            <div style="margin-left:{$widi}px">
                <img src="/modules/mod_calendar/images/line.png" margin-left="{$widi}px" ></div>
            <div class="events_on_site" style="width:100%">
                <div class="events_on_site2"><c class="events_on_site3">{$title} на сайте:</c></div>

                <ul class="cssmenu">
                    {if $cfg.content != 'off'}
                    <li><a href="#">
                            статьи {$even.content}</a>
                        {if $even.content >'0'}
                        <ul><li>
                                <div class="sc_menu_wrapper">

                                    <div class="sc_menu">

                                        {foreach key=k item=content from=$contents}
                                        <a href="/{$content.url}.html">
                                            <div>
                                                <div class="photo_cal"><img src="{$content.avatar}" width="45px"></div>
                                                <div class="user_reg_kalend">
                                                    <div class="u_reg_c" >
                                                        <c style="font-size:10px; color:#C5C5C5; float:bottom;">{$content.nickname} - {$content.pubdate}</c><br>
                                                        <b style="position:relativ; top:-30px; height:16px;">{$content.title|truncate:30:"..."}</b> <br>
                                                        раздел: {$content.name|truncate:35:"..."}
                                                    </div>
                                                </div></div>
                                        </a>
                                        {/foreach}
                                    </div>
                                </div>
                        </ul></li>
                    {/if}
                    </li>
                    {/if}
                    {if $cfg.blog != 'off'}

                    <li><a href="#">
                            блоги {$even.posts} </a>
                        {if $even.posts >'0'}
                        <ul><li>
                                <div class="sc_menu_wrapper">

                                    <div class="sc_menu">

                                        {foreach key=k item=post from=$posts}
                                        <a href="/blogs/{$post.ublog}/{$post.upost}.html">
                                            <div>
                                                <div class="photo_cal"><img src="{$post.avatar}" width="45px"></div>
                                                <div class="user_reg_kalend">
                                                    <div class="u_reg_c" >
                                                        <c style="font-size:10px; color:#C5C5C5; float:bottom;">{$post.nickname} - {$post.pubdate}</c><br>
                                                        <b style="position:relativ; top:-30px; height:16px;">{$post.title|truncate:30:"..."}</b> <br>
                                                        блог: {$post.name|truncate:35:"..."}
                                                    </div>
                                                </div></div>

                                        </a>
                                        {/foreach}
                                    </div>
                                </div>
                        </ul></li>
                    {/if}
                    </li>
                    {/if}
                    {if $cfg.photo != 'off'}

                    <li><a href="#">
                            фотографий {$even.photo}</a>
                        {if $even.photo >'0'}
                        <ul><li>
                                <div class="sc_menu_wrapper">

                                    <div class="sc_menu">

                                        {foreach key=k item=photo from=$photos}
                                        <a href="/photos/photo{$photo.id}.html">
                                            <div>
                                                <div class="photo_cal"><img src="/images/photos/small/{$photo.file}" width="45px"></div>
                                                <div class="user_reg_kalend">

                                                    <div class="u_reg_c" >
                                                        <c style="font-size:10px; color:#C5C5C5; float:bottom;">{$photo.nickname} - {$photo.pubdate}</c><br>
                                                        <b style="position:relativ; top:-30px; height:16px;">{$photo.title|truncate:30:"..."}</b> <br>
                                                        альбом: {$photo.name|truncate:35:"..."}


                                                    </div>

                                                </div></div>


                                        </a>
                                        {/foreach}

                                    </div>

                                </div>
                        </ul></li>
                    {/if}
                    </li>
                    {/if}
                    {if $cfg.board != 'off'}

                    <li><a href="#">
                            объявления {$even.board}</a>
                        {if $even.board >'0'}
                        <ul><li>
                                <div class="sc_menu_wrapper">
                                    <div class="sc_menu">
                                        {foreach key=k item=board from=$boards}
                                        <a href="/board/read{$board.id}.html">
                                            <div>
                                                <div class="photo_cal"><img src="{$board.avatar}" width="45px"></div>
                                                <div class="user_reg_kalend">

                                                    <div class="u_reg_c" >
                                                        <c style="font-size:10px; color:#C5C5C5; float:bottom;">{$board.nickname} - {$board.pubdate}</c><br>
                                                        <b style="position:relativ; top:-30px; height:16px;">{$board.title|truncate:30:"..."}</b> <br>
                                                        раздел: {$board.obtype|truncate:35:"..."}
                                                    </div>
                                                </div></div>
                                        </a>
                                        {/foreach}
                                    </div>
                                </div>
                        </ul></li>
                    {/if}
                    </li>
                    {/if}
                    {if $cfg.comment != 'off'}

                    <li><a href="#">
                            комментария {$even.comment}</a>
                        {if $even.comment >'0'}
                        <ul><li>
                                <div class="sc_menu_wrapper">
                                    <div class="sc_menu">
                                        {foreach key=k item=comment from=$comments}
                                        <a href="{$comment.url}">
                                            <div>
                                                <div class="photo_cal"><img src="{$comment.avatar}" width="45px"></div>

                                                <div class="user_reg_kalend">

                                                    <div class="u_reg_c" >
                                                        <c style="font-size:10px; color:#C5C5C5; float:bottom;">{$comment.nickname} - {$comment.pubdate}</c><br>
                                                        <b style="position:relativ; top:-30px; height:16px;">{$comment.title|truncate:30:"..."}</b> <br>
                                                        {$comment.content|truncate:35:"..."}


                                                    </div>

                                                </div>
                                            </div>
                                        </a>
                                        {/foreach}
                                    </div>
                                </div>
                        </ul></li>
                    {/if}
                    </li>
                    {/if}
                    {if $cfg.forum != 'off'}

                    <li><a href="#">
                            форум {$even.forum}</a>
                        {if $even.forum >'0'}
                        <ul><li>
                                <div class="sc_menu_wrapper">
                                    <div class="sc_menu">
                                        {foreach key=k item=forum from=$forums}
                                        <a href="/forum/thread{$forum.id}.html">
                                            <div>
                                                <div class="photo_cal"><img src="{$forum.avatar}" width="45px" ></div>

                                                <div class="user_reg_kalend">

                                                    <div class="u_reg_c" >
                                                        <c style="font-size:10px; color:#C5C5C5; float:bottom;">{$forum.nickname} - {$forum.pubdate}</c><br>
                                                        <b style="position:relativ; top:-30px; height:16px;">{$forum.title|truncate:30:"..."}<br>
                                                            {$forum.content|truncate:35:"..."}
                                                        </b>

                                                    </div>

                                                </div></div>

                                        </a>
                                        {/foreach}
                                    </div>
                                </div>
                        </ul></li>
                    {/if}
                    </li>
                    {/if}
                    {if $cfg.user != 'off'}

                    <li><a href="#">
                            регистрации {$even.use}</a>
                        {if $even.use>'0'}
                        <ul><li>
                                <div class="sc_menu_wrapper">
                                    <div class="sc_menu">
                                        {foreach key=k item=user from=$users}
                                        <a href="/users/{$user.login}">
                                            <div>
                                                <div class="photo_cal"><img src="{$user.avatar}" width="45px"></div>
                                                <div class="user_reg_kalend">

                                                    <div class="u_reg_c" >
                                                        <c style="font-size:10px; color:#C5C5C5; float:bottom;"> {$user.regdata}</c><br>
                                                        <b style="position:relativ; top:-30px; height:16px;">{$user.nickname|truncate:30:"..."}</b> <br>
                                                        {$user.status}
                                                    </div>
                                                </div></div>
                                        </a>
                                        {/foreach}
                                    </div>
                                </div>
                        </ul></li>
                    {/if}
                    </li>
                    {/if}
                </ul>
            </div></div>
    </div></div>