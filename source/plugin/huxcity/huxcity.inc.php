<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

loadcache('huxcity');
require_once libfile('function/cache');
$city_root = "source/plugin/huxcity";
$appmod_root = $city_root.'/mod';
$index = empty($_GET['mod']) ? 'myinfo' : dhtmlspecialchars(addslashes($_GET['mod']));
$action = empty($_GET['action']) ? '' : dhtmlspecialchars(addslashes($_GET['action']));
$citysetting = $_G['cache']['plugin']['huxcity'];
$navtitle = $citysetting['pluginname'];
$atclass[$index] = "class='a'";
$actives[$action] = "class='a'";
$aetives[$appeid] = "class='a'";
$uid = $_G['uid'];
$adminid = $_G['adminid'];
$closemsg = $citysetting['closemsg'];
$paymoney = "extcredits".$citysetting['paymoney'];
$paymoneyname = $_G['setting']['extcredits'][$citysetting['paymoney']]['title'];
$paymoneyunit = $_G['setting']['extcredits'][$citysetting['paymoney']]['unit'];
$expmoney = "extcredits".$citysetting['ctoexp'];
$expmoneyname = $_G['setting']['extcredits'][$citysetting['ctoexp']]['title'];
$expmoneyunit = $_G['setting']['extcredits'][$citysetting['ctoexp']]['unit'];
$mycash = getuserprofile($paymoney);
$myctoexp = getuserprofile($expmoney);
$cityadmin = $citysetting['cityadmin'];
$cacheuptime = intval($citysetting['cachetime'] * 60);
$vipgp = unserialize($citysetting['vipgp']);
$gpout = unserialize($citysetting['gpout']);
$timeoffset = $_G['setting']['timeoffset'] * 3600;

$huxapplang =$citysetting['applang'];
if ($huxapplang == 'auto') {
	if (CHARSET == 'gbk') {
		$huxapplang = 'sc_gbk';
	} elseif (CHARSET == 'big5') {
		$huxapplang = 'tc_big5';
	} else {
		if (CHARSET == 'utf-8' && $_G['config']['output']['language'] == 'zh_cn') {
			$huxapplang = 'sc_utf8';
		} elseif (CHARSET == 'utf-8' && $_G['config']['output']['language'] == 'zh_tw') {
			$huxapplang = 'tc_utf8';
		}
	}
}

$colora =$citysetting['colora'];
$colorb =$citysetting['colorb'];
$luckygl = 0;
$act = '';
$huxcitydb_msg = lang('plugin/huxcity','huxcitydb_msg');
if ($action == 'luckyadd') {
	$act = 'luckyadd';
}elseif ($action == 'luckyedit') {
	$act = 'luckyedit';
}
if ($action == 'leveladd') {
	$levelact = 'leveladd';
}elseif ($action == 'leveledit') {
	$levelact = 'leveledit';
}

if(empty($uid)) showmessage('to_login', 'member.php?mod=logging&action=login', array(), array('showmsg' => true, 'login' => 1));

if ($citysetting['open'] == '0') {
	showmessage("$closemsg","index.php");
}

