<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE pre_hux_city_app;
DROP TABLE pre_hux_city_config;
DROP TABLE pre_hux_city_history;
DROP TABLE pre_hux_city_job;
DROP TABLE pre_hux_city_lucky;
DROP TABLE pre_hux_city_user;
DROP TABLE pre_hux_city_level;

EOF;

runquery($sql);

C::t('common_syscache')->delete('huxcity');
C::t('common_syscache')->delete('huxcity_data');

$finish = TRUE;

?>