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

class teachermodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_teachermodel($base);
	}

	function _teachermodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	/**
	 +----------------------------------------------------------
	 * 根据基础数据中的老师身份证号获取老师的基础信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param
	 +----------------------------------------------------------
	 * @return
	 +----------------------------------------------------------
	 */
	function get_userinfo_by_userno($userno){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."teacherinfo WHERE sfzjh = '$userno'";
		$data = $this->db->fetch_first($sql);
		return $data;
	}

	/**
	 +----------------------------------------------------------
	 * 根据基础数据中的ID号获取学生的基础信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param
	 +----------------------------------------------------------
	 * @return
	 +----------------------------------------------------------
	 */
	function get_teacher_by_id($id){
		$sql = "SELECT t.*,d.DepartName as deptname FROM ".UC_DBTABLEPRE."teacherinfo t LEFT JOIN ".UC_DBTABLEPRE."dept d ON t.depid=d.DeptID  WHERE identityid = '$id'";
		//echo $sql;
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
		$sql = "SELECT t.*,d.DepartName as deptname,s.xxmc as schoolname FROM ".UC_DBTABLEPRE."teacherinfo t LEFT JOIN ".UC_DBTABLEPRE."dept d ON t.depid=d.DeptID LEFT JOIN ".UC_DBTABLEPRE."schoolinfo as s ON t.schoolid=s.id WHERE t.identityid = (SELECT identityid FROM ".UC_DBTABLEPRE."members WHERE uid = '$uid')";
		$data = $this->db->fetch_first($sql);
		return $data;
	}
	/**
	 +----------------------------------------------------------
	 * 根据老师的uid获取老师所在学校的所有部门
	 +----------------------------------------------------------
	 * @param integer $uid
	 * @return array
	 * @author 小伟 2013-3-19
	 +----------------------------------------------------------
	 */
	function get_depts_by_uid($uid){
		//var_dump($uid);
		//uid其实就是用来获取该老师所在的学校的schoolId的
		$sql2	=	"select identityid from ".UC_DBTABLEPRE."members where `uid` = '".$uid."'";
		$result2	=	$this->db->fetch_first($sql2);
		$result2	=	arrayKeyTolower($result2);
		$sql3	=	"select schoolid from ".UC_DBTABLEPRE."teacherinfo where `identityid`='".$result2['identityid']."'";
		$result3	=	$this->db->fetch_first($sql3);
		$schoolId	=	$result3['schoolid'];
		$schoolWhere	=	 " AND schoolid = '".$schoolId."'";
		$sql = "SELECT DeptID,DepartName as title,UpDeptID,schoolid FROM ".UC_DBTABLEPRE."dept WHERE  DeptID > 0 ".$schoolWhere." order by UpDeptID ASC,DeptID ASC";
		$data	=	$this->db->fetch_all($sql);
		return $data;
	}
	/**
	 +----------------------------------------------------------
	 * 根据部门ID获取该部门下的所有老师
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-3-22
	 +----------------------------------------------------------
	 */
	function get_teachers_by_deptid($deptId,$schoolId){
		//$sql =	"select m.username,m.email from ".UC_DBTABLEPRE."members as m where identityID in (SELECT t.identityid FROM ".UC_DBTABLEPRE."teacherinfo as t WHERE depid = ".$deptId."  AND schoolid = ".$schoolId." )";
		$sql =	"SELECT t.identityid,t.xm FROM ".UC_DBTABLEPRE."teacherinfo as t WHERE depid = ".$deptId."  AND schoolid = ".$schoolId;
		$data	=	$this->db->fetch_all($sql);
		return $data;		
	}
	
}
?>
