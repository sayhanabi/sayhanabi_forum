<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_common_member_magic extends discuz_table
{

	public function __construct() {

		$this->_table = 'common_member_magic';
		$this->_pk    = '';

		parent::__construct();
	}

	public function result_by_uid_magicid($uid,$magicid,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE uid=%d AND magicid=%d", array($this->_table, $uid,$magicid));
	}

	public function update_num_jia_by_uid_magicid($uid,$magicid,$value) {
		return DB::query("UPDATE %t SET num=num+%d WHERE uid=%d AND magicid=%d", array($this->_table, $value, $uid, $magicid));
	}
	
	public function update_num_jian_by_uid_magicid($uid,$magicid,$value) {
		return DB::query("UPDATE %t SET num=num-%d WHERE uid=%d AND magicid=%d", array($this->_table, $value, $uid, $magicid));
	}

}

?>