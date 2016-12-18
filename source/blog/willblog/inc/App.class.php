<?php
/** 
 *	File name: App.class.php
 *	Created by: 半世烛光 QQ24203741
 *	Contact: wds00136@163.com,http://www.113344.com
 *	Last modified: 2013-04-30
 */

if (!defined('BASE_URI')) exit('No direct script access allowed');

class App extends Action {
	protected $isLogin = 0;
	protected $_admin = array();

	public function __construct() {
		parent::__construct();
		$conf = require(COM_PATH.'/site_conf.php');
		if (isset($conf['username'],$conf['password'])) {
			$this->_admin = array('username'=>$conf['username'], 'password'=>$conf['password']);
		} else {
			$this->_admin = array('username'=>'admin','password'=>md5('admin'));
		}
		define('__CSS__', $conf['site_css']); 	
		$this->assign('conf', $conf);
		$this->isLogin = (!isset($_SESSION['isLogin'])) ? 0 : 1;
		$this->assign('isLogin', $this->isLogin); 		
	}

	public function index() {
		$pn = isset($_GET['p'])? (int)$_GET['p'] : 1;
		$count = $this->Db()->total('weibo');
		$this->assign('count', $count);
		$pg = '';
		$list = array();
		if ($count > 0) {
			$p = new Page($count, 5, $pn, 'index.php?p={page}');
			$p->setConfig('theme',0);
			$p->setConfig('header', '条记录');	
			$list = $this->Db()->findAll('weibo', '', 'id DESC', $p->getLimit());
			foreach ($list as &$val) {
				$val['title'] = toHtml($val['title']);
				$val['content'] = UbbToHtml(mb_substr($val['content'], 0, 120, 'utf-8'));
				$val['ctime'] = date('Y-m-d', $val['ctime']);
			}	
			$pg = $p->getHtml();
		}
		$this->assign('list', $list);
		$this->assign('page', $pg);		
		$this->display();
	}

	//显示文章
	public function view() {
		$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
		if ($id > 0) {
			$vo = $this->Db()->find('weibo', 'id', $id);
			if ($vo) {
				$vo['title'] = toHtml($vo['title']);
				$vo['content'] = UbbToHtml($vo['content']);
				$vo['ctime'] = date('Y-m-d H:i:s', $vo['ctime']);
				$this->assign('vo', $vo);
				$this->display();
			} else {
				$this->error('没有此记录!');
			}
		} else {
			$this->error('ID设置错误!');
		}
	}
	//添加文章
	public function add() {
		$this->display();
	}
	//编辑文章
	public function edit() {
		$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
		if ($id > 0) {
			$vo = $this->Db()->find('weibo', 'id', $id);
			if ($vo) {
				$this->assign('vo', $vo);
				$this->display();
			} else {
				$this->error('没有此记录!');
			}
		} else {
			$this->error('ID设置错误!');
		}
	}	
	//添加操作
	public function a_insert() {
		$this->check_login();
		$r = $this->Db()->add('weibo', $_POST, array('ctime'=>time()));
		if ($r) {
			$this->success('添加成功!', '?a=index');
		} else {
			$this->error('添加失败!');
		}
	}
	//更新操作
	public function a_update() {
		$this->check_login();
		$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
		if ($id > 0) {
			$r = $this->Db()->update('weibo', $_POST, array('ctime'=>time()), 'id', $id);
			if ($r) {
				$this->success('更新成功!', '?a=view&id='.$id);
			} else {
				$this->error('更新失败!', '?a=view&id='.$id);
			}
		} else {
			$this->error('非法操作!');
		}
	}
	//删除操作
	public function a_del() {
		$this->check_login();
		$id = isset($_GET['id'])? (int)$_GET['id'] : 0; 
		if ($id > 0) {
			$r = $this->Db()->del('weibo', 'id', $id);
			if ($r) {
				$this->success('删除成功!');
			} else {
				$this->error('删除失败!');
			}
		} else {
			$this->error('非法操作!');
		}		
	}
	//网站配置
	public function site() {	
		$this->check_login();	
		$this->display();
	}
	// 网站配置更新
    public function site_update() {
    	$this->check_login();
		$_POST = toHtml($_POST);
		if ($_POST['password'] == '') {
			$_POST['password'] = $this->_admin['password'];
		} else {
			$_POST['password'] = md5($_POST['password']);
		}
    	file_put_contents(COM_PATH.'/site_conf.php',"<?php\nreturn ".var_export($_POST, true).";\n?>");
   		$this->success('配置更新成功!'); 
    }

