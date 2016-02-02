<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$_GET['handlekey'] = empty($_GET['handlekey']) ? 'bank' : dhtmlspecialchars(addslashes($_GET['handlekey']));
$closemsg = $appconfig['closemsg'];
$hqfeilv = round($appconfig['hqfeilv'] * 100,2).'%';
$dqfeilv = round($appconfig['dqfeilv'] * 100,2).'%';
$dkfeilv = round($appconfig['dkfeilv'] * 100,2).'%';
if ($appconfig['open'] == '0' && $adminid != '1' && $cityadmin != $username) {
	showmessage("$closemsg","plugin.php?id=huxcity:huxcity");
}

if ($userpow < $appconfig['paypower']) {
	showmessage('huxcity:no_pow','plugin.php?id=huxcity:huxcity&mod=hotel');
}

include $appmod_root.'/bank/bank_fun.php';
$hux_getbankinfo = hux_getbankinfo($uid);
$hux_getbankinfo['hqtime'] = dgmdate($hux_getbankinfo['hqtime'],'Y-m-d H:i:s');
$hux_getbankinfo['dqtime'] = dgmdate($hux_getbankinfo['dqtime'],'Y-m-d H:i:s');
$hux_getbankinfo['outtime'] = dgmdate($hux_getbankinfo['outtime'],'Y-m-d H:i:s');
$dktime = dgmdate($hux_getbankinfo['dktime'],'Y-m-d H:i:s');
$hux_getbankinfo['dkouttime'] = dgmdate($hux_getbankinfo['dkouttime'],'Y-m-d H:i:s');
$savemoneysum = C::t('#huxcity#hux_city_bank')->sum_by_type(3);
$huankuan_all = intval($hux_getbankinfo['dkmoney'] + $hux_getbankinfo['dklixi']);

