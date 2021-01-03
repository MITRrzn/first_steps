<?php 

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
setlocale (LC_ALL, 'ru_RU');
date_default_timezone_set('Europe/Moscow');
header('Content-type: text/html; charset=utf-8');

include_once 'phpQuery.php';
$doc = phpQuery::newDocument(file_get_contents('https://www.imdb.com/name/nm0001525/?ref_=tt_cl_t5'));

/* Старая цена - strike */

$entry = $doc->find('.itemprop'); 

$entry->find('span')->remove();

$data['name'] = trim(pq($entry)->text());

 
echo $data['name'];

 

?>