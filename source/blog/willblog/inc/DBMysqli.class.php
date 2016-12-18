<?php
/** 
 *	File name: DBMysqli.class.php v2.1
 *	Created by: 半世烛光 QQ24203741
 *	Contact: wds00136@163.com,http://www.113344.com
 *	Last modified: 2013-04-30
 */
if (!defined('DB_HOST')) exit('No direct script access allowed');

class DBMysqli {
	private static $_instance = NULL;
	private $linkID = FALSE;
	private $queryID = NULL;

	private function __construct() {
		$this->linkID = @ new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
		if (mysqli_connect_errno()) {
			$this->linkID = FALSE;
			exit('Could not connect to the database!');
		}
		$this->linkID->query("SET NAMES 'utf8';");
		$this->linkID->query("SET TIME_ZONE = '+8:00';");
	}
	private function __clone() {}

	public static function getInstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance; 
	}

	private function db_query($sql) {
		$this->queryID = @ $this->linkID->query($sql);
		if (! $this->queryID) {
			exit('Could not exectute SQL query! SQL::'.$sql);
		}
		return $this->queryID;
	}

	//total
	public function total($tabName, $idName = 'id', $where = '') {
		$where = empty($where) ? '' : $this->_prase_where($where);
		$q = sprintf("SELECT %s FROM `%s`%s", $idName, $tabName, $where);
		$this->db_query($q);
		return $this->queryID->num_rows;
	}
	//find
	public function find($tabName, $idName, $id = 0) {
		$id = (int) $id;
		if ($id > 0) {
			$q = sprintf("SELECT * FROM `%s` WHERE `%s`=%d", $tabName, $idName, $id);
			$this->db_query($q);
			if ($this->queryID && $this->queryID->num_rows > 0) {
				return $this->queryID->fetch_assoc();
			} else {
				return NULL;
			}
		}		
	}
	//findAll
	public function findAll($tabName, $fields = array(), $orderBy = 'id DESC', $limit = '', $where = '') {
		if (!empty($fields) && is_array($fields)) {
			$fields = join(',', $fields);
		} else {
			$fields = '*';
		}
		$where = empty($where) ? '' : $this->_prase_where($where);
		$limit = empty($limit) ? '' : ' LIMIT '.$limit;
		$q = sprintf("SELECT %s FROM `%s`%s ORDER BY %s%s", $fields, $tabName, $where, $orderBy, $limit);
		$this->db_query($q);
		if ($this->queryID && $this->queryID->num_rows > 0) {
			$data = array();
			while($row = $this->queryID->fetch_assoc()){
				$data[] = $row;
			}
			return $data;
		} else {
			return NULL;
		}
	}
	//del
	public function del($tabName, $idName, $id = 0) {
		$id = (int) $id;
		if ($id > 0) {
			$q = sprintf("DELETE FROM `%s` WHERE `%s`=%d", $tabName, $idName, $id);
			$this->db_query($q);
			return $this->linkID->affected_rows;
		}				
	}
	//add
	public function add($tabName, $post = array(), $auto = array()) {
		$data = array_merge($post, $auto);
		$fields = $values = '';
		foreach ($data as $key=>$val) {
			$fields .= sprintf("`%s`,", $key);
			$values .= sprintf("'%s',", $this->_clean($val));
		}
		$fields = trim($fields, ',');
		$values = trim($values, ',');
		$q = sprintf("INSERT INTO `%s` (%s) VALUES (%s)", $tabName, $fields, $values);
		$this->db_query($q);
		return $this->linkID->insert_id;
	}
	//update
	public function update($tabName, $post = array(), $auto = array(), $idName = 'id', $id = 0) {
		$data = array_merge($post, $auto);
		if (isset($data[$idName])) {
			$id = $data[$idName];
			unset($data[$idName]);
		} else {
			$id = (int) $id;
		}
		if ($id > 0) {
			$values = '';
			foreach ($data as $key => $val) {
				$values .= sprintf("`%s`='%s',", $key, $this->_clean($val));
			}
			$values = trim($values, ',');
			$q = sprintf("UPDATE `%s` SET %s WHERE `%s`=%d", $tabName, $values, $idName, $id);
			$this->db_query($q);
			return $this->linkID->affected_rows;			
		}
	}
	//clean
	protected function _clean($str) {
		if (ini_get('magic_quotes_gpc')) {
			$str = stripslashes($str);
		}
		return $this->linkID->real_escape_string(trim($str)); 
	}
	//处理where
	private function _prase_where() {
		if (is_array($where)) {
			$wh = array();
			foreach ($where as $key => $val) {
				$value = (is_numeric($val))? $val : "'".$this->_clean($val)."'";
				if (is_scalar($value)) {
					$wh[] = "`".$key."` = ".$value;
				} 
			}
			$where = implode(' AND ',$wh);
		}
		return (empty($where)) ? '' : ' WHERE '.$where;		
	}

	public function __destruct() {
		if (is_array($this->queryID)) {
			$this->queryID->free();
		}
		$this->queryID = NULL;	
		if ($this->linkID) {
			$this->linkID->close();
		}
		$this->linkID = FALSE;
	}
}

?>