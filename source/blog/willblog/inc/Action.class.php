<?php
/** 
 *	File name: Action.class.php
 *	Created by: 半世烛光 QQ24203741
 *	Contact: wds00136@163.com,http://www.113344.com
 *	Last modified: 2013-04-30
 */
if (!defined('BASE_URI')) exit('No direct script access allowed');
if (!defined('__THEME__')) define('__THEME__', '');

class Action {
	protected $_tpl = NULL;

	public function __construct() {
		$this->_tpl = MyTpl::getInstance();
	}

	public function assign($key, $value) {
		$this->_tpl->assign($key, $value);
	}

	public function display($file = '') {
		if ($file == '') {
			$file = isset($_GET['a']) ? strtolower($_GET['a']).'.html' : 'index.html';
		}
		$file = 'Public/'.$file;
		$this->_tpl->display($file);
	}

	public function run() {
		$action = isset($_GET['a'])? $_GET['a'] : 'index';
		if (method_exists($this, $action)) {
			$this->$action();
		} else {
			$this->error('没有这个协作!');
		}
	}

	protected function _info($info = '', $url = 'index.php', $time = 3, $css = 0) {
		$this->assign('waitSecond', $time);
		$this->assign('jumpUrl', $url);
		$this->assign('css', $css);
		$this->assign('error', $info);
		$this->display('success.html');
	}

	public function error($info = '未知错误!', $url = 'index.php', $time = 3) {
		$this->_info($info, $url, $time, 0);
		exit();
	}

	public function success($info = '操作成功!', $url = 'index.php', $time = 3) {
		$this->_info($info, $url, $time, 1);
	}

	protected function Db() {
		return DBMySqli::getInstance();
	}
}
?>