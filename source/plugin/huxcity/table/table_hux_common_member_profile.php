<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_common_member_profile extends discuz_table_archive
{
	//private $_fields;

	public function __construct() {

		$this->_table = 'common_member_profile';
		$this->_pk    = 'uid';
		$this->_pre_cache_key = 'common_member_profile_';
		//$this->_fields = array('qq');

		parent::__construct();
	}

	public function result_by_uid($uid,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE uid=%d", array($this->_table, $uid));
	}

	public function fetch_all_IN_by_uid($uid,$field='*') {
		return DB::fetch_all("SELECT $field FROM %t WHERE uid IN($uid)", array($this->_table));
	}

}

?>