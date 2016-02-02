<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function cityadmin_money_change($moneynum,$moneytype=0) {
	global $cityadminuid,$cityadmininfo,$paymoney,$_G;
	if ($moneytype == 1) {
		updatemembercount($cityadminuid , array($paymoney => $moneynum));
	} elseif ($moneytype == 0) {
		if ($cityadmininfo[$paymoney] <= $moneynum) {
			C::t('common_member_count')->update($cityadminuid, array($paymoney => 0));
		} else {
			updatemembercount($cityadminuid , array($paymoney => -$moneynum));
		}
	}
}

function appadmin_money_change($uid,$appadminmoneynum,$moneynum,$appid='',$moneytype=0) {
	global $cityadmin,$paymoney,$cityadmininfo,$_G;
	if ($moneytype == 1) {
		updatemembercount($uid , array($paymoney => $moneynum));
	} elseif ($moneytype == 0) {
		if ($appadminmoneynum <= $moneynum) {
			$cityadminpaymoney = $moneynum - $appadminmoneynum;
			if ($cityadmin != '' && $cityadmininfo[$paymoney] > 0 && $cityadminpaymoney > 0) {
				cityadmin_money_change($cityadminpaymoney,0);
			}
			C::t('common_member_count')->update($uid, array($paymoney => 0));
		} else {
			updatemembercount($uid , array($paymoney => -$moneynum));
		}
	}
}

function pk_get_exp($uid,$pkuser='',$pow,$sh,$level,$type=0,$msg='',$ismsg=0) {
	global $citysetting;
	if ($sh > $pow) {
		if ($level <= 0) {
			$level == 1;
		}
		if ($type == 0) {
			$pkgetexp = intval($pow * $level);
		} elseif ($type == 1) {
			if ($pow * (1 / $level) < 1) {
				$pkgetexp = 1;
			} else {
				$pkgetexp = intval($pow * round(1 / $level,3));
			}
		}
	} else {
		if ($level <= 0) {
			$level == 1;
		}
		if ($type == 0) {
			$pkgetexp = intval($sh * $level);
		} elseif ($type == 1) {
			if ($sh * (1 / $level) < 1) {
				$pkgetexp = 1;
			} else {
				$pkgetexp = intval($sh * round(1 / $level,3));
			}
		}
	}
	C::t('#huxcity#hux_city_user')->update_exp_jia_by_uid($uid,$pkgetexp);
	if ($ismsg == 1 && !$citysetting['pkflash']) {
		$pk_exp_msg = $msg.$pkgetexp;
		showmessage($pk_exp_msg,"plugin.php?id=huxcity:huxcity&mod=userinfo&uuid=$pkuser", array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'info'));
	}
	return $pkgetexp;
}

function app_is_install($appid) {
	global $_G;
	loadcache('huxcity_data');
	$appisinstall = false;
	if ($_G['cache']['huxcity_data'][$appid]) {
		$appisinstall = true;
	} else {
		$appisinstall = false;
	}
	return $appisinstall;
}

