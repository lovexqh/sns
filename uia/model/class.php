<?php
/*
 * Created on 2013-4-22
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
!defined('IN_UC') && exit ('Access Denied');

class classmodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(& $base) {
		$this->classmodel($base);
	}

	function classmodel(& $base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT . 'lib/baseInfoPub.php';

	}
	
	
	//一次性加载
	function get_class_dept_tree($schoolid){
		$sql = '';
		if($schoolid != -1){
			$sql_a = " AND id = '".$schoolid."'";
		}
		$sql1 = "SELECT id,xxmc FROM ".UC_DBTABLEPRE."schoolinfo WHERE 1=1".$sql_a;
		$school = $this->db->fetch_all($sql1);		//所有学校
		$num_s = count($school);
		$treeNode = array();
		$j = 1;
		$m = 0;
		for($i1=0 ; $i1<$num_s ; $i1++){
			$tree1 = array(
					"id"=>$j++,
					"pid"=>0,
					"name"=>$school[$i1]['xxmc'],
					"eid"=>$school[$i1]['id'],
					"ss"=>1
			);
			array_push($treeNode,$tree1);
			$sql2 = "SELECT id,yxmc FROM ".UC_DBTABLEPRE."academyinfo WHERE xxid=".$tree1['eid'];
			$yx = $this->db->fetch_all($sql2);
			$num_y = count($yx);
			for ($i2=1 ; $i2<=$num_y ; $i2++){
			$tree2 = array(
			"id"=> $j++,
			"pid"=>$tree1['id'],
			"name"=>$yx[$i2-1]['yxmc'],
					"eid"=>$yx[$i2-1]['id'],
					"ss"=>2
			);
			array_push($treeNode,$tree2);
			$sql3 = "SELECT id,zymc FROM ".UC_DBTABLEPRE."specialtyinfo WHERE yxid=".$tree2['eid'];
			$zy = $this->db->fetch_all($sql3);
			$num_z = count($zy) ;
			if($num_z > 0){
				for($i3=1 ; $i3<=$num_z ; $i3++){
					$tree3 = array(
							"id"=>$j++,
							"pid"=>$tree2['id'],
							"name"=>$zy[$i3-1]['zymc'],
							"eid"=>$zy[$i3-1]['id'],
							"ss"=>3
							);
					array_push($treeNode,$tree3);
				}
			}
		}
	}
			return json_encode($treeNode);
	}
	
	function get_class_list_grid($wherear,$keyvalue="",$page=0,$pagesize=50){
		$schoolid = $wherear['schoolid'];
		$eid = $wherear['eid'];
		$ss = $wherear['ss'];
		$sql = "";
		if(!$ss){
			if($schoolid){
				$sql .= " AND xxid=$schoolid";
			}
		}
		else if($ss == 1){
			$sql .= " AND xxid=$eid";
		}
		else if($ss == 2){
			$sql .= " AND yxid=$eid";
		}
		else if($ss == 3){
			$sql .= " AND zyid=$eid";
		}
		if($keyvalue != ''){
			$sql .= " AND bm like '%$keyvalue%'";
		}
		$index = $page * $pagesize;
		$sql1 = "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."classinfo WHERE id > 0".$sql;
		$sum = $this->db->fetch_first($sql1);
		$data['total'] = $sum['num'];
		$sql2 = "SELECT * FROM ".UC_DBTABLEPRE."classinfo WHERE id>0 ".$sql." order by id DESC LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql2);
		return $data;
	}
	
	function get_mc_by_zyid($zyid){
		$sql1 = "SELECT b.xxmc,b.id as xxid,c.yxmc,c.id as yxid,c.yxbm,d.zymc,d.id as zyid,d.zyh FROM ".UC_DBTABLEPRE."schoolinfo b,".UC_DBTABLEPRE."academyinfo c,".UC_DBTABLEPRE."specialtyinfo d WHERE b.id=c.xxid AND c.id=d.yxid AND d.id=$zyid";
		$class = $this->db->fetch_first($sql1);
		return $class;
	}
	
	function getClass_by_id($classid){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."classinfo WHERE id=$classid";
		$class = $this->db->fetch_first($sql);
	}
	
	function checkClassByBm($bm,$classid){
		$chekstr = "SELECT count(*) FROM ".UC_DBTABLEPRE."classinfo WHERE bm='$bm' AND id NOT IN ($classid)";
		$result = $this->db->result_first($chekstr);
		if($result == 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	
	function get_xsid_by_xh($xh){
		$sql = "SELECT identityid FROM ".UC_DBTABLEPRE."studentinfo WHERE xh='$xh'";
		$result = $this->db->result_first($sql);
		return $result;
	}
	
	function get_lsid_by_gh($gh){
		$sql = "SELECT identityid FROM ".UC_DBTABLEPRE."teacherinfo WHERE zgh='$gh'";
		$result = $this->db->result_first($sql);
		return $result;
	}
	
	function upClassByArray($classlist){
		$id = $classlist['classid'];
		//添加班级
		unset($classlist['classid']);
		$bms = $classlist['bms'];
		unset($classlist['bms']);
		if($id == 0 && $bms == 1 ){
			$instr = "INSERT ".UC_DBTABLEPRE."classinfo(";
			foreach($classlist as $key=>$value){
				if($key == "bzrid"){
					$keysql .= "$key";
					$valuesql .= "'$value'";
				}
				else{
					$keysql .= "$key,";
					$valuesql .= "'$value',";
				}
			}
			$instr .= $keysql.") VALUES(".$valuesql.")";
			$this->db->query($instr);
		}
		else if($bms == 1){
			$upstr = "UPDATE ".UC_DBTABLEPRE."classinfo SET ";
			foreach ($classlist as $key=>$value){
				if($key == "bzrid"){
					$sql .= "$key='$value'";
				}
				else{
					$sql .= "$key='$value',";
				}
			}
			$upstr .= $sql." WHERE id=$id";
			$this->db->query($upstr);
		}
	}
	
	function delClassById($classid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."classinfo WHERE id in ($classid)";
		$this->db->query($delstr);
	}
	
	function getClassByClassid($id){
		$sql = "SELECT xxid,yxid,zyid FROM ".UC_DBTABLEPRE."classinfo WHERE id=$id";
		$arr = $this->db->fetch_first($sql);
		if(!$arr['xxid']){
			$sql1 = "SELECT * FROM ".UC_DBTABLEPRE."classinfo WHERE id=$id";
		}
		else if(!$arr['zyid']){
			$sql1 = "SELECT a.*,b.xxmc,c.yxmc FROM ".UC_DBTABLEPRE."classinfo a,".UC_DBTABLEPRE."schoolinfo b,".UC_DBTABLEPRE."academyinfo c WHERE a.xxid=b.id AND a.yxid=c.id AND a.id=$id";
		}
		else if(!$arr['yxid']){
			$sql1 = "SELECT a.*,b.xxmc FROM ".UC_DBTABLEPRE."classinfo a,".UC_DBTABLEPRE."schoolinfo b WHERE a.xxid=b.id AND a.id=$id";
		}
		else{
			$sql1 = "SELECT a.*,b.xxmc,c.yxmc,d.zymc FROM ".UC_DBTABLEPRE."classinfo a,".UC_DBTABLEPRE."schoolinfo b,".UC_DBTABLEPRE."academyinfo c,".UC_DBTABLEPRE."specialtyinfo d WHERE b.id=c.xxid AND c.id=d.yxid AND a.zyid=d.id AND a.id=$id";
		}
		$result = $this->db->fetch_first($sql1);
		return $result;
	}

}
?>
