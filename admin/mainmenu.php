<div class="navbar navbar-inverse" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-icons">
            <span class="sr-only">Toggle right icons</span>
            <i class="icon-grid"></i>
        </button>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
            <span class="sr-only">Toggle menu</span>
            <i class="icon-paragraph-justify2"></i>
        </button>

    </div>

    <ul class="nav navbar-nav collapse" id="navbar-menu">
        <li><a href="index.php"><i class="icon-screen2"></i> <span>Главный</span></a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-numbered-list"></i> <span>Меню</span> <b class="caret"></b></a>
            <ul class="dropdown-menu dropdown-menu-right">

                <li class="<?php if (Core::$action == 'addmenu') echo 'active'; ?>  " ><a
                       href="index.php?do=menus&action=addmenu">Создать меню</a></li>
                <li class="<?php if (Core::$action == 'addmenuitem') echo 'active'; ?>  "><a
                       href="index.php?do=menus&action=addmenuitem">Создать пункт</a></li>
                <li class="<?php if (Core::$do == 'menus') echo 'active'; ?> "><a  href="index.php?do=menus">Показать
                        все</a></li>

            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-grid"></i> <span>Модули</span> <b class="caret"></b></a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a class=" " href="index.php?do=modules&action=add">Создать модуль</a></li>
                <li><a class=" " href="index.php?do=modules">Показать все</a></li>

            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-copy"></i> <span>Каталог статей</span>  <b class="caret"></b></a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a class=" " href="index.php?do=content">Cтатьи</a></li>
                <li><a class=" " href="index.php?do=content&action=cats">Разделы</a></li>
<!--                <li><a class=" " href="index.php?do=content&action=arhive">Архив статей</a></li>-->
                <li><a class=" " href="index.php?do=content&action=addcats">Создать раздел</a></li>
                <li><a class=" " href="index.php?do=content&action=addcontent">Создать статью</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-paragraph-justify2"></i> <span>Компоненты</span>  <b class="caret"></b></a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a class=" " href="index.php?do=components">Все комьпоненты</a></li>

                <?php foreach ($core->getComponentList() as $complist):
                    ?>
                    <li><a class=" "
                           href="index.php?do=components&action=config&id=<?php echo $complist['id']; ?>"><?php echo $complist['title']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> <span>Пользователи</span>  <b class="caret"></b></a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a class=" " href="index.php?do=users">Пользователи</a></li>
                <li><a class=" " href="index.php?do=users&action=usergroup">Группы</a></li>
                <li><a class=" " href="index.php?do=users&action=add">Создать пользователя</a></li>
                <li><a class=" " href="index.php?do=users&action=addgroup">Создать группу</a></li>
                <li><a class=" " href="index.php?do=users&action=settings">Настройки профилей</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> <span>Допольнения</span>  <b class="caret"></b></a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a class=" " href="index.php?do=plugins">Плагины</a></li>
                <li><a class=" " href="index.php?do=filters">Фильтры</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-settings"></i> <span>Натройка</span>  <b class="caret"></b></a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a class=" " href="index.php?do=config">Конфигурация</a></li>
                <li><a class=" " href="contacts.html">Резервное копирование</a></li>
                <li><a class=" " href="index.php?do=backup">Файловый менеджер</a></li>
                <li><a class=" " href="index.php?do=backup">Настраиваемые поля</a></li>
            </ul>
        </li>
    </ul>

    <ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">
        <li class="user dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown">
                <img src="/uploads/avatars/<?php echo $users->avatar;?>" alt="">
                <span><?php echo $users->name;?></span>
                <i class="caret"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right icons-right">
                <li><a href="index.php?do=users&action=edit&id=<?php echo $users->uid; ?>"><i class="icon-user"></i> Профиль</a></li>
                <li><a href="#"><i class="icon-bubble4"></i> Сообщения</a></li>
                <li><a href="#"><i class="icon-cog"></i> Настройка</a></li>
                <li><a href="logout.php"><i class="icon-exit"></i> Выйти</a></li>
            </ul>
        </li>
    </ul>
</div>