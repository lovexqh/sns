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

class classinfomodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_classinfomodel($base);
	}

	function _classinfomodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	//根据id查处班级基本信息
	function get_classinfo_byId(&$classid) {
		$data = $this->db->fetch_first("SELECT *,(SELECT xxmc from ".UC_DBTABLEPRE."schoolinfo as s where c.xxid=s.id) as xxmc FROM ".UC_DBTABLEPRE."classinfo as c WHERE c.id='$classid'");
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学校ID获取班级列表
	 +----------------------------------------------------------
	 * @param intval $sid 学校ID
	 * @return array 班级列表
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-14 下午1:35:15
	 +----------------------------------------------------------
	 */
	function get_classlist_by_sid($sid,$nj,$xd,$fields='*'){
		if ($xd==0 && intval($nj)==0){
			$sql = "SELECT $fields FROM ".UC_DBTABLEPRE."classinfo WHERE xxid = '$sid'";
			$data = $this->db->fetch_all($sql);
			return $data;
		}else 
			return $this->getClasses($xd,$nj,$sid);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据用户ID获取用户所在的班级信息
	 +----------------------------------------------------------
	 * @param intval $uid 用户ID
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午1:25:47
	 +----------------------------------------------------------
	 */
	function get_classinfo_by_uid($uid,$fields='*'){
		$uid = intval($uid);
		$sql = "SELECT $fields FROM ".UC_DBTABLEPRE."classinfo WHERE id = 
				(SELECT bjid FROM ".UC_DBTABLEPRE."studentinfo WHERE identityid = 
				(SELECT identityid FROM ".UC_DBTABLEPRE."members WHERE uid = $uid))";
		$data = $this->db->fetch_first($sql);
		return $data;
	}
	
	/**
	 * 获取某个学段下，某个年级下的所有班级
	 * $xd ：学段 21：小学； 31：初中；	34:高中
	 * $nji：年级 顺序号，1：1年级；2:2年级
	 * $schoolid：学校ID
	 */
	public function getClasses($xd,$nji,$schoolid){
		$condition=$this->genClzCondition($xd,$nji,$schoolid);
		$sql="SELECT * FROM ".UC_DBTABLEPRE."classinfo".$condition." ORDER BY bh";
		$classes = $this->db->fetch_all($sql);
		return $classes;
	}
	
	private function genClzCondition($xd='',$nji='',$schoolid=''){
		$tmonth=date('n');	//当前月份
		$tyear=date('Y');	//当前年份
	
		$upGrade=0;
		if($tmonth<9){//当前月份小于9月,则年级还没有升级,各个年级的级别需要年度额外-1,如2013年9月以前,高一为2013-1级,高二为(2013-1)-1,高三为(2013-2)-1
			$upGrade=-1;
		}
	
		$grade=$tyear+$upGrade-($nji-1);//2012年入学,高一为2012级,高二为2012-(2-1)级,高三为2012-(3-1)级
	
		if (intval($xd)>0) {
			$condition=" WHERE xd = '$xd'";
		}else{
			$condition=" WHERE 1=1";
		}
		$condition.=" AND jbny like '$grade%'";
		$condition.=" AND xxid = '$schoolid'";
		return $condition;
	}
}
?>
