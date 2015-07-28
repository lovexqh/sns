<?php
/*=========================================================*
 * GRIDSNS
 * 视图操作相关Control
 * @auth				xiaobo.sun@ruijie-grid.com
 * @copyright			(C) 2012-2022 GRIDSNS
 * @license				http://www.ruijie-grid.com
 * @Created				2012-8-24
 *=========================================================*/
!defined('IN_UC') && exit('Access Denied');

class viewcontrol extends base {

	function __construct() {
		$this->_viewcontrol();
	}

	function _viewcontrol() {
		parent::__construct();
		$this->load('view');
	}

	/**
	 +----------------------------------------------------------
	 * 获取某老师相关的任课，班级，学校等信息
	 +----------------------------------------------------------
	 * @param unknown $id
	 * @return multitype:|unknown
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-26 上午10:07:06
	 +----------------------------------------------------------
	 */
	function onget_teacher_class_course() {
 		$this->init_input();
 		$id = $this->input('id');
		$result = $_ENV['view']->get_teacher_class_course($id);

 		return $result;
	}
	
	function onget_teacher_class_spacenum(){
		$this->init_input();
		$id = $this->input('id');
		$result = $_ENV['view']->get_teacher_class_spacenum($id);
		
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据教师identityid获取所带课程（去重复列）
	 +----------------------------------------------------------
	 * @param	int identityid 老师identityid
	 * @return	array
	 * @author	小朱 2013-3-30
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-30 下午02:41:49
	 +----------------------------------------------------------
	 */
	function onget_course_by_identityid(){
		$this->init_input();
 		$identityid = $this->input('identityid');
		$result = $_ENV['view']->get_course_by_identityid($identityid);
 		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 发布作业时，根据subject课程不同动态获取所带班级
	 +----------------------------------------------------------
	 * @param	int $subject 学科id
	 * @param	int $identityid 用户身份id
	 * @return	array data 班级列表
	 * @author	小朱 2013-3-15
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-15 下午04:44:28
	 +----------------------------------------------------------
	 */
	function onget_classids_by_courseid(){
		$this->init_input();
		$courseid = $this->input('courseid');
		$identityid = $this->input('identityid');
		if (intval($courseid) && intval($identityid)){
			$data = $_ENV['view']->get_classids_by_courseid($courseid,$identityid);
			return $data;
		}
		else
			return null;
	}
	
	/**
	 +----------------------------------------------------------
	 * 通过课程id获取课程名称
	 +----------------------------------------------------------
	 * @param	int courseid 课程id
	 * @return	string xkmc 课程名称
	 * @author	小朱 2013-4-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-1 上午10:30:14
	 +----------------------------------------------------------
	 */
	function onget_xkmc_by_courseid(){
		$this->init_input();
		$courseid = $this->input('courseid');
		if (intval($courseid)){
			$data = $_ENV['view']->get_xkmc_by_courseid($courseid);
			return $data;
		}
		else
			return null;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取普教的科目列表
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-1 下午4:12:59
	 +----------------------------------------------------------
	 */
	function onget_esn_course(){
		return $_ENV['view']->get_esn_course();
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据班级id获取班级所学课程
	 +----------------------------------------------------------
	 * @param	int classid 班级classid
	 * @return	array
	 * @author	小朱 2013-3-30
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-30 下午02:41:49
	 +----------------------------------------------------------
	 */
	function onget_course_by_classid(){
		$this->init_input();
 		$classid = $this->input('classid');
		$result = $_ENV['view']->get_course_by_classid($classid);
 		return $result;
	}
	/**
 	+----------------------------------------------------------
 	* 通过identityid获取已经注册的老师信息
 	+----------------------------------------------------------
 	* @param	int $identityid 教师identityid
 	* @return	return_type <返回类型(void的方法就不用该选项)>
 	* @author	小朱 2013-4-7
 	+----------------------------------------------------------
	 * 创建时间：	2013-4-7 下午06:26:16
	 +----------------------------------------------------------
 	*/
	function onget_related_by_identityid(){
		$this->init_input();
 		$identityid = $this->input('identityid');
		$result = $_ENV['view']->get_related_by_identityid($identityid);
 		return $result;
	}
}
?>
