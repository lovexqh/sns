<?php

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1082 2011-04-07 06:42:14Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

class coursecontrol extends base {

	function __construct() {
		$this->_coursecontrol();
	}

	function _coursecontrol() {
		parent::__construct();
		$this->load('course');
	}
	/**
	 * 
	* @Title: get_course_by_uid
	* @Description: 根据用户的xh返回对应的课程信息列表
	* @return 课程信息列表
	* @author Ricker lhyfe@sohu.com
	 */
	function onget_course_by_uid(){		
		$memberid = $this->input('memberid');
		$courseList =  $_ENV['course']->get_course_by_uid($memberid);
		
		//生成课程表的算法
		$courseSet = $this->getTotalCourseNum($courseList[0]['zxs'],$courseList[0]['xszk']);
		$totalzhou = $courseSet['total'];
		$relist = $totallist = array();
		for($i=0;$i<=$totalzhou;$i++){
			foreach ($courseList as $key=>$val){				
				if($val != ''){
					$courseValue = $this->getTotalCourseNum($val['zxs'],$val['xszk']);
					$relist[$val['skxq']][$val['skjc']] = $val;
					if((int)$courseValue['data'][$i] == 0 ){
						unset($relist[$val['skxq'].$val['skjc']]);
					}
					//如果是单周的情况下
					if((($i+1)%2==1) && $val['dsz'] == 1){
						unset($relist[$val['skxq']][$val['skjc']]);
					}
					//如果是双周的情况下
					if((($i+1)%2==0) && $val['dsz'] == 2){
						unset($relist[$val['skxq']][$val['skjc']]);
					}
				}
				
			}
			if(count($relist) > 0){
				$totallist[$i] = $relist;
			}
		}		
		//print_r($relist);
		return $totallist;
	}
	
	function getTotalCourseNum($zxs,$xszk){
		$result = array();
		$len = strlen($zxs);
		//把所有的$zxs处理成“$zxs,”这个字样的
		$xszk = str_replace($zxs, $zxs.',', $xszk);
		//把所有的0处理的和$zxs一样长度的形式，比如0=》00，
		$xszk = str_replace('0', '0,', $xszk);
		$xszk = preg_replace('/,{2,5}/', ',', $xszk);
		$data= explode(",",$xszk);
		$result['data'] = $data;
		$result['total'] = count($data)-1;
		return $result;		
	}
	
}

?>