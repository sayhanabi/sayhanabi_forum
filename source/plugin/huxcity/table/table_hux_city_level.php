<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_level extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_level';
		$this->_pk    = 'eid';

		parent::__construct();
	}

	public function fetch_by_search($condition,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE 1 %i $orders", array($this->_table, $condition));
	}

	public function result_by_eid_appid($eid,$appid,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE eid=%d AND appid=%s", array($this->_table, $eid, $appid));
	}
	
	public function result_by_magicid_eid_appid($magicid,$eid,$appid,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE magicid=%d AND eid=%d AND appid=%s", array($this->_table, $magicid, $eid, $appid));
	}
	
	public function result_by_magicid_exp_appid($magicid,$exp,$appid,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE magicid=%d AND exp=%d AND appid=%s", array($this->_table, $magicid, $exp, $appid));
	}
	
	public function result_by_eid($eid,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE eid=%d", array($this->_table, $eid));
	}
	
	public function result_by_appid_level($appid,$level,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE appid=%s AND level=%s", array($this->_table, $appid, $level));
	}

	public function result_userlevel_min_by_exp($exp) {
		return DB::result_first("SELECT min(exp) FROM %t WHERE exp>%d", array($this->_table, $exp));
	}

	public function result_userlevel_max_by_exp($exp) {
		return DB::result_first("SELECT max(exp) FROM %t WHERE exp<=%d", array($this->_table, $exp));
	}

	public function fetch_all_by_appid($appid,$field='*',$order='') {
		return DB::fetch_all("SELECT $field FROM %t WHERE appid=%s $order", array($this->_table ,$appid));
	}
	
	public function fetch_all_by_appid_magicid($appid,$magicid,$field='*',$order='') {
		return DB::fetch_all("SELECT $field FROM %t WHERE appid=%s AND magicid=%d $order", array($this->_table ,$appid ,$magicid));
	}
	
	public function fetch_all_by_appid_magicid_exp($appid,$magicid,$exp,$field='*',$order='') {
		return DB::fetch_all("SELECT $field FROM %t WHERE appid=%s AND magicid=%d AND exp<%d $order", array($this->_table ,$appid ,$magicid ,$exp));
	}
	
	public function fetch_by_exp_appid($exp,$appid,$field='*',$orders='',$start=0,$limit=0) {
		return DB::fetch_first("SELECT $field FROM %t WHERE exp<=%d AND appid=%s $orders LIMIT $start,$limit", array($this->_table, $exp,$appid));
	}
	
	public function fetch_by_appid_level($appid,$level,$field='*',$orders='',$start=0,$limit=0) {
		return DB::fetch_first("SELECT $field FROM %t WHERE appid=%s AND level=%s $orders LIMIT $start,$limit", array($this->_table, $appid,$level));
	}
	
	public function fetch_by_eid($eid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE eid=%d $orders", array($this->_table, $eid));
	}
	
	public function fetch_by_appid($appid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE appid=%s $orders", array($this->_table, $appid));
	}
	
	public function count_x_by_exp($value1,$value2,$value3,$value4) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE (exp<=%d AND exp>=%d) OR (exp<=%d AND exp>=%d)", array($this->_table, $value1,$value2,$value3,$value4));
	}
	
	public function count_x_by_appid_magicid($value1,$value2,$value3) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE (appid=%s OR appid=%s) AND magicid=%d", array($this->_table, $value1,$value2,$value3));
	}
	
	public function count_by_appid_magicid($appid,$magicid) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE appid=%s AND magicid=%d", array($this->_table, $appid,$magicid));
	}
	
	public function count_by_level_appid($level,$appid) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE level=%s AND appid=%s", array($this->_table, $level,$appid));
	}
	
	public function delete_by_appid_magicid($appid,$magicid) {
		return DB::query("DELETE FROM %t WHERE appid=%s AND magicid=%d", array($this->_table, $appid, $magicid));
	}
	
	public function delete_by_appid($appid) {
		return DB::query("DELETE FROM %t WHERE appid=%s", array($this->_table, $appid));
	}
	
	public function update_exp_by_magicid_appid($magicid,$appid,$value) {
		return DB::query("UPDATE %t SET exp=%d WHERE magicid=%d AND appid=%s", array($this->_table, $value, $magicid, $appid));
	}
	
	public function update_level_exp_by_eid($eid,$value1,$value2) {
		return DB::query("UPDATE %t SET level=%s,exp=%d WHERE eid=%d", array($this->_table, $value1, $value2, $eid));
	}
	
	public function update_level_sxplus_by_eid($eid,$value1,$value2) {
		return DB::query("UPDATE %t SET level=%s,sxplus=%d WHERE eid=%d", array($this->_table, $value1, $value2, $eid));
	}
	
	public function update_exp_magicid_sxplus_by_eid($eid,$value1,$value2,$value3) {
		return DB::query("UPDATE %t SET exp=%d,magicid=%d,sxplus=%d WHERE eid=%d", array($this->_table, $value1, $value2, $value3, $eid));
	}
	
	public function update_sxplus_by_exp_sxplus_appid($exp,$sxplus,$appid,$value) {
		return DB::query("UPDATE %t SET sxplus=%d WHERE exp=%d AND sxplus>%d AND appid=%s", array($this->_table, $value, $exp, $sxplus, $appid));
	}
	
	public function sum_sxplus_by_appid_magicid_eid($appid,$magicid,$eid) {
		return DB::result_first("SELECT SUM(sxplus) FROM %t WHERE appid=%s AND magicid=%d AND eid<>%d", array($this->_table, $appid,$magicid,$eid));
	}
	
	public function sum_sxplus_by_appid_magicid($appid,$magicid) {
		return DB::result_first("SELECT SUM(sxplus) FROM %t WHERE appid=%s AND magicid=%d", array($this->_table, $appid,$magicid));
	}

}

?>