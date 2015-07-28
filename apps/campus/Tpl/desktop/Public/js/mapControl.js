
/*
//增加覆盖物
function addOverly(hlname) {
    var ps = new Array(); //定义覆盖物数组
    for (var i = 0; i < hlname.length; i++) {
        ps.push(new BMap.Point(hlname[i].x, hlname[i].y));
    }
    var polygon = new BMap.Polygon(ps, { strokeColor: "", fillColor: "#84C0F2", strokeWeight: 0, StrokeOpacity: 0.5 });
    //polygon.hide();
    return polygon;
}
*/


function yanzheng(pt) {var campusMark = new Array();for (var i = 0; i < campus_polygon.length; i++) {var result = BMapLib.GeoUtils.isPointInPolygon(pt, campus_polygon[i]);if (result == true) {campusMark.push(campus_polygon[i]);campusMark.push(AllPopInformation[i]);return campusMark;}}};function tourpop(toururl) {w = $("#map").width() * 0.8;h = $("#map").height() * 0.8;var viewHtml = "<div id='tourw' style='width:100%; height:100%; z-index:300; background-image:url(images/toumbg.png); background-repeat:repeat; position:absolute; top:0px; left:0px;'>" + "<div id='closei' style='background-image:url(images/Close-2-icon.png); width:40px; height:40px; float:right; margin:15px 20px 0px 0px;'></div>" + "<iframe frameborder='0' src='" + toururl + "' " + "style=\" border:none; left:" + (($("#map").width() - w) * 0.5) + "px;" + "top:" + (($("#map").height() - h) * 0.5) + "px; width:" + w + "px; height:" + h + "px; position:absolute;\">" + "</iframe></div>";$("#map").append(viewHtml);$("#closei").click(function () {$("#tourw").fadeOut("slow", function () {$("#tourw").remove();});});};function tourpop_En(toururl) {w = $("#map").width() * 0.8;h = $("#map").height() * 0.8;var viewHtml = "<div id='tourw' style='width:100%; height:100%; z-index:300; background-image:url(../images/toumbg.png); background-repeat:repeat; position:absolute; top:0px; left:0px;'>" + "<div id='closei' style='background-image:url(../images/Close-2-icon.png); width:40px; height:40px; float:right; margin:15px 20px 0px 0px;'></div>" + "<iframe frameborder='0' src='" + toururl + "' " + "style=\" border:none; left:" + (($("#map").width() - w) * 0.5) + "px;" + "top:" + (($("#map").height() - h) * 0.5) + "px; width:" + w + "px; height:" + h + "px; position:absolute;\">" + "</iframe></div>";$("#map").append(viewHtml);$("#closei").click(function () {$("#tourw").fadeOut("slow", function () {$("#tourw").remove();});});};



function isIE() {/*is ie???*/if (window.navigator.userAgent.toLowerCase().indexOf("msie") >= 1)
        return true;
    else
        return false;
}

if (!isIE()) { /*firefox innerText define*/HTMLElement.prototype.__defineGetter__("innerText", function () {var anyString = "";var childS = this.childNodes; for (var i = 0; i < childS.length; i++) {if (childS[i].nodeType == 1) {anyString += childS[i].tagName == "BR" ? '\n' : childS[i].textContent;}else if (childS[i].nodeType == 3) {anyString += childS[i].nodeValue;}}return anyString;});HTMLElement.prototype.__defineSetter__("innerText", function (sText) {this.textContent = sText;});};

/*字符串取出空格*/
String.prototype.trim = function () {
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
String.prototype.ltrim = function () {
    return this.replace(/(^\s*)/g, "");
}
String.prototype.rtrim = function () {
    return this.replace(/(\s*$)/g, "");
}


function trim(str) { //删除左右两端的空格
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
function ltrim(str) { //删除左边的空格
    return str.replace(/(^\s*)/g, "");
}
function rtrim(str) { //删除右边的空格
    return str.replace(/(\s*$)/g, "");
}



function invokeClick(element) {
    if (element.click)
        element.click();
    else if (element.fireEvent)
        element.fireEvent('onclick');
    else if (document.createEvent) {
        var evt = document.createEvent("MouseEvents");
        evt.initEvent("click", true, true);
        element.dispatchEvent(evt);
    }
}

function Campus_search() {var alength = $("#accordion div div a").length;for (var i = 0; i < alength; i++) {if (trim($("#inputer").attr("value")) == trim($("#accordion div div a")[i].innerText)) {invokeClick($("#accordion div div a")[i]);}}}