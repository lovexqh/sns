<?php
class ClubReplyModel extends Model {
	
	/**
	 * @title  根据帖子id获取该帖子的回复内容
	 * @description 
	 * @param  帖子id
	 * @return
	 * @author	xiawei 2013-12-13
	 */
	public function getReplyByTopicid( $topicid ){
		$map['topicid'] = $topicid;
		$map['replyid'] = 0;
		$map['isdel'] = 0;
		$topicList = $this->where( $map )->order( 'ctime asc' )->findPage( 30 );
		return $topicList;
	}
	
	/**
	 * @title  查找回复内容下的回复
	 * @description 
	 * @param  回复id
	 * @return
	 * @author	xiawei 2013-12-13
	 */
	public function getReplyByReplyid( $replyid ){
		$map['replyid'] = $replyid;
		$map['isdel'] = 0;
		$reply1List = $this->where( $map )->order( 'ctime asc' )->select();
		return $reply1List;
	}
	
	/**
	 * @title  根据帖子id获取最大的楼层数
	 * @description 
	 * @param  帖子id
	 * @return
	 * @author	xiawei 2013-12-19
	 */
	public function getMaxFloor( $topicid ){
		$map['topicid'] = $topicid;
		$map['isdel'] = 0;
		$maxFloor = $this->where( $map )->max( 'floor' );
		return $maxFloor;
	}
	
	/**
	 * @title  添加回复
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-19
	 */
	public function addReply( $map ){
		$rs = $this->add( $map );
		return $rs;
	}
	
	public function getReplyById( $replyid ){
		$map['id'] = $replyid;
		$map['isdel'] = 0;
		$replyInfo = $this->where( $map )->find();
		return $replyInfo;
	}
	
	// 获取文件
	/**
	 * getGroupList
	
	 */
	public function getClubReplyList($html=1,$map = null,$fields=null,$order = null,$limit = null,$isDel=0) {
		//处理where条件
		if(!$isDel)$map[] = 'isdel=0';
		else $map[] = 'isdel=1';
	
		$map = implode(' AND ',$map);
		//连贯查询.获得数据集
		$result         = $this->where( $map )->field( $fields )->order( $order )->findPage($limit) ;
		if($html) return $result;
		return $result['data'];
	}
	
	// 回收站
	function remove($id){
		$id = is_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
		//$uids = D('Post')->field('uid')->where('id IN' . $id)->findAll();
		$res  = D('ClubReply')->setField('isdel', 1, 'id IN' . $id); //回复
		return $res;
	}
	
	function getReplyForMobile($topicid,$start,$num){
		$map['topicid'] = $topicid;
		$map['replyid'] = 0;
		$map['isdel'] = 0;
		$topicList = $this->where( $map )->order( 'ctime asc' )->limit($start.','.$num)->select();
		return $topicList;
	}
}