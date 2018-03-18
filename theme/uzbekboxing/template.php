<!DOCTYPE html>
<html lang="en">
<head>
    <?php $page->printHead();?>

    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />


    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,600,700%7CRoboto:400,400i,700' rel='stylesheet'>

    <!-- Css -->
    <link rel="stylesheet" href="<?php echo THEMEURL ?>/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo THEMEURL ?>/css/font-icons.css" />
    <link rel="stylesheet" href="<?php echo THEMEURL ?>/css/style.css" />
<!--    <link rel="stylesheet" href="--><?php //echo THEMEURL ?><!--/css/style2.css" />-->

    <!-- Favicons -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">

    <!-- Lazyload -->
    <script type="text/javascript" src="<?php echo THEMEURL ?>/js/lazysizes.min.js"></script>

</head>

<body class="bg-light">

<!-- Preloader -->
<div class="loader-mask">
    <div class="loader">
        <div></div>
    </div>
</div>

<!-- Bg Overlay -->
<div class="content-overlay"></div>

<!-- Subscribe Modal -->
<div class="modal fade" id="subscribe-modal" tabindex="-1" role="dialog" aria-labelledby="subscribeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subscribeModalLabel">Subscribe for Newsletter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="mc4wp-form" method="post">
                    <div class="mc4wp-form-fields">
                        <p>
                            <i class="mc4wp-form-icon ui-email"></i>
                            <input type="email" name="EMAIL" placeholder="Your email" required="">
                        </p>
                        <p>
                            <input type="submit" class="btn btn-md btn-color" value="Subscribe">
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> <!-- end subscribe modal -->


<!-- Mobile Sidenav -->
<header class="sidenav" id="sidenav">
    <!-- Search -->
    <div class="sidenav__search-mobile">
        <form method="get" class="sidenav__search-mobile-form">
            <input type="search" class="sidenav__search-mobile-input" placeholder="Search..." aria-label="Search input">
            <button type="submit" class="sidenav__search-mobile-submit" aria-label="Submit search">
                <i class="ui-search"></i>
            </button>
        </form>
    </div>

    <nav>
        <?php $core->loadModule('fixmodule');?>
        <ul class="sidenav__menu" role="menubar">
            <li>
                <a href="index.html" class="sidenav__menu-link">Home</a>
                <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                <ul class="sidenav__menu-dropdown">
                    <li><a href="index.html" class="sidenav__menu-link">Home Demo 1</a></li>
                    <li><a href="index-2.html" class="sidenav__menu-link">Home Demo 2</a></li>
                    <li><a href="index-3.html" class="sidenav__menu-link">Home Demo 3</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="sidenav__menu-link">Posts</a>
                <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                <ul class="sidenav__menu-dropdown">
                    <li><a href="single-post.html" class="sidenav__menu-link">Gallery Post</a></li>
                    <li><a href="single-post.html" class="sidenav__menu-link">Video Post</a></li>
                    <li><a href="single-post.html" class="sidenav__menu-link">Audio Post</a></li>
                    <li><a href="single-post.html" class="sidenav__menu-link">Quote Post</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="sidenav__menu-link">Pages</a>
                <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                <ul class="sidenav__menu-dropdown">
                    <li><a href="about.html" class="sidenav__menu-link">About</a></li>
                    <li><a href="contact.html" class="sidenav__menu-link">Contact</a></li>
                    <li><a href="404.html" class="sidenav__menu-link">404</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="sidenav__menu-link">Features</a>
                <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                <ul class="sidenav__menu-dropdown">
                    <li><a href="lazyload.html" class="sidenav__menu-link">Lazyload</a></li>
                    <li><a href="shortcodes.html" class="sidenav__menu-link">Shortcodes</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="sidenav__menu-link">Purchase</a>
            </li>
        </ul>
    </nav>
    <div class="socials sidenav__socials ">
        <a class="social-facebook" href="#" target="_blank" aria-label="facebook">
            <i class="ui-facebook"></i>
        </a>
        <a class="social-twitter" href="#" target="_blank" aria-label="twitter">
            <i class="ui-twitter"></i>
        </a>
        <a class="social-youtube" href="#" target="_blank" aria-label="youtube">
            <i class="ui-youtube"></i>
        </a>
    </div>
