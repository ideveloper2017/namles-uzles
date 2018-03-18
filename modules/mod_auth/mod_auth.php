<?php

function mod_auth($id)
{
    $core = Registry::get("Core");
    $users = Registry::get("Users");
    $cfg = $core->getComponentConfig('registration');
    if (!$users->logged_in) {
        ?>
        <div class="pi-row-block pi-pull-right pi-hidden-xs" style="margin-right: 5px;">
            <form class="form-inline pi-search-form-wide" name="logform" method="post" action="/auth"
                  style="margin-right: 1px;">
                <div class="pi-input-with-icon pi-input-inline">
                    <div class="pi-input-icon"><i class="icon-user"></i></div>
                    <input type="text" name="username" class="form-control pi-input-wide" placeholder="Логин"/>
                </div>
                <div class="pi-input-with-icon pi-input-inline">
                    <div class="pi-input-icon"><i class="icon-lock"></i></div>
                    <input type="password" name="password" class="form-control pi-input-wide"
                           placeholder="Пароль"/>
                </div>
                <div class="pi-row-block pi-pull-right pi-hidden-xs pi-no-margin-right">
                    <input class="btn pi-btn pi-btn-base-2" type="submit" value="Вход"/>
                    <a href="/signup">Регистрация</a> |
                    <a href="/forget">Забыл</a>
                </div>
            </form>
        </div>
        <?php
    }
    return true;
}
?>