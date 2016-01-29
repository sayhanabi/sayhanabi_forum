<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_plbeautify {

	var $isopen = FALSE;
	var $lazyon = FALSE;
	var $showlv = FALSE;
	var $hidesp = FALSE;
	var $module_setting = array();
	var $lazymod = array();
	var $modules = array(
			'forumdisplay' => 1,
			'viewthread' => 2,
		);

	const SHOW_RIGHT_SIDE_AVATAR = 1;
	const SHOW_LEFT_SIDE_AVATAR = 2;

	function plugin_plbeautify() {

		global $_G;

		$this->isopen = $_G['forum']['picstyle'] && !$_G['cookie']['forumdefstyle'] ? false : intval($_G['cache']['plugin']['plbeautify']['isopen']);
		$this->lazyon = $_G['cache']['plugin']['plbeautify']['lazyon'] ? TRUE : FALSE;
		$this->showlv = $_G['cache']['plugin']['plbeautify']['showlv'] ? TRUE : FALSE;
		$this->hidesp = $_G['cache']['plugin']['plbeautify']['hidesp'] ? TRUE : FALSE;
		$this->module_setting = (array)unserialize($_G['cache']['plugin']['plbeautify']['lazyloads']);
		$this->lazymod = array_intersect($this->module_setting, $this->modules);
		include_once template('plbeautify:module');
	}

	function global_header() {

		$showStyle = $showLazyLoadJs = false;
		if($this->isopen && CURMODULE == 'forumdisplay') {
			$showStyle = true;
		}
		if($this->lazyon && in_array($this->modules[CURMODULE], $this->lazymod)) {
			$showLazyLoadJs = true;
		}

		return tpl_plbeautify_global_header_output($showStyle, $showLazyLoadJs);
	}
}

class plugin_plbeautify_forum extends plugin_plbeautify {

	//note 列表页头像
	function forumdisplay_author_output() {

		$plbeautify = array();
		if($this->isopen == self::SHOW_RIGHT_SIDE_AVATAR) {
			global $_G;
			foreach($_G['forum_threadlist'] as $member) {
				if (!empty($member['author'])) {
					$theAvatar = avatar($member['authorid'], 'small');
				}
				$isAnonymous = !empty($member['author']) ? false : true;
				$plbeautify[] = tpl_plbeautify_forumdisplay_author_output($member['authorid'], $theAvatar, $isAnonymous);
			}
		}

		return $plbeautify;
	}

	//note 列表页帖子标题左侧
	function forumdisplay_thread_output() {

		$plbeautify = array();
		if($this->isopen == self::SHOW_LEFT_SIDE_AVATAR) {
			global $_G;
			foreach($_G['forum_threadlist'] as $member) {
				if (!empty($member['author'])) {
					$theAvatar = avatar($member['authorid'], 'small');
				}
				$isAnonymous = !empty($member['author']) ? false : true;
				$plbeautify[] = tpl_plbeautify_forumdisplay_author_output($member['authorid'], $theAvatar, $isAnonymous, 'left');
			}
		}

		return $plbeautify;

	}

	//note 内容页升级进度条
	function viewthread_sidetop_output() {

		global $_G, $postlist;

		$pllvl = array();
		if($this->showlv) {
			foreach($postlist as $post) {
				// 上限
				$lower = $_G['cache']['usergroups'][$post['groupid']]['creditslower'];
				// 下限
				$higher = $_G['cache']['usergroups'][$post['groupid']]['creditshigher'];
				// 百分比
				$lvlup = round(($post['credits'] - $higher) / ($lower - $higher), 4);
				// 升级需要积分
				$needcredit = $lower < $post['credits'] ? 0 : $lower-$post['credits'];
				$authortitle = $post['authortitle'] ? $post['authortitle'] : $_G['cache']['usergroups'][$post['groupid']]['grouptitle'];
				$next_group = 'next_group';
				if($lvlup < 0 || $lvlup > 1) {
					$width = $cper = 0;
					$authortitle = $authortitle.' <img src="static/image/feed/notice.gif" alt="notice" class="vm" />' .lang('plugin/plbeautify', 'need_renew');
				} else {
					$width = $lvlup * 48;
					$cper = $lvlup * 100;
				}
				if($_G['cache']['usergroups'][$post['groupid']]['type'] != 'member') {
					$width = 48;
					$cper = 100;
					$next_group = 'not_member_group';
				}
				$lvshow = tpl_plbeautify_viewthread_sidetop_output($post['pid'], $post['credits'], $cper, $authortitle, $next_group, $needcredit, $width);
				$pllvl[] = $this->hidesp && !$lower ? '' : $lvshow;
			}
		}

		return $pllvl;
	}

}