    //留言本
    public function gbook() {
		$pn = isset($_GET['p'])? (int)$_GET['p'] : 1;
		$count = $this->Db()->total('gbook');
		$this->assign('count', $count);
		$pg = '';
		$list = array();
		if ($count > 0) {
			$p = new Page($count, 5, $pn, 'index.php?a=gbook&p={page}');
			$p->setConfig('theme',0);
			$p->setConfig('header', '条留言');	
			$list = $this->Db()->findAll('gbook', '', 'id DESC', $p->getLimit());
			foreach ($list as &$val) {
				$val['username'] = toHtml($val['username']);
				$val['ctime'] = date('Y-m-d H:i:s', $val['ctime']);				
				$val['content'] = UbbToHtml($val['content']);
				$val['uip'] = preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$1.$2.$3.*",$val['uip']);
				$val['uface'] = rand(10,64);
				if ($val['rtime'] != 0) {
					$val['rtime'] = date('Y-m-d H:i:s', $val['rtime']);		
					$val['re'] = toHtml($val['re']);
				}
			}
			$pg = $p->getHtml();
		}
		$this->assign('list', $list);
		$this->assign('page', $pg);		
		$this->display();    	
    }
	//添加留言
	public function g_insert() {
		if ($_POST['content'] == '') {
			$this->error('留言内容不能为空!', '?a=gbook');
		} 
		$r = $this->Db()->add('gbook', $_POST, array('ctime'=>time(),'uip'=>get_ip()));
		if ($r) {
			$this->success('添加成功!', '?a=gbook');
		} else {
			$this->error('添加失败!');
		}		
	}
	//删除留言
 	public function g_del() {
 		$this->check_login();
 		$id = isset($_GET['id'])? (int)$_GET['id'] : 0; 
		if ($id > 0) {
			$r = $this->Db()->del('gbook', 'id', $id);
			if ($r) {
				$this->success('删除成功!', 'index.php?a=gbook');
			} else {
				$this->error('删除失败!','index.php?a=gbook');
			}
		} else {
			$this->error('非法操作!', 'index.php?a=gbook');
		}			
 	}	
 	//回复留言
 	public function g_replay() {
 		$this->check_login();
		$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
		if ($id > 0) {
			$rtime = ($_POST['re'] == '')? 0 : time();
			$r = $this->Db()->update('gbook', $_POST, array('rtime'=>$rtime), 'id', $id);
			if ($r) {
				$this->success('回复成功!', 'index.php?a=gbook');
			} else {
				$this->error('更新失败!', 'index.php?a=gbook');
			}
		} else {
			$this->error('非法操作!','index.php?a=gbook');
		}	
	}	
 	//登录操作
	public function a_login() {
		if ($_POST['admin_name'] == $this->_admin['username'] && md5($_POST['admin_pwd']) == $this->_admin['password']) {
			$_SESSION['isLogin'] = 1;
			$this->success('登录成功!');
		} else {
			$this->error('用户名或密码错误!');
		}
	}
	//退出登录
	public function a_logout() {
		if (isset($_SESSION['isLogin'])) {
			unset($_SESSION['isLogin']);
			unset($_SESSIION);
			session_destroy();
			$this->success('退出成功!');
		} else {
			$this->error('已经退出!');
		}		
	}
	//检测登录状态
	protected function check_login() {
		if (!isset($_SESSION['isLogin'])) {
			$this->error('请先登录!');
		}
	}	
}
?>