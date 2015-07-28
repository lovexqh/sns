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

class teacher_classmodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_teacherclassmodel($base);
	}

	function _teacherclassmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	
	/**
	 +----------------------------------------------------------
	 * 根据班级编号，获取班主任信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_class_teacher_ById($id){
		$sql = "SELECT (select xm from ".UC_DBTABLEPRE."teacherinfo i where t.identityid=i.identityid ) as bzrxm FROM ".UC_DBTABLEPRE."r_teacher_class t WHERE t.classid='$id' and t.dutyid=0";
		$data = $this->db->fetch_first($sql);
		return $data;
	}
	/**
	 +----------------------------------------------------------
	 * 根据班级编号，获取班主任注册uid
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_class_adviser_ById($id){
		$sql="select identityid from ".UC_DBTABLEPRE."r_teacher_class where classid='$id' and dutyid=0";
		$res = $this->db->fetch_first($sql);
		if($res)
		{$regsql="select uid from ".UC_DBTABLEPRE."members where identityid ='$res[identityid]' and identitytype=2";
		 $obj=$this->db->fetch_first($regsql);
		 $result=$obj['uid'];
		}
		return $result;
	}
	/**
	 +----------------------------------------------------------
	 * 根据班级编号，获取所有老师信息信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_teacher_get_id($id){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."r_teacher_class t WHERE t.classid='$id'";
		$data = $this->db->fetch_all($sql);
		$data=$this->get_teacherinfo_by_identityid($data);
		return $data;
	}
	
	/*
		根据教师用户identityid，查出详细信息
	*/
	function get_teacherinfo_by_identityid($arr)
	{
		//$array=array();
		if($arr)
		{
			foreach($arr as $k=>$obj)
			{
				//查询教师teacherinfo基本信息
				$infosql="select xm from ".UC_DBTABLEPRE."teacherinfo where identityid='$obj[identityid]'";//注册用户
				$data= $this->db->fetch_first($infosql);
				$arr[$k]['name']=$data['xm']?$data['xm']:'';
				//查询是否注册
				$sql="select uid from ".UC_DBTABLEPRE."members  where identitytype=2 and identityid='$obj[identityid]'";//注册用户
				$data= $this->db->fetch_first($sql);
				$arr[$k]['uid']=$data['uid']?$data['uid']:'';
			}
		}
		return $arr;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据identityid获取任班主任的班级列表
	 +----------------------------------------------------------
	 * @param	int identityid  身份id
	 * @return	array
	 * @author	小朱 2013-4-9
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-9 下午02:15:58
	 +----------------------------------------------------------
	 */
	function get_adviserclassids_by_identityid($identityid){
		if (!empty($identityid) && intval($identityid)>0) {
			$sql = "SELECT * FROM ".UC_DBTABLEPRE."r_teacher_class WHERE identityid = '$identityid' and dutyid=0";
		}else{
			return array();
		}
		$data = $this->db->fetch_all($sql);
		return $data;
	}
	
}
?>
