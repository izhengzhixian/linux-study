<?php
/** 
 *	File name: MyTpl.class.php
 *	Created by: 半世烛光 QQ24203741
 *	Contact: wds00136@163.com,http://www.113344.com
 *	Last modified: 2013-04-30
 */
if (!defined('TPL_PATH') && !defined('COM_PATH')) exit('No direct script access allowed');

class MyTpl {
	private static $_instance = NULL;
	private $_view = array();
	private $_conf = array();

	private function __construct() {
		$this->_conf = array(
			't_dir'	  => rtrim(TPL_PATH, '/').'/',
			'c_dir'	  => rtrim(COM_PATH, '/').'/',
			'l_limit' => '<\{',
			'r_limit' => '\}>',
		);
	}
	private function __clone() {}

	public static function getInstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance; 
	}

	public function assign($key, $value = NULL) {
		if ($key != '') {
			$this->_view[$key] = $value;
		}
	}

	public function display($file = '') {
		$t_file = $this->_conf['t_dir'].$file;
		if (!file_exists($t_file)) {
			exit('File Not Found! -'.$file);
		}
		if (basename($file) == 'success.html') {
			$c_name = 'com_success.html.php';
		} else {
			$c_name = 'com_113344_'.basename($file).'.php';
		}
		$c_file = $this->_conf['c_dir'].$c_name;
		if (!file_exists($c_file) || filemtime($c_file) < filemtime($t_file)) {
			$content = file_get_contents($t_file);
			$content = $this->_comp($content);
			$content = preg_replace('/__(\w+)__/', '<?php echo __\\1__; ?>', $content);
			file_put_contents($c_file, $content);
		}
		include $c_file;
	}

	private function _comp($content) {
		$str	= '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
		$left 	= $this->_conf['l_limit'];
		$right 	= $this->_conf['r_limit'];
		$pattern = array(
			'/'.$left.'\s*\$('.$str.')\s*'.$right.'/i',
			'/'.$left.'\s*\$('.$str.')\.('.$str.')\s*'.$right.'/i',
			'/'.$left.'\s*if\s*(.+?)\s*'.$right.'(.+?)'.$left.'\s*\/if\s*'.$right.'/ies',
			'/'.$left.'\s*else\s*'.$right.'/is',
			'/'.$left.'\s*else\s*if\s*(.+?)\s*'.$right.'/ies',
			'/'.$left.'\s*loop\s+\$(\S+)\s+as\s+\$('.$str.')\s*'.$right.'(.+?)'.$left.'\s*\/loop\s*'.$right.'/is',
			'/'.$left.'\s*loop\s+\$(\S+)\s+as\s+\$('.$str.')\s*=>\s*\$(\S+)\s*'.$right.'(.+?)'.$left.'\s*\/loop\s*'.$right.'/is',
			'/'.$left.'\s*noloop\s*\$('.$str.')\s*'.$right.'/i',
			'/'.$left.'\s*include\s*\$('.$str.')\s*'.$right.'/ie',
			'/'.$left.'\s*include\s+[\"\']?(.+?)[\"\']?\s*'.$right.'/ie',
			);
		$replacement = array(
			'<?php echo $this->_view["${1}"]; ?>',
			'<?php echo $this->_view["${1}"]["${2}"]; ?>',
			'$this->_stripvtags(\'<?php if(${1}) { ?>\',\'${2}<?php } ?>\')',
			'<?php } else { ?>',
			'$this->_stripvtags(\'<?php } elseif(${1}) { ?>\',"")',
			'<?php foreach((array)$this->_view["${1}"] as $this->_view["${2}"]) { ?>${3}<?php } ?>',
			'<?php foreach((array)$this->_view["${1}"] as $this->_view["${2}"] => $this->_view["${3}"]) { ?>${4}<?php } ?>',
			'<?php } if (empty($this->_view["${1}"])) { ?>',
			'file_get_contents($this->_conf["t_dir"].$this->_view["${1}"])',
			'file_get_contents($this->_conf["t_dir"]."${1}")',
			);
		$content = preg_replace($pattern, $replacement, $content);
		if (preg_match('/'.$left.'([^('.$right.')]{1,})'.$right.'/', $content)) {
			$content = preg_replace($pattern, $replacement, $content);
		}		
		return $content;
	}

	private function _stripvtags($expr, $statement = '') {
		$var_pat   = '/\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*/is';	
		$expr	   = preg_replace($var_pat, '$this->_view["${1}"]', $expr);
		$expr      = str_replace("\\\"", "\"", $expr);
		$statement = str_replace("\\\"", "\"", $statement);
		return $expr.$statement;
	}		

	public function __destruct() {
		$this->_view = NULL;
	}
}

?>