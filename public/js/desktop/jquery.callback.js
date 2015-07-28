/**
 * @fileOverview This plugin is for adding callbacks to jQuery method. You can add callbacks to class method or instance method of jQuery
 * @dependency jQuery1.7+
 * @author huhai
 * @since 2013-01-21
   demo:
   	html为jquery方法
   	$.addCallback("html", function(){console.log("callback 2");}); 
	$.removeCallback("html"); 
 */
(function($){
  $._callbacks = {};
	$._callbacks_ = {};
	$._alias = {};
	$._alias_ = {};

	$.extend({

		/**
         * @decription 给方法添加回调函数 
         * @param funcName : string 需要添加回调的函数名称 
         * @param callback : function 回调函数（如需移除，不要使用匿名方法） 
         * @param static : boolean 是否是类方法，默认为false 
         */  
		addCallback : function(funcName, callback, static){
			if("string" === typeof(funcName) && $.isFunction(callback)){
				if(static === true){
					if($[funcName] && $.isFunction($[funcName])){
						if(!this._callbacks[funcName]){
							this._callbacks[funcName] = $.Callbacks();		
						}
						this._callbacks[funcName].add(callback);
						if(!$._alias[funcName]){
							$._alias[funcName] = $[funcName];//save original class method
							
							$[funcName] = function(){//proxy class method
							var result = $._alias[funcName].apply(this, arguments);
							$._callbacks[funcName].fireWith(this, arguments);
							return result;
							};
						}						
					}
				}else{
					if($.fn[funcName] && $.isFunction($.fn[funcName])){
						if(!this._callbacks_[funcName]){
							this._callbacks_[funcName] = $.Callbacks();		
						}
						this._callbacks_[funcName].add(callback);
						if(!$._alias_[funcName]){
							$._alias_[funcName] = $.fn[funcName];//save original instance method 
							$.fn[funcName] = function(){//proxy instance method
								var result = $._alias_[funcName].apply(this, arguments);
								$._callbacks_[funcName].fireWith(this, arguments);
								return result;
							};
						}
					}
				}
			}
		},

		/** 
         * @decription 移除给方法添加的回调函数 
         * @param funcName : string 已添加回调的函数名称 
         * @param callback : function 回调函数 
         * @param static : boolean 是否是类方法，默认为false 
         */  
		removeCallback: function(funcName, callback, static){
			if("string" === typeof(funcName) && $.isFunction(callback)){
				if(static === true){
					if($[funcName] && $.isFunction($[funcName])){
						if(this._callbacks[funcName]){
							this._callbacks.remove(callback);
						}
					}
				}else{
					if($.fn[funcName] && $.isFunction($.fn[funcName])){
						if(this._callbacks_[funcName]){
							this._callbacks_.remove(callback);
						}
					}
				}
			}
		}
	});
})(jQuery);