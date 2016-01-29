<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_cdc_html5eff {
		function global_cpnav_top() {
			return '<link rel="stylesheet" type="text/css" href="source/plugin/cdc_html5eff/images/html5.css"/>';
		}
		function global_usernav_extra2() {
			return '<link rel="stylesheet" type="text/css" href="source/plugin/cdc_html5eff/images/html5.css"/>';
		}
}
?>