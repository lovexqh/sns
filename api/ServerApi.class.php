<?php
class ServerApi extends Api{
	function snslist(){
		$mysql_server_name="172.16.172.235"; //数据库服务器名称
		$mysql_username="root"; // 连接数据库用户名
		$mysql_password="root"; // 连接数据库密码
		$mysql_database="snslist_db"; // 数据库的名字
		
		// 连接到数据库
		$conn=mysql_connect($mysql_server_name, $mysql_username,$mysql_password);
		mysql_select_db($mysql_database,$conn);
		mysql_query("SET NAMES UTF8");
		// 从表中提取信息的sql语句
		$strsql="SELECT * FROM `snslist`";
		// 执行sql查询
		$result=mysql_query($strsql);
		// 获取查询结果
		while($row = mysql_fetch_array($result))
		{
			$return[] = array(
				'serverid'		=> $row['serverid'],
				'servername'	=> $row['servername'],
				'serverurl'		=> $row['serverurl']
			);
		}
		//echo json_encode($return);
		// 释放资源
		mysql_free_result($result);
		// 关闭连接
		mysql_close($conn);
		return $return;
	}
}
?>