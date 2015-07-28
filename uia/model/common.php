<?php

/*
 [RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
This is NOT a freeware, use is subject to license terms

$Id: app.php 1059 2011-03-01 07:25:09Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class commonmodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->commonmodel($base);
	}

	function commonmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	/*
	 * 获取学校性质
	*/
	function get_XXXZ(){
		$sql = "SELECT itemcode, itemcn from uc_dict_item WHERE uc_dict_item.dataid=(SELECT dataid from uc_dict_type WHERE dataen='XXXZ')";
		return $this->db->fetch_all($sql);
	}
	
	/*
	 * 获取学校举办者
	*/
	function get_XXJBZ(){
		$sql = "SELECT itemcode, itemcn from uc_dict_item WHERE uc_dict_item.dataid=(SELECT dataid from uc_dict_type WHERE dataen='XXJYJGJBZ')";
		return $this->db->fetch_all($sql);
	}

	/*
	 * 获取办学类型码
	*/
	function get_BXLX(){
		return  $this->db->fetch_all("SELECT itemcode, itemcn from uc_dict_item WHERE uc_dict_item.dataid=(SELECT dataid from uc_dict_type WHERE dataen='BXLX')");
	}

	/*
	 * 获取所在地城乡类型
	*/
	function get_SZDCXLX(){
		return  $this->db->fetch_all("SELECT itemcode, itemcn from uc_dict_item WHERE uc_dict_item.dataid=(SELECT dataid from uc_dict_type WHERE dataen='SZDCXLX')");
	}

	/*
	 * 获取所在地区经济属性
	*/
	function get_SZDQJJSX(){
		return  $this->db->fetch_all("SELECT itemcode, itemcn from uc_dict_item WHERE uc_dict_item.dataid=(SELECT dataid from uc_dict_type WHERE dataen='SZDQJJSX')");
	}

	/*
	 * 获取学校办别
	*/
	function get_XXBB(){
		return  $this->db->fetch_all("SELECT itemcode, itemcn from uc_dict_item WHERE uc_dict_item.dataid=(SELECT dataid from uc_dict_type WHERE dataen='XXBB')");
	}

	/*
	 * 获取小学入学年龄
	*/
	function get_XXRXNL(){
		return $this->db->fetch_all("SELECT itemcode, itemcn from uc_dict_item WHERE itemcode<=2 AND uc_dict_item.dataid=(SELECT dataid from uc_dict_type WHERE dataen='RXNL')");
	}

	/*
	 * 获取初中入学年龄
	*/
	function get_CZRXNL(){
		return $this->db->fetch_all("SELECT itemcode, itemcn from uc_dict_item WHERE itemcode>=3 AND uc_dict_item.dataid=(SELECT dataid from uc_dict_type WHERE dataen='RXNL')");
	}

	function  get_dict_item($dataen){
		//如果是数组，遍历后按格式输出
		if(is_array($dataen)){
			$whereen = implode(',',$dataen);
			$whereen = "'".str_replace(",","','",$whereen)."'";
		}else{
			$whereen = "'".$dataen."'";
		}
		$sql = "SELECT a.itemcode, a.itemcn,b.dataen from uc_dict_item a,uc_dict_type b WHERE a.dataid=b.dataid AND b.dataen IN (".$whereen.") ORDER BY b.dataen ASC,a.itemcode ASC";
		$dictList = $this->db->fetch_all($sql);
		
		foreach ($dictList as $key=>$val){
			$relist[$val['dataen']][$val['itemcode']] = $val['itemcn'];
		}
		return $relist;
	}
	
	function  get_dict_item_by_en($dataen){
		//如果是数组，遍历后按格式输出
		if(is_array($dataen)){
			$whereen = implode(',',$dataen);
			$whereen = "'".str_replace(",","','",$whereen)."'";
		}else{
			$whereen = "'".$dataen."'";
		}
		$sql = "SELECT a.itemcode, a.itemcn,b.dataen from uc_dict_item a,uc_dict_type b WHERE a.dataid=b.dataid AND b.dataen IN (".$whereen.") ORDER BY b.dataen ASC,a.itemcode ASC";
		$dictList = $this->db->fetch_all($sql);
		
		foreach ($dictList as $key=>$val){
			$relist[$key]['itemcode'] = $val['itemcode'];
			$relist[$key]['itemcn'] = $val['itemcn'];
		}
		return $relist;
	}

	/*
	 * 根据参数得到自己想要的数据
	* table - 数据表
	* field - 列名
	* where - 条件
	*/
	function get_array($obj){
		if($obj['field'] == ''){
			$obj['field'] = '*';
		}
		if($obj['where'] != ''){
			$obj['where']  = ' where '.$obj['where'];
		}
		$rearray=  $this->db->fetch_all("SELECT ".$obj['field']." FROM ".UC_DBTABLEPRE.$obj['table'].$obj['where']);
		return  $rearray;
	}

	/*
	 * 按$check的要求进行array的检测
	* 1 - 不能为空
	* 0 - 可以为空
	*/

	function check_array($contrast,$data){
		$i = 0;
		$msg = '';
		foreach ($contrast as $key=>$val){
			if($val == 1 && (is_null($data[$key]) || $data[$key] == '')){
				$msg .= $key.' - 这个字段被设置为不允许为空！_';
				break;
			}
			$i = $i + 1;
		}
		if($i == count($contrast)){
			return array('result'=>'1','msg'=>'结果符合规范要求');
		}else{
			return array('result'=>'0','msg'=>$msg);
		}
	}

	/*
	 * 按$dbcheck的要求进行array的检测
	* 如果存在，返回0
	* 如果不存在，返回1
	*/
	function dbcheck_array($contrast,$data){
		//print_r($contrast);
		if($contrast['result']){
			$records = $contrast['records'];
			foreach ($data as $key=>$val){
				foreach ($records as $rkey=>$rval){
					if(!$this->judgeEqual($rval,$val)){
						unset($data[$key]);
					}
				}
			}
		}
		return $data;
	}
	
	function arraytoSql($rows){
		$sql = '';
		foreach ($rows as $key=>$val){
			$sql .= " $key = '$val',";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		return $sql;
		
	}

	function judgeEqual($key1,$key2){
		return (count(array_diff($key1,$key2)) || count(array_diff($key2,$key1)));
	}
	
	
	function whereEquale($wherear)
	{
		$wherestr = "";
		foreach ($wherear as $key=>$val){
			if(!empty($val)){
				$wherestr .= " AND $key = '$val' ";
			}
		}
		return $wherestr;
	}
	
	
	function jiemi($txt, $key = null) {
		if (empty ( $key )){
			$key = UC_SECURE_CODE;
		}
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
		$ch = $txt [0];
		$nh = strpos ( $chars, $ch );
		$mdKey = md5 ( $key . $ch );
		$mdKey = substr ( $mdKey, $nh % 8, $nh % 8 + 7 );
		$txt = substr ( $txt, 1 );
		$tmp = '';
		$i = 0;
		$j = 0;
		$k = 0;
		for($i = 0; $i < strlen ( $txt ); $i ++) {
			$k = $k == strlen ( $mdKey ) ? 0 : $k;
			$j = strpos ( $chars, $txt [$i] ) - $nh - ord ( $mdKey [$k ++] );
			while ( $j < 0 )
				$j += 64;
			$tmp .= $chars [$j];
		}
		return base64_decode ( $tmp );
	}
	
	function curl_sns($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		if ($output === FALSE) {
			return '';
		}else{
			return $output;
		}
	}

}
?>