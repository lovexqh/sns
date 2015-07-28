<?php
class ClubTopicModel extends Model {
	
	/**
	 * 
	 * @title 获取发帖多的前两个社团
	 * @description 按最近七天的发帖数进行排序
	 * @param
	 * @return
	 * @author	xiawei 2013-11-23
	 */
	public function getTopicHotClub($clubcount, $topiccount){
		$weekAgoTime = strtotime(date('Y-m-d')) - 3600*24*6;
		//获取帖子活跃的前两个社团的id和name
		$hotClub = M('')->table ( C ( 'DB_PREFIX' ) . 'club c, ' . C ( 'DB_PREFIX' ) . 'club_topic t' )
								//->where ( 't.clubid=c.id AND t.isdel=0 AND c.isdel=0 AND t.type=1 AND t.ctime >'.$weekAgoTime )//lihao，时间规则待修改
								->where ( 't.clubid=c.id AND t.isdel=0 AND c.isdel=0 AND t.type=1 ')
								->group('clubid')
								->order ('count(t.clubid) desc')
								->limit($clubcount)
								->field ( 'c.id as clubid,c.title,c.logo,c.type,c.membercount' )
								->select();
		foreach ($hotClub as &$value){
			$value['topic'] = $this->where('type=1 AND isdel=0 AND clubid='.$value['clubid'])->field('id as topicid,clubid,title,ctime')->order('replytime desc,ctime desc')->limit($topiccount)->select();
			foreach ($value['topic'] as &$topic){
				$topic['ctime'] = date("Y-m-d",$topic['ctime']);
				$topic['title'] = htmlspecialchars($topic['title']);
			}
		}
		return $hotClub;
	}
	
	/**
	 *
	 * @title 根据社团id,查找该社团中一周内的前五条帖子
	 * @description 根据帖子发布时间倒序排序
	 * @param
	 * @return
	 * @author	xiawei 2013-11-23
	 */
	public function getHotTopicByClubid($clubid){
		$weekAgoTime = strtotime(date('Y-m-d')) - 3600*24*6;
		//$map ['ctime'] = array ('gt',$weekAgoTime);  //lihao，时间规则待修改
		$map ['isdel'] = 0;
		$map ['type'] = 1;
		$map ['clubid'] = $clubid;
		
		$topicList = $this->where($map)->field('id as topicid,title,ctime,topicpic')->order('ctime desc')->limit(5)->select();
		
// 		$topicList = M('')->table ( C ( 'DB_PREFIX' ) . 'club_topic t,' . C ( 'DB_PREFIX' ) . 'club_reply r' )
// 							->where ( 't.id=r.topicid AND t.isdel=0 AND r.isdel=0 AND t.type=1 AND t.clubid='.$clubid.' AND t.ctime>'.$weekAgoTime )
// 							->group ( 'r.topicid' )
// 							->field ( 't.*' )
// 							->order ('count(r.id) desc')
// 							->limit (5)
// 							->select();
		//时间格式转化
		foreach($topicList as &$Club){
			$Club['ctime'] = date("Y-m-d",$Club['ctime']);
			$Club['title'] = htmlspecialchars($Club['title']);
		}
		return $topicList;
	}
	
	/**
	 * @title  获取最新的社团活动风采
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-11-23
	 */
	public function getNewEventShow(){
		$newEvent = M('')->table ( C ( 'DB_PREFIX' ) . 'club_topic t,' . C ( 'DB_PREFIX' ) . 'club c' )
						->where('t.clubid=c.id AND c.isdel=0 AND t.isdel=0 AND t.type=2')
						->field('c.id as clubid,c.title as clubname,t.id as topicid,t.title,t.issue,t.ctime,t.content, t.topicpic')
						->order('t.ctime desc')
						->limit('6')
						->select();
		foreach ($newEvent as &$value){
			//$content = M('club_post')->where('isdel=0 AND topicid='.$value['topicid'])->field('content')->find();
			//$value['content'] = $content['content'];
			$value['ctime']=date("Y-m-d",$value['ctime']);
			$value['title']=htmlspecialchars($value['title']);
		}
		return $newEvent;
	}
	
