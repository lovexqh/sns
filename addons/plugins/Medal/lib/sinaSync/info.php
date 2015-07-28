<?php
return array(
	'path_name'		=> 'sinaSync',
	'title'			=> '新浪达人',
	'data'			=> serialize(array(
									'icon_url'		=> SITE_URL . '/addons/plugins/Medal/lib/sinaSync/icon.gif',
									'big_icon_url'	=> SITE_URL . '/addons/plugins/Medal/lib/sinaSync/big_icon.gif',
									'description'	=> '绑定新浪微广播即可获得此勋章',
									'alert_message'	=> '<a href="'.U('home/Account/bind').'#sina">绑定</a> 新浪微广播, 做新浪达人',
								)),
);