<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$banklang = array
(
	'pluginname' => '银行',
	'copyright' => '<font color=#999999>Powered by <font color=#999999>[HUXTeam]</font>vit</font>',
	'bank' => '银行储蓄',	
	'infotitle' => '用户信息',
	'bankinfo' => '1.存、取款 (活期和定期)<br>活期为每日计算利息,可随时存取<br>定期为只有当存款时间到达存款期限后开始计算利息，在到存款期限前取款不计算利息<br>2.贷款、还款<br>贷款需要用经验值做抵押，比例为1:1，到期还不上贷款的，经验值收归银行所有，在规定期限内一次性还清贷款后，经验值归还',
	'bankinfotitle' => '银行功能简介',
	'notice' => '银行公告',
	'daylixi' => '日利息',
	'huoqi' => '活期',
	'dingqi' => '定期',
	'dkopen' => '允许贷款',
	'inmax' => '每笔最高存款',
	'outmax' => '每笔最高取款',
	'dkmin' => '最低贷款',
	'dkmax' => '最高贷款',
	'jiaoyitime' => '每笔交易时间间隔(秒)',
	'hqfeilv' => '活期利率',
	'dqfeilv' => '定期利率',
	'dkfeilv' => '贷款利率',
	'dqdate' => '定期天数',
	'dkdate' => '还款周期(天)',
	'nodata' => '暂无记录',
	'daikuan' => '银行贷款',
	'jiaoyidata' => '交易记录',
	'exp' => '经验',
	'tocun' => '存款',
	'toqu' => '取款',
	'todai' => '贷款',
	'tohuan' => '还款',
	'moneynum' => '金额',
	'savemoneymsg' => '操作金额必须是大于零的整数，且每笔金额不能大于{inmax}',
	'jiaoyitimemsg' => '两次操作时间必须大于{jiaoyitime}秒',
	'liximsg' => '结算利息{lixi}',
	'tolixi' => '利息',
	'moneytime' => '存款时间',
	'moneytimeend' => '到期时间',
	'moneytimepay' => '还款时间',
	'moneytimedai' => '贷款时间',
	'moneytimedailixi' => '应还利息',
	'daikuanmsg' => '先还清贷款才能再次贷款',
	'no_cunkuan' => '没有这么多存款，请重新操作',
	'qu_all' => '定期存款必须一次取完，你的定期存款金额(含利息)为：{dqmoneynum}',
	'clear_all' => '清空交易记录',
	'dai_del' => '贷款到期未还清，您抵押的经验值被银行没收',
	'diya_msg' => '贷款需要抵押经验值，抵押比例为1/1，您目前的经验值不足',
	'daimoneymsg' => '贷款金额范围：{dkmin} ~ {dkmax}',
	'savemoneysum' => '总存款',
	'not_huan' => '您没有贷过款，不用急着还',
	'huankuan_all' => '还款必须一次性还清',
	'huankuan_time' => '贷款24小时之后才能还款',
);

?>