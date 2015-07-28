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

class studentmodel {

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
	 * 根据基础数据中的学生学号获取学生的基础信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_userinfo_by_userno($userno){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE xh = '$userno'";
		$data = $this->db->fetch_first($sql);
		
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据基础数据中的家庭号获取学生的基础信息
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
	function get_student_by_id($id){
		$sql = "SELECT stu.*,c.id as bjid,c.bm as bjmc,s.xxmc,sub.yxmc FROM ".UC_DBTABLEPRE."studentinfo as stu INNER JOIN ".UC_DBTABLEPRE."classinfo as c ON stu.bjid = c.id LEFT JOIN ".UC_DBTABLEPRE."schoolinfo as s on stu.schoolid = s.id LEFT JOIN ".UC_DBTABLEPRE."academyinfo sub ON stu.yxid = sub.id WHERE stu.identityid = '$id'";
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
		$sql = "SELECT u.*,c.*,s.xxmc as schoolname FROM ".UC_DBTABLEPRE."studentinfo u LEFT JOIN ".UC_DBTABLEPRE."classinfo c ON u.bjid=c.id LEFT JOIN ".UC_DBTABLEPRE."schoolinfo as s ON u.schoolid=s.id WHERE u.identityid = (SELECT identityid FROM ".UC_DBTABLEPRE."members WHERE uid = '$uid')";
		$data = $this->db->fetch_first($sql);
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据班级id获取班级男女人数
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_studentcount_ById($classid) {
		$sql="select count(*) as count,sum(case xbm when 1 then 1 else 0 end ) as mancount ,sum(case xbm when 2 then 1 else 0 end) as womencount from ".UC_DBTABLEPRE."studentinfo where classid='$classid'";
		$result = $this->db->fetch_first($sql);
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据班级classid获取班级成员信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_student_ById($classid) {
		$sql="select xm,identityid from ".UC_DBTABLEPRE."studentinfo where classid='$classid'";
		$result = $this->db->fetch_all($sql);
		foreach($result as $k=>$v)
		{
			$regsql="select uid from ".UC_DBTABLEPRE."members where identityID ='$v[identityid]' and identityType=3";
			$obj=$this->db->fetch_first($regsql);
			$result[$k]['uid']=$obj['uid'];
		}
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据基础数据中的班级学生姓名获取的基础信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_student_By_IdandName($classid,$name){
		$sql="select xm,identityid from ".UC_DBTABLEPRE."studentinfo where classid='$classid' and xm like '$name%'";
		$result = $this->db->fetch_all($sql);
		foreach($result as $k=>$v)
		{
			$regsql="select uid from ".UC_DBTABLEPRE."members where identityID ='$v[identityid]' and identityType=3";
			$obj=$this->db->fetch_first($regsql);
			$result[$k]['uid']=$obj['uid'];
		}
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据班级classid获取班级未安排座位学生信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_noseat_student_ById($classid,$identityids) {
		$sql="select xm,identityid from ".UC_DBTABLEPRE."studentinfo where classid='$classid'";
		if(!empty($identityids))
		{$sql.=" and identityid not in($identityids)";}
		$result = $this->db->fetch_all($sql);
		foreach($result as $k=>$v)
		{
			$regsql="select uid from ".UC_DBTABLEPRE."members where identityID ='$v[identityid]' and identityType=3";
			$obj=$this->db->fetch_first($regsql);
			$result[$k]['uid']=$obj['uid'];
		}
		return $result;
	}
   /**
	 +----------------------------------------------------------
	 * 根据班级id获取本月过生日的人
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_birthday_student_ById($id)
	{
		$registersql="select s.identityid,m.uid,s.csrq,s.xm from ".UC_DBTABLEPRE."studentinfo as s left join ".UC_DBTABLEPRE."members as m on s.identityid=m.identityID where date_format(s.csrq,'%m')=date_format(now(),'%m') and s.classid='$id'";
		$return= $this->db->fetch_all($registersql);
		return $return;
	}
	/**
	 +----------------------------------------------------------
	 * 根据学生姓名与家长姓名获取用户相关信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_student_by_name($stuName,$parentName){
		$sql = "SELECT s.identityid sid,s.schoolid schoolid,s.xbm xbm,s.classid classid,s.familyid familyid,s.xh xh,s.xm stuName,s.xm xm,p.xm parentName FROM ".UC_DBTABLEPRE."studentinfo s LEFT JOIN ".UC_DBTABLEPRE."parentinfo p ON s.familyid = p.familyid WHERE s.xm = '$stuName' AND p.xm = '$parentName'";
		$data = $this->db->fetch_first($sql);
		return $data;
	}
	/**
	 +----------------------------------------------------------
	 * 根据老师的uid获取老师所在学校的所有班级结构
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-3-22
	 +----------------------------------------------------------
	 */
	function get_grades_by_uid($uid){
		//uid其实就是用来获取该老师所在的学校的schoolId的
		$sql2	=	"select identityid from ".UC_DBTABLEPRE."members where `uid` = '".$uid."'";
		$result2	=	$this->db->fetch_first($sql2);
		$result2	=	arrayKeyTolower($result2);
		$sql3	=	"select schoolid from ".UC_DBTABLEPRE."studentinfo where `identityid`='".$result2['identityid']."'";
		$result3	=	$this->db->fetch_first($sql3);
		$schoolId	=	$result3['schoolid'];
		//$schoolId	=	26;
		//$sql = "SELECT id,bh,xxid FROM ".UC_DBTABLEPRE."classinfo WHERE xxid = '".$schoolId."' order by id ASC ";
		$sql	=	"select c.id,c.bh,c.xxid,s.xxmc FROM ".UC_DBTABLEPRE."classinfo as c INNER JOIN ".UC_DBTABLEPRE."schoolinfo as s on c.xxid = s.id WHERE c.xxid = ".$schoolId;
		$data	=	$this->db->fetch_all($sql);
		return $data;		
	}
	/**
	 +----------------------------------------------------------
	 * Enter description here ... （方法功能的注释）
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-3-23
	 +----------------------------------------------------------
	 */
	function get_students_by_classid($classId,$schoolId){
		$sql =	"SELECT s.identityid,s.xm FROM ".UC_DBTABLEPRE."studentinfo as s WHERE classid = ".$classId."  AND schoolid = ".$schoolId;
		$data	=	$this->db->fetch_all($sql);
		return $data;			
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学生和家长姓名获取学生的基础数据
	 +----------------------------------------------------------
	 * @param string $stuname 学生姓名
	 * @param string $parname 家长姓名
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-2 上午9:52:37
	 +----------------------------------------------------------
	 */
	function get_userinfo_by_name($stuname,$parname){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE xm = '".$stuname."' AND familyid in (SELECT familyid FROM ".UC_DBTABLEPRE."parentinfo WHERE xm = '".$parname."')";
		$data	=	$this->db->fetch_first($sql);
		return $data;
	}
}
?>
