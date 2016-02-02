<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_online extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_online';
		$this->_pk    = 'eid';

		parent::__construct();
	}

	public function delete_by_search($condition) {
		return DB::fetch_first("DELETE FROM %t WHERE 1 %i", array($this->_table, $condition));
	}

	public function count_by_username_appid($username,$appid) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE username=%s AND appid=%s", array($this->_table, $username, $appid));
	}

	public function update_ontime_by_username_appid($username,$appid,$value) {
		return DB::query("UPDATE %t SET ontime=%s WHERE username=%s AND appid=%s", array($this->_table, $value, $username, $appid));
	}

	public function fetch_all_by_appid($appid,$field='*',$order='') {
		return DB::fetch_all("SELECT $field FROM %t WHERE appid=%s $order", array($this->_table ,$appid));
	}
	
	public function count_by_username($username) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE username=%s", array($this->_table, $username));
	}

}

?>