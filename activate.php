<?php
define('CURSCRIPT', 'activate');
require_once ('./include/common.inc.php');

$T->load('{{ include("templates/default/activate.tpl") }}');


$activate=antixss($_GET['code']);
$e_hash = addslashes(pack("H*", $activate));
$activate_hash = bin2hex($e_hash);
if ($e_hash){
$params = array(":activate|$e_hash|STR");
$rows = $db->query_secure("SELECT title, ban FROM xbt_torrents WHERE tor_hash =:activate;", $params, true, false);
if($rows!=false){
foreach($rows as $row){
$title = antixss($row["title"]);
$ban = antixss($row["ban"]);
if ($ban!=0){
$T->block('/title', array('title' => $title), TRUE);  
}

} 
}
//if null
$rows = null;
}

if (isset($_POST['activate'])) { 
    try
    {
        // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
        NoCSRF::check( 'token', $_POST, true, 60*10, false );
        // form parsing, DB inserts, etc.
$activatecode=antixss($_POST['activatecode']);
$e_hash = addslashes(pack("H*", $activatecode));
$db->query("UPDATE xbt_torrents SET ban='0' WHERE tor_hash='$e_hash'");
        //$result = 'CSRF check passed. Form parsed.';
        //echo   $result;
//Добавление в базу торрентов
$params = array(":info_hash|$e_hash|STR", ":mtime|$timestamp|INT", ":ctime|$timestamp|INT");
$result = $db->query_secure("INSERT INTO xbt_files (info_hash,mtime,ctime) VALUES(:info_hash,:mtime,:ctime);", $params, false, false);

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
$T->block('/token', array('token' =>$token, 'activate' =>$activate_hash), TRUE);
$db->disconnect();
$T->display();
?>







