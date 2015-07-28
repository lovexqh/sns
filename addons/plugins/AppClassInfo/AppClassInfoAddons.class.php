<?php
class AppClassInfoAddons extends NormalAddons
{
	protected $version = '1.0';
	protected $author  = '孙晓波';
	protected $site    = 'http://www.zzstudio.net';
	protected $info    = '应用分类信息';
	protected $pluginName = '应用分类';
	protected $tsVersion  = "2.5";         // ts核心版本号

    /**
     * getHooksInfo
     * 获得该插件使用了哪些钩子聚合类，哪些钩子是需要进行排序的
     * @access public
     * @return void
     */
    public function getHooksInfo(){
        $hooks['list'] = array('AppClassInfoHooks');
        return $hooks;
    }

    public function adminMenu(){
        $menu = array(
					'classInfo'	=> '分类信息',
					'addClass'	=> '添加分类'
				);
        return $menu;
    }

    public function start()
    {

    }

	public function install()
	{
		$db_prefix = C('DB_PREFIX');
		$sql = array(
			"CREATE TABLE IF NOT EXISTS `{$db_prefix}app_class` (
			  `class_id` int(11) NOT NULL auto_increment,
			  `class_name` varchar(255) NOT NULL,
			  `app_id` int(11) NOT NULL,
			  `uid` int(11) NOT NULL,
			  `parentid` int(11) NOT NULL default 0,
			  `description` text,
			  `display_order` smallint(5) NOT NULL default '0',
			  `status` smallint(5) not null default 0 comment '是否为系统分类,1:是，0：否',
			  `ctime` int(11) default NULL,
			  `mtime` int(11) default NULL,
			  PRIMARY KEY  (`class_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
		);

		foreach ($sql as $v)
			$res = M()->execute($v);
		if (false !== $res) {
			return true;
		}
		return true;
	}

	public function uninstall()
	{
		$db_prefix = C('DB_PREFIX');
		$sql = array(
			"DROP TABLE IF EXISTS `{$db_prefix}app_class`;"
		);

		foreach ($sql as $v)
			$res = M()->execute($v);

		if (false !== $res) {
			return true;
		}
	}
}