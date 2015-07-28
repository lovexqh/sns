<?php
/**
 +------------------------------------------------------------------------------
 * 资源收藏Model
 +------------------------------------------------------------------------------
 * @category	square
 * @package		Lib/Model
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:26:59
 +------------------------------------------------------------------------------
 */
class ResCollectModel extends Model{
  var $tableName	=	'resource_collect';
  
   function deleteres($pids) {
		//解析ID成数组
		if(!is_array($pids)){
			$pids	=	explode(',',$pids);
		}
		$map['c_id'] = array('in',$pids);
		$result	= $this->where($map)->delete();
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	
	

}
?>