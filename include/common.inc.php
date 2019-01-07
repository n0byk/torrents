<?php
error_reporting (E_ERROR | E_WARNING | E_PARSE);

define('IN_TOR', TRUE);
define('ROOT_DIR', substr(dirname(__FILE__), 0, -7));
define('TOR_DIR', "./data/torrents/");
define('LOG_DIR', "./data/logs/");
//main
require_once ROOT_DIR.'./include/global.func.php';
require_once ROOT_DIR.'./include/classes/database.class.php';
//csrf form
require_once ROOT_DIR.'./include/classes/nocsrf.class.php';
//xss
require_once ROOT_DIR.'./include/classes/jevix.class.php';
//debug
require_once ROOT_DIR.'./include/classes/debug.class.php';
//recaptcha
require_once ROOT_DIR.'./modules/recaptcha/recaptchalib.php';
//require_once ROOT_DIR.'./include/blitz.class.php';
if(!defined('CURSCRIPT'))
{
	exit('CURSCRIPT ERROR');
}
//Базовые настройки
$version      ='1.0 beta';
$timestamp	  = time();
$onlineip	  = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
$tordir       = "./data/torrents/";
$admin_email  = "n0byk@yandex.ru";
$site_name    = "Torrent.ru";
//Соединение с базой
$db = new wArLeY_DBMS("mysql", "127.0.0.1", "torrent", "root", "123123", "");
$dbCN = $db->Cnxn(); 
if($dbCN==false){ 
logger( "Error: Cant connect to database. ".$db->getError()."","engine" );
die("Error: Cant connect to database.");
}
echo $db->getError();
//$db->properties();


//Шаблонизатор
$T = new Blitz();

//Языки && Титлы ...
$lang=get_language();
$T->setGlobals($lang);
$T->set(array('site_name' =>$site_name));
//captcha
$publickey = "6LdcbMgSAAAAADuOQvvRhb127BdZDclkY2IEoOey";
$privatekey = "6LdcbMgSAAAAAOxDHCWkbr1BAUU5a2MkfMR4FOGY";
$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], isset($_POST["recaptcha_challenge_field"]), isset($_POST["recaptcha_response_field"]));

//xss в Get запросах
check_xss();



//Конфигурация XSS
$errors = null;
$jevix = new Jevix();
// 1. Устанавливаем разрешённые теги. (Все не разрешенные теги считаются запрещенными.)
$jevix->cfgAllowTags(array('a', 'img', 'i', 'b', 'u', 'em', 'strong', 'nobr', 'li', 'ol', 'ul', 'sup', 'abbr', 'pre', 'acronym', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'adabracut', 'br', 'code'));
// 2. Устанавливаем коротие теги. (не имеющие закрывающего тега)
$jevix->cfgSetTagShort(array('br','img'));
// 3. Устанавливаем преформатированные теги. (в них все будет заменятся на HTML сущности)
$jevix->cfgSetTagPreformatted(array('pre'));
// 4. Устанавливаем теги, которые необходимо вырезать из текста вместе с контентом.
$jevix->cfgSetTagCutWithContent(array('script', 'object', 'iframe', 'style'));
// 5. Устанавливаем разрешённые параметры тегов. Также можно устанавливать допустимые значения этих параметров.
$jevix->cfgAllowTagParams('a', array('title', 'href'));
$jevix->cfgAllowTagParams('img', array('src', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int'));
// 6. Устанавливаем параметры тегов являющиеся обязяательными. Без них вырезает тег оставляя содержимое.
$jevix->cfgSetTagParamsRequired('img', 'src');
$jevix->cfgSetTagParamsRequired('a', 'href');
// 7. Устанавливаем теги которые может содержать тег контейнер
//    cfgSetTagChilds($tag, $childs, $isContainerOnly, $isChildOnly)
//       $isContainerOnly : тег является только контейнером для других тегов и не может содержать текст (по умолчанию false)
//       $isChildOnly : вложенные теги не могут присутствовать нигде кроме указанного тега (по умолчанию false)
//$jevix->cfgSetTagChilds('ul', 'li', true, false);
// 8. Устанавливаем атрибуты тегов, которые будут добавлятся автоматически
$jevix->cfgSetTagParamDefault('a', 'rel', null, true);
//$jevix->cfgSetTagParamsAutoAdd('a', array('rel' => 'nofollow'));
//$jevix->cfgSetTagParamsAutoAdd('a', array('name'=>'rel', 'value' => 'nofollow', 'rewrite' => true));
$jevix->cfgSetTagParamDefault('img', 'width',  '300px');
$jevix->cfgSetTagParamDefault('img', 'height', '300px');
//$jevix->cfgSetTagParamsAutoAdd('img', array('width' => '300', 'height' => '300'));
//$jevix->cfgSetTagParamsAutoAdd('img', array(array('name'=>'width', 'value' => '300'), array('name'=>'height', 'value' => '300') ));
// 9. Устанавливаем автозамену
$jevix->cfgSetAutoReplace(array('+/-', '(c)', '(r)'), array('±', '©', '®'));
// 10. Включаем или выключаем режим XHTML. (по умолчанию включен)
$jevix->cfgSetXHTMLMode(true);
// 11. Включаем или выключаем режим замены переноса строк на тег <br/>. (по умолчанию включен)
$jevix->cfgSetAutoBrMode(true);
// 12. Включаем или выключаем режим автоматического определения ссылок. (по умолчанию включен)
$jevix->cfgSetAutoLinkMode(true);
// 13. Отключаем типографирование в определенном теге
$jevix->cfgSetTagNoTypography('code');
// Все!

?>
