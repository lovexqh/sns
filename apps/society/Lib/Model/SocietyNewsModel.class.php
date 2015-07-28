<?php
/**
 +------------------------------------------------------------------------------
 * 圈子消息
 +------------------------------------------------------------------------------
 * @category	社交圈
 * @author		ssq
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-12-21 上午9:12:47
 +------------------------------------------------------------------------------
 */
class SocietyNewsModel extends Model {
	var $tableName	=	'society_news';
	
	/**
	 * 获取圈子消息
	 * --------------------------------------- 
	 * @param  int   $societyId
	 * @return array 返回圈子消息列表
	 * @author ssq   2013-12-23
	 * ---------------------------------------
	 */
	public function getNews($societyId){
		$param['n.societyId'] = $societyId;
		$param['n.isDel'] = 0;
		$result = M('SocietyNews n')
				->join('ts_society s on n.societyId=s.id')
				->where($param)
				->order('n.cTime desc')
				->field('*,n.cTime as cTime')
				->select();
		return $result;
	}
	
	/**
	 * 获取消息
	 * ---------------------------------------
	 * @param  int   $uid
	 * @return array 返回我的消息列表
	 * @author ssq   2013-12-23
	 * ---------------------------------------
	 */
	public function getNewsByUid($uid,$societyIdArray){
		$param['_string'] = 'n.societyId in ('.$societyIdArray.') and n.isDel = 0';
		$result = M('SocietyNews n')
				->join('ts_society s on n.societyId=s.id')
				->where($param)
				->order('n.cTime desc')
				->field('*,n.cTime as cTime')
				->select();
		return $result;
	}
	/**
	 * 获取个人消息
	 * ---------------------------------------
	 * @param  int   $uid
	 * @return array 返回我的消息列表
	 * @author ssq   2013-12-23
	 * ---------------------------------------
	 */
	public function getMyNewsByUid($uid){
		$param['n.toUid'] = $uid;
		$param['n.isDel'] = 0;
		$result = M('SocietyNews n')
				->join('ts_society s on n.societyId=s.id')
				->where($param)
				->order('n.cTime desc')
				->field('*,n.cTime as cTime')
				->select();
		return $result;
	}

	/**
	 * 添加圈子消息
	 * --------------------------------------- 
	 * @param  array($societyId,$fromUid,$toUid,$newsType,)
	 * @return id 返回数据的ID
	 * @author ssq  2013-12-23
	 * ---------------------------------------
	 */
	public function addNews($param){
		$result = $this
				->add($param);
		return $result;
	}
	
	/**
	 * 任意条件查询消息
	 * --------------------------------------- 
	 * @param  array 
	 * @return array $List
	 * @author ssq   2013-12-23
	 * ---------------------------------------
	 */
	public function getNewsByParam($param){
		$param['isDel'] = 0;
		$result = $this
				->where($param)
				->select();
		return $result;
	}
	
	/**
	 * 删除消息
	 * --------------------------------------- 
	 * @param  int  $newsId  消息newsId
	 * @return int  返回操作的条数
	 * @author ssq  2013-12-23
	 * ---------------------------------------
	 */
	public function deleteNews($newsId){
		$result = $this
				->where('newsId='.$newsId)
				->save(array('isDel'=>1));
		return $result;
	}
	
	/**
	 * 忽略消息
	 * --------------------------------------- 
	 * @param  int  $newsId  消息newsId
	 * @return int  返回操作的条数  
	 * @author ssq  2013-12-23
	 * ---------------------------------------
	 */
	public function ignoreNews($newsId,$param){
		$result = $this
				->where('newsId='.$newsId)
				->save($param);
		return $result;
	}
	
	/**
	 * 通过消息
	 * --------------------------------------- 
	 * @param  int  $newsId  消息newsId
	 * @return int  返回操作的条数
	 * @author ssq  2013-12-23
	 * ---------------------------------------
	 */
	public function tongGuoNews($newsId,$list){
		$result = $this
				->where('newsId='.$newsId)
				->save($list);
		return $result;
	}
	
	/**
	 * 加入消息
	 * --------------------------------------- 
	 * @param  int  $id  消息ID
	 * @return int  返回操作的条数
	 * @author ssq  2013-12-23
	 * ---------------------------------------
	 */
	public function joinNews($newsId,$list){
		$result = $this
				->where('newsId='.$newsId)
				->save($list);
		return $result;
	}
	/**
	 * 圈子消息总数
	 * ---------------------------------------
	 * @param  int  $id  消息ID
	 * @return int  返回操作的条数
	 * @author ssq  2013-12-23
	 * ---------------------------------------
	 */
	public function countSocietyNews($societyId){
		$result = $this
				->where(array('societyId'=>$societyId,'isDel'=>'0'))
				->select();
		return $result;
	}
}
?>