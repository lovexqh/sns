<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// 系统默认的核心列表文件
return array(
    THINK_PATH.'/Exception/ThinkException.class.php',  // 异常处理类
    THINK_PATH.'/Core/Log.class.php',    // 文章处理类
    THINK_PATH.'/Core/App.class.php',   // 应用程序类
    THINK_PATH.'/Core/Action.class.php', // 控制器类
    THINK_PATH.'/Core/Model.class.php', // 模型类
    THINK_PATH.'/Core/View.class.php',  // 视图类
	THINK_PATH.'/Db/Db.class.php',  // DB类
	THINK_PATH.'/Db/Driver/DbMysql.class.php',  // DB类
    THINK_PATH.'/Common/alias.php',  // 加载别名
);
?>