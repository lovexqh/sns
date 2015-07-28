<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

	/**
	 *生成查询条件语句
	 *$columns 	要查询的列
	 *$isLike 	是否采用模糊查询
	 */
	function genSearchSql($columns,$isLike){
		$sql="where 1=1";

		foreach ($columns as $key=>$colName){
			$val=$_POST[$colName];

			if($val){
				if(!$isLike){
					$sql.=" and $colName ='$val'";
				}else{
					$sql.=" and $colName like '%$val%'";
				}
			}
		}

		return $sql;
	}

	/**
	 * 生成 插入SQL语句
	 */
	function genInsertSql($columns){
		$sql="";

		foreach ($columns as $key=>$colName){
			$val[]=$_POST[$colName];
		}

		$cols = $this->base->implode($columns);//将列名数组转换为","分割的字符串
		$values=$this->base->implode($val);//将值数组转换为","分割的字符串

		$sql="( '$cols' ) values ( '$values' )";
		return $sql;
	}

	/*
	 * 生成插入或更新SQL语句
	 */
	function genKeyValSql($columns){
		print_r($columns);
		$sql=" set ";

		for ($index = 0; $index < sizeof($columns)-1; $index++) {
			$colName = $columns[$index];
			$val=$_POST[$colName];
			$sql.=" $colName ='$val',";
		}

		$colName = $columns[sizeof($columns)-1];
		$val=$_POST[$colName];
		$sql.=" $colName ='$val'";

		return $sql;
	}

	/**
	 * 生成添加记录时的通用信息sql
	 * 通用信息主要指用户id,最后更新时间
	 */
	function genInsertCommonSql($user){
		if(!$user){
			return "";
		}

		$uid=$user['uid'];
		if(!$uid){
			$uid=0;
		}

		$sql=" ";
		$sql.=" input_userid='$uid'";
		$sql.=", lastupdate=".time();
		return $sql;
	}
	function genUpdateCommonSql($user){
		if(!$user){
			return "";
		}
		$uid=$user['uid'];
		if(!$uid){
			$uid=0;
		}
		$sql=" ";
		$sql.=" update_userid='$uid'";
		$sql.=", lastupdate=".time();

		return $sql;
	}

	/**
	 * 使用","连接数组中的每个元素
	 */
	function commaJoin($valArr){
		if(!$valArr){
			return "";
		}
		$join="";
		for ($index = 0; $index < sizeof($valArr)-1; $index++) {
			$val=$valArr[$index];
			$join.= $val.",";
		}

		$join.= $valArr[sizeof($valArr)-1];
		return $join;

	}

?>