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

class schoolmodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_studentmodel($base);
	}

	function _studentmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	/**
	 +----------------------------------------------------------
	 * 获取社区班级列表
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_school_list($type=0,$fields = '*')
	{
		if (empty($fields)) {
			$fields = '*';
		}
		switch (intval($type)) {
			case 1:
				$where = "WHERE FIND_IN_SET('21',xxbxlxm)";
				break;
			case 2:
				$where = "WHERE FIND_IN_SET('31',xxbxlxm)";
				break;
			case 3:
				$where = "WHERE FIND_IN_SET('34',xxbxlxm)";
				break;
			default:
				$where = "WHERE FIND_IN_SET($type,xxbxlxm)";
		}
		$sql="SELECT $fields FROM ".UC_DBTABLEPRE."schoolinfo $where";
		$return= $this->db->fetch_all($sql);
		return $return;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据用户ID获取学校名称
	 +----------------------------------------------------------
	 * @param intval $uid 用户ID
	 * @return string 学校名称
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 上午10:10:27
	 +----------------------------------------------------------
	 */
	function get_school_info($uid){
		$uid = intval($uid);
		$sql="SELECT identityid,identitytype FROM ".UC_DBTABLEPRE."members WHERE uid = $uid";
		$member = $this->db->fetch_first($sql);
		$identitytype = $member['identitytype'];
		$identityid = $member['identityid'];
		$sql = "";
		switch ($identitytype){
			case "2": 	//老师
				$sql = "SELECT id,xxmc FROM ".UC_DBTABLEPRE."schoolinfo WHERE id LIKE (SELECT schoolid FROM ".UC_DBTABLEPRE."teacherinfo WHERE identityid = $identityid)";
				break;
			case "3": 	//学生
				$sql = "SELECT id,xxmc FROM ".UC_DBTABLEPRE."schoolinfo WHERE id LIKE (SELECT schoolid FROM ".UC_DBTABLEPRE."studentinfo WHERE identityid = $identityid)";
				break;
			case "4": 	//家长
				$sql = "SELECT id,xxmc FROM ".UC_DBTABLEPRE."schoolinfo WHERE id LIKE (SELECT schoolid FROM ".UC_DBTABLEPRE."studentinfo WHERE familyid = (SELECT familyid FROM ".UC_DBTABLEPRE."parentinfo WHERE identityid = $identityid))";
				break;
			default:
		}
		if(!empty($sql)){
			$result = $this->db->fetch_first($sql);
			return $result;
		}
	}
}
?>
