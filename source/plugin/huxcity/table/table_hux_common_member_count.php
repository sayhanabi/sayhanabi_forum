<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_common_member_count extends discuz_table_archive
{
	public function __construct() {

		$this->_table = 'common_member_count';
		$this->_pk    = 'uid';
		$this->_pre_cache_key = 'common_member_count_';

		parent::__construct();
	}

	public function fetch_by_uid($uid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE uid=%d $orders", array($this->_table, $uid));
	}

	public function update_credits_jia_by_uid($uid,$value) {
		global $paymoney;
		return DB::query("UPDATE %t SET $paymoney=$paymoney+%d WHERE uid=%d", array($this->_table, $value, $uid));
	}

	public function fetch_all_usertop($start=0,$limit=0) {
		global $paymoney;
		return DB::fetch_all("SELECT m.".$paymoney." as usermoney,rs.username,rs.uid FROM ".DB::table('hux_city_user')." rs LEFT JOIN ".DB::table('common_member_count')." m ON rs.uid = m.uid GROUP BY rs.uid ORDER BY $paymoney DESC LIMIT $start,$limit");
	}
	
	public function result_by_uid($uid,$value) {
		return DB::result_first("SELECT $value FROM %t WHERE uid=%d", array($this->_table, $uid));
	}

}

?>