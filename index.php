<?php
define('CURSCRIPT', 'index');
require_once ('./include/common.inc.php');
//pagination
require_once ('./include/pagination/Manager.php');
require_once ('./include/pagination/Helper.php');
//cache
require_once ('./include/classes/pageszdr.class.php');
$T->load('{{ include("templates/default/index.tpl") }}');
//Установка титлов
$T->set(array('title' => 'Это тестовая страница торрента'));



/*$sortid=intval($_GET['sortid']);

$ord['d']='2';
$ord['a']='3';
$ord['s']='5';

$order=" order by asktime DESC";
switch($sortid)
{
	case 1 : $order=" order by asktime DESC"; $ord['d']='2';break;
	case 2 : $order=" order by asktime ASC"; $ord['d']='1';break;
	case 3 : $order=" order by answercount DESC"; $ord['a']='4';break;
	case 4 : $order=" order by answercount ASC"; $ord['a']='3';break;
	case 5 : $order=" order by score DESC"; $ord['s']='6';break;
	case 6 : $order=" order by score ASC"; $ord['s']='5';break;
}	
*/
//SELECT u.id, u.name, d.name AS d_name FROM users u INNER JOIN departments d ON u.d_id = d.id

/*
$msg .= "That happened on page '" . $_SERVER['PHP_SELF'] . "' from IP Address '" . $_SERVER['REMOTE_ADDR'] . ":". $_SERVER['REMOTE_PORT'] . "' coming from the page (referrer) '" . $_SERVER['HTTP_REFERER'] . "'.\n\n";
$log->error("This is a error log message. Nr: " . $msg);
*/
//pagination
$paginationManager = new Krugozor_Pagination_Manager(10, 7, $_REQUEST); 

//SELECT a.tor_hash, a.title, a.description, m.leechers, m.seeders FROM xbt_torrents as a	LEFT JOIN xbt_files as m  ON xbt_torrents.tor_hash = xbt_files.info_hash"
$rs = $db->query("SELECT a.tid, a.tor_hash, a.title, a.description, a.category, a.size, m.leechers, m.seeders FROM xbt_torrents as a LEFT JOIN xbt_files as m  ON a.tor_hash = m.info_hash WHERE a.ban='0' 
	ORDER BY a.tid DESC LIMIT ".$paginationManager->getStartLimit().",".$paginationManager->getStopLimit()."");

$rows = $db->rowcount();
$db->disconnect();
$paginationManager->setCount($rows);
$counter = $paginationManager->getAutoincrementNum(); 

foreach($rs as $row){
	$tid = intval($row["tid"]);
	$tor_hash = bin2hex($row["tor_hash"]);
	$title = $jevix->parse($row["title"], $errors);
	$description = $jevix->parse($row["description"], $errors);
	$category = $jevix->parse($row["category"], $errors);
	$leechers = intval($row["leechers"]);
	$seeders = intval($row["seeders"]);
    $size = file_size($row["size"]);
	$peers = $leechers + $seeders;
	$T->block('/torrents', array('tid' => $tid, 'tor_hash' => $tor_hash, 'title' => $title ,'description' => $description ,'category' => $category ,'size' => $size ,'leechers' => $leechers ,'seeders' => $seeders ,'peers' => $peers), TRUE);
}
    // Инстанцирование объекта `Krugozor_Pagination_Helper`,
    // в него передаётся объект класса `Krugozor_Pagination_Manager` $paginationManager
    $paginationHelper = new Krugozor_Pagination_Helper($paginationManager);

                       // Хотим получить стандартный вид пагинации

$paginationHelper->setPaginationType(Krugozor_Pagination_Helper::PAGINATION_NORMAL_TYPE)
                       // Устанавливаем CSS-класс каждого элемента <a> в интерфейсе пагинатора
                     ->setCssNormalLinkClass("normal_link")
                       // Устанавливаем CSS-класс элемента <span> в интерфейсе пагинатора,
                       // страница которого открыта в текущий момент.
                     ->setCssActiveLinkClass("active_link")
                       // Параметр для query string гиперссылки
                     ->setRequestUriParameter("param_1", "value_1")
                       // Параметр для query string гиперссылки
                     ->setRequestUriParameter("param_2", "value_2")
                       // Устанавливаем идентификатор фрагмента гиперссылок пагинатора
                     ->setFragmentIdentifier("result4")
                       // Не показываем сслыки перехода на первую («««)...
                     ->setViewFirstPageLabel(false)
                       // ..и последнюю (»»») страницу
                     ->setViewLastPageLabel(false)
                       // Не показываем ссылки перехода между блоками страниц («« и »»)
                     ->setViewPreviousBlockLabel(false)
                     ->setViewNextBlockLabel(false)
                       // Заменим якоря тегов перехода между страницами (« и »)
                     ->setPreviousPageAnchor("&larr; Туда")
                     ->setNextPageAnchor("Сюда &rarr;");

$T->block('/pagination', array('all' => $paginationHelper->getPagination()->getCount(), 'pages' => $paginationHelper->getHtml() ), TRUE);


$T->display();
//cache
$cacheZDR->pageFooter();
?>