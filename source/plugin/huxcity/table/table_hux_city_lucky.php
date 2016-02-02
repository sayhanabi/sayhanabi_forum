<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_hux_city_lucky extends discuz_table
{

	public function __construct() {

		$this->_table = 'hux_city_lucky';
		$this->_pk    = 'eid';

		parent::__construct();
	}

	public function fetch_all() {
		return DB::fetch_all("SELECT * FROM %t", array($this->_table));
	}
	
	public function fetch_by_eid($eid,$field='*',$orders='') {
		return DB::fetch_first("SELECT $field FROM %t WHERE eid=%d $orders", array($this->_table, $eid));
	}
	
	public function insert($data) {
		return DB::insert($this->_table, $data);
	}
	
	public function delete_by_eid($eid) {
		return DB::query("DELETE FROM %t WHERE eid=%d", array($this->_table, $eid));
	}
	
	public function num_rows_by_search() {
		$n = DB::query("SELECT * FROM ".DB::table('hux_city_lucky'));
		return DB::num_rows($n);
	}
	
	public function fetch_all_by_search($start=0,$limit=0) {
		return DB::fetch_all("SELECT * FROM %t ORDER BY eid DESC limit $start,$limit",array($this->_table));
	}

}

?>