<?php

session_start();
$myemail = $_SESSION['nik'];
$allowattach = $_SESSION['ni'];
$websitename = 'http://' . $_SERVER['HTTP_HOST'] . '';
$senderName = 'http://' . $_SERVER['HTTP_HOST'] . '';
$allowtypes = array("zip", "rar", "txt", "doc", "jpg", "png", "gif", "odt", "xml");
$priority = "3";
$max_file_size = "1024";
$max_file_total = "2048";
$submitvalue = " Послать сообщение  ";
$resetvalue = " Обновить форму ";
$defaultsubject = "No Subject";
$use_subject_drop = false;
$subjects = array("Department 1", "Department 2", "Department 3");
$emails = array("dept_1@domain.com", "dept_2@domain.com", "dept_3@domain.com");
$headmessage = "Здесь Вы можете с помощью модуля обратной связи написать нам письмо, а также прикрепить к нему файлы. Поля со звёздочкой обязательны для заполнения. Обратите внимание на тип  файлов и их размер.";
$thanksmessage = "Ваше электронное письмо было отправлено. Вам ответят в ближайшее время на тот электронный адрес, который Вы указали.";
function get_ext($key)
{
    $key = strtolower(substr(strrchr($key, "."), 1));
    $key = str_replace("jpeg", "jpg", $key);
    return $key;
}

function phattach($file, $name, $boundary)
{
    $fp = fopen($file, "r");
    $str = fread($fp, filesize($file));
    $str = chunk_split(base64_encode($str));
    $message = "--" . $boundary . "\n";
    $message .= "Content-Type: application/octet-stream; name=\"" . $name . "\"\n";
    $message .= "Content-disposition: attachment; filename=\"" . $name . "\"\n";
    $message .= "Content-Transfer-Encoding: base64\n";
    $message .= "\n";
    $message .= "$str\n";
    $message .= "\n";

    return $message;
}

function clean_msg($key)
{
    $key = str_replace("\r", "", $key);
    $key = str_replace("\n", "", $key);
    $find = array(
        "/bcc\:/i",
        "/Content\-Type\:/i",
        "/Mime\-Type\:/i",
        "/cc\:/i",
        "/to\:/i"
    );
    $key = preg_replace($find, "", $key);
    return $key;
}


$error = "";
$sent_mail = false;