if ($action == 'admin') {

	if($adminid != '1' && $cityadmin != $username){
		showmessage('huxcity:not_allow');
	}

	if(submitcheck('addsubmit')){
		$bankopen = intval($_GET['bankopen']);
		$bankclosemsg = dhtmlspecialchars(addslashes($_GET['bankclosemsg']));
		$bankpaypower = intval($_GET['bankpaypower']);
		$banknotice = dhtmlspecialchars(addslashes($_GET['banknotice']));
		$bankdkopen = intval($_GET['bankdkopen']);
		$bankinmax = intval($_GET['bankinmax']);
		$bankoutmax = intval($_GET['bankoutmax']);
		$bankdkmin = intval($_GET['bankdkmin']);
		$bankdkmax = intval($_GET['bankdkmax']);
		$bankjiaoyitime = intval($_GET['bankjiaoyitime']);
		$bankhqfeilv = dhtmlspecialchars(addslashes($_GET['bankhqfeilv']));
		$bankdqfeilv = dhtmlspecialchars(addslashes($_GET['bankdqfeilv']));
		$bankdkfeilv = dhtmlspecialchars(addslashes($_GET['bankdkfeilv']));
		$bankdqdate = intval($_GET['bankdqdate']);
		$bankdkdate = intval($_GET['bankdkdate']);
		$bankclear_all = intval($_GET['clear_all']);
		$bankadminarr = 'open:'.$bankopen.'||closemsg:'.$bankclosemsg.'||paypower:'.$bankpaypower.'||notice:'.$banknotice.'||dkopen:'.$bankdkopen.'||inmax:'.$bankinmax.'||outmax:'.$bankoutmax.'||dkmin:'.$bankdkmin.'||dkmax:'.$bankdkmax.'||jiaoyitime:'.$bankjiaoyitime.'||hqfeilv:'.$bankhqfeilv.'||dqfeilv:'.$bankdqfeilv.'||dkfeilv:'.$bankdkfeilv.'||dqdate:'.$bankdqdate.'||dkdate:'.$bankdkdate;
		C::t('#huxcity#hux_city_config')->update_configs_appadmin_by_appid('bank',$bankadminarr,'');
		$appconfigsql = C::t('#huxcity#hux_city_config')->result_by_appid('bank','configs');
		$cacheArray = $appconfigsql;
		if ($_G['cache']['huxcity_data']['bank']) {
			$cacheArray_data = str_replace($_G['cache']['huxcity_data']['bank'],$cacheArray,$_G['cache']['huxcity_data']);
		} else {
			$cacheArray_Old = $_G['cache']['huxcity_data'];
			foreach($cacheArray_Old as $key => $value){
				$cacheArray_data[$key] = $value;
			}
			$cacheArray_data['bank'] = $cacheArray;
		}
		save_syscache('huxcity_data',$cacheArray_data);
		if ($bankclear_all == 1) {
			C::t('#huxcity#hux_city_bank_log')->delete_all();
		}
		showmessage('huxcity:op_sus',dreferer());
	}
} elseif ($action == 'save') {
	$optype = intval($_GET['btype']);
	if(submitcheck('submit')){
		$savemoney = intval($_GET['savemoney']);
		if ($mycash < $savemoney) {
			showmessage('huxcity:no_money','',array(),array('showdialog' => 1));
		}
		if ($savemoney < 1 || $savemoney > $appconfig['inmax']) {
			showmessage($banklang['savemoneymsg'], '', array('inmax' => $appconfig['inmax']), array('showdialog' => 1));
		}
		$optime = C::t('#huxcity#hux_city_bank_log')->fetch_by_uid($uid,'dateline','ORDER BY eid DESC');
		if ($optime && (TIMESTAMP - $optime['dateline']) < $appconfig['jiaoyitime']) {
			showmessage($banklang['jiaoyitimemsg'],'',array('jiaoyitime' => $appconfig['jiaoyitime']), array('showdialog' => 1));
		}
		hux_getmoneyop($savemoney,$optype);
		showmessage('huxcity:op_sus', dreferer(), array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'right'));
	}
} elseif ($action == 'draw') {
	$optype = intval($_GET['btype']);
	if(submitcheck('submit')){
		$drawmoney = intval($_GET['drawmoney']);
		$drawmoneydata = C::t('#huxcity#hux_city_bank')->fetch_by_uid_type($uid,$optype,'money,dateline,outtime');
		$moneydatedraw = hux_count_days($drawmoneydata['dateline']);
		if ($drawmoney < 1 || $drawmoney > $appconfig['outmax']) {
			showmessage($banklang['savemoneymsg'], '', array('inmax' => $appconfig['outmax']), array('showdialog' => 1));
		}
		$optime = C::t('#huxcity#hux_city_bank_log')->fetch_by_uid($uid,'dateline','ORDER BY eid DESC');
		if ($optime && (TIMESTAMP - $optime['dateline']) < $appconfig['jiaoyitime']) {
			showmessage($banklang['jiaoyitimemsg'],'',array('jiaoyitime' => $appconfig['jiaoyitime']), array('showdialog' => 1));
		}
		if ($optype == 1) {
			$drawmoneyall = $drawmoneydata['money'] + intval($drawmoneydata['money'] * $appconfig['hqfeilv'] * $moneydatedraw);
			if (!$drawmoneydata || $drawmoneyall < $drawmoney) {
				showmessage($banklang['no_cunkuan'],'',array(),array('showdialog' => 1));
			}
		} elseif ($optype == 2) {
			$drawmoneyall = $drawmoneydata['money'] + intval($drawmoneydata['money'] * $appconfig['dqfeilv'] * $moneydatedraw);
			if (!$drawmoneydata || (TIMESTAMP < $drawmoneydata['outtime'] && $drawmoneydata['money'] < $drawmoney) || (TIMESTAMP >= $drawmoneydata['outtime'] && $drawmoneyall < $drawmoney)) {
				showmessage($banklang['no_cunkuan'],'',array(),array('showdialog' => 1));
			}
			if (TIMESTAMP < $drawmoneydata['outtime'] && $drawmoneydata['money'] != $drawmoney) {
				showmessage($banklang['qu_all'],'',array('dqmoneynum' => $drawmoneydata['money']),array('showdialog' => 1,'alert' => 'info'));
			}
			if (TIMESTAMP >= $drawmoneydata['outtime'] && $drawmoneyall != $drawmoney) {
				showmessage($banklang['qu_all'],'',array('dqmoneynum' => $drawmoneyall),array('showdialog' => 1,'alert' => 'info'));
			}
		}
		hux_getmoneyop_draw($drawmoney,$optype);
		showmessage('huxcity:op_sus', dreferer(), array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'right'));
	}
} elseif ($action == 'dai') {
	if(submitcheck('submit') && $appconfig['dkopen'] == 1){
		$daimoney = intval($_GET['daimoney']);
		$daimoneydata = C::t('#huxcity#hux_city_bank')->fetch_by_uid_type($uid,3,'money,dateline,outtime');
		if ($daimoneydata && $daimoneydata['outtime'] < TIMESTAMP) {
			C::t('#huxcity#hux_city_bank')->delete_by_uid_type($uid,3);
			showmessage($banklang['dai_del'],dreferer(),array(),array('showdialog' => 1,'locationtime' => 0,'alert' => 'info'));
		} elseif($daimoneydata && $daimoneydata['outtime'] >= TIMESTAMP) {
			showmessage($banklang['daikuanmsg'],'',array(),array('showdialog' => 1));
		}
		if ($myexp < $daimoney) {
			showmessage($banklang['diya_msg'],'',array(),array('showdialog' => 1));
		}
		if ($daimoney < $appconfig['dkmin'] || $drawmoney > $appconfig['dkmax']) {
			showmessage($banklang['daimoneymsg'], '', array('dkmin' => $appconfig['dkmin'],'dkmax' => $appconfig['dkmax']), array('showdialog' => 1,'alert' => 'info'));
		}
		hux_getmoneyop_dk($daimoney,1);
		showmessage('huxcity:op_sus', dreferer(), array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'right'));
	}
} elseif ($action == 'huan') {
	if(submitcheck('submit')){
		$huanmoney = intval($_GET['huanmoney']);
		$huanmoneydata = C::t('#huxcity#hux_city_bank')->fetch_by_uid_type($uid,3,'money,dateline,outtime');
		if ($huanmoneydata && $huanmoneydata['outtime'] < TIMESTAMP) {
			C::t('#huxcity#hux_city_bank')->delete_by_uid_type($uid,3);
			showmessage($banklang['dai_del'],dreferer(),array(),array('showdialog' => 1,'locationtime' => 0,'alert' => 'info'));
		}
		if(!$huanmoneydata) {
			showmessage($banklang['not_huan'],'',array(),array('showdialog' => 1));
		}
		if ($mycash < $huanmoney) {
			showmessage('huxcity:no_money','',array(),array('showdialog' => 1));
		}
		if ($huanmoney != $huankuan_all) {
			showmessage($banklang['huankuan_all'], '', array(), array('showdialog' => 1));
		}
		$huankuan_time = intval($dktime + 86400);
		if (TIMESTAMP > $huankuan_time) {
			showmessage($banklang['huankuan_time'], '', array(), array('showdialog' => 1));
		}
		hux_getmoneyop_dk($huanmoney,2);
		showmessage('huxcity:op_sus', dreferer(), array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'right'));
	}
} else {
	$perpage = 20;
	$numd = C::t('#huxcity#hux_city_bank_log')->num_rows_by_search(" AND uid='".$uid."'");
	$page = max(1, intval($_GET['page']));
	$resultList = hux_getoplist($page,$perpage);
	$multi = multi($numd, $perpage, $page, "plugin.php?id=huxcity:huxcity&mod=bank");
}

include template('huxcity:index');
?>