<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$hotelpaynum = $citysetting['hotelpaynum'];
$hotelvipnum = intval($citysetting['hotelpaynum'] * $citysetting['hotelvipnum']);

if ($action == 'ok') {
	if (addslashes($_GET['formhash']) == formhash()) {
		if ($cityvip) {
			$hotelpayok = $hotelvipnum;
		} else {
			$hotelpayok = $hotelpaynum;
		}
	
		if ($hotelpayok > $mycash) {
			showmessage('huxcity:no_money','plugin.php?id=huxcity:huxcity&mod=hotel');
		}
	
		updatemoney($uid,1,-$hotelpayok);
		C::t('#huxcity#hux_city_user')->update_power_by_uid($uid, $userpowmax);
		$hotelgomsg = lang('plugin/huxcity','hotelgomsg').$userpowmax;
		showmessage($hotelgomsg,'plugin.php?id=huxcity:huxcity&mod=hotel');
	}
} elseif ($action == 'changegender') {
	if(submitcheck('sexregsubmit')){
		if ($mygender != '1' && $mygender != '2') {
			$usereditgender = intval($_GET['gender']);
			C::t('#huxcity#hux_city_user')->update_gender_by_uid($uid, $usereditgender);
			showmessage("huxcity:op_sus","plugin.php?id=huxcity:huxcity");
		}
	}
} elseif ($action == 'qq') {
	$huxqq = $get_huxcity_user['qq'];
	if(submitcheck('sexregsubmit')){
		$huxnewqq = addslashes($_GET['qq']);
		C::t('common_member_profile')->update($_G['uid'], array('qq' => $huxnewqq));
		showmessage("huxcity:op_sus","plugin.php?id=huxcity:huxcity");
	}
} elseif ($action == 'equipshow') {
	$eid = dhtmlspecialchars(addslashes($_GET['eid']));
	$type = dhtmlspecialchars(addslashes($_GET['type']));
	$equipname = C::t('#huxcity#hux_common_magic')->result_by_identifier($eid,'name');
	$equipuid = addslashes($_GET['equipuid']);
	if ($type == '1') {
		if ($equipuid == '') {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_atk.php');
		} else {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$equipuid.'_atk.php');
		}
		$equiplv = intval($huxcityatklv);
		$equipsx = lang('plugin/huxcity','equipatk').'<font color='.$colorb.'>+'.intval($huxcityatknum).'</font>';
	} elseif ($type == '2') {
		if ($equipuid == '') {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_def.php');
		} else {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$equipuid.'_def.php');
		}
		$equiplv = intval($huxcitydeflv);
		$equipsx = lang('plugin/huxcity','equipdef').'<font color='.$colorb.'>+'.intval($huxcitydefnum).'</font>';
	} elseif ($type == '3') {
		if ($equipuid == '') {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_pow.php');
		} else {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$equipuid.'_pow.php');
		}
		$equiplv = intval($huxcitypowlv);
		$equipsx = lang('plugin/huxcity','equippow').'<font color='.$colorb.'>+'.intval($huxcitypownum).'</font>';
	}
}

include template('huxcity:index');
?>