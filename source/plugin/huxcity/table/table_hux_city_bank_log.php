<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_bank_log extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_bank_log';
		$this->_pk    = 'eid';

		parent::__construct();
	}

	public function fetch_by_uid($uid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE uid=%d $orders", array($this->_table, $uid));
	}
	
	public function num_rows_by_search($condition) {
		$n = DB::query("SELECT * FROM ".DB::table('hux_city_bank_log')." WHERE 1=1$condition");
		return DB::num_rows($n);
	}
	
	public function fetch_all_by_search($condition,$start=0,$limit=0) {
		return DB::fetch_all("SELECT * FROM %t WHERE 1%i ORDER BY eid DESC limit $start,$limit",array($this->_table,$condition));
	}
	
	public function count_by_uid($uid) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE uid=%d", array($this->_table, $uid));
	}
	
	public function delete_all() {
		return DB::query("DELETE FROM %t", array($this->_table));
	}
	
	public function update_exp_jian_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET exp=exp-%d WHERE uid=%d", array('hux_city_user', $value, $uid));
	}
	
	public function update_exp_jia_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET exp=exp+%d WHERE uid=%d", array('hux_city_user', $value, $uid));
	}

}

?>