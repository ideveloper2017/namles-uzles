<?php if ($users->logged_in){?>
<div class="pi-row-block pi-pull-right">
    <ul class="pi-menu pi-has-hover-border pi-items-have-double-borders pi-full-height pi-hidden-xs">


        <li>
            <a href="/profile/<?php echo $users->username;?>"><span>Профиль</span></a>
        </li>
        <li>
            <a href="/my"><span>Статьи</span></a>
        </li>
        <li>
            <a href="/blogs"><span>Блог</span></a>
        </li>
        <li>
            <a href="/photoalbum"><span>Фото</span></a>
        </li>
        <li>
            <a href="/add"><span>Написать</span></a>
        </li>
        <?php if ($users->is_Admin()){?>
            <li>
                <a href="/admin" target="_blank"><span>Админка</span></a>
            </li>
        <?php }?>
        <li>
            <a href="/logout"><span>Выход</span></a>
        </li>

    </ul>
</div>
<?php }?>
<div class="pi-row-block pi-row-block-txt pi-pull-right pi-hidden-xs">Добро пожаловать,<?php echo $users->username;?></div>
