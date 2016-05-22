"use strict";

(function (){
	var servers = [
		{"host": "mc.say-hanabi.com", "port": "25565", "alias": "mc"},
		{"host": "mc19.say-hanabi.com", "port": "25565", "alias": "mc19"}
	],
	api = "https://say-hanabi.com/minecraft/mcQuery.php",
	idPrefix = "mcstat-",
	queryParser = function(obj){
		var result = "?";
		for (var i = 0; i < obj.length; ++i){
			result = result + obj[i][0] + "=" + obj[i][1] + "&";
		}
		return result;
	},
	ajaxRequest = function(host, parameters, alias){
		var xhttp = new XMLHttpRequest();
  		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
 				singleUpdater(JSON.parse(xhttp.responseText), alias);
			}
  		};
  		xhttp.open("GET", host + parameters, true);
		xhttp.send();
	},
	singleUpdater = function(jsonResult, queryName){
		var targetElement = document.getElementById(idPrefix+queryName);
		if (jsonResult && jsonResult["result"] == 1){
			targetElement.getElementsByClassName("online")[0].innerHTML = jsonResult["players"]["online"];
			//targetElement.getElementsByClassName("max")[0].innerHTML = jsonResult["players"]["max"];
			if (jsonResult["description"]["text"]){
				targetElement.setAttribute("title", jsonResult["description"]["text"]);
			} else {
				targetElement.setAttribute("title", jsonResult["description"]);
			}
			if (jsonResult["players"]["online"] > 0) {
				var sample = jsonResult["players"]["sample"],
				sampleElement = targetElement.getElementsByClassName("sample")[0],
				buffer = "(",
				textNode;
				for (var i = 0; i < sample.length; ++i){
					buffer = buffer + sample[i]["name"] + ", ";
				}
				textNode = document.createTextNode(buffer.substring(0, buffer.length - 2)+")");
				sampleElement.innerHTML = "";
				sampleElement.appendChild(textNode);
			}
		}
	},
	MainQuerier = function(){
		for (var i = 0; i < servers.length; ++i){
			ajaxRequest(api, queryParser([["server", servers[i]["alias"]]]), servers[i]["alias"]);
		}
	};
	document.addEventListener('DOMContentLoaded', function() {
    	//var mainTarget = document.getElementById("mcstat");
    	var mainTarget = document.getElementById("toptb").getElementsByClassName("z")[0],
    	headElement = document.createElement("A");
    	headElement.innerHTML = "花火学园 Minecraft 服务器";
    	headElement.style.color = "red";
    	mainTarget.appendChild(headElement);
    	for (var i = 0; i < servers.length; ++i){
			var targetElement = document.createElement("A");
			targetElement.innerHTML = '<span class="address"></span> \
										<span class="online"></span> 人在线 \
										<span class="sample" style="color:blue"></span>';
			targetElement.id = idPrefix + servers[i]["alias"];
			targetElement.getElementsByClassName("address")[0].innerHTML = servers[i]["host"];
			mainTarget.appendChild(targetElement);
		}
		MainQuerier();
		setInterval(MainQuerier, 60000);
	}, false);
})();
