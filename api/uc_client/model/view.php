<?php
/*=========================================================*
 * GRIDSNS
 * 视图操作相关Model
 * @auth				xiaobo.sun@ruijie-grid.com
 * @copyright			(C) 2012-2022 GRIDSNS
 * @license				http://www.ruijie-grid.com
 * @Created				2012-8-24
 *=========================================================*/

!defined('IN_UC') && exit('Access Denied');

class viewmodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_viewmodel($base);
	}

	function _viewmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
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
	function get_teacher_class_course($id) {
		if (!empty($id) && intval($id)>0) {
			$sql = "SELECT * FROM ".UC_DBTABLEPRE."v_teacher_class_course WHERE identityid = '$id'  GROUP BY bjid";
		}else{
			return array();
		}
		$data = $this->db->fetch_all($sql);
		return $data;
	}
	
	function get_teacher_class_spacenum($id){
		if (!empty($id) && intval($id)>0) {
			$sql = "SELECT count(*) as num FROM ".UC_DBTABLEPRE."v_teacher_class_course WHERE identityid = '$id'  GROUP BY bjid";
		}else{
			return 0;
		}
		$data = $this->db->fetch_first($sql);
		return $data['num'];
	}
	
	/**
	 +----------------------------------------------------------
	 * Enter description here ... （方法功能的注释）
	 +----------------------------------------------------------
	 * @param	int identityid 老师identityid
	 * @return	array
	 * @author	小朱 2013-3-30
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-30 下午02:43:40
	 +----------------------------------------------------------
	 */
	function get_course_by_identityid($identityid){
		if (!empty($identityid) && intval($identityid)>0) {
			$sql = "SELECT * FROM ".UC_DBTABLEPRE."v_teacher_class_course WHERE identityid = '$identityid' and xkmc is not null GROUP BY xkid";
		}else{
			return array();
		}
		$data = $this->db->fetch_all($sql);
		return $data;
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
	 * 创建时间：	2013-3-15 下午04:48:26
	 +----------------------------------------------------------
	 */
	function get_classids_by_courseid($courseid,$identityid){
		if(empty($identityid) && empty($courseid) && !intval($courseid)>0 && !intval($identityid)>0 ){
			return array();
		}else{
			$sql = "SELECT * FROM ".UC_DBTABLEPRE."v_teacher_class_course WHERE identityid = '$identityid'  and xkid='$courseid'";
			$data = $this->db->fetch_all($sql);
			return $data;
		}
	}
	/**
	 +----------------------------------------------------------
	 * 通过课程id获取课程名称
	 +----------------------------------------------------------
	 * @param	int courseid 课程id
	 * @author	小朱 2013-4-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-1 上午10:30:14
	 +----------------------------------------------------------
	 */	
	function get_xkmc_by_courseid($courseid){
		if(empty($courseid) && !intval($courseid)>0){
			return array();
		}else{
			$sql = "SELECT * FROM ".UC_DBTABLEPRE."v_pjkc WHERE itemcode='$courseid'";
			$data = $this->db->fetch_first($sql);
			return $data;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取普教的学科信息
	 +----------------------------------------------------------
	 * @return unknown
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-1 下午4:13:48
	 +----------------------------------------------------------
	 */
	function get_esn_course(){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."v_pjkc order by sorder asc";
		$data = $this->db->fetch_all($sql);
		return $data;
	}
	
/**
	 +----------------------------------------------------------
	 * 通过班级id获取所学课程
	 +----------------------------------------------------------
	 * @param	int identityid 老师identityid
	 * @return	array
	 * @author	小朱 2013-3-30
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-30 下午02:43:40
	 +----------------------------------------------------------
	 */
	function get_course_by_classid($classid){
		if (!empty($classid) && intval($classid)>0) {
			$sql = "SELECT xkid,xkmc FROM ".UC_DBTABLEPRE."v_teacher_class_course WHERE bjid = '$classid' and xkmc is not null GROUP BY xkid";
		}else{
			return array();
		}
		$data = $this->db->fetch_all($sql);
		return $data;
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
	function get_related_by_identityid($identityid){
		if (!empty($identityid) && intval($identityid)>0) {
			$sql = "select teacherStaff.xm,teacherStaff.identityid,member.uid 											/*查找与某老师同年级，同课程的老师，老师必须存在于member表中。*/
					from (select DISTINCT tcc.xm,tcc.identityid from ".UC_DBTABLEPRE."v_teacher_class_course tcc,	/*查找与某老师同年级，同课程的老师，去除重复记录*/
							(	select DISTINCT tc.xm,tc.xkmc,tc.xkid,class.xd,class.jbny					/*查询老师教授的所有年级和课程，去除重复记录*/
								from ".UC_DBTABLEPRE."v_teacher_class_course as tc,".UC_DBTABLEPRE."classinfo as class 
								where tc.bjid=class.id and  tc.identityid='$identityid') as teacherCourse
					where tcc.xkid=teacherCourse.xkid and tcc.jbny=teacherCourse.jbny and tcc.identityid!='$identityid') as teacherStaff ,".UC_DBTABLEPRE."members as member
					where teacherStaff.identityid=member.identityid and member.identitytype='2' ";
		}else{
			return array();
		}
		$data = $this->db->fetch_all($sql);
		return $data;
	}
}
?>
