<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_gojob_user extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_gojob_user';
		$this->_pk    = 'eid';

		parent::__construct();
	}
	
	public function fetch_by_typeid_username($typeid,$username,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE typeid=%d AND username=%s $orders", array($this->_table, $typeid, $username));
	}
	
	public function count_by_typeid_username($typeid,$username) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE typeid=%d AND username=%s $orders", array($this->_table, $typeid, $username));
	}
	
	public function delete_by_dateline($dateline) {
		return DB::query("DELETE FROM %t WHERE dateline<%d", array($this->_table, $dateline));
	}

}

?>