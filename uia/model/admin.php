<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: admin.php 1098 2011-05-19 01:28:17Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

class adminbase extends base {

	var $cookie_status = 1;

	function __construct() {
		$this->adminbase();
	}

	function adminbase() {
		parent::__construct();
		$this->cookie_status = isset($_COOKIE['sid']) ? 1 : 0;
		$sid = $this->cookie_status ? getgpc('sid', 'C') : rawurlencode(getgpc('sid', 'R'));
		$this->view->sid = $this->sid_decode($sid) ? $sid : '';
		
		$this->view->assign('sid', $this->view->sid);
		$this->view->assign('iframe', getgpc('iframe'));
		$a = getgpc('a');
		if(!(getgpc('m') =='user' && ($a == 'login' || $a == 'logout'))) {
			$this->check_priv();
		}
	}

	function check_priv() {
		$username = $this->sid_decode($this->view->sid);
		$ts_logged_user = $_COOKIE['TS_LOGGED_UIA_USER'];
		//判断如果是空的话，从TS_LOGGED_USER去得到一下，如果这个有，就获取，没有还空
		if(empty($username) && !empty($ts_logged_user)){
			$this->load('common');
			$ts_user = $_ENV['common']->jiemi($ts_logged_user);
			$uc_uid = explode('.', $ts_user);
			$uc_str = "SELECT email FROM ". UC_DBTABLEPRE ."members WHERE uid = ".$uc_uid[2];
			$uc_user = $this->db->fetch_first($uc_str);
			if($uc_user){
				$username = $uc_user['email'];
			}
		}
		if(empty($username)) {
			header('Location: '.UC_API.'/admin.php?m=user&a=login&iframe='.getgpc('iframe', 'G').($this->cookie_status ? '' : '&sid='.$this->view->sid));
			exit;
		} else {
			$this->user['isfounder'] = $username == 'Administrator' ? 1 : 0;
			if(!$this->user['isfounder']) {
				
				/**
				 * 新版登录操作
				 */
				$sysadmin = $this->db->fetch_first("SELECT * FROM ". UC_DBTABLEPRE ."sysadmin WHERE username = '$username'");
				if(empty($sysadmin)) {
					header('Location: '.UC_API.'/admin.php?m=user&a=login&iframe='.getgpc('iframe', 'G').($this->cookie_status ? '' : '&sid='.$this->view->sid));
					exit;
				} else {
					/**
						得到用户对应的导航栏目 - 开始
					 */
					$prvSql = "SELECT a.roleID,a.roleExtend,a.appID,b.id as aid,b.appName,b.appAlias,b.appEntry,b.pid FROM ". UC_DBTABLEPRE ."uia_r_role_func a,". UC_DBTABLEPRE ."uia_func b WHERE a.roleID IN ( SELECT roleID FROM `uc_sysadmin` a,uc_uia_r_user_role b WHERE a.id = b.uid AND a.username = '$username') AND a.appID = b.id";
					$prvlist = $this->db->fetch_all($prvSql);
					$power = $pid  = array();
					foreach ($prvlist as $key=>$val){
						$power[$val['appName']] = $val;
						array_push($pid, $val['pid']);
					}
					if($pid){
						$pidstr = implode(',', array_unique($pid)).',6';
					}else{
						$pidstr = '6';
					}
					
					$funsql = "SELECT b.id as aid,b.appName,b.appAlias,b.appEntry,b.pid FROM ". UC_DBTABLEPRE ."uia_func b WHERE b.id IN ($pidstr)";
					$funlist = $this->db->fetch_all($funsql);
					foreach ($funlist as $key=>$val){
						$power[$val['appName']] = $val;
					}
					/**
					 	得到用户对应的导航栏目 - 结束
					 */
					$this->user = array();
					foreach ($power as $key=>$val){
						$this->user['allowadmin'.$val['appName']] = 1;
					}
					$this->user['isfounder'] = $username == 'Administrator' ? 1 : 0;
					$this->user['username'] = $username;
					$this->user['admin'] = 1;
					$this->user['power'] = $power;
					$this->view->sid = $this->sid_encode($username);
					$this->setcookie('sid', $this->view->sid, 86400);
				}
				
			} else {
				
				$power = array();
				$funsql = "SELECT b.id as aid,b.appName,b.appAlias,b.appEntry,b.pid FROM ". UC_DBTABLEPRE ."uia_func b ORDER BY b.id ASC";
				$funlist = $this->db->fetch_all($funsql);
				foreach ($funlist as $key=>$val){
					$power[$val['appName']] = $val;
				}
				$this->user = array();
				foreach ($power as $key=>$val){
					$this->user['allowadmin'.$val['appName']] = 1;
				}
				$this->user['isfounder'] = $username == 'Administrator' ? 1 : 0;
				$this->user['username'] = 'Administrator';
				$this->user['admin'] = 1;
				$this->user['power'] = $power;
				$this->view->sid = $this->sid_encode($this->user['username']);
				$this->setcookie('sid', $this->view->sid, 86400);
			}
			$this->view->assign('user', $this->user);
		}
	}

	function is_founder($username) {
		return $this->user['isfounder'];
	}

	function writelog($action, $extra = '') {
		$log = htmlspecialchars($this->user['username']."\t".$this->onlineip."\t".$this->time."\t$action\t$extra");
		$logfile = UC_ROOT.'./data/logs/'.gmdate('Ym', $this->time).'.php';
		if(@filesize($logfile) > 2048000) {
			PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
			$hash = '';
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
			for($i = 0; $i < 4; $i++) {
				$hash .= $chars[mt_rand(0, 61)];
			}
			@rename($logfile, UC_ROOT.'./data/logs/'.gmdate('Ym', $this->time).'_'.$hash.'.php');
		}
		if($fp = @fopen($logfile, 'a')) {
			@flock($fp, 2);
			@fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>', '<?php'), '', $log)."\n");
			@fclose($fp);
		}
	}

	function fetch_plugins() {
		$plugindir = UC_ROOT.'./plugin';
		$d = opendir($plugindir);
		while($f = readdir($d)) {
			if($f != '.' && $f != '..' && is_dir($plugindir.'/'.$f)) {
				$pluginxml = $plugindir.$f.'/plugin.xml';
				$plugins[] = xml_unserialize($pluginxml);
			}
		}
	}

	function _call($a, $arg) {
		if(method_exists($this, $a) && $a{0} != '_') {
			$this->$a();
		} else {
			exit('Method does not exists');
		}
	}

	function sid_encode($username) {
		$ip = $this->onlineip;
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$authkey = md5($ip.$agent.UC_KEY);
		$check = substr(md5($ip.$agent), 0, 8);
		return rawurlencode($this->authcode("$username\t$check", 'ENCODE', $authkey, 1800));
	}

	function sid_decode($sid) {
		$ip = $this->onlineip;
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$authkey = md5($ip.$agent.UC_KEY);
		$s = $this->authcode(rawurldecode($sid), 'DECODE', $authkey, 1800);
		if(empty($s)) {
			return FALSE;
		}
		@list($username, $check) = explode("\t", $s);
		if($check == substr(md5($ip.$agent), 0, 8)) {
			return $username;
		} else {			
			return FALSE;
		}
	}

}

?>