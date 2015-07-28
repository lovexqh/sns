<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class adminsmodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->adminsmodel($base);
	}

	function adminsmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';

	}
	
	function get_newadmin_list_grid($keyvalue="", $page=0, $pagesize=50)
	{
		$sql = "";
		if($keyvalue != ''){
			$sql .= "WHERE username LIKE '%$keyvalue%'";
		}
		$index = $page * $pagesize;
		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."sysadmin a LEFT JOIN ".UC_DBTABLEPRE."schoolinfo s ON a.schoolid=s.id ".$sql);
		$data['total'] = $sum['num'];
		$sql = "SELECT a.*,s.xxmc FROM ".UC_DBTABLEPRE."sysadmin a LEFT JOIN ".UC_DBTABLEPRE."schoolinfo s ON a.schoolid=s.id ".$sql." order by a.id DESC LIMIT $index,$pagesize";

		$data['data'] =  $this->db->fetch_all($sql);
		for($i=0; $i<$data['total']; $i++){
			if($data['data'][$i]['adminType']==0){
				$data['data'][$i]['adminType'] = "非注册用户管理员";
			}else if($data['data'][$i]['adminType']==1){
				$data['data'][$i]['adminType'] = "注册用户管理员";
			}
		}
		return $data;
	}
	
	function date($time, $type = 3) {
		$format[] = $type & 2 ? (!empty($this->settings['dateformat']) ? $this->settings['dateformat'] : 'Y-n-j') : '';
		$format[] = $type & 1 ? (!empty($this->settings['timeformat']) ? $this->settings['timeformat'] : 'H:i') : '';
		return gmdate(implode(' ', $format), $time + $this->settings['timeoffset']);
	}
	
	function addNewAdminByArray($rows){
		$id = $rows[0]['id'];
		if($id == 0){
			$upstr = "INSERT INTO ".UC_DBTABLEPRE."sysadmin SET ";
			foreach ($rows[0] as $key=>$value){
				$upstr .= "$key = '$value' ,";
			}
			$upstr = substr($upstr,0,(strlen($upstr)-1));
		}else{
			unset($rows[0]['password']);
			$upstr = "UPDATE ".UC_DBTABLEPRE."sysadmin SET ";
			foreach ($rows[0] as $key=>$value){
				$upstr .= "$key = '$value' ,";
			}
			$upstr = substr($upstr,0,(strlen($upstr)-1));
			$upstr .= " WHERE id = '$id'";
		}
		$this->db->query($upstr);
	}
	
	function delAdminById($uid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."admins WHERE uid in ($uid)";
		$this->db->query($delstr);
	}
	
	function delNewAdminById($id){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."sysadmin WHERE id in ($id)";
		$this->db->query($delstr);
	}

	function getAdminById($id){
		return $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."sysadmin WHERE id='$id'");
	}
	
	function getSchool(){
		return $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."schoolinfo");
	}
	
	function checkAdminByUsername($id, $userName){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."sysadmin WHERE username = '$userName' AND id!='$id'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	function getTeacherDeptTree($schoolid){
		$sql = "";
		if($schoolid != -1){
			$sql .= " WHERE schoolid='$schoolid'";
		}
		$treeSql = "SELECT DeptID as id,DepartName as text,UpDeptID as pid,schoolid FROM ".UC_DBTABLEPRE."dept ".$sql." ORDER BY UpDeptID ASC,DeptID ASC";
		$teacherTreeNode = $this->db->fetch_all($treeSql);
		return json_encode($teacherTreeNode);
	}
	
	function getMemberList($logschoolid, $schoolid, $updepid, $depid,$keyvalue,$pageIndex,$pagesize){
		$wherestr = "";
		if($logschoolid != -1){	//非超级管理员
			$wherestr .= " AND t.schoolid=$logschoolid";
			if( $depid ){
				$wherestr = " AND t.depid='$depid'";
			}
		}else{	//超级管理员
			$wherestr .= "";
			if( $schoolid ){
				if( $updepid==-1 ){
					$wherestr .= " AND t.schoolid='$schoolid'";
				}else if($updepid!=-1){
					$wherestr .= " AND t.depid='$depid'";
				}
			}
		}
	
		if($keyvalue != ''){
			$wherestr .= " AND (m.username LIKE '%$keyvalue%' )";
		}
		$index = $pageIndex * $pagesize;
		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."members m,".UC_DBTABLEPRE."teacherinfo t,".UC_DBTABLEPRE."schoolinfo s WHERE t.schoolid=s.id AND m.identityID=t.identityid AND m.identityType='2' ".$wherestr," AND m.email NOT IN (SELECT username FROM uc_sysadmin WHERE adminType='1')");
		$data['total'] = $sum['num'];
		$sql = "SELECT m.uid,m.username,m.email,t.xm,t.sfzjh,s.xxmc FROM ".UC_DBTABLEPRE."members m,".UC_DBTABLEPRE."teacherinfo t,".UC_DBTABLEPRE."schoolinfo s WHERE t.schoolid=s.id AND m.identityID=t.identityid AND m.identityType='2' ".$wherestr." AND m.email NOT IN (SELECT username FROM uc_sysadmin WHERE adminType='1') ORDER BY m.uid DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		return $data;
	}
	
	function addadmin($uid){
		foreach($uid as $value){
			$member = $this->getMemberByUid($value);
			$username = $member['email'];
			$password = $member['password'];
			$schoolid = $member['schoolid'];
			$adminType = '1';
			$name = $member['name'];
			$contact = $member['contact'];
			$upstr = "insert into ".UC_DBTABLEPRE."sysadmin (username,password,schoolid,adminType,name,contact) values ('$username','$password','$schoolid','$adminType','$name','$contact')";
			$this->db->query($upstr);
			// 			$id = mysql_insert_id();
			// 			$sql2 = "INSERT INTO ".UC_DBTABLEPRE."uia_r_user_role (uid,roleID) VALUES ('$id','7')";
			// 			$this->db->query($sql2);
		}
	}
	
	function getMemberByUid($uid){
		$sql = "SELECT m.username,m.`password`,m.email,t.schoolid,t.xm AS name,t.lxdh AS contact FROM uc_members m,uc_teacherinfo t WHERE m.identityID=t.identityid AND m.uid='$uid'";
		return $this->db->fetch_first($sql);
	}
	
	function getRoleById($id){
		return $this->db->fetch_all("SELECT r.* FROM ".UC_DBTABLEPRE."uia_role r, ".UC_DBTABLEPRE."uia_r_user_role u WHERE r.roleID=u.roleID AND u.uid='$id'");
	}
	
	function getOtherRoleById($id){
		return $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."uia_role WHERE roleID NOT IN (SELECT roleID FROM ".UC_DBTABLEPRE."uia_r_user_role WHERE uid='$id')");
	}
	
	function getAppLimit($id){
		$sql = "SELECT DISTINCT f.appAlias, group_concat(r.roleExtend SEPARATOR ',') AS roleExtend FROM uc_uia_r_user_role u, uc_uia_r_role_func r, uc_uia_func f WHERE u.uid = '$id' AND u.roleID = r.roleID AND r.appID=f.id GROUP BY f.appName ORDER BY f.appName DESC";
		$data = $this->db->fetch_all($sql);
		return $data;
	}
	
	function addRole($adminid, $roleid){
		foreach($roleid as $key=>$value){
			$this->db->query("INSERT INTO ".UC_DBTABLEPRE."uia_r_user_role SET uid='$adminid',roleID='$value'");
		}
	}
	
	function delRole ( $adminid, $roleid ){
		$delsql = "DELETE FROM ".UC_DBTABLEPRE."uia_r_user_role WHERE uid='$adminid' AND roleID='$roleid'";
		$this->db->query($delsql);
	}
	
	
}

?>