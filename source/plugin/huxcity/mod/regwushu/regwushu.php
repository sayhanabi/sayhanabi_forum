<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if ($action == 'pk') {
	if (addslashes($_GET['formhash']) == formhash()) {
	if (!$citysetting['pkopen']) {
		exit('Access Denied');
	}
	$rid2 = 9999;
	$rid = '0|0|0';
	$pkuser = dhtmlspecialchars(addslashes($_GET['uuid']));
	if ($pkuser == '') {
		dheader('location:plugin.php?id=huxcity:huxcity');
	} else {
		$userinfosqls = C::t('#huxcity#hux_city_user')->count_by_myid($pkuser);
		if ($userinfosqls > 0) {
			$pkusersql = C::t('#huxcity#hux_city_user')->fetch_by_myid($pkuser,'username,uid,exp,power,atk,def,cid');
		} else {
			$uuuid = substr($pkuser,10);
			$pkusersql = C::t('#huxcity#hux_city_user')->fetch_by_eid($uuuid,'username,uid,exp,power,atk,def,cid');
		}
	}
	$levelchasql = C::t('#huxcity#hux_city_level')->count_x_by_exp($uidnum['exp'],$pkusersql['exp'],$pkusersql['exp'],$uidnum['exp']);
	if ($levelchasql > 0) {
		$levelcha = $levelchasql;
	} else {
		$levelcha = 1;
	}
	if ($uidnum['exp'] <= $pkusersql['exp']) {
		$pkgetexptype = 0;
		$pkgetexptypeb = 1;
	} else {
		$pkgetexptype = 1;
		$pkgetexptypeb = 0;
	}
	$pkuid = $pkusersql['uid'];
	if ($citysetting['offlinepk'] == '0') {
		if ($citysetting['onlinetype'] == '1') {
			if ($citysetting['onlineopen'] == '1') {
				$pkuseronlinesql = C::t('#huxcity#hux_city_online')->count_by_username($pkusersql['username']);
			} else {
				$pkuseronlinesql = 0;
			}
		} else {
			$pkuseronlinesql = C::t('#huxcity#hux_common_session')->count_by_uid_invisible($pkuid);
		}
		if ($pkuseronlinesql <= 0) {
			if ($citysetting['pkflash']) {
				$rid2 = 1;
			} else {
				showmessage('huxcity:offline_msg',"plugin.php?id=huxcity:huxcity&mod=userinfo&uuid=$pkuser", array(), array('showdialog' => 1));
			}
		}
	}
	$get_huxcity_user_pk = get_huxcity_user($pkuid);
	if ($get_huxcity_user_pk['bantime'] != '0' && $mybantime == '0') {
		showmessage('huxcity:fuxinging','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1));
	}
	if ($get_huxcity_user_pk['bantime'] == '0' && $mybantime != '0') {
		showmessage('huxcity:baned','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1));
	}
	$usergid = $get_huxcity_user_pk['gid'];
	$pkmsgopen = $citysetting['pkmsgopen'];
	if ($pkuser != $username && $uidnum['atk'] != 0) {
		if ($pkusersql['power'] <= 0 || $pkusersql['power'] < $citysetting['pkpow']) {
			if ($citysetting['pkflash']) {
				$rid2 = 2;
			} else {
				showmessage('huxcity:pknopow_msg',"plugin.php?id=huxcity:huxcity&mod=userinfo&uuid=$pkuser", array(), array('showdialog' => 1,'alert' => 'info'));
			}
		}
		if ($userpow <= 0) {
			if ($citysetting['pkflash']) {
				$rid2 = 3;
			} else {
				showmessage('huxcity:no_pow',"plugin.php?id=huxcity:huxcity&mod=userinfo&uuid=$pkuser", array(), array('showdialog' => 1));
			}
		}
		$pkuserhuxplus = $get_huxcity_user_pk['plus'];
		$pkuserequipatkplus = $get_huxcity_user_pk['equipatkplus'];
		$pkuserequipdefplus = $get_huxcity_user_pk['equipdefplus'];
		//$pkuserequippowplus = $get_huxcity_user_pk['equippowplus'];
		//$pkuserpow = intval($pkusersql['power'] + $pkuserequippowplus + $pkusersql['power'] * ($pkuserhuxplus / 100));
		$pkuserpow = intval($pkusersql['power']);
		$pkuseratk = intval($pkusersql['atk'] + $pkuserequipatkplus + $pkusersql['atk'] * ($pkuserhuxplus / 100));
		$pkuserdef = intval($pkusersql['def'] + $pkuserequipdefplus + $pkusersql['def'] * ($pkuserhuxplus / 100));
		if ($citysetting['pkgs'] == '') {
			$usershbak = intval($useratk - $pkuserdef);
			$pkusershbak = intval($pkuseratk - $userdef);
		} else {
			$usershbak = str_replace('{atk}',$useratk,$citysetting['pkgs']);
			$usershbak = str_replace('{def}',$pkuserdef,$usershbak);
			$usershbak = eval("return $usershbak;");
			$pkusershbak = str_replace('{atk}',$pkuseratk,$citysetting['pkgs']);
			$pkusershbak = str_replace('{def}',$userdef,$pkusershbak);
			$pkusershbak = eval("return $pkusershbak;");
		}
		if ($citysetting['pkxz'] == '') {
			$usershxz = intval($useratk * 0.05);
			$pkusershxz = intval($pkuseratk * 0.05);
		} else {
			$usershxz = str_replace('{atk}',$useratk,$citysetting['pkgs']);
			$usershxz = str_replace('{def}',$pkuserdef,$usershxz);
			$usershxz = eval("return $usershxz;");
			$pkusershxz = str_replace('{atk}',$pkuseratk,$citysetting['pkgs']);
			$pkusershxz = str_replace('{def}',$userdef,$pkusershxz);
			$pkusershxz = eval("return $pkusershxz;");
		}
		if ($usershxz < 1) {
			$usershxz = 1;
		}
		if ($pkusershxz < 1) {
			$pkusershxz = 1;
		}
		if ($usershbak <= 0) {
			$usersh = $usershxz;
		} else {
			if ($usershbak <= $usershxz) {
				$usersh = $usershxz;
			} else {
				$usersh = $usershbak;
			}
		}
		if ($pkusershbak <= 0) {
			$pkusersh = $pkusershxz;
		} else {
			if ($pkusershbak <= $pkusershxz) {
				$pkusersh = $pkusershxz;
			} else {
				$pkusersh = $pkusershbak;
			}
		}
		$pkshibailv = $citysetting['pkshibailv'] * 100;
		$usershibai = mt_rand(1,100);
		$pkusershibai = mt_rand(1,100);
		$sqname = $citysetting['pluginname'];
		$pkto_expmsg = lang('plugin/huxcity','pkto_expmsg');
		$pkoversb_msga = lang('plugin/huxcity','pkoversb_msga').$pkusersh;
		$pkoversb_msgb = lang('plugin/huxcity','pkoversb_msgb').$usersh.$pkto_expmsg;
		$pkover_msg = lang('plugin/huxcity','pkover_msga').$usersh.lang('plugin/huxcity','pkover_msgb').$pkusersh.$pkto_expmsg;
		$pkover_msgc = lang('plugin/huxcity','pkover_msgc');
		$pkto_msga = lang('plugin/huxcity','pkto_msga');
		$pkto_msgb = lang('plugin/huxcity','pkto_msgb');
		$pkto_msgc = lang('plugin/huxcity','pkto_msgc');
		if ($rid2 == 9999) {
		if ($usershibai <= $pkshibailv && $pkusershibai > $pkshibailv) {
			if ($uidnum['power'] < $pkusersh) {
				C::t('#huxcity#hux_city_user')->update_power_by_uid($uid,0);
			} else {
				C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$pkusersh);
			}
			$jobregmsg = '<a href=plugin.php?id=huxcity:huxcity>['.$sqname.']</a>:'.$username.$pkto_msgb;
			if ($pkmsgopen == '1') {
				notification_add($pkuid,'system',$jobregmsg,0,1);
			}
			if ($citysetting['pkuserexp']){
				pk_get_exp($pkuid,'',$uidnum['power'],$pkusersh,$levelcha,$pkgetexptypeb,'',0);
			}
			if ($citysetting['pkflash']) {
				$rid = $pkusersh.'|0|0';
			} else {
				showmessage($pkoversb_msga,"plugin.php?id=huxcity:huxcity&mod=userinfo&uuid=$pkuser", array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'info'));
			}
		} elseif ($usershibai > $pkshibailv && $pkusershibai <= $pkshibailv) {
			if ($pkusersql['power'] < $usersh) {
				C::t('#huxcity#hux_city_user')->update_power_by_uid($pkuid,0);
			} else {
				C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($pkuid,$usersh);
			}
			$jobregmsg = '<a href=plugin.php?id=huxcity:huxcity>['.$sqname.']</a>:'.$username.$pkto_msga.$usersh;
			if ($pkmsgopen == '1') {
				notification_add($pkuid,'system',$jobregmsg,0,1);
			}
			$pkgetexpnum = pk_get_exp($uid,$pkuser,$pkusersql['power'],$usersh,$levelcha,$pkgetexptype,$pkoversb_msgb,1);
			if ($citysetting['pkflash']) {
				$rid = '0|'.$usersh.'|'.$pkgetexpnum;
			}
		} elseif ($usershibai > $pkshibailv && $pkusershibai > $pkshibailv) {
			if ($pkusersql['power'] < $usersh) {
				C::t('#huxcity#hux_city_user')->update_power_by_uid($pkuid,0);
			} else {
				C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($pkuid,$usersh);
			}
			if ($uidnum['power'] < $pkusersh) {
				C::t('#huxcity#hux_city_user')->update_power_by_uid($uid,0);
			} else {
				C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$pkusersh);
			}
			$jobregmsg = '<a href=plugin.php?id=huxcity:huxcity>['.$sqname.']</a>:'.$username.$pkto_msga.$usersh;
			if ($pkmsgopen == '1') {
				notification_add($pkuid,'system',$jobregmsg,0,1);
			}
			if ($citysetting['pkuserexp']){
				pk_get_exp($pkuid,'',$uidnum['power'],$pkusersh,$levelcha,$pkgetexptypeb,'',0);
			}
			$pkgetexpnum = pk_get_exp($uid,$pkuser,$pkusersql['power'],$usersh,$levelcha,$pkgetexptype,$pkover_msg,1);
			if ($citysetting['pkflash']) {
				$rid = $pkusersh.'|'.$usersh.'|'.$pkgetexpnum;
			}
		} else {
			$jobregmsg = '<a href=plugin.php?id=huxcity:huxcity>['.$sqname.']</a>:'.$username.$pkto_msgc;
			if ($pkmsgopen == '1') {
				notification_add($pkuid,'system',$jobregmsg,0,1);
			}
			if ($citysetting['pkflash']) {
				$rid = '0|0|0';
			} else {
				showmessage($pkover_msgc,"plugin.php?id=huxcity:huxcity&mod=userinfo&uuid=$pkuser", array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'info'));
			}
		}
		}
	}
	if ($citysetting['pkflash']) {
		echo 'r='.$rid.'&r2='.$rid2;
	}
	}
} elseif ($action == 'pkstart') {
	$pkuser = dhtmlspecialchars(addslashes($_GET['uuid']));
	if ($pkuser == '') {
		dheader('location:plugin.php?id=huxcity:huxcity');
	} else {
		$userinfosqls = C::t('#huxcity#hux_city_user')->count_by_myid($pkuser);
		if ($userinfosqls > 0) {
			$pkusersql = C::t('#huxcity#hux_city_user')->fetch_by_myid($pkuser,'username,uid,exp,power,atk,def,cid');
		} else {
			$uuuid = substr($pkuser,10);
			$pkusersql = C::t('#huxcity#hux_city_user')->fetch_by_eid($uuuid,'username,uid,exp,power,atk,def,cid');
		}
	}
	$pkuid = $pkusersql['uid'];
	$get_huxcity_user_pk = get_huxcity_user($pkuid);
	if ($get_huxcity_user_pk['bantime'] != '0' && $mybantime == '0') {
		showmessage('huxcity:fuxinging','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1));
	}
	if ($get_huxcity_user_pk['bantime'] == '0' && $mybantime != '0') {
		showmessage('huxcity:baned','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1));
	}
	$pkuserhuxplus = $get_huxcity_user_pk['plus'];
	$pkuserequipatkplus = $get_huxcity_user_pk['equipatkplus'];
	$pkuserequipdefplus = $get_huxcity_user_pk['equipdefplus'];
	$pkuserpow = intval($pkusersql['power']);
	$pkuseratk = intval($pkusersql['atk'] + $pkuserequipatkplus + $pkusersql['atk'] * ($pkuserhuxplus / 100));
	$pkuserdef = intval($pkusersql['def'] + $pkuserequipdefplus + $pkusersql['def'] * ($pkuserhuxplus / 100));
	//if (CHARSET != 'utf-8') {
			//$username = diconv($username,CHARSET,'utf-8');
			//$get_huxcity_user_pk['username'] = diconv($get_huxcity_user_pk['username'],CHARSET,'utf-8');
	//}
	include template('huxcity:pk');
} elseif ($action == 'pkcheck') {
	$rid = 9999;
	$pkuser = dhtmlspecialchars(addslashes($_GET['uuid']));
	if ($pkuser == '') {
		dheader('location:plugin.php?id=huxcity:huxcity');
	} else {
		$userinfosqls = C::t('#huxcity#hux_city_user')->count_by_myid($pkuser);
		if ($userinfosqls > 0) {
			$pkusersql = C::t('#huxcity#hux_city_user')->fetch_by_myid($pkuser,'username,uid,exp,power,atk,def,cid');
		} else {
			$uuuid = substr($pkuser,10);
			$pkusersql = C::t('#huxcity#hux_city_user')->fetch_by_eid($uuuid,'username,uid,exp,power,atk,def,cid');
		}
	}
	$pkuid = $pkusersql['uid'];
	if ($citysetting['offlinepk'] == '0') {
		if ($citysetting['onlinetype'] == '1') {
			if ($citysetting['onlineopen'] == '1') {
				$pkuseronlinesql = C::t('#huxcity#hux_city_online')->count_by_username($pkusersql['username']);
			} else {
				$pkuseronlinesql = 0;
			}
		} else {
			$pkuseronlinesql = C::t('#huxcity#hux_common_session')->count_by_uid_invisible($pkuid);
		}
		if ($pkuseronlinesql <= 0) {
			$rid = 1;
		}
	}
	if ($pkusersql['power'] <= 0 || $pkusersql['power'] < $citysetting['pkpow']) {
		$rid = 2;
	}
	if ($userpow <= 0) {
		$rid = 3;
	}
	echo 'r='.$rid;
} elseif ($action == 'dajie') {
	if (addslashes($_GET['formhash']) == formhash()) {
	$pkuser = dhtmlspecialchars(addslashes($_GET['uuid']));
	if ($pkuser == '') {
		dheader('location:plugin.php?id=huxcity:huxcity');
	} else {
		$userinfosqls = C::t('#huxcity#hux_city_user')->count_by_myid($pkuser);
		if ($userinfosqls > 0) {
			$pkusersql = C::t('#huxcity#hux_city_user')->fetch_by_myid($pkuser,'uid,power');
		} else {
			$uuuid = substr($pkuser,10);
			$pkusersql = C::t('#huxcity#hux_city_user')->fetch_by_eid($uuuid,'uid,power');
		}
	}
	$pkuid = $pkusersql['uid'];
	$get_huxcity_user_pk = get_huxcity_user($pkuid);
	if ($get_huxcity_user_pk['bantime'] != '0' && $mybantime == '0') {
		showmessage('huxcity:fuxinging','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1));
	}
	if ($get_huxcity_user_pk['bantime'] == '0' && $mybantime != '0') {
		showmessage('huxcity:baned','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1));
	}
	$pkusermoney = C::t('#huxcity#hux_common_member_count')->result_by_uid($pkuid,$paymoney);
	if ($pkuser != $username && $uidnum['power'] > 0 && $pkusersql['power'] < $citysetting['dajiepow'] && $citysetting['dajieopen'] == '1') {
		if ($pkusermoney <= 0) {
			showmessage('huxcity:dajie_no_money',"plugin.php?id=huxcity:huxcity&mod=userinfo&uuid=$pkuser", array(), array('showdialog' => 1));
		}
		$dajiegl = $citysetting['dajiegl'] * 100;
		$dajiegl_auto = mt_rand(1,100);
		if ($dajiegl_auto > $dajiegl) {
			$dajiemin = intval($citysetting['dajiemin']);
			$dajiemax = intval($citysetting['dajiemax']);
			$dajie_bannum = mt_rand($dajiemin,$dajiemax);
			$dajie_bantime = TIMESTAMP + 3600 * $dajie_bannum;
			C::t('#huxcity#hux_city_user')->update_bantime_dajie_by_uid($uid,$dajie_bantime);
			showmessage('huxcity:baned','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'info'));
		} else {
			$dajie_moneynum = intval($pkusermoney * $citysetting['dajiebl']);
			updatemembercount($pkuid , array($paymoney => -$dajie_moneynum));
			updatemembercount($uid , array($paymoney => $dajie_moneynum));
			$dajie_sus_msg = lang('plugin/huxcity','dajie_sus_msg').$dajie_moneynum.$paymoneyname;
			$dajie_to_msg = lang('plugin/huxcity','dajie_to_msg').$dajie_moneynum.$paymoneyname;
			$sqname = $citysetting['pluginname'];
			$jobregmsg = '<a href=plugin.php?id=huxcity:huxcity>['.$sqname.']</a>:'.$username.$dajie_to_msg;
			notification_add($pkuid,'system',$jobregmsg,0,1);
			showmessage($dajie_sus_msg,"plugin.php?id=huxcity:huxcity&mod=userinfo&uuid=$pkuser", array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'right'));
		}
	}
	}
} elseif ($action == 'ctoexp') {
	if(submitcheck('addsubmit')){
		$ctoexpnum = intval($_GET['ctoexpnum']);
		if($ctoexpnum < 1 || $ctoexpnum > 10000){
			showmessage('huxcity:no_this');
		}
		if($myctoexp < $ctoexpnum){
			showmessage('huxcity:no_money');
		}
		if ($citysetting['ctoexp'] != '' && $citysetting['ctoexp'] != null) {
			updatemembercount($uid , array($expmoney => -$ctoexpnum));
			C::t('#huxcity#hux_city_user')->update_exp_jia_by_uid($uid,$ctoexpnum);
		}
		showmessage('huxcity:op_sus',dreferer());
	} else {
		include template('huxcity:index');
	}
} elseif ($action == 'xidian') {
	if (addslashes($_GET['formhash']) == formhash()) {
		if($mycash < $citysetting['xdmoney']){
			showmessage('huxcity:no_money','', array(), array('showdialog' => 1));
		}

		if ($uidnum['atk'] != 0) {
			updatemembercount($uid , array($paymoney => -$citysetting['xdmoney']));
			$atkauto = mt_rand(1,10);
			$defauto = mt_rand(1,10);
			C::t('#huxcity#hux_city_user')->update_atk_def_by_uid($uid,$atkauto,$defauto);
			showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'right'));
		}
	}
} elseif ($action == 'equipup') {
	if(submitcheck('addsubmit')){
		$type = dhtmlspecialchars(addslashes($_GET['type'])); 
		if ($type == '1') {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_atk.php');
			$equipid = dhtmlspecialchars($huxcityatkid);
			$equiplv = intval($huxcityatklv) + 1;
			$equipsx = intval($huxcityatknum) + 1;
			$huxcacheArray = "\$huxcityatkid='".$equipid."';\n\$huxcityatknum='".$equipsx."';\n\$huxcityatklv='".$equiplv."';\n\n";
			$equiptype = 'atk';
		} elseif ($type == '2') {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_def.php');
			$equipid = dhtmlspecialchars($huxcitydefid);
			$equiplv = intval($huxcitydeflv) + 1;
			$equipsx = intval($huxcitydefnum) + 1;
			$huxcacheArray = "\$huxcitydefid='".$equipid."';\n\$huxcitydefnum='".$equipsx."';\n\$huxcitydeflv='".$equiplv."';\n\n";
			$equiptype = 'def';
		} elseif ($type == '3') {
			include(DISCUZ_ROOT.'./data/cache/huxcity/equip/'.$_G['uid'].'_pow.php');
			$equipid = dhtmlspecialchars($huxcitypowid);
			$equiplv = intval($huxcitypowlv) + 1;
			$equipsx = intval($huxcitypownum) + 10;
			$huxcacheArray = "\$huxcitypowid='".$equipid."';\n\$huxcitypownum='".$equipsx."';\n\$huxcitypowlv='".$equiplv."';\n\n";
			$equiptype = 'pow';
		}
		if ($equiplv <= 10) {
			$equipupquery = C::t('common_magic')->fetch_by_identifier('huxcity_equip_up1');
			if (!$equipupquery) {showmessage(lang('plugin/huxcity','equipupout'));}
			$equipupnum = C::t('#huxcity#hux_common_member_magic')->result_by_uid_magicid($uid,$equipupquery['magicid'],'num');
			if ($equipupnum) {
				if ($equipupnum > 1) {
					C::t('#huxcity#hux_common_member_magic')->update_num_jian_by_uid_magicid($uid,$equipupquery['magicid'],1);
				} else {
					C::t('common_member_magic')->delete($uid,$equipupquery['magicid']);
				}
				$dir = DISCUZ_ROOT.'./data/cache/huxcity/equip/';
				if($fp = @fopen($dir.$_G['uid'].'_'.$equiptype.'.php', 'wb')) {
					fwrite($fp, "<?php\n//Discuz! cache file, DO NOT modify me!\n//Identify: ".md5($dir.$_G['uid'].'_'.$equiptype.'.php'.$huxcacheArray.$_G['config']['security']['authkey'])."\n\n$huxcacheArray?>");
					fclose($fp);
				} else {
					exit('Can not write to cache files, please check directory ./data/ and ./data/cache/ and ./data/cache/huxcity/ and ./data/cache/huxcity/equip/ .');
				}
			} else {
				showmessage(lang('plugin/huxcity','equipuperr').$equipupquery['name'],'home.php?mod=magic');
			}
		} elseif ($equiplv > 10 && $equiplv <= 20) {
			$equipupquery = C::t('common_magic')->fetch_by_identifier('huxcity_equip_up2');
			if (!$equipupquery) {showmessage(lang('plugin/huxcity','equipupout'));}
			$equipupnum = C::t('#huxcity#hux_common_member_magic')->result_by_uid_magicid($uid,$equipupquery['magicid'],'num');
			if ($equipupnum) {
				if ($equipupnum > 1) {
					C::t('#huxcity#hux_common_member_magic')->update_num_jian_by_uid_magicid($uid,$equipupquery['magicid'],1);
				} else {
					C::t('common_member_magic')->delete($uid,$equipupquery['magicid']);
				}
				$equipsusnum = mt_rand(1,100);
				if ($equipsusnum > 20) {
					$dir = DISCUZ_ROOT.'./data/cache/huxcity/equip/';
					if($fp = @fopen($dir.$_G['uid'].'_'.$equiptype.'.php', 'wb')) {
						fwrite($fp, "<?php\n//Discuz! cache file, DO NOT modify me!\n//Identify: ".md5($dir.$_G['uid'].'_'.$equiptype.'.php'.$huxcacheArray.$_G['config']['security']['authkey'])."\n\n$huxcacheArray?>");
						fclose($fp);
					} else {
						exit('Can not write to cache files, please check directory ./data/ and ./data/cache/ and ./data/cache/huxcity/ and ./data/cache/huxcity/equip/ .');
					}
				} else {
					showmessage(lang('plugin/huxcity','equipupshibai'));
				}
			} else {
				showmessage(lang('plugin/huxcity','equipuperr').$equipupquery['name'],'home.php?mod=magic');
			}
		} elseif ($equiplv > 20 && $equiplv <= 30) {
			$equipupquery = C::t('common_magic')->fetch_by_identifier('huxcity_equip_up3');
			if (!$equipupquery) {showmessage(lang('plugin/huxcity','equipupout'));}
			$equipupnum = C::t('#huxcity#hux_common_member_magic')->result_by_uid_magicid($uid,$equipupquery['magicid'],'num');
			if ($equipupnum) {
				if ($equipupnum > 1) {
					C::t('#huxcity#hux_common_member_magic')->update_num_jian_by_uid_magicid($uid,$equipupquery['magicid'],1);
				} else {
					C::t('common_member_magic')->delete($uid,$equipupquery['magicid']);
				}
				$equipsusnum = mt_rand(1,100);
				if ($equipsusnum > 50) {
					$dir = DISCUZ_ROOT.'./data/cache/huxcity/equip/';
					if($fp = @fopen($dir.$_G['uid'].'_'.$equiptype.'.php', 'wb')) {
						fwrite($fp, "<?php\n//Discuz! cache file, DO NOT modify me!\n//Identify: ".md5($dir.$_G['uid'].'_'.$equiptype.'.php'.$huxcacheArray.$_G['config']['security']['authkey'])."\n\n$huxcacheArray?>");
						fclose($fp);
					} else {
						exit('Can not write to cache files, please check directory ./data/ and ./data/cache/ and ./data/cache/huxcity/ and ./data/cache/huxcity/equip/ .');
					}
				} else {
					showmessage(lang('plugin/huxcity','equipupshibai'));
				}
			} else {
				showmessage(lang('plugin/huxcity','equipuperr').$equipupquery['name'],'home.php?mod=magic');
			}
		} elseif ($equiplv > 30 && $equiplv <= 50) {
			$equipupquery = C::t('common_magic')->fetch_by_identifier('huxcity_equip_up4');
			if (!$equipupquery) {showmessage(lang('plugin/huxcity','equipupout'));}
			$equipupnum = C::t('#huxcity#hux_common_member_magic')->result_by_uid_magicid($uid,$equipupquery['magicid'],'num');
			if ($equipupnum) {
				if ($equipupnum > 1) {
					C::t('#huxcity#hux_common_member_magic')->update_num_jian_by_uid_magicid($uid,$equipupquery['magicid'],1);
				} else {
					C::t('common_member_magic')->delete($uid,$equipupquery['magicid']);
				}
				$equipsusnum = mt_rand(1,100);
				if ($equipsusnum > 80) {
					$dir = DISCUZ_ROOT.'./data/cache/huxcity/equip/';
					if($fp = @fopen($dir.$_G['uid'].'_'.$equiptype.'.php', 'wb')) {
						fwrite($fp, "<?php\n//Discuz! cache file, DO NOT modify me!\n//Identify: ".md5($dir.$_G['uid'].'_'.$equiptype.'.php'.$huxcacheArray.$_G['config']['security']['authkey'])."\n\n$huxcacheArray?>");
						fclose($fp);
					} else {
						exit('Can not write to cache files, please check directory ./data/ and ./data/cache/ and ./data/cache/huxcity/ and ./data/cache/huxcity/equip/ .');
					}
				} else {
					showmessage(lang('plugin/huxcity','equipupshibai'));
				}
			} else {
				showmessage(lang('plugin/huxcity','equipuperr').$equipupquery['name'],'home.php?mod=magic');
			}
		} else {
			showmessage(lang('plugin/huxcity','equipupout'));
		}
		showmessage(lang('plugin/huxcity','equipupsus'),'plugin.php?id=huxcity:huxcity');
	}
} else {
	if (addslashes($_GET['formhash']) == formhash()) {
		if($mycash < $citysetting['wushupay']){
			showmessage('huxcity:no_money','', array(), array('showdialog' => 1));
		}

		if ($uidnum['atk'] == 0) {
			updatemembercount($uid , array($paymoney => -$citysetting['wushupay']));
			$atkauto = mt_rand(1,10);
			$defauto = mt_rand(1,10);
			C::t('#huxcity#hux_city_user')->update_atk_def_by_uid($uid,$atkauto,$defauto);
			showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity', array(), array('showdialog' => 1,'locationtime' => 0,'alert' => 'right'));
		}
	}
}
?>