<?php
if(!defined('IN_TOR'))
{
	exit('Access Denied');
}
//$ques_hidanswer = $_POST['hidanswer'] ? 1 : 0;
function create_magnet($dn, $xl = false, $btih = '', $tr = '')
{
        $magnet = 'magnet:?';
        if ($dn)
        {
                $magnet .= 'dn=' . $dn; // download name
        }
        if ($xl)
        {
                $magnet .= '&xl=' . $xl; // size
        }
        if ($btih)
        {
                $magnet .= '&xt=urn:btih:' . $btih; // bittorrent info_hash (Base32)
        }
        if ($tr)
        {
                if(is_array($tr)) {
                        $magnet .= '&tr=' . implode("&tr=",$tr);
                } else {
                        $magnet .= '&tr=' . $tr; // gnutella sha1 (base32)
                }
        }
        return $magnet;
}
//$magnet = create_magnet(urlencode($name), $tor['size'], strtoupper(base32_encode(hex2bin($info_hash))), $tr); 

function get_language (){
if(!isset($_COOKIE['lang'])) {
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
setcookie('lang',"$lang");
}
$lang = isset($_COOKIE['lang']);
switch($lang){
case "ru" : $languagepack = require_once ROOT_DIR.'templates/lang/ru_lang.php';
break;
//case 2 : $order=" order by asktime ASC"; $ord['d']='1';break;
default: $languagepack = require_once ROOT_DIR.'templates/lang/en_lang.php';
}	
return $languagepack;
}

//Protection
function antixss($output) {
$clear = trim(htmlentities($output, ENT_QUOTES, 'UTF-8'));
return $clear;
} 

function hash_pad($hash) {
    return str_pad($hash, 20);
}

function is_numder($val) {
    //return (int)preg_replace('/[^0-9]/', '', $val);
	  return (int)eregi_replace('([^0-9])', '', $val);
}

function is_email($val) {
  $val=trim($val);
  return (bool)(preg_match("/^([a-z0-9+_-]+)(.[a-z0-9+_-]+)*@([a-z0-9-]+.)+[a-z]{2,6}$/ix", $val));
}   
function check_xss() {
$url = html_entity_decode( urldecode( $_SERVER['QUERY_STRING'] ) );
if( $url ) {
if( (strpos( $url, '<' ) !== false) || (strpos( $url, '>' ) !== false) || (strpos( $url, '"' ) !== false) || (strpos( $url, './' ) !== false) || (strpos( $url, '../' ) !== false) || (strpos( $url, '\'' ) !== false) || (strpos( $url, '.php' ) !== false) ) {
die('Hacking attempt!');
}}
$url = html_entity_decode( urldecode( $_SERVER['REQUEST_URI'] ) );
if( $url ) {
if( (strpos( $url, '<' ) !== false) || (strpos( $url, '>' ) !== false) || (strpos( $url, '"' ) !== false) || (strpos( $url, '\'' ) !== false) ) {
die('Hacking attempt!');
}}}
//Protection off

function sendmail($to,$subject,$body)
{
  global $charset,$site_name,$admin_email;
  
  $from=$site_name.' <'.$admin_email.'>';
  $from_list=explode(' <', $from);
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
  $headers .= "From: =?UTF-8?B?".base64_encode($from_list[0])."?= <".$from_list[1]."\r\n";
  $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
  $body='<html><body>'.$body.'</body></html>';
  @mail($to, $subject, $body, $headers);
}

function downloadFile($file,$name,$type){
    $mime = 'application/x-bittorrent';
    header('Pragma: public');   
    header('Expires: 0');       
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private',false);
    header('Content-Type: '.$mime);
    header('Content-Disposition: attachment; filename="'.$name.'.'.$type.'"');
    header('Content-Transfer-Encoding: binary');
    header('Connection: close');
    readfile(TOR_DIR.$file);   
    exit();
}

function logger( $logthis,$file ){
file_put_contents(LOG_DIR.$file.'.log', date("Y-m-d H:i:s").': '.$logthis.PHP_EOL, FILE_APPEND | LOCK_EX);
}
/*
function cutStr($str, $lenght, $end = '&nbsp;&hellip;', $charset = 'UTF-8', $token = '~') {
    $str = strip_tags($str);
    if (mb_strlen($str, $charset) >= $lenght) {
        $wrap = wordwrap($str, $lenght, $token);
        $str_cut = mb_substr($wrap, 0, mb_strpos($wrap, $token, 0, $charset), $charset);   
        return $str_cut .= $end;
    } else {
        return $str;
    }
}*/

function cutStr($string, $limit)
{
if (strlen($string) >= $limit ) {
$substring_limited = substr($string,0, $limit);
return substr($substring_limited, 0, strrpos($substring_limited, ' ' ));
} else {
return $string;
}}





function file_size($size){  
$filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");  
return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0 Bytes';  
}
/* список языков
$sites = array(
    "en" => "http://en.mysite.com/",
    "es" => "http://es.mysite.com/",
  "fr" => "http://fr.mysite.com/",
);

// получаем язык
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

// проверяем язык
if (!in_array($lang, array_keys($sites))){
    $lang = 'en';
}
// перенаправление на субдомен
header('Location: ' . $sites[$lang]);
*/


//редирект HTTPS
//RewriteEngine On
 // RewriteCond %{HTTPS} !on
  //RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI}

function redirectToHTTPS()
{
  if($_SERVER['HTTPS']!="on")
  {
     $redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
     header("Location:$redirect");
  }
}

?>
