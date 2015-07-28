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
class ResDownModel extends Model{
  var $tableName	=	'resource_down';
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
	  return $count;
	  
  }
  
 public function sendGift($mid,$uid,$prices,$id){
			if($mid!=$uid)
			{
			  $moneyType = getConfig('credit');
			  //实例化用户积分操作		
			  $setCredit = X('Credit');
			  //获取用户积分
			  $userCredit = $setCredit->getUserCredit($mid);
			// var_dump($userCredit);exit;
			  if($userCredit['score']['credit']<$prices)
			   { 
				  return false;
			   }
			  $setCredit->setUserCredit($mid,array('score'=>$prices),-1);
			  $setCredit->setUserCredit($uid,array('score'=>$prices),1);
			}
			//更新下载表
			$sql = "UPDATE {$this->tablePrefix}resource SET downCount=downCount+1 WHERE id='$id' LIMIT 1 ";
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