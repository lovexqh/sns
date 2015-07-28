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

class grademodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_grademodel($base);
	}

	function _grademodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	/**
	 +----------------------------------------------------------
	 * 获取年级列表
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_grade_list($sid,$xd=0)
	{
		if ($xd>0) {
			$where = " AND jd = '".$xd."'";
		}
		$sql="SELECT * FROM ".UC_DBTABLEPRE."gradeinfo WHERE xxid = '$sid' $where ORDER BY jd,nj";
		$return= $this->db->fetch_all($sql);
		return $return;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学段ID获取年级信息
	 +----------------------------------------------------------
	 * @param unknown $xdid
	 * @return unknown
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-1 下午4:49:20
	 +----------------------------------------------------------
	 */
	function get_grades_by_xd($xdid){
		if ( isset($xdid) && intval($xdid) > 0 ) {
			$sql = "SELECT * FROM ".UC_DBTABLEPRE."gradeinfo WHERE xxid IN 
				(SELECT id FROM ".UC_DBTABLEPRE."schoolinfo WHERE FIND_IN_SET('".$xdid."',xxbxlxm)) and jd = '".$xdid."' GROUP BY njmc ORDER BY nj";
		}else{
			$sql = "SELECT * FROM ".UC_DBTABLEPRE."gradeinfo GROUP BY jd,njmc ORDER BY jd,nj";
		}
		$return= $this->db->fetch_all($sql);
		return $return;
	}

}
?>