if ($_POST['submit'] == true) {
    extract($_POST, EXTR_SKIP);
    if (trim($yourname) == "") {
        $error .= "Вы не ввели Ваши фамилию и имя!<br />";
    }

    if (trim($youremail) == "") {
        $error .= "You did not enter your email!<br />";
    } elseif (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/", $youremail)) {
        $error .= "Неправильный email адрес<br />";
    }

    if (trim($emailsubject) == "") {
        $emailsubject = $defaultsubject;
    }

    if (trim($yourmessage) == "") {
        $error .= "Вы не ввели сообщение!<br />";
    }

    // Verify Attchment info
    if ($allowattach > 0) {
        if ((array_sum($_FILES['attachment']['size'])) > ($max_file_total * 1024)) {
            $error .= "Макимальный размер загружаемых файлов " . $max_file_total . "kb<br />";
        } else {
            For ($i = 0; $i <= $allowattach - 1; $i++) {
                if ($_FILES['attachment']['name'][$i]) {
                    if (!in_array(get_ext($_FILES['attachment']['name'][$i]), $allowtypes)) {
                        $error .= "Неразрешённый для загрузки тип файла: " . $_FILES['attachment']['name'][$i] . "<br />";
                    } elseif (($_FILES['attachment']['size'][$i]) > ($max_file_size * 1024)) {
                        $error .= "Файл: " . $_FILES['attachment']['name'][$i] . " для загрузки слишком большой.<br />";
                    }
                  }
            }
        }
    }
    if ($error) {
        $display_message = $error;
    } else {
        if ($use_subject_drop AND is_array($subjects) AND is_array($emails)) {
            $subject_count = count($subjects);
            $email_count = count($emails);

            if ($subject_count == $email_count) {

                $myemail = $emails[$emailsubject];
                $emailsubject = $subjects[$emailsubject];
            }
        }

        $boundary = md5(uniqid(time()));
        $headers = "Return-Path: <" . clean_msg($youremail) . ">\n";
        $headers .= "From: " . clean_msg($yourname) . " <" . clean_msg($youremail) . ">\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";
        $headers .= "X-Sender: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $headers .= "X-Priority: " . $priority . "\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\n";
        $headers .= "This is a multi-part message in MIME format.\n";

        $message = "--" . $boundary . "\n";
        $message .= "Content-Type: text/html; charset=\"utf-8\"\n";
        $message .= "Content-Transfer-Encoding: quoted-printable\n";
        $message .= "\n";
        $message .= clean_msg(nl2br(strip_tags($yourmessage)));
        $message .= "\n";

        if ($allowattach > 0) {
            for ($i = 0; $i <= $allowattach - 1; $i++) {
                if ($_FILES['attachment']['tmp_name'][$i]) {
                    $message .= phattach($_FILES['attachment']['tmp_name'][$i], $_FILES['attachment']['name'][$i], $boundary);
                }
            }
        }

        $message .= "--" . $boundary . "--\n";
        if (!mail($myemail, clean_msg($emailsubject), $message, $headers)) {
            exit("Случилась ошибка.Известите администратора сайта.\n");
        } else {
            $sent_mail = true;
        }

    }

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Language" content="en-us"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $websitename; ?> - Powered By phMailer</title>

    <style type="text/css">
        body {
            background-color: #FFFFFF;
            font-family: Verdana, Arial, sans-serif;
            font-size: 12pt;
            color: #000000;
        }

        .error_message {
            font-family: Verdana, Arial, sans-serif;
            font-size: 11pt;
            color: #FF0000;
        }

        .thanks_message {
            font-family: Verdana, Arial, sans-serif;
            font-size: 11pt;
            color: #000000;
        }

        a:link {
            text-decoration: none;
            color: #000000;
        }

        a:visited {
            text-decoration: none;
            color: #000000;
        }

        a:hover {
            text-decoration: none;
            color: #000000;
        }

        .table {
            border-collapse: collapse;
            border: 1px solid #000000;
            width: 500px;
        }

        .table_header {
            border: 1px solid #070707;
            background-color: #C03738;
            font-family: Verdana, Arial, sans-serif;
            font-size: 11pt;
            font-weight: bold;
            color: #FFFFFF;
            text-align: center;
            padding: 2px;
        }

        .attach_info {
            border: 1px solid #070707;
            background-color: #EBEBEB;
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #000000;
            padding: 4px;
        }

        .table_body {
            border: 1px solid #070707;
            background-color: #EBEBEB;
            font-family: Verdana, Arial, sans-serif;
            font-size: 10pt;
            color: #000000;
            padding: 2px;
        }

        .table_footer {
            border: 1px solid #070707;
            background-color: #C03738;
            text-align: center;
            padding: 2px;
        }

        input, select, textarea {
            font-family: Verdana, Arial, sans-serif;
            font-size: 10pt;
            color: #000000;

            border: 1px solid #000000;
        }

        .copyright {
            border: 0px;
            font-family: Verdana, Arial, sans-serif;
            font-size: 9pt;
            color: #000000;
            text-align: right;
        }

        form {
            padding: 0px;
            margin: 0px;
        }
    </style>

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

</head>
<body>

<? if ($display_message) { ?>

    <div align="center" class="error_message"><b><?= $display_message; ?></b></div>


<? } ?>

<? if($sent_mail!=true) {?>
    </br>
	<div align="center" class="thanks_message"><?=$headmessage;?></div>
	</br>
<form method="post" action="<?=$_SERVER['PHP_SELF'];?>" enctype="multipart/form-data" name="phmailer" onsubmit="return Checkit(this);">
<table align="center" class="table">
	<tr>
		<td colspan="2" class="table_header" width="100%"><?=$websitename;?></td>
	</tr>
	<?if($allowattach > 0) {?>
		<tr>
			<td width="100%" class="attach_info" colspan="2">
				<b>Разрешённые типы файлов:</b> <?=implode($allowtypes, ", ");?><br />
				<b>Максимальный размер одного прикрепляемого файла:</b> <?=$max_file_size?>kb.<br />
				<b>Максимальный размер всех прикрепляемых файлов:</b> <?=$max_file_total?>kb.
			</td>
		</tr>
	<?}?>
	
	<tr>
		<td width="30%" class="table_body">Ваши фамилия и имя:</td>
		<td width="70%" class="table_body"><input name="yourname" type="text" size="30" value="<?=stripslashes(htmlspecialchars($yourname));?>" /><span class="error_message">*</span></td>
	</tr>
	<tr>
		<td width="30%" class="table_body">Ваш Email:</td>
		<td width="70%" class="table_body"><input name="youremail" type="text" size="30" value="<?=stripslashes(htmlspecialchars($youremail));?>" /><span class="error_message">*</span></td>
	</tr>
	<tr>
		<td width="30%" class="table_body">Тема:</td>
		<td width="70%" class="table_body">
		
			<?if($use_subject_drop AND is_array($subjects)) {?>
					<select name="emailsubject" size="1">
						<?while(list($key,$val)=each($subjects)) {?>

							<option value="<?=intval($key);?>"><?=htmlspecialchars(stripslashes($val));?></option>
						
						<?}?>
					</select>
				
			
			<?} else {?>
				
				<input name="emailsubject" type="text" size="30" value="<?=stripslashes(htmlspecialchars($emailsubject));?>" />
				
			<?}?>
			
		</td>
	</tr>

	<?For($i=1;$i <= $allowattach; $i++) {?>
		<tr>
			<td width="30%" class="table_body">Прикрепить файл:</td>
			<td width="70%" class="table_body"><input name="attachment[]" type="file" size="30" /></td>
		</tr>
	<?}?>
	
	<tr>
		<td colspan="2" width="100%" class="table_body">Ваше сообщение:<span class="error_message">*</span><br />
			<div align="center">
				<textarea name="yourmessage" rows="8" cols="60"><?=stripslashes(htmlspecialchars($yourmessage));?></textarea>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" width="100%" class="table_footer">
			<input type="hidden" name="submit" value="true" />
			<input type="submit" value="<?=$submitvalue;?>" /> &nbsp;
			<input type="reset" value="<?=$resetvalue;?>" />
		</td>
	</tr>
</table>
</form>

<?} else {?>
</br>
	<div align="center" class="thanks_message"><?=$thanksmessage;?></div>
</br>	
</br>
</br>


<center><img src="/modules/mod_feedback/12.gif" title="Сами видите, что почтальон уже несёт к нам Ваше письмо." /></center>

<?}
?>

</body>
</html>
