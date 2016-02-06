<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_luckypost {

	var $open = '';
	var $trigger = array();
	var $msgforward = array();
	var $groupon = false;
	var $forumon = false;
	var $probability = 0;
	var $rprobability = 0;
	var $iflucky = false;
	var $event = array();
	var $unsetkey = array();
	var $luckyColor = '';
	var $badColor = '';

	const KEY_LUCKY_EVENT = 1;
	const KEY_PUNISH_EVNET = 2;
	const THREAD_DISPLAYORDER_DRAFT = -4;
	const POST_INVISIBLE_DRAFT = -3;
	const EMPTY_PID = 0;

	function plugin_luckypost() {

		global $_G;
		$luckgroups = $luckfids = $rewards = $punishs = array();

		$this->open = $_G['cache']['plugin']['luckypost']['isopen'];
		if ($this->open) {
			//note ��̨���ý����¼�
			$rewards = explode("\n", str_replace(array("\r\n", "\r"), "\n", $_G['cache']['plugin']['luckypost']['rewardevent']));
			foreach($rewards as $reward) {
				$this->event[self::KEY_LUCKY_EVENT][] = explode('|', $reward);
			}
			//note ��̨���óͷ��¼�
			$punishs = explode("\n", str_replace(array("\r\n", "\r"), "\n", $_G['cache']['plugin']['luckypost']['punishevent']));
			foreach($punishs as $punish) {
				$this->event[self::KEY_PUNISH_EVNET][] = explode('|', $punish);
			}
			//note ��̨����������û���
			$luckgroups = (array)unserialize($_G['cache']['plugin']['luckypost']['luckgroups']);
			//note ��̨��������İ��
			$luckfids = (array)unserialize($_G['cache']['plugin']['luckypost']['luckfids']);
			$this->probability = $_G['cache']['plugin']['luckypost']['probability'] - 0;
			$this->rprobability = $_G['cache']['plugin']['luckypost']['rprobability'] - 0;
			$this->luckyColor = $_G['cache']['plugin']['luckypost']['eventstyle_1'];
			$this->badColor = $_G['cache']['plugin']['luckypost']['eventstyle_2'];

			//note ��ǰ�û����ж�
			$this->groupon = in_array('', $luckgroups) ? TRUE : (in_array($_G['member']['groupid'], $luckgroups) ? TRUE : FALSE);
			//note ��ǰ����ж�
			$this->forumon = in_array('', $luckfids) ? TRUE : (in_array($_G['fid'], $luckfids) ? TRUE : FALSE);
			//note ����������������
			$this->trigger = $_G['cache']['plugin']['luckypost']['threadonly'] ? array('post_newthread_succeed') : array('post_newthread_succeed', 'post_reply_succeed');
			include_once template('luckypost:module');
		}
	}

	function _luckypost($tid, $pid = self::EMPTY_PID, $isanonymous = FALSE) {

		//note �������������ж�
		$this->iflucky = $this->_lottery($this->probability);
		if($this->iflucky) {
			//note ����/�ͷ������ж�
			$eventKey = $this->_lottery($this->rprobability) ? self::KEY_LUCKY_EVENT : self::KEY_PUNISH_EVNET;
			$maxNum = count($this->event[$eventKey]) - 1;
			//note ������¼�id
			$eventId = $this->_randomnum(0, $maxNum);
			$threadValue = array(
				'tid' => $tid,
				'pid' => $pid,
			);
			return $this->_runthelottery($eventKey, $eventId, $threadValue, $isanonymous);
		}
	}

	function _randomnum($min = 0, $max = 100) {

		PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
		$num = mt_rand($min, $max);

		return $num;
	}

	function _lottery($probability) {

		$random = ($this->_randomnum(1, 10000) / 10000);
		if($probability >= $random) {
			return true;
		} else {
			return false;
		}
	}

	function _runthelottery($eventKey, $eventId, $ids, $isanonymous) {

		if ($this->event[$eventKey][$eventId] && $ids['tid']) {
			global $_G;

			$creditRange = $data = array();
			$creditData = '';

			list($creditId, $creditRangeString, $event) = $this->event[$eventKey][$eventId];
			$creditId = intval($creditId);

			//note ���ַ�Χ
			$creditRange = explode(',', $creditRangeString);
			//note ��������ͻ���
			$creditData = $this->_randomnum(abs($creditRange[0]), abs($creditRange[1]));
			//note �жϽ���
			$creditData = $eventKey == self::KEY_LUCKY_EVENT ? $creditData : '-' . $creditData;

			if($creditId) {
				$dataArr = array('extcredits' . $creditId => $creditData);
				//$getmsg_utf8 = diconv($getmsg, CHARSET, 'utf-8');
				//updatemembercount($_G['uid'], $dataArr, 1, '', 0, $getmsg_utf8);
				updatemembercount($_G['uid'], $dataArr);
				$data = array(
					'tid' => $ids['tid'],
					'pid' => $ids['pid'],
					'uid' => $_G['uid'],
					'anonymous' => intval($isanonymous),
					'extcredit' => $creditId,
					'credits' => $creditData,
					'dateline' => TIMESTAMP,
					'eventid' => $eventId,
				);
				C::t('#luckypost#common_plugin_luckypost')->insert($data);
				if(!C::t('#luckypost#common_plugin_luckypostlog')->fetch($_G['uid'])) {
					$log = array(
						'uid' => $_G['uid'],
						'goodtimes' => 0,
						'badtimes' => 0,
					);
					C::t('#luckypost#common_plugin_luckypostlog')->insert($log);
				}
				$field = $eventKey == self::KEY_LUCKY_EVENT ? 'goodtimes' : 'badtimes';
				C::t('#luckypost#common_plugin_luckypostlog')->increase($_G['uid'], $field);
			}
		}

		return true;
	}

	function _show_event() {

		global $_G, $postlist;

		$pids = $luckyList = $luckyEvent = array();

		foreach($postlist as $post) {
			$pids[] = $post['pid'];
			if ($post['first']) {
				$thisFirstPId = $post['pid'];
			}
		}

		//note �����ݸ���pid���
		if ($_G['page'] == '1') {
			$pids[] = self::EMPTY_PID;
		}

		foreach(C::t('#luckypost#common_plugin_luckypost')->fetch_all_by_tid_pids($_G['tid'], $pids) as $result) {
			if ($result['pid'] == self::EMPTY_PID) {
				$result['pid'] = $thisFirstPId;
			}

			//note �û�����ʾ����
			$member = array('username' => lang('plugin/luckypost', 'anonymous'));
			if (!$postlist[$result['pid']]['anonymous']) {
				$member = getuserbyuid($result['uid']);
			}
			//note �����жϽ�����ͷ�
			$eventKey = $result['credits'] > 0 ? self::KEY_LUCKY_EVENT : self::KEY_PUNISH_EVNET;

			$creditId = $creditRangeString = $event ='';
			list($creditId, $creditRangeString, $event, $cardName, $picName) = $this->event[$eventKey][$result['eventid']];

			//note ������
			$extcredits = $_G['setting']['extcredits'][$creditId]['img'] . $_G['setting']['extcredits'][$creditId]['title'];
			//note ����ֵ
			$result['credits'] = abs($result['credits']) . ' ' . $_G['setting']['extcredits'][$creditId]['unit'];
			//note �����¼�����
			$eventMsg = str_replace(array('{username}', '{credit}', '{extcredits}'), array($member['username'], $result['credits'], $extcredits), $event);
			$luckyList[$result['pid']] = array(
				'msg' => $eventMsg,
				'key' => $eventKey,
				'cardName' => $cardName,
				'picName' => $picName,
				'credit' => $result['credits'],
				'extcredits' => $extcredits,
			);
		}
		foreach($pids as $key => $pid) {
			if($pid == self::EMPTY_PID) {
				unset($pids[$key]);
				continue;
			}
			//$luckyEvent[] = $luckyList[$pid] ? lang('plugin/luckypost', 'luckyshow', array('$luckyevent' => $luckyList[$pid])) : '';
			if($luckyList[$pid]['msg']) {
				$colorStyle = $luckyList[$pid]['key'] == self::KEY_PUNISH_EVNET ? $this->badColor : $this->luckyColor;
				$luckyEvent[] = tpl_viewthread_postbottom_luckyshow($luckyList[$pid], $colorStyle);
			} else {
				$luckyEvent[] = '';
			}
		}
		return $luckyEvent;

	}
}

