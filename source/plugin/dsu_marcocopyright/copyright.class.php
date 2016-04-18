<?php
/*
	[Copyright] (C) Marco129 & Discuz Student Union
	Author: Marco129 (http://cubichost.net)
	Please respect the author, DO NOT delete any copyright!
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_dsu_marcocopyright{
	function plugin_dsu_marcocopyright(){
		global $_G;
		$this->settings = $_G['cache']['plugin']['dsu_marcocopyright'];
		$this->forums = unserialize($this->settings['forums']);
		$this->pic_copyright = ($this->settings['pic_copyright'] == '{default_color}') ? 1 : (($this->settings['pic_copyright'] == '{default_black}') ? 2 : $this->settings['pic_copyright']);
		if(!$this->settings['copy_link_style']){
			$this->copy_link_style = 'source/plugin/dsu_marcocopyright/images/'.(($_G['charset'] == 'gbk') ? 'share_sc.gif' : 'share_tc.gif');
		}elseif($this->settings['copy_link_style'] == '{tc}'){
			$this->copy_link_style = 'source/plugin/dsu_marcocopyright/images/share_tc.gif';
		}elseif($this->settings['copy_link_style'] == '{sc}'){
			$this->copy_link_style = 'source/plugin/dsu_marcocopyright/images/share_sc.gif';
		}else{
			$this->copy_link_style = $this->settings['copy_link_style'];
		}
		$this->home_link = $_G['siteurl'].'?'.$_G['forum_thread']['authorid'];
		$this->post_subject = str_replace("'","\'",urldecode($_G['forum_thread']['subjectenc']));
		if(in_array('forum_viewthread', $_G['setting']['rewritestatus'])){
			$this->post_link = $_G['siteurl'].rewriteoutput('forum_viewthread', 1, '', $_G['tid'], $_G['page'], $_G['prevpage'], '');
		}else{
			$this->post_link = str_replace(array('&extra='.$_GET['extra'],'&extra='.urlencode($_GET['extra']),'&page='.$_G['page']),'',base64_decode($_G['currenturl_encode']));
		}
		$replace_words = array('{boardurl}', '{bbname}', '{author}');
		$replace = array($_G['siteurl'], $_G['setting']['bbname'], '<a href="'.$this->home_link.'" target="_blank" style="color:'.$this->settings['author_color'].';">'.$_G['forum_thread']['author'].'</a>');
		$this->text = str_replace($replace_words,$replace,$this->_linkseo($this->settings['text']));
		$this->legend_css = str_replace($replace_words,$replace,$this->settings['legend_css']);
		$this->line = ($this->text != '') ? $this->text : '<div align="center" style="font-size:15px;">'.lang('plugin/dsu_marcocopyright', 'text_error').'</div>';
		$this->text_mini = str_replace(array('{bbname}', '{boardurl}', '{author}', '{blank}', PHP_EOL, '"'),array($_G['setting']['bbname'], $_G['siteurl'], '<a href="'.$this->home_link.'" target="_blank" style="color:'.$this->settings['author_color'].';">'.$_G['forum_thread']['author'].'</a>', '<br>', '<br>', ''),$this->_linkseo($this->settings['text_mini']));
	}
	function global_header(){
		global $_G;
		$url = base64_decode($_G['currenturl_encode']);
		if($_G['mod'] == 'viewthread'){
			if(in_array('forum_viewthread', $_G['setting']['rewritestatus'])){
				$url = $_G['siteurl'].rewriteoutput('forum_viewthread', 1, '', $_G['tid'], $_G['page'], $_G['prevpage'], '');
			}else{
				$url = str_replace(array('&extra='.$_GET['extra'],'&extra='.urlencode($_GET['extra']),'&page='.$_G['page']),'',$url);
			}
		}elseif($_G['mod'] == 'redirect'){
			if(in_array('forum_viewthread', $_G['setting']['rewritestatus'])){
				$url = $_G['siteurl'].rewriteoutput('forum_viewthread', 1, '', $_G['tid'], $_G['page'], $_G['prevpage'], '');
			}
		}elseif($_G['mod'] == 'forumdisplay'){
			if(in_array('forum_forumdisplay', $_G['setting']['rewritestatus'])){
				$url = $_G['siteurl'].rewriteoutput('forum_forumdisplay', 1, '', $_G['fid'], $_G['page'], '', '');
			}else{
				$url = str_replace(array('&page='.$_G['page']),'',$url);
			}
		}elseif($_G['basescript'] == 'group' && $_G['mod'] == 'forumdisplay'){
			if(in_array('group_group', $_G['setting']['rewritestatus'])){
				$url = $_G['siteurl'].rewriteoutput('group_group', 1, '', $_G['fid'], $_G['page'], '', '');
			}
		}
		$copy_open_area = str_replace(array('1', '2', '3', '4', '5', '6'),array('forumdisplay', 'viewthread', 'redirect', 'group', 'home', 'portal'),unserialize($this->settings['copy_open_area']));
		$return = '';
		if((in_array($_G['mod'], $copy_open_area) || in_array($_G['basescript'], $copy_open_area)) && in_array($_G['groupid'], unserialize($this->settings['copy_group']))){
			if($this->settings['ban_copy'] && !$this->settings['add_copy']){
				if(in_array($_G['mod'], array('forumdisplay','viewthread','redirect'))){
					if(in_array($_G['fid'], unserialize($this->settings['copy_forum']))){
						$return .= '<script type="text/javascript">document.onselectstart=function(){return false};</script><style type="text/css">html{-ms-user-select:none;-moz-user-select:none;-webkit-user-select:none;}</style>';
					}
				}else{
					$return .= '<script type="text/javascript">document.onselectstart=function(){return false};</script><style type="text/css">html{-ms-user-select:none;-moz-user-select:none;-webkit-user-select:none;}</style>';
				}
			}
			if($this->settings['add_copy'] && !$this->settings['ban_copy']){
				$auto_add = str_replace(array("'", '"', '{bbname}', '{boardurl}', '{url}'),array("\'", '\"', $_G['setting']['bbname'], $_G['siteurl'], $url),$this->settings['add_copy_content']);
				if(in_array($_G['mod'], array('forumdisplay','viewthread','redirect'))){
					if(in_array($_G['fid'], unserialize($this->settings['copy_forum']))){
						$return .= '<script type="text/javascript">function dsuMarcoAddCopyright(){var a="'.$auto_add.'";if("function"==typeof window.getSelection){var b=window.getSelection();if("Microsoft Internet Explorer"==navigator.appName&&navigator.appVersion.match(/MSIE ([\d.]+)/)[1]>=10||"Opera"==navigator.appName){var c=b.getRangeAt(0),d=document.createElement("span");d.appendChild(c.cloneContents()),c.insertNode(d);var e=d.innerHTML.replace(/(?:\n|\r\n|\r)/gi,"").replace(/<\s*script[^>]*>[\s\S]*?<\/script>/gim,"").replace(/<\s*style[^>]*>[\s\S]*?<\/style>/gim,"").replace(/<!--.*?-->/gim,"").replace(/<!DOCTYPE.*?>/gi,"");try{document.getElementsByTagName("body")[0].removeChild(d)}catch(f){d.style.display="none",d.innerHTML=""}}else var e=""+b;var g=document.getElementsByTagName("body")[0],h=document.createElement("div");h.style.position="absolute",h.style.left="-99999px",g.appendChild(h),h.innerHTML=e.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,"$1<br />$2")+"<br />"+a,b.selectAllChildren(h),setTimeout(function(){g.removeChild(h)},0)}else if("object"==typeof document.selection.createRange){event.returnValue=!1;var b=document.selection.createRange().text;window.clipboardData.setData("Text",b+"\r\n"+a)}}document.body.oncopy=dsuMarcoAddCopyright;</script>';
					}
				}else{
					$return .= '<script type="text/javascript">function dsuMarcoAddCopyright(){var a="'.$auto_add.'";if("function"==typeof window.getSelection){var b=window.getSelection();if("Microsoft Internet Explorer"==navigator.appName&&navigator.appVersion.match(/MSIE ([\d.]+)/)[1]>=10||"Opera"==navigator.appName){var c=b.getRangeAt(0),d=document.createElement("span");d.appendChild(c.cloneContents()),c.insertNode(d);var e=d.innerHTML.replace(/(?:\n|\r\n|\r)/gi,"").replace(/<\s*script[^>]*>[\s\S]*?<\/script>/gim,"").replace(/<\s*style[^>]*>[\s\S]*?<\/style>/gim,"").replace(/<!--.*?-->/gim,"").replace(/<!DOCTYPE.*?>/gi,"");try{document.getElementsByTagName("body")[0].removeChild(d)}catch(f){d.style.display="none",d.innerHTML=""}}else var e=""+b;var g=document.getElementsByTagName("body")[0],h=document.createElement("div");h.style.position="absolute",h.style.left="-99999px",g.appendChild(h),h.innerHTML=e.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,"$1<br />$2")+"<br />"+a,b.selectAllChildren(h),setTimeout(function(){g.removeChild(h)},0)}else if("object"==typeof document.selection.createRange){event.returnValue=!1;var b=document.selection.createRange().text;window.clipboardData.setData("Text",b+"\r\n"+a)}}document.body.oncopy=dsuMarcoAddCopyright;</script>';
				}
			}
			if($this->settings['popup']){
				if(in_array($_G['mod'], array('forumdisplay','viewthread','redirect'))){
					if(in_array($_G['fid'], unserialize($this->settings['copy_forum']))){
						$return .= '<script type="text/javascript">function copyright(msg, script){script = !script ? \'\' : script;var c = \'<div class="f_c"><div class="c floatwrap" style="height:'.$this->settings['height_mini'].';">\' + msg + \'</div></div>\';var t = \''.lang('plugin/dsu_marcocopyright', 'copyright').'\' ;showDialog(c, \'info\', t);}document.oncontextmenu=function(){copyright(\''.$this->text_mini.'\', this.href);return false;}</script>';
					}
				}else{
					$return .= '<script type="text/javascript">function copyright(msg, script){script = !script ? \'\' : script;var c = \'<div class="f_c"><div class="c floatwrap" style="height:'.$this->settings['height_mini'].';">\' + msg + \'</div></div>\';var t = \''.lang('plugin/dsu_marcocopyright', 'copyright').'\' ;showDialog(c, \'info\', t);}document.oncontextmenu=function(){copyright(\''.$this->text_mini.'\', this.href);return false;}</script>';
				}
			}
			return $return;
		}
	}
	function _linkseo($string){
		if(preg_match_all('/rel="([^"]*)"/', $string, $matches)){
			$string = str_replace($matches[0], "", $string);
		}
		return preg_replace('/(<a\b[^><]*)>/i', '$1 rel="nofollow">', $string);
	}
}

class plugin_dsu_marcocopyright_forum extends plugin_dsu_marcocopyright{
	function viewthread_postfooter_output(){
		global $_G;
		if($this->settings['forum_open'] && $this->settings['open_mini'] && !in_array($_G['fid'], $this->forums) && !empty($_G['forum_firstpid'])){
			return array(0=>'<style type="text/css">.copyright {background: transparent url(\'source/plugin/dsu_marcocopyright/images/copyright.png\') no-repeat 0 50%; }</style><script type="text/javascript">function mini_copyright(msg, script){script = !script ? \'\' : script;var c = \'<div class="f_c"><div class="c floatwrap" style="height:'.$this->settings['height_mini'].';">\' + msg + \'</div></div>\';var t = \''.lang('plugin/dsu_marcocopyright', 'copyright').'\' ;showDialog(c, \'info\', t);}</script><a class="copyright" style="cursor:pointer;" onclick="mini_copyright(\''.$this->text_mini.'\', this.href);return false;">'.lang('plugin/dsu_marcocopyright', 'copyright').'</a>');
		}else{
			return array();
		}
	}
	function viewthread_postbottom_output(){
		global $_G;
		if($this->settings['forum_open'] && $this->settings['place'] == 1 && !$this->settings['open_mini'] && !in_array($_G['fid'], $this->forums) && $this->forums!='' && !empty($_G['forum_firstpid'])){
			if($this->settings['pictureopen'] && $this->pic_copyright == 1){
				return array(0=>'<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="source/plugin/dsu_marcocopyright/images/defaultpic.png"></fieldset></div><br />');
			}elseif($this->settings['pictureopen'] && $this->pic_copyright == 2){
				return array(0=>'<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="source/plugin/dsu_marcocopyright/images/defaultpic_black.png"></fieldset></div><br />');
			}elseif($this->settings['pictureopen'] && (strpos($this->pic_copyright, 'http://') !== FALSE)){
				return array(0=>'<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="'.$this->pic_copyright.'" onerror="this.src=(\'source/plugin/dsu_marcocopyright/images/defaultpic.png\')"></fieldset></div><br />');
			}else{
				return array(0=>'<br />'.$this->settings['fieldset_css'].$this->legend_css.$this->line.'</fieldset><br />');
			}
		}else{
			return array();
		}
	}
	function viewthread_useraction_output(){
		global $_G;
		$return = '';
		if($this->settings['copy_link'] == 1){
			if($this->copy_link_style == '{default}'){
				$return .='<div style="padding:5px;text-align:center;margin-top:10px;color:#00A2D2;"><b>'.lang('plugin/dsu_marcocopyright', 'copy_link').'</b><input type="text" value="'.$this->post_link.'" size="40" class="px" readonly="readonly" style="vertical-align:middle;">&nbsp;<button type="submit" class="pn" onclick="setCopy(\''.$this->post_subject.'\\n'.$this->post_link.'\', \''.lang('plugin/dsu_marcocopyright', 'copy_link_done').'\')"><em>'.lang('plugin/dsu_marcocopyright', 'copy_link_words').'</em></button></div>';
			}else{
				$return .= '<div align="center"><img src="'.$this->copy_link_style.'" style="cursor:pointer;" onclick="setCopy(\''.$this->post_subject.'\\n'.$this->post_link.'\', \''.lang('plugin/dsu_marcocopyright', 'copy_link_done').'\');"></div>';
			}
		}
		if($this->settings['forum_open'] && $this->settings['place'] == 2 && !$this->settings['open_mini'] && !in_array($_G['fid'], $this->forums) && $this->forums!=''){
			if($this->settings['pictureopen'] && $this->pic_copyright == 1){
				$return .= '<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="source/plugin/dsu_marcocopyright/images/defaultpic.png"></fieldset></div><br />';
			}elseif($this->settings['pictureopen'] && $this->pic_copyright == 2){
				$return .= '<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="source/plugin/dsu_marcocopyright/images/defaultpic_black.png"></fieldset></div><br />';
			}elseif($this->settings['pictureopen'] && (strpos($this->pic_copyright, 'http://') !== FALSE)){
				$return .= '<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="'.$this->pic_copyright.'" onerror="this.src=(\'source/plugin/dsu_marcocopyright/images/defaultpic.png\')"></fieldset></div><br />';
			}else{
				$return .= '<br />'.$this->settings['fieldset_css'].$this->legend_css.$this->line.'</fieldset><br />';
			}
		}
		return '</div><div>'.$return;
	}
}
class plugin_dsu_marcocopyright_group extends plugin_dsu_marcocopyright{
	function viewthread_postfooter_output(){
		global $_G;
		if($this->settings['group_open'] && $this->settings['open_mini'] && !empty($_G['forum_firstpid'])){
			return array(0=>'<style type="text/css">.copyright {background: transparent url(\'source/plugin/dsu_marcocopyright/images/copyright.png\') no-repeat 0 50%; }</style><script type="text/javascript">function mini_copyright(msg, script){script = !script ? \'\' : script;var c = \'<div class="f_c"><div class="c floatwrap" style="height:'.$this->settings['height_mini'].';">\' + msg + \'</div></div>\';var t = \''.lang('plugin/dsu_marcocopyright', 'copyright').'\' ;showDialog(c, \'info\', t);}</script><a class="copyright" style="cursor:pointer;" onclick="mini_copyright(\''.$this->text_mini.'\', this.href);return false;">'.lang('plugin/dsu_marcocopyright', 'copyright').'</a>');
		}else{
			return array();
		}
	}
	function viewthread_postbottom_output(){
		global $_G;
		if($this->settings['group_open'] && $this->settings['place'] == 1 && !$this->settings['open_mini'] && !empty($_G['forum_firstpid'])){
			if($this->settings['pictureopen'] && $this->pic_copyright == 1){
				return array(0=>'<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="source/plugin/dsu_marcocopyright/images/defaultpic.png"></fieldset></div><br />');
			}elseif($this->settings['pictureopen'] && $this->pic_copyright == 2){
				return array(0=>'<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="source/plugin/dsu_marcocopyright/images/defaultpic_black.png"></fieldset></div><br />');
			}elseif($this->settings['pictureopen'] && (strpos($this->pic_copyright, 'http://') !== FALSE)){
				return array(0=>'<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="'.$this->pic_copyright.'" onerror="this.src=(\'source/plugin/dsu_marcocopyright/images/defaultpic.png\')"></fieldset></div><br />');
			}else{
				return array(0=>'<br />'.$this->settings['fieldset_css'].$this->legend_css.$this->line.'</fieldset><br />');
			}
		}else{
			return array();
		}
	}
	function viewthread_useraction_output(){
		global $_G;
		$return = '';
		if($this->settings['copy_link'] == 1){
			if($this->copy_link_style == '{default}'){
				$return .='<div style="padding:5px;text-align:center;margin-top:10px;color:#00A2D2;"><b>'.lang('plugin/dsu_marcocopyright', 'copy_link').'</b><input type="text" value="'.$this->post_link.'" size="40" class="px" readonly="readonly" style="vertical-align:middle;">&nbsp;<button type="submit" class="pn" onclick="setCopy(\''.$this->post_subject.'\\n'.$this->post_link.'\', \''.lang('plugin/dsu_marcocopyright', 'copy_link_done').'\')"><em>'.lang('plugin/dsu_marcocopyright', 'copy_link_words').'</em></button></div>';
			}else{
				$return .= '<div align="center"><img src="'.$this->copy_link_style.'" style="cursor:pointer;" onclick="setCopy(\''.$this->post_subject.'\\n'.$this->post_link.'\', \''.lang('plugin/dsu_marcocopyright', 'copy_link_done').'\');"></div>';
			}
		}
		if($this->settings['group_open'] && $this->settings['place'] == 2 && !$this->settings['open_mini']){
			if($this->settings['pictureopen'] && $this->pic_copyright == 1){
				$return .= '<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="source/plugin/dsu_marcocopyright/images/defaultpic.png"></fieldset></div><br />';
			}elseif($this->settings['pictureopen'] && $this->pic_copyright == 2){
				$return .= '<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="source/plugin/dsu_marcocopyright/images/defaultpic_black.png"></fieldset></div><br />';
			}elseif($this->settings['pictureopen'] && (strpos($this->pic_copyright, 'http://') !== FALSE)){
				$return .= '<br /><div align="center">'.$this->settings['fieldset_css'].$this->legend_css.'<img src="'.$this->pic_copyright.'" onerror="this.src=(\'source/plugin/dsu_marcocopyright/images/defaultpic.png\')"></fieldset></div><br />';
			}else{
				$return .= '<br />'.$this->settings['fieldset_css'].$this->legend_css.$this->line.'</fieldset><br />';
			}
		}
		return '</div><div>'.$return;
	}
}
?>