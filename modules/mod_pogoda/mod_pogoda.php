<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 02.01.2016
 * Time: 15:40
 */
function mod_pogoda($id)
{
    $db = Registry::get("DataBase");
    $menu = Registry::get("Menus");
    $users = Registry::get("Users");
    $core = Registry::get("Core");
    $langID = Lang::getLangID();
    $config = Registry::get("Config");
    $cfg = $core->getModuleConfig($id);
    $cfg['city'] = $cfg['city']?$cfg['city']:'28674';

    $pogoda = array();
    $data_file = 'http://export.yandex.ru/weather-ng/forecasts/' . $cfg['city'] . '.xml';
    $xml = simplexml_load_file($data_file);
    $pogoda['data'] = $xml->day[1]['date'];

    $pogoda['temperature'] = $xml->day[1]->day_part[1]->temperature;
    if (!$pogoda['temperature']) {
        $pogoda['temperature'] = $xml->day[1]->day_part[1]->temperature_from . '...' . $xml->day[1]->day_part[1]->temperature_to;
    }

    $pogoda['temperature_s'] = explode('.', $pogoda['temperature']);
    $pogoda['temperature_s'] = $pogoda['temperature_s'][0];
    $pogoda['morning'] = $xml->day[1]->day_part[0]->temperature;
    if (!$pogoda['morning']) {
        $pogoda['morning'] = $xml->day[1]->day_part[0]->temperature_from . '...' . $xml->day[1]->day_part[0]->temperature_to;
    }

    $pogoda['evening'] = $xml->day[1]->day_part[2]->temperature;
    if (!$pogoda['evening']) {
        $pogoda['evening'] = $xml->day[1]->day_part[2]->temperature_from . '...' . $xml->day[1]->day_part[2]->temperature_to;
    }

    $pogoda['img1'] = $xml->day[1]->day_part[0]->{'image-v3'};
    $pogoda['img2'] = $xml->day[1]->day_part[1]->{'image-v3'};
    $pogoda['img3'] = $xml->day[1]->day_part[2]->{'image-v3'};

    $pogoda['alt1'] = $xml->day[1]->day_part[0]->weather_type;
    $pogoda['alt2'] = $xml->day[1]->day_part[1]->weather_type;
    $pogoda['alt3'] = $xml->day[1]->day_part[2]->weather_type;

    $v_file = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp?");

    $valuta = array();

    foreach ($v_file as $el) {
        $valuta[strval($el->CharCode)] = strval($el->Value);
    }
//    print_r($pogoda);
    include(PATH . '/theme/' . Registry::get("Config")->template . '/modules/mod_pogoda.php');
    return true;
}

?>