if ($uid){
	$cityadminsql = C::t('common_member')->fetch_by_username($cityadmin);
	$cityadminuid = $cityadminsql['uid'];
	$cityadmininfo = C::t('#huxcity#hux_common_member_count')->fetch_by_uid($cityadminuid);
	
	include_once(DISCUZ_ROOT.'./source/plugin/huxcity/huxcity.func.php');

	$get_huxcity_user = get_huxcity_user();
	$iscityuser = $get_huxcity_user['incity'];
	$username = $get_huxcity_user['username'];
	$myregtime = $get_huxcity_user['regtime'];
	$mybantime = $get_huxcity_user['bantime'];
	$myluckytime = $get_huxcity_user['luckytime'];
	$mygid = $get_huxcity_user['gid'];
	$cityvip = $get_huxcity_user['vip'];
	
	if (in_array($mygid,$gpout)) {
		showmessage('huxcity:gpout_msg','index.php');
	}
	if (!$iscityuser) {
		$setarr = array(
			'uid' => $uid,
			'username' => $username,
			'power' => 100,
			'regtime' => TIMESTAMP,
		);
		
		C::t('hux_city_user')->insert($setarr);
		
		showmessage('huxcity:enter_first','plugin.php?id=huxcity:huxcity');
	} else {
		$myuuid = $get_huxcity_user['uuid'];
		$mygender = $get_huxcity_user['gender'];
		$uidnum = C::t('#huxcity#hux_city_user')->fetch_by_uid($uid);	
		if ($mygender != '1' && $mygender != '2' && $index != 'hotel') {
			dheader('location:plugin.php?id=huxcity:huxcity&mod=hotel&action=changegender');
		}
		$userpow = $get_huxcity_user['power'];
		$mypowmax = $get_huxcity_user['powermax'];;
		$myexp = $get_huxcity_user['exp'];
		$myatk = $get_huxcity_user['atk'];
		$mydef = $get_huxcity_user['def'];
		$myspd = $get_huxcity_user['spd'];
		$userlevel = C::t('#huxcity#hux_city_level')->fetch_by_search("AND exp <= '".$myexp."' AND appid='system'",'level,sxplus','ORDER BY exp DESC');
		$mylevel = $get_huxcity_user['level'];
		$usercid = $get_huxcity_user['cname'];
		$huxequipatkpic = $get_huxcity_user['equipatkpic'];
		$huxequipatkplus = $get_huxcity_user['equipatkplus'];
		$huxequipdefpic = $get_huxcity_user['equipdefpic'];
		$huxequipdefplus = $get_huxcity_user['equipdefplus'];
		$huxequippowpic = $get_huxcity_user['equippowpic'];
		$huxequippowplus = $get_huxcity_user['equippowplus'];
		$huxplus = $get_huxcity_user['plus'];
		$userpowmax = intval($mypowmax + $huxequippowplus + $mypowmax * ($huxplus / 100));
		$useratk = intval($myatk + $huxequipatkplus + $myatk * ($huxplus / 100));
		$userdef = intval($mydef + $huxequipdefplus + $mydef * ($huxplus / 100));
		$userspd = intval($myspd + $myspd * ($huxplus / 100));
		if (TIMESTAMP - $mybantime >= 0 && $mybantime != 0) {
			C::t('#huxcity#hux_city_user')->update_bantime_by_uid($uid,0);
		}
		if (!in_array($index,array('myinfo','userinfo','police','admin','hotel','regwushu')) && $mybantime != 0) {
			showmessage('huxcity:baned','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1));
		}
		if ($citysetting['lucky'] && (TIMESTAMP - $myluckytime > $citysetting['luckytime'])) {
			$luckyglauto = mt_rand(1,100);
			$luckygl = intval($citysetting['luckygl'] * 100);
			C::t('#huxcity#hux_city_user')->update_luckytime_by_uid($uid,TIMESTAMP);
			if ($luckyglauto <= $luckygl) {
				$luckyuid = $uid;
				$sqlluckygl = C::t('#huxcity#hux_city_lucky')->fetch_all();
				foreach ($sqlluckygl as $rowluckygl){
					$data[] = $rowluckygl;
				}
				$luckyweight = 0;
				$tempdata = array();
				foreach ($data as $one) {
					$luckyweight += $one['luckygl'];
					for ($i = 0; $i < $one['luckygl']; $i ++) {
						$tempdata[] = $one;
					}
				}
				$use = mt_rand(0, $luckyweight-1);
				$one = $tempdata[$use];
				$lucky_msg = $one['luckymsg'];
				if ($one['luckytype'] == '0') {
					$jlmoneynum = mt_rand(1,$one['luckymax']);
					$magicid = C::t('#huxcity#hux_common_member_magic')->result_by_uid_magicid($uid,$one['luckymin'],'magicid');
					$magicname = C::t('#huxcity#hux_common_magic')->result_by_magicid($one['luckymin'],'name');
					if($magicid) {
						C::t('#huxcity#hux_common_member_magic')->update_num_jia_by_uid_magicid($uid,$one['luckymin'],$jlmoneynum);
					} else {
						C::t('common_member_magic')->insert(array('uid'=>$uid,'magicid'=>$one['luckymin'],'num'=>$jlmoneynum));
					}
				} else {
					$jlmoneynum = mt_rand($one['luckymin'],$one['luckymax']);
					if ($one['luckytype'] == '-1') {
						C::t('#huxcity#hux_city_user')->update_exp_jia_by_uid($uid,$jlmoneynum);
					} elseif ($one['luckytype'] == '1') {
						C::t('#huxcity#hux_common_member_count')->update_credits_jia_by_uid($uid,$jlmoneynum);
					}
				}
				$lucky_msg = str_replace(array('{itemname}','{paymoneyname}','{luckymoney}'),array($magicname,$_G['setting']['extcredits'][$citysetting['paymoney']]['title'],$jlmoneynum),$lucky_msg);
			}
		}
	}
}

$cachedate = C::t('#huxcity#hux_common_syscache')->result_by_cname('huxcity','dateline');
$cacheup = false;
if (!$cachedate || TIMESTAMP - $cachedate > $cacheuptime) {
	$cacheup = true;
} else {
	$cacheup = false;
}
//$cacheArray_Index = array();
//$cacheArray_data = array();
//if ($cacheup) {
//	$sqlmenu = C::t('#huxcity#hux_city_app')->fetch_all_by_appshow(1,'*','ORDER BY ordernum ASC');
//	foreach ($sqlmenu as $menus) {
//		$menuss[] = $menus;
//	}
//	$cacheArray_Index['menu'] = $menuss;
//	$cacheArray_Index['version'] = C::t('#huxcity#hux_common_plugin')->result_by_identifier('huxcity','version');
//	save_syscache('huxcity',$cacheArray_Index);
//} else {
	$menuss = $_G['cache']['huxcity']['menu'];
	$VeRsIoN = C::t('#huxcity#hux_common_plugin')->result_by_identifier('huxcity','version');
//	$VeRsIoN = $_G['cache']['huxcity']['version'];
//}
$appconfigsql = C::t('#huxcity#hux_city_config')->fetch_by_appid($index);
loadcache('huxcity_data');
if (app_is_install($index)) {
	$appconfigss = $_G['cache']['huxcity_data'][$index];
	$appconfigs = explode('||',$appconfigss);
} else {
	$appconfigs = explode('||',$appconfigsql['configs']);
}
foreach($appconfigs as $value){ 
	$appconfigss = explode(':',$value);
	$appconfig[$appconfigss[0]] = $appconfigss[1];
}

$langpath = $appmod_root.'/'.$index.'/lang/lang.'.currentlang().'.php';
if (file_exists($langpath)) {
	include $langpath;
} else {
	include "$city_root/mod/$index/lang/$huxapplang.lang.php";
}
$jobnum = C::t('#huxcity#hux_city_job')->count_by_appid_status($index,1);
$jobuserlistsql = C::t('#huxcity#hux_city_job')->fetch_all_by_appid_status($index,1,'username');
foreach ($jobuserlistsql as $jobuserlist) {
	$jobuserlists[] = $jobuserlist['username'];
}
$jobuserlists = array_unique($jobuserlists);
$jobuserlists = array_filter($jobuserlists);

$appshowname = C::t('#huxcity#hux_city_app')->result_by_appid($index,'appname');

if ($citysetting['onlineopen'] == '1') {
	include $city_root.'/huxcity.online.php';
}

include $appmod_root.'/'.$index.'/'.$index.'.php';
?>