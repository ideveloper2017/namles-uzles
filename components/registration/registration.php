<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 23.08.13
 * Time: 16:43
 * To change this template use File | Settings | File Templates.
 */
function registration()
{
    $db = Registry::get("DataBase");
    $config = Registry::get("Config");
    $core = Registry::get("Core");
    $users = Registry::get("Users");


    if (!Core::$action) {
        $core->action = 'view';
    } else {
        $core->action = Core::$action;
    }

    if (!Core::$id) {
        $core->id = 0;
    } else {
        $core->id = Core::$id;
    }

    $cfg = Registry::get("Core")->getComponentConfig("registration");

    if (!isset($cfg['is_on'])) {
        $cfg['is_on'] = 1;
    }
    if (!isset($cfg['name_mode'])) {
        $cfg['name_mode'] = 'nickname';
    }
    if (!isset($cfg['first_auth_redirect'])) {
        $cfg['first_auth_redirect'] = 'profile';
    }
    if (!isset($cfg['ask_icq'])) {
        $cfg['ask_icq'] = 1;
    }
    if (!isset($cfg['ask_birthdate'])) {
        $cfg['ask_birthdate'] = 1;
    }
    if (!isset($cfg['send_greetmsg'])) {
        $cfg['send_greetmsg'] = 0;
    }


    if ($core->action == 'view') {
//        $smarty = $ker->smartyInitComponent();
//        $smarty->assign("cfg",$cfg);
//        $smarty->display('com_registration.tpl');


//        include(PATH.'/theme/'.$config->template.'/com_registration.php');
    }

    if ($core->action == 'register') {
        $users->register();
        include(PATH . '/theme/' . Registry::get("Config")->template . '/com_registration.php');
    }

    if ($core->action == 'signup') {
        if (!$cfg['is_on']) {
            echo $cfg['offmsg'];
        } else {
            if ($cfg['reg_type'] == 'open') {
                include(PATH . '/theme/' . Registry::get("Config")->template . '/com_registration.php');
//                $core->includeFile("com_registration");
            }
            if ($cfg['reg_type'] == 'email') {
                $core->includeFile("com_signup_email");
            }
        }
    }

    if ($core->action == 'signin') {
        if ($users->logged_in)
            if (isset($_REQUEST['doLogin']))
                :
                $result = $users->login($_REQUEST['username'], $_REQUEST['password']);
                /* Login Successful */
                if ($result):
                    echo $result;
                endif;
            endif;
        ?>
        <div class="pi-col-md-6 pi-col-md-offset-4 pi-col-sm-6 pi-col-sm-offset-3 pi-col-xs-8 pi-col-xs-offset-2">
            <div class="pi-box pi-round pi-shadow-15">
                <form method="post" id="login_form" name="login_form">
                    <input name="action" type="hidden" value="vixod">
                    <!-- Email form -->
                    <div class="form-group">
                        <div class="pi-input-with-icon">
                            <div class="pi-input-icon"><i class="icon-mail"></i></div>
                            <input type="text" class="form-control" id="username" name="username"
                                   placeholder="username">
                        </div>
                    </div>
                    <!-- End email form -->

                    <!-- Password form -->
                    <div class="form-group">
                        <div class="pi-input-with-icon">
                            <div class="pi-input-icon"><i class="icon-lock"></i></div>
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Password">
                        </div>
                    </div>
                    <!-- End password form -->

                    <p class="pi-pull-right pi-small-text">
                        <a href="#">
                            Forgot password?
                        </a>
                    </p>

                    <!-- Checkbox -->
                    <div class="checkbox">
                        <label class="pi-small-text">
                            <input type="checkbox">Remember me
                        </label>
                    </div>
                    <!-- End checkbox -->

                    <!-- Submit button -->
                    <p>
                        <button type="submit" class="btn pi-btn-base pi-btn-wide pi-weight-600">
                            Sign In
                        </button>
                    </p>
                    <!-- End submit button -->
                    <?php print Core::$showMsg; ?>
                </form>
            </div>
            <!-- End box -->

            <p class="pi-text-center">
                Don't have Account? <a href="#" class="pi-weight-600">Sign Up</a>
            </p>

        </div>
        <?php
    }

    if ($core->action == 'logout') {
        if ($users->logged_in)
            $users->logout();
        redirect_to("index.php");

    }

    if ($core->action == 'auth') {
        if (!$users->logged_in):

            $result = $users->login(Core::$post['username'], Core::$post['password']);
            if ($result) {
                redirect_to("/");
            }else{
                print Core::$showMsg;
            }
        endif;
    }

    if ($core->action=='profile'){
        $user=Core::$get['username'];
        $row=$users->getUserData($user);

//        $sql="select * from users u where u.username='{$user}'";
//        $row=$db->first($sql);

        include(PATH . '/theme/' . Registry::get("Config")->template . '/com_users_profile.php');
    }

    if ($core->action=='updateprofile'){
        $user=Core::$get['username'];
        $row=$users->getUserData($user);
         $users->updateprofile();

//         header("Location:". $_SERVER['HTTP_REFERER']);

        include(PATH . '/theme/' . Registry::get("Config")->template . '/com_users_profile.php');
    }

}

?>