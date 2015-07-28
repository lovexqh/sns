<?php
/**
 * 微广播小工具
 * @author _慢节奏
 *
 */
class ToolAction extends Action
{
	public function webpageComment()
    {
		$this->display();
	}

	public function bulkFollow()
	{
		$this->display();
	}

	/***
	 * APP创建一个新分类
	 */
	public function create_class_tab(){
		$classid = intval($_GET['cid']);
		$map['class_id'] = $classid;
		$parentinfo = D('app_class')->where($map)->find();
		if(!empty($parentinfo)){
			$this->assign('parentinfo',$parentinfo);
			$this->display();
		}
	}
	public function do_create_class(){
		echo 1;
	}
}
