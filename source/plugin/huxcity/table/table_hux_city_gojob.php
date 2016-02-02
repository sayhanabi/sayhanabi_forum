<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_gojob extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_gojob';
		$this->_pk    = 'eid';

		parent::__construct();
	}
	
	public function fetch_by_adtype($adtype,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE adtype=%s $orders", array($this->_table, $adtype));
	}
	
	public function fetch_by_eid($eid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE eid=%d $orders", array($this->_table, $eid));
	}
	
	public function result_by_adtype($adtype,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE adtype=%s", array($this->_table, $adtype));
	}
	
	public function num_rows_by_search($condition) {
		$n = DB::query("SELECT * FROM ".DB::table('hux_city_gojob')." WHERE 1=1$condition");
		return DB::num_rows($n);
	}
	
	public function fetch_all_by_search($condition,$start=0,$limit=0) {
		return DB::fetch_all("SELECT * FROM %t WHERE 1%i ORDER BY adhitmoney DESC,dateline DESC limit $start,$limit",array($this->_table,$condition));
	}
	
	public function update_adtext_dateline_by_adtype($adtype,$value1,$value2) {
		return DB::query("UPDATE %t SET adtext=%s,dateline=%d WHERE adtype=%s", array($this->_table, $value1, $value2, $adtype));
	}
	
	public function update_adtext_adurl_adhitmoney_by_eid($eid,$value1,$value2,$value3) {
		return DB::query("UPDATE %t SET adtext=%s,adurl=%s,adhitmoney=%d WHERE eid=%d", array($this->_table, $value1, $value2, $value3, $eid));
	}
	
	public function update_adhitnum_jia_by_eid($eid,$value) {
		return DB::query("UPDATE %t SET adhitnum=adhitnum+%d WHERE eid=%d", array($this->_table, $value, $eid));
	}
	
	public function update_admoney_jian_by_eid($eid,$value) {
		return DB::query("UPDATE %t SET admoney=admoney-%d WHERE eid=%d", array($this->_table, $value, $eid));
	}
	
	public function insert($data) {
		return DB::insert($this->_table, $data);
	}
	
	public function delete_by_eid($eid) {
		return DB::query("DELETE FROM %t WHERE eid=%d", array($this->_table, $eid));
	}
	
	public function delete_by_admoney_x_adtype($value,$adtype) {
		return DB::query("DELETE FROM %t WHERE (admoney=%d OR admoney<adhitmoney) AND adtype<>%s", array($this->_table, $value, $adtype));
	}

}

?>