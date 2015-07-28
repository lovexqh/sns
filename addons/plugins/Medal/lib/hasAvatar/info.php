<?php
return array(
	'path_name'		=> 'hasAvatar',
	'title'			=> '有头有脸',
	'data'			=> serialize(array(
									'icon_url'		=> SITE_URL . '/addons/plugins/Medal/lib/hasAvatar/icon.gif',
									'big_icon_url'	=> SITE_URL . '/addons/plugins/Medal/lib/hasAvatar/big_icon.gif',
									'description'	=> '上传头像即可获得此勋章',
									'alert_message'	=> '<a href="javascript:;" onclick="OpenWindow("url","'.U('home/account/index',array('position'=>'face')).'","个人设置","","titlebutton=close|max|min,width=1035,height=600");"><span style="color:red;">上传头像</span></a>, 做有头有脸的好公民',
								)),
);