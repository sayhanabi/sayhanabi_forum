<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$dajie_ok_msg = lang('plugin/huxcity','dajie_ok_msg');
$usertopsql = C::t('#huxcity#hux_common_member_count')->fetch_all_usertop(0,10);
foreach ($usertopsql as $usertop) {
	$get_huxcity_user_usertop = get_huxcity_user($usertop['uid']);
	$usertop['myid'] = $get_huxcity_user_usertop['uuid'];
	$usertopdata[] = $usertop;
}
	$usernewsql = C::t('#huxcity#hux_city_user')->fetch_all_usernew(0,10);
foreach ($usernewsql as $usernew) {
	$get_huxcity_user_usernew = get_huxcity_user($usernew['uid']);
	$usernew['myid'] = $get_huxcity_user_usernew['uuid'];
	$usernewdata[] = $usernew;
}
$usernum = C::t('#huxcity#hux_city_user')->count_all();
$userid = dhtmlspecialchars(addslashes($_GET['user']));
$uuid = dhtmlspecialchars(addslashes($_GET['uuid']));
if ($userid == '') {
	if ($uuid == '') {
		dheader('location:plugin.php?id=huxcity:huxcity');
	} else {
		$userinfosqls = C::t('#huxcity#hux_city_user')->count_by_myid($uuid);
		if ($userinfosqls > 0) {
			$userinfosql = C::t('#huxcity#hux_city_user')->fetch_by_myid($uuid);
		} else {
			$uuuid = substr($uuid,10);
			$userinfosql = C::t('#huxcity#hux_city_user')->fetch_by_eid($uuuid);
		}
	}
} else {
	$userinfosql = C::t('#huxcity#hux_city_user')->fetch_by_username($userid);
}
$get_huxcity_user_userinfo = get_huxcity_user($userinfosql['uid']);
$huxqq = $get_huxcity_user_userinfo['qq'];
$userinfoname = $get_huxcity_user_userinfo['username'];
$userinforegtime = $get_huxcity_user_userinfo['regtime'];

if ($huxqq == '') {
	$userinfoimgfile1 = "<img src='".$city_root."/images/novi.gif' width='120' height='145' />";
} else {
	$userinfoimgfile1 = "<img src='http://qqshow-user.tencent.com/".$huxqq."/10/00/' width='99' height='160' />";
}
	
if ($citysetting['onlinetype'] == '1') {
	if ($citysetting['onlineopen'] == '1') {
		$useronlinesql = C::t('#huxcity#hux_city_online')->count_by_username($userinfosql['username']);
	} else {
		$useronlinesql = 0;
	}
} else {
	$useronlinesql = C::t('#huxcity#hux_common_session')->count_by_uid_invisible($userinfosql['uid']);
}
$dajieuser = $userinfosql['username'];
$usergid = $get_huxcity_user_userinfo['gid'];
$userinfouuid = $get_huxcity_user_userinfo['uuid'];
$userinfogender = $get_huxcity_user_userinfo['gender'];
$userinfolevel = C::t('#huxcity#hux_city_level')->fetch_by_exp_appid($userinfosql['exp'],'system','level,sxplus','ORDER BY exp DESC',0,1);

if ($userinfosql['cid'] != '0' && app_is_install('consortia')) {
	$conappname =  C::t('#huxcity#hux_city_app')->result_by_appid('consortia','appname');
}
$userinfolevelshow = $get_huxcity_user_userinfo['level'];
$userinfocid = $get_huxcity_user_userinfo['cname'];
$userinfohuxplus = $get_huxcity_user_userinfo['plus'];
$userinfoequipatkpic = $get_huxcity_user_userinfo['equipatkpic'];
$userinfoequipatkplus = $get_huxcity_user_userinfo['equipatkplus'];
$userinfoequipdefpic = $get_huxcity_user_userinfo['equipdefpic'];
$userinfoequipdefplus = $get_huxcity_user_userinfo['equipdefplus'];
$userinfoequippowpic = $get_huxcity_user_userinfo['equippowpic'];
$userinfoequippowplus = $get_huxcity_user_userinfo['equippowplus'];
$userinfopow = $get_huxcity_user_userinfo['power'];
$userinfopowmax = intval($userinfosql['powermax'] + $userinfoequippowplus + $userinfosql['powermax'] * ($userinfohuxplus / 100));
$userinfoatk = intval($userinfosql['atk'] + $userinfoequipatkplus + $userinfosql['atk'] * ($userinfohuxplus / 100));
$userinfodef = intval($userinfosql['def'] + $userinfoequipdefplus + $userinfosql['def'] * ($userinfohuxplus / 100));
$userinfospd = intval($userinfosql['spd'] + $userinfosql['spd'] * ($userinfohuxplus / 100));
$userlevelmin = C::t('#huxcity#hux_city_level')->result_userlevel_min_by_exp($userinfosql['exp']);
$userlevelmax = C::t('#huxcity#hux_city_level')->result_userlevel_max_by_exp($userinfosql['exp']);
$expf = floor (100 * (($userinfosql['exp']-$userlevelmax) / ($userlevelmin-$userlevelmax)));
if ($userlevelmin == 0) {
	$expf = 100;
} else {
	$expf = ($expf >= 97)?99:$expf;
}
$hpf = floor (100 * ($userinfosql['power'] / $userinfopowmax));
$hpf = ($hpf >= 97)?99:$hpf;
//if ($userinfosql['bantime'] != '0' && $uidnum['bantime'] == '0') {
//	showmessage('huxcity:fuxinging','plugin.php?id=huxcity:huxcity');
//}
$eid = $userinfosql['houseid'];
if ($eid == '0') {
	$gpeid = intval($userinfosql['eid']);
	$upid = C::t('#huxcity#hux_city_user')->result_id_max_by_eid($gpeid);
	$get_huxcity_user_up = get_huxcity_user(C::t('#huxcity#hux_city_user')->result_by_eid($upid,'uid'));
	$upuser = $get_huxcity_user_up['uuid'];
	$nextid = C::t('#huxcity#hux_city_user')->result_id_min_by_eid($gpeid);
	$get_huxcity_user_next = get_huxcity_user(C::t('#huxcity#hux_city_user')->result_by_eid($nextid,'uid'));
	$nextuser = $get_huxcity_user_next['uuid'];
	$housepic = "$city_root/images/house.jpg";
	$houseid = $gpeid;
	$housename = lang('plugin/huxcity','housefq');
} else {
	include $appmod_root.'/house/house_userinfo.php';
}

include template('huxcity:index');
?>