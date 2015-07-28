<?php

class MeituAddons extends NormalAddons
{
	protected $version = "1.0";
	protected $author = "网格插件";
	protected $site = "www.gridinfo.com.cn";
	protected $info = "拼接图片，美图秀秀";
	protected $pluginName = "美图秀秀";
	protected $tsVersion = '2.5';

	public function getHooksInfo()
	{ 
		// ,'VideoHooks','MusicHooks','FileHooks'
		$hooks['list'] = array('MeituHooks');
		return $hooks;
	}
	/**
	 * 该插件的管理界面的处理逻辑。
如果return false,则该插件没有管理界面。
这个接口的主要作用是，该插件在管理界面时的初始化处理
	 * 
	 * @param string $page 
	 */
	public function adminMenu()
	{
		return array('config' => "全局设置");
	}

	public function start()
	{
		return true;
	}

	public function install()
	{
		return true;
	}

	public function uninstall()
	{
		return true;
	}
}
