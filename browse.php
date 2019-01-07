<?php
define('CURSCRIPT', 'browse');
require_once ('./include/common.inc.php');
//torrent lib.
require_once ('./include/torrent/torrent.php');
require_once ('./include/torrent/bencode.php');
//cache
require_once ('./include/classes/pageszdr.class.php');
$T->load('{{ include("templates/default/browse.tpl") }}');

$act=antixss($_GET['act']);
$torid=is_numder($_GET['tid']);
$leechers=intval(abs($_GET['le']));
$seeders=intval(abs($_GET['se']));

if ($torid){
$params = array(":id|$torid|INT");
$rows = $db->query_secure("SELECT tor_hash, title, description, addtime, clickcount, size FROM xbt_torrents WHERE tid=:id;", $params, true, false);
if($rows!=false){
	foreach($rows as $row){
$tor_hash = bin2hex($row["tor_hash"]);
$title = $jevix->parse($row["title"], $errors);
$description = $jevix->parse($row["description"], $errors);
$clickcount=intval($row["clickcount"]);
$addtime=intval($row["addtime"]);
$size =intval($row["size"]);
//Установка титлов
$T->set(array('title' => $title));
//$T->setGlobal(array('global' => $title));


$T->block('/torrents', array('tid' => $torid, 'tor_hash' => $tor_hash, 'title' => $title, 'description' => $description, 'leechers' => $leechers, 'seeders' => $seeders, 'peers' => $leechers + $seeders, 'addtime' => $addtime, 'size' => file_size($size) ,'clickcount' => $clickcount), TRUE);
} 
//счетчик просмотров
$db->query("UPDATE xbt_torrents SET clickcount=clickcount+1 WHERE tid=$torid;");
$db->disconnect();


/*$file = new download("$tordir$tor_hash.torrent", $title.torrent, 1, 500);
//$file->download_file();
  $path = "/some/long/path/to/the/special_file.txt";  
    $filename1 = basename($path); // special_file.txt  
    $filename2 = basename($path, ".txt"); // special_file
	*/
switch($act){
case "download" : 
$scr=str_pad($tor_hash, 48, ".torrent");
downloadFile($scr,$title,'torrent');
break;
case "twit" : 
//твиттер пост
break;
default: 
//ХЗ Чо!
;	
}



// Load the .torrent file contents. This could be done via file upload.
// Example loads a local file specified in $torrent_file
$data = file_get_contents("$tordir$tor_hash.torrent");
if ($data == false) {
logger( "Failed to read torrent file. ID: " .$torid. "",CURSCRIPT );
header( 'Location: /error.php?key=404', true, 301 );
//exit("Failed to read from $torrent_file");
}
//echo downloadFile($tor_hash,"dsfsdfd.torrent");
// Create a torrent object
$torrent = new Torrent();

// Load torrent file data obtained above 
if ($torrent->load($data) == false) {
//exit("An error occured: {$torrent->error}");
logger( "An error occured: ".$torrent->error."",CURSCRIPT );	
header( 'Location: /error.php?key=404', true, 301 );
}
// Add the new tracker to the torrent
$files = $torrent->getFiles();
// Loop through the trackers and display them
//Debug::superjam($torrent->getFiles(length));

$count= 0;
foreach ($files as $file) {
$count++;
$T->block('/file_list', array('count' => $count,'name' => $file->name, 'length' => file_size($file->length)), TRUE);
}

// Add the new tracker to the torrent
$trackers = $torrent->getTrackers();
// Loop through the trackers and display them
$countt = 0;
foreach ($trackers as $tracker) {
    $countt++;
$T->block('/tracker', array('countt' => $countt,'tracker' => $tracker), TRUE);  
}
// Display metadata again
$T->block('/metadata', array('date' => $torrent->getCreationDate(),'comment' => $torrent->getComment(),'created' => $torrent->getCreatedBy(),'length' => file_size($torrent->getPieceLength())), TRUE);  

$magnet = create_magnet($title, $size, $tor_hash,"Localhost.ru, test.com lolal.tr");
$T->block('/torrents/magnet', array('magnet' => $magnet), TRUE); 
}
//else
$rows = null;
}
// Generate CSRF token
$token = NoCSRF::generate( 'token' );
$T->block('/token', array('token' =>$token), TRUE);

$T->display();
//cache
//$cacheZDR->pageFooter();
?>