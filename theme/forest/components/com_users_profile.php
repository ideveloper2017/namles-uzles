<h4 class="pi-has-bg pi-weight-700 pi-uppercase pi-letter-spacing pi-margin-bottom-25">
    Настройте свой аккаунт
</h4>

<?php if (!empty(Core::$showMsg)) { ?>
    <!--            <div class="alert alert-warning">-->
    <!--                <button type="button" class="close" data-dismiss="alert">×</button>-->
    <!--                <h4>--><?php //echo Core::$showMsg?><!--</h4>-->
    <!--            </div>-->

    <?php echo Core::$showMsg ?>
<?php } ?>
<hr class="pi-divider-gap-10">
<form role="form" method="post" enctype="multipart/form-data">
    <input name="action" value="updateprofile" type="hidden"/>
    <div class="pi-row pi-grid-small-margins">
        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="username">Имя пользователя</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-user"></i></div>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Имя пользователя"
                           value="<?php echo $row->username; ?>" readonly="readonly">
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

    </div>
    <div class="pi-row pi-grid-small-margins">
        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="fname">Имя</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-user"></i></div>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="Имя"
                           value="<?php echo $row->fname ?>"/>
                </div>
            </div>
        </div>
        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="lname">Фамилия</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-user"></i></div>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Фамилия"
                           value="<?php echo $row->lname ?>"/>
                </div>
            </div>

        </div>

        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="birthdate">Дата рождения</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-calendar"></i></div>
                    <input type="text" class="form-control" id="birthdate" name="birthdate" placeholder="Дата рождения"
                           value="<?php echo $row->birthdate ?>"/>
                </div>
            </div>
        </div>

        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="phone">Телефон</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-phone"></i></div>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Телефон"
                           value="<?php echo $row->phone ?>"/>
                </div>
            </div>
        </div>

        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="email">Электронной почты</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-mail"></i></div>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Электронной почты"
                           value="<?php echo $row->email ?>"/>
                </div>
            </div>
        </div>
        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="email">Рассылка пользователь</label>

                <div class="radio">
                    <label for="email_newmsg">Нет
                        <input type="radio" id="newsletter" name="newsletter"
                               value="0" <?php getChecked($row->newsletter, 0) ?>/>
                    </label>
                </div>
                <div class="radio">
                    <label for="email_newmsg">Да
                        <input type="radio" id="newsletter" name="newsletter"
                               value="1" <?php getChecked($row->newsletter, 1) ?>/>
                    </label>
                </div>
            </div>
        </div>

        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="avatar">Пользователь аватар</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-image"></i></div>
                    <input type="file" class="form-control" id="avatar" name="avatar"
                           placeholder="Пользователь аватар"/>
                </div>
            </div>
        </div>

        <div class="pi-col-sm-3">
            <div class="form-group">
                <label for="avatar">Пользователь аватар</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-image"></i></div>


                    <span class="pi-testimonial-author-photo pi-img-round"><img
                            src="/uploads/avatars/<?php echo $row->avatar; ?>" alt=""></span>
                    <!--                        <img src="/uploads/avatars/-->
                    <?php //echo $row->avatar;?><!--" alt="">-->

                </div>
            </div>
        </div>
    </div>

    <hr class="pi-divider-gap-10">
    <div class="pi-row pi-grid-small-margins">
        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="captcha">Последний войти</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-calendar"></i></div>
                    <input type="text" readonly="readonly" class="form-control" id="lastlogin" name="lastlogin"
                           placeholder="Последний войти"
                           value="<?php echo Registry::get("Core")->cmsRusDate(Core::doDate($config->long_date, $row->lastlogin)); ?>"/>
                </div>
            </div>
        </div>
        <div class="pi-col-sm-6">
            <div class="form-group">
                <label for="created_at">Дата регистрации</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-calendar"></i></div>
                    <input type="text" readonly="readonly" class="form-control" id="created_at" name="created_at"
                           placeholder="Защитный код код"
                           value="<?php echo Registry::get("Core")->cmsRusDate(Core::doDate($config->long_date, $row->created_at)); ?>"/>
                </div>
            </div>

        </div>
    </div>

    <hr class="pi-divider-gap-10">
    <div class="pi-row pi-grid-small-margins">
        <div class="pi-col-sm-3">
            <div class="form-group">
                <label for="fb_link">Facebook</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-facebook-squared"></i></div>
                    <input type="text" class="form-control" id="fb_link" name="fb_link" placeholder="Facebook"
                           value="<?php echo $row->fb_link; ?>"/>
                </div>
            </div>
        </div>
        <div class="pi-col-sm-3">
            <div class="form-group">
                <label for="tw_link">Twitter</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-twitter"></i></div>
                    <input type="text" class="form-control" id="tw_link" name="tw_link" placeholder="Twitter"
                           value="<?php echo $row->tw_link; ?>"/>
                </div>
            </div>
        </div>
        <div class="pi-col-sm-3">
            <div class="form-group">
                <label for="tw_link">Goole Plus</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-gplus"></i></div>
                    <input type="text" class="form-control" id="gp_link" name="gp_link" placeholder="Goole Plus"
                           value="<?php echo $row->tw_link; ?>"/>
                </div>
            </div>
        </div>
        <div class="pi-col-sm-3">
            <div class="form-group">
                <label for="tw_link">Вконтакте</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-vkontakte"></i></div>
                    <input type="text" class="form-control" id="vk_link" name="vk_link" placeholder="Вконтакте"
                           value="<?php echo $row->vk_link; ?>"/>
                </div>
            </div>
        </div>
    </div>
    <hr class="pi-divider-gap-10">
    <div class="pi-row pi-grid-small-margins">
        <div class="pi-col-sm-12">
            <div class="form-group">
                <label for="tw_link">Веб сайта</label>

                <div class="pi-input-with-icon">
                    <div class="pi-input-icon"><i class="icon-link"></i></div>
                    <input type="text" class="form-control" id="website" name="website" placeholder="Веб сайта"
                           value="<?php echo $row->website; ?>"/>
                </div>
            </div>
        </div>
        </div>

    <hr class="pi-divider-gap-10">
    <div class="pi-row pi-grid-small-margins">
        <div class="pi-col-sm-12">
        <div class="form-group">
            <label for="tw_link">О себе</label>
            <div class="pi-input-with-icon">
                <div class="pi-input-icon"><i class="icon-pencil"></i></div>
                <textarea class="form-control" id="info" name="info"  rows="4"><?php echo $row->info;?></textarea>
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




