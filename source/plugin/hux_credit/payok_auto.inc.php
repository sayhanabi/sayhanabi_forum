<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include DISCUZ_ROOT.'./source/plugin/hux_credit/config.php';
$paymoney = 'extcredits'.$csetting['moneytype'];
$orderid = daddslashes($_GET['orderid']);
$param = array(
	'hid' => $payhid,
	'orderid' => $orderid,
);
ksort($param);
$params = '';
foreach($param as $k => $v) {
	$params .= '&'.$k.'='.rawurlencode($v);
}
$params .= '&md5hash='.md5(substr($params, 1).$paypass);
$rrrrr = $csetting['payurlopen'] ? $csetting['payurl'] : hux_get_data('http://api.k1cn.com/index.php?action=payurl&hid='.$payhid);
$r = hux_get_data('http://'.$rrrrr.'/plugin.php?id=hux_api:pay&action=getresult&'.substr($params, 1));
$paystatus = explode(',',$r);
if ($paystatus[0] == '1') {
	$jfnum = intval($paystatus[2] * $csetting['moneybl']);
	updatemembercount($paystatus[1] , array($paymoney => $jfnum), 1, 'AFD', $paystatus[1]);
	notification_add($paystatus[1],'system',lang('plugin/hux_credit','zz_sus'),0,1);
	echo 1;
} else {
	echo 0;
}
?>