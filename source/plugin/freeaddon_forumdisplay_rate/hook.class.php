<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: hook.class.php 2276 2016-07-30 16:41:00Z zhuge $
 * 应用售后问题：http://www.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */
/*
 * This is NOT a freeware, use is subject to license terms
 * From www.1314study.com
 */
if(!defined('IN_DISCUZ')) {
exit('2016020222g11xc1LgxH'); 
}
class plugin_freeaddon_forumdisplay_rate {

}

class plugin_freeaddon_forumdisplay_rate_forum extends plugin_freeaddon_forumdisplay_rate {
		function forumdisplay_thread_subject_output(){
				global $_G;
				$return = array();
				$splugin_setting = $_G['cache']['plugin']['freeaddon_forumdisplay_rate'];
				$freeaddon_fids = (array)unserialize($splugin_setting['freeaddon_fids']);
				if(in_array($_G['fid'], $freeaddon_fids)) {
						$tids = '';
						$i = '1314';
						foreach($_G['forum_threadlist'] as $key => $thread){
								if($thread['rate']){
										$tid = intval($thread['tid']);
			              if(!empty($tid) && $tid) {
			                  if($i == '1314') {
			                      $tids .= $tid;
			                      $i = 'DIY';
			                  }else {
			                      $tids .= ',' . $tid;
			                  }
			              }
			          }
						}
						if($tids){
								$inpids = '';
								$pids = array();
								$query = DB::query("SELECT tid,pid FROM ".DB::table('forum_post')." WHERE tid in($tids) AND first = 1");
								while($result = DB::fetch($query)){
										$pids[$result['tid']] = $result['pid'];
								}
								$inpids = implode(',', $pids);
								if($inpids){
										$ratelogs = array();
										$query = DB::query("SELECT * FROM ".DB::table('forum_ratelog')." WHERE pid in($inpids)");
										while($result = DB::fetch($query)){
												$ratelogs[$result['pid']][$result['extcredits']] += $result['score'];
										}
								}
								foreach($_G['forum_threadlist'] as $key => $thread){
										$ratelog = $ratelogs[$pids[$thread['tid']]];
										if($ratelog){
												$return_rate = '';
												foreach($ratelog as $extid => $score){
														$return_rate .= $_G['setting']['extcredits'][$extid]['title'].' <font color="red">'.($score > 0 ? '+'.$score : $score).'</font> ';
												}
												$return[] = $return_rate;
										}else{
												$return[] = '';
										}
								}
								
						}
				}
				return $return;
		}
}


//Copyright 2001-2099 1314学习网.
//This is NOT a freeware, use is subject to license terms
//$Id: hook.class.php 2722 2016-07-30 08:41:00Z zhuge $
//应用售后问题：http://www.1314study.com/services.php?mod=issue
//应用售前咨询：QQ 15326940
//应用定制开发：QQ 643306797
//本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
//未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。