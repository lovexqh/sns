<?php
/**
 * 
 +------------------------------------------------------------------------------
 * 圈子共享
 +------------------------------------------------------------------------------
 * @category	社交圈
 * @author		Snail <spsnail@163.com>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-12-02 上午13:12:47
 +------------------------------------------------------------------------------
 */
class SocietyShareModel extends Model {
	var $tableName	=	'society_share';
	
	/**
	 +----------------------------------------------------------
	 * 根据圈子编号获取圈子共享列表
	 +----------------------------------------------------------
	 * @param integer $societyId
	 * @return array
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	function getSocietyShareBySocietyid($societyId,$page,$count=20){
		$limit = ($page-1)*$count;
		$limit = $limit.",".$count;
		$map['societyId']=$societyId;
		$map['isDel']=0; //可用标识
		$result=$this
		        ->where($map)
		        ->order('cTime desc')
		        ->limit($limit)
		        ->select();
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取共享数量
	 +----------------------------------------------------------
	 * @param  none
	 * @return integer
	 * @author ssq 2013-12-11
	 +----------------------------------------------------------
	 */
	function getCount($societyId){
		$result=$this->where(array('societyId'=>$societyId,'isDel'=>0))->count();
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 下载次数
	 +----------------------------------------------------------
	 * @param integer $id
	 * @return integer times
	 * @author ssq 2013-12-11
	 +----------------------------------------------------------
	 */
	function download($id){
		$map['download'] = array('exp', 'download+1');	
		$result = $this->where('id='.$id)->save($map);
		$result = $this->where('id='.$id)->find();
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 删除共享
	 +----------------------------------------------------------
	 * @param integer $id
	 * @return integer 
	 * @author ssq 2013-12-11
	 +----------------------------------------------------------
	 */
	function deleteShare($id){
		$map['isDel'] = '1';	
		$result = $this->where('id='.$id)->save($map);
		return $result;
	}
	
	
}
?>