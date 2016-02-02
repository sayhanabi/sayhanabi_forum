<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$apponlinetime = TIMESTAMP - $citysetting['ontime'] * 60;
C::t('#huxcity#hux_city_online')->delete_by_search("AND ontime < '$apponlinetime'");
$onlinesql = C::t('#huxcity#hux_city_online')->count_by_username_appid($username,$index);
if ($onlinesql > 0) {
	C::t('#huxcity#hux_city_online')->update_ontime_by_username_appid($username,$index,TIMESTAMP);
} else {
	C::t('#huxcity#hux_city_online')->insert(array('username' => $username,'appid' => $index,'ontime' => TIMESTAMP));
}

$onlinelistsql = C::t('#huxcity#hux_city_online')->fetch_all_by_appid($index,'username','ORDER BY ontime DESC');
foreach ($onlinelistsql as $onlinelists) {
	$onlineuid = C::t('#huxcity#hux_city_user')->result_by_username($onlinelists['username'],'uid');
	$get_huxcity_user_online = get_huxcity_user($onlineuid);
	$onlinelists['myid'] = $get_huxcity_user_online['uuid'];
	$onlinelist[] = $onlinelists;
}
?>