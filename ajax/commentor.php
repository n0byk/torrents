<?php
define('CURSCRIPT', 'commentor');
require_once ('../include/common.inc.php');

if ( isset( $_POST[ 'commenter' ] ) )
{

// Парсим POST
$comment = $jevix->parse($_POST['comtext'], $errors);
$email=$_POST['email'];

echo $email;
/*$email=is_email($_POST['email']);
if (!$email){
header( 'Location: /', true, 301 );
exit;}
else {
$email=$_POST['email'];
}*/
$torparams = array(":comment|$comment|STR",":email|$email|STR", ":addip|$onlineip|STR", ":addtime|$timestamp|INT", ":ban|0|INT");
$result = $db->query_secure("INSERT INTO xbt_comment (comment,email,addip,addtime,ban) VALUES(:comment,:email,:addip,:addtime,:ban);", $torparams, false, false);
$db->disconnect();

try
    {
        // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
        NoCSRF::check( 'token', $_POST, true, 60*10, false );
        // form parsing, DB inserts, etc.
		

		
		
		
        $result = 'CSRF check passed. Form parsed.';
		$T->block('/errors', array('result' =>$result ), TRUE);
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

?>