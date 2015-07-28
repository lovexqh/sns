$(document).ready(function(){
	$('.yzylisty').click(function(){
		$(this).parent('li').children('.yzyschool').removeClass('yzylistya');
		$(this).addClass('yzylistya');
	});
});