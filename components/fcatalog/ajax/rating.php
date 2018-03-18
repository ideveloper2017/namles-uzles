<?php
/**
 * Created by PhpStorm.
 * User: iDeveloper
 * Date: 17.02.2016
 * Time: 2:28
 */
if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }
session_start();
define("BPA_CMS", true);
define("PATH", $_SERVER['DOCUMENT_ROOT']);
require ("../../../init.php");

if (!isset($_REQUEST['vote'])) { die(1); }
if (!isset($_REQUEST['item_id'])) { die(2); }
define('PATH', $_SERVER['DOCUMENT_ROOT']);

$inCore=Registry::get("Core");
$inUser = Registry::get("Users");
$inDB   = Registry::get("DataBase");

$user_id = $inUser->userid;
$vote= $_REQUEST['vote'];
$item_id = $_REQUEST['item_id'];

$is_vote = $inDB->getFieldById('vote','files_rating',"f_id={$item_id} AND user_id={$user_id}");
if (!$is_vote) {
    $add =  $inDB->query("INSERT INTO files_rating (f_id, user_id, vote) VALUES ('$item_id','$user_id','$vote')"); }

$votes = $inDB->rows_count('files_rating',"f_id={$item_id}");
$total =	$inDB->query("SELECT SUM(vote) as votes FROM files_rating WHERE f_id={$item_id}");
$rat = $inDB->fetch($total,true);
$rating = round( ($rat['votes'] /  $votes), 0 );
$ratform = '<ul class="voting">
		<li class="one"><a href="#" title="плохо" onclick="return false;" ';  if ($rating ==1) {$ratform .= 'class="cur"';}
$ratform .='>1</a></li>
		<li class="two"><a href="#" title="приемлимо" onclick="return false;" '; if ($rating ==2) {$ratform .= 'class="cur"';}
$ratform .= '>2</a></li>
		<li class="three"><a href="#" title="нормально" onclick="return false;" ';  if ($rating ==3) {$ratform .= 'class="cur"';}
$ratform .= '>3</a></li>
		<li class="four"><a href="#" title="хорошо" onclick="return false;" ';  if ($rating ==4) {$ratform .= 'class="cur"';}
$ratform .= '>4</a></li>
		<li class="five"><a href="#" title="отлично" onclick="return false;" '; if ($rating ==5) {$ratform .= 'class="cur"';}
$ratform .= '>5</a></li>
	</ul> <span>('.$votes.' голосов)</span>';

echo $ratform;

?>