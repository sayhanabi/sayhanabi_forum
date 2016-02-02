<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$closemsg = $appconfig['closemsg'];
if ($appconfig['open'] == '0' && $adminid != '1' && $cityadmin != $username) {
	showmessage("$closemsg","plugin.php?id=huxcity:huxcity");
}

$betadminuid = C::t('common_member')->fetch_uid_by_username($appconfigsql['appadmin']);
$zhuangzhumoney = C::t('#huxcity#hux_common_member_count')->result_by_uid($betadminuid,$paymoney);

if (($appconfigsql['appadmintime'] != 0 && $appconfigsql['appadmintime'] < TIMESTAMP) || ($zhuangzhumoney < $appconfig['betadminmin'] || $zhuangzhumoney == 0)) {
	C::t('#huxcity#hux_city_config')->update_appadmin_appadmintime_by_appid('bet','',0);
}

if ($action == 'admin') {

	if($adminid != '1' && $cityadmin != $username){
		showmessage('huxcity:not_allow');
	}

	if(submitcheck('addsubmit')){
		$betopen = intval($_GET['betopen']);
		$betclosemsg = dhtmlspecialchars(addslashes($_GET['betclosemsg']));
		$betpaypower = intval($_GET['betpaypower']);
		$betadmin = dhtmlspecialchars(addslashes($_GET['betadmin']));
		$betadminmin = intval($_GET['betadminmin']);
		$betadminreg = intval($_GET['betadminreg']);
		$bettzmin = intval($_GET['bettzmin']);
		$bettzmax = intval($_GET['bettzmax']);
		$betopenzb = intval($_GET['betopenzb']);
		$betzjglnum = dhtmlspecialchars(addslashes($_GET['betzjglnum']));
		$betadminarr = 'betopen:'.$betopen.'||betclosemsg:'.$betclosemsg.'||betpaypower:'.$betpaypower.'||betadminmin:'.$betadminmin.'||betadminreg:'.$betadminreg.'||bettzmin:'.$bettzmin.'||bettzmax:'.$bettzmax.'||betopenzb:'.$betopenzb.'||betzjglnum:'.$betzjglnum;
		$oldadmin = C::t('#huxcity#hux_city_config')->result_by_appid('bet','appadmin');
		C::t('#huxcity#hux_city_config')->update_configs_appadmin_by_appid('bet',$betadminarr,$betadmin);
		if ($oldadmin != $betadmin) {
			C::t('#huxcity#hux_city_config')->update_appadmintime_by_appid('bet',0);
		}
			$appconfigsql = C::t('#huxcity#hux_city_config')->result_by_appid('bet','configs');
			$cacheArray = $appconfigsql;
			if ($_G['cache']['huxcity_data']['bet']) {
				$cacheArray_data = str_replace($_G['cache']['huxcity_data']['bet'],$cacheArray,$_G['cache']['huxcity_data']);
			} else {
				$cacheArray_Old = $_G['cache']['huxcity_data'];
				foreach($cacheArray_Old as $key => $value){
					$cacheArray_data[$key] = $value;
				}
				$cacheArray_data['bet'] = $cacheArray;
			}
			save_syscache('huxcity_data',$cacheArray_data);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=bet&action=admin');
	}
} elseif ($action == 'adminreg') {

	if(submitcheck('adminregsubmit')){
		if ($appconfigsql['appadmin'] != '' && $appconfigsql['appadmin'] != $username) {
			showmessage('huxcity:betadminregerr','plugin.php?id=huxcity:huxcity&mod=bet');
		}
		if ($appconfig['betadminmin'] > $mycash) {
			showmessage('huxcity:appadminregerr','plugin.php?id=huxcity:huxcity&mod=bet');
		}
		$betadminregpay = intval($_GET['betadminregpay'] * $appconfig['betadminreg']);
		if ($betadminregpay > $mycash) {
			showmessage('huxcity:no_money','plugin.php?id=huxcity:huxcity&mod=bet');
		}
		$betadminregpaytime = TIMESTAMP + (intval($_GET['betadminregpay']) * 86400);
		$betadminregpaytimeb = intval($_GET['betadminregpay']) * 86400;
		updatemembercount($uid , array($paymoney => -$betadminregpay));
		if ($cityadmin != '') {
			updatemembercount($cityadminuid , array($paymoney => $betadminregpay));
		}
		if ($appconfigsql['appadmintime'] == 0) {
			C::t('#huxcity#hux_city_config')->update_appadmin_appadmintime_by_appid('bet',$username,$betadminregpaytime);
		} else {
			C::t('#huxcity#hux_city_config')->update_appadmin_appadmintime_jia_by_appid('bet',$username,$betadminregpaytimeb);
		}
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=bet');
	}
} elseif ($action == 'betbsover') {
	if(submitcheck('betbssubmit')){
		if ($userpow < $appconfig['betpaypower']) {
			showmessage('huxcity:no_pow','plugin.php?id=huxcity:huxcity');
		}
	$betvalue=intval($_GET['betvalue']);
	$select=dhtmlspecialchars(addslashes($_GET['select']));
	if($betvalue > $appconfig['bettzmax'] || $betvalue < $appconfig['bettzmin'])showmessage('huxcity:no_this','plugin.php?id=huxcity:huxcity&mod=bet');
	if ($betvalue>$mycash)showmessage('huxcity:no_money','plugin.php?id=huxcity:huxcity&mod=bet');
	$win=0;
	$m=0;
	if($appconfig['betopenzb'] == '1')
		{
		$zzwin = $appconfig['betzjglnum'] * 100;
		$bet_auto = mt_rand(1,100);
		if($bet_auto >= $zzwin) { $win=1; $m=$select; }
		if($bet_auto < $zzwin) {
			$win=-1;
			if($select=="big")
			$m="small";
			if($select=="small")
			$m="big";
			}
		}
	else
		{
		$mtemp = mt_rand(1,6);
		if($mtemp<=3) { $win=1; $m=$select; }
		else {
		$win=-1;
		if($select=="big")
		$m="small";
		if($select=="small")
		$m="big";
			}
		}
	
	if ($userpow > 0) {
		C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['betpaypower']);
	}
	if ($win==1)
	{
		updatemembercount($uid , array($paymoney => $betvalue));
		C::t('#huxcity#hux_city_user')->update_exp_jia_by_uid($uid,1);
		if ($appconfigsql['appadmin'] != '') {
			appadmin_money_change($betadminuid,$zhuangzhumoney,$betvalue,'bet',0);
		}
	}

	if ($win==-1)
	{
		updatemembercount($uid , array($paymoney => -$betvalue));
		if ($appconfigsql['appadmin'] != '') {
			appadmin_money_change($betadminuid,$zhuangzhumoney,$betvalue,'bet',1);
		}
		}
	}
} elseif ($action == 'betguessover') {
	if(submitcheck('betguesssubmit')){
		if ($userpow < $appconfig['betpaypower']) {
			showmessage('huxcity:no_pow','plugin.php?id=huxcity:huxcity');
		}
	$qqq=intval($_GET['qqq']);
	$dddimg=dhtmlspecialchars(addslashes($_GET['dddimg']));
	if($qqq<$appconfig['bettzmin']||$qqq>$appconfig['bettzmax'])
		showmessage('huxcity:no_this','plugin.php?id=huxcity:huxcity&mod=bet');
	$q=$dddimg;
	$w=$qqq;
	$p=$mycash;
	$m=0;
	if ($w>$p)
		showmessage('huxcity:no_money','plugin.php?id=huxcity:huxcity&mod=bet');
	if($appconfig['betopenzb'] == '1')
		{
			$zzwin = $appconfig['betzjglnum'] * 100;
			$guess_auto = mt_rand(1,100);
			if($guess_auto >= $zzwin) $m = $dddimg;
			if($guess_auto < $zzwin) {
				$mtemp = mt_rand(1,6);
				if ($dddimg == $mtemp) { $m = $dddimg-1; }
				else { $m = $mtemp; }
				}
		}
	else 
		{
		$m = mt_rand(1,6);
		}

	$s=$w*3;

	if ($userpow > 0) {
		C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['betpaypower']);
	}
	if ($m==$q)
	{

		updatemembercount($uid , array($paymoney => $s));
		C::t('#huxcity#hux_city_user')->update_exp_jia_by_uid($uid,1);
		if ($appconfigsql['appadmin'] != '') {
			appadmin_money_change($betadminuid,$zhuangzhumoney,$s,'bet',0);
		}
	}


	elseif ($m!=$q)
		{
		updatemembercount($uid , array($paymoney => -$w));
		if ($appconfigsql['appadmin'] != '') {
			appadmin_money_change($betadminuid,$zhuangzhumoney,$w,'bet',1);
		}
		}
	}
} elseif ($action == 'betguessszover') {
	if(submitcheck('betguessszsubmit')){
		if ($userpow < $appconfig['betpaypower']) {
			showmessage('huxcity:no_pow','plugin.php?id=huxcity:huxcity');
		}
	$dvsvalue=intval($_GET['dvsvalue']);
	if($dvsvalue < $appconfig['bettzmin'] || $dvsvalue > $appconfig['bettzmax'])showmessage('huxcity:no_this','plugin.php?id=huxcity:huxcity&mod=bet');
	if ($dvsvalue>$mycash)showmessage('huxcity:no_money','plugin.php?id=huxcity:huxcity&mod=bet');
	$win=0;
	if($appconfig['betopenzb'] == '1')
		{
		$zzwin = $appconfig['betzjglnum'] * 100;
		$dvs_auto = mt_rand(1,100);
		if($dvs_auto >= $zzwin) {
		$dvsresult1 = mt_rand(1,2);
	        $dvsresult2 = mt_rand(1,3);
	        $dvsresult3 = mt_rand(2,4);
	        $dvsresult4 = mt_rand(4,6);
	        $dvsresult5 = mt_rand(4,6);
	        $dvsresult6 = mt_rand(2,6);
		$win=1; }
		if($dvs_auto < $zzwin) {
	        $dvsresult1 = mt_rand(2,6);
	        $dvsresult2 = mt_rand(4,6);
	        $dvsresult3 = mt_rand(4,6);
	        $dvsresult4 = mt_rand(1,2);
	        $dvsresult5 = mt_rand(1,3);
	        $dvsresult6 = mt_rand(2,4);
		$win=2; }
		}
	else
		{
	        $dvsresult1 = mt_rand(1,6);
	        $dvsresult2 = mt_rand(1,6);
	        $dvsresult3 = mt_rand(1,6);
	        $dvsresult4 = mt_rand(1,6);
	        $dvsresult5 = mt_rand(1,6);
	        $dvsresult6 = mt_rand(1,6);
	        $dvstemp = $dvsresult1 + $dvsresult2 + $dvsresult3;
	        $dvstemp2 = $dvsresult4 + $dvsresult5 + $dvsresult6;
	        if ($dvstemp > $dvstemp2)
	            $win=2;
	        if ($dvstemp < $dvstemp2)
	            $win=1;
		if ($dvstemp == $dvstemp2)
		    $win=3;
        	}

	if ($userpow > 0) {
		C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['betpaypower']);
	}
	if ($win==1)
	{
		updatemembercount($uid , array($paymoney => $dvsvalue));
		C::t('#huxcity#hux_city_user')->update_exp_jia_by_uid($uid,1);
		if ($appconfigsql['appadmin'] != '') {
			appadmin_money_change($betadminuid,$zhuangzhumoney,$dvsvalue,'bet',0);
		}
	}

	elseif ($win==2)
		{
		updatemembercount($uid , array($paymoney => -$dvsvalue));
		if ($appconfigsql['appadmin'] != '') {
			appadmin_money_change($betadminuid,$zhuangzhumoney,$dvsvalue,'bet',1);
		}
		} else {

		}
	}
}

include template('huxcity:index');
?>