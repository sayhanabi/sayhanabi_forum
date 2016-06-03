var ShBg = function() {
    var prefix = "shbg",
        info = "infobox",
        link = "image",
        through = "through",
        srcPrefix = "/static/image/background/",
        loadState = 0,
        totalImage = 5,
        defaultImage = 4,
        defaultTrans = 1,
        curImage = localStorage.SayHanabiBg || defaultImage,
        curTran = localStorage.SayHanabiBgTrans || defaultTrans,
        imageSrc = srcPrefix + curImage + ".jpg",
        imageTransSrc = srcPrefix + curImage + "_dim.jpg",
        infoElm = document.createElement("P"),
        cssAssets = [
            [
                ["forum", "thread", "home::spacecp", "home::task", "home::medal", "home::magic", "portal", "userapp", "cp", "search", "plugin"],
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
        imageAssets = [{
            "user": "dummy",
            "source": "other",
            "id": 1
        }, {
            "user": "dummy",
            "source": "other",
            "id": 2
        }, {
            "user": "dummy",
            "source": "other",
            "id": 3
        }, {
            "user": "dummy",
            "source": "other",
            "id": 4
        }, {
            "user": "dummy",
            "source": "other",
            "id": 5
        }, ],
        load = function() {
            if (loadState) return;
            loadState = 1;
            document.getElementById(prefix+"-"+info).innerHTML=infoUpdate();
        },
        unload = function() {
            if (!loadState) return;
            loadState = 0;
            document.getElementById(prefix+"-"+info).innerHTML=infoUpdate();
        },
        infoUpdate = function() {
            var infoText = imageAssets[curImage]["source"]+'，'+'\u7531<a href="/space-uid-'+imageAssets[curImage]["id"]+'.html" target="_blank">'+imageAssets[curImage]["user"]+'</a>\u63A8\u8350';            
            if (loadState==1){
                return '\u6B63\u5728\u8F7D\u5165'+infoText;
            } else {
            	return infoText;
            }
        },
        loadBackground = function() {
            document.body.removeAttribute("style");
            document.body.style.backgroundImage = "url(" + imageSrc + ")";
            document.body.style.backgroundSize = "cover";
            document.body.style.backgroundRepeat = "no-repeat";
            document.body.style.backgroundPosition = "center center";
            document.body.style.backgroundAttachment = "fixed";
            document.body.style.backgroundSize = "cover";
            if (curTran == 0) {
                unload();
            }
        },
        loadBackgroundDim = function() {
            for (var i = 0; i < cssAssets.length; ++i) {
                for (var j = 0; j < cssAssets[i][0].length; ++j) {
                    if (cssAssets[i][0][j] == zone || cssAssets[i][0][j] == region) {
                        for (var k = 0; k < cssAssets[i][1].length; ++k) {
                            var target = document.getElementsByClassName(cssAssets[i][1][k]);
                            for (var l = 0; l < target.length; ++l) {
                                target[l].removeAttribute("style");
                                target[l].style.backgroundImage = "url(" + imageTransSrc + ")";
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
            unload();
        },
        loadBackgroundOpaque = function() {
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
            unload();
        },
        postChange = function() {
            loadBackground();
            if (curTran == 1) {
                loadBackgroundDim();
            }
        },
        postTrans = function() {
            if (curTran == 1) {
                loadBackgroundDim();
            } else {
                loadBackgroundOpaque();
            }
        },
        handleChange = function(sel) {
            if (sel == curImage || loadState == 1) return;
            load();
            curImage = sel;
            var img = new Image(),
                imgTran = new Image();
            imageSrc = srcPrefix + sel + ".jpg";
            imageTransSrc = srcPrefix + sel + "_dim.jpg";
            img.onload = postChange;
            imgTran.src = imageTransSrc;
            img.src = imageSrc;
            localStorage.SayHanabiBg = sel;
        },
        handleTrans = function(sel) {
            if (sel == curTran || loadState == 1) return;
            load();
            curTran = sel;
            if (sel == 1) {
                var img = new Image();
                imageTransSrc = srcPrefix + curImage + "_dim.jpg",
                    img.onload = postTrans;
                img.src = imageTransSrc;
            } else {
                postTrans();
            }
            localStorage.SayHanabiBgTrans = sel;
        },
        getRegion = function(path) {
            var position = path.indexOf(".php");
            if (position !== -1) {
                return path.substring(1, position);
            } else {
                return path.substring(1, path.indexOf("-"));
            }
        },
        getMod = function(path) {
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
        getZone = function() {
            var path = window.location.pathname,
                query = window.location.search,
                region = getRegion(path),
                mod = getMod(query);
            if (mod) {
                mod = "::" + mod;
            } else {
                mod = "";
            }
            return region + mod;
        },
        zone = getZone(),
        region = zone.substring(0, zone.indexOf("::"));
    this.phase1 = function() {
        var img = new Image();
        img.onload = loadBackground;
        img.src = imageSrc;
        load();
    };
    this.phase2 = function() {
        if (curTran == 1) {
            var img = new Image();
            img.onload = loadBackgroundDim;
            img.src = imageTransSrc;
        } else {
            loadBackgroundOpaque();
        }
    };
    document.addEventListener("DOMContentLoaded", function() {
        try {
            var target = document.getElementById("um");
        } catch (err) {
            return;
        }
        var lnk = document.createElement("A"),
            spn = document.createElement("SPAN"),
            zeroline = document.createElement("P"),
            firstline = document.createElement("P"),
            secondline = document.createElement("P"),
            trans,
            opaq;
        zeroline.id = prefix+"-"+info;
        zeroline.innerHTML=infoUpdate();
        lnk.href = "javascript:void(0);";
        spn.innerHTML = "|";
        spn.setAttribute("class", "pipe");
        trans = lnk.cloneNode();
        opaq = lnk.cloneNode();
        for (var i = 0; i <= totalImage - 1; ++i) {
            var cur = lnk.cloneNode()
            cur.setAttribute("data-" + prefix + "-" + link, i);
            cur.innerHTML = "\u80CC\u666F" + i;
            cur.addEventListener("click", function() {
                handleChange(this.getAttribute("data-" + prefix + "-" + link))
            });
            firstline.appendChild(cur);
            firstline.appendChild(spn.cloneNode(true));
        }
        trans.innerHTML = "\u900F\u660E";
        trans.setAttribute("data-" + prefix + "-" + through, 1);
        trans.addEventListener("click", function() {
            handleTrans(1)
        });
        opaq.innerHTML = "\u4E0D\u900F\u660E";
        opaq.setAttribute("data-" + prefix + "-" + through, 0);
        opaq.addEventListener("click", function() {
            handleTrans(0)
        });
        secondline.appendChild(trans);
        secondline.appendChild(spn.cloneNode(true));
        secondline.appendChild(opaq);
        secondline.appendChild(spn.cloneNode(true));
        target.appendChild(zeroline);
        target.appendChild(firstline);
        target.appendChild(secondline);
    });
};

var shbgInstance = new ShBg();
