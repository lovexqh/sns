<?php
/*=========================================================*
 * GRIDSNS
 *
 * @auth				xiaobo.sun@ruijie-grid.com
 * @copyright			(C) 2012-2022 GRIDSNS
 * @license				http://www.ruijie-grid.com
 * @Created				2012-8-24
 *=========================================================*/

!defined('IN_UC') && exit('Access Denied');

class deptmodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_deptmodel($base);
	}

	function _deptmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function get_dept_list($childid,$parentid,$page=0,$size=0,$totalnum=0,$sqladd='') {
		$start = $this->base->page_get_start($page, $size, $totalnum);
		if($page!=$size&&$totalnum!=0)
			$limit = "LIMIT $start, $size";
		if($childid)
			$where .= "AND DeptLevel='$childid' ";
		if($parentid)
			$where .= "AND UpDeptID=$parentid";

		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE 1 $where $sqladd $limit");
		return $data;
	}

	function get_dept_by_id($id){
		$data = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE DeptID = $id");
		$parent_data = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE DeptID = '".$data['UpDeptID']."'");
		$data['ParentDeptName'] = $parent_data['DepartName'];
		return $data;
	}
	
	function get_dept_by_manager($deptid){
		$data = $this->db->fetch_first("SELECT DeptManager FROM ".UC_DBTABLEPRE."dept WHERE DeptID = '$deptid'");
		return $data;
	}

	function get_parent_dept_by_id($id){
		$data = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE DeptID = (SELECT UpDeptID FROM ".UC_DBTABLEPRE."dept WHERE DeptID = $id)");
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据用户ID获取老师用户所在部门的信息
	 +----------------------------------------------------------
	 * @param intval $uid 用户ID
	 * @param string $fields 所要查询的数据
	 * @return array 结果数据
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午1:43:38
	 +----------------------------------------------------------
	 */
	function get_departinfo_by_uid($uid,$fields = '*'){
		$sql ="SELECT $fields FROM ".UC_DBTABLEPRE."dept WHERE DeptID = (SELECT depid FROM ".UC_DBTABLEPRE."teacherinfo WHERE identityid = (SELECT identityid FROM ".UC_DBTABLEPRE."members WHERE uid = $uid))";
		$data = $this->db->fetch_first($sql);
		return $data;
	}
}
?>