class plugin_luckypost_forum extends plugin_luckypost {

	function post_luckypost_message($params) {

		global $_G, $displayorder, $isanonymous, $pinvisible;

		list($message, $forwordURL, $threadValue) = $params['param'];
		//note ��������ж�
		if($this->open && $this->groupon && $this->forumon && in_array($message, $this->trigger)
			&& $displayorder != self::THREAD_DISPLAYORDER_DRAFT && $pinvisible != self::POST_INVISIBLE_DRAFT
			&& $threadValue['pid']) {
			//note ��������
			return $this->_luckypost($threadValue['tid'], $threadValue['pid'], $isanonymous);
		}

		return true;
	}

	function misc_luckypost_message($params) {

		global $_G;

		list($message) = $params['param'];
		//note ��������ж�(�Ӳݸ��з���)��ֻ����òݸ��������
		if($_GET['action'] == 'pubsave' && $this->open && $this->groupon && $this->forumon && in_array($message, $this->trigger)) {
			$isanonymous = trim($_G['forum_thread']['author']) ? FALSE : TRUE;
			//note ��������
			return $this->_luckypost($_G['forum_thread']['tid'], self::EMPTY_PID, $isanonymous);
		}

		return true;
	}

	function viewthread_postbottom_output() {

		if (!$this->open) {
			return array();
		}

		return $this->_show_event();
	}
}

class plugin_luckypost_group extends plugin_luckypost {

	function post_luckypost_message($params) {

		global $_G, $displayorder, $isanonymous, $pinvisible;

		list($message, $forwordURL, $threadValue) = $params['param'];
		//note ��������ж�
		if($this->open && $this->groupon && $this->forumon && in_array($message, $this->trigger)
			&& $displayorder != self::THREAD_DISPLAYORDER_DRAFT && $pinvisible != self::POST_INVISIBLE_DRAFT
			&& $threadValue['pid']) {
			//note ��������
			return $this->_luckypost($threadValue['tid'], $threadValue['pid'], $isanonymous);
		}

		return true;
	}

	function misc_luckypost_message($params) {

		global $_G;

		list($message) = $params['param'];
		//note ��������ж�(�Ӳݸ��з���)��ֻ����òݸ��������
		if($_GET['action'] == 'pubsave' && $this->open && $this->groupon && $this->forumon && in_array($message, $this->trigger)) {
			$isanonymous = trim($_G['forum_thread']['author']) ? FALSE : TRUE;
			//note ��������
			return $this->_luckypost($_G['forum_thread']['tid'], self::EMPTY_PID, $isanonymous);
		}

		return true;
	}

	function viewthread_postbottom_output() {

		if (!$this->open) {
			return array();
		}

		return $this->_show_event();
	}

}