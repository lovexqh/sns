var pb_strConfirmCloseMessage;
var pb_blnCloseWindow = false;
pb_strConfirmCloseMessage ="如果您已经上传资源，离开本页，上传的资源会丢失！";

ShowConfirmClose(true);

function ConfirmClose() {
	window.event.returnValue = pb_strConfirmCloseMessage;
	pb_blnCloseWindow = true;
}
 
function ShowConfirmClose(blnValue) {
	if(blnValue) {
		document.body.onbeforeunload = ConfirmClose;
	} else {
		document.body.onbeforeunload = null;
	}
}