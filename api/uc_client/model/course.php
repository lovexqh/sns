<?php
/**
 * @deprecated  course课程表Model模块
 * @author Ricker lhyfe@sohu.com
 * @version 1.0
 * 
 */
!defined('IN_UC') && exit('Access Denied');

class coursemodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_coursemodel($base);
	}

	function _coursemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	/**
	 * 
	* @Title: get_course_by_uid
	* @Description: 根据学生UID返回course表和courseselected表级联的记录集
	* @param @param 学生号 $uid
	* @return 
	* @author Ricker lhyfe@sohu.com
	 */
	function get_course_by_uid($memberid){
		$type = $this->get_uid_type($memberid);
		if($type == 3){
			$sql = "SELECT xsid,kcm,jxlm,jsm,jsxm,kcxz,xf,skxq,skjc,xszk,zxs,dsz FROM ".UC_DBTABLEPRE."course a,".UC_DBTABLEPRE."courseselected b WHERE a.id = b.kcid AND b.xsid = (SELECT identityID FROM ".UC_DBTABLEPRE."members WHERE uid = '$memberid') ";
		}else{
			$sql = "SELECT kcm,jxlm,jsm,jsxm,kcxz,xf,skxq,skjc,xszk,zxs,dsz,a.jsh,a.jsxm FROM ".UC_DBTABLEPRE."course a,".UC_DBTABLEPRE."teacherinfo b WHERE a.jsh = b.jsh AND b.identityid = (SELECT identityID FROM ".UC_DBTABLEPRE."members WHERE uid = '$memberid') ";
		}
		return $this->db->fetch_all($sql);
		
	}
	
	function get_uid_type($memberid){
		$sql = "SELECT identityType FROM ".UC_DBTABLEPRE."members WHERE uid = '$memberid'";
		$result = $this->db->fetch_first($sql);
		return $result['identityType'];
	}
	
}
?>
