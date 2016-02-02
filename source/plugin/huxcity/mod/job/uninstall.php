<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE pre_hux_city_gojob;
DROP TABLE pre_hux_city_gojob_user;

EOF;

runquery($sql);

C::t('#huxcity#hux_city_config')->delete_by_appid('job');

$finish = TRUE;

?>