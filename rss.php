<?php
define('CURSCRIPT', 'rss');
require_once ('./include/common.inc.php');
$T = new Blitz();
$T->load('{{ include("templates/default/rss.tpl") }}');



$T->display();

?>