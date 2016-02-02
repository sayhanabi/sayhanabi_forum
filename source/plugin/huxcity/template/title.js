var sPop = null;
var pltsoffsetX = 10;
var pltsoffsetY = 15;

document.write('\
	<style type="text/css"> \
	#popLayer { \
		position:absolute; \
		Z-INDEX: 1000; \
		text-align : left; \
		white-space: nowrap; \
		font-family: Tahoma, Verdana; \
		visibility: hidden; \
	} \
	</style> \
	<div id="popLayer" class="popupmenu_popup" nowrap></div> \
');

function showPopupText(event) {
	try {
		o = event.srcElement ? event.srcElement : event.target;
		if (o.name=='xxx'){ 
		if (o.alt != null && o.alt != '') { o.pop = o.alt; o.alt = ''; }
		if (o.title != null && o.title != '') { o.pop = o.title; o.title = ''; }
		if (o.pop != sPop) {
			sPop = o.pop;
			if (sPop == null || sPop == '') {
				$('popLayer').style.visibility = 'hidden';
				$('popLayer').innerHTML = '';
			} else {
				$('popLayer').style.visibility = 'visible';
				$('popLayer').innerHTML = sPop.replace(/<(.*)>/g, "&lt;$1&gt;").replace(/\n/g, '<br />');
				moveToMouseLoc(event);
			}
		}} else {
			if (o.alt != null && o.alt != '') { o.pop = o.alt;}
		if (o.title != null && o.title != '') { o.pop = o.title;}
		if (o.pop != sPop) {
			sPop = o.pop;
			if (sPop == null || sPop == '' ||1) {
				$('popLayer').style.visibility = 'hidden';
				$('popLayer').innerHTML = '';
			} else {
				$('popLayer').style.visibility = 'visible';
				$('popLayer').innerHTML = sPop.replace(/<(.*)>/g, "&lt;$1&gt;").replace(/\n/g, '<br />');
				moveToMouseLoc(event);
			}
		}
		}
	} catch (e) {
		return true;
	}
}

function moveToMouseLoc(event){
	try {
		if ($('popLayer').innerHTML == '') return true;
		var MouseX = event.clientX;
		var MouseY = event.clientY;
		var popHeight = $('popLayer').offsetHeight;
		var popWidth = $('popLayer').offsetWidth;
		if (MouseY + pltsoffsetY + popHeight > document.documentElement.clientHeight - 10) {
			popTopAdjust =- popHeight - pltsoffsetY * 1.5; 
		} else {
			popTopAdjust = 0;
		}
		if (MouseX + pltsoffsetX + popWidth > document.documentElement.clientWidth - 10) {
			popLeftAdjust =- popWidth - pltsoffsetX * 2; 
		} else {
			popLeftAdjust = 0;
		}
		var pleft = MouseX + pltsoffsetX + document.documentElement.scrollLeft + popLeftAdjust;
		var ptop = MouseY + pltsoffsetY + document.documentElement.scrollTop + popTopAdjust;
		$('popLayer').style.left = (pleft > 5 ? pleft : 5) + 'px';
		$('popLayer').style.top = (ptop > 5 ? ptop : 5) + 'px';
	  	return true;
	} catch (e) {
		return true;
	}
}

if (!document.onmouseover) {
	document.onmouseover = function(e) {
		!e ? showPopupText(window.event) : showPopupText(e);
	};
}
