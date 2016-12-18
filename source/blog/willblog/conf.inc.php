<?php
/** 
 *	File name: conf.inc.php
 *	Created by: 心扬紫黯 QQ24203741
 *	Contact: wds00136@163.com,http://www.113344.com
 *	Last modified: 2013-04-15
 */
if (!defined('BASE_URI')) exit('No direct script access allowed');

date_default_timezone_set('PRC'); 

define('DB_HOST', 'localhost'); //MySql服务器
define('DB_USER', 'root');      //MySql用户
define('DB_PWD', 'xsbsyhzbyp');			//MySql密码
define('DB_NAME', 'users');      //MySql数据库名

define('TPL_PATH', BASE_URI.'/tpl');   //模板路径
define('COM_PATH', BASE_URI.'/tpl_c'); //模板编译路径

//载入函数库
require BASE_URI.'/inc/function.inc.php';
//载入类库
require BASE_URI.'/inc/DBMysqli.class.php';
require BASE_URI.'/inc/Page.class.php';
require BASE_URI.'/inc/MyTpl.class.php';
require BASE_URI.'/inc/Action.class.php';

?>