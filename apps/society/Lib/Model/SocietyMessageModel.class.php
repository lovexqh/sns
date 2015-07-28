<?php
/**
 * 
 +------------------------------------------------------------------------------
 * 圈子帖子
 +------------------------------------------------------------------------------
 * @category	社交圈
 * @author		Snail <spsnail@163.com>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-11-21 上午9:12:47
 +------------------------------------------------------------------------------
 */
class SocietyMessageModel extends Model {
	var $tableName	=	'society_message';
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 根据圈子编号获取圈子帖子列表
	 +----------------------------------------------------------
	 * @param integer $societyId
	 * @param integer $limit
	 * @return array
	 * @author Snail 2013-11-21 kung rewrite 2013-12-10
	 +----------------------------------------------------------
	 */
	function getSocietyMessageBySocietyid($societyId,$limit=10){
		$map['societyId']=$societyId;
		$map['isDel']=0; //可用标识
		$result=$this
		        ->where($map)
		        ->order('mTime desc')
                ->findPage($limit);
        //kung rewrite
        foreach($result['data'] as $k=>$v){
            $uid = $v['uid'];
            $name_reuslt = M('user')->where("uid=$uid")->field('uname')->find();
            $result['data'][$k]['u_name']  = $name_reuslt['uname'];
        }
		return $result;
	}
	/**
	 * 
	 +----------------------------------------------------------
	 * 根据圈子编号获取圈子帖子列表 for api
	 +----------------------------------------------------------
	 * @param integer $societyId
	 * @param integer $limit
	 * @return array
	 * @author Snail 2013-11-21 kung rewrite 2013-12-10
	 +----------------------------------------------------------
	 */
	function getSocietyMessageBySocietyidForApi($societyId,$page=1,$count=10){
		$limit = ($page-1)*$count;
		$limit = $limit.",".$count;
		$map['societyId']=$societyId;
		$map['isDel']=0; //可用标识
		$result=$this
		        ->where($map)
		        ->order('mTime desc')
                ->limit($limit)
				->select();
        //kung rewrite
        foreach($result as $k=>$v){
            $uid = $v['uid'];
            $name_reuslt = M('user')->where("uid=$uid")->field('uname')->find();
            $result[$k]['u_name']  = $name_reuslt['uname'];
        }
		return $result;
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 社交圈的帖子数统计，如果传$uid查询此用户在此圈子发表的帖子数，如果没有则查本圈子所有帖子数，$societyId同上
	 +----------------------------------------------------------
	 * @param integer $societyId
	 * @param integer $uid
	 * @return integer 帖子数量 
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	function getSocietyMessageCount($societyId=NULL,$uid=NULL,$id=NULL){
		$map['isDel']=0; //可用标识
		if(!empty($societyId))
			$map['societyId']=$societyId;
		if(!empty($uid))
			$map['uid']=$uid;
		if(!empty($id))
			$map['id']=$id;
		
		$result=$this
		        ->where($map)
		        ->count();
		return $result;
	}
    /**
     *
    +----------------------------------------------------------
     * 社交圈       根据社交圈ID和帖子ID获取帖子信息
    +----------------------------------------------------------
     * @param integer $societyId
     * @param integer $id
     * @return array        帖子数据
     * @author Kung 2013-12-11
    +----------------------------------------------------------
     */
	public function getMessById($societyId=NULL,$id=NULL){
        $map['societyId'] = $societyId;
        $map['id']        = $id;
		
        $result = $this->where($map)->find();
        if($result)
            return $result;
        else
            return false;
    }
    
    /**
     * 社交圈       根据和帖子ID获取帖子信息
     * --------------------------------------------------------
     * @param integer $id
     * @return array        帖子数据
     * @author ssq 2014-02-11
     * -------------------------------------------------------
     */
	public function getMess($id=NULL){
        $map['id']        = $id;

        $result = $this->where($map)->find();
        if($result)
            return $result;
        else
            return false;
    }
	
    /**
     *
     +----------------------------------------------------------
     * 根据圈子编号获取圈子帖子列表
     +----------------------------------------------------------
     * @param integer $societyId
     * @param integer $limit
     * @return array
     * @author Snail 2013-11-21
     +----------------------------------------------------------
     */
    function getSocietyMessagesBySocietyid($societyId,$limit=10){
    	$map['societyId']=$societyId;
    	$map['isDel']=0; //可用标识
    
    	$result=$this
    		->where($map)
    		->order('mTime desc')
    		->limit($limit)
    		->select();
    	return $result;
    }
    /**
     *
     +----------------------------------------------------------
     * 删除帖子
     +----------------------------------------------------------
     * @param integer $societyId
     * @param integer $limit
     * @return array
     * @author ssq 2014-1-21
     +----------------------------------------------------------
     */
    function deleteMess($id){
    	$map['id'] = $id;
    	$result = $this
    			->where($map)
    			->delete();
    	return $result;
    }
    
    /**
     +----------------------------------------------------------
     * 添加帖子
     +----------------------------------------------------------
     * @param array 
     * @return int id 
     * @author ssq 2014-1-21
     +----------------------------------------------------------
     */
    function addMessage($param){
    	$result = $this->add($param);
    	return $result;
    }
	
}
?>