	/**
	 * @title  根据社团id获取该社团中置顶的帖子
	 * @description  获取前四条
	 * @param  $clubid
	 * @return
	 * @author	xiawei 2013-11-25
	 */
	public function getTopTopic( $map ){
		$map['type'] = 1;
		$map['zding'] = 1;
		$map['isdel'] = 0;
		$topTopic = $this->where( $map )->order('ctime desc')->limit(4)->select();
		foreach ($topTopic as &$top){
			$top['title'] = htmlspecialchars($top['title']);
		}
		return $topTopic;
	}
	
	/**
	 * @title  根据社团id获取社团中的帖子
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-11-25
	 */
	public function getTopicList( $map ){
		$map['type'] = 1;
		//$map['status']  = array('neq', 2);
		$map ['isdel'] = 0;
		$result = $this->where ( $map )->order ( 'ctime desc,replytime desc' )->findPage ( 20 );
		return $result;
	}
	
	/**
	 * @title  获取前三条详细展示的活动
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-11-26
	 */
	public function getShowEvent($clubid, $num){
		$map['clubid'] = $clubid;
		$map['type'] = 2;
		$map['isdel'] = 0;
		$showEvent = $this->where( $map )->order('issue desc')->limit($num)->select();
		return $showEvent;
	}
	
	/**
	 * @title  获取活动列表
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-11-26
	 */
	public function getEventList($clubid, $minIssue){
		$map['clubid'] = $clubid;
		$map['type'] = 2;
		$map['issue'] = array('lt', $minIssue);
		$map['isdel'] = 0;
		$eventList = $this->where( $map )->order('issue desc')->findPage ( 20 );
		return $eventList;
	}
	
	/**
	 * @title  获取所有风采列表
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-3-17
	 */
	public function getAllEventList($clubid){
		$map['clubid'] = $clubid;
		$map['type'] = 2;
		$map['isdel'] = 0;
		$eventList = $this->where($map)->order('issue desc')->findPage(20);
		return $eventList;
	}
	
	public function getMaxIssue( $clubid ){
		$map['clubid'] = $clubid;
		$map['isdel'] = 0;
		$maxIssue = $this->where($map)->max('issue');
		return $maxIssue;
	}
	
	/**
	 * @title  获取推荐的帖子
	 * @description 
	 * @param  社团id
	 * @return
	 * @author	xiawei 2013-12-12
	 */
	public function getTjianTopic($clubid){
		$map['clubid'] = $clubid;
		$map['type'] = 1;
		$map['tjian'] = 1;
		$map['isdel'] = 0;
		$tjianTopic = $this->where( $map )->order('zding desc,replytime desc,ctime desc')->findPage( 30 );
		return $tjianTopic;
	}
	
	/**
	 * @title  获取我发布的帖子
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-12
	 */
	public function getMyTopic( $map ){
		$map['type'] = 1;
		$map['isdel'] = 0;
		$myTopic = $this->where( $map )->order('zding desc,replytime desc,ctime desc')->findPage( 30 );
		return $myTopic;
	}
	
	public function getTopicById($topicid){
		$topicInfo = $this->where( 'id='.$topicid )->find();
		return $topicInfo;
	}
	
	/**
	 * @title  点击量加1
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-20
	 */
	public function addClickCount( $topicid ){
		$data['clickcount'] = array('exp','clickcount+1');
		$this->where( 'id='.$topicid )->save( $data );
	}
	
	/**
	 * @title  更新帖子表中的回复信息,包括回复数,最后回复时间,最后回复人的uid
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-20
	 */
	public function updateReplyInfoInTopic( $data, $topicid ){
		$data['replycount'] = array('exp','replycount+1');
		$this->where( 'id='.$topicid )->save( $data );
	}
	
	/**
	 * @title  对帖子进行相关操作,包括:置顶与取消置顶,推荐与取消推荐,删除
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-23
	 */
	public function operateTopic($map, $id){
		$rs = $this->where( 'id='.$id )->save( $map );
		return $rs;
	}
	
	//获取帖子列表
	/**
	 * getTopicList
	 */
	public function getTopicListAdmin($html=1, $map = null, $fields=null, $order = null, $limit = null, $isDel=0)
	{
		//处理where条件
		if(!$isDel)$map[] = 'isdel=0';
		else $map[] = 'isdel=1';
	
		$map = implode(' AND ', $map);
		//连贯查询.获得数据集
		$result = $this->where( $map )->field( $fields )->order( $order )->findPage( $limit ) ;
// 		foreach ($result['data'] as &$topic){
// 			$topic['uname']=getUserName($topic['uid']);
// 		}
		if($html) return $result;
		print_r($result);
		return $result['data'];
	}
	
	//回收站
	public function remove($id){
		$id = is_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
		//$uids = D('ClubTopic')->field('uid')->where('id IN' . $id)->findAll();
		$res  = D('ClubTopic')->setField('isdel', 1, 'id IN' . $id); //回收话题
		if ($res) {
			D('ClubReply')->setField('isdel',1,'topicid IN'.$id); //回复
		}
		return $res;
	}
	
	/**
	 * 获取前4张社团图片
	 * @author lihao
	 */
	public function getTopicPic($clubid){
		$topicList = $this->where('isdel=0 AND clubid='.$clubid.' AND topicpic IS NOT NULL AND type=1 ')
						  ->field('id, clubid, topicpic')
		                  ->order('ctime desc')
		                  ->limit(4)
		                  ->select();
		return $topicList;
	}
	
	public function getEventByIssue($clubid, $issue){
		$map['clubid'] = $clubid;
		$map['type'] = 2;
		$map['issue'] = $issue;
		$map['isdel'] = 0;
		$findrs = $this->where( $map )->find();
		return $findrs;
	}
	
	/**
	 * 获取社团帖子数量
	 */
	public function getTopicNumByClubid($clubid){
		$topicNum = $this->where('isdel=0 AND type=1 AND clubid='.$clubid.' ')
		->field('id')
		->select();
		return $topicNum;
	}
	
	/**
	 * @title  获取七天内社团中热门的帖子
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-1-10
	 */
	public function getHotTopic( $count ){
		$map['type'] = 1;
		$map['isdel'] = 0;
		$map['ctime'] = array('gt', time()-7*24*3600);
		$topicList = $this->where( $map )->order('replycount desc,clickcount desc')->limit( $count )->select();
		return $topicList;
	}
	
	/**
	 * @title  根据社团id获取社团中帖子数
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-1-26
	 */
	public function getTopicCountById($clubid){
		$count = $this->where('clubid='.$clubid.' AND type=1 AND isdel=0')->count();
		return $count;
	}
	
	/**
	 * @title  根据社团id获取社团中最新的帖子
	 * @description 
	 * @param  社团id,查找的数量
	 * @return
	 * @author	xiawei 2014-1-26
	 */
	public function getNewTopicByClubid($clubid,$count){
		$topicList = $this->where('clubid='.$clubid.' AND type=1 AND isdel=0')
						->field('uid,id as tid,topicpic as thead,title as ttittle,ctime as ttime')
						->order('ctime desc')->limit($count)->select();
		return $topicList;
	}
	
	/**
	 * @title  根据相应参数查询相应的帖子
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-1-26
	 */
	public function getTopicByParam($map, $start, $num){
		$topicList = $this->where( $map )->order('zding desc,ctime desc')->limit($start.','.$num)->select();
		return $topicList;
	}
	
	public function addTopic($map){
		$rs = $this->add($map);
		return $rs;
	}
	
	public function getAllEvent($clubid){
		$map['type'] = 2;
		$map['clubid'] = $clubid;
		$map['isdel'] = 0;
		$eventList = $this->where( $map )->order('issue desc')->field('id,clubid,uid,title,issue,topicpic,content,replycount,ctime')->select();
		return $eventList;
	}

}