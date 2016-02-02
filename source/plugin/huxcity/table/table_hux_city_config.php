<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_config extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_config';
		$this->_pk    = 'eid';

		parent::__construct();
	}

	public function fetch_by_appid($appid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE appid=%s $orders", array($this->_table, $appid));
	}
	
	public function result_by_appid($appid,$value) {
		return DB::result_first("SELECT $value FROM %t WHERE appid=%s", array($this->_table, $appid));
	}
	
	public function update_appadmin_appadmintime_by_appid($appid,$value1,$value2) {
		return DB::query("UPDATE %t SET appadmin=%s,appadmintime=%d WHERE appid=%s", array($this->_table, $value1, $value2, $appid));
	}
	
	public function update_appadmin_appadmintime_jia_by_appid($appid,$value1,$value2) {
		return DB::query("UPDATE %t SET appadmin=%s,appadmintime=appadmintime+%d WHERE appid=%s", array($this->_table, $value1, $value2, $appid));
	}
	
	public function update_appadmintime_by_appid($appid,$value) {
		return DB::query("UPDATE %t SET appadmintime=%d WHERE appid=%s", array($this->_table, $value, $appid));
	}
	
	public function update_appmoney_jian_by_appid($appid,$value) {
		return DB::query("UPDATE %t SET appmoney=appmoney-%d WHERE appid=%s", array($this->_table, $value, $appid));
	}
	
	public function update_appmoney_jia_by_appid($appid,$value) {
		return DB::query("UPDATE %t SET appmoney=appmoney+%d WHERE appid=%s", array($this->_table, $value, $appid));
	}
	
	public function update_configs_appadmin_by_appid($appid,$value1,$value2) {
		return DB::query("UPDATE %t SET configs=%s,appadmin=%s WHERE appid=%s", array($this->_table, $value1, $value2, $appid));
	}
	
	public function delete_by_appid($appid) {
		return DB::query("DELETE FROM %t WHERE appid=%s", array($this->_table, $appid));
	}

}

?>