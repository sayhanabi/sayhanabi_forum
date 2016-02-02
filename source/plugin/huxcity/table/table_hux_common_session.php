<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_common_session extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_session';
		$this->_pk    = 'sid';

		parent::__construct();
	}

	public function count_by_uid_invisible($uid,$type = 0) {
		return DB::result_first('SELECT COUNT(*) FROM %t WHERE uid=%d AND invisible=%d', array($this->_table, $uid, $type));
	}

}

?>