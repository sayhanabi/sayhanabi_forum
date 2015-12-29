<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: task_avatar.php 16433 2010-09-07 00:04:33Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class task_avatar {

	var $version = '1.0';
	var $name = 'avatar_name';
	var $description = 'avatar_desc';
	var $copyright = '<a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>';
	var $icon = '';
	var $period = '';
	var $periodtype = 0;
	var $conditions = array();

	function csc($task = array()) {
		global $_G;
		$uid = $_G['uid'];
		$url = "https://say-hanabi.com/uc_server/avatar.php?uid=$uid&size=small";
    		$ch = curl_init($url);
    		curl_setopt($ch, CURLOPT_HEADER, 0);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    		$raw=curl_exec($ch);
		$avt = md5(base64_encode($raw));
		if($avt == "f71494693bdb4c3c309236b774e8f374") {
			return array('csc' => 0, 'remaintime' => 0);
		} else {
			return true;
		}
	}

	function view() {
		return lang('task/avatar', 'avatar_view');
	}

}

?>
