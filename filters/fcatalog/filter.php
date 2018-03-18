
<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 14.02.2016
 * Time: 19:25
 */



function f_fcatalog(&$text){
    //REPLACE FILE DOWNLOAD LINKS

    $regex = '/{(КАТАЛОГФАЙЛ=)\s*(.*?)}/i';
    $matches = array();
    preg_match_all( $regex, $text, $matches, PREG_SET_ORDER );
    foreach ($matches as $elm) {
        $elm[0] = str_replace('{', '', $elm[0]);
        $elm[0] = str_replace('}', '', $elm[0]);
        parse_str( $elm[0], $args );
        $file=@$args['КАТАЛОГФАЙЛ'];
        if ($file){
            $output = getlinkByTitle($file);
        } else { $output = ''; }
        $text = str_replace('', $output, $text );

    }

    return true;
}
?>