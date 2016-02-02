<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_app extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_app';
		$this->_pk    = 'eid';

		parent::__construct();
	}

	public function fetch_all_by_appshow($appshow,$field='*',$order='') {
		return DB::fetch_all("SELECT $field FROM %t WHERE appshow=%d $order", array($this->_table ,$appshow));
	}
	
	public function fetch_all_no_by_appid($appid,$field='*',$order='') {
		return DB::fetch_all("SELECT $field FROM %t WHERE appid<>%s $order", array($this->_table ,$appid));
	}
	
	public function fetch_all_by_appid($appid,$field='*',$order='') {
		return DB::fetch_all("SELECT $field FROM %t WHERE appid=%s $order", array($this->_table ,$appid));
	}
	
	public function fetch_all($field='*',$order='') {
		return DB::fetch_all("SELECT $field FROM %t $order", array($this->_table));
	}

	public function result_by_appid($appid,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE appid=%s", array($this->_table, $appid));
	}
	
	public function result_by_eid($eid,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE eid=%d", array($this->_table, $eid));
	}
	
	public function update_appshow_by_appid($appid,$value) {
		return DB::query("UPDATE %t SET appshow=%d WHERE appid=%s", array($this->_table, $value, $appid));
	}
	
	public function update_appver_by_appid($appid,$value) {
		return DB::query("UPDATE %t SET appver=%s WHERE appid=%s", array($this->_table, $value, $appid));
	}
	
	public function update_appinfo_by_appid($appid,$value1,$value2,$value3) {
		return DB::query("UPDATE %t SET appname=%s,ordernum=%d,colorcode=%s WHERE appid=%s", array($this->_table, $value1, $value2, $value3, $appid));
	}
	
	public function delete_by_appid($appid) {
		return DB::query("DELETE FROM %t WHERE appid=%s", array($this->_table, $appid));
	}
	
	public function fetch_by_appid($appid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE appid=%s $orders", array($this->_table, $appid));
	}
	
	public function fetch_by_eid($eid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE eid=%s $orders", array($this->_table, $eid));
	}
	
	public function count_by_appid($appid) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE appid=%s", array($this->_table, $appid));
	}

}

?>