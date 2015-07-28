$(document).ready(function(){
	$('.xd a').click(function(){
		$('.xd .posjt').remove();
		$('.xd a').removeClass('yzylistya');
		$(this).parent().width(function(n,c){
			var nl = parseInt((c-15)/2);
			var divstr = '<div class="posjt" style="left:'+nl+'px;"></div>';
			$(this).append(divstr);			
		});		
		$(this).addClass('yzylistya');
		ajaxget('xd',$(this).attr('lang'));
		return false;
	});
	ajaxget('xd','21');
});

function clickf(obj,type){
	$('.'+type+' a').removeClass('yzylistya');
	$(obj).addClass('yzylistya');
	ajaxget(type,$(obj).attr('lang'));
	return false;
}

