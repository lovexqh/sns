<?php
 /**
 +------------------------------------------------------------------------------
 * ESN 班级空间课程表模型 Model
 +------------------------------------------------------------------------------
 * @category	ESN CourseModel
 * @package		ESN Lib/Model
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午10:48:18
 +------------------------------------------------------------------------------
 */
class CourseModel extends Model{
		var $tableName = 'class_course';
	/**
     +----------------------------------------------------------
     * 班级课程表信息（公共方法）
     +----------------------------------------------------------
     * @param	array reszzx 早自习
     * @param	array reszw	上午课程
     * @param	array resxw	下午课程
     * @param	array reswzx 晚自习课程
     * @return	array result 完成课程信息
     * @author	小朱 2013-3-4
     +----------------------------------------------------------
     * 创建时间：	2013-3-4 下午01:21:57
     +----------------------------------------------------------
     */
	public  function _course($classid)
	{	
		$result['zzx'] = D('Course')->field('festival,weekday,subjectid')->where('quantum=1 and classid='.$classid)->findall();
		$result['zw'] = D('Course')->field('festival,weekday,subjectid')->where('quantum=2 and classid='.$classid)->findall();
		$result['xw'] = D('Course')->field('festival,weekday,subjectid')->where('quantum=3 and classid='.$classid)->findall();
		$result['wzx'] = D('Course')->field('festival,weekday,subjectid')->where('quantum=4 and classid='.$classid)->findall();
		$reszzx=array();//早自习
	   	foreach($result['zzx'] as $k=>$vo){
			$reszzx[$vo['festival']][$vo['weekday']]=$vo['subjectid'];
	   	}
	   	$reszw=array();//中午课程
	   	foreach($result['zw'] as $k=>$vo){
		 	$reszw[$vo['festival']][$vo['weekday']]=$vo['subjectid'];
	   	}
	   	$resxw=array();//下午课程
	   	foreach($result['xw'] as $k=>$vo){
		 	$resxw[$vo['festival']][$vo['weekday']]=$vo['subjectid'];
	   	}
	   	$reswzx=array();//晚自习课程
	   	foreach($result['wzx'] as $k=>$vo){
		 	$reswzx[$vo['festival']][$vo['weekday']]=$vo['subjectid'];
	   	}
	   	$result['zzx']=$reszzx;
	   	$result['zw']=$reszw;
	   	$result['xw']=$resxw;
	   	$result['wzx']=$reswzx;
		return $result;
	}
}