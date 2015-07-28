<?php
/**
 +------------------------------------------------------------------------------
 * 权限类型与用户关系模型
 +------------------------------------------------------------------------------
 * @category	ESN （应用名称）
 * @package		ESN （类的路径，从Lib开始：例 Lib/Model）
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-4-11 下午02:56:45
 +------------------------------------------------------------------------------
 */
class SetManagerModel extends Model{
		var $tableName = 'class_setmanager';
	/**
	 +----------------------------------------------------------
	 * 获取教师与学生权限信息公用方法
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-4-10
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-10 下午02:08:27
	 +----------------------------------------------------------
	 */
	public  function _power($classid,$db_prefix){
		$power = M('')->field('setpower.identityid as identityid,power.userType as type')
					->table($db_prefix.'class_setmanager as setpower,'.$db_prefix.'class_manager as power')
					->where('setpower.mid=power.id and setpower.classid='.$classid)
					->order('setpower.identityid ASC')
					->findall();
		//var_dump($power);
    	foreach ($power as $v){
    		if($v['type']==2){
    			$date['tea_pos'][$v['identityid']]['identityid'] = $v['identityid'];
	   	 		$date['tea_pos'][$v['identityid']]['mid'] = M('class_setmanager')->field('mid')->where('identityid='.$v['identityid'])->findAll();	
    		}elseif ($v['type']==3){
    			$date['stu_pos'][$v['identityid']]['identityid'] = $v['identityid'];
	   	 		$date['stu_pos'][$v['identityid']]['mid'] = M('class_setmanager')->field('mid')->where('identityid='.$v['identityid'])->findAll();	
    		}
	   	}
	   	return $date;
	}
   }