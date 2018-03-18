<?php
define("BPA_CMS", true);
define("PATH", $_SERVER['DOCUMENT_ROOT']);
include("../init.php");

if ($users->is_Admin()) {
    redirect_to("index.php");
} else {
    if (isset(Core::$post['submit'])) {
        $result = $users->login(Core::$post['username'], Core::$post['password']);
        if ($result) {
            redirect_to("index.php");
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <title>Авторизация</title>

    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/londinium-theme.css" rel="stylesheet" type="text/css">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link href="css/icons.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

    <script type="text/javascript" src="js/plugins/charts/sparkline.min.js"></script>

    <script type="text/javascript" src="js/plugins/forms/uniform.min.js"></script>
    <script type="text/javascript" src="js/plugins/forms/select2.min.js"></script>
    <script type="text/javascript" src="js/plugins/forms/inputmask.js"></script>
    <script type="text/javascript" src="js/plugins/forms/autosize.js"></script>
    <script type="text/javascript" src="js/plugins/forms/inputlimit.min.js"></script>
    <script type="text/javascript" src="js/plugins/forms/listbox.js"></script>
    <script type="text/javascript" src="js/plugins/forms/multiselect.js"></script>
    <script type="text/javascript" src="js/plugins/forms/validate.min.js"></script>
    <script type="text/javascript" src="js/plugins/forms/tags.min.js"></script>
    <script type="text/javascript" src="js/plugins/forms/switch.min.js"></script>

    <script type="text/javascript" src="js/plugins/forms/uploader/plupload.full.min.js"></script>
    <script type="text/javascript" src="js/plugins/forms/uploader/plupload.queue.min.js"></script>

    <script type="text/javascript" src="js/plugins/forms/wysihtml5/wysihtml5.min.js"></script>
    <script type="text/javascript" src="js/plugins/forms/wysihtml5/toolbar.js"></script>

    <script type="text/javascript" src="js/plugins/interface/daterangepicker.js"></script>
    <script type="text/javascript" src="js/plugins/interface/fancybox.min.js"></script>
    <script type="text/javascript" src="js/plugins/interface/moment.js"></script>
    <script type="text/javascript" src="js/plugins/interface/jgrowl.min.js"></script>
    <script type="text/javascript" src="js/plugins/interface/datatables.min.js"></script>
    <script type="text/javascript" src="js/plugins/interface/colorpicker.js"></script>
    <script type="text/javascript" src="js/plugins/interface/fullcalendar.min.js"></script>
    <script type="text/javascript" src="js/plugins/interface/timepicker.min.js"></script>

    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/application.js"></script>

</head>

<body class="full-width page-condensed">

<!-- Navbar -->

<!-- /navbar -->


<!-- Login wrapper -->
<div class="login-wrapper">
    <?php  if (!empty(Core::$showMsg)) {?>
    <div class="alert alert-danger fade in block-inner">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="icon-cancel-circle"></i> <?php echo Core::$showMsg;?>
    </div>
    <?php } ?>
    <form action="" role="form" method="post">
        <div class="popup-header">
            <a href="#" class="pull-left"><i class="icon-user-plus"></i></a>
                <span class="text-semibold">Логин пользователя</span>
            <div class="btn-group pull-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cogs"></i></a>

            </div>
        </div>

        <div class="well">
            <div class="form-group has-feedback">
                <label>Логин</label>
                <input type="text" class="form-control" placeholder="Имя пользователя" name="username">
                <i class="icon-users form-control-feedback"></i>
            </div>

            <div class="form-group has-feedback">
                <label>Пароль</label>
                <input type="password" class="form-control" placeholder="пароль" name="password">
                <i class="icon-lock form-control-feedback"></i>
            </div>

            <div class="row form-actions">
                <div class="col-xs-6">
                   
                </div>

                <div class="col-xs-6">
                    <button type="submit" name="submit" class="btn btn-warning pull-right"><i class="icon-menu2"></i>Войти</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /login wrapper -->


<!-- Footer -->
<div class="footer clearfix">
    <div class="pull-left">&copy; 2015. IDrive.uz </div>

</div>
<!-- /footer -->


</body>
</html>



