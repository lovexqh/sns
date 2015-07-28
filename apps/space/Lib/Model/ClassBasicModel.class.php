<?php
/**
 +------------------------------------------------------------------------------
 * ESN 班级空间班级基础信息模型 Model
 +------------------------------------------------------------------------------
 * @category	ESN ClassInfoModel
 * @package		ESN Lib/Model
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午10:48:18
 +------------------------------------------------------------------------------
 */
class ClassBasicModel extends Model{
		var $tableName = 'class';
	/**
	 +----------------------------------------------------------
	 * 获取班级基础信息（公共方法）
	 +----------------------------------------------------------
	 * @param	$data['uc_class'] UC班级基础信息
	 * @param	$data['uc_classcount'] UC班级男女生数量
	 * @param	$data['sns_class']	SNS班级基础信息
	 * @return	array data 班级基础信息 
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午03:12:12
	 +----------------------------------------------------------
	 */
	public function _classInfo($classid)
	{
		$data['uc_class']=uc_class_get_id($classid);
		$data['uc_classcount']=uc_studentcount_get_id($classid);
    	$data['sns_class']=D('ClassBasic')->where('classid='.$classid)->find();
		return $data;
	}
}