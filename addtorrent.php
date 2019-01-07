<?php
define('CURSCRIPT', 'addtorrent');
require_once ('./include/common.inc.php');
//torrent lib.
require_once ('./include/torrent/torrent.php');
require_once ('./include/torrent/bencode.php');
//img.class.php
require_once ('./include/classes/img.class.php');
//category
require_once('./ajax/category.php');
logger( "Failed to read torrent file. ID: ".$torrent_file."",CURSCRIPT );
$T->load('{{ include("templates/default/addtorrent.tpl") }}');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$T->set(array('title' => 'Это тестовая страница торрента'));
// возвращаем список городов
if ($action == 'getCity')
{
    if (isset($categorylist[$_GET['region']]))
    {
        echo json_encode($categorylist[$_GET['region']]); // возвраащем данные в JSON формате;
    }
    else
    {
        echo json_encode(array('Выберите область'));
    }

    exit;
}
foreach ($categorylist as $region => $cityList)
{
    $T->block('/category', array('id_cat' =>$region, 'name' =>$region), TRUE);
}
//category off

if (isset($_POST['addtorrent']) && !$resp->is_valid) { 
    try
    {
        // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
        NoCSRF::check( 'token', $_POST, true, 60*10, false );
        // form parsing, DB inserts, etc.
        $result = 'CSRF check passed. Form parsed.';
        $T->block('/errors', array('result' =>$result ), TRUE);

// Парсим POST
$tname = $jevix->parse($_POST['tname'], $errors);
//$category = $jevix->parse($_POST['category']);
$category = $jevix->parse($_POST['subcategory'], $errors);
$email=is_email($_POST['email']);
if (!$email){
header( 'Location: /', true, 301 );
exit;}
else {
$email=$_POST['email'];
}
$description = $jevix->parse($_POST['description'], $errors);
$rules=intval($_POST['rules']);
if (!$tname or !$description or !$rules){
header( 'Location: /', true, 301 ); 
exit;
}


if ( !empty($_FILES['torrent']) ) {
    if ( !empty($_FILES['torrent']) && empty($_FILES['torrent']['error']) && file_exists($_FILES['torrent']['tmp_name']) ) {
        $torrent_file = $_FILES['torrent']['tmp_name'];
        $save_as_file = $_FILES['torrent']['name'];

// Load the .torrent file contents. This could be done via file upload.
// Example loads a local file specified in $torrent_file
$data = file_get_contents($torrent_file);
if ($data == false) {
    //exit("Failed to read from $torrent_file.");
logger( "Failed to read torrent file. ID: ".$torrent_file."",CURSCRIPT );
header( 'Location: /error.php?key=404', true, 301 );
}


// Create a torrent object
$torrent = new Torrent();

// Load torrent file data obtained above 
if ($torrent->load($data) == false) {
    //exit("An error occured: {$torrent->error}");
logger( "An error occured: ".$torrent->error."",CURSCRIPT );
header( 'Location: /error.php?key=404', true, 301 );
}

// Add the new tracker to the torrent
$torrent->addTracker('http://localhost:2710/announce');
$torrent->setComment('Trololo');
$torrent->setCreatedBy('tlalala');

//размер всех файлов
$files = $torrent->getFiles();
$size = 0;
foreach ($files as $file) {
$size += $file->length;
}
// Get the hash
$hash = $torrent->getHash();
$e_hash = addslashes(pack("H*", $hash));
//echo "$e_$hash";
//upload img
$handle = new upload($_FILES['torrentimg']);
if ($handle->uploaded) {
    $handle->file_new_name_body   = $hash;
    $handle->image_convert = jpg;
    $handle->image_resize         = true;
    $handle->image_x              = 300;
    $handle->image_ratio_y        = true;
	$handle->process($_SERVER['DOCUMENT_ROOT'].'/data/timg/');

    if ($handle->processed) {
        //echo 'image resized';
        $handle->clean();
    } else {
        echo 'error : ' . $handle->error;
		
    }
}
//upload img  OFF


// Save the modified torrent to $save_as_file
$data = $torrent->bencode();
if (file_put_contents("$tordir$hash.torrent", $data) == false) {
logger( "Failed to write to: ".$save_as_file."",CURSCRIPT );
exit("Failed to write to $save_as_file.");
}
//$torinfo = array(":info_hash|$e_hash|STR", ":mtime|$timestamp|INT", ":ctime|$creatdate|INT");
//$inserttor = $db->query_secure("INSERT INTO xbt_files (info_hash,mtime,ctime) VALUES(:info_hash,:mtime,:ctime);", $torinfo, false, false);
}}

$torparams = array(":tor_hash|$e_hash|STR",":title|$tname|STR", ":description|$description|STR", ":category|$category|STR", ":addip|$onlineip|STR", ":addtime|$timestamp|INT", ":email|$email|STR", ":ban|1|INT", ":size|$size|INT");
$result = $db->query_secure("INSERT INTO xbt_torrents (tor_hash,title,description,category,addip,addtime,email,ban,size) VALUES(:tor_hash,:title,:description,:category,:addip,:addtime,:email ,:ban ,:size);", $torparams, false, false);
$db->disconnect();

if ($result){
sendmail($email,"Add Torrent Activate","<a href='http://localhost/torrent/activate.php?code=".$hash."'>This activation code</a>");
}


}
    catch ( Exception $e )
    {
        // CSRF attack detected
        $result = $e->getMessage() . ' Form ignored.';
        $T->block('/errors', array('result' =>$result ), TRUE);
    }
}
else
{
    $result = 'No post data yet.';
    $T->block('/errors', array('result' =>$result ), TRUE);
}
$captcha=recaptcha_get_html($publickey);
$T->block('/captcha', array('captcha' =>$captcha ), TRUE);
$token = NoCSRF::generate( 'token' );
$T->block('/token', array('token' =>$token ), TRUE);
//echo $T->parse();
$T->display();
?>
