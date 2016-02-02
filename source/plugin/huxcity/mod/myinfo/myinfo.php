<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$regwushumsg = lang('plugin/huxcity','regwushu_msg').$citysetting['wushupay'].$paymoneyname;
$xidianmsg = lang('plugin/huxcity','xidian_msg').$citysetting['xdmoney'].$paymoneyname;
$huxqq = $get_huxcity_user['qq'];

if ($huxqq == '') {
	$userimgfile1 = "<a href='plugin.php?id=huxcity:huxcity&mod=hotel&action=qq' title='".lang('plugin/huxcity','qq_msg')."' name='xxx'><img src='".$city_root."/images/novi.gif' width='120' height='145' /></a>";
} else {
	$userimgfile1 = "<a href='plugin.php?id=huxcity:huxcity&mod=hotel&action=qq' title='".lang('plugin/huxcity','qq_msg')."' name='xxx'><img src='http://qqshow-user.tencent.com/".$huxqq."/10/00/' width='99' height='160' /></a>";
}

if ($cacheup) {
	if ($citysetting['cityfid'] != '') {
		$cityfid = $citysetting['cityfid'];
		$threadlist=array();
		$query = C::t('forum_thread')->fetch_all_by_fid_typeid_displayorder($cityfid,null,0, '>=',0,5);
		foreach ($query as $threadinfo){
			$get_huxcity_user_thread = get_huxcity_user($threadinfo['authorid']);
			$threadinfo['uuid']=$get_huxcity_user_thread['uuid'];
			$threadinfo['dateline']=dgmdate($threadinfo['dateline']);
			$threadlist[]=$threadinfo;
		}
	}
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
	$cacheArray_Old = $_G['cache']['huxcity'];
	foreach($cacheArray_Old as $key => $value){
			$cacheArray_Index[$key] = $value;
	}
	$cacheArray_Index['threadlist'] = $threadlist;
	$cacheArray_Index['usertop'] = $usertopdata;
	$cacheArray_Index['usernew'] = $usernewdata;
	$cacheArray_Index['usernum'] = $usernum;
	save_syscache('huxcity',$cacheArray_Index);
} else {
	$threadlist = $_G['cache']['huxcity']['threadlist'];
	$usertopdata = $_G['cache']['huxcity']['usertop'];
	$usernewdata = $_G['cache']['huxcity']['usernew'];
	$usernum = $_G['cache']['huxcity']['usernum'];
}
if ($uidnum['cid'] != '0' && app_is_install('consortia')) {
	$conappname =  C::t('#huxcity#hux_city_app')->result_by_appid('consortia','appname');
}

$userlevelmin = C::t('#huxcity#hux_city_level')->result_userlevel_min_by_exp($uidnum['exp']);
$userlevelmax = C::t('#huxcity#hux_city_level')->result_userlevel_max_by_exp($uidnum['exp']);
$expf = floor (100 * (($uidnum['exp']-$userlevelmax) / ($userlevelmin-$userlevelmax)));
if ($userlevelmin == 0) {
	$expf = 100;
} else {
	$expf = ($expf >= 97)?99:$expf;
}
$hpf = floor (100 * ($userpow / $userpowmax));
$hpf = ($hpf >= 97)?99:$hpf;
$eid = $uidnum['houseid'];
if ($eid == '0') {
	$gpeid = intval($uidnum['eid']);
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
	include $appmod_root.'/house/house_myinfo.php';
}

include template('huxcity:index');
?>