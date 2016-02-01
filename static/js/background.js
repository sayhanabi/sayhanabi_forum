var totalImages = 5; //总图片数量
var defaultImage = 5; //默认图片，填编号
var cssAssets = [
    [
        ["forum", "thread", "home::spacecp", "home::task", "home::medal", "home::magic", "portal", "userapp", "cp", "search"],
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
];

var shc0;
var shc1;
var zone;
var region;

function drawThemeSelect() {
    var theme = "<p>";
    for (var i = 1; i < (totalImages + 1); i++) {
       if (i==shc0){
            theme = theme + "<a href=\"javascript:reDrawBackground("+ i + ");\" style=\"color: red\" id=\"theme"+ i +"\">背景"+ i +" </a><span class=\"pipe\">|</span>";
        } else {
            theme = theme + "<a href=\"javascript:reDrawBackground("+ i + ");\" id=\"theme"+ i +"\">背景"+ i +" </a><span class=\"pipe\">|</span>";
        }
        if (i%5==0){
            theme = theme + "</p><p>"
        }
    };
    if (shc1==1){
        theme = theme + "</p<p><a href=\"javascript:setTransparent(1);\" id=\"trans1\" style=\"color:red\">透明 </a><span class=\"pipe\">|</span><a href=\"javascript:setTransparent(0);\" id=\"trans0\">不透明 </a><span class=\"pipe\">|</span>"
    } else {
        theme = theme + "</p<p><a href=\"javascript:setTransparent(1);\" id=\"trans1\">透明 </a><span class=\"pipe\">|</span><a href=\"javascript:setTransparent(0);\" style=\"color:red\" id=\"trans0\">不透明 </a><span class=\"pipe\">|</span>"
    }
    theme = theme + "</p><p><a href=\"javascript:;\" onclick=\"widthauto(this)\">切换宽版/窄版 </a><span class=\"pipe\">|</span><a id=\"sslct\" href=\"javascript:;\" onmouseover=\"delayShow(this, function() {showMenu({'ctrlid':'sslct','pos':'34!'})});\">切换风格 </a><span class=\"pipe\">|</span>";
    var area = document.getElementById("um");
    area.innerHTML = area.innerHTML + theme + "</p>";
}

function setTransparent(newTrans) {
    try {
        document.getElementById("trans"+shc1).removeAttribute("style");
    } catch (err) {
    
    }
    shc1 = newTrans;
    localStorage.SayHanabiBgTrans = newTrans;
    try {
        document.getElementById("trans"+newTrans).style.color = "red";
    } catch (err) {

    }
    if (newTrans==1) {
        loadBackgroundDim();
    } else {
        loadBackgroundOpaque();
    }
}

function reDrawBackground(newID) {
    try {
        document.getElementById("theme"+shc0).removeAttribute("style");
    } catch (err) {
    
    }
    shc0 = newID;
    localStorage.SayHanabiBg = newID;
    try {
        document.getElementById("theme"+newID).style.color = "red";
    } catch (err) {

    }
    loadBackground();
    loadBackgroundDim();
    if (shc1==0){
        loadBackgroundOpaque();
    }
}

function getRegion(path) {
    var position = path.indexOf(".php");
    if (position !== -1) {
        return path.substring(1, position);
    } else {
        return path.substring(1, path.indexOf("-"));
    }
}

function getMod(path) {
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
}

function getZone() {
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
}

function initEnvironment() {
    if (localStorage.SayHanabiBg) {
        shc0 = localStorage.SayHanabiBg;
    } else {
        shc0 = defaultImage;
        localStorage.SayHanabiBg = defaultImage;
    }
    if (localStorage.SayHanabiBgTrans) {
        shc1 = localStorage.SayHanabiBgTrans;
    } else {
        shc1 = 1;
        localStorage.SayHanabiBgTrans = 1;
    }
    zone = getZone();
    region = zone.substring(0, zone.indexOf("::"));
}

function loadBackground() {
    document.body.removeAttribute("style");
    document.body.style.backgroundImage = "url(static/image/background/" + shc0 + ".jpg)";
    document.body.style.backgroundSize = "cover";
    document.body.style.backgroundRepeat = "no-repeat";
    document.body.style.backgroundPosition = "center center";
    document.body.style.backgroundAttachment = "fixed";
    document.body.style.backgroundSize = "cover";
}

function loadBackgroundDim() {
    for (var i = 0; i < cssAssets.length; i++) {
        for (var j = 0; j < cssAssets[i][0].length; j++) {
            if (cssAssets[i][0][j] == zone || cssAssets[i][0][j] == region) {
                for (var k = 0; k < cssAssets[i][1].length; k++) {
                    var target = document.getElementsByClassName(cssAssets[i][1][k]);
                    for (var l = 0; l < target.length; l++) {
                        target[l].removeAttribute("style");
                        target[l].style.backgroundImage = "url(static/image/background/" + shc0 + "_dim.jpg)";
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
}

function loadBackgroundOpaque() {
    for (var i = 0; i < cssAssets.length; i++) {
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
}

initEnvironment();
