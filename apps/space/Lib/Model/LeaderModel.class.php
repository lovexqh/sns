<?php
/**
 +------------------------------------------------------------------------------
 * ESN 班级空间权限模型 Model
 +------------------------------------------------------------------------------
 * @category	ESN VisitModel
 * @package		ESN Lib/Model
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午10:48:18
 +------------------------------------------------------------------------------
 */
class LeaderModel extends Model{
		var $tableName = 'class_leader';
	
	/**
	 +----------------------------------------------------------
	 * 获取已设置班级干部公共方法
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-4-10
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-10 下午02:08:27
	 +----------------------------------------------------------
	 */
	public  function _leader($classid){
		$leaders = M('class_leader')->where('classid='.$classid)->group('identityid')->findAll();
	   	foreach ($leaders as $v){
	   		$date['leader'][$v['identityid']]['identityid'] = $v['identityid'];
	   	 	$date['leader'][$v['identityid']]['positionid'] = M('class_leader')->field('positionid')->where('identityid='.$v['identityid'])->findAll();	
	   	}
	   	return $date;
	}
	
	public function _leaderlist($classid){
		$db_prefix = C('DB_PREFIX');
		$userinfo=uc_student_get_id($classid);
		$leaders = D('Leader')->where('classid='.$classid)->group('identityid')->findAll();
	   	foreach ($leaders as $v){
	   		$data['leader'][$v['identityid']]['identityid'] = $v['identityid'];
	   		$data['leader'][$v['identityid']]['positionid'] = D('Leader')->field('positionid')->where('identityid='.$v['identityid'])->findAll();
	   		foreach($userinfo as $k=>$vo){
	   			if($v['identityid']==$vo['identityid']){
	   				$follow=M('')->field('snsuser.uid AS uid')
							->table($db_prefix."user AS snsuser LEFT JOIN ".$db_prefix."ucenter_user_link AS uc_user ON snsuser.uid=uc_user.uid")
							->where("uc_user.uc_uid=".$vo['uid'])
							->find();
	   				$data['leader'][$v['identityid']]['uid']=$follow['uid'];
	   			}
	   		}
	   	}
	   	return $data;
	}
}