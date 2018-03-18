<h4 class="pi-has-bg pi-weight-700 pi-uppercase pi-letter-spacing pi-margin-bottom-25">
    Регистрация форма
</h4>
<hr class="pi-divider-gap-10">
<form role="form" method="post">
    <input name="action" value="register" type="hidden"/>
<div class="pi-row pi-grid-small-margins">
    <div class="pi-col-sm-12">
        <div class="form-group">
            <label for="username">Имя пользователя</label>

            <div class="pi-input-with-icon">
                <div class="pi-input-icon"><i class="icon-user"></i></div>
                <input type="text" class="form-control" id="username" name="username" placeholder="Имя пользователя">
            </div>
        </div>
    </div>
      <div class="pi-col-sm-6">
               <div class="form-group">
            <label for="password">Пароль *</label>
            <div class="pi-input-with-icon">
                <div class="pi-input-icon"><i class="icon-lock"></i></div>
                <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
            </div>
        </div>
    </div>
    <div class="pi-col-sm-6">
         <div class="form-group">
            <label for="exampleInputEmail-4">Повторите пароль *</label>
            <div class="pi-input-with-icon">
                <div class="pi-input-icon"><i class="icon-lock"></i></div>
                <input type="password" class="form-control" id="password1" name="password1" placeholder="Повторите пароль">
            </div>
        </div>
    </div>
</div>
<div class="pi-row pi-grid-small-margins">
    <div class="pi-col-sm-12">
        <div class="form-group">
            <label for="email">Электронной почты</label>
            <div class="pi-input-with-icon">
                <div class="pi-input-icon"><i class="icon-mail"></i></div>
                <input type="text" class="form-control" id="email" name="email" placeholder="Электронной почты">
            </div>
        </div>
     </div>

    <div class="pi-col-sm-6">
        <div class="form-group">
            <label for="fname">Имя</label>
            <div class="pi-input-with-icon">
                <div class="pi-input-icon"><i class="icon-phone"></i></div>
                <input type="text" class="form-control" id="fname" name="fname" placeholder="Имя">
            </div>
        </div>
    </div>
    <div class="pi-col-sm-6">
        <div class="form-group">
            <label for="lname">Фамилия</label>
            <div class="pi-input-with-icon">
                <div class="pi-input-icon"><i class="icon-phone"></i></div>
                <input type="text" class="form-control" id="lname" name="lname" placeholder="Фамилия">
            </div>
        </div>

    </div>
</div>

<hr class="pi-divider-gap-10">
    <div class="pi-row pi-grid-small-margins">
        <div class="pi-col-sm-10">
            <div class="form-group">
                <label for="captcha">Защитный код код</label>
                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-mail"></i></div>
                    <input type="text" class="form-control" id="captcha" name="captcha" placeholder="Защитный код код">
                </div>
            </div>
        </div>
        <div class="pi-col-sm-2">
            <div class="form-group">
                <label for="email">&nbsp;</label>
                <div class="pi-input-with-icon">
                    <img src="<?php echo SITEURL;?>/libs/captcha.php" alt="" class="captcha-append">
                </div>
            </div>

        </div>
        </div>
<!-- Submit button -->
<p>
    <button type="submit" class="btn pi-btn-base pi-btn-wide pi-uppercase pi-weight-700 pi-letter-spacing">
        Регистрация Аккаунта<i class="icon-paper-plane pi-icon-right"></i>
    </button>
</p>
<!-- End submit button -->

</form>



<?php if (!empty(Core::$showMsg)) { ?>
    <!--            <div class="alert alert-warning">-->
    <!--                <button type="button" class="close" data-dismiss="alert">×</button>-->
    <!--                <h4>--><?php //echo Core::$showMsg?><!--</h4>-->
    <!--            </div>-->

    <?php echo Core::$showMsg ?>
<?php } ?>


