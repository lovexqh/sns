<?php


/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit ('Access Denied');

define('UC_SCHOOL_CHECK_SCHOOLNAME_ILLEGAL', -1);
define('UC_SCHOOL_CHECK_SCHOOLNAME_EXISTS', -2);
define('UC_SCHOOL_EMAIL_FORMAT_ILLEGAL', -3);
define('UC_SCHOOL_TEL_FORMAT_ILLEGAL', -4);
define('UC_SCHOOL_CHECK_XXDM_EXISTS', -5);
define('UC_SCHOOL_YZBM_FORMAT_ILLEGAL', -6);
define('UC_SCHOOL_CHECK_XXDM_NULL', -7);
define('UC_SCHOOL_CHECK_XXDZ_NULL', -8);
define('UC_SCHOOL_CHECK_XZXM_NULL', -9);
define('UC_LOGIN_SUCCEED', 0);
class control extends adminbase {
	function __construct() {
		$this->control();
	}
	function control() {
		parent :: __construct();
		if (getgpc('a') != 'login' && getgpc('a') != 'logout') {
			if (!$this->user['isfounder'] && !$this->user['allowadminschool']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('school');
	}
	
	function onls(){
		$this->view->display('admin_School');
	}
	
	/**
	 *
	 * @Title: onschoollist
	 * @Description:跳转admin_school_list页面,班级信息页面
	 * @author Ricker lhyfe@sohu.com
	 */
	function onschoolList(){
		$this->clearSession();
		$this->view->display("admin_school_list");
	}
	
	/**
	 *
	 * @Title: ongetSchoolList
	 * @Description:在页面上显示sclool的信息
	 * @author Ricker lhyfe@sohu.com
	 */
	function ongetSchoolList(){
		$this->clearSession();
		$schoolid = $this->getLoginUserSchoolId();
		
		$wherear['schoolid'] = $schoolid;
		
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$schoollist = $_ENV['school']->get_school_list_grid($wherear,$keyvalue,$pageIndex,$pageSize);
		$jsonstr = json_encode($schoollist);
		print_r($jsonstr);
	}
	
	/**
	 *
	 * @Title: onSchoolAdd
	 * @Description:显示学校添加界面
	 * @author Ricker lhyfe@sohu.com
	 */
	function onSchoolAdd(){
		$schoolid = isset($_GET['schoolid']) ? $_GET['schoolid'] : 0;
		$this->view->assign('schoolid',$schoolid);
		$this->view->display("admin_school_add");
	}
	
	/**
	 *
	 * @Title: ongetXxxzm
	 * @Description:得到学校性质集合
	 * @return 学校性质集合
	 * @author Ricker lhyfe@sohu.com
	 */
	function ongetXxxzm(){
		$this->load('common');
		$xxxz = $_ENV['common']->get_XXXZ();
		print_r(json_encode($xxxz));
	}
	
	/**
	 *
	 * @Title: ongetXxbxlxm
	 * @Description:得到学校办学类型集合
	 * @return 学校办学类型集合
	 * @author Ricker lhyfe@sohu.com
	 */
	function ongetXxbxlxm(){
		$this->load('common');
		$xxbxlx = $_ENV['common']->get_BXLX();
		print_r(json_encode($xxbxlx));
	}
	
	/**
	 *
	 * @Title: ongetXxjbzm
	 * @Description:得到学校举办者型集合
	 * @return 学校举办者型集合
	 * @author Ricker lhyfe@sohu.com
	 */
	function ongetXxjbzm(){
		$this->load('common');
		$xxjbz = $_ENV['common']->get_XXJBZ();
		print_r(json_encode($xxjbz));
	}
	
	/**
	 *
	 * @Title: onupSchoolById
	 * @Description: 获取admin_school_add页面的post值,并执行添加或更新操作
	 * @author Ricker lhyfe@sohu.com
	 */
	function onupSchoolById(){
		$schoolid = isset($_GET['schoolid']) ? $_GET['schoolid'] : 0;;
		$oldxxmc = $_ENV['school']->getXxmcById($schoolid);
		$schooljson = $_POST['data'];
		$schooljson = stripslashes($schooljson);
		$school = json_decode($schooljson,true);
		$school[0]['schoolid'] = $schoolid;
		$jxnyr = $school[0]['jxnyr'];
		$rq = explode("-",$jxnyr);
		$year = $rq[0];
		$month = $rq[1];
		$day = $rq[2];
		$jxny = $year.$month;
		$xqr = $month.$day;
		$school[0]['jxny'] = $jxny;
		$school[0]['xqr'] = $xqr;
		unset($school[0]['jxnyr']);
		$schoollist = $school[0];
		$xxdm = $schoollist['xxdm'];
		$xxmc = $schoollist['xxmc'];
		$dms = $_ENV['school']->checkSchoolByXxdm($xxdm,$schoolid);
		$mcs = $_ENV['school']->checkSchoolByXxmc($xxmc,$schoolid);
		$schoollist['dms'] = $dms;
		$schoollist['mcs'] = $mcs;
		$schoollist['oldxxmc'] = $oldxxmc;
		$_ENV['school']->upSchoolByArray($schoollist);
	}
	
	/**
	 *
	 * @Title: checkSchoolByXxdm
	 * @Description: 验证学校代码
	 * @author Ricker lhyfe@sohu.com
	 */
	function oncheckSchoolByXxdm(){
		$xxdm = $_POST['xxdm'];
		$schoolid = $_GET['schoolid'];
		echo $_ENV['school']->checkSchoolByXxdm($xxdm,$schoolid);
	}
	
	function oncheckSchoolByXxmc(){
		$xxmc = $_POST['xxmc'];
		$schoolid = $_GET['schoolid'];
		echo $_ENV['school']->checkSchoolByXxmc($xxmc,$schoolid);
	}
	
	function ongetSchoolBySchoolid(){
		$schoolid = isset($_GET['schoolid']) ? $_GET['schoolid'] : 0;
		$schoollist = $_ENV['school']->getSchoolById($schoolid);
		print_r(json_encode($schoollist));
	}
	
	function ondelSchool(){
		$schoolid = isset($_POST['schoolid']) ? $_POST['schoolid'] : 0;
		$_ENV['school']->delSchoolById($schoolid);
	}
	

	function clearSession(){
		$_SESSION['LoginUserSchoolId'] = null;
	}
	
	//-----------------------------------分割--------------------------------------------------------------------

	//编辑学校信息
	function onedit() {
		$id = getgpc('ID');
		$status = 0;
		if ($_POST) {
			//学校代码
			$newxxdm = $_POST[newxxdm];
			$oldxxdm = $_POST[oldxxdm];
			//学校名称
			$newxxmc = $_POST[newxxmc];
			$oldxxmc = $_POST[oldxxmc];

			$newxxywmc = $_POST[newywmc];
			$newxxdz = $_POST[newxxdz];
			$newxxyzbm = $_POST[newyzbm];
			$newxzqhm = $_POST[newbqhm];
			$newjxny = $_POST[newjxny];
			$newxqr = $_POST[newxxqrm].'-'.$_POST[newxxqrd];
			$newxxbxlxm = $_POST[newxxbxlxm];
			$newxxzgbmm = $_POST[newxxzgbmm];
			$newfddbrh = $_POST[newfddbrh];
			$newfrzsh = $_POST[newfrzsh];
			$newxzgh = $_POST[newxzgh];
			$newxzxm = $_POST[newxzxm];
			$newdwfzrh = $_POST[newdwfzrh];
			$newzzjgm = $_POST[newzzjgm];
			$newlxdh = $_POST[newlxdh];
			$newczdh = $_POST[newczdh];
			$newdzxx = $_POST[newdzxx];
			$newzydz = $_POST[newzydz];
			$newlsyg = $_POST[newlsyg];
			$newxxbbm = $_POST[newxxbbm];
			$newsszgdwm = $_POST[newsszgdwm];
			$newszdcxlxm = $_POST[newszdcxlxm];
			$newszdjjsxm = $_POST[newszdjjsxm];
			$newszdmzsx = $_POST[newszdmzsx];
			$newxxxz = $_POST[newxxxz];
			$newxxrxnl = $_POST[newxxrxnl];
			$newczxz = $_POST[newczxz];
			$newczrxnl = $_POST[newczrxnl];
			$newgzxz = $_POST[newgzxz];
			$newzjxyym = $_POST[newzjxyym];
			$newfjxyym = $_POST[newfjxyym];
			$newzsbj = $_POST[newzsbj];

			if ($id != '') {
				//学校代码
				if ($newxxdm != $oldxxdm) {
					if ($_ENV['school']->get_school_by_xxdm($newxxdm)) {
						$this->message('admin_xxdm_exists');
					}
				}
				//名称
				if ($newxxmc != $oldxxmc) {
					if ($_ENV['school']->get_school_by_xxmc($newxxmc)) {
						$this->message('admin_xxmc_exists');
					}
					break;
				}

				$upstr = "UPDATE " . UC_DBTABLEPRE . "schoolinfo SET xxmc='$newxxmc',xxdm='$newxxdm',xxywmc='$newxxywmc',xxdz='$newxxdz',xxyzbm='$newxxyzbm',xzqhm='$newxzqhm',jxny='$newjxny',xqr='$newxqr',xxbxlxm='$newxxbxlxm', xxzgbmm='$newxxzgbmm', fddbrh='$newfddbrh',frzsh='$newfrzsh',xzgh='$newxzgh', xzxm='$newxzxm', dwfzrh='$newdwfzrh',zzjgm='$newzzjgm', lxdh='$newlxdh',czdh='$newczdh',dzxx='$newdzxx', zydz='$newzydz',lsyg='$newlsyg',xxbbm='$newxxbbm', sszgdwm='$newsszgdwm',szdcxlxm='$newszdcxlxm', szdjjsxm='$newszdjjsxm',szdmzsx='$newszdmzsx', xxxz='$newxxxz',xxrxnl='$newxxrxnl', czxz='$newczxz',czrxnl='$newczrxnl', gzxz='$newgzxz',zjxyym='$newzjxyym',fjxyym='$newfjxyym',zsbj='$newzsbj'  WHERE id='$id'";
				//echo $upstr;
				$this->db->query($upstr);
				$status = $this->db->errno() ? -1 : 1;
			} else {
				//学校代码是否已经存在(-5),是否为空(-7)
				if (($status = $this->_check_xxdm($newxxdm)) >= 0) {
					//检查学校名称是否合法(-1)，是否已经存在(-2)
					if (($status = $this->_check_schoolname($newxxmc)) >= 0) {
						//检查联系电话是否合法(-4)
						//if (($status = $this->_check_lxdh($newlxdh)) >= 0) {
							//检查邮政编码是否合法(-6)
							//if (($status = $this->_check_yzbm($newxxyzbm)) >= 0) {
								//检查电子信箱是否合法(-3)
								//if (($status = $this->_check_dzxx($newdzxx)) >= 0) {
									//检查学校地址是否为空(-8)
									//if (($status = $this->_check_xxdz($newxxdz)) >= 0) {
										//检查校长姓名是否为空(-9)
										//if (($status = $this->_check_xzxm($newxzxm)) >= 0) {
											$inestr = "INSERT INTO " .
											UC_DBTABLEPRE . "schoolinfo(xxmc,xxdm,xxywmc,xxdz,xxyzbm,xzqhm,jxny,xqr,xxbxlxm,xxzgbmm,fddbrh,frzsh,xzgh,xzxm,dwfzrh,zzjgm,lxdh,czdh,dzxx,zydz,lsyg,xxbbm,sszgdwm,szdcxlxm,szdjjsxm,szdmzsx,xxxz,xxrxnl,czxz,czrxnl,gzxz,zjxyym,fjxyym,zsbj) VALUES('$newxxmc','$newxxdm','$newxxywmc','$newxxdz','$newxxyzbm','$newxzqhm','$newjxny','$newxqr','$newxxbxlxm','$newxxzgbmm','$newfddbrh','$newfrzsh','$newxzgh','$newxzxm','$newdwfzrh','$newzzjgm','$newlxdh','$newczdh','$newdzxx','$newzydz','$newlsyg','$newxxbbm','$newsszgdwm','$newszdcxlxm','$newszdjjsxm','$newszdmzsx','$newxxxz','$newxxrxnl','$newczxz','$newczrxnl','$newgzxz','$newzjxyym','$newfjxyym','$newzsbj')";
											$this->db->query($inestr);
											$d_id = mysql_insert_id();
											$inedep ="INSERT INTO " .UC_DBTABLEPRE . "dept(DepartName,UpDeptId,schoolid) VALUES('$newxxmc','0','$d_id')";
											$this->db->query($inedep);
											$this->writelog('school_add', "xxdm=$addxxdm");
											$status = $this->db->errno() ? -1 : 2;
										//}
									//}
								//}
							//}
						//}
					}
				}


			}
		}
		//echo $status;

		$schoollist = $this->db->fetch_first("SELECT * FROM " . UC_DBTABLEPRE . "schoolinfo WHERE id='$id' AND isdel = '0'");

		//ricker add
		if(strpos($schoollist['xqr'],'-')){
			$xxqr = explode('-',$schoollist['xqr']);
		}else{
			$xxqr = explode('-','0-0');
		}
		$schoollist['xqrm'] = $xxqr[0];
		$schoollist['xqrd'] = $xxqr[1];
		//ricker end

		$this->load(common);
		//获取办学类型
		$bxlxlist = $_ENV['common']->get_BXLX();
		$this->view->assign('bxlxlist', $bxlxlist);

		//获取所在地城乡类型
		$szdcxlxlist = $_ENV['common']->get_SZDCXLX();
		$this->view->assign('szdcxlxlist', $szdcxlxlist);

		//获取所在地区经济类型
		$szdqjjlxlist = $_ENV['common']->get_SZDQJJSX();
		$this->view->assign('szdqjjlxlist', $szdqjjlxlist);

		//获取学校办别
		$xxbblist = $_ENV['common']->get_XXBB();
		$this->view->assign('xxbblist', $xxbblist);

		//获取小学入学年龄
		$xxrxnllist = $_ENV['common']->get_XXRXNL();
		$this->view->assign('xxrxnllist', $xxrxnllist);

		//获取初中入学年龄
		$czrxnllist = $_ENV['common']->get_CZRXNL();
		$this->view->assign('czrxnllist', $czrxnllist);

		$this->view->assign('schoollist', $schoollist);
		//$this->view->assign('types', $types);
		$this->view->assign('id', $id);
		$this->view->assign('status', $status);
		$a = getgpc('a');
		$this->view->assign('a', $a);
		$this->view->display('admin_school');

	}
	//学校详细信息
	function ondetail() {
		$id = getgpc('ID');
		$status = 0;
		$schoollist = $this->db->fetch_first("SELECT * FROM " . UC_DBTABLEPRE . "schoolinfo WHERE id='$id' AND isdel = '0'");
		$a = getgpc('a');
		$this->view->assign('a', $a);
		$this->view->assign('schoollist', $schoollist);
		$this->view->display('admin_school');

	}

	//验证学校代码
	function _check_xxdm($xxdm) {
		$re = 1;
		$xxdm = trim($xxdm);
		if (!$xxdm) {
			//-7
			echo ($xxdm == 0);
			$re =  UC_SCHOOL_CHECK_XXDM_NULL;
		}
		elseif ($_ENV['school']->check_xxdmexists($xxdm)) {
			//-5
			$re =  UC_SCHOOL_CHECK_XXDM_EXISTS;
		}
		return $re;
	}
	//验证学校名称是否合法，学校名称是否存在
	function _check_schoolname($schoolname) {
		$schoolname = addslashes(trim(stripslashes($schoolname)));
		if (!$_ENV['school']->check_schoolname($schoolname)) {
			//-1
			return UC_SCHOOL_CHECK_SCHOOLNAME_ILLEGAL;
		}
		elseif ($_ENV['school']->check_schoolnameexists($schoolname)) {
			//-2
			return UC_SCHOOL_CHECK_SCHOOLNAME_EXISTS;
		}
		return 1;
	}
	//核对学校地址是否为空
	function _check_xxdz($xxdz) {
		$xxdz = trim($xxdz);
		if ($xxdz == 0) {
			return UC_SCHOOL_CHECK_XXDZ_NULL;
		}
		return 1;
	}
	function _check_xzxm($xzxm) {
		$xzxm = trim($xzxm);
		if ($xzxm == 0) {
			return UC_SCHOOL_CHECK_XZXM_NULL;
		}
		return 1;
	}
	//验证联系电话
	function _check_lxdh($lxdh) {
		$lxdh = trim($lxdh);
		if (!$_ENV['school']->check_lxdh($lxdh)) {
			//-4
			return UC_SCHOOL_TEL_FORMAT_ILLEGAL;
		}
		return 1;
	}
	//验证电子信箱
	function _check_dzxx($dzxx) {
		$dzxx = trim($dzxx);
		if (!$_ENV['school']->check_dzxx($dzxx)) {
			//-3
			return UC_SCHOOL_EMAIL_FORMAT_ILLEGAL;
		}
		return 1;
	}
	//验证邮政编码
	function _check_yzbm($yzbm) {
		$yzbm = trim($yzbm);
		if (!$_ENV['school']->check_yzbm($yzbm)) {
			//-6
			return UC_SCHOOL_YZBM_FORMAT_ILLEGAL;
		}
		return 1;
	}

	function _check_schoolname_edit($schoolname, $ID) {
		$schoolname = addslashes(trim(stripslashes($schoolname)));
		$ID = $ID;
		if (!$_ENV['school']->check_schoolname($schoolname)) {
			//-1
			return UC_SCHOOL_CHECK_SCHOOLNAME_FAILED;
		}
		elseif ($_ENV['school']->check_schoolname_edit_exists($schoolname, $ID)) {
			//-2
			return UC_SCHOOL_CHECK_SCHOOLNAME_EXISTS;
		}
		return 1;
	}
}
?>