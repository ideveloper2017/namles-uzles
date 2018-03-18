<?php

function redirect_to($location)
{
    if (!headers_sent()) {
        header('Location: ' . $location);
        exit;
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $location . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
        echo '</noscript>';
    }
}

function redirect($uri = '', $method = 'location', $http_response_code = 302)
{
    switch($method)
    {
        case 'refresh'	: header("Refresh:0;url=".$uri);
            break;
        default			: header("Location: ".$uri, TRUE, $http_response_code);
            break;
    }
    exit;
}

function getChecked($row, $status)
{
    if ($row == $status) {
        echo "checked=\"checked\"";
    }
}
function cleanOut($text)
{
    $text = strtr($text, array(
        '\r\n' => "",
        '\r' => "",
        '\n' => ""));
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = str_replace('<br>', '<br />', $text);
    return stripslashes($text);
}

function sanitize($string, $trim = false, $int = false, $str = false)
{
    $string = filter_var($string, FILTER_SANITIZE_STRING);
    $string = trim($string);
    $string = stripslashes($string);
    $string = strip_tags($string);
    $string = str_replace(array(
        '‘',
        '’',
        '“',
        '”'), array(
        "'",
        "'",
        '"',
        '"'), $string);

    if ($trim)
        $string = substr($string, 0, $trim);
    if ($int)
        $string = preg_replace("/[^0-9\s]/", "", $string);
    if ($str)
        $string = preg_replace("/[^a-zA-Z\s]/", "", $string);

    return $string;
}

function inArray($array, $item){

    $found = false;
    foreach($array as $key=>$value){
        if ($value == $item) {
            $found = true;
        }
    }
    return $found;

}

function truncate($str, $n = 100, $end_char = '&#8230;')
{
    if (strlen($str) < $n) {
        return $str;
    }

    $str = preg_replace("/\s+/", ' ', str_replace(array(
        "\r\n",
        "\r",
        "\n"), ' ', $str));

    if (strlen($str) <= $n) {
        return $str;
    }

    $out = "";
    foreach (explode(' ', trim($str)) as $val) {
        $out .= $val . ' ';

        if (strlen($out) >= $n) {
            $out = trim($out);
            return (strlen($out) == strlen($str)) ? $out : $out . $end_char;
        }
    }
}
?>