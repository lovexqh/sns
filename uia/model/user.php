<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class usermodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->usermodel($base);
	}

	function usermodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function getIdentityById($id){
		return $this->db->fetch_first("SELECT IdentityID,IdentityName FROM ".UC_DBTABLEPRE."identity WHERE IdentityID='$id'");
	}
	
	function get_user_list_grid($keyvalue="",$page=0,$pagesize=50){
		$sql = "";
		if($keyvalue != ''){
			$sql .= " WHERE (m.username like ('%$keyvalue%') or m.realname like('%$keyvalue%') or m.email like('%$keyvalue%'))";
		}
		$index = $page * $pagesize;
		$sql1 = "SELECT count(*) FROM ".UC_DBTABLEPRE."members m LEFT JOIN ".UC_DBTABLEPRE."role r ON m.RoleID=r.RoleID LEFT JOIN ".UC_DBTABLEPRE."dept d ON m.DeptID=d.DeptID".$sql;
		$sum = $this->db->result_first($sql1);
		$data['total'] = $sum;
		$sql2 = "SELECT * FROM ".UC_DBTABLEPRE."members m LEFT JOIN ".UC_DBTABLEPRE."role r ON m.RoleID=r.RoleID LEFT JOIN ".UC_DBTABLEPRE."dept d ON m.DeptID=d.DeptID".$sql." order by uid DESC LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql2);
		return $data;
	}
	
	function upUserByArray($userlist,$role,$data){
		$uid = $userlist['uid'];
		unset($userlist['uid']);
		$userlist['regdate'] = $this->base->time;
		$userlist['salt'] =  substr(uniqid(rand()), 0, 6);
		$questionid = $userlist['rmrecques'];
		$userlist['regip'] = empty($regip) ? $this->base->onlineip : $regip;
		$password = $userlist['password'];
		$userlist['password'] = md5(md5($password).$salt);
		$rmrecques = $userlist['rmrecques'];
		if($rmrecques){
			$userlist['secques'] = "";
		}
		unset($userlist['rmrecques']);
		if($uid == 0 && $data['name_s'] == 1 && $data['pass_s'] == 1 && $data['email_s'] == 1 && $data['real_s'] == 1){
			
			$instr = "INSERT INTO ".UC_DBTABLEPRE."members (";
			foreach($userlist as $key=>$value){
				if($key == "secques"){
					$keysql .= "$key";
					$valuesql .= "'$value'";
				}
				else{
					$keysql .= "$key,";
					$valuesql .= "'$value',";
				}
			}
			$instr .= $keysql.") VALUES (".$valuesql.")";
			$this->db->query($instr);
			$roles = explode(",",$role);
// 			print_r($roles);
// 			exit;
			$instr_r = "INSERT INTO ".UC_DBTABLEPRE."r_user_role (uid,roleid) VALUES(";
			$userid = mysql_insert_id();
			$num_r = count($roles);
			for($i=0;$i<$num_r;$i++){
				$sql = "";
				$instr_rs = "";
				$sql .= $roles[$i];
				$instr_rs .= $instr_r.$userid.",".$sql.")";
				$this->db->query($instr_rs);
			}
		}
		else if($data['name_s'] == 1 && $data['pass_s'] == 1 && $data['email_s'] == 1 && $data['real_s'] == 1){
			$upstr = "UPDATE ".UC_DBTABLEPRE."members SET ";
			foreach($userlist as $key=>$value){
				if($key == "secques"){
					$sql .= $key."='".$value."'";
				}
				else{
					$sql .= $key."='".$value."',";
				}
			}
			$upstr .= $sql." WHERE uid=$uid";
			$this->db->query($upstr);
		}
	}
	
	function delUserById($uid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."members WHERE uid IN ($uid)";
		$this->db->query($delstr);
	}
	
	function getAppByUser($userid){
		$app = $this->get_user_appList($userid);
		$data['data'] = $app;
		return $data;
	}
	
	/*
	 * 根据用户角色,获得用户的应用功能点列表
	*/
	function get_user_appList($uid) {
		$sql = "SELECT DISTINCT a.App_alias, group_concat(a.RoleExtend SEPARATOR ',') AS RoleExtend FROM (SELECT DISTINCT a.App_alias,r.RoleExtend ,u.uid FROM uc_r_user_role u,uc_r_role_func r,uc_app a WHERE u.RoleID=r.RoleID AND r.App_name=a.App_name UNION SELECT DISTINCT a.App_alias,r.RoleExtend ,m.uid FROM uc_r_identity_role i,uc_r_role_func r,uc_app a, uc_members m WHERE i.RoleID=r.RoleID AND r.App_name=a.App_name AND m.identityType=i.identityid) a WHERE a.uid='$uid' GROUP BY a.App_alias";
		$data = $this->db->fetch_all("$sql");
		return $data;
	}
	
	function check_usernameexists($username,$userid) {
		$data = $this->db->result_first("SELECT username FROM ".UC_DBTABLEPRE."members WHERE username='$username' AND uid NOT IN($userid)");
		return $data;
	}
	
	function check_username($username) {
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = $this->dstrlen($username);
		if($len > 15 || $len < 3 || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $username)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function check_emailformat($email) {
		return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
	}
	
	function check_emailaccess($email) {
		$setting = $this->base->get_setting(array('accessemail', 'censoremail'));
		$accessemail = $setting['accessemail'];
		$censoremail = $setting['censoremail'];
		$accessexp = '/('.str_replace("\r\n", '|', preg_quote(trim($accessemail), '/')).')$/i';
		$censorexp = '/('.str_replace("\r\n", '|', preg_quote(trim($censoremail), '/')).')$/i';
		if($accessemail || $censoremail) {
			if(($accessemail && !preg_match($accessexp, $email)) || ($censoremail && preg_match($censorexp, $email))) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return TRUE;
		}
	}
	
	function check_emailexists($email, $userid = '') {
		$username = $this->db->result_first("SELECT username FROM ".UC_DBTABLEPRE."members WHERE uid=$userid");
		$sqladd = $username !== '' ? "AND username<>'$username'" : '';
		$email = $this->db->result_first("SELECT email FROM  ".UC_DBTABLEPRE."members WHERE email='$email' $sqladd");
		return $email;
	}
	
	
//--------------------------------分割线-------------------------------------------------------------------
	function get_user_by_uid($uid) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."members WHERE uid='$uid'");
		return $arr;
	}

	function get_user_by_username($username) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."members WHERE username='$username'");
		return $arr;
	}

	function get_user_by_email($email) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."members WHERE email='$email'");
		return $arr;
	}

	

	function dstrlen($str) {
		if(strtolower(UC_CHARSET) != 'utf-8') {
			return strlen($str);
		}
		$count = 0;
		for($i = 0; $i < strlen($str); $i++){
			$value = ord($str[$i]);
			if($value > 127) {
				$count++;
				if($value >= 192 && $value <= 223) $i++;
				elseif($value >= 224 && $value <= 239) $i = $i + 2;
				elseif($value >= 240 && $value <= 247) $i = $i + 3;
		    	}
	    		$count++;
		}
		return $count;
	}

	function check_mergeuser($username) {
		$data = $this->db->result_first("SELECT count(*) FROM ".UC_DBTABLEPRE."mergemembers WHERE appid='".$this->base->app['appid']."' AND username='$username'");
		return $data;
	}

	function check_usernamecensor($username) {
		$_CACHE['badwords'] = $this->base->cache('badwords');
		$censorusername = $this->base->get_setting('censorusername');
		$censorusername = $censorusername['censorusername'];
		$censorexp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($censorusername = trim($censorusername)), '/')).')$/i';
		$usernamereplaced = isset($_CACHE['badwords']['findpattern']) && !empty($_CACHE['badwords']['findpattern']) ? @preg_replace($_CACHE['badwords']['findpattern'], $_CACHE['badwords']['replace'], $username) : $username;
		if(($usernamereplaced != $username) || ($censorusername && preg_match($censorexp, $username))) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	
	function check_usernoexists($UserNO) {
		$data = $this->db->result_first("SELECT UserNO FROM ".UC_DBTABLEPRE."members WHERE UserNO='$UserNO'");
		return $data;
	}

	

	

	

	function check_login($username, $password, &$user) {
		$user = $this->get_user_by_username($username);
		if(empty($user['username'])) {
			return -1;
		} elseif($user['password'] != md5(md5($password).$user['salt'])) {
			return -2;
		}
		return $user['uid'];
	}

	function add_user($username, $password, $email,$RealName,$RoleID,$DeptID,$UserNO, $uid = 0, $questionid = '', $answer = '', $regip = '') {
		$regip = empty($regip) ? $this->base->onlineip : $regip;
		$salt = substr(uniqid(rand()), -6);
		$password = md5(md5($password).$salt);
		$sqladd = $uid ? "uid='".intval($uid)."'," : '';
		$sqladd .= $questionid > 0 ? " secques='".$this->quescrypt($questionid, $answer)."'," : " secques='',";
		$this->db->query("INSERT INTO ".UC_DBTABLEPRE."members SET $sqladd username='$username', password='$password', email='$email',RealName='$RealName',RoleID='$RoleID',DeptID='$DeptID',UserNO='$UserNO', regip='$regip', regdate='".$this->base->time."', salt='$salt'");
		$uid = $this->db->insert_id();
		$this->db->query("INSERT INTO ".UC_DBTABLEPRE."memberfields SET uid='$uid'");
		return $uid;
	}

	function edit_user($username, $oldpw, $newpw, $email, $ignoreoldpw = 0, $questionid = '', $answer = '') {
		$data = $this->db->fetch_first("SELECT username, uid, password, salt FROM ".UC_DBTABLEPRE."members WHERE username='$username'");

		if($ignoreoldpw) {
			$isprotected = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."protectedmembers WHERE uid = '$data[uid]'");
			if($isprotected) {
				return -8;
			}
		}

		if(!$ignoreoldpw && $data['password'] != md5(md5($oldpw).$data['salt'])) {
			return -1;
		}

		$sqladd = $newpw ? "password='".md5(md5($newpw).$data['salt'])."'" : '';
		$sqladd .= $email ? ($sqladd ? ',' : '')." email='$email'" : '';
		if($questionid !== '') {
			if($questionid > 0) {
				$sqladd .= ($sqladd ? ',' : '')." secques='".$this->quescrypt($questionid, $answer)."'";
			} else {
				$sqladd .= ($sqladd ? ',' : '')." secques=''";
			}
		}
		if($sqladd || $emailadd) {
			$this->db->query("UPDATE ".UC_DBTABLEPRE."members SET $sqladd WHERE username='$username'");
			return $this->db->affected_rows();
		} else {
			return -7;
		}
	}

	function delete_user($uidsarr) {
		$uidsarr = (array)$uidsarr;
		if(!$uidsarr) {
			return 0;
		}
		$uids = $this->base->implode($uidsarr);
		$arr = $this->db->fetch_all("SELECT uid FROM ".UC_DBTABLEPRE."protectedmembers WHERE uid IN ($uids)");
		$puids = array();
		foreach((array)$arr as $member) {
			$puids[] = $member['uid'];
		}
		$uids = $this->base->implode(array_diff($uidsarr, $puids));
		if($uids) {
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."members WHERE uid IN($uids)");
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."memberfields WHERE uid IN($uids)");
			$this->delete_useravatar($uidsarr);
			$this->base->load('note');
			$_ENV['note']->add('deleteuser', "ids=$uids");
			return $this->db->affected_rows();
		} else {
			return 0;
		}
	}

	function delete_useravatar($uidsarr) {
		$uidsarr = (array)$uidsarr;
		foreach((array)$uidsarr as $uid) {
			file_exists($avatar_file = UC_DATADIR.'./avatar/'.$this->base->get_avatar($uid, 'big', 'real')) && unlink($avatar_file);
			file_exists($avatar_file = UC_DATADIR.'./avatar/'.$this->base->get_avatar($uid, 'middle', 'real')) && unlink($avatar_file);
			file_exists($avatar_file = UC_DATADIR.'./avatar/'.$this->base->get_avatar($uid, 'small', 'real')) && unlink($avatar_file);
			file_exists($avatar_file = UC_DATADIR.'./avatar/'.$this->base->get_avatar($uid, 'big')) && unlink($avatar_file);
			file_exists($avatar_file = UC_DATADIR.'./avatar/'.$this->base->get_avatar($uid, 'middle')) && unlink($avatar_file);
			file_exists($avatar_file = UC_DATADIR.'./avatar/'.$this->base->get_avatar($uid, 'small')) && unlink($avatar_file);
		}
	}

	function get_total_num($sqladd = '') {
		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."members m $sqladd");
		return $data;
	}

	function get_list($page, $ppp, $totalnum, $sqladd) {
		$start = $this->base->page_get_start($page, $ppp, $totalnum);
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."members m LEFT JOIN ".UC_DBTABLEPRE."role r ON m.RoleID=r.RoleID LEFT JOIN ".UC_DBTABLEPRE."dept d ON m.DeptID=d.DeptID $sqladd  LIMIT $start, $ppp");
		return $data;
	}

	function get_user_list() {
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."members");
		return $data;
	}


	
	/*
	 * 获得用户的角色列表
	 */
	function get_user_roleList($uid) {
		$data = $this->db->fetch_all("SELECT u.RoleID,r.RoleName FROM uc_r_user_role u,uc_role r WHERE u.Uid='$uid' and u.RoleID=r.RoleID");
		return $data;
	}

	//根据用户名获取用户身份
	function get_user_identity($uid){
		return $this->db->fetch_all("SELECT IdentityName FROM uc_identity i, uc_members m WHERE m.uid = 64 AND i.IdentityID = m.identityID");
	}

	/*
	 * 为用户设置角色列表
	 */
	function add_user_roleList($uid,$roleList) {

		//$this->db->query("DELETE FROM ".UC_DBTABLEPRE."r_user_role WHERE Uid=$uid");//删除该用户已设置的角色列表
		//得到原来账号有的角色
		$oldrole = $this->check_user_role($uid);
		//两个数组取差集，得到最新的账号
		$rolenewList = array_diff($roleList,$oldrole);
		foreach($rolenewList as $key=>$roleId){
			$date=$this->add_user_role($uid,$roleId);
		}
		return $date;
	}

	/*
	 * 查看用户role里面有没有这个选项
	 */
	 function check_user_role($uid){
	 	$re = array();
	 	if($uid !=''){
			$roleList = $this->db->fetch_all("SELECT roleid FROM ".UC_DBTABLEPRE."r_user_role WHERE Uid=$uid");
			if(is_array($roleList)){
				foreach($roleList as $key=>$val){
					array_push($re,$val['roleid']);
				}
			}
	 	}
	 	return $re;

	 }

	/*
	 * 为用户添加角色
	 */
	function add_user_role($uid,$roleId) {
		$date=$this->db->query("INSERT INTO ".UC_DBTABLEPRE."r_user_role SET  Uid='$uid',RoleID='$roleId'");
		return $date;
	}

	function del_user_role($uid,$roleId) {
		$date=$this->db->query("DELETE FROM  ".UC_DBTABLEPRE."r_user_role WHERE  Uid='$uid' AND roleid IN ($roleId)");
		return $date;
	}


	function name2id($usernamesarr) {
		$usernamesarr = daddslashes($usernamesarr, 1, TRUE);
		$usernames = $this->base->implode($usernamesarr);
		$query = $this->db->query("SELECT uid FROM ".UC_DBTABLEPRE."members WHERE username IN($usernames)");
		$arr = array();
		while($user = $this->db->fetch_array($query)) {
			$arr[] = $user['uid'];
		}
		return $arr;
	}

	function id2name($uidarr) {
		$arr = array();
		$query = $this->db->query("SELECT uid, username FROM ".UC_DBTABLEPRE."members WHERE uid IN (".$this->base->implode($uidarr).")");
		while($user = $this->db->fetch_array($query)) {
			$arr[$user['uid']] = $user['username'];
		}
		return $arr;
	}

	function quescrypt($questionid, $answer) {
		return $questionid > 0 && $answer != '' ? substr(md5($answer.md5($questionid)), 16, 8) : '';
	}

}

?>