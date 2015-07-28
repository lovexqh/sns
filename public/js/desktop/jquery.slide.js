/**
 * @author ZzStudio
 *
 * JQ-Slide插件参数说明表
	参数名
	参数值(默认值可以省略)   
	
	effect 
	scroolY（默认）：垂直滚动   
	scroolX：水平滚动 
	scroolTxt：文本垂直滚动 
	fade：淡出 
	scroolLoop：水平左右点击滚动 
	
	autoPlay
	true（默认）： or false   
	
	speed
	动画速度时间，默认"normal"，可以使用毫秒或者JQ中的fast，slow，normal   
	
	timer
	滚动间隔时间，默认"1000，可以使用毫秒或者JQ中的fast，slow，normal   
	
	claNav
	JQ-slide-nav（默认）：触点对象数组父级   
	
	claCon
	JQ-slide-content（默认）：滚动对象或滚动对象父级   
	
	steps
	1（默认）：滚动几个   

 */
(function($){
	$.fn.Slide=function(options){
		var opts = $.extend({},$.fn.Slide.deflunt,options);
		var index=1;
		var ContentNav = $("." + opts.claNav, $(this));//nav对象
		var targetLi = $("." + opts.claNav + " li", $(this));//目标对象
		var clickNext = $("." + opts.claNav + " .next", $(this));//点击下一个按钮
		var clickPrev = $("." + opts.claNav + " .prev", $(this));//点击上一个按钮
		var ContentBox = $("." + opts.claCon , $(this));//滚动的对象
		var ContentBoxNum=ContentBox.children().size();//滚动对象的子元素个数
		var slideH=ContentBox.children().first().height();//滚动对象的子元素个数高度，相当于滚动的高度
		var slideW=ContentBox.children().first().width();//滚动对象的子元素宽度，相当于滚动的宽度
		var autoPlay;
		var slideWH;
		if(opts.effect=="scroolY"||opts.effect=="scroolTxt"){
			slideWH=slideH;
		}else if(opts.effect=="scroolX"||opts.effect=="scroolLoop"){
			ContentBox.css("width",ContentBoxNum*slideW);
			slideWH=slideW;
		}else if(opts.effect=="fade"){
			ContentBox.children().first().css("z-index","1");
		}

		return this.each(function() {
			var $this=$(this);
			//设置Nav上的文字
			if(opts.text && ContentNav){
				$.fn.Slide.setNavigate(ContentBox,ContentNav,index-1,'span')
			}
			//滚动函数
			var doPlay=function(){
				$.fn.Slide.effect[opts.effect](ContentBox, targetLi, index, slideWH, opts);
				index++;
				if (index*opts.steps >= ContentBoxNum) {
					index = 0;
				}
				if(opts.text && ContentNav){
					$.fn.Slide.setNavigate(ContentBox,ContentNav,index-1,'span')
				}
			};
			clickNext.click(function(event){
				clearInterval(autoPlay);
				for(var i=0;i<opts.steps;i++){
					ContentBox.find("li:first").appendTo(ContentBox);
				}
				ContentBox.css({"left":"0"});
				$.fn.Slide.effectLoop.scroolLeft(ContentBox, targetLi, index, slideWH, opts);
				if(opts.text && ContentNav){
					$.fn.Slide.setNavigate(ContentBox,ContentNav,index,'span')
				}
				autoPlay = setInterval(doPlay, opts.timer);
				event.preventDefault();
			});
			clickPrev.click(function(event){
				clearInterval(autoPlay);
				for(var i=0;i<opts.steps;i++){
	                ContentBox.find("li:last").prependTo(ContentBox);
	            }
	          	ContentBox.css({"left":-index*opts.steps*slideW});
				$.fn.Slide.effectLoop.scroolRight(ContentBox, targetLi, index, slideWH, opts);
				if(opts.text && ContentNav){
					$.fn.Slide.setNavigate(ContentBox,ContentNav,index-1,'span')
				}
				autoPlay = setInterval(doPlay, opts.timer);
				event.preventDefault();
			});
			//自动播放
			if (opts.autoPlay) {
				autoPlay = setInterval(doPlay, opts.timer);
				ContentBox.hover(function(){
					if(autoPlay){
						clearInterval(autoPlay);
					}
				},function(){
					if(autoPlay){
						clearInterval(autoPlay);
					}
					autoPlay=setInterval(doPlay, opts.timer);
				});
			}
			
			//目标事件
			targetLi.hover(function(){
				if(autoPlay){
					clearInterval(autoPlay);
				}
				index=targetLi.index(this);
				window.setTimeout(function(){$.fn.Slide.effect[opts.effect](ContentBox, targetLi, index, slideWH, opts);},200);
				
			},function(){
				if(autoPlay){
					clearInterval(autoPlay);
				}
				autoPlay = setInterval(doPlay, opts.timer);
			});
    	});
	};
	$.fn.Slide.deflunt={
		effect : "scroolY",
		autoPlay:true,
		speed : "normal",
		timer : 1000,
		defIndex : 0,
		claNav:"JQ-slide-nav",
		claCon:"JQ-slide-content",
		steps:1,
		text:1
	};
	$.fn.Slide.effectLoop={
		scroolLeft:function(contentObj,navObj,i,slideW,opts,callback){
			contentObj.animate({"left":-i*opts.steps*slideW},opts.speed,callback);
			if (navObj) {
				navObj.eq(i).addClass("on").siblings().removeClass("on");
			}
		},
		
		scroolRight:function(contentObj,navObj,i,slideW,opts,callback){
			contentObj.stop().animate({"left":0},opts.speed,callback);
			
		}
	}
	$.fn.Slide.effect={
		fade:function(contentObj,navObj,i,slideW,opts){
			contentObj.children().eq(i).stop().animate({opacity:1},opts.speed).css({"z-index": "1"}).siblings().animate({opacity: 0},opts.speed).css({"z-index":"0"});
			navObj.eq(i).addClass("on").siblings().removeClass("on");
		},
		scroolTxt:function(contentObj,undefined,i,slideH,opts){
			//alert(i*opts.steps*slideH);
			contentObj.animate({"margin-top":-opts.steps*slideH},opts.speed,function(){
                for( var j=0;j<opts.steps;j++){
                	contentObj.find("li:first").appendTo(contentObj);
                }
                contentObj.css({"margin-top":"0"});
            });
		},
		scroolX:function(contentObj,navObj,i,slideW,opts,callback){
			contentObj.stop().animate({"left":-i*opts.steps*slideW},opts.speed,callback);
			if (navObj) {
				navObj.eq(i).addClass("on").siblings().removeClass("on");
			}
		},
		scroolY:function(contentObj,navObj,i,slideH,opts){
			contentObj.stop().animate({"top":-i*opts.steps*slideH},opts.speed);
			if (navObj) {
				navObj.eq(i).addClass("on").siblings().removeClass("on");
			}
		}
	};
	//设置nav显示的内容
	$.fn.Slide.setNavigate=function(contentObj,navObj,i,span,callback){
		//获取图片的简介
		var description = contentObj.find('li').eq(i).find('img').attr('description');
		if(!navObj.find('span').length){
			navObj.prepend("<"+span+"></"+span+">");
		}
		navObj.find(span).html(description);
	}
})(jQuery);