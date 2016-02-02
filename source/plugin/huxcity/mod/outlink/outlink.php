<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$eid = intval($_GET['appeid']);
if ($eid > 0) {
	$outlinkurl = C::t('#huxcity#hux_city_app')->result_by_eid($eid,'url');
	dheader('location:'.$outlinkurl);
} else {
	dheader('location:plugin.php?id=huxcity:huxcity');
}
?>