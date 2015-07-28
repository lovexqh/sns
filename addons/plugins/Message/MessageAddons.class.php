<?php
class MessageAddons extends NormalAddons
{
	protected $version = '1.0';
	protected $author  = '小波';
	protected $site    = 'www.gridinfo.com.cn';
	protected $info    = '用户消息显示';
	protected $pluginName = '消息显示';
	protected $sqlfile = 'install.sql';    // 安装时需要执行的sql文件名
	protected $tsVersion  = "2.5";                               // ts核心版本号

    /**
     * getHooksInfo
     * 获得该插件使用了哪些钩子聚合类，哪些钩子是需要进行排序的
     * @access public
     * @return void
     */
    public function getHooksInfo(){
        $hooks['list'] = array('MessageHooks');
        return $hooks;
    }

    public function adminMenu(){
        $menu = array(
					'showconfig' 	  => '前台显示配置'
				);
        return $menu;
    }

    public function start()
    {
    }

	public function install()
	{
		$db_prefix = C('DB_PREFIX');
		$sql = "DROP TABLE IF EXISTS `{$db_prefix}plugin_message`;
                CREATE TABLE `{$db_prefix}plugin_message` (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `table` varchar(50) NOT NULL COMMENT '对应的数据表',
                  `rid` bigint(20) NOT NULL COMMENT '相关的数据记录ID',
                  `isread` int(1) NOT NULL DEFAULT '0' COMMENT '是否阅读',
                  `ctime` int(11) DEFAULT NULL COMMENT '创建时间',
                  `mtime` int(11) DEFAULT NULL COMMENT '修改时间',
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

		if (false !== M()->execute($sql)) {
			return true;
		}
	    return true;
	}

	public function uninstall()
	{
		$db_prefix = C('DB_PREFIX');
		$sql = "DROP TABLE IF EXISTS `{$db_prefix}plugin_message`;";

		if (false !== M()->execute($sql)) {
			return true;
		}
	    return true;
	}
}