                           
<?php

function showresults($poll_id){
    $db=DB::getInstance();
    $query=$db->query("SELECT COUNT(*) as totalvotes FROM votes WHERE option_id IN(SELECT id FROM options WHERE ques_id='$poll_id')");
    while($row=$db->fetch_assoc($query))
        $total=$row['totalvotes'];
    $query=$db->query("SELECT options.id, options.value, COUNT(*) as votes FROM votes, options WHERE votes.option_id=options.id AND votes.option_id IN(SELECT id FROM options WHERE ques_id='$poll_id') GROUP BY votes.option_id");
    while($row=$db->fetch_assoc($query)){
        $percent=round(($row['votes']*100)/$total);
        echo '<div class="option" ><p>'.$row['value'].' (<em>'.$percent.'%, '.$row['votes'].' votes</em>)</p>';
        echo '<div class="bar ';
        if($_POST['poll']==$row['id']) echo ' yourvote';
        echo '" style="width: '.$percent.'%; " ></div></div>';
    }
    echo '<p>Total Votes: '.$total.'</p>';
}

function mod_polls($id){

    $db=DB::getInstance();
    $ker=Engine::getInstance();
    $langID=getLangID();
    $cfg=getModuleConfig($id);

//    if ($cfg['pollid']!=0){
//        $sql='';
//    }else{
//        $sql='SELECT id, ques FROM polls ORDER BY id DESC LIMIT 1';
//    }

    if (!$_POST['poll'] || !$_POST['pollid']){
    $sql="SELECT id, ques FROM polls ORDER BY RAND() LIMIT 1";
    $result=$db->query($sql);
    $polls=array();
    $pollI=0;
    while ($poll=$db->fetch_assoc($result)){
        $pollI++;
        $polls[$pollI]['id']=$poll['id'];
        $polls[$pollI]['ques']=$poll['ques'];
        $poll_id=$poll['id'];
    }
     if ($_GET["result"]==1 || $_COOKIE["voted".$poll_id]=='yes'){
         showresults($poll_id);
         exit;
     }else {
    $res=$db->query("SELECT id, value FROM options WHERE ques_id='{$poll_id}'");
    $options=array();
    $poolII=0;
    if ($db->num_rows($res)){

    while ($opt=$db->fetch_assoc($res)){
        $poolII++;
        $options[$poolII]['id']=$opt['id'];
        $options[$poolII]['value']=$opt['value'];

    }
     }
     }

}else{
        if($_COOKIE["voted".$_POST['pollid']]!='yes'){
            $query=$db->query("SELECT * FROM options WHERE id='".intval($_POST["poll"])."'");
            if($db->num_rows($query)){
                $query="INSERT INTO votes(option_id, voted_on, ip) VALUES('".$_POST["poll"]."', '".date('Y-m-d H:i:s')."', '".$_SERVER['REMOTE_ADDR']."')";
                if($db->query($query))
                {
                    setcookie("voted".$_POST['pollid'], 'yes', time()+86400*300);
                }
                else
                    echo "There was some error processing the query: ".mysql_error();
            }
        }
        showresults(intval($_POST['pollid']));
    }
//    print_r($options);

    $smarty = $ker->smartyInitModule();
//    $smarty->assign("polls",$polls);
//    $smarty->assign("options",$options);
//    $smarty->assign('pool_id',$poll_id);
//    $smarty->assign("self",$_SERVER['PHP_SELF']);
    $smarty->display('mod_polls.tpl');


return true;
}


?>                        