function get_huxcity_user($uid=0) {
	global $_G,$vipgp,$citysetting,$huxcityatkid,$huxcityatknum,$huxcityatklv,$huxcitydefid,$huxcitydefnum,$huxcitydeflv,$huxcitypowid,$huxcitypownum,$huxcitypowlv;
	loadcache('huxcity_data');
	$iscityuser = false;
	$iscityvip = false;
	if ($uid == 0) {
		$userquery = C::t('#huxcity#hux_city_user')->fetch_by_uid($_G['uid']);
		$userqq = C::t('#huxcity#hux_common_member_profile')->result_by_uid($_G['uid'],'qq');
		$usergid = $_G['groupid'];
		$usercityname = $_G['username'];
		if (file_exists(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_atk.php')) {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_atk.php');
			$equipatkpic = "<a href='plugin.php?id=huxcity:huxcity&mod=hotel&action=equipshow&eid=".$huxcityatkid."&type=1' onclick=\"showWindow('huxcity_equipshow', this.href,'get',0);return false;\"><img src='static/image/magic/".$huxcityatkid.".gif' width='44' height='44' /></a>";
		} else {
			$huxcityatknum = 0;
			$huxcityatklv = 0;
			$equipatkpic = "<img src='source/plugin/huxcity/images/atk.gif' width='44' height='44' />";
		}
		if (file_exists(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_def.php')) {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_def.php');
			$equipdefpic = "<a href='plugin.php?id=huxcity:huxcity&mod=hotel&action=equipshow&eid=".$huxcitydefid."&type=2' onclick=\"showWindow('huxcity_equipshow', this.href,'get',0);return false;\"><img src='static/image/magic/".$huxcitydefid.".gif' width='44' height='44' /></a>";
		} else {
			$huxcitydefnum = 0;
			$huxcitydeflv = 0;
			$equipdefpic = "<img src='source/plugin/huxcity/images/def.gif' width='44' height='44' />";
		}
		if (file_exists(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_pow.php')) {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_pow.php');
			$equippowpic = "<a href='plugin.php?id=huxcity:huxcity&mod=hotel&action=equipshow&eid=".$huxcitypowid."&type=3' onclick=\"showWindow('huxcity_equipshow', this.href,'get',0);return false;\"><img src='static/image/magic/".$huxcitypowid.".gif' width='44' height='44' /></a>";
		} else {
			$huxcitypownum = 0;
			$huxcitypowlv = 0;
			$equippowpic = "<img src='source/plugin/huxcity/images/pow.gif' width='44' height='44' />";
		}
	} else {
		$userquery = C::t('#huxcity#hux_city_user')->fetch_by_uid($uid);
		$userqq = C::t('#huxcity#hux_common_member_profile')->result_by_uid($uid,'qq');
		$user_cm = C::t('#huxcity#hux_common_member')->fetch_by_uid($uid,'username,groupid');
		$usergid = $user_cm['groupid'];
		$usercityname = $user_cm['username'];
		if (file_exists(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$uid.'_atk.php')) {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$uid.'_atk.php');
			$equipatkpic = "<a href='plugin.php?id=huxcity:huxcity&mod=hotel&action=equipshow&eid=".$huxcityatkid."&type=1&equipuid=".$uid."' onclick=\"showWindow('huxcity_equipshow', this.href,'get',0);return false;\"><img src='static/image/magic/".$huxcityatkid.".gif' width='44' height='44' /></a>";
		} else {
			$huxcityatknum = 0;
			$huxcityatklv = 0;
			$equipatkpic = "<img src='source/plugin/huxcity/images/atk.gif' width='44' height='44' />";
		}
		if (file_exists(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$uid.'_def.php')) {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$uid.'_def.php');
			$equipdefpic = "<a href='plugin.php?id=huxcity:huxcity&mod=hotel&action=equipshow&eid=".$huxcitydefid."&type=2&equipuid=".$uid."' onclick=\"showWindow('huxcity_equipshow', this.href,'get',0);return false;\"><img src='static/image/magic/".$huxcitydefid.".gif' width='44' height='44' /></a>";
		} else {
			$huxcitydefnum = 0;
			$huxcitydeflv = 0;
			$equipdefpic = "<img src='source/plugin/huxcity/images/def.gif' width='44' height='44' />";
		}
		if (file_exists(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$uid.'_pow.php')) {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$uid.'_pow.php');
			$equippowpic = "<a href='plugin.php?id=huxcity:huxcity&mod=hotel&action=equipshow&eid=".$huxcitypowid."&type=3&equipuid=".$uid."' onclick=\"showWindow('huxcity_equipshow', this.href,'get',0);return false;\"><img src='static/image/magic/".$huxcitypowid.".gif' width='44' height='44' /></a>";
		} else {
			$huxcitypownum = 0;
			$huxcitypowlv = 0;
			$equippowpic = "<img src='source/plugin/huxcity/images/pow.gif' width='44' height='44' />";
		}
	}
	
	if ($userquery) {
		$iscityuser = true;
		if ($userquery['myid'] == '') {
			$huxuuid = $userquery['regtime'].$userquery['eid'];
		} else {
			$huxuuid = $userquery['myid'];
		}
		$huxregtime = dgmdate($userquery['regtime']);
		$huxbantime = $userquery['bantime'];
		$huxluckytime = $userquery['luckytime'];
		$huxgender = $userquery['gender'];
		$huxpower = $userquery['power'];
		$huxpowermax = $userquery['powermax'];
		$huxexp = $userquery['exp'];
		$huxatk = $userquery['atk'];
		$huxdef = $userquery['def'];
		$huxspd = $userquery['spd'];
		$userlevel = C::t('#huxcity#hux_city_level')->fetch_by_search("AND exp <= '".$huxexp."' AND appid='system'",'level,sxplus','ORDER BY exp DESC');
		if ($userlevel) {
			$huxlevel = $userlevel['level'];
			$userlevelplus = $userlevel['sxplus'];
		} else {
			$huxlevel = lang('plugin/huxcity','mylevel');
			$userlevelplus = 0;
		}
		if ($_G['cache']['huxcity_data']['consortia'] && $userquery['cid'] != '0') {
			include DISCUZ_ROOT.'./source/plugin/huxcity/mod/consortia/consortia_func.php';
		} else {
			$usercid = '';
			$cplus = 0;
		}
	} else {
		$iscityuser = false;
	}
	
	if (in_array($usergid, $vipgp)) {
		$iscityvip = true;
		$vipplus = $citysetting['vipplus'];
	} else {
		$iscityvip = false;
		$vipplus = 0;
	}
	
	$huxplus = $userlevelplus + $vipplus + $cplus;
	
	$infofields = array(
		'incity'	=>	$iscityuser,
		'uuid'	=>	$huxuuid,
		'qq'	=>	$userqq,
		'gid'	=>	$usergid,
		'username'	=>	$usercityname,
		'gender'	=>	$huxgender,
		'power'	=>	$huxpower,
		'powermax'	=>	$huxpowermax,
		'exp'	=>	$huxexp,
		'atk'	=>	$huxatk,
		'def'	=>	$huxdef,
		'spd'	=>	$huxspd,
		'vip'	=>	$iscityvip,
		'level'	=>	$huxlevel,
		'cname'	=>	$usercid,
		'plus'	=>	$huxplus,
		'equipatkplus'	=>	intval($huxcityatknum),
		'equipatkpic'	=>	$equipatkpic,
		'equipdefplus'	=>	intval($huxcitydefnum),
		'equipdefpic'	=>	$equipdefpic,
		'equippowplus'	=>	intval($huxcitypownum),
		'equippowpic'	=>	$equippowpic,
		'regtime'	=>	$huxregtime,
		'bantime'	=>	$huxbantime,
		'luckytime'	=>	$huxluckytime,
	);
	//return $infofields[$field];
	return $infofields;
}

function updatemoney($uid,$moneytype,$moneynum) {
	global $paymoney,$rmbmoney;
	if ($moneytype == 1) {
		updatemembercount($uid , array($paymoney => $moneynum));
	} else {
		updatemembercount($uid , array($rmbmoney => $moneynum));
	}
}

function huxcity_updatepow($paynum) {
	global $userpow,$uid;
	if ($userpow > 0) {
		C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$paynum);
	}
}

function check_status($url){
	$http_run = false;
	$ch = curl_init();
	if (!$ch) {
		$http_run = false;
	}
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_HEADER,1);   
	curl_setopt($ch,CURLOPT_NOBODY,1);   
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);   
	curl_setopt($ch,CURLOPT_TIMEOUT,30); 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch); 
	$string=$info['http_code'];
	if(substr($string,0,1)==4 || substr($string,0,1)==5){
		$http_run = false;
	}else{
		$http_run = true;
	}
	return $http_run;
}

?>