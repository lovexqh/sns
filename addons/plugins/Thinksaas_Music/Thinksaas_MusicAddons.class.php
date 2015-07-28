<?php
class Thinksaas_MusicAddons extends NormalAddons
{
	protected $version = "1.0";
	protected $author  = "网格插件";
	protected $site    = "www.gridinfo.com.cn";
	protected $info    = "虾米搜索，取自Thinksaas开源编码，可移植2.0-2.5任意版本";
    protected $pluginName = "音乐扩展";
    protected $tsVersion = '2.5';

    public function getHooksInfo(){
        $hooks['list'] = array('Thinksaas_MusicHooks');
        return $hooks;
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
