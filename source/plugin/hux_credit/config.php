<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$csetting = $_G['cache']['plugin']['hux_credit'];
$payhid = $csetting['hid'];
$paypass = $csetting['paypass'];

function hux_get_data($url) {
	global $_G;
	if (function_exists('curl_init')) {
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_REFERER, $_G['siteurl']); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec($curl); 
		curl_close($curl);
	} else {
		$result = dfsockopen($url);
	}
	return $result;
}

?>