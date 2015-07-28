<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class identitymodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->identitymodel($base);
	}

	function identitymodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function getIdentityList($keyvalue="",$page=0,$pagesize=50){
		$sql = "";
		if($keyvalue){
			$sql .= " AND IdentityName like ('%$keyvalue%')";
		}
		$index = $page * $pagesize;
		$sql1 = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."identity WHERE IdentityID>0".$sql;
		$sum = $this->db->result_first($sql1);
		$data['total'] = $sum;
		$sql2 = "SELECT * FROM ".UC_DBTABLEPRE."identity WHERE IdentityID>0".$sql." order by IdentityID desc LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql2);
		return $data;
	}
	
	function addIdentityById($identity){
		$identityName = $identity[0][IdentityName];
		$instr = "INSERT INTO ".UC_DBTABLEPRE."identity(IdentityName) VALUE('$identityName')";
		$this->db->query($instr);
	}
	
	function upIdentityById($identity, $identityId){
		$identityName = $identity[0][IdentityName];
		$instr = "UPDATE ".UC_DBTABLEPRE."identity SET IdentityName='$identityName' WHERE IdentityID='$identityId'";
		$this->db->query($instr);
	}
	
	function getIdentityById($identityId){
		return $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."identity WHERE IdentityID='$identityId'");
	}
	
	function checkIdentityidByName($identityName, $identityId=0){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."identity WHERE IdentityName = '$identityName' AND IdentityID != '$identityId'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	function delIdentityById($identityid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."identity WHERE identityid in ($identityid)";
		$this->db->query($delstr);
	}
	
	/**
	 *
	 * @Title: getIdentity
	 * @Description: 获取身份信息
	 * @return 身份集合
	 * @author 肖萌
	 */
	function getIdentity() {
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."identity order by identityid asc");
		return $data;
	}
	
	function getRoleByIdentity($identityId,$page=0,$pagesize=50){
		$index = $page * $pagesize;
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."r_identity_role a,".UC_DBTABLEPRE."role b WHERE a.roleid=b.RoleID AND a.identityid='$identityId' LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql);
		$sql_n = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."r_identity_role a,".UC_DBTABLEPRE."role b WHERE a.roleid=b.RoleID AND a.identityid=$identityId";
		$data['total'] = $this->db->result_first($sql_n);
		return $data;
	}
	
	function getOtherRoleByIdentity($identityId,$page=0,$pagesize=50){
		$index = $page * $pagesize;
		$sql="SELECT * FROM ".UC_DBTABLEPRE."role WHERE roleid NOT IN (SELECT roleid FROM ".UC_DBTABLEPRE."r_identity_role WHERE identityid = '$identityId')  order by RoleID ASC LIMIT $index,$pagesize";
		$arr = $this->db->fetch_all($sql);
		$data['data'] = $arr;
		$sql_n = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."role WHERE roleid NOT IN (SELECT roleid FROM ".UC_DBTABLEPRE."r_identity_role WHERE identityid = '$identityId')";
		$sum = $this->db->result_first($sql_n);
		$data['total'] = $sum;
		return $data;
	}
	
	function addIdentityRole($identityId, $roleid){
		$addsql = "INSERT INTO ".UC_DBTABLEPRE."r_identity_role (identityid, roleid) VALUES ('$identityId','$roleid')";
		$this->db->query($addsql);
	}
	
	function delIdentityRole($identityId, $roleid){
		$delsql = "DELETE FROM ".UC_DBTABLEPRE."r_identity_role WHERE identityid='$identityId' AND roleid='$roleid'";
		$this->db->query($delsql);
	}
	
	function getAppByIdentity($identityId){
		$sql = "SELECT DISTINCT a.App_alias, group_concat(r.RoleExtend SEPARATOR ',') AS RoleExtend FROM uc_r_identity_role i, uc_r_role_func r, uc_app a WHERE i.identityid = '$identityId' AND i.RoleID = r.RoleID AND r.App_id=a.app_id GROUP BY r.App_name ORDER BY r.App_name DESC";
		$data['data'] = $this->db->fetch_all($sql);
		return $data;
	}
	
}

?>