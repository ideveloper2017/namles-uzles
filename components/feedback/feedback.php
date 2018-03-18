<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 18.02.2016
 * Time: 18:00
 */


function get_ext($key) {
    $key=strtolower(substr(strrchr($key, "."), 1));
    $key=str_replace("jpeg", "jpg", $key);
    return $key;
}
function phattach($file, $name, $boundary) {
    $fp=fopen($file, "r");
    $str=fread($fp, filesize($file));
    $str=chunk_split(base64_encode($str));
    $message="--".$boundary."\n";
    $message.="Content-Type: application/octet-stream; name=\"".$name."\"\n";
    $message.="Content-disposition: attachment; filename=\"".$name."\"\n";
    $message.="Content-Transfer-Encoding: base64\n";
    $message.="\n";
    $message.="$str\n";
    $message.="\n";

    return $message;
}

function clean_msg($key) {
    $key=str_replace("\r", "", $key);
    $key=str_replace("\n", "", $key);
    $find=array(
        "/bcc\:/i",
        "/Content\-Type\:/i",
        "/Mime\-Type\:/i",
        "/cc\:/i",
        "/to\:/i"
    );
    $key=preg_replace($find, "", $key);
    return $key;
}

function feedback()
{

    $db = Registry::get("DataBase");
    $core = Registry::get("Core");
    $user=Registry::get("Users");
    $mail=Registry::get("PHPMailer");


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
    $cfg = $core->getComponentConfig("feedback");

    if (!$cfg['myemail']) {
        $cfg['myemail'] = 'mymail@dfgh.ru';
    }
    if (!$cfg['allowattach']) {
        $cfg['allowattach'] = '1';
    }
    $myemail = $cfg['myemail'];
    $allowattach = $cfg['allowattach'];
    session_start();

    $_SESSION['nik'] = $myemail;
    $_SESSION['ni'] = $allowattach;


    $myemail = $_SESSION['nik'];
    $allowattach = $_SESSION['ni'];
    $websitename='http://'.$_SERVER['HTTP_HOST'].'';
    $senderName  = 'http://'.$_SERVER['HTTP_HOST'].'';
    $allowtypes=array("zip", "rar", "txt", "doc", "jpg", "png", "gif", "odt", "xml");
    $priority="3";
    $max_file_size="1024";
    $max_file_total="2048";
    $submitvalue=" Хабарни жўнатиш  ";
    $resetvalue=" Формани янгилаш ";
    $defaultsubject="No Subject";
    $use_subject_drop=false;
    $subjects=array("Department 1", "Department 2", "Department 3");
    $emails=array("dept_1@domain.com", "dept_2@domain.com", "dept_3@domain.com");
    $headmessage="Здесь Вы можете с помощью модуля обратной связи написать нам письмо, а также прикрепить к нему файлы. Поля со звёздочкой обязательны для заполнения. Обратите внимание на тип  файлов и их размер.";
    $thanksmessage="Ваше электронное письмо было отправлено. Вам ответят в ближайшее время на тот электронный адрес, который Вы указали.";

    if ($core->action=='view'){
        require(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_feedback.php');
    }


    if ($core->action=='sending'){
        $error="";
        $sent_mail=false;
        extract($_POST, EXTR_SKIP);

        if(trim($yourname)=="") {
            $error.="Вы не ввели Ваши фамилию и имя!<br />";
        }

        if(trim($youremail)=="") {
            $error.="You did not enter your email!<br />";
        } elseif(!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/", $youremail)) {
            $error.="Неправильный email адрес<br />";
        }

        if(trim($emailsubject)=="") {
            $emailsubject=$defaultsubject;
        }

        if(trim($yourmessage)=="") {
            $error.="Вы не ввели сообщение!<br />";
        }

        if($allowattach > 0) {
            if((array_sum($_FILES['attachment']['size'])) > ($max_file_total*1024)) {
                $error.="Макимальный размер загружаемых файлов ".$max_file_total."kb<br />";
            } else {
                For($i=0; $i <= $allowattach-1; $i++) {

                    if($_FILES['attachment']['name'][$i]) {


                        if(!in_array(get_ext($_FILES['attachment']['name'][$i]), $allowtypes)) {

                            $error.= "Неразрешённый для загрузки тип файла: ".$_FILES['attachment']['name'][$i]."<br />";
                        } elseif(($_FILES['attachment']['size'][$i]) > ($max_file_size*1024)) {

                            $error.= "Файл: ".$_FILES['attachment']['name'][$i]." для загрузки слишком большой.<br />";

                        }

                    }

                }

            }

        }

        if($error) {

        $display_message=$error;

    } else {

            if($use_subject_drop AND is_array($subjects) AND is_array($emails)) {
                $subject_count=count($subjects);
                $email_count=count($emails);

                if($subject_count==$email_count) {

                    $myemail=$emails[$emailsubject];
                    $emailsubject=$subjects[$emailsubject];

                }

            }
            $boundary=md5(uniqid(time()));
            $headers="Return-Path: <".clean_msg($youremail).">\n";
            $headers.="From: ".clean_msg($yourname)." <".clean_msg($youremail).">\n";
            $headers.="X-Mailer: PHP/".phpversion()."\n";
            $headers.="X-Sender: ".$_SERVER['REMOTE_ADDR']."\n";
            $headers.="X-Priority: ".$priority."\n";
            $headers.="MIME-Version: 1.0\n";
            $headers.="Content-Type: multipart/mixed; boundary=\"".$boundary."\"\n";
            $headers.="This is a multi-part message in MIME format.\n";

            $message = "--".$boundary."\n";
            $message.="Content-Type: text/html; charset=\"utf-8\"\n";
            $message.="Content-Transfer-Encoding: quoted-printable\n";
            $message.="\n";
            $message.=clean_msg(nl2br(strip_tags($yourmessage)));
            $message.="\n";

            if($allowattach > 0) {
                For($i=0; $i <= $allowattach-1; $i++) {
                    if($_FILES['attachment']['tmp_name'][$i]) {
                        $message.=phattach($_FILES['attachment']['tmp_name'][$i], $_FILES['attachment']['name'][$i], $boundary);
                    }
                }
            }
            $message.="--".$boundary."--\n";
            if(!mail($myemail, clean_msg($emailsubject), $message, $headers)) {

                Exit("Случилась ошибка.Известите администратора сайта.\n");

            } else {

                $sent_mail=true;

            }

        }
        require(PATH . '/theme/' . Registry::get("Config")->template . '/components/com_feedback.php');
    }


//    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/components/feedback/inc/feedback.php';
//
//
//    echo '<script language="javascript" type="text/javascript">
//                function iframeHeight() {
//                    var h = 0;
//                    if (!document.all) {
//                        h = document.getElementById("blockrandom").contentDocument.height;
//                        document.getElementById("blockrandom").style.height = h + 60 + "px";
//                    } else if (document.all) {
//                        h = document.frames("blockrandom").document.body.scrollHeight;
//                        document.all.blockrandom.style.height = h + 20 + "px";
//                    }
//                }
//          	</script>';
//    echo '<iframe onload="iframeHeight()" id="blockrandom" src="' . $url . '" width="100%" height="500" scrolling="avto" align="top" frameborder="0" ></iframe>';
}
    ?>