</header>

<main class="main oh" id="main">
    <header class="nav">
        <div class="nav__holder nav--sticky">

            <div class="container relative">

                <div class="flex-parent">
                    <!-- Mobile Menu Button -->
                    <button class="nav-icon-toggle" id="nav-icon-toggle" aria-label="Open mobile menu">
              <span class="nav-icon-toggle__box">
                <span class="nav-icon-toggle__inner"></span>
              </span>
                    </button> <!-- end mobile menu button -->

                    <!-- Logo -->
                    <a href="/" class="logo">
                        <img class="logo__img" src="<?php echo THEMEURL; ?>/img/logo_light.png" srcset="<?php echo THEMEURL; ?>/img/logo_light.png 1x, <?php echo THEMEURL; ?>/img/logo_light@2x.png 2x" alt="logo">
                    </a>


                    <!-- Nav-wrap -->
                    <nav class="flex-child nav__wrap d-none d-lg-block">
                        <?php $core->loadModule('topmenu');?>
                    </nav>

                    <!-- Nav Right -->
                    <div class="nav__right nav--align-right d-none d-lg-flex">

                        <div class="nav__right-item socials socials--nobase nav__socials ">
                            <a class="social-facebook" href="#" target="_blank">
                                <i class="ui-facebook"></i>
                            </a>
                            <a class="social-twitter" href="#" target="_blank">
                                <i class="ui-twitter"></i>
                            </a>
                            <a class="social-youtube" href="#" target="_blank">
                                <i class="ui-youtube"></i>
                            </a>
                        </div>
<!---->
<!--                        <div class="nav__right-item">-->
<!--                            <a href="" class="nav__subscribe" data-toggle="modal" data-target="#subscribe-modal">Subscribe</a>-->
<!--                        </div>-->


                        <div class="nav__right-item nav__search">
                            <a href="#" class="nav__search-trigger" id="nav__search-trigger">
                                <i class="ui-search nav__search-trigger-icon"></i>
                            </a>

                            <?php $core->loadModule('search')?>
                        </div>
                    </div>

                </div> <!-- end flex-parent -->
            </div> <!-- end container -->

        </div>
    </header> <!-- end navigation -->


    <div class="header">
        <div class="container">
            <div class="flex-parent align-items-center">
                <!-- Logo -->
                <?php $core->loadModule('banner1')?>

<!--                <a href="index.html" class="logo d-none d-lg-block">-->
<!--                    <img class="logo__img" src="--><?php //echo THEMEURL;?><!--/img/logo_light.png" alt="logo">-->
<!--                </a>-->

                <!-- Ad Banner 728 -->
                <div class="text-center">
                    <?php $core->loadModule('banner2')?>
<!--                    <a href="#">-->
<!--                        <img src="--><?php //echo THEMEURL ?><!--/img/blog/placeholder_leaderboard.jpg" alt="">-->
<!--                    </a>-->
                </div>

            </div>


        </div>
    </div>

    <div class="trending-now">
        <?php $core->loadModule('top3');?>

    </div>

    <div class="main-container container mt-10" id="main-container">

        <?php if ($core->loadModuleCount('slider')){?>
        <?php $core->loadModule('slider');?>
        <?php }?>

        <section class="section-wrap pt-30 pb-30">
            <div class="container">
                <div class="row">
                   <!-- Posts -->

                    <?php $core->loadModule('maintop');?>
                    <!-- Sidebar -->
                    <?php if ($page->page_body) { ?>
                    <?php $page->printComponent(); ?>
                    <?php }?>

                    <aside class="col-md-4 sidebar sidebar--right">
                        <!-- Widget Popular Posts -->
                        <?php $core->loadModule('sidebar1');?>

                        <!-- Widget Banner -->

