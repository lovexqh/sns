<?php
/**
 +------------------------------------------------------------------------------
 * 电台直播模型
 +------------------------------------------------------------------------------
 * @category	电台直播
 * @package		Lib/Model
 * @author		小伟 <ericyang@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-5 下午04:31:06
 +------------------------------------------------------------------------------
 */
class LiveModel extends Model {
	var $tableName	=	'live';
	/***
	 * deleteLive
	 * 删除直播频道
	 */
	/**
	 +----------------------------------------------------------
	 * 删除直播频道
	 +----------------------------------------------------------
	 * @param string $pids 频道ID;integer $uid 用户ID;bool $isAdmin 是否管理员
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-3-5
	 +----------------------------------------------------------
	 */
	function deleteLive($pids,$uid,$isAdmin=0) {
		//解析ID成数组
		if(!is_array($pids)){
			$pids	=	explode(',',$pids);
		}
		//非管理员只能删除自己的图片
		if(!$isAdmin){
			$map['userId']	=	$uid;
		}
		$map['id'] = array('in',$pids);
		$result	= $this->where($map)->delete();
		if($result){
			return true;
		}else{
			return false;
		}
	}
}
?>