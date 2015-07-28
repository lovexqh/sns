<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class rolemodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->rolemodel($base);
	}

	function rolemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function getRoleById($roleId){
		return $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."role WHERE RoleID='$roleId'");
	}
	
	function get_role_list_grid($keyvalue="", $page=0, $pagesize=50)
	{
		$sql = "";
		if($keyvalue != ''){
			$sql .= " AND (RoleName LIKE '%$keyvalue%' )";
		}
		$index = $page * $pagesize;
		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."role WHERE  RoleID > 0 ".$sql);
		$data['total'] = $sum['num'];
		$sql = "SELECT `RoleID`,`RoleName`,`ridentityid`,`RoleIcon`,`IdentityID`,`IdentityName`,`IdentityIcon` FROM ".UC_DBTABLEPRE."role left join ".UC_DBTABLEPRE."identity on ridentityid = identityid WHERE  RoleID > 0 ".$sql." order by RoleID DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		return $data;
	}
	
	function addRoleByArray($rows){
		$upstr = "INSERT ".UC_DBTABLEPRE."role SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}
	
	function delRoleById($RoleID){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."role WHERE RoleID in ($RoleID)";
		$delstr1 = "DELETE FROM ".UC_DBTABLEPRE."r_identity_role WHERE roleid in ($RoleID)";
		$delstr2 = "DELETE FROM ".UC_DBTABLEPRE."r_role_func WHERE RoleID in ($RoleID)";
		$this->db->query($delstr);
		$this->db->query($delstr1);
		$this->db->query($delstr2);
	}
	
	function checkRoleByRoleName($RoleName){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."role WHERE RoleName = '$RoleName'";
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
		$funcList = $this->db->fetch_all("SELECT r.*,a.app_alias FROM ".UC_DBTABLEPRE."r_role_func r,".UC_DBTABLEPRE."app a WHERE r.App_name=a.app_name AND RoleID='$roleID' order by SortOrder ASC");
		$funNum = count ( $funcList );
		for($i=0; $i<$funNum; $i++){
			if($funcList[$i]['RoleExtend'] != ''){
				$extendstr = $funcList[$i]['RoleExtend'];	//数据库中的字符串
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
					}else if($extendlist[$j]=="R"){	  //release
						$funcList[$i][release] = "R";
					}else if($extendlist[$j]=="A"){	  //audit
						$funcList[$i][audit] = "A";
					}
				}
			}
		}
		
		return $funcList;
	}
	
	function get_role_otherFuncList($roleID) {
		$optionalFuncList = $this->db->fetch_all("SELECT DISTINCT app_name,app_alias,description FROM uc_app WHERE app_name NOT IN (SELECT App_name FROM uc_r_role_func WHERE RoleID='$roleID') order by app_id ASC");
		return $optionalFuncList;
	}
	
	function addFuncList($RoleID, $funcList){
		foreach($funcList as $key=>$funcName){
			$this->add_role_func($RoleID,$funcName);
			$app = $this->db->fetch_first("SELECT app_id FROM ".UC_DBTABLEPRE."app WHERE app_name='$funcName'");
			$date = $this->db->query("UPDATE ".UC_DBTABLEPRE."r_role_func SET App_id='$app[app_id]' WHERE App_name='$funcName'");
		}
		return $date;
	}
	
	function add_role_func($RoleID,$AppName,$AppID,$RoleExtend,$SortOrder) {
		$date=$this->db->query("INSERT INTO ".UC_DBTABLEPRE."r_role_func SET  RoleID='$RoleID',App_name='$AppName',App_id='$AppID',RoleExtend='$RoleExtend',SortOrder='$SortOrder'");
		return $date;
	}
	
	function delFuncList($RoleID,$funcList){
		foreach($funcList as $key=>$funcName){
			$sql = "DELETE FROM ".UC_DBTABLEPRE."r_role_func WHERE RoleID='$RoleID' AND App_name='$funcName'";
			$data = $this->db->query($sql);
		}
		return $data;
	}
	
	function saveDataByArray($rows){
		$rowsNum = count($rows);	//正常
		if($rowsNum > 0){
			for($i=0; $i<$rowsNum; $i++){
				$str = "";
				if ( $rows[$i][insert] ) {
					$str .= $rows[$i][insert].",";
				}
				if($rows[$i][delete]){
					$str .= $rows[$i][delete].",";
				}
				if($rows[$i][update]){
					$str .= $rows[$i][update].",";
				}
				if($rows[$i][select]){
					$str .= $rows[$i][select].",";
				}
				if($rows[$i][release]){
					$str .= $rows[$i][release].",";
				}
				if($rows[$i][audit]){
					$str .= $rows[$i][audit].",";
				}
				$roleId = $rows[$i][RoleID];
				$appName = $rows[$i][App_name];
				$savaSql = "UPDATE ".UC_DBTABLEPRE."r_role_func SET RoleExtend='$str' WHERE RoleID='$roleId' AND App_name='$appName'";
				$this->db->query($savaSql);
			}
		}
		
	}
	
}

?>