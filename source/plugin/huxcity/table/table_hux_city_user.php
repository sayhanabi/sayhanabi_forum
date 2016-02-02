<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_user extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_user';
		$this->_pk    = 'eid';

		parent::__construct();
	}

	public function fetch_by_uid($uid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE uid=%d $orders", array($this->_table, $uid));
	}

	public function fetch_all_IN_by_uid($uid) {
		return DB::fetch_all("SELECT * FROM %t WHERE uid IN ($uid)", array($this->_table));
	}

	public function result_by_username($username,$value) {
		return DB::result_first("SELECT $value FROM %t WHERE username=%s", array($this->_table, $username));
	}

	public function fetch_all_usernew($start=0,$limit=0) {
		return DB::fetch_all("SELECT username,regtime,uid FROM %t ORDER BY regtime DESC LIMIT $start,$limit", array($this->_table));
	}

	public function count_all() {
		return DB::result_first("SELECT COUNT(*) FROM %t", array($this->_table));
	}

	public function result_id_min_by_eid($eid) {
		return DB::result_first("SELECT min(eid) FROM %t WHERE eid>%d ORDER BY eid ASC", array($this->_table, $eid));
	}

	public function result_id_max_by_eid($eid) {
		return DB::result_first("SELECT max(eid) FROM %t WHERE eid<%d ORDER BY eid ASC", array($this->_table, $eid));
	}

	public function result_by_eid($eid,$value) {
		return DB::result_first("SELECT $value FROM %t WHERE eid=%d", array($this->_table, $eid));
	}
	
	public function count_by_myid($myid) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE myid=%s", array($this->_table, $myid));
	}
	
	public function fetch_by_myid($myid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE myid=%s $orders", array($this->_table, $myid));
	}
	
	public function fetch_by_eid($eid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE eid=%d $orders", array($this->_table, $eid));
	}
	
	public function fetch_by_username($username,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE username=%s $orders", array($this->_table, $username));
	}
	
	public function num_rows_by_search($condition) {
		$n = DB::query("SELECT * FROM ".DB::table('hux_city_user')." WHERE 1=1$condition");
		return DB::num_rows($n);
	}
	
	public function fetch_all_by_search($condition,$start=0,$limit=0) {
		return DB::fetch_all("SELECT * FROM %t WHERE 1%i ORDER BY eid DESC limit $start,$limit",array($this->_table,$condition));
	}
	
	public function fetch_list_by_search($condition,$orders='',$type=0,$start=0,$limit=0) {
		if ($type == 1) {
			$result = DB::fetch_all("SELECT * FROM %t WHERE 1%i $orders LIMIT $start,$limit",array($this->_table,$condition));
		} else {
			$n = DB::query("SELECT * FROM ".DB::table($this->_table)." WHERE 1$condition $orders");
			$result = DB::num_rows($n);
		}
		return $result;
	}
	
	public function update_gender_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET gender=%d WHERE uid=%d", array($this->_table, $value, $uid));
	}
	
	public function update_luckytime_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET luckytime=%d WHERE uid=%d", array($this->_table, $value, $uid));
	}
	
	public function update_bantime_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET bantime=%d WHERE uid=%d", array($this->_table, $value, $uid));
	}
	
	public function update_bantime_by_username($username,$value) {
		return DB::query("UPDATE %t SET bantime=%d WHERE username=%s", array($this->_table, $value, $username));
	}
	
	public function update_bantime_jian_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET bantime=bantime-%d WHERE uid=%d", array($this->_table, $value, $uid));
	}
	
	public function update_bantime_dajie_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET bantime=%d WHERE uid=%d AND bantime='0'", array($this->_table, $value, $uid));
	}
	
	public function update_bantime_by_bantime($bantime,$value) {
		return DB::query("UPDATE %t SET bantime=%d WHERE bantime<>%d", array($this->_table, $value, $bantime));
	}
	
	public function update_atk_def_by_uid($uid,$value1,$value2) {
		return DB::query("UPDATE %t SET atk=%d,def=%d WHERE uid=%d", array($this->_table, $value1, $value2, $uid));
	}
	
	public function update_cid_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET cid=%d WHERE uid=%d", array($this->_table, $value, $uid));
	}
	
	public function update_cid_by_uid_cid($uid,$cid,$value) {
		return DB::query("UPDATE %t SET cid=%d WHERE uid=%d AND cid=%d", array($this->_table, $value, $uid, $cid));
	}
	
	public function update_cid($value) {
		return DB::query("UPDATE %t SET cid=%d", array($this->_table, $value));
	}
	
	public function update_myid($value) {
		return DB::query("UPDATE %t SET myid=%s", array($this->_table, $value));
	}
	
	public function update_cid_by_cid($cid,$value) {
		return DB::query("UPDATE %t SET cid=%d WHERE cid=%d", array($this->_table, $value, $cid));
	}
	
	public function update_myid_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET myid=%s WHERE uid=%d", array($this->_table, $value, $uid));
	}
	
	public function update_power_jian_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET power=power-%d WHERE uid=%d", array($this->_table, $value, $uid));
	}
	
	public function update_power_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET power=%d WHERE uid=%d", array($this->_table, $value, $uid));
	}

	public function update_exp_jia_by_uid($uid,$value) {
		return DB::query("UPDATE %t SET exp=exp+%d WHERE uid=%d", array($this->_table, $value, $uid));
	}

}

?>