<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include DISCUZ_ROOT.'./source/plugin/hux_credit/config.php';
$action = empty($_GET['action']) ? '' : daddslashes($_GET['action']);
$gpformhash = daddslashes($_GET['formhash']);
$gppage = daddslashes($_GET['page']);
$atclass[$action] = "class='a'";
$uid = $_G['uid'];
$username = $_G['username'];
$adminid = $_G['adminid'];
$closemsg = $csetting['closemsg'];
if ($csetting['open'] == '0') {
	showmessage("$closemsg","index.php");
}

if ($action == 'buy') {
	if(empty($uid)) showmessage('to_login', 'member.php?mod=logging&action=login', array(), array('showmsg' => true, 'login' => 1));
	if(submitcheck('addsubmit')){
		$moneynum = dintval($_GET['money']);
		$moneymin =  ($csetting['moneymin'] > 1) ? $csetting['moneymin'] : 1;
		if ($moneynum < $moneymin || $moneynum > 99999 || $moneynum == '') {
			showmessage('hux_credit:zzmin_msg','index.php');
		}
		$moneyorderid = dgmdate(TIMESTAMP,'YmdHis').random(6);
		$param = array(
			'orderid' => $moneyorderid,
			'title' => lang('plugin/hux_credit','zzmsg'),
			'price' => $moneynum,
			'paytype' => 'hux_credit',
			'timestamp' => TIMESTAMP,
			'other' => $moneynum,
		);

		ksort($param);
		$params = '';
		foreach($param as $k => $v) {
			$params .= '&'.$k.'='.rawurlencode($v);
		}
		$params .= '&hid='.$payhid.'&uid='.$uid.'&charset='.CHARSET.'&md5hash='.md5(substr($params, 1).$paypass);
		$r = $csetting['payurlopen'] ? $csetting['payurl'] : hux_get_data('http://api.k1cn.com/index.php?action=payurl&hid='.$payhid);
		//dheader('location:http://'.$r.'/plugin.php?id=hux_api:hux_api&huxac=pay&'.substr($params, 1));
		echo diconv(str_replace(array('<noscript>','</noscript>','"plugin.php','"source/plugin'),array('','','"http://'.$r.'/plugin.php','"http://'.$r.'/source/plugin'),hux_get_data('http://'.$r.'/plugin.php?id=hux_api:hux_api&huxac=pay&'.substr($params, 1))),'utf-8',CHARSET);
	} else {
		include template('hux_credit:hux_credit');
	}
}
?>