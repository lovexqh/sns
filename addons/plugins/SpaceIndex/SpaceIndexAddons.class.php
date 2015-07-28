<?php
/**
 * 排行榜
 */
class SpaceIndexAddons extends NormalAddons
{
	protected $version = '1.0';
	protected $author  = '空间动态插件';
	protected $site    = 'www.gridinfo.com.cn';
	protected $info    = '显示空间动态里面的信息';
	protected $pluginName = '空间动态插件';
    protected $tsVersion  = "2.5";

	public function getHooksInfo()
	{
		$hooks['list'] = array('ClubListHooks','PhotoListHooks','PosterListHooks','BlogListHooks');
		//$hooks['sort'] = array('home_spaceindex_index'=>array(1,0));
		return $hooks;
		//给模版调用
		//$this->apply("home_spaceindex_index","home_spaceindex_video");
	}



	public function adminMenu()
	{

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