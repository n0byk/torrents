<?php
define('CURSCRIPT', 'error');
require_once ('./include/common.inc.php');

$T->load('{{ include("templates/default/error.tpl") }}');
$key=antixss($_GET['key']);

switch($key){
case "404" :
$T->block('/inform', array('title' => $title, 'tid' => $tid), TRUE);
break;
case "twit" : 
//твиттер пост
break;
default: 
//ХЗ Чо!
;	
}




$T->display();
?>