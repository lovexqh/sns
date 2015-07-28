    //自定义函数
	var alliconMark = new Array();
    var alliconMark2 = new Array();
	
    function addicon(name) {
        for (var i = 0; i < facilityservice.length; i++) {
            if (facilityservice[i][1] == name) {
                mapAddMark(facilityservice[i][0], facilityservice[i][2], facilityservice[i][1]);
            }
        }
    }
    function mapAddMark(points, pic, id) {
        var iconMark = new Array();
        for (var i = 0; i < points.length; i++) {
            mymarker = new Array();
            var pt = new BMap.Point(points[i].x, points[i].y);
            var myIcon = new BMap.Icon(pic, new BMap.Size(52, 52));
            var marker = new BMap.Marker(pt, {
                icon: myIcon
            });
            map.addOverlay(marker);
            mymarker.push(id);
            mymarker.push(marker);
            iconMark.push(mymarker);
        }
        alliconMark.push(iconMark);
    }
    function cl(mmm) {
        for (var i = 0; i < alliconMark.length; i++) {
            for (var j = 0; j < alliconMark[i].length; j++) {
                if (mmm == alliconMark[i][j][0]) {
                    map.removeOverlay(alliconMark[i][j][1]);
                }
            }
        }
    }
    $(document).ready(function() {
        $(".s_fwss").click(function(e) {
            if (e.target.checked) {
                addicon($(this).attr("value"));
                $("#a_ffwss").attr("checked", true);
                var ccount = $(".s_fwss").length;
                for (var i = 0; i < ccount; i++) {
                    if ($(".s_fwss")[i].checked == false) {
                        $("#a_ffwss").attr("checked", false);
                        break;
                    }
                }
            } else {
                $("#a_ffwss").attr("checked", false);
                cl($(this).attr("value"));
            }
        });
        $("#a_ffwss").click(function(e) {
            var count = $(".s_fwss").length;
            if (e.target.checked) {
                for (var i = 0; i < count; i++) {
                    $(".s_fwss")[i].checked = true;
                    addicon($(".s_fwss")[i].value);
                }
            } else {
                for (var i = 0; i < count; i++) {
                    $(".s_fwss")[i].checked = false;
                    cl($(".s_fwss")[i].value);
                }
            }
        });
		
		$('.Bmap_title').mouseenter(function(){
			  $(this).addClass("hover");
			});
		$('.Bmap_title').mouseleave(function(){
			  $(this).removeClass("hover");
			});
		$('.Bmap_title').click(function(){
			  if($(this).next().css("display")=="block") return;
			  $('.Bmap_title').removeClass('click');
			  $(this).addClass('click');
			  $('.Bmap_title').next('ul').hide(200);
			  $(this).next('ul').show(200);
			
			});
		
    });
    function addicon2(name) {
        for (var i = 0; i < campustraffic.length; i++) {
            if (campustraffic[i][1] == name) {
                mapAddMark2(campustraffic[i][0], campustraffic[i][2], campustraffic[i][1]);
            }
        }
    }
    function mapAddMark2(points, pic, id) {
        var iconMark = new Array();
        for (var i = 0; i < points.length; i++) {
            mymarker = new Array();
            var pt = new BMap.Point(points[i].x, points[i].y);
            var myIcon = new BMap.Icon(pic, new BMap.Size(52, 52));
            var marker = new BMap.Marker(pt, {
                icon: myIcon
            });
            map.addOverlay(marker);
            mymarker.push(id);
            mymarker.push(marker);
            iconMark.push(mymarker);
        }
        alliconMark2.push(iconMark);
    }
    function cl2(mmm) {
        for (var i = 0; i < alliconMark2.length; i++) {
            for (var j = 0; j < alliconMark2[i].length; j++) {
                if (mmm == alliconMark2[i][j][0]) {
                    map.removeOverlay(alliconMark2[i][j][1]);
                }
            }
        }
    }