<?php

/**
 * Copyright 2001-2099 1314ѧϰ��.
 * This is NOT a freeware, use is subject to license terms
 * $Id: addon.inc.php 852 2016-07-30 16:41:00Z zhuge $
 * Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
 * Ӧ����ǰ��ѯ��QQ 15326940
 * Ӧ�ö��ƿ�����QQ 643306797
 * �����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
 * δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��
 */
/*
 * This is NOT a freeware, use is subject to license terms
 * From www.1314study.com ver 2.0
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
exit('Access Denied');
}
define('STUDYADDONS_ADDON_URL', 'http://addon.1314study.com/index.php');
require_once ('pluginvar.func.php');/*3569*/
require_once DISCUZ_ROOT.'./source/discuz_version.php';
$data = 'pid='.$plugin['identifier'].'&siteurl='.rawurlencode($_G['siteurl']).'&sitever='.DISCUZ_VERSION.'/'.DISCUZ_RELEASE.'&sitecharset='.CHARSET.'&pversion='.rawurlencode($plugin[version]);splugin_thinks($plugin['identifier'],0);
$param = 'data='.rawurlencode(base64_encode($data));
$param .= '&md5hash='.substr(md5($data.TIMESTAMP), 8, 8).'&timestamp='.TIMESTAMP;
s_addon_stat($plugin,'addon');//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ
?>
