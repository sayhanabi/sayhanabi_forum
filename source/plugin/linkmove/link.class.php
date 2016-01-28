<?php
	if(!defined('IN_DISCUZ')) {
		exit('Access Denied');
	}

	class plugin_linkmove {
		function global_footer(){	
		global $_G;
		$set= $_G['cache']['plugin']['linkmove'];
		$c1 = $set['direction'];
		$c2 = $set['distance'];
		$c3 = $set['speed'];
		$noload = $set['noload'];
			return '<script src="./source/plugin/linkmove/jquery-1.4.2.min.js" type="text/javascript"></script><style type="text/css">a {position:relative;}</style>     
				<script type="text/javascript">
					jQuery(document).ready(function($){  
					jQuery("a:not(img,'.$noload.')").hover(function() {  
					    jQuery(this).stop().animate({"'.$c1.'": "'.$c2.'px"}, "'.$c3.'");  
					}, function() {  
					jQuery(this).stop().animate({"'.$c1.'": "0px"}, "'.$c3.'");  
					});  
					});
				</script>';
		}
	}
?>