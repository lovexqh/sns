<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

define('UC_ROSTER_CHECK_ROLENAME_FAILED', -1);
define('UC_ROSTER_CHECK_ROLENAME_EXISTS', -2);

define('UC_ROSTER_CHECK_ROLENAME_EXISTS', -2);
define('UC_LOGIN_SUCCEED', 0);
class control extends adminbase {


	function __construct() {
		$this->control();
	}
	function control() {
		parent::__construct();
		if(getgpc('a') != 'login' && getgpc('a') != 'logout') {
			if(!$this->user['isfounder'] && !$this->user['allowadminroster']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('roster');
	}
	function onls() {
		include_once UC_ROOT.'view/default/admin.lang.php';
		$status = 0;
		if(!empty($_POST['addrolename']) && $this->submitcheck()) 
		{  
		}
		if(!empty($_POST['filepath'])&& $this->submitcheck())
          {   
		  	$filepath = $_POST['filepath'];
		  	include_once UC_ROOT.'lib/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('utf-8');
			$filepath = iconv("UTF-8","gb2312",$filepath);
			$data->read($filepath); 
			
			error_reporting(E_ALL ^ E_NOTICE);
			for ($i = 2; $i <$data->sheets[0]['numRows']-1; $i++) { 
 
				//根据你的excel表字段的多少，改写以下代码吧！ 
				$sql = "INSERT INTO `uc_roster_student` VALUES(".
				($i-1).",'". 
				$data->sheets[0]['cells'][$i][1]."','". 
				$data->sheets[0]['cells'][$i][2]."','". 
				$data->sheets[0]['cells'][$i][3]."','". 
				$data->sheets[0]['cells'][$i][4]."','". 
				$data->sheets[0]['cells'][$i][5]."','". 
				$data->sheets[0]['cells'][$i][6]."','". 
				$data->sheets[0]['cells'][$i][7]."','". 
				$data->sheets[0]['cells'][$i][8]."','". 
				$data->sheets[0]['cells'][$i][9]."','". 
				$data->sheets[0]['cells'][$i][10]."','". 
				$data->sheets[0]['cells'][$i][11]."','". 
				$data->sheets[0]['cells'][$i][12]."','". 
				$data->sheets[0]['cells'][$i][13]."','". 
				$data->sheets[0]['cells'][$i][14]."','". 
				$data->sheets[0]['cells'][$i][15]."','". 
				$data->sheets[0]['cells'][$i][16]."','". 
				$data->sheets[0]['cells'][$i][17]."','". 
				$data->sheets[0]['cells'][$i][18]."','". 
				$data->sheets[0]['cells'][$i][19]."','". 
				$data->sheets[0]['cells'][$i][20]."','". 
				$data->sheets[0]['cells'][$i][21]."','". 
				$data->sheets[0]['cells'][$i][22]."','". 
				$data->sheets[0]['cells'][$i][23]."','". 
				$data->sheets[0]['cells'][$i][24]."','". 
				$data->sheets[0]['cells'][$i][25]."','". 
				$data->sheets[0]['cells'][$i][26]."','". 
				$data->sheets[0]['cells'][$i][27]."','". 
				$data->sheets[0]['cells'][$i][28]."','". 
				$data->sheets[0]['cells'][$i][29]."','". 
				$data->sheets[0]['cells'][$i][30]."','". 
				$data->sheets[0]['cells'][$i][31]."','". 
				$data->sheets[0]['cells'][$i][32]."','". 
				$data->sheets[0]['cells'][$i][33]."','". 
				$data->sheets[0]['cells'][$i][34]."','". 
				$data->sheets[0]['cells'][$i][35]."','". 
				$data->sheets[0]['cells'][$i][36]."')"; 
				$this->db->query($sql);
				}
				$status=3;
				
		}
		
		if(!empty($_POST['delete'])) {
			$_ENV['roster']->delete_roster($_POST['delete']);
			$status = 2;
			$this->writelog('role_delete', "uid=".implode(',', $_POST['delete']));
		}
		
		
		$rostername = getgpc('rostername', 'R');
		$rosteruserno = getgpc('rosteruserno', 'R');
		$rosteridentity = getgpc('rosteridentity', 'R');
		
		
		$sqladd = '';
		if($rostername) {
			$sqladd .= " AND name like '$rostername%'";
			$this->view->assign('rostername', $rostername);
		}
		if($rosteruserno) {
			$sqladd .= " AND userno='$rosteruserno'";
			$this->view->assign('rosteruserno', $rosteruserno);
		}
		if($rosteridentity) {
			$sqladd .= " AND identity='$rosteridentity'";
			$this->view->assign('rosteridentity', $rosteridentity);
		}
		$sqladd = $sqladd ? " WHERE 1 $sqladd" : '';
		
		$num = $_ENV['roster']->get_total_num($sqladd);
		$rosterlist = $_ENV['roster']->get_list($_GET['page'], UC_PPP, $num, $sqladd);
		foreach($rosterlist as $key => $roster) {
			$rosterlist[$key] = $roster;
		}
		$multipage = $this->page($num, UC_PPP, $_GET['page'], 'admin.php?m=roster&a=ls');
		$this->view->assign('rosterlist', $rosterlist);
		$this->view->assign('multipage', $multipage);
		
		
		$this->view->assign('status', $status);
		$a = getgpc('a');
		$this->view->assign('a', $a);
		$this->view->display('admin_roster');
	}
	
	function onedit() {
	    
		$id = getgpc('ID');
		$status = 0;
		if($this->submitcheck()) 
		{
		    $newrostername = getgpc('newrostername', 'P');
			$newrosterclassname = getgpc('newrosterclassname', 'P');
			$newrostersex = getgpc('newrostersex', 'P');
			$newrosteruserno = getgpc('newrosteruserno', 'P');
			$oldrosteruserno = getgpc('oldrosteruserno', 'P');
			
			$newrosteridentity = getgpc('newrosteridentity', 'P');
			$oldrosteridentity = getgpc('oldrosteridentity', 'P');
			
			$newrosternation = getgpc('newrosternation', 'P');
			$sqladd = '';
			if($newrostername) {
				$sqladd .= "name='$newrostername', ";
			}
			if($newrosterclassname) {
				$sqladd .= "classname='$newrosterclassname', ";
			}
			if($newrostersex) {
				$sqladd .= "sex='$newrostersex', ";
			}
			if($newrosteruserno!=$oldrosteruserno) {
			   if($_ENV['roster']->get_roster_by_userno($newrosteruserno)) 
			      {
					$this->message('admin_userno_exists');
			      }
				$sqladd .= "userno='$newrosteruserno', ";
			}
			if($newrosteridentity!=$oldrosteridentity) {
			   if($_ENV['roster']->get_roster_by_useridentity($newrosteridentity)) 
			      {
					$this->message('admin_useridentity_exists');
			      }
				$sqladd .= "identity='$newrosteridentity', ";
			}
			if($newrosternation) {
				$sqladd .= "nation='$newrosternation', ";
			}
			
			$this->db->query("UPDATE ".UC_DBTABLEPRE."roster_student SET $sqladd id='$id' WHERE id='$id'");
			$status = $this->db->errno() ? -1 : 1;
			
		}
		$roster = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."roster_student WHERE id='$id'");
		$this->view->assign('roster', $roster);
		$this->view->assign('types', $types);
		$this->view->assign('id', $id);
		$this->view->assign('status', $status);
		$this->view->display('admin_roster');
	}
	function _check_rolename($rolename) {
	   
		$rolename = addslashes(trim(stripslashes($rolename)));
		if(!$_ENV['roster']->check_rolename($rolename)) {
			return UC_ROLE_CHECK_ROLENAME_FAILED;
		} elseif($_ENV['roster']->check_rolenameexists($rolename)) {
			return UC_ROLE_CHECK_ROLENAME_EXISTS;
		}
		return 1;
	}
	
	function _check_rolename_edit($rolename,$RoleID) {
	   
		$rolename = addslashes(trim(stripslashes($rolename)));
		$RoleID = $RoleID;
		if(!$_ENV['roster']->check_rolename($rolename)) {
			return UC_ROLE_CHECK_ROLENAME_FAILED;
		} elseif($_ENV['roster']->check_rolename_edit_exists($rolename,$RoleID)) {
			return UC_ROLE_CHECK_ROLENAME_EXISTS;
		}
		return 1;
	}
	
}



?>