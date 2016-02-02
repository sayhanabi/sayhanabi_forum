<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_common_magic extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_magic';
		$this->_pk    = 'magicid';

		parent::__construct();
	}

	public function result_by_magicid($magicid,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE magicid=%d", array($this->_table, $magicid));
	}

	public function result_by_identifier($identifier,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE identifier=%s", array($this->_table, $identifier));
	}

}

?>