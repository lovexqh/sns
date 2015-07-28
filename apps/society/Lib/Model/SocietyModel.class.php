<?php
/**
 * 
 +------------------------------------------------------------------------------
 * 圈子基本信息
 +------------------------------------------------------------------------------
 * @category	社交圈
 * @author		Snail <spsnail@163.com>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-11-21 上午9:12:47
 +------------------------------------------------------------------------------
 */
class SocietyModel extends Model {
	var $tableName	=	'society';
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 根据圈子编号获取圈子的基本信息
	 +----------------------------------------------------------
	 * @param int $societyId
	 * @return array
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	function getSocietyInfoBySocietyid($societyId){
		$map['id'] = $societyId;
// 		$map['isDel'] = 0;
		$result=$this->where($map)->find();
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 多重条件查询圈子
	 +----------------------------------------------------------
	 * @param  array 
	 * @return array
	 * @author ssq 2013-12-11
	 +----------------------------------------------------------
	 */
	function getSocietyInfoBypara($map){
		$result = $this->where($map)->find();
		return $result;
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 多重条件查询圈子 多条记录
	 +----------------------------------------------------------
	 * @param  array 
	 * @return array
	 * @author ssq 2013-12-11
	 +----------------------------------------------------------
	 */
	function getSocietyInfosBypara($map){
		$result = $this
				->where($map)
				->select();
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据班级，年级，专业，院系ID创建圈子
	 +----------------------------------------------------------
	 * @param array 班级，年级，专业，院系ID
	 * @return array
	 * @author ssq 2013-12-11
	 +----------------------------------------------------------
	 */
	function addSocietyInfoBypara($map){
		$result=$this->add($map);
		return $result;
	}

    /**
    +----------------------------------------------------------
     * 根据typeID和type查找圈子信息
    +----------------------------------------------------------
     * @return array
     * @author    Kung 13-12-18
    +----------------------------------------------------------
     */
	public function getSocietyInfoByTypeid($typeid=0,$type=1){
        $map['typeid'] = $typeid;
        $map['type']   = $type;
        $result = $this->where($map)->find();
        return $result;
    }
    
    /**
     +----------------------------------------------------------
     * 设置圈子
     +----------------------------------------------------------
     * @param  array int 
     * @return array
     * @author ssq 2013-12-11
     +----------------------------------------------------------
     */
    function settingSocietyBypara($map,$societyId){
    	$result=$this->where('id='.$societyId)->save($map);
    	return $result;
    }
    
    /**
     +----------------------------------------------------------
     * join society_member 表  查询圈子及创建
     +----------------------------------------------------------
     * @param  array 圈子信息
     * @return null
     * @author ssq 2013-12-11
     +----------------------------------------------------------
     */
    function getSocietyBypara($param,$limit){
    	$param['sm.isDel'] = 0;
    	$param['s.isDel'] = 0;
    	if ($limit) {
    		$result =M('Society sm')
    				->join('ts_society_member s on sm.id=s.societyId')
    				->where($param)
    				->findPage($limit);
    	}else{
    		$result =M('Society sm')
    				->join('ts_society_member s on sm.id=s.societyId')
    				->where($param)
    				->select();
    	}
    	return $result;
    }	
}
?>