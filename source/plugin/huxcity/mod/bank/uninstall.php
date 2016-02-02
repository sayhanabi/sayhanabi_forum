<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE pre_hux_city_bank;
DROP TABLE pre_hux_city_bank_log;

EOF;

runquery($sql);

C::t('#huxcity#hux_city_config')->delete_by_appid('bank');

$finish = TRUE;

?>