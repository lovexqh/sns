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

/**
 +------------------------------------------------------------------------------
 * 文章处理类
 +------------------------------------------------------------------------------
 * @category   Think
 * @package  Think
 * @subpackage  Core
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class Log extends Think
{//类定义开始

    // 文章级别 从上到下，由低到高
    const EMERG   = 'EMERG';  // 严重错误: 导致系统崩溃无法使用
    const ALERT    = 'ALERT';  // 警戒性错误: 必须被立即修改的错误
    const CRIT      = 'CRIT';  // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
    const ERR       = 'ERR';  // 一般错误: 一般性错误
    const WARN    = 'WARN';  // 警告性错误: 需要发出警告的错误
    const NOTICE  = 'NOTIC';  // 通知: 程序可以运行但是还不够完美的错误
    const INFO     = 'INFO';  // 信息: 程序输出信息
    const DEBUG   = 'DEBUG';  // 调试: 调试信息
    const SQL       = 'SQL';  // SQL：SQL语句 注意只在调试模式开启时有效

    // 文章记录方式
    const SYSTEM = 0;
    const MAIL      = 1;
    const TCP       = 2;
    const FILE       = 3;

    // 文章信息
    static $log =   array();

    // 日期格式
    static $format =  '[ c ]';

    /**
     +----------------------------------------------------------
     * 记录文章 并且会过滤未经设置的级别
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $message 文章信息
     * @param string $level  文章级别
     * @param boolean $record  是否强制记录
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    static function record($message,$level=self::ERR,$record=false) {
        if($record || in_array($level,C('LOG_RECORD_LEVEL'))) {
            $now = date(self::$format);
            self::$log[] =   "{$now} {$level}: {$message}\r\n";
        }
    }

    /**
     +----------------------------------------------------------
     * 文章保存
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param integer $type 文章记录方式
     * @param string $destination  写入目标
     * @param string $extra 额外参数
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    static function save($type=self::FILE,$destination='',$extra='')
    {
        if(empty($destination))
            $destination = LOG_PATH.date('y_m_d').".log";
        if(self::FILE == $type) { // 文件方式记录文章信息
            //检测文章文件大小，超过配置大小则备份文章文件重新生成
            if(is_file($destination) && floor(C('LOG_FILE_SIZE')) <= filesize($destination) )
                  rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
        }
        error_log(implode("",self::$log), $type,$destination ,$extra);
        // 保存后清空文章缓存
        self::$log = array();
        //clearstatcache();
    }

    /**
     +----------------------------------------------------------
     * 文章直接写入
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $message 文章信息
     * @param string $level  文章级别
     * @param integer $type 文章记录方式
     * @param string $destination  写入目标
     * @param string $extra 额外参数
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    static function write($message,$level=self::ERR,$type=self::FILE,$destination='',$extra='')
    {
        $now = date(self::$format);
        if(empty($destination))
            $destination = LOG_PATH.date('y_m_d').".log";
        if(self::FILE == $type) { // 文件方式记录文章
            //检测文章文件大小，超过配置大小则备份文章文件重新生成
            if(is_file($destination) && floor(C('LOG_FILE_SIZE')) <= filesize($destination) )
                  rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
        }
        error_log("{$now} {$level}: {$message}\r\n", $type,$destination,$extra );
        //clearstatcache();
    }

}//类定义结束
?>