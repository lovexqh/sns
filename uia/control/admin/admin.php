<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: admin.php 1059 2011-03-01 07:25:09Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class control extends adminbase {

	function __construct() {
		$this->control();
	}

	function control() {
		parent::__construct();
		$this->load('user');
		$this->check_priv();
		if(!$this->user['isfounder'] && !$this->user['allowadminbadword']) {
			$this->message('no_permission_for_this_module');
		}

		$this->load('teacher');
	}

	function onls() {

		//include_once UC_ROOT.'view/default/admin.lang.php';
		$status = 0;
		if(!empty($_POST['addname']) && $this->submitcheck()) {
			$addname = getgpc('addname', 'P');
			$this->view->assign('addname', $addname);
			$uid = $this->db->result_first("SELECT uid FROM ".UC_DBTABLEPRE."members WHERE username='$addname'");
			if($uid) {
				$adminuid = $this->db->result_first("SELECT uid FROM ".UC_DBTABLEPRE."admins WHERE username='$addname'");
				if($adminuid) {
					$status = -1;
				} else {
					$allowadminsetting = getgpc('allowadminsetting', 'P');
					$allowadminapp = getgpc('allowadminapp', 'P');
					$allowadminuser = getgpc('allowadminuser', 'P');
					$allowadminbadword = getgpc('allowadminbadword', 'P');
					$allowadmincredits = getgpc('allowadmincredits', 'P');
					$allowadmintag = getgpc('allowadmintag', 'P');
					$allowadminpm = getgpc('allowadminpm', 'P');
					$allowadmindomain = getgpc('allowadmindomain', 'P');
					$allowadmindb = getgpc('allowadmindb', 'P');
					$allowadminnote = getgpc('allowadminnote', 'P');
					$allowadmincache = getgpc('allowadmincache', 'P');
					$allowadminlog = getgpc('allowadminlog', 'P');
					$this->db->query("INSERT INTO ".UC_DBTABLEPRE."admins SET
						uid='$uid',
						username='$addname',
						allowadminsetting='$allowadminsetting',
						allowadminapp='$allowadminapp',
						allowadminuser='$allowadminuser',
						allowadminbadword='$allowadminbadword',
						allowadmincredits='$allowadmincredits',
						allowadmintag='$allowadmintag',
						allowadminpm='$allowadminpm',
						allowadmindomain='$allowadmindomain',
						allowadmindb='$allowadmindb',
						allowadminnote='$allowadminnote',
						allowadmincache='$allowadmincache',
						allowadminlog='$allowadminlog'");
					$insertid = $this->db->insert_id();
					if($insertid) {
						$this->writelog('admin_add', 'username='.htmlspecialchars($addname));
						$status = 1;
					} else {
						$status = -2;
					}
				}
			} else {
				$status = -3;
			}
		}

		if(!empty($_POST['editpwsubmit']) && $this->submitcheck()) {
			$oldpw = getgpc('oldpw', 'P');
			$newpw = getgpc('newpw', 'P');
			$newpw2 = getgpc('newpw2', 'P');
			if(UC_FOUNDERPW == md5(md5($oldpw).UC_FOUNDERSALT)) {
				$configfile = UC_ROOT.'./data/config.inc.php';
				if(!is_writable($configfile)) {
					$status = -4;
				} else {
					if($newpw != $newpw2) {
						$status = -6;
					} else {
						$config = file_get_contents($configfile);
						$salt = substr(uniqid(rand()), 0, 6);
						$md5newpw = md5(md5($newpw).$salt);
						$config = preg_replace("/define\('UC_FOUNDERSALT',\s*'.*?'\);/i", "define('UC_FOUNDERSALT', '$salt');", $config);
						$config = preg_replace("/define\('UC_FOUNDERPW',\s*'.*?'\);/i", "define('UC_FOUNDERPW', '$md5newpw');", $config);
						$fp = @fopen($configfile, 'w');
						@fwrite($fp, $config);
						@fclose($fp);
						$status = 2;
						$this->writelog('admin_pw_edit');
					}
				}
			} else {
				$status = -5;
			}
		}

		$this->view->assign('status', $status);

		if(!empty($_POST['delete'])) {
			$uids = $this->implode(getgpc('delete', 'P'));
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."admins WHERE uid IN ($uids)");
		}

		$page = max(1, getgpc('page'));
		$ppp  = 15;
		$totalnum = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."admins");
		$start = $this->page_get_start($page, $ppp, $totalnum);
		$userlist = $this->db->fetch_all("SELECT m.* FROM ".UC_DBTABLEPRE."admins a LEFT JOIN ".UC_DBTABLEPRE."members m USING(uid) LIMIT $start, $ppp");
//		$userlist = $this->db->fetch_all("SELECT a.*,m.* FROM ".UC_DBTABLEPRE."admins a LEFT JOIN ".UC_DBTABLEPRE."members m USING(uid) LIMIT $start, $ppp");
		$multipage = $this->page($totalnum, $ppp, $page, 'admin.php?m=admin&a=admin');
		if($userlist) {
			foreach($userlist as $key => $user) {
				$user['regdate'] = $this->date($user['regdate']);
				$userlist[$key] = $user;
			}
		}

		$a = getgpc('a');
		$this->view->assign('a', $a);
		$this->view->assign('multipage', $multipage);
		$this->view->assign('userlist', $userlist);
		$this->view->display('admin_admin2');

	}

	function onlsM(){
		$page = max(1, getgpc('page'));
		$ppp  = 15;
		$totalnum = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."admins");
		$start = $this->page_get_start($page, $ppp, $totalnum);
		$userlist = $this->db->fetch_all("SELECT m.* FROM ".UC_DBTABLEPRE."admins a LEFT JOIN ".UC_DBTABLEPRE."members m USING(uid) LIMIT $start, $ppp");
		$multipage = $this->page($totalnum, $ppp, $page, 'admin.php?m=admin&a=lsM');
		if($userlist) {
			foreach($userlist as $key => $user) {
				$user['regdate'] = $this->date($user['regdate']);
				$userlist[$key] = $user;
			}
		}
		$this->view->assign('multipage', $multipage);
		$this->view->assign('userlist', $userlist);
		$this->view->display('admin_admin2');
	}

	function ondelete(){
		if(!empty($_POST['delete'])) {
			$uids = $this->implode(getgpc('delete', 'P'));
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."admins WHERE uid IN ($uids)");
		}
		$this->onlsM();

	}

	function onedit() {
		$uid = getgpc('uid');
		$status = 0;
		if($this->submitcheck()) {
			$allowadminsetting = getgpc('allowadminsetting', 'P');
			$allowadminapp = getgpc('allowadminapp', 'P');
			$allowadminuser = getgpc('allowadminuser', 'P');
			$allowadminbadword = getgpc('allowadminbadword', 'P');
			$allowadmintag = getgpc('allowadmintag', 'P');
			$allowadminpm = getgpc('allowadminpm', 'P');
			$allowadmincredits = getgpc('allowadmincredits', 'P');
			$allowadmindomain = getgpc('allowadmindomain', 'P');
			$allowadmindb = getgpc('allowadmindb', 'P');
			$allowadminnote = getgpc('allowadminnote', 'P');
			$allowadmincache = getgpc('allowadmincache', 'P');
			$allowadminlog = getgpc('allowadminlog', 'P');
			$this->db->query("UPDATE ".UC_DBTABLEPRE."admins SET
				allowadminsetting='$allowadminsetting',
				allowadminapp='$allowadminapp',
				allowadminuser='$allowadminuser',
				allowadminbadword='$allowadminbadword',
				allowadmincredits='$allowadmincredits',
				allowadmintag='$allowadmintag',
				allowadminpm='$allowadminpm',
				allowadmindomain='$allowadmindomain',
				allowadmindb='$allowadmindb',
				allowadminnote='$allowadminnote',
				allowadmincache='$allowadmincache',
				allowadminlog='$allowadminlog'
				WHERE uid='$uid'");
			$status = $this->db->errno() ? -1 : 1;
			$this->writelog('admin_priv_edit', 'username='.htmlspecialchars($admin));
		}
		$admin = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."admins WHERE uid='$uid'");
		$this->view->assign('uid', $uid);
		$this->view->assign('admin', $admin);
		$this->view->assign('status', $status);
		$this->view->display('admin_admin');
	}



	function onAdd(){

		$this->load('dept');
		$deptlist=$_ENV['dept']->genDepTreeAll();
		$this->view->assign('deptlist', $deptlist);

		$this->view->display('admin_deptManager');
	}

	function onAddSubmit(){

		$success = $this->add_admins();
		if($success==-1){
			$errMsg="数据库异常!";
			$this->message($errMsg,"admin.php?m=admin&a=ls");
			return;
		}

		//使用如下语句,可以跳出iFrame框架,实现页面跳转
		echo "<script language='javascript'>";
		echo 'parent.location="admin.php?m=admin&a=lsM"';
		echo "</script>";
		exit;

	}


	function onUserList(){

		$this->showData();

	}
	/**
	 * 点击部门树时的用户信息查询
	 */
	function onTreeNav(){
		$deptId=$_GET['deptId'];
		$_SESSION['departmentId']=$deptId;//将部门id存入session,做为其他查询时的默认条件需要使用
		$deptName=$_GET['deptName'];//部门名称
		$_SESSION['departmentName']=$deptName;//将部门名称存入session,做为其他查询时结果的显示使用
		$this->onSearch();
	}

	/**
	 * 用户信息查询
	 */
	function onSearch(){
		$condition =$this->searchCondition();											//查询符合条件的记录总数
		$this->showData($condition);

	}

	private function showData($condition=''){
//		$totalnum =$this->get_total_num($condition);											//查询符合条件的记录总数
//		$teacherList = $this->get_teacher_aPage($_GET['page'], UC_PPP, $totalnum,$condition);	//获得一页数据,其中,$_GET['page']参数由分页控制器提供
//		$url='admin.php?m=admin&a=NextPage';
//		$multipage = $this->page($totalnum, UC_PPP, $_GET['page'], $url);//生成分页控制器,其中会自动包含$_GET['page']参数

		$userList = $this->get_teacher_List($condition);

		$deptName=$_SESSION['departmentName'];//部门名称

		$this->view->assign('deptName', $deptName);
		$this->view->assign('multipage', $multipage);
		$this->view->assign('userList', $userList);
		$_SESSION['condition']=$condition;//将查询条件存入session,点击"下一页"时需要使用
		$this->view->display('admin_Manager');
	}
	/**
	 * 批量将用户设置为管理员
	 */
	function add_admins() {
		$IDsarr=$_POST['userList'];
		for ($index = 0; $index < sizeof($IDsarr); $index++) {
			$id=$IDsarr[$index];
			$idExist=$this->isIDexist($id);
			if(!$idExist){
				$status = $this->addAdmin($id);
				if($status==-1){
					return $status;
				}
			}else{
				return -2;//管理员已经存在
			}
		}
	}
	/*
	 * 根据用户id,将用户设置为管理员
	 */
	private function addAdmin($id){
			$sql="SELECT * FROM ".UC_DBTABLEPRE."members WHERE uid='$id'";
			$member=$this->db->fetch_first($sql);
			$userName=$member['username'];

			$sql="INSERT INTO ".UC_DBTABLEPRE."admins VALUES($id,'$userName',1,1,1,1,1,1,1,1,1,1,1,1)";
			$this->db->query($sql);
			$status = $this->db->errno() ? -1 : 1;
			return $status;
	}
	/*
	 * 判断用户id是否已经存在于管理员表中
	 */
	private function isIDexist($id){
			$sql="SELECT * FROM ".UC_DBTABLEPRE."admins WHERE uid='$id'";

			$rt=$this->db->fetch_first($sql);
			if($rt){
				return true;
			}else{
				return false;
			}
	}

	/**
	 * 查询符合条件的记录条数
	 */
    function get_total_num($sql = '') {

		$sql="SELECT COUNT(*) FROM (SELECT m.uid,t.* FROM uc_members m , uc_teacherinfo t where m.identityType=2 and m.identityID=t.identityID) userL ";
		$data = $this->db->result_first($sql);
		return $data;
	}

	/**
	 * 分页查询
	 */
    function get_teacher_aPage($crtPage,$lineApage,$totalnum,$sqladd) {
//		var_dump("parm==".$crtPage.$lineApage.$totalnum.$sqladd);
//		$start = $this->base->page_get_start($crtPage, $lineApage, $totalnum);
//		var_dump("start==".$start);
		$sql="SELECT m.uid,t.* FROM uc_members m , uc_teacherinfo t where m.identityType=2 and m.identityID=t.identityID ";
		$sql.="$sqladd ORDER BY identityid DESC";
//		$sql.="$sqladd ORDER BY identityid DESC LIMIT $start, $lineApage";
		var_dump("sql==".$sql);

		$data = $this->db->fetch_all($sql);

		return $data;
	}

    function get_teacher_List($sqladd) {
    	if(!$sqladd){
    		$sqladd="WHERE 1=1 ";
    	}

		$sql="SELECT m.uid,t.* FROM uc_members m , uc_teacherinfo t ";
		$sql.="$sqladd ";
		$sql.=" AND m.identityType=2 AND m.identityID=t.identityID  ";
		$sql.="ORDER BY identityid DESC";

		$data = $this->db->fetch_all($sql);

		return $data;
	}

	/**
	 * 条件查询老师信息
	 */
	function searchCondition(){
		$searchCol=array(
			"gh",			//工号
			"xm",			//姓名
			"sfzjh"			//身份证号
		);

		$searchSql=genSearchSql($searchCol,true);

		$deptId=$_SESSION['departmentId'];//从session中获取部门id,做为查询条件
		if($deptId){
			$searchSql.=" And depid='$deptId'";
		}
		return $searchSql;

	}


}

?>