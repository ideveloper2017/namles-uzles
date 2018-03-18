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
</div>
