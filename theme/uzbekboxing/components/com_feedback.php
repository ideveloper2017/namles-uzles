<script type="text/javascript">
    var error = "";
    e_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/;

    function Checkit(theform) {
        if (theform.yourname.value == "") {
            error += "Вы не ввели своё имя\n";
        }

        if (theform.youremail.value == "") {
            error += "Вы не ввели свой email\n";
        } else if (!e_regex.test(theform.youremail.value)) {
            error += "Неправильный email-адрес\n";
        }

        if (theform.yourmessage.value == "") {
            error += "Вы не ввели Ваше сообщение\n";
        }

        if (error) {
            alert('**При заполнении формы были допушены  ошибки, которые необходимо исправить:**\n\n' + error);
            error = "";
            return false;
        } else {
            return true;
        }
    }
</script>

<?php if ($display_message) { ?>
    <div align="center" class="error_message"><b><?= $display_message; ?></b></div>
<?php } ?>

<?php if($sent_mail!=true) {?>
    <div id="map" style="height:600px;width:100%;margin-top:30px;"></div>

    <div class="col-md-8 col-md-offset-2">
        <form  id="contactform" class="flat-contact-form" method="post"  enctype="multipart/form-data"  onsubmit="return Checkit(this);" novalidate="novalidate">
            <div class="quick-appoinment">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" id="name" name="name" class="input-text-name" placeholder="Ф.И.О" required="required">
                    </div><!-- /.col-md-6 -->
                    <div class="col-md-6">
                        <input type="text" id="emailsubject" name="emailsubject" class="input-text-email" placeholder="Email" required="required">
                    </div><!-- /.col-md-6 -->
                </div><!-- /.row -->

                <div class="flat-divider d30px"></div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="text" id="subject" name="subject" class="input-text-subject" placeholder="Мавзу" required="required">
                    </div><!-- /.col-md-6 -->
                </div><!-- /.row -->

                <div class="flat-divider d30px"></div>

                <div class="row">
                    <div class="col-md-12">
                        <textarea class="textarea-question" id="yourmessage" name="yourmessage" placeholder="Хабар" required="required"></textarea>
                    </div><!-- /.col-md-12 -->
                </div><!-- /.row -->

                <div class="flat-divider d26px"></div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" name="submit" value="<?php echo $submitvalue;?>" class="input-submit">
                    </div><!-- /.col-md-12 -->
                </div><!-- /.row -->
            </div>
        </form>
    </div><!-- /.col-md-8 -->
    <!--    <form id="contactform" method="post"  enctype="multipart/form-data"  onsubmit="return Checkit(this);" novalidate="novalidate">-->
<!--        <div class="quick-appoinment">-->
<!--            <div class="row">-->
<!--                <div class="col-md-6">-->
<!--                    <input type="text" id="name" name="yourname" class="input-text-name" placeholder="Исмингиз" required="required" value="--><?//=stripslashes(htmlspecialchars($yourname));?><!--">-->
<!--                </div>-->
<!--                <div class="col-md-6">-->
<!--                    <input type="text" id="email" name="youremail" class="input-text-email" placeholder="Email" required="required" value="--><?//=stripslashes(htmlspecialchars($youremail));?><!--">-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="flat-divider d30px"></div>-->
<!---->
<!--            <div class="row">-->
<!--                <div class="col-md-12">-->
<!--                    <textarea class="textarea-question" id="message" name="message" placeholder="Хабар" required="required"></textarea>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="flat-divider d30px"></div>-->
<!--            <div class="row">-->
<!---->
<!--                <div class="col-sm-12" >-->
<!--                    --><?php //if($use_subject_drop AND is_array($subjects)) {?>
<!--                        <select name="emailsubject" size="1">-->
<!--                            --><?php //while(list($key,$val)=each($subjects)) {?>
<!--                                <option value="--><?//=intval($key);?><!--">--><?//=htmlspecialchars(stripslashes($val));?><!--</option>-->
<!--                            --><?php //}?>
<!--                        </select>-->
<!--                    --><?php //} else {?>
<!--                        <input name="emailsubject" type="text" class="form-control" placeholder="Мавзу" value="--><?//=stripslashes(htmlspecialchars($emailsubject));?><!--" />-->
<!--                    --><?//}?>
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="flat-divider d26px"></div>-->
<!---->
<!--            <div class="row">-->
<!--                <div class="col-md-12">-->
<!--                    <input type="submit" value="--><?//=$submitvalue;?><!--"  class="input-submit">-->
<!--                    <input type="reset" class="btn btn-danger" value="--><?//=$resetvalue;?><!--" />-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </form>-->

<!--<form method="post"  enctype="multipart/form-data"  onsubmit="return Checkit(this);">-->
<!--	<div class="form-group">-->
<!--		<label class="col-sm-2 control-label" >Ф.И.О <span class="error_message">*</span>:</label>-->
<!---->
<!--		<div class="col-sm-10" >-->
<!--            <input name="yourname" class="form-control input-lg" type="text"  value="--><?//=stripslashes(htmlspecialchars($yourname));?><!--" />-->
<!--            </div>-->
<!--	</div>-->
<!---->
<!--	<div class="form-group">-->
<!--		<label class="control-label col-sm-3">E-mail <span class="error_message">*</span>:</label>-->
<!--		<div class="col-sm-4" ><input name="youremail" type="text" class="form-control" value="--><?//=stripslashes(htmlspecialchars($youremail));?><!--" /></div>-->
<!--	</div>-->
<!--	<div class="form-group">-->
<!--		<label class="col-sm-3 control-label">Мавзу<span class="error_message">*</span>:</label>-->
<!--		<div class="col-sm-4" >-->
<!--			--><?php //if($use_subject_drop AND is_array($subjects)) {?>
<!--					<select name="emailsubject" size="1">-->
<!--						--><?php //while(list($key,$val)=each($subjects)) {?>
<!--							<option value="--><?//=intval($key);?><!--">--><?//=htmlspecialchars(stripslashes($val));?><!--</option>-->
<!--						--><?php //}?>
<!--					</select>-->
<!--			--><?php //} else {?>
<!--				<input name="emailsubject" type="text" class="form-control" value="--><?//=stripslashes(htmlspecialchars($emailsubject));?><!--" />-->
<!--			--><?//}?>
<!--		</div>-->
<!--	</div>-->
<!--    <div class="form-group">-->
<!--	--><?php //for($i=1;$i <= $allowattach; $i++) {?>
<!--			<label class="col-sm-3 control-label">Файл иловаси:</label>-->
<!--			<div class="col-sm-4"><input name="attachment[]" type="file" size="50" class="form-control"/></div>-->
<!--	--><?//}?>
<!--    </div>-->
<!--    <div class="form-group">-->
<!--		<label class="col-sm-3 control-label">Хабар <span class="error_message">*</span>:</label>-->
<!--			<div class="col-sm-4">-->
<!--				<textarea name="yourmessage" class="form-control" rows="8" cols="60">--><?//=stripslashes(htmlspecialchars($yourmessage));?><!--</textarea>-->
<!--			</div>-->
<!---->
<!--	</div>-->
<!--	<div class="form-actions text-center">-->
<!--			<input type="hidden"  name="action" value="sending" />-->
<!--			<input type="submit" class="btn btn-primary" value="--><?//=$submitvalue;?><!--" /> &nbsp;-->
<!--			<input type="reset" class="btn btn-danger" value="--><?//=$resetvalue;?><!--" />-->
<!---->
<!--	</div>-->
<!---->
<!--</form>-->

<?php } else {?>
</br>
<div align="center" class="thanks_message"><?=$thanksmessage;?></div>
</br>
</br>
</br>
<?php }
?>