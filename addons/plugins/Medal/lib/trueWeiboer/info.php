<?php
return array(
	'path_name'		=> 'trueWeiboer',
	'title'			=> '微广播达人',
	'data'			=> serialize(array(
									'icon_url'		=> SITE_URL . '/addons/plugins/Medal/lib/trueWeiboer/icon.gif',
									'big_icon_url'	=> SITE_URL . '/addons/plugins/Medal/lib/trueWeiboer/big_icon.gif',
									'description'	=> '连续3天发微广播,即可获得这枚勋章(每天都要发原创才有效噢)',
									'alert_message'	=> '你是微广播控么? <a href="'.U('home/Account/medal',array('addon'=>'Medal','hook'=>'home_account_show')).'">Show Me!</a>',
								)),
        );
