<?phpfunction mod_search($id){    $lang = Lang::getLangID();    switch ($lang) {        case 'ru':            $strlang = 'Поиск...';            break;        case 'uz':            $strlang = 'Излаш...';            break;    }    ?>    <div class="nav__search-box" id="nav__search-box">        <form class="nav__search-form" method="post" action="/search.html">            <input type="hidden" name="do" value="search"/>            <input type="text" placeholder="Излаш учун сўз киритинг" class="nav__search-input">            <button type="submit" class="nav__search-button btn btn-md btn-color btn-button">                <i class="ui-search nav__search-icon"></i>            </button>        </form>    </div><!--    <ul class="nav-cta hidden-xs">--><!--        <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i--><!--                        class="fa fa-search"></i></a>--><!--            <ul class="dropdown-menu">--><!--                <li>--><!--                    <div class="head-search">--><!--                        <form role="form" method="post" action="/search.html">--><!--                            <input type="hidden" name="do" value="search"/>--><!--                            <div class="input-group">--><!--                                <input type="text" class="form-control" name="q"--><!--                                       placeholder="Излаш учун сўз киритинг"> <span--><!--                                        class="input-group-btn">--><!--                                                                            <button type="submit"--><!--                                                                                    class="btn btn-primary">--><?php //echo $strlang; ?><!--                                                                            </button>--><!--                                                                        </span></div>--><!--                        </form>--><!--                    </div>--><!--                </li>--><!--            </ul>--><!--        </li>--><!--    </ul>-->    <!--    <div id="top-search">-->    <!--        <a href="#" id="top-search-trigger"><i class="fa fa-search"></i><i class="icon-line-cross"></i></a>-->    <!--        <form method="post" action="/search.html">-->    <!--            <input type="hidden" name="do" value="search" />-->    <!--            <input type="text" name="q" class="form-control" value="" placeholder="--><?php //echo $strlang;    ?><!--">-->    <!---->    <!--        </form>-->    <!--    </div>-->    <?php    return true;}?>