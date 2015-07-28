<?php
/**
 +------------------------------------------------------------------------------
 * 资源下载管理Model
 +------------------------------------------------------------------------------
 * @category	square
 * @package		Lib/Model
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:27:47
 +------------------------------------------------------------------------------
 */
class ToolDownModel extends Model{
  var $tableName	=	'tool_down';
  /**
   +----------------------------------------------------------
   * 统计下载次数
   +----------------------------------------------------------
   * @param	int $id 资源id
   * @return int $count 下载次数
   * @author	美美2013-3-5
   +----------------------------------------------------------
   * 创建时间：	2013-3-5 上午08:38:55
   +----------------------------------------------------------
   */
  public function CountNum($id){
	  
	  $count=$this->where("id=$id")->Count();
	  //echo M()->getLastSql();exit();
	  return $count;
	  
  }
  
 public function sendGift($mid,$uid,$prices,$id){
 	if($mid!=$uid)
 	{//$moneyType = getConfig('credit');//主要用来获取是积分(score)还是经验(experience)的类型
 		//$moneyType = getConfig1('credit');//使用getConfig1()能获取类型
 		//实例化用户积分操作
 		$setCredit = X('Credit');
 		//获取用户积分
 		$userCredit = $setCredit->getUserCredit($mid);
 		// var_dump($userCredit);exit;
 		if($userCredit['score']['credit']<$prices)
 		{
 			return false;
 		}
 		$setCredit->setUserCredit($mid,array('score'=>$prices),-1);//传递是积分还是经验类型，此处手动传递是积分(score)
 		$setCredit->setUserCredit($uid,array('score'=>$prices),1);
 	}
 	//更新下载表
 	$sql = "UPDATE {$this->tablePrefix}tool SET downCount=downCount+1 WHERE id='$id' LIMIT 1 ";
 	$this->execute($sql);
 	$data['id']=$id;
 	$data['uid']=$mid;
 	$data['dTime']=time();
 	$res = $this->add($data);
 	//是否更新成功下载表
 	if($res){
 		return true;
 	}else{
 		return false;
 	}
 }
}
?>