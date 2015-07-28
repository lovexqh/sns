<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class ucrolemodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->ucrolemodel($base);
	}

	function ucrolemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function getRoleById($roleId){
		return $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."uia_role WHERE roleID='$roleId'");
	}
	
	function get_role_list_grid($keyvalue="", $page=0, $pagesize=50)
	{
		$sql = "";
		if($keyvalue != ''){
			$sql .= " AND (roleName LIKE '%$keyvalue%' )";
		}
		$index = $page * $pagesize;
		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."uia_role WHERE  roleID > 0 ".$sql);
		$data['total'] = $sum['num'];
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."uia_role WHERE  RoleID > 0 ".$sql." order by roleID DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		return $data;
	}
	
	function addRoleByArray($roleid, $roleName){
		if($roleid){
			$sql = "UPDATE ".UC_DBTABLEPRE."uia_role SET roleName='$roleName' WHERE roleID='$roleid'";
		}else{
			$sql = "INSERT INTO ".UC_DBTABLEPRE."uia_role (roleName) VALUES ('$roleName')";
		}
		$this->db->query($sql);
	}
	
	function delRoleById($roleID){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."uia_role WHERE roleID in ($roleID)";
		$delstr1 = "DELETE FROM ".UC_DBTABLEPRE."uia_r_user_role WHERE roleID in ($roleID)";
		$delstr2 = "DELETE FROM ".UC_DBTABLEPRE."uia_r_role_func WHERE roleID in ($roleID)";
		$this->db->query($delstr);
		$this->db->query($delstr1);
		$this->db->query($delstr2);
	}
	
	function checkRoleByRoleName($roleid, $roleName){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."uia_role WHERE roleName = '$roleName' AND roleID!='$roleid'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
function getRoleByUser($userid,$page=0,$pagesize=50){
		$index = $page * $pagesize;
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."r_user_role a,".UC_DBTABLEPRE."role b WHERE a.roleid=b.RoleID AND a.uid=$userid LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql);
		$sql_n = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."r_user_role a,".UC_DBTABLEPRE."role b WHERE a.roleid=b.RoleID AND a.uid=$userid";
		$data['total'] = $this->db->result_first($sql_n);
		return $data;
	}
	
	function getRoleByNoUser($userid,$page=0,$pagesize=50){
		$index = $page * $pagesize;
		$sql="SELECT * FROM ".UC_DBTABLEPRE."role WHERE roleid NOT IN (SELECT roleid FROM ".UC_DBTABLEPRE."r_user_role WHERE uid = '$userid')  order by ridentityid ASC LIMIT $index,$pagesize";
		$arr = $this->db->fetch_all($sql);
		$data['data'] = $arr;
		$sql_n = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."role WHERE roleid NOT IN (SELECT roleid FROM ".UC_DBTABLEPRE."r_user_role WHERE uid = '$userid')";
		$sum = $this->db->result_first($sql_n);
		$data['total'] = $sum;
		return $data;
	}
	
	function addRoleByUser($userid,$roleid){
		$instr = "INSERT INTO ".UC_DBTABLEPRE."r_user_role SET roleid='$roleid',uid='$userid'";
		$arr = $this->db->query($instr);
	}
	
	function delRoleByUser($userid,$roleid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."r_user_role WHERE uid='$userid' AND roleid='$roleid'";
		$arr = $this->db->query($delstr);
	}
	
	/*
	 * 获取角色对应的功能点列表
	 */
	function get_role_funcList($roleID) {
		$funcList = $this->db->fetch_all("SELECT f.id, f.appName, f.appAlias, r.roleExtend, r.roleID FROM ".UC_DBTABLEPRE."uia_r_role_func r,".UC_DBTABLEPRE."uia_func f WHERE r.appID=f.id AND r.roleID='$roleID' order by f.id ASC");
		$funNum = count ( $funcList );
		for($i=0; $i<$funNum; $i++){
			if($funcList[$i]['roleExtend'] != ''){
				$extendstr = $funcList[$i]['roleExtend'];	//数据库中的字符串
				$extendlist = explode(",",$extendstr);	//转换成数组
				$extendNum = count ( $extendlist );		//数组单元个数
				for($j=0; $j<$extendNum; $j++){
					if($extendlist[$j]=="I"){	//insert
						$funcList[$i][insert] = "I";
					}else if($extendlist[$j]=="D"){   //delete
						$funcList[$i][delete] = "D";
					}else if($extendlist[$j]=="U"){	  //update
						$funcList[$i][update] = "U";
					}else if($extendlist[$j]=="S"){	  //select
						$funcList[$i][select] = "S";
					}else if($extendlist[$j]=="C"){	  //select
						$funcList[$i][cross] = "C";
					}
				}
			}
		}
		
		return $funcList;
	}
	
	function get_role_otherFuncList($roleID) {
		$optionalFuncList = $this->db->fetch_all("SELECT DISTINCT id, appName,appAlias FROM uc_uia_func WHERE pid AND id NOT IN (SELECT appID FROM uc_uia_r_role_func WHERE roleID='$roleID') order by id ASC");
		return $optionalFuncList;
	}
	
	function addFuncList($roleID, $funcList){
		foreach($funcList as $key=>$funcId){
			$this->add_role_func($roleID,$funcId);
			//$app = $this->db->fetch_first("SELECT app_id FROM ".UC_DBTABLEPRE."app WHERE app_name='$funcName'");
			//$date = $this->db->query("UPDATE ".UC_DBTABLEPRE."r_role_func SET App_id='$app[app_id]' WHERE App_name='$funcName'");
		}
		//return $date;
	}
	
	function add_role_func($roleID,$funcId) {
		$this->db->query("INSERT INTO ".UC_DBTABLEPRE."uia_r_role_func SET roleID='$roleID',appID='$funcId'");
		//return $date;
	}
	
	function delFuncList($roleID,$funcList){
		foreach($funcList as $key=>$funcId){
			$sql = "DELETE FROM ".UC_DBTABLEPRE."uia_r_role_func WHERE roleID='$roleID' AND appID='$funcId'";
			$data = $this->db->query($sql);
		}
		return $date;
	}
	
	function saveDataByArray($rows){
		$rowsNum = count($rows);	//正常
		if($rowsNum > 0){
			for($i=0; $i<$rowsNum; $i++){
				$str = "";
				if ( $rows[$i][insert] ) {
					$str .= $rows[$i]['insert'].",";
				}
				if($rows[$i][delete]){
					$str .= $rows[$i]['delete'].",";
				}
				if($rows[$i][update]){
					$str .= $rows[$i]['update'].",";
				}
				if($rows[$i][select]){
					$str .= $rows[$i]['select'].",";
				}
				if($rows[$i][cross]){
					$str .= $rows[$i]['cross'].",";
				}
				$len = strlen($str);
				$str1 = substr($str, 0, $len-1); 
				$roleId = $rows[$i][roleID];
				$appId = $rows[$i][id];
				$savaSql = "UPDATE ".UC_DBTABLEPRE."uia_r_role_func SET RoleExtend='$str1' WHERE roleID='$roleId' AND appID='$appId'";
				$this->db->query($savaSql);
			}
		}
		
	}
	
	function getRoleList() {
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."role  order by ridentityid ASC");
		return $data;
	}
	
}

?>