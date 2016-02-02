<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS `pre_hux_city_gojob`;
CREATE TABLE IF NOT EXISTS `pre_hux_city_gojob` (
  `eid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adtext` text NOT NULL,
  `adurl` text NOT NULL,
  `username` char(15) NOT NULL,
  `adhitmoney` int(10) unsigned NOT NULL DEFAULT '0',
  `admoney` int(10) unsigned NOT NULL DEFAULT '0',
  `adhitnum` int(10) unsigned NOT NULL DEFAULT '0',
  `adtype` varchar(10) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS `pre_hux_city_gojob_user`;
CREATE TABLE IF NOT EXISTS `pre_hux_city_gojob_user` (
  `eid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM ;

EOF;

runquery($sql);

C::t('hux_city_config')->insert(array('appid' => 'job', 'configs' => 'open:1||closemsg:close...||paypower:1||adpay:10||adhuxhit:1||adwidth:728||adheight:90'));

$finish = TRUE;
?>