<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS `pre_hux_city_bank`;
CREATE TABLE IF NOT EXISTS `pre_hux_city_bank` (
  `eid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `outtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS `pre_hux_city_bank_log`;
CREATE TABLE IF NOT EXISTS `pre_hux_city_bank_log` (
  `eid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `moneymsg` varchar(100) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM ;

EOF;

runquery($sql);

C::t('hux_city_config')->insert(array('appid' => 'bank', 'configs' => 'open:1||closemsg:close...||dkopen:0||paypower:1||inmax:10000||outmax:10000||dkmin:100||dkmax:10000||jiaoyitime:2||hqfeilv:0.05||dqfeilv:0.1||dkfeilv:0.15||dqdate:7||dkdate:30||notice:welcome'));

$finish = TRUE;
?>