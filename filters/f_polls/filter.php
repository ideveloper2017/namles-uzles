<?php

function getpoll($pollid){
    $inCore = cmsCore::getInstance();

    cmsCore::loadModel('instpolls');
    $model = new cms_model_instpolls();
    cmsCore::loadLanguage('components/instpolls');
    global $_LANG;

    $poll   = $model->getPollFilter($pollid);
    if(!$poll){ return true; }
    return  $poll;
}

function f_polls(&$text){



    $regex = '/{(ОПРОС=)\s*(.*?)}/i';

    $matches = array();
    preg_match_all( $regex, $text, $matches, PREG_SET_ORDER );
    foreach ($matches as $elm) {
        $elm[0] = str_replace('{', '', $elm[0]);
        $elm[0] = str_replace('}', '', $elm[0]);
        mb_parse_str( $elm[0], $args );
        $poll=@$args['ОПРОС'];
        if ($poll){
            $output = getpoll($poll);
        } else { $output = 'нет ';
        }
        $text = str_replace('{ОПРОС='.$poll.'}', $output, $text );
    }



    return true;

}
?>