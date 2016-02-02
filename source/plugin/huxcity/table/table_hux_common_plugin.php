<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_common_plugin extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_plugin';
		$this->_pk    = 'pluginid';

		parent::__construct();
	}

	public function result_by_identifier($identifier,$field) {
		return DB::result_first("SELECT $field FROM %t WHERE identifier=%s", array($this->_table, $identifier));
	}

}

?>