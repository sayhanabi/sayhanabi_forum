<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function hux_count_days($date){
	$a_dt=getdate(TIMESTAMP);
	$b_dt=getdate($date);
	$a_new=mktime(0,0,0,$a_dt['mon'],$a_dt['mday'],$a_dt['year']);
	$b_new=mktime(0,0,0,$b_dt['mon'],$b_dt['mday'],$b_dt['year']);
	return round(($a_new-$b_new)/86400);
}

function hux_getmoneyop($money,$type){
	global $uid,$userpow,$appconfig,$paymoney,$banklang;
	$bankdata = C::t('#huxcity#hux_city_bank')->fetch_by_uid_type($uid,$type,'money,dateline,outtime');
	if ($type == 1) {
		updatemembercount($uid,array($paymoney => -$money));
		if ($bankdata) {
			$moneydate = hux_count_days($bankdata['dateline']);
			$lixi = intval($bankdata['money'] * $appconfig['hqfeilv'] * $moneydate);
			$savemoney = intval($money + $lixi);
			C::t('#huxcity#hux_city_bank')->update_money_jia_dateline_by_uid_type($uid,$type,$savemoney,TIMESTAMP);
		} else {
			$lixi = 0;
			$data_array = array(
				'uid' => $uid,
				'type' => $type,
				'money' => $money,
				'dateline' => TIMESTAMP,
				'outtime' => 0,
			);
			C::t('hux_city_bank')->insert($data_array);
		}
		$moneymsg = $banklang['huoqi'].$banklang['tocun'].' '.$banklang['moneynum'].$money.' '.$banklang['liximsg'];
		$moneymsg = str_replace(array('{lixi}'),array($lixi),$moneymsg);
	} elseif ($type == 2) {
		updatemembercount($uid,array($paymoney => -$money));
		$outtime = TIMESTAMP + $appconfig['dqdate'] * 86400;
		if ($bankdata) {
			$moneydate = hux_count_days($bankdata['dateline']);
			if ($bankdata['outtime'] < TIMESTAMP) {
				$lixi = intval($bankdata['money'] * $appconfig['dqfeilv'] * $moneydate);
				$savemoney = intval($money + $lixi);			
			} else {
				$lixi = 0;
				$savemoney = $money;
			}
			C::t('#huxcity#hux_city_bank')->update_money_jia_dateline_outtime_by_uid_type($uid,$type,$savemoney,TIMESTAMP,$outtime);
		} else {
			$lixi = 0;
			$data_array = array(
				'uid' => $uid,
				'type' => $type,
				'money' => $money,
				'dateline' => TIMESTAMP,
				'outtime' => $outtime,
			);
			C::t('hux_city_bank')->insert($data_array);
		}
		$moneymsg = $banklang['dingqi'].$banklang['tocun'].' '.$banklang['moneynum'].$money.' '.$banklang['liximsg'];
		$moneymsg = str_replace(array('{lixi}'),array($lixi),$moneymsg);
	}
	$data_array2 = array(
		'uid' => $uid,
		'moneymsg' => $moneymsg,
		'dateline' => TIMESTAMP,
	);
	C::t('hux_city_bank_log')->insert($data_array2);
	if ($userpow > 0) {
		C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['paypower']);
	}
}

function hux_getmoneyop_draw($money,$type){
	global $uid,$userpow,$appconfig,$paymoney,$banklang;
	$bankdata = C::t('#huxcity#hux_city_bank')->fetch_by_uid_type($uid,$type,'money,dateline');
	updatemembercount($uid,array($paymoney => $money));
	$moneydate = hux_count_days($bankdata['dateline']);
	if ($type == 1) {
		$lixi = intval($bankdata['money'] * $appconfig['hqfeilv'] * $moneydate);
		C::t('#huxcity#hux_city_bank')->update_money_jia_dateline_by_uid_type($uid,$type,$lixi,TIMESTAMP);
		C::t('#huxcity#hux_city_bank')->update_money_jian_dateline_by_uid_type($uid,$type,$money,TIMESTAMP);
		$moneymsg = $banklang['huoqi'].$banklang['toqu'].' '.$banklang['moneynum'].$money.' '.$banklang['liximsg'];
		$moneymsg = str_replace(array('{lixi}'),array($lixi),$moneymsg);
	} elseif ($type == 2) {
		$lixi = intval($bankdata['money'] * $appconfig['dqfeilv'] * $moneydate);
		C::t('#huxcity#hux_city_bank')->delete_by_uid_type($uid,$type);
		$moneymsg = $banklang['dingqi'].$banklang['toqu'].' '.$banklang['moneynum'].$money.' '.$banklang['liximsg'];
		$moneymsg = str_replace(array('{lixi}'),array($lixi),$moneymsg);
	}
	$data_array2 = array(
		'uid' => $uid,
		'moneymsg' => $moneymsg,
		'dateline' => TIMESTAMP,
	);
	C::t('hux_city_bank_log')->insert($data_array2);
	if ($userpow > 0) {
		C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['paypower']);
	}
}

function hux_getmoneyop_dk($money,$type){
	global $uid,$userpow,$appconfig,$paymoney,$banklang;
	if ($type == 1) {
		updatemembercount($uid,array($paymoney => $money));
		$outtime = TIMESTAMP + $appconfig['dkdate'] * 86400;
		C::t('#huxcity#hux_city_bank_log')->update_exp_jian_by_uid($uid,$money);
		$data_array = array(
			'uid' => $uid,
			'type' => 3,
			'money' => $money,
			'dateline' => TIMESTAMP,
			'outtime' => $outtime,
		);
		C::t('hux_city_bank')->insert($data_array);	
		$moneymsg = $banklang['daikuan'].' '.$banklang['moneynum'].$money;
		$moneymsg = str_replace(array('{lixi}'),array($lixi),$moneymsg);
	} elseif ($type == 2) {
		updatemembercount($uid,array($paymoney => -$money));
		C::t('#huxcity#hux_city_bank_log')->update_exp_jia_by_uid($uid,$money);
		C::t('#huxcity#hux_city_bank')->delete_by_uid_type($uid,3);	
		$moneymsg = $banklang['pluginname'].$banklang['tohuan'].' '.$banklang['moneynum'].$money;
	}
	$data_array2 = array(
		'uid' => $uid,
		'moneymsg' => $moneymsg,
		'dateline' => TIMESTAMP,
	);
	C::t('hux_city_bank_log')->insert($data_array2);
	if ($userpow > 0) {
		C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['paypower']);
	}
}

function hux_getbankinfo($uid){
	global $appconfig;
	$hqinfo = C::t('#huxcity#hux_city_bank')->fetch_by_uid_type($uid,1);
	$dqinfo = C::t('#huxcity#hux_city_bank')->fetch_by_uid_type($uid,2);
	$dkinfo = C::t('#huxcity#hux_city_bank')->fetch_by_uid_type($uid,3);
	$hqmoneydate = hux_count_days($hqinfo['dateline']);
	$hqlixi = intval($hqinfo['money'] * $appconfig['hqfeilv'] * $hqmoneydate);
	$dqmoneydate = hux_count_days($dqinfo['dateline']);
	$dqlixi = intval($dqinfo['money'] * $appconfig['dqfeilv'] * $dqmoneydate);
	$dkmoneydate = hux_count_days($dkinfo['dateline']);
	$dklixi = intval($dkinfo['money'] * $appconfig['dkfeilv'] * $dkmoneydate);
	if ($hqinfo) {
		$hqtrue = 1;
	} else {
		$hqtrue = 0;
	}
	if ($dqinfo) {
		$dqtrue = 1;
	} else {
		$dqtrue = 0;
	}
	if ($dkinfo) {
		$dktrue = 1;
	} else {
		$dktrue = 0;
	}
	$infofields = array(
		'hqmoney' => $hqinfo['money'],
		'dqmoney' => $dqinfo['money'],
		'dkmoney' => $dkinfo['money'],
		'hqtime' => $hqinfo['dateline'],
		'dqtime' => $dqinfo['dateline'],
		'dktime' => $dkinfo['dateline'],
		'outtime' => $dqinfo['outtime'],
		'dkouttime' => $dkinfo['outtime'],
		'hqlixi' => $hqlixi,
		'dqlixi' => $dqlixi,
		'dklixi' => $dklixi,
		'hqtrue' => $hqtrue,
		'dqtrue' => $dqtrue,
		'dktrue' => $dktrue,
	);
	return $infofields;
}

function hux_getpageinfo($listnum){
	global $uid;
	$totalNum = C::t('#huxcity#hux_city_bank_log')->count_by_uid($uid);
	$totalPage = ceil($totalNum/$itemNum);
	$setPageArr['tn']=$totalNum;
	$setPageArr['tp']=$totalPage;
	return $setPageArr;
}

function hux_getoplist($page,$listnum){
	global $uid;
	$oplist = array();
	$num = ($page-1)*$listnum;
	$query = C::t('#huxcity#hux_city_bank_log')->fetch_all_by_search(" AND uid='".$uid."'",$num,$listnum);
	foreach ($query as $row) {
		$row['dateline'] = dgmdate($row['dateline'],'Y-m-d H:i:s');
		$oplist[] = $row;
	}
	return $oplist;
}

?>