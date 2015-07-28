<?php

/**
 * 
 +------------------------------------------------------------------------------
 * 社交圈成员表
 +------------------------------------------------------------------------------
 * @category	社交圈
 * @author		Snail <spsnail@163.com>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-11-21 下午4:29:17
 +------------------------------------------------------------------------------
 */
class SocietyMemberModel extends Model {
	var $tableName	=	'society_member';
	
	
	/**
	 +----------------------------------------------------------
	 * 根据圈子ID和UID插入数据 
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return unknown <返回类型(void的方法就不用该选项)>
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	function addMemberBySocietyid_Uid($map){
		$map['cTime'] = time();
		$map['isDel'] = 0;
	    $result = $this->add($map);
	    return $result;
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 根据社交圈ID查询本圈子的成员
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return unknown <返回类型(void的方法就不用该选项)>
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	function getMemberBySocietyid($societyId){
		$map['societyId']=$societyId;
		$map['isDel']=0;
	    $memberList=$this->where($map)->field('uid')->select();
	    return $memberList;
	}

	/**
	 *
	 +----------------------------------------------------------
	 * 根据社交圈ID查询本圈子的成员 分页
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return unknown <返回类型(void的方法就不用该选项)>
	 * @author ssq 2014-02-21
	 +----------------------------------------------------------
	 */
	function getMemberBySocietyidPage($map,$page,$count=10){
		$limit = ($page-1)*$count;
		$limit = $limit.",".$count;
		$memberList=$this->where($map)->field('uid')->limit($limit)->select();
		return $memberList;
	}
	
    /**
     *
    +----------------------------------------------------------
     * 根据社交圈ID查询本圈子的管理成员
    +----------------------------------------------------------
     * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
     * @return unknown <返回类型(void的方法就不用该选项)>
     * @author  kung  2013-12-10
    +----------------------------------------------------------
     */
    function getManagBySocietyid($societyId){
        $map['societyId'] =$societyId;
        $map['isDel']     =0;
        $map['status']    = array('neq',0);
        $memberInfo = $this
        			->where($map)
                    ->order("status desc")
                    ->select();
        return $memberInfo;
    }
    /**
     *
    +----------------------------------------------------------
     * 根据社交圈ID查询本圈子的管理成员 分页
    +----------------------------------------------------------
     * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
     * @return unknown <返回类型(void的方法就不用该选项)>
     * @author  kung  2013-12-10
    +----------------------------------------------------------
     */
    function getManagBySocietyidPage($societyId,$page,$count){
    	$limit = ($page-1)*$count;
    	$limit = $limit.",".$count;
        $map['societyId'] =$societyId;
        $map['isDel']     =0;
        $map['status']    = array('neq',0);
        $memberInfo = $this
        			->where($map)
                    ->order("status desc")
                    ->limit($limit)
                    ->select();
        return $memberInfo;
    }

    /**
     *
    +----------------------------------------------------------
     * 根据社交圈ID和用户ID查看是否是圈子成员
    +----------------------------------------------------------
     * @param $societyId,$uid
     * @return int
     * @author  kung  2013-12-10
    +----------------------------------------------------------
     */
    function checkMemberBySociety($societyId=NULL,$uid=NULL){
        $map['societyId'] = $societyId;
        $map['uid'] = $uid;
        $map['isDel'] = 0;

        $result = $this
                ->where($map)
                ->find();
        if($result)
            return '1';
        else
            return '0';
    }
    
    /**
     * ----------------------------------------------
     * 根据用户ID获取所有所在的圈子
     * ----------------------------------------------
     * @param int $uid 用户UID
     * @return array 所在圈子列表信息
     * @author  ssq 2013-12-13
     * ----------------------------------------------
     */
    public function getListByUID($uid){
    	$result =M('SocietyMember sm')
    			->join('ts_society s on sm.societyId=s.id')
    			->where(array('sm.uid'=>$uid,'s.type'=>0,'sm.isDel'=>0,'s.isDel'=>0))
    			->order('s.id')
    			->select();
    	return $result;
    }
    
    /**
     * ----------------------------------------------
     * 根据用户ID和societyId获取成员信息
     * ----------------------------------------------
     * @param   
     * @return
     * @author  ssq 2013-12-13
     * ----------------------------------------------
     */
    public function getSocietyInfoByParam($param){
    	$result =$this
    			->where($param)
    			->order('status desc')
    			->select();
    	return $result;
    }
    
    /**
     * ----------------------------------------------
     * 根据societyId 和 uid 删除该成员
     * ----------------------------------------------
     * @param   array {$societyId,$uid}
     * @return	int 操作结果  返回操作成功的条数 1为成功  0 失败
     * @author  ssq 2013-12-13
     * ----------------------------------------------
     */
    public function delMember($param){
    	$result =$this
    			->where($param)
    			->save(array('isDel'=>1));
    	return $result;
    }
    
    /**
     * ----------------------------------------------
     * 根据societyId 和 uid 恢复成员
     * ----------------------------------------------
     * @param   array {$societyId,$uid}
     * @return	int 操作结果  返回操作成功的条数 1为成功  0 失败
     * @author  ssq 2013-12-13
     * ----------------------------------------------
     */
    public function reMember($param){
    	$result =$this
    			->where($param)
    			->save(array('isDel'=>0,'status'=>0,'cTime'=>time()));
    	return $result;
    }
    
    /**
     * ----------------------------------------------
     * 查找成员个数
     * ----------------------------------------------
     * @param   array {$societyId,$uid}
     * @return	int 个数
     * @author  ssq 2013-12-13
     * ----------------------------------------------
     */
    public function countMember($param){
    	$param['isDel'] = 0;
    	$result =$this
    			->where($param)
    			->count();
    	return $result;
    }
    
    /**
     * ----------------------------------------------
     * 根据societyId 和 uid 升为OR取消管理员
     * ----------------------------------------------
     * @param   array {$societyId,$uid}
     * @return	int 操作结果  返回操作成功的条数 1为成功  0 失败
     * @author  ssq 2013-12-13
     * ----------------------------------------------
     */
    public function memberManager($param,$status){
    	$result =$this
    			->where($param)
    			->save(array('status'=>$status,'isDel'=>0));
    	return $result;
    }
    
    /**
     * ----------------------------------------------
     * 删除成员
     * ----------------------------------------------
     * @param   array {$societyId,$uid}
     * @return	int 操作结果  返回操作成功的条数 1为成功  0 失败
     * @author  ssq 2014-1-7
     * ----------------------------------------------
     */
    public function deleteMember($param){
    	$result =$this
    			->where($param)
    			->delete();
    	return $result;
    }
    
    /**
     *
     +----------------------------------------------------------
     * 根据社交圈ID和用户ID查看是否是圈子管理成员
     +----------------------------------------------------------
     * @param $societyId,$uid
     * @return int
     * @author  kung  2013-12-10
     +----------------------------------------------------------
     */
    function checkMemberManager($societyId=NULL,$uid=NULL){
    	$map['societyId'] = $societyId;
    	$map['uid'] = $uid;
    	$map['isDel'] = 0;
    	$map['status'] = array('neq',0);
    	$result = $this
    			->where($map)
    			->find();
    	if($result)
    		return '1';
    	else
    		return '0';
    }
}
?>