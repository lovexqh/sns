<?php
class ToolDownModel extends BaseModel{
  var $tableName	=	'tool_down';
	  public function CountNum($id){
		  
		  $count=$this->where("id=$id")->count();
		  return $count;
		  
	  }
    /**
     +----------------------------------------------------------
     * 资源下载时扣除积分
     +----------------------------------------------------------
     * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
     * @return	return_type <返回类型(void的方法就不用该选项)>
     * @author	小朱 2013-8-2
     +----------------------------------------------------------
     * 创建时间：	2013-8-2 上午08:27:58
     +----------------------------------------------------------
     */
	public function setCredit($mid,$uid,$prices,$id){
			if($mid!=$uid){
			  $moneyType = getConfig('credit');
			  $setCredit = X('Credit');
			  
			  //获取下载用户积分
			  $userCredit = $setCredit->getUserCredit($mid);
			  if($userCredit['score']['credit']<$prices){ 
				  return false;
			   }
			  $setCredit->setUserCredit($mid,array('score'=>$prices),-1);
			  $setCredit->setUserCredit($uid,array('score'=>$prices),1);
			}
			//更新下载表
			$sql = "UPDATE {$this->tablePrefix}tool SET downCount=downCount+1 WHERE id='$id' LIMIT 1 ";
            $res= $this->execute($sql);
          //echo M()->getLastSql();exit();
			$data['id']=$id;
			$data['uid']=$mid;
			$data['dTime']=time();
			//是否更新成功下载表
			if($this->add($data)){
				return true;
			}else{
				return false;
			}	
			
		}
	function deleteres($pids) {
		if(!is_array($pids)){
			$pids	=	explode(',',$pids);
		}
		$map['d_id'] = array('in',$pids);
		$result	= $this->where($map)->delete();
		if($result){
			return true;
		}else{
			return false;
		}
	}
}
?>