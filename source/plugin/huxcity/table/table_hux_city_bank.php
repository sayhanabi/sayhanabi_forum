<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_bank extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_bank';
		$this->_pk    = 'eid';

		parent::__construct();
	}
	
	public function fetch_by_uid_type($uid,$type,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE uid=%d AND type=%d $orders", array($this->_table, $uid ,$type));
	}
	
	public function sum_by_type($type) {
		return DB::result_first("SELECT SUM(money) FROM %t WHERE type<>%d", array($this->_table, $type));
	}
	
	public function update_money_jia_dateline_by_uid_type($uid,$type,$value1,$value2) {
		return DB::query("UPDATE %t SET money=money+%d,dateline=%d WHERE uid=%d AND type=%d", array($this->_table, $value1, $value2, $uid, $type));
	}
	
	public function update_money_jian_dateline_by_uid_type($uid,$type,$value1,$value2) {
		return DB::query("UPDATE %t SET money=money-%d,dateline=%d WHERE uid=%d AND type=%d", array($this->_table, $value1, $value2, $uid, $type));
	}
	
	public function update_money_jia_dateline_outtime_by_uid_type($uid,$type,$value1,$value2,$value3) {
		return DB::query("UPDATE %t SET money=money+%d,dateline=%d,outtime=%d WHERE uid=%d AND type=%d", array($this->_table, $value1, $value2, $value3, $uid, $type));
	}
	
	public function delete_by_uid_type($uid,$type) {
		return DB::query("DELETE FROM %t WHERE uid=%d AND type=%d", array($this->_table, $uid, $type));
	}

}

?>