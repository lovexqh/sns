<?php
/**
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
class SocietyWallModel extends Model {
	var $tableName	=	'society_wall';
	
	/**
	 +----------------------------------------------------------
	 * 根据圈子编号获取圈子印象列表
	 +----------------------------------------------------------
	 * @param integer $societyId
	 * @return array
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	public function getSocietyWallBySocietyid($societyId,$limit){
		$map['societyId']=$societyId;
		$map['isDel']=0; //可用标识
		$result=$this
		        ->where($map)
		        ->order('cTime desc')
		        ->findPage($limit);
		return $result;
	}
	/**
	 +----------------------------------------------------------
	 * 根据圈子编号获取圈子印象列表 for API 
	 +----------------------------------------------------------
	 * @param integer $societyId
	 * @return array
	 * @author ssq 2014-02-21
	 +----------------------------------------------------------
	 */
	public function getSocietyWallBySocietyidForApi($societyId,$page,$num){
		$limit = ($page-1)*$num;
		$limit = $limit.",".$num;
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
	 * 添加圈子印象
	 +----------------------------------------------------------
	 * @param array 圈子印象内容
	 * @return array
	 * @author ssq 2013-12-11
	 +----------------------------------------------------------
	 */
	public function addWall($data){
		$result=$this->add($data);
		return $result;
	}
	
	/**
	 *
	 +----------------------------------------------------------
	 * 社交圈              删除印象
	 +----------------------------------------------------------
	 * @return array 印象ＩＤ
	 * @author ssq 2013-12-26
	 +----------------------------------------------------------
	 */
	function deleteWall($id){
		$map['id'] = $id;
		$res = $this
				->where($map)
				->delete();
		return $res;
	}
	
	
	
}
?>