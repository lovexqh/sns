<?php
/**
 * 
 +------------------------------------------------------------------------------
 * 圈子帖子回复
 +------------------------------------------------------------------------------
 * @category	社交圈
 * @author		Snail <spsnail@163.com>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-11-21 上午9:12:47
 +------------------------------------------------------------------------------
 */
class SocietyCommentModel extends Model {
	var $tableName	=	'society_comment';
	
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
	function getSocietyCommentBySocietyid($societyId,$limit=10){
		$map['$societyId']=$societyId;
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
	 * 社交圈的帖子回复数统计，如果传$uid查询此用户在此圈子发表的帖子回复数，如果没有则查本圈子所有帖子回复数 $messageIdy，$societyId同上
	 +----------------------------------------------------------
	 * @param integer $societyId
	 * @param integer $uid
	 * @param integer $messageId
	 * @return integer 帖子回复数量 
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	function getSocietyCommentCount($societyId=NULL,$uid=NULL,$messageId=NULL){
		$map['isDel']=0; //可用标识
		if(!empty($societyId))
			$map['societyId']=$societyId;
		if(!empty($uid))
			$map['uid']=$uid;
		if(!empty($messageId))
			$map['messageId']=$messageId;
		$result=$this
		        ->where($map)
		        ->count();
		return $result;
	}
	//不包括子回复
	function getSocietyCommentCounts($societyId=NULL,$uid=NULL,$messageId=NULL){
		$map['isDel']=0; //可用标识
		if(!empty($societyId))
			$map['societyId']=$societyId;
		if(!empty($uid))
			$map['uid']=$uid;
		if(!empty($messageId))
			$map['messageId']=$messageId;
		$map['toId'] = 0;
		$map['toUid'] = 0;
		$result=$this
		        ->where($map)
		        ->count();
		return $result;
	}

    /**
     *
    +----------------------------------------------------------
     * 社交圈的帖子最后回复者的相关信息
    +----------------------------------------------------------
     * @param integer $societyId
     * @param integer $messageId
     * @return array 帖子最后回复者的相关信息
     * @author kung 2013-12-10
    +----------------------------------------------------------
     */
    function getSocietyCommentLast($societyId=NULL,$messageId=NULL){
        $map['isDel'] = 0;
        $map['societyId'] = $societyId;
        $map['messageId'] = $messageId;
// ->join(' ts_user on ts_society_comment.uid = ts_user.uid')
        $result = $this
            ->where($map)
            ->order('cTime desc')
            ->find();

        return $result;
    }

    /**
     *
    +----------------------------------------------------------
     * 社交圈的帖子主回复信息
    +----------------------------------------------------------
     * @param integer $societyId
     * @param integer $messageId
     * @param integer $toId
     * @return array 帖子回复信息
     * @author kung 2013-12-12
    +----------------------------------------------------------
     */
     function getCommentLimit($societyId=NULL,$messageId=NULL,$toId=NULL,$limit = 100){
     	if ($societyId != null) {
	        $map['societyId'] = $societyId;
     	}
        $map['messageId'] = $messageId;
        $map['toId']      = $toId;
        $map['isDel']     = 0;
		$result = $this->where($map)->order('cTime ASC')->findPage($limit);
        return $result;
     }

     /**
      *
      +----------------------------------------------------------
      * 社交圈的帖子主回复信息 for api
      +----------------------------------------------------------
      * @param integer $societyId
      * @param integer $messageId
      * @param integer $toId
      * @return array 帖子回复信息
      * @author kung 2013-12-12
      +----------------------------------------------------------
      */
     function getCommentLimitForApi($societyId=NULL,$messageId=NULL,$toId=0,$page=1,$count = 100){
     	$limit = ($page-1)*$count;
     	$limit = $limit.",".$count;
     	if ($societyId != null) {
     		$map['societyId'] = $societyId;
     	}
     	$map['messageId'] = $messageId;
     	$map['toId']      = $toId;
     	$map['isDel']     = 0;
     	$result = $this->where($map)->order('cTime ASC')->limit($limit)->select();
     	return $result;
     }
     
    /**
     *
    +----------------------------------------------------------
     * 社交圈      帖子中存在子回复的主回复ID
    +----------------------------------------------------------
     * @param integer $messageId
     * @return array 帖子回复中有子回复的主回复ＩＤ
     * @author kung 2013-12-12
    +----------------------------------------------------------
     */
    function getCommentHasSon($messageId){
        $map['messageId'] = $messageId;
        $map['toId']      = array('NEQ',0);
        $map['isDel']     = 0;

        $result = $this->Distinct(true)->where($map)->field('toId')->select();
        if($result){
            foreach ($result as $k=>$v){
                $result_end[] = $v['toId'];
            }
            return $result_end;
        }else{
            return false;
        }
    }

    /**
     *
    +----------------------------------------------------------
     * 社交圈               添加帖子回复
    +----------------------------------------------------------
     * @return array 帖子回复ＩＤ
     * @author kung 2013-12-26
    +----------------------------------------------------------
     */
    function addMainComment($data){
        $res = $this->add($data);
        return $res;
    }

    /**
     *
    +----------------------------------------------------------
     * 社交圈              删除帖子所有回复
    +----------------------------------------------------------
     * @return array 帖子回复ＩＤ
     * @author ssq 2013-12-26
    +----------------------------------------------------------
     */
    function deleteComment($messageId){
    	$map['messageId'] = $messageId;
    	$res = $this
    			->where($map)
        		->delete();
        return $res;
    }

    /**
     *
    +----------------------------------------------------------
     * 社交圈             删除帖子回复内容
    +------------------------------------------------------
     * @return bool
     * @author kung 2013-12-30
    +----------------------------------------------------------
     */
    function deleteMyComment($sonid,$uid,$isManager){
        $data['isDel'] = 1;
        if($isManager)
            $result = $this->where('id ='.$sonid)->data($data)->save();
        else{
            $map['id']  = $sonid;
            $map['uid'] = $uid;
            $result = $this->where($map)->data($data)->save();
        }
        if($result)
            return true;
        else
            return false;
    }

}
?>