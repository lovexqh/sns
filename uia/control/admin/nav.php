<?php
session_start();
/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

class control extends adminbase
{
	function __construct()
	{
		$this->control();
	}
	function control()
	{
		parent::__construct();		
	}
	
	function onls(){
	
		$power = $this->user['power'];
		$nav = $pid = array();
		foreach ($power as $key=>$val){
			$temp = array();
			$temp['id'] = $val['aid'];
			$temp['text'] = $val['appAlias'];
			$temp['appname']=$val['appName'];
			if($val['pid']){
				$temp['pid'] = $val['pid'];
			}
			array_push($nav, $temp);
		}
		print_r(json_encode($nav));
	}
	
}
?>