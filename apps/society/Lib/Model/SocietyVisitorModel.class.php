<?php
/**
 *
+------------------------------------------------------------------------------
 * 圈子帖子
+------------------------------------------------------------------------------
 * @category	社交圈
 * @author		kung <ohdas@163.com>
 * @version		1.0
+------------------------------------------------------------------------------
 * 创建时间：	2013-12-10 16:38
+------------------------------------------------------------------------------
 */
class SocietyVisitorModel extends Model {

    /**
     *
    +----------------------------------------------------------
     *               检查用户是否已经访问过该圈子
    +---------------------------------------------------------
     * @param integer $societyId
     * @param integer $uid
     * @return bool
     * @author kung 2013-12-10
     *
    +----------------------------------------------------------
     */
    public  function checkUserVisited($societyId=NULL,$uid=NULL){
        $map['societyId'] = $societyId;
        $map['uid']       = $uid;

        $result = $this->where($map)->find();

        if($result)
            return $result;
        else
            return false;
    }
    /**
     *
    +----------------------------------------------------------
     *               数据库中无数据，则插入数据
     *+----------------------------------------------------------
     * @param integer $societyId
     * @param integer $uid
     * @param integer $status
     * @return bool
     * @author kung 2013-12-10
     *
    +----------------------------------------------------------
     */
    public function addUserVisitor($societyId=NULL,$uid=NULL,$status=NULL){
        $map['societyId'] = $societyId;
        $map['uid']       = $uid;
        $map['status']    = $status;
        $map['cTime']     = time();
        $map['times']     = '1';
        $map['isDel']     = 0;

        $result = $this->add($map);
        if($result)
            return true;
        else
            return false;
    }

    /**
     *
    +----------------------------------------------------------
     *               数据库中无数据，则更新数据
     *+----------------------------------------------------------
     * @param integer $societyId
     * @param integer $uid
     * @param integer $status
     * @return bool
     * @author kung 2013-12-10
     *
    +----------------------------------------------------------
     */
    public function updateUserVisited($societyId=NULL,$uid=NULL,$status=NULL,$svid=NULL,$times=NULL){
        $data['societyId'] = $societyId;
        $data['status']    = $status;
        $data['cTime']     = time();
        $data['times']     = $times+1;
        $map['uid']        = $uid;
        $map['sv_id']      = $svid;

        $result = $this->where($map)->save($data);
        if($result)
            return true;
        else
            return false;
    }

    /**
     *
    +----------------------------------------------------------
     *               获取圈子访客
     *+----------------------------------------------------------
     * @param integer $societyId
     * @param integer $status
     * @return array
     * @author kung 2013-12-11
     *
    +----------------------------------------------------------
     */
    public function getMemVisitor($societyId=NULL,$status=NULL,$num=NULL){
        $map['societyId'] = $societyId;
        $map['status']    = $status;

        $result = $this->where($map)->limit($num)->order('cTime desc')->select();
        if($result)
            return $result;
        else
            return false;
    }
    /**
     *  获取圈子访客  分页
     * @param integer $societyId
     * @param integer $status
     * @return array
     * @author ssq 2014-2-11
     */
    public function getMemVisitorByPage($societyId=NULL,$status=NULL,$num=12){
        $map['societyId'] = $societyId;
        $map['status']    = $status;

        $result = $this->where($map)->order('cTime desc')->findPage($num);
        if($result)
            return $result;
        else
            return false;
    }
    /**
     *  获取圈子访客  分页 for api
     * @param integer $societyId
     * @param integer $status
     * @return array
     * @author ssq 2014-2-11
     */
    public function getMemVisitorByPageForApi($societyId=NULL,$status=NULL,$page=1,$num=12){
    	$limit = ($page-1)*$num;
    	$limit = $limit.",".$num;
        $map['societyId'] = $societyId;
        $map['status']    = $status;

        $result = $this->where($map)->order('cTime desc')->limit($limit)->select();
        if($result)
            return $result;
        else
            return false;
    }
    /**
     *
    +----------------------------------------------------------
     *               获取圈子访客数量
     *+----------------------------------------------------------
     * @param integer $societyId
     * @param integer $status
     * @param time    $startTime
     * @return array
     * @author kung 2013-12-11
     *
    +----------------------------------------------------------
     */
    public function getVisitorNum($societyId=NULL,$status=NULL,$startTime=NULL){
        $map['societyId'] = $societyId;
        $map['status']    = $status;
        $mapAll = $map;
        if(!empty($startTime))
            $mapAll['cTime'] = array('egt',$startTime);

        $result['allTimes'] = $this->where($map)->sum('times');
        $result['todayTimes'] = $this->where($mapAll)->count();

        return $result;
    }
    
    /**
     * -------------------------------------------------
     * 访问人员由status=1改为0
     * -------------------------------------------------
     * @param   int $societyId 
     * 			int $uid
     * @return  int 返回操作的条数 
     * @author  ssq  2013-12-23
     * -------------------------------------------------
     */
    public function outOfMemberVisitor($map){
    	$result = $this
    			->where($map)
    			->save(array('status'=>0));
    	return $result;
    }
    
    /**
     * -------------------------------------------------
     * 访问人员由status=0改为1
     * -------------------------------------------------
     * @param   int $societyId 
     * 			int $uid
     * @return  int 返回操作的条数 
     * @author  ssq  2013-12-23
     * -------------------------------------------------
     */
    public function innerMemberVisitor($map){
    	$result = $this
    			->where($map)
    			->save(array('status'=>1));
    	return $result;
    }

}