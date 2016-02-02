<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

EOF;

runquery($sql);

C::t('hux_city_config')->insert(array('appid' => 'bet', 'configs' => 'betopen:1||betclosemsg:close...||betpaypower:1||betadminmin:1000||betadminreg:100||bettzmin:1||bettzmax:100||betopenzb:1||betzjglnum:0.6'));

$finish = TRUE;
?>