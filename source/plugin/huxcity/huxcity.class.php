<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_huxcity {

	function huxcity() {
		global $_G;
		$citysetting = $_G['cache']['plugin']['huxcity'];
		$uid = $_G['uid'];
		$username = $_G['username'];
		if (($citysetting['powtobbs'] == '1' || $citysetting['bantobbs'] == '1') && $uid) {
			$uidnum = C::t('#huxcity#hux_city_user')->fetch_by_uid($uid,'power,bantime');
			if (!$uidnum) {
				$setarr = array(
					'uid' => $uid,
					'username' => $username,
					'power' => 100,
					'regtime' => TIMESTAMP,
				);
		
				C::t('hux_city_user')->insert($setarr);
			} else {
				$userpow = $uidnum['power'];
			}
		}
		if ($citysetting['powtobbs'] == '1' && $uid && $uidnum && $userpow <= 0) {
			$nopow_msg = lang('plugin/huxcity','no_pow');
			$pow_yes = lang('plugin/huxcity','pow_yes');
			$pow_no = lang('plugin/huxcity','pow_no');
			include template('huxcity:powtobbs');
			return $return;
		} elseif ($citysetting['bantobbs'] == '1' && $uid && $uidnum && $uidnum['bantime'] != 0) {
			$ban_msg = lang('plugin/huxcity','baned').dgmdate($uidnum['bantime']);
			$ban_yes = lang('plugin/huxcity','ban_yes');
			$ban_no = lang('plugin/huxcity','ban_no');
			include template('huxcity:bantobbs');
			return $returnb;
		} else {
			return '';
		}

	}

	function huxcity_viewthread() {
		global $_G;
		$citysetting = $_G['cache']['plugin']['huxcity'];
		$uid = $_G['uid'];
		if ($citysetting['powtobbs'] == '1' && $uid) {
			$uidnum = C::t('#huxcity#hux_city_user')->fetch_by_uid($uid,'power');
			if ($uidnum) {
				$userpow = $uidnum['power'];
				if ($userpow > 0) {
					C::t('#huxcity#hux_city_user')->update_power_jian_by_uid($uid,$citysetting['powtopost']);
				}
			}
		}
		return '';
	}

	function global_footer() {
		global $_G;
		return $this->huxcity();
	}

	function viewthread_bottom() {
		global $_G;
		return $this->huxcity_viewthread();
	}

}
class plugin_huxcity_forum extends plugin_huxcity {
	function viewthread_sidetop_output() {
		global $postlist,$_G;
		$citysetting = $_G['cache']['plugin']['huxcity'];
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
		$tidshowtext = explode('|',$citysetting['tidshowtext']);
		$colora = $citysetting['colora'];
		$colorb = $citysetting['colorb'];
		if(empty($_GET['tid']) || !is_array($postlist)) return array();
		$pids=array_keys($postlist);
		$authorids=array();
		foreach($postlist as $pid=>$pinfo){
			$authorids[]=$pinfo['authorid'];
		}
		$authorids = array_unique($authorids);
		$authorids = array_filter($authorids);
		$authorids = dimplode($authorids);
		if($authorids == '') return array();
	
		$applist = C::t('#huxcity#hux_city_user')->fetch_all_IN_by_uid($authorids);
		$myapp = array();
		foreach($applist as $mrc) {
			if ($citysetting['myidopen']) {
				if ($mrc['myid'] == '') {
					$myids = $mrc['regtime'].$mrc['eid'];
					$myidsc = cutstr($mrc['regtime'].$mrc['eid'],14,'');
				} else {
					$myids = $mrc['myid'];
					$myidsc = "<font color='".$colorb."'>".cutstr($mrc['myid'],14,'')."</font>";
				}
				$myid="<dl class='pil cl vm'><img src='source/plugin/huxcity/images/ico/myinfo.png' width='16' height='16' alt='".$tidshowtext[1]."' /> <a href='plugin.php?id=huxcity:huxcity&mod=userinfo&uuid=".$myids."' title='".$tidshowtext[1]."'>".$myidsc."</a></dl>";
			} else {
				$myid="";
			}
			$huxcityclass_show = $myid;
			include_once(DISCUZ_ROOT.'./source/plugin/huxcity/huxcity.func.php');
			loadcache('huxcity_data');
			$cacheArray_huxcityclass = $_G['cache']['huxcity_data'];
			foreach($cacheArray_huxcityclass as $key => $value){
				include DISCUZ_ROOT.'./source/plugin/huxcity/mod/'.$key.'/'.$key.'_hook.php';
			}
			$myapp[$mrc['uid']]['myappout'] = $huxcityclass_show;
			$myapp[] = $mrc;
		}

		$showhout = array();
		foreach($postlist as $key => $val) {
		
			if(!$myapp[$postlist[$key][authorid]][myappout]){
				$myapp[$postlist[$key][authorid]][myappout] = "";
			}
		
		$showhout[] = "<div style='border-bottom:1px dashed #CCCCCC;padding-bottom:5px;'>".$myapp[$postlist[$key][authorid]][myappout]."</div>";
		}

		return $showhout;
	}
}

?>