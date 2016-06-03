'use strict';

var shbg = function(){
    var prefix="shbg",
    info="infobox",
    link="image",
    through="through",
    srcPrefix="https://say-hanabi.com/static/image/background/",
    loadState=0, 
    totalImage=5,
    defaultImage=4,
    defaultTrans=1,
    curImage=localStorage.SayHanabiBg || defaultImage,
    curTran=localStorage.SayHanabiBgTrans || defaultTrans,
   	imageSrc=srcPrefix+curImage+".jpg",
   	imageTransSrc=srcPrefix+curImage+"_dim.jpg",
    cssAssets = [
	    [
	        ["forum", "thread", "home::spacecp", "home::task", "home::medal", "home::magic", "portal", "userapp", "cp", "search", "huxcity", "plugin"],
	        ["bm_c", "pl", "ct2_a", "ct1", "frame"]
	    ],
	    [
	        ["group", "dsu_paulsign", "plugin"],
	        ["ct2"]
	    ],
	    [
	        ["member"],
	        ["bm"]
	    ],
	    [
	        ["home::space", "home::follow", "misc", "space"],
	        ["ct2_a", "ct1"]
	    ],
	    [
	        ["home"],
	        ["ct3_a"]
	    ],
	    [
	        ["forum"],
	        ["frame-tab"]
	    ],
	    [
	        ["portal"],
	        ["tb-c"]
	    ],
	    [
	        ["misc::mobile"],
	        ["ptw"]
	    ]
	],
	imageAssets=[
		{"user": "dummy", "source": "other"},
		{"user": "dummy", "source": "other"},
		{"user": "dummy", "source": "other"},
		{"user": "dummy", "source": "other"},
		{"user": "dummy", "source": "other"},
	],
    load=function(text){
        if (loadState) return;
        loadState=1;
        var target = document.getElementById(prefix+"-"+info);
        target.innerHTML=text;
        target.style.display="block";
    },
    unload=function(){
        if (!loadState) return;
        var target = document.getElementById(prefix+"-"+info);
        target.style.display="none";
        loadState=0;
    },
    loadBackground=function(){
    	document.body.removeAttribute("style");
	    document.body.style.backgroundImage = "url("+imageSrc+")";
	    document.body.style.backgroundSize = "cover";
	    document.body.style.backgroundRepeat = "no-repeat";
	    document.body.style.backgroundPosition = "center center";
	    document.body.style.backgroundAttachment = "fixed";
	    document.body.style.backgroundSize = "cover";
    },
    loadBackgroundDim=function(){
    	for (var i = 0; i < cssAssets.length; ++i) {
	        for (var j = 0; j < cssAssets[i][0].length; ++j) {
	            if (cssAssets[i][0][j] == zone || cssAssets[i][0][j] == region) {
	                for (var k = 0; k < cssAssets[i][1].length; ++k) {
	                    var target = document.getElementsByClassName(cssAssets[i][1][k]);
	                    for (var l = 0; l < target.length; ++l) {
	                        target[l].removeAttribute("style");
	                        target[l].style.backgroundImage = "url("+imageTransSrc+")";
	                        target[l].style.backgroundSize = "cover";
	                        target[l].style.backgroundRepeat = "no-repeat";
	                        target[l].style.backgroundPosition = "center center";
	                        target[l].style.backgroundAttachment = "fixed";
	                        target[l].style.boxShadow = "2px 2px 15px #929292";
	                    };
	                };
	            }
	        };
	    };
    },
    loadBackgroundOpaque=function(){
		for (var i = 0; i < cssAssets.length; ++i) {
	        for (var j = 0; j < cssAssets[i][0].length; j++) {
	            if (cssAssets[i][0][j] == zone || cssAssets[i][0][j] == region) {
	                for (var k = 0; k < cssAssets[i][1].length; k++) {
	                    var target = document.getElementsByClassName(cssAssets[i][1][k]);
	                    for (var l = 0; l < target.length; l++) {
	                        target[l].removeAttribute("style");
	                        target[l].style.boxShadow = "2px 2px 15px #929292";
	                        target[l].style.backgroundColor = "white";
	                    };
	                };
	            }
	        };
	    }; 
    },
    postChange=function(){
		loadBackground();
		if (curTran==1){
			loadBackgroundDim();
		}
    	unload();
    },
    postTrans=function(){
    	if (curTran==1){
			loadBackgroundDim();
    	} else {
		    loadBackgroundOpaque();
    	}
    	unload();
    },
    handleChange=function(sel){
    	if (sel==curImage||loadState==1) return;
    	load("正在载入背景"+sel+"，上传者"+imageAssets[sel]["user"]+"，来自"+imageAssets[sel]["source"]);
    	curImage = sel;
    	var img = new Image(), imgTran = new Image();
    	imageSrc = srcPrefix+sel+".jpg";
    	imageTransSrc = srcPrefix+sel+"_dim.jpg";
    	img.addEventListener("load", postChange);
    	imgTran.src = imageTransSrc;
    	img.src = imageSrc;
    	localStorage.SayHanabiBg=sel;
    },
    handleTrans=function(sel){
    	if (sel==curTran||loadState==1) return;
    	load("正在切换透明度");
    	curTran=sel;
    	if (sel==1){
    		var img = new Image();
    		imageTransSrc = srcPrefix+curImage+"_dim.jpg",
    		img.addEventListener("load", postTrans);
    		img.src = imageTransSrc;
    	} else {
    		postTrans();
    	}
    	localStorage.SayHanabiBgTrans=sel;
    },
    getRegion=function(path){
		var position = path.indexOf(".php");
	    if (position !== -1) {
	        return path.substring(1, position);
	    } else {
	        return path.substring(1, path.indexOf("-"));
	    }
    },
    getMod=function(path){
		var position = path.split("mod=");
	    if (position.length !== 1) {
	        var param = position[1].indexOf("&");
	        if (param !== -1) {
	            return position[1].substring(0, param);
	        } else {
	            return position[1];
	        }
	    } else {
	        return undefined;
	    }
    }, 
    getZone=function(){
		var path = window.location.pathname;
	    var query = window.location.search;
	    var region = getRegion(path);
	    var mod = getMod(query);
	    if (mod) {
	        mod = "::" + mod;
	    } else {
	        mod = "";
	    }
	    return region + mod;
    },
    zone=getZone(),
    region=zone.substring(0, zone.indexOf("::")),
    phase1=function(){
    	var img = new Image();
    	img.addEventListener("load", loadBackground);
    	img.src = imageSrc;
    },
    phase2=function(){
    	if (curTran==1){
    		var img = new Image();
    		img.addEventListener("load", loadBackgroundDim);
    		img.src = imageTransSrc;
    	} else {
    		loadBackgroundOpaque();
      	}
    };
    document.addEventListener("DOMContentLoaded", function(){ 
            var elm=document.createElement("P"),
            target=document.getElementById("um"),
            lnk=document.createElement("A"),
            spn=document.createElement("SPAN"),
            firstline=document.createElement("P"),
            secondline=document.createElement("P"),
            trans,
            opaq;
            elm.style="min-width:60px;color:white;position:fixed;right:10px;bottom:10px;background:purple;";
            elm.innerHTML="Test";
            elm.id=prefix+"-"+info;
            lnk.href="javascript:void(0);";
            spn.innerHTML="|";
            spn.setAttribute("class", "pipe");
            trans=lnk.cloneNode();
            opaq=lnk.cloneNode();
            for (var i = 0; i <= totalImage - 1; ++i){
            	var cur=lnk.cloneNode()
            	cur.setAttribute("data-"+prefix+"-"+link,i);
            	cur.innerHTML="背景"+i;
            	cur.addEventListener("click", function(){ handleChange(this.getAttribute("data-"+prefix+"-"+link)) });
            	firstline.appendChild(cur);
            	firstline.appendChild(spn.cloneNode(true));
            }
            trans.innerHTML="透明";
            trans.setAttribute("data-"+prefix+"-"+through, 1);
            trans.addEventListener("click", function(){ handleTrans(1) });
            opaq.innerHTML="不透明";
            opaq.setAttribute("data-"+prefix+"-"+through, 0);
            opaq.addEventListener("click", function(){ handleTrans(0) });
            secondline.appendChild(trans);
            secondline.appendChild(spn.cloneNode(true));
            secondline.appendChild(opaq);
            secondline.appendChild(spn.cloneNode(true));
            target.appendChild(firstline);
            target.appendChild(secondline);
            document.body.appendChild(elm);
    });
};

shbg();
