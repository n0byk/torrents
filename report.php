<?php
define('CURSCRIPT', 'report');
require_once ('./include/common.inc.php');


$T->load('{{ include("templates/default/report.tpl") }}');
//Установка титлов
$T->set(array('title' => 'Это тестовая страница торрента'));


$tid=is_numder(isset($_GET['tid']));

if ($tid){
$params = array(":tid|$tid|INT");
$rows = $db->query_secure("SELECT title, tid FROM xbt_torrents WHERE tid =:tid;", $params, true, false);
if($rows!=false){
foreach($rows as $row){
$title = antixss($row["title"]);
$tid = intval($row["tid"]);
$T->block('/tortitle', array('title' => $title, 'tid' => $tid), TRUE);  
} 
}
//if null
$rows = null;
}
if (isset($_POST['report'])) { 
    try
    {
        // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
        NoCSRF::check( 'token', $_POST, true, 60*10, false );
        // form parsing, DB inserts, etc.
$description=$jevix->parse($_POST['description'], $errors);
$reason=$jevix->parse($_POST['reason'], $errors);
//Добавление в базу торрентов
$params = array(":tid|$tid|INT", ":description|$description|STR", ":reason|$reason|STR", ":time|$timestamp|STR");
$result = $db->query_secure("INSERT INTO xbt_report (tid,description,reason,time) VALUES(:tid,:description,:reason,:time);", $params, false, false);

}
    catch ( Exception $e )
{
        // CSRF attack detected
header( 'Location: /', true, 301 );
exit;
    }
}
// Generate CSRF token to use in form hidden field
$token = NoCSRF::generate( 'token' );
$T->block('/token', array('token' =>$token), TRUE);
$db->disconnect();
$T->display();
?>