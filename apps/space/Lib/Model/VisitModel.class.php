<?php
/**
 +------------------------------------------------------------------------------
 * ESN 班级空间访问记录模型 Model
 +------------------------------------------------------------------------------
 * @category	ESN VisitModel
 * @package		ESN Lib/Model
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午10:48:18
 +------------------------------------------------------------------------------
 */
class VisitModel extends Model{
		var $tableName = 'class_visit';
	/**
	 +----------------------------------------------------------
	 * 访客记录（公共方法）
	 +----------------------------------------------------------
	 * @param	array visit 最近一次登录者的访问记录
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午03:21:46
	 +----------------------------------------------------------
	 */
	public function _visited($uid,$classid)
	{
		$visit=D('Visit')->where('classid='.$classid.' AND visituid='.$uid)->find();
		if($visit){   
			$data['vTime'] = time();
			if(intval($data['vTime'])-intval($visit['vTime'])>=600)//若但前时间大于数据表时间600秒则重新更新
			$result = D('Visit')->where('classid='.$classid.' AND visituid='.$uid)->save($data);
		}else{
			$data['visituid']=$uid;
			$data['vTime'] = time();
			$count=D('Visit')->where('classid='.$classid)->count();
			if($count>9){
				$result = D('Visit')->where('classid='.$classid)->order('id ASC')->limit('1')->save($data);	
			}else{
				$data['classid'] =$classid;
				$result = D('Visit')->add($data);
			}
		}  
		if(!empty($result)){
			//班级总访问记录加1
			M('class')->execute('UPDATE '.C('DB_PREFIX')."class SET visitcount=visitcount+1 WHERE classid=".$classid." LIMIT 1");
		}
	}
	
	
}