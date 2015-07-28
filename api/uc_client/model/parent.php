<?php
/*=========================================================*
 * GRIDSNS
 *
 * @auth				xiaobo.sun@ruijie-grid.com
 * @copyright			(C) 2012-2022 GRIDSNS
 * @license				http://www.ruijie-grid.com
 * @Created				2012-12-29
 *=========================================================*/

!defined('IN_UC') && exit('Access Denied');

class parentmodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_parentmodel($base);
	}

	function _parentmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	/**
	 +----------------------------------------------------------
	 * 根据基础数据中的学生学号获取学生的基础信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_userinfo_by_userno($userno,$name){
		if (empty($name)) return null;
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE xm = '$name' AND familyid = (SELECT familyid FROM ".UC_DBTABLEPRE."studentinfo WHERE xh = '$userno')";
		$data = $this->db->fetch_first($sql);
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据基础数据中的学生学号获取学生的基础信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_studentinfo_by_familyid($familyid){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE familyid = '$familyid'";
		$data = $this->db->fetch_all($sql);
		return $data;
	}
	
	function get_parentinfo_by_familyid($familyid){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE familyid = '$familyid'";
		$data = $this->db->fetch_all($sql);
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据基础数据中的ID号获取家长的基础信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_parent_by_id($id){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE identityid = '$id'";
		$data = $this->db->fetch_first($sql);
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据用户ID获取用户相关部门信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_deptinfo_by_uid($uid){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE identityid = (SELECT identityid FROM ".UC_DBTABLEPRE."members WHERE uid = '$uid')";
		$data = $this->db->fetch_first($sql);
		$sql = "SELECT u.*,c.bj as bj,s.xxmc as schoolname FROM ".UC_DBTABLEPRE."studentinfo u LEFT JOIN ".UC_DBTABLEPRE."classinfo c ON u.classid=c.id LEFT JOIN ".UC_DBTABLEPRE."schoolinfo as s ON u.schoolid=s.id WHERE u.identityid = (SELECT identityid FROM ".UC_DBTABLEPRE."members WHERE uid = '$uid')";
		$_student = $this->db->fetch_first($sql);
		$data['sonname'] = $_student['xm'];
		$data['schoolname'] = $_student['schoolname'];
		$data['classname'] = $_student['bj'];
		return $data;
	}

}
?>