<!--                        <div class="widget widget_media_image">-->
<!--                            <a href="#">-->
<!--                                <img src="--><?php //echo THEMEURL ?><!--/img/blog/placeholder_300.jpg" alt="">-->
<!--                            </a>-->
<!--                        </div> -->

                    </aside> <!-- end sidebar -->

                </div>
            </div>
        </section> <!-- end content -->

    </div> <!-- end main container -->
    <footer class="footer">
        <div class="container">
            <div class="footer__widgets">
                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <div class="widget">
                            <img src="<?php echo THEMEURL ;?>/img/logo_light.png" srcset="<?php echo THEMEURL ;?>/img/logo_light.png 1x, <?php echo THEMEURL ;?>/img/logo_light@2x.png 2x" class="logo__img" alt="">
                            <p class="mt-20">Ижтимоий тармоқларда</p>

                            <div class="socials mt-20">
                                <a href="#" class="social-facebook" aria-label="facebook"><i class="ui-facebook"></i></a>
                                <a href="#" class="social-twitter" aria-label="twitter"><i class="ui-twitter"></i></a>
                                <a href="#" class="social-google-plus" aria-label="google+"><i class="ui-google"></i></a>
                                <a href="#" class="social-instagram" aria-label="instagram"><i class="ui-instagram"></i></a>
                            </div>
                        </div>
                    </div>

<!--                    <div class="col-lg-3 col-md-6">-->
<!--                        <h4 class="widget-title white">twitter feed</h4>-->
<!--                        <div class="tweets-container">-->
<!--                            <div id="tweets"></div>-->
<!--                        </div>-->
<!--                    </div>-->


<!--                    <div class="col-lg-3 col-md-6">-->
<!--                        <div class="widget widget_nav_menu">-->
<!--                            <h4 class="widget-title white">Useful Links</h4>-->
<!--                            <ul>-->
<!--                                <li><a href="#">About</a></li>-->
<!--                                <li><a href="#">Contact</a></li>-->
<!--                                <li><a href="#">Projects</a></li>-->
<!--                                <li><a href="#">Wordpress Themes</a></li>-->
<!--                                <li><a href="#">Advertise</a></li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </div>-->

                    <div class="col-lg-8 col-md-6">
                        <div class="widget widget__newsletter">
<!--                            <h4 class="widget-title white">subscribe to deothemes</h4>-->
                            <p>Сайт материалларидан тўлиқ ёки қисман фойдаланилганда веб-сайт манзили кўрсатилиши шарт! Барча ҳуқуқлар амалдаги қонунчиликка биноан ҳимояланган.
                            </p>



                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end container -->

        <div class="footer__bottom">
            <div class="container text-center">
            <span class="copyright">
              &copy; <?php echo date('Y')?> <?php echo $page->printSitename();?>
            </span>
            </div>
        </div> <!-- end bottom footer -->
    </footer> <!-- end footer -->

    <div id="back-to-top">
        <a href="#top" aria-label="Go to top"><i class="ui-arrow-up"></i></a>
    </div>

</main> <!-- end main-wrapper -->



<!-- jQuery Scripts -->
<script src="<?php echo THEMEURL ?>/js/jquery.min.js"></script>
<script src="<?php echo THEMEURL ?>/js/bootstrap.min.js"></script>
<script src="<?php echo THEMEURL ?>/js/easing.min.js"></script>
<script src="<?php echo THEMEURL ?>/js/owl-carousel.min.js"></script>
<script src="<?php echo THEMEURL ?>/js/twitterFetcher_min.js"></script>
<script src="<?php echo THEMEURL ?>/js/jquery.newsTicker.min.js"></script>
<script src="<?php echo THEMEURL ?>/js/modernizr.min.js"></script>
<script src="<?php echo THEMEURL ?>/js/scripts.js"></script>

</body>
</html>