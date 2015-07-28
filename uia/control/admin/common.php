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
		$nav = array(
				array('id'=>'index','text'=>'首页'),
				array('id'=>'user','text'=>'用户管理'),
				array('id'=>'schmanager','text'=>'学校设置'),
				array('id'=>'teacher','text'=>'老师管理'),
				array('id'=>'student','text'=>'学生管理'),
				array('id'=>'system','text'=>'系统管理'),
				array('id'=>'dept','text'=>'部门管理','pid'=>'schmanager'),
				array('id'=>'school','text'=>'学校管理','pid'=>'schmanager'),
				array('id'=>'college','text'=>'院系管理','pid'=>'schmanager'),
				array('id'=>'specialty','text'=>'专业管理','pid'=>'schmanager'),
				array('id'=>'class','text'=>'班级管理','pid'=>'schmanager'),
				array('id'=>'teachbuliding','text'=>'教学楼管理','pid'=>'schmanager'),
				array('id'=>'classroom','text'=>'教室管理','pid'=>'schmanager'),
		
				array('id'=>'identity','text'=>'身份管理','pid'=>'system'),
				array('id'=>'role','text'=>'角色管理','pid'=>'system'),
				array('id'=>'application','text'=>'应用管理','pid'=>'system'),
				array('id'=>'sclmanager','text'=>'管理员管理','pid'=>'system'),
				array('id'=>'db','text'=>'数据备份','pid'=>'system'),
				array('id'=>'cache','text'=>'更新缓存','pid'=>'system'),
				array('id'=>'logout','text'=>'更新缓存')
		);
		
		print_r(json_encode($nav));
	}
	
}
?>