<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_common_syscache extends discuz_table
{

	public function __construct() {

		$this->_table = 'common_syscache';
		$this->_pk    = 'cname';

		parent::__construct();
	}

	public function result_by_cname($cname,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE cname=%s", array($this->_table, $cname));
	}
}

?>