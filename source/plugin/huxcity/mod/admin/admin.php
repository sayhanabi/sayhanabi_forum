<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($adminid != '1'){
	showmessage('huxcity:not_allow');
}

if ($action == 'appadd') {
	$sqlapp = C::t('#huxcity#hux_city_app')->fetch_all('appid');
	foreach ($sqlapp as $apps) {
		$appss[] = $apps['appid'];
	}
	$appss = array_unique($appss);
	$appss = array_filter($appss);
	$appdir = DISCUZ_ROOT.'./source/plugin/huxcity/mod';
	$appsdir = dir($appdir);
	$applist = "<table width='100%' border='0' cellspacing='0' cellpadding='1' align='center'>";
	$appinstalllang = lang('plugin/huxcity','app_install');
	while($appid = $appsdir->read()) {
		if(!in_array($appid, array('.', '..', 'admin', 'myinfo', 'userinfo', 'hotel', 'regwushu', 'outlink')) && is_dir($appdir.'/'.$appid) && !in_array($appid,$appss)) {
			include DISCUZ_ROOT.'./source/plugin/huxcity/mod/'.$appid.'/version.php';
			//include "$city_root/mod/$appid/lang/$huxapplang.lang.php";
			$langpathadmin = $appmod_root.'/'.$appid.'/lang/lang.'.currentlang().'.php';
			if (file_exists($langpathadmin)) {
				include $langpathadmin;
			} else {
				include "$city_root/mod/$appid/lang/$huxapplang.lang.php";
			}
			$huxapplangname = $appid.'lang';			
			$appnamebak = $$huxapplangname;
			if ($huxapplangname == 'consortialang') {
				$appname = $conlang['pluginname'];
			} else {
				if ($appnamebak['pluginname'] == '') {
					$appname = $appid;
				} else {
					if ($appid == 'consortia') {
						$appname = $conlang['pluginname'];
					} else {
						$appname = $appnamebak['pluginname'];
					}
				}
			}
			$applist .= "<tr><td width='50%'>".$appname." ".APP_VER." (".$appid."/)</td><td width='50%' align='right'><a href='plugin.php?id=huxcity:huxcity&mod=admin&action=appaddsave&appid=".$appid."&formhash=".$_G['formhash']."'>".$appinstalllang."</a></td></tr>";
		}
	}
	$applist .= "</table>";
} elseif ($action == 'appaddsave') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$appid = dhtmlspecialchars(addslashes($_GET['appid']));
		include DISCUZ_ROOT.'./source/plugin/huxcity/mod/'.$appid.'/version.php';
		//require_once DISCUZ_ROOT.'./source/discuz_version.php';
		//include "$city_root/mod/$appid/lang/$huxapplang.lang.php";
		$langpathadmin = $appmod_root.'/'.$appid.'/lang/lang.'.currentlang().'.php';
		if (file_exists($langpathadmin)) {
			include $langpathadmin;
		} else {
			include "$city_root/mod/$appid/lang/$huxapplang.lang.php";
		}
		$huxapplangname = $appid.'lang';
		$appnamebak = $$huxapplangname;
		if ($huxapplangname == 'consortialang') {
				$appname = $conlang['pluginname'];
			} else {
			if ($appnamebak['pluginname'] == '') {
				$appname = $appid;
			} else {
				if ($appid == 'consortia') {
					$appname = $conlang['pluginname'];
				} else {
					$appname = $appnamebak['pluginname'];
				}
			}
		}
		$apparr = array(
			'appname' => $appname,
			'appid' => $appid,
			'appver' => APP_VER,
		);
		$appnum = C::t('#huxcity#hux_city_app')->count_by_appid($appid);
		if ($appnum == 0) {
			C::t('hux_city_app')->insert($apparr);
		}
		require_once libfile('function/plugin');
		if ($appnum == 0) {
			include DISCUZ_ROOT.'./source/plugin/huxcity/mod/'.$appid.'/install.php';
		}
		$appconfigsql = C::t('#huxcity#hux_city_config')->result_by_appid($appid,'configs');
		$cacheArray = $appconfigsql;
		if ($_G['cache']['huxcity_data'][$appid]) {
			$cacheArray_data = str_replace($_G['cache']['huxcity_data'][$appid],$cacheArray,$_G['cache']['huxcity_data']);
		} else {
			$cacheArray_Old = $_G['cache']['huxcity_data'];
			foreach($cacheArray_Old as $key => $value){
				$cacheArray_data[$key] = $value;
			}
			$cacheArray_data[$appid] = $cacheArray;
		}
		save_syscache('huxcity_data',$cacheArray_data);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=applist');
	}
} elseif ($action == 'applist') {
	$sqlapp = C::t('#huxcity#hux_city_app')->fetch_all_no_by_appid('outlink','*','ORDER BY appshow DESC,ordernum ASC');
	foreach ($sqlapp as $apps) {
		$appss[] = $apps;
	}
} elseif ($action == 'outlink') {
	$sqlappoutlink = C::t('#huxcity#hux_city_app')->fetch_all_by_appid('outlink','*','ORDER BY appshow DESC,ordernum ASC');
	foreach ($sqlappoutlink as $appsoutlink) {
		$appssoutlink[] = $appsoutlink;
	}
} elseif ($action == 'outlinkopen') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$eid = intval($_GET['eid']);
		C::t('hux_city_app')->update($eid,array('appshow'=>1));
		$cacheArray_Index = array();
		$sqlmenu = C::t('#huxcity#hux_city_app')->fetch_all_by_appshow(1,'*','ORDER BY ordernum ASC');
		foreach ($sqlmenu as $menusss) {
			$menussss[] = $menusss;
		}
		$cacheArray_Old = $_G['cache']['huxcity'];
		foreach($cacheArray_Old as $key => $value){
			$cacheArray_Index[$key] = $value;
		}
		$cacheArray_Index['menu'] = $menussss;
		save_syscache('huxcity',$cacheArray_Index);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=outlink');
	}
} elseif ($action == 'outlinkclose') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$eid = intval($_GET['eid']);
		C::t('hux_city_app')->update($eid,array('appshow'=>0));
		$cacheArray_Index = array();
		$sqlmenu = C::t('#huxcity#hux_city_app')->fetch_all_by_appshow(1,'*','ORDER BY ordernum ASC');
		foreach ($sqlmenu as $menusss) {
			$menussss[] = $menusss;
		}
		$cacheArray_Old = $_G['cache']['huxcity'];
		foreach($cacheArray_Old as $key => $value){
			$cacheArray_Index[$key] = $value;
		}
		$cacheArray_Index['menu'] = $menussss;
		save_syscache('huxcity',$cacheArray_Index);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=outlink');
	}
} elseif ($action == 'outlinkdel') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$eid = intval($_GET['eid']);
		C::t('hux_city_app')->delete($eid);
		$cacheArray_Index = array();
		$sqlmenu = C::t('#huxcity#hux_city_app')->fetch_all_by_appshow(1,'*','ORDER BY ordernum ASC');
		foreach ($sqlmenu as $menusss) {
			$menussss[] = $menusss;
		}
		$cacheArray_Old = $_G['cache']['huxcity'];
		foreach($cacheArray_Old as $key => $value){
			$cacheArray_Index[$key] = $value;
		}
		$cacheArray_Index['menu'] = $menussss;
		save_syscache('huxcity',$cacheArray_Index);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=outlink');
	}
} elseif ($action == 'appopen') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$appid = dhtmlspecialchars(addslashes($_GET['appid']));
		C::t('#huxcity#hux_city_app')->update_appshow_by_appid($appid,1);
		$cacheArray_Index = array();
		$sqlmenu = C::t('#huxcity#hux_city_app')->fetch_all_by_appshow(1,'*','ORDER BY ordernum ASC');
		foreach ($sqlmenu as $menusss) {
			$menussss[] = $menusss;
		}
		$cacheArray_Old = $_G['cache']['huxcity'];
		foreach($cacheArray_Old as $key => $value){
			$cacheArray_Index[$key] = $value;
		}
		$cacheArray_Index['menu'] = $menussss;
		save_syscache('huxcity',$cacheArray_Index);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=applist');
	}
} elseif ($action == 'appclose') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$appid = dhtmlspecialchars(addslashes($_GET['appid']));
		C::t('#huxcity#hux_city_app')->update_appshow_by_appid($appid,0);
		$cacheArray_Index = array();
		$sqlmenu = C::t('#huxcity#hux_city_app')->fetch_all_by_appshow(1,'*','ORDER BY ordernum ASC');
		foreach ($sqlmenu as $menusss) {
			$menussss[] = $menusss;
		}
		$cacheArray_Old = $_G['cache']['huxcity'];
		foreach($cacheArray_Old as $key => $value){
			$cacheArray_Index[$key] = $value;
		}
		$cacheArray_Index['menu'] = $menussss;
		save_syscache('huxcity',$cacheArray_Index);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=applist');
	}
} elseif ($action == 'appup') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$appid = dhtmlspecialchars(addslashes($_GET['appid']));
		include DISCUZ_ROOT.'./source/plugin/huxcity/mod/'.$appid.'/version.php';
		$appver = C::t('#huxcity#hux_city_app')->fetch_by_appid($appid,'appver');
		if ($appver['appver'] == APP_VER) {
			showmessage('huxcity:app_up_no','plugin.php?id=huxcity:huxcity&mod=admin&action=applist');
		}
		C::t('#huxcity#hux_city_app')->update_appver_by_appid($appid,APP_VER);
		require_once libfile('function/plugin');
		include DISCUZ_ROOT.'./source/plugin/huxcity/mod/'.$appid.'/upgrade.php';
		$appconfigsql = C::t('#huxcity#hux_city_config')->result_by_appid($appid,'configs');
		$cacheArray = $appconfigsql;
		if ($_G['cache']['huxcity_data'][$appid]) {
			$cacheArray_data = str_replace($_G['cache']['huxcity_data'][$appid],$cacheArray,$_G['cache']['huxcity_data']);
		} else {
			$cacheArray_Old = $_G['cache']['huxcity_data'];
			foreach($cacheArray_Old as $key => $value){
				$cacheArray_data[$key] = $value;
			}
			$cacheArray_data[$appid] = $cacheArray;
		}
		save_syscache('huxcity_data',$cacheArray_data);
		$appupmsg = lang('plugin/huxcity','app_up_sus').APP_VER;
		showmessage("$appupmsg","plugin.php?id=huxcity:huxcity&mod=admin&action=applist");
	}
} elseif ($action == 'appdel') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$appid = dhtmlspecialchars(addslashes($_GET['appid']));
		C::t('#huxcity#hux_city_app')->delete_by_appid($appid);
		require_once libfile('function/plugin');
		include DISCUZ_ROOT.'./source/plugin/huxcity/mod/'.$appid.'/uninstall.php';
		//unlink(DISCUZ_ROOT.'./data/cache/cache_huxcity_'.$appid.'.php');
		$cacheArray_Index = array();
		$sqlmenu = C::t('#huxcity#hux_city_app')->fetch_all_by_appshow(1,'*','ORDER BY ordernum ASC');
		foreach ($sqlmenu as $menusss) {
			$menussss[] = $menusss;
		}
		$cacheArray_Old = $_G['cache']['huxcity'];
		foreach($cacheArray_Old as $key => $value){
			$cacheArray_Index[$key] = $value;
		}
		$cacheArray_Index['menu'] = $menussss;
		$cacheArray_data = str_replace($_G['cache']['huxcity_data'][$appid],null,$_G['cache']['huxcity_data']);
		save_syscache('huxcity',$cacheArray_Index);
		save_syscache('huxcity_data',$cacheArray_data);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=applist');
	}
} elseif ($action == 'appedit') {
	$appid = dhtmlspecialchars(addslashes($_GET['appid']));
	$ed = C::t('#huxcity#hux_city_app')->fetch_by_appid($appid,'appname,ordernum,appid,colorcode');
	if(submitcheck('addsubmit')){
		$appname = dhtmlspecialchars(addslashes($_GET['appname']));
		$colorcode = dhtmlspecialchars(addslashes($_GET['colorcode']));
		$ordernum = intval($_GET['ordernum']);
		C::t('#huxcity#hux_city_app')->update_appinfo_by_appid($appid,$appname,$ordernum,$colorcode);
		$cacheArray_Index = array();
		$sqlmenu = C::t('#huxcity#hux_city_app')->fetch_all_by_appshow(1,'*','ORDER BY ordernum ASC');
		foreach ($sqlmenu as $menusss) {
			$menussss[] = $menusss;
		}
		$cacheArray_Old = $_G['cache']['huxcity'];
		foreach($cacheArray_Old as $key => $value){
			$cacheArray_Index[$key] = $value;
		}
		$cacheArray_Index['menu'] = $menussss;
		save_syscache('huxcity',$cacheArray_Index);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=applist');
	}
} elseif ($action == 'outlinkedit') {
	$eid = intval($_GET['eid']);
	$ed = C::t('#huxcity#hux_city_app')->fetch_by_eid($eid,'appname,ordernum,url,colorcode');
	if(submitcheck('addsubmit')){
		$appname = dhtmlspecialchars(addslashes($_GET['appname']));
		$colorcode = dhtmlspecialchars(addslashes($_GET['colorcode']));
		$ordernum = intval($_GET['ordernum']);
		$outlinkurl = dhtmlspecialchars(addslashes($_GET['outlinkurl']));
		C::t('hux_city_app')->update($eid,array('appname'=>$appname,'ordernum'=>$ordernum,'colorcode'=>$colorcode,'url'=>$outlinkurl));
		$cacheArray_Index = array();
		$sqlmenu = C::t('#huxcity#hux_city_app')->fetch_all_by_appshow(1,'*','ORDER BY ordernum ASC');
		foreach ($sqlmenu as $menusss) {
			$menussss[] = $menusss;
		}
		$cacheArray_Old = $_G['cache']['huxcity'];
		foreach($cacheArray_Old as $key => $value){
			$cacheArray_Index[$key] = $value;
		}
		$cacheArray_Index['menu'] = $menussss;
		save_syscache('huxcity',$cacheArray_Index);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=outlink');
	}
} elseif ($action == 'outlinkadd') {
	if(submitcheck('addsubmit')){
		$appname = dhtmlspecialchars(addslashes($_GET['appname']));
		$colorcode = dhtmlspecialchars(addslashes($_GET['colorcode']));
		$ordernum = intval($_GET['ordernum']);
		$outlinkurl = dhtmlspecialchars(addslashes($_GET['outlinkurl']));
		C::t('hux_city_app')->insert(array('appname' => $appname, 'ordernum' => $ordernum, 'colorcode' => $colorcode, 'url' => $outlinkurl, 'appid' => 'outlink', 'appver' => '0'));
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=outlink');
	}
} elseif ($action == 'luckyadd') {
	if(submitcheck('addsubmit')){
		$luckymsg = dhtmlspecialchars(addslashes($_GET['luckymsg']));
		$luckytype = intval($_GET['luckytype']);
		$luckymin = intval($_GET['luckymin']);
		$luckymax = intval($_GET['luckymax']);
		$luckygl = intval($_GET['luckygl']);
		$luckyarr = array(
			'luckymsg' => $luckymsg,
			'luckytype' => $luckytype,
			'luckymin' => $luckymin,
			'luckymax' => $luckymax,
			'luckygl' => $luckygl,
		);
		C::t('#huxcity#hux_city_lucky')->insert($luckyarr);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin');
	}
} elseif ($action == 'luckyedit') {
	$eid = intval($_GET['eid']);
	$ed = C::t('#huxcity#hux_city_lucky')->fetch_by_eid($eid);
	if(submitcheck('addsubmit')){
		$luckymsg = dhtmlspecialchars(addslashes($_GET['luckymsg']));
		$luckytype = intval($_GET['luckytype']);
		$luckymin = intval($_GET['luckymin']);
		$luckymax = intval($_GET['luckymax']);
		$luckygl = intval($_GET['luckygl']);
		C::t('hux_city_lucky')->update($eid,array('luckymsg'=>$luckymsg,'luckytype'=>$luckytype,'luckymin'=>$luckymin,'luckymax'=>$luckymax,'luckygl'=>$luckygl));
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin');
	}
} elseif ($action == 'luckydel') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$eid = intval($_GET['eid']);
		C::t('#huxcity#hux_city_lucky')->delete_by_eid($eid);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin');
	}
} elseif ($action == 'leveladd') {
	if(submitcheck('addsubmit')){
		$levelname = dhtmlspecialchars(addslashes($_GET['levelname']));
		$levelexp = intval($_GET['levelexp']);
		$sxplus = intval($_GET['sxplus']);
		$levelarr = array(
			'level' => $levelname,
			'exp' => $levelexp,
			'sxplus' => $sxplus,
			'appid' => 'system',
		);
		
		C::t('hux_city_level')->insert($levelarr);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=levelmanage');
	}
} elseif ($action == 'leveledit') {
	$eid = intval($_GET['eid']);
	$ed = C::t('#huxcity#hux_city_level')->fetch_by_eid($eid);
	if(submitcheck('addsubmit')){
		$levelname = dhtmlspecialchars(addslashes($_GET['levelname']));
		$levelexp = intval($_GET['levelexp']);
		$sxplus = intval($_GET['sxplus']);
		C::t('hux_city_level')->update($eid,array('level'=>$levelname,'exp'=>$levelexp,'sxplus'=>$sxplus));
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=levelmanage');
	}
} elseif ($action == 'leveldel') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$eid = intval($_GET['eid']);
		C::t('hux_city_level')->delete($eid);
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=levelmanage');
	}
} elseif ($action == 'levelmanage') {
	$queryd = C::t('#huxcity#hux_city_level')->fetch_all_by_appid('system','*','ORDER BY eid ASC');
	foreach ($queryd as $resultd){
		$levellist[] = $resultd;
	}
} elseif ($action == 'usermanagelist') {
	//if(submitcheck('searchsubmit')){
		$jmname = dhtmlspecialchars(addslashes($_GET['username']));
		$jmuid = dhtmlspecialchars(addslashes($_GET['uid']));
		$jmid = dhtmlspecialchars(addslashes($_GET['myid']));
		$where = '';
		$exc = '';
		if ($jmname != '') {
			$where = " AND username='".$jmname."'";
			$exc = "&username=".$jmname;
		}
		if ($jmuid != '') {
			$where = " AND uid='".$jmuid."'";
			$exc = "&uid=".$jmuid;
		}
		if ($jmid != '') {
			$jmidsql = C::t('#huxcity#hux_city_user')->count_by_myid($jmid);
			if ($jmidsql >0) {
				$where = " AND myid='".$jmid."'";
				$exc = "&myid=".$jmid;
			} else {
				$jmmyid = substr($jmid,10);
				$where = " AND eid='".$jmmyid."'";
				$exc = "&myid=".$jmid;
			}
		}
		$perpage = 20;
		$numd = C::t('#huxcity#hux_city_user')->num_rows_by_search($where);
		$page = max(1, intval($_GET['page']));	
		$start = ($page-1)*$perpage;
		$queryd = C::t('#huxcity#hux_city_user')->fetch_all_by_search($where,$start,$perpage);
		foreach ($queryd as $resultd){
			$flist[] = $resultd;
		}
		$multi = multi($numd, $perpage, $page, "plugin.php?id=huxcity:huxcity&mod=admin&action=usermanagelist$exc");
	//}
} elseif ($action == 'useredit') {
	$eid = intval($_GET['eid']);
	$ed = C::t('#huxcity#hux_city_user')->fetch_by_eid($eid);
	if(submitcheck('editsubmit')){
		$usereditgender = intval($_GET['gender']);
		$usereditexp = intval($_GET['exp']);
		$usereditpow = intval($_GET['power']);
		$usereditpowmax = intval($_GET['powermax']);
		$usereditatk = intval($_GET['atk']);
		$usereditdef = intval($_GET['def']);
		$usereditspd = intval($_GET['spd']);
		C::t('hux_city_user')->update($eid,array('gender'=>$usereditgender,'exp'=>$usereditexp,'power'=>$usereditpow,'powermax'=>$usereditpowmax,'atk'=>$usereditatk,'def'=>$usereditdef,'spd'=>$usereditspd));
		showmessage("huxcity:op_sus","plugin.php?id=huxcity:huxcity&mod=admin&action=useredit&eid=$eid");
	}
} elseif ($action == 'lock') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$eid = intval($_GET['eid']);
		$locktime = TIMESTAMP + 86400 * 1000;
		C::t('hux_city_user')->update($eid,array('bantime'=>$locktime));
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=usermanage');
	}
} elseif ($action == 'unlock') {
	if (addslashes($_GET['formhash']) == formhash()) {
		$eid = intval($_GET['eid']);
		C::t('hux_city_user')->update($eid,array('bantime'=>0));
		showmessage('huxcity:op_sus','plugin.php?id=huxcity:huxcity&mod=admin&action=usermanage');
	}
} elseif ($action == 'getapp') {

	$param = array(
		'key' => 'wwevit',
		'addonid' => 'huxcity.plugin',
	);

	ksort($param);
	$params = '';
	foreach($param as $k => $v) {
		$params .= '&'.$k.'='.rawurlencode($v);
	}

	$r = @implode('', file('http://open.discuz.net/api/getaddons?'.substr($params, 1)));
	$r = unserialize($r);
	$r_data = $r['DATA'];
	foreach($r_data as $k => $v) {
		$rr[$k] = $v;
	}
	$r_ver = $rr['revisions'];

	foreach($r_ver as $k => $v) {
		$rrr[$k] = $v;
	}
	$jxlist.="<div class='bm_c'><font color='#E18840'><strong>".lang('plugin/huxcity','modlist')." >>></strong></font></div><div class='bm_c'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	$i = 0;
	foreach($rrr as $k => $v) {
		$v['memo'] = cutstr($v['memo'],300);		
		if (CHARSET != 'gbk') {
			$v['version'] = diconv($v['version'],'GBK');
			$v['memo'] = diconv($v['memo'],'GBK');
		}
		if ($v['price'] == 0) {
			$v['price'] = "<font color='green'>".lang('plugin/huxcity','free')."</font>";
		} else {
			$v['price'] = "<font color='#E67403'>".lang('plugin/huxcity','rmbfuhao').$v['price']."</span>";
		}
		if ($v['type'] == 'component') {
			$jxlist.="<td valign='top' style='padding:2px;' height='70'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td><a href='http://addon.discuz.com/?@huxcity.plugin.".$k."' target='_blank' title='".$v['memo']."'><strong>".$v['version']."</strong></a></td></tr><tr><td>".lang('plugin/huxcity','downloads').$v['downloads']."</td></tr><tr><td>".lang('plugin/huxcity','price').$v['price']."</td></tr></table></td>";
			$i++;
			if (!($i % 4)) {
				$jxlist.="</tr><tr>";
			}
		}
	}
	$jxlist.="</tr></table></div>";

	$param2 = array(
		'key' => 'wwevit',
	);

	ksort($param2);
	$params2 = '';
	foreach($param2 as $k => $v) {
		$params2 .= '&'.$k.'='.rawurlencode($v);
	}

	$r2 = @implode('', file('http://open.discuz.net/api/getaddons?'.substr($params2, 1)));
	$r2 = unserialize($r2);
	$r_data2 = $r2['DATA'];
	foreach($r_data2 as $k => $v) {
		$rr2[$k] = $v;
	}
	$jxlist2.="<div class='bm_c' style='border-top:1px dashed #CCC;'><font color='#E18840'><strong>".lang('plugin/huxcity','itemlist')." >>></strong></font></div><div class='bm_c'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	$i2 = 0;
	foreach($rr2 as $k => $v) {
		$v['memo'] = cutstr($v['memo'],300);
		if (CHARSET != 'gbk') {
			$v['name'] = diconv($v['name'],'GBK');
			$v['memo'] = diconv($v['memo'],'GBK');
		}
		$vv = explode('.',$v['ID']);
		if ($vv[1] == 'pack' && strstr($vv[0],'huxcity_')) {
			$jxlist2.="<td width='45'height='60' valign='top'><a href='http://addon.discuz.com/?@".$v[ID]."' target='_blank'><img src='".$v[logo]."' width='40' height='40' border='0' align='absmiddle' alt='".$v[name]."' /></a></td><td valign='top' style='padding:2px;'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td><a href='http://addon.discuz.com/?@".$v[ID]."' target='_blank' title='".$v['memo']."'><strong>".$v['name']."</strong></a></td></tr><tr><td>".lang('plugin/huxcity','downloads').$v['downloads']."</td></tr></table></td>";
			$i2++;
			if (!($i2 % 3)) {
				$jxlist2.="</tr><tr>";
			}
		}
	}
	$jxlist2.="</tr></table></div>";
} else {
	$perpage = 20;
	$numd = C::t('#huxcity#hux_city_lucky')->num_rows_by_search();
	$page = max(1, intval($_GET['page']));	
	$start = ($page-1)*$perpage;
	$queryd = C::t('#huxcity#hux_city_lucky')->fetch_all_by_search($start,$perpage);
	foreach ($queryd as $resultd){
		$luckylist[] = $resultd;
	}
	$multi = multi($numd, $perpage, $page, "plugin.php?id=huxcity:huxcity&mod=admin");
}
include template('huxcity:index');
?>