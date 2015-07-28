<?php

/*
 [UCenter] (C)2001-2099 Comsenz Inc.
This is NOT a freeware, use is subject to license terms

$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class schoolmodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->schoolmodel($base);
	}

	function schoolmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_school_list_grid($wherear,$keyvalue="",$page=0,$pagesize=50){
		$schoolid = $wherear['schoolid'];
		
		$sql = "";
		
		if($schoolid != -1){	
			$sql .= " AND id=$schoolid";
		}
		if($keyvalue != ''){
			$sql .= " AND xxmc like '%$keyvalue%'";
		}
		$index = $page * $pagesize;
		$sql1 = "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."schoolinfo WHERE id>0".$sql;
		$sum = $this->db->fetch_first($sql1);
		$data['total'] = $sum['num'];
		$sql2 = "SELECT * FROM ".UC_DBTABLEPRE."schoolinfo WHERE id>0".$sql." order by id DESC LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql2);
		return $data;
	}
	
	function checkSchoolByXxdm($xxdm,$schoolid){
		$chekstr = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."schoolinfo WHERE xxdm='$xxdm' AND id NOT IN('$schoolid')";
		$result = $this->db->result_first($chekstr);
		if($result == 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	
	function checkSchoolByXxmc($xxmc,$schoolid){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."schoolinfo WHERE xxmc='$xxmc' AND id NOT IN('$schoolid')";
		$result = $this->db->result_first($chekstr);
		if($result == 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	
	function upSchoolByArray($schoollist){
		$schoolid = $schoollist['schoolid'];
		unset($schoollist['schoolid']);
		$dms = $schoollist['dms'];
		$mcs = $schoollist['mcs'];
		unset($schoollist['dms']);
		unset($schoollist['mcs']);
		$xxmc = $schoollist['xxmc'];
		$oldxxmc = $schoollist['oldxxmc'];
		unset($schoollist['oldxxmc']);
		if($schoolid == 0 && $dms == 1 && $mcs == 1){
			$instr = "INSERT ".UC_DBTABLEPRE."schoolinfo(";
			foreach($schoollist as $key=>$value){
				if($key == "xqr"){
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
			$d_id = mysql_insert_id();
			$inedep ="INSERT INTO " .UC_DBTABLEPRE . "dept(DepartName,UpDeptId,schoolid) VALUES('$xxmc','0','$d_id')";
			$this->db->query($inedep);
		}
		else if($dms == 1 && $mcs == 1){
				$upstr = "UPDATE ".UC_DBTABLEPRE."schoolinfo SET ";
				foreach($schoollist as $key=>$value){
					if($key == "xqr"){
						$sql .= "$key='$value'";
					}
					else{
						$sql .= "$key='$value',";
					}
				}
				$upstr .= $sql." WHERE id=$schoolid";
				$this->db->query($upstr);
				if($xxmc != $oldxxmc){
					$upddep = "UPDATE SET ".UC_DBTABLEPRE."dept DepartName='$xxmc' WHERE UpDeptId='0' AND schoolid='$schoolid'";
				}
		}
	}
	
	function getSchoolById($schoolid){
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."schoolinfo WHERE id=$schoolid";
		$arr = $this->db->fetch_first($sql);
		$jxny = $arr['jxny'];
		$year = substr($jxny,0,4);
		$month = substr($jxny,-1,2);
		$xqr = $arr['xqr'];
		$day = substr($xqr,-1,2);
		$jxnyr = $year."-".$month."-".$day;
		$arr['jxnyr'] = $jxnyr;
		return  $arr;
	}
	
	function delSchoolById($schoolid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."schoolinfo WHERE id in ($schoolid)";
		$this->db->query($delstr);
		//删除学校的同时删除dept表里对应的作为根节点的学校
		$delstr1 = "DELETE FROM ".UC_DBTABLEPRE."dept WHERE UpDeptID = '0' AND schoolid in ($schoolid)";
		$this->db->query($delstr1);
	}
	
	function getXxmcById($schoolid){
		$sql = "SELECT xxmc FROM ".UC_DBTABLEPRE."schoolinfo WHERE id=$schoolid";
		$arr = $this->db->result_first($sql);
		return $arr;
	}
	
	
	//------------------------------分割线----------------------------------------------------------------------------------
	//得到信息总条数
	function get_total_num($sqladd = '') {
		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."schoolinfo $sqladd");
		return $data;
	}
	//得到所有信息
	function get_list($page, $ppp, $totalnum, $sqladd) {
		$start = $this->base->page_get_start($page, $ppp, $totalnum);
		//echo "SELECT * FROM ".UC_DBTABLEPRE."schoolinfo $sqladd LIMIT $start, $ppp";
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."schoolinfo $sqladd LIMIT $start, $ppp");
		return $data;
	}
	//通过学校代码查找学校信息
	function get_school_by_xxdm($xxdm) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."schoolinfo WHERE xxdm='$xxdm' AND isdel = '0'");
		return $arr;
	}
	//通过学校名称查找学校信息
	function get_school_by_xxmc($xxmc) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."schoolinfo WHERE xxmc='$xxmc' AND isdel = '0'");
		return $arr;
	}
	//通过学校schoolid查找学校信息
	function get_school_by_id($id) {
		/**
		 * 添加了一个-1的判断，如果Id = -1，则返回所有的学校信息
		 * 把fetch_first => fetch_all
		 */
		if(-1 != (int)$id){
			$whestr = " AND id='$id' ";
		}
		$arr = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."schoolinfo WHERE isdel = '0' ".$whestr);
		return $arr;
	}
	//核对学校名称
	function check_schoolname($schoolname)
	{
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = $this->dstrlen($schoolname);
		if($len > 20 || $len < 2 || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $schoolname))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	function dstrlen($str)
	{
		//strtolower() 函数把字符串转换为小写
		if(strtolower(UC_CHARSET) != 'utf-8')
		{
			//strlen() 函数用于计算字符串的长度
			return strlen($str);
		}
		$count=0;
		for($i=0;$i<strlen($str);$i++)
		{
			//ord()函数返回字符串第一个字符的 ASCII值
			$value = ord($str[$i]);
			if($value>127)
			{
				$count++;
				if($value>=192&&$value <= 223)$i++;
				elseif($value >= 224 && $value <= 239) $i = $i + 2;
				elseif($value >= 240 && $value <= 247) $i = $i + 3;
			}
			$count++;
		}
		return $count;
	}
	//核对学校名称是否存在
	function check_schoolnameexists($schoolname)
	{
		$data = $this->db->result_first("SELECT xxmc FROM ".UC_DBTABLEPRE."schoolinfo WHERE xxmc='$schoolname' AND isdel = '0'");
		return $data;
	}

	//核对邮政编码
	function check_yzbm($yzbm)
	{
		$pattern='/^[1-9]\d{5}$/';
		if(preg_match($pattern,$yzbm))
		{
			return true;
		}else
		{
			return false;
		}
	}
	//核对联系电话
	function check_lxdh($lxdh)
	{
		$pattern='/(^[0-9]{3,4}\-[0-9]{3,8}$)|(^[0-9]{3,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)|(^0{0,1}13[0-9]{9}$)|(^15\d{5,9}$)|(^18\d{5,9}$)/';
		if (preg_match($pattern,$lxdh))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//验证电子信箱
	function check_dzxx($dzxx)
	{
		return strlen($dzxx) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $dzxx);
	}

	//核对学校代码是否存在
	function check_xxdmexists($xxdm)
	{
		$data=$this->db->result_first("SELECT xxdm FROM ".UC_DBTABLEPRE."schoolinfo WHERE xxdm='$xxdm' AND isdel = '0'");
		return $data;
	}
	//删除学校
	function delete_school($IDsarr)
	{
		$IDsarr = (array)$IDsarr;
		if(!$IDsarr)
		{
			return 0;
		}
		$IDs = $this->base->implode($IDsarr);
		if($IDs)
		{
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."schoolinfo WHERE ID IN($IDs)");
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."dept WHERE UpDeptID = '0' AND schoolid IN($IDs)");
			$this->base->load('note');
			$_ENV['note']->add('deleteschool', "ID=$IDs");
			return $this->db->affected_rows();
		}
		else
		{
			return 0;
		}
	}
	

}

?>