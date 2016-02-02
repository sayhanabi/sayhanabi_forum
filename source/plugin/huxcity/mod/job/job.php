<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if ($appconfig['open'] == '0' && $adminid != '1' && $cityadmin != $username) {
	showmessage($appconfig['closemsg'],'plugin.php?id=huxcity:huxcity');
}

if ($userpow < $appconfig['paypower']) {
	showmessage('huxcity:no_pow','plugin.php?id=huxcity:huxcity&mod=hotel');
}

if ($action == 'admin') {

	if($adminid != '1' && $cityadmin != $username){
		showmessage('huxcity:not_allow');
	}
	$adhuxnum = C::t('#huxcity#hux_city_gojob')->fetch_by_adtype('adhux','adtext');	
	if(submitcheck('addsubmit')){
		$adopen = intval($_GET['open']);
		$adclosemsg = dhtmlspecialchars(addslashes($_GET['closemsg']));
		$adpaypower = intval($_GET['paypower']);
		$adpay = intval($_GET['adpay']);
		$adhuxhit = intval($_GET['adhuxhit']);
		$adwidth = intval($_GET['adwidth']);
		$adheight = intval($_GET['adheight']);
		$adadminarr = 'open:'.$adopen.'||closemsg:'.$adclosemsg.'||paypower:'.$adpaypower.'||adpay:'.$adpay.'||adhuxhit:'.$adhuxhit.'||adwidth:'.$adwidth.'||adheight:'.$adheight;
		C::t('#huxcity#hux_city_config')->update_configs_appadmin_by_appid('job',$adadminarr,'');
		if (!$adhuxnum) {
			$adhuxjs = addslashes($_GET['adhuxjs']);
			C::t('#huxcity#hux_city_gojob')->insert(array('adtext' => $adhuxjs, 'adurl' => '', 'username' => $username, 'adhitmoney' => '0', 'admoney' => '0', 'adtype' => 'adhux', 'dateline' => TIMESTAMP));
		} else {
			$adhuxjs = addslashes(stripslashes($_GET['adhuxjs']));
			C::t('#huxcity#hux_city_gojob')->update_adtext_dateline_by_adtype('adhux',$adhuxjs,TIMESTAMP);
		}
		$appconfigsql = C::t('#huxcity#hux_city_config')->result_by_appid('job','configs');
		$cacheArray = $appconfigsql;
		if ($_G['cache']['huxcity_data']['job']) {
			$cacheArray_data = str_replace($_G['cache']['huxcity_data']['job'],$cacheArray,$_G['cache']['huxcity_data']);
		} else {
			$cacheArray_Old = $_G['cache']['huxcity_data'];
			foreach($cacheArray_Old as $key => $value){
				$cacheArray_data[$key] = $value;
			}
			$cacheArray_data['job'] = $cacheArray;
		}
		save_syscache('huxcity_data',$cacheArray_data);
		showmessage('huxcity:op_sus',dreferer());
	}
} elseif ($action == 'adadd') {

	if(submitcheck('addsubmit')){
		$adtext = cutstr(dhtmlspecialchars(addslashes($_GET['adtext'])),50,'');
		$adurl = dhtmlspecialchars(addslashes($_GET['adurl']));
		$adhitmoney = intval($_GET['adhitmoney']);
		$admoney = intval($_GET['admoney']);
		$adallmoney = intval($admoney + $appconfig['adpay']);
		if ($mycash < $adallmoney) {
			showmessage('huxcity:no_money');
		}
		if ($adtext == '') {
			showmessage($joblang['adtext_empty']);
		}
		if ($adhitmoney < 1) {
			showmessage($joblang['adhitmoney_msg']);
		}
		if ($admoney < 1 || $admoney > 10000 || $admoney < $adhitmoney) {
			showmessage($joblang['admoney_msg']);
		}
		updatemembercount($uid , array($paymoney => -$adallmoney));
		if ($userpow > 0) {
			C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['paypower']);
		}
		C::t('#huxcity#hux_city_gojob')->insert(array('adtext' => $adtext, 'adurl' => $adurl, 'username' => $username, 'adhitmoney' => $adhitmoney, 'admoney' => $admoney, 'adtype' => 'ad', 'dateline' => TIMESTAMP));
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=job');
	}
} elseif ($action == 'adedit') {
	$eid = intval($_GET['eid']);
	$ed = C::t('#huxcity#hux_city_gojob')->fetch_by_eid($eid);
	if(submitcheck('addsubmit')){
		$adtext = cutstr(dhtmlspecialchars(addslashes($_GET['adtext'])),50,'');
		$adurl = dhtmlspecialchars(addslashes($_GET['adurl']));
		$adhitmoney = intval($_GET['adhitmoney']);
		if ($username != $ed['username']) {
			showmessage('huxcity:not_allow');
		}
		if ($adtext == '') {
			showmessage($joblang['adtext_empty']);
		}
		if ($adhitmoney < 1) {
			showmessage($joblang['adhitmoney_msg']);
		}
		if ($ed['admoney'] < $adhitmoney) {
			showmessage($joblang['admoney_msg']);
		}
		if ($userpow > 0) {
			C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['paypower']);
		}
		C::t('#huxcity#hux_city_gojob')->update_adtext_adurl_adhitmoney_by_eid($eid,$adtext,$adurl,$adhitmoney);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=job');
	}
} elseif ($action == 'addel') {
	if($adminid != '1' && $cityadmin != $username){
		showmessage('huxcity:not_allow');
	}
	if (addslashes($_GET['formhash']) == formhash()) {
		$eid = intval($_GET['eid']);
		C::t('#huxcity#hux_city_gojob')->delete_by_eid($eid);
		showmessage('huxcity:op_sus',dreferer());
	}
} elseif ($action == 'showad') {
	$eid = intval($_GET['eid']);
	$ed = C::t('#huxcity#hux_city_gojob')->fetch_by_eid($eid,'adurl,adhitmoney');
	C::t('#huxcity#hux_city_gojob')->update_adhitnum_jia_by_eid($eid,1);
	$myhit = C::t('#huxcity#hux_city_gojob_user')->count_by_typeid_username($eid,$username);
	if ($myhit == 0) {
		C::t('#huxcity#hux_city_gojob')->update_admoney_jian_by_eid($eid,$ed['adhitmoney']);
		updatemembercount($uid , array($paymoney => $ed['adhitmoney']));
		if ($userpow > 0) {
			C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['paypower']);
		}
		C::t('hux_city_gojob_user')->insert(array('username' => $username, 'typeid' => $eid, 'dateline' => TIMESTAMP));
	}
	dheader("location:".str_replace('&amp;','&',$ed['adurl']));
} elseif ($action == 'adclick') {
	$eid = intval($_GET['eid']);
	C::t('#huxcity#hux_city_gojob')->update_adhitnum_jia_by_eid($eid,1);
	$myhit = C::t('#huxcity#hux_city_gojob_user')->count_by_typeid_username($eid,$username);
	if ($myhit == 0) {
		updatemembercount($uid , array($paymoney => $appconfig['adhuxhit']));
		if ($userpow > 0) {
			C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$appconfig['paypower']);
		}
		C::t('hux_city_gojob_user')->insert(array('username' => $username, 'typeid' => $eid, 'dateline' => TIMESTAMP));
	}
} elseif ($action == 'adhuxshow') {
	$adhuxshow = C::t('#huxcity#hux_city_gojob')->result_by_adtype('adhux','adtext');
	$adhuxshow = stripslashes($adhuxshow);
} else {
	C::t('#huxcity#hux_city_gojob')->delete_by_admoney_x_adtype(0,'adhux');
	$chaotime = TIMESTAMP - 86400;
	C::t('#huxcity#hux_city_gojob_user')->delete_by_dateline($chaotime);
	$adhuxnum = C::t('#huxcity#hux_city_gojob')->fetch_by_adtype('adhux');
	$adhuxhited = C::t('#huxcity#hux_city_gojob_user')->fetch_by_typeid_username($adhuxnum['eid'],$username,'dateline');
	if (!$adhuxhited) {
		$adhuxnum['status'] = $joblang['adweihit'];
	} else {
		$adhuxnum['status'] = str_replace('{sytime}',round(($adhuxhited['dateline']+86400-TIMESTAMP)/3600,1),$joblang['adhited']);
	}
	$perpage = 20;
	$fnum = C::t('#huxcity#hux_city_gojob')->num_rows_by_search(" AND adtype='ad'");
	$page = max(1, intval($_GET['page']));
	
	$start = ($page-1)*$perpage;	
	
	$fquery = C::t('#huxcity#hux_city_gojob')->fetch_all_by_search(" AND adtype='ad'",$start,$perpage);
	foreach ($fquery as $fresult){
		$adhited = C::t('#huxcity#hux_city_gojob_user')->fetch_by_typeid_username($fresult['eid'],$username,'dateline');
		if (!$adhited) {
			$fresult['status'] = $joblang['adweihit'];
		} else {
			$fresult['status'] = str_replace('{sytime}',round(($adhited['dateline']+86400-TIMESTAMP)/3600,1),$joblang['adhited']);
		}
		$fresult['adtext'] = cutstr($fresult['adtext'],50,'');
		$flist[] = $fresult;
	}
	$multi = multi($fnum, $perpage, $page, "plugin.php?id=huxcity:huxcity&mod=job");
}
if ($action == 'adhuxshow') {
	include template('huxcity:job/adhuxshow');
} else {
	include template('huxcity:index');
}
?>