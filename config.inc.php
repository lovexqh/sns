<?php
if (!defined('SITE_PATH')) exit();
return array(
    // 数据库常用配置
    'DB_TYPE'			=>	'mysql',			// 数据库类型
    'DB_HOST'			=>	'127.0.0.1',			// 数据库服务器地址
//     'DB_HOST'			=>	'10.1.5.123',			// 数据库服务器地址
    'DB_NAME'			=>	'dalian_dsk_db',			// 数据库名
    'DB_USER'			=>	'root',		// 数据库用户名
    'DB_PWD'			=>	'root',		// 数据库密码
    'DB_PORT'			=>	3306,				// 数据库端口
    'DB_PREFIX'			=>	'ts_',		// 数据库表前缀（因为漫游的原因，数据库表前缀必须写在本文件）
    'DB_CHARSET'		=>	'utf8',				// 数据库编码
    'DB_FIELDS_CACHE'	=>	true,				// 启用字段缓存

    'COOKIE_DOMAIN'	=>	'.127.0.0.1',	//cookie域,请替换成你自己的域名 以.开头

    //Cookie加密密码
    'SECURE_CODE'       =>  'SECURE18785',

    // 默认应用
    'DEFAULT_APPS'		=> array('api', 'admin','comment', 'home','space', 'square', 'myop', 'weibo', 'wap', 'w3g','blog','photo','poster','event','vote','gift','timeline','desktop','campus','club','society','file'),

    // 是否开启URL Rewrite
    'URL_ROUTER_ON'		=> false,
		//memcache配置
// 		'DATA_CACHE_TYPE' => 'Memcache', 
// 		'MEMCACHE_HOST'   =>  '10.3.200.115:11211',
		'MEMCACHE_HOST'   =>  '127.0.0.1:11211',
	  'DATA_CACHE_TIME' => '3600',

    'SHOW_PAGE_TRACE' =>true,
    // 是否开启调试模式 (开启AllInOne模式时该配置无效, 将自动置为false)
    'APP_DEBUG'			=> true,
    'LOG_RECORD' => true,
    'SHOW_ADV_TIME'=>true,
    'SHOW_DB_TIMES'=>true,
    'SHOW_CACHE_TIMES'=>true,
    'LOG_RECORD_LEVEL' => array('INFO','SQL','EMERG','ALERT','CRIT','ERR'),
	'NOT_DESKTOP_THEME'	=> array('home', 'desktop', 'admin', 'timeline'),
		
);


