    <h2>Фуқаролар мурожаати</h2>
    <form id="contact-form">
        <div class="success-message">Contact form submitted.</div>
        <div class="holder">
            <div class="form-div-1 clearfix">
                <label class="name">
                    <input type="text" placeholder="Ф.И.О*:" data-constraints="@Required @JustLetters" />
                    <span class="empty-message">*This field is required.</span>
                    <span class="error-message">*This is not a valid name.</span>
                </label>
            </div>
            <div class="form-div-2 clearfix">
                <label class="email">
                    <input type="text" placeholder="Email*:" data-constraints="@Required @Email" />
                    <span class="empty-message">*This field is required.</span>
                    <span class="error-message">*This is not a valid email.</span>
                </label>
            </div>
            <div class="form-div-3 clearfix">
                <label class="phone">
                    <input type="text" placeholder="Телефон:" data-constraints="@JustNumbers"/>
                    <span class="empty-message">*This field is required.</span>
                    <span class="error-message">*This is not a valid phone.</span>
                </label>
            </div>
        </div>
        <div class="form-div-4 clearfix">
            <label class="message">
                <textarea placeholder="Хабар*:" data-constraints='@Required @Length(min=20,max=999999)'></textarea>
                <span class="empty-message">*This field is required.</span>
                <span class="error-message">*The message is too short.</span>
            </label>
        </div>
        <div class="btns">
            <a href="#" data-type="submit" class="btn-default btn">Жўнатиш</a>
            <p>*Required fields</p>
        </div>
    </form>

<!--<div class="clearfix"></div>-->
<!--<div class="eleven floated">-->
<!--    <div class="clearfix">&nbsp;</div>-->
<!--    {if $sending}-->
<!--    <div class="notification success closeable" id="notification_2" style="display: block;">-->
<!--        <p><span>Success!</span> {$sending}.</p>-->
<!--        <a class="close" href="#"><i class="icon-remove"></i></a></div>-->
<!--    {else}-->
<!--    <h2>Фукароларнинг мурожаатлари</h2>-->
<!--    {if !empty($mess)}-->
<!--    <div class="notification error closeable" id="notification_1" style="display: block;">-->
<!--        <p><span>Error!</span> {$mess}</p>-->
<!--        <a class="close" href="#"><i class="icon-remove"></i></a>-->
<!--    </div>-->
<!--    {/if}-->
<!---->
<!--    <form action='' method='POST' class="form-horizontal">-->
<!--        <input type="hidden" name="action" value="send">-->
<!--        <div id="contact" class="form-group">-->
<!--            <mark id="message" style="display: none;"></mark>-->
<!--            <form id="contactform">-->
<!--                <fieldset>-->
<!--                    <div>-->
<!--                        <label> Ф.И.Ш :</label>-->
<!--                        <input type="text" name="fio" value="--><?php //echo $fio ?><!--"/><mark class="validate"></mark>-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <label> Сизнинг E-mail:</label>-->
<!--                        <input type="text" name="email" value="--><?php //echo $email;?><!--"><mark class="validate"></mark>-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <label> Телефон рақам:</label>-->
<!--                        <input type="text" name="phone" value="{$phone}"><mark class="validate"></mark>-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <label>Мурожаат сарлавҳаси: <span>*</span></label>-->
<!--                        <input type="text" name="title" value="{$title}"><mark class="validate"></mark>-->
<!--                        <div>-->
<!--                            <label>Мурожаат матни: <span>*</span></label>-->
<!--                            <textarea cols="40" rows="3" name="message">{$message}</textarea><mark class="validate"></mark>-->
<!--                        </div>-->
<!---->
<!--                        <div>-->
<!--                            <label>Расмдаги кодни киритинг: <span>*</span></label>-->
<!--                            {$captcha}-->
<!--                        </div>-->
<!--                </fieldset>-->
<!--                <input type="submit" class="submit" value="Жўнатиш">-->
<!--                <div class="clearfix"></div>-->
<!--            </form>-->
<!--        </div>-->
<!--        <div class="clearfix"></div>-->
<!---->
<!--    </form>-->
<!--    {/if}-->
<!--</div>-->