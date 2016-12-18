<?php
/** 
 *	File name: index.php
 *	Created by: 半世烛光 QQ24203741
 *	Contact: wds00136@163.com,http://www.113344.com
 *	Last modified: 2013-04-30
 */
header('Content-Type:text/html;charset=utf-8');
define('BASE_URI', str_replace('\\', '/', realpath(dirname(__FILE__))));

session_start();

require BASE_URI.'/conf.inc.php';
require BASE_URI.'/inc/App.class.php';

$app = new App();
$app->run();
?>