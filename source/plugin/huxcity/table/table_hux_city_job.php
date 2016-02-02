<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_job extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_job';
		$this->_pk    = 'eid';

		parent::__construct();
	}

	public function fetch_all_by_appid_status($appid,$status,$field='*',$order='') {
		return DB::fetch_all("SELECT $field FROM %t WHERE appid=%s AND status=%d $order", array($this->_table ,$appid , $status));
	}
	
	public function fetch_all_by_search($condition,$orders='',$type=0,$start=0,$limit=0) {
		if ($type == 1) {
			$result = DB::fetch_all("SELECT * FROM %t WHERE 1%i $orders LIMIT $start,$limit",array($this->_table,$condition));
		} else {
			$n = DB::query("SELECT * FROM ".DB::table($this->_table)." WHERE 1$condition $orders");
			$result = DB::num_rows($n);
		}
		return $result;
	}
	
	public function fetch_by_appid_username($appid,$username,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE appid=%s AND username=%s $orders", array($this->_table, $appid, $username));
	}
	
	public function count_by_appid_status($appid,$status) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE appid=%s AND status=%d", array($this->_table, $appid ,$status));
	}
	
	public function count_by_appid_username($appid,$username) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE appid=%s AND username=%s", array($this->_table, $appid ,$username));
	}
	
	public function update_status_jobtime_moneytime_by_appid_username($appid,$username,$value1,$value2,$value3) {
		return DB::query("UPDATE %t SET status=%d,jobtime=%d,moneytime=%d WHERE appid=%s AND username=%s", array($this->_table, $value1, $value2, $value3, $appid, $username));
	}
	
	public function update_moneytime_by_appid_username($appid,$username,$value) {
		return DB::query("UPDATE %t SET moneytime=%d WHERE appid=%s AND username=%s", array($this->_table, $value, $appid, $username));
	}
	
	public function update_jobmoney_by_appid_username($appid,$username,$value) {
		return DB::query("UPDATE %t SET jobmoney=%d WHERE appid=%s AND username=%s", array($this->_table, $value, $appid, $username));
	}
	
	public function delete_by_appid_username($appid,$username) {
		return DB::query("DELETE FROM %t WHERE appid=%s AND username=%s", array($this->_table, $appid, $username));
	}

}

?>