<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF


EOF;

runquery($sql);

C::t('#huxcity#hux_city_config')->delete_by_appid('bet');

$finish = TRUE;

?>