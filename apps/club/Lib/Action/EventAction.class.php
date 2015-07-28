<?php
class EventAction extends Action {
	const INDEX_TYPE_WEIBO = 0;
	const INDEX_TYPE_GROUP = 1;
	const INDEX_TYPE_ALL = 2;
	function _initialize() {
		
	}
	
	function init() {
		echo './Public/miniblog.js';
	}
	
	//风采
	public function index(){
		$clubid = $_GET['id'];
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		$clubTopic = D( 'ClubTopic' );
		//获取前三条展示的活动
// 		$showEvent = $clubTopic->getShowEvent($clubid, 3);
// 		$issueArr = array();
// 		foreach ($showEvent as &$value){
// 			$value['title'] = htmlspecialchars($value['title']);
// 			if( strlen($value['title'])>80 ){				
// 				$value['title'] = mStr(text($value['title']),30);
// 			}
// 			if( strlen($value['content'])>400 ){
// 				$value['content'] = mStr(text($value['content']),138);
// 			}
// 			if( $value['ctime'] == null ){
// 				$value['ctime'] = '';
// 			}
// 			array_push($issueArr, $value['issue']);
// 		}
// 		$minIssue = min( $issueArr );
		//dump($minIssue);exit;
		//获取其他活动列表
		//$eventList = $clubTopic->getEventList($clubid, $minIssue);
		$eventList = $clubTopic->getAllEventList($clubid);
		foreach ($eventList['data'] as &$val){
			$val['title'] = htmlspecialchars($val['title']);
			if( strlen($val['title'])>40 ){
				$val['title'] = mStr(text($val['title']),40);
			}
			if( $val['ctime'] == null ){
				$val['ctime'] = '';
			}
		}
		//dump($eventList);exit;
		//获取活动的最大期数
		$maxIssue = D('ClubTopic')->getMaxIssue( $clubid );
		$issue = intval($maxIssue) + 1;
		$this->assign( $headData );
		//$this->assign('showEvent', $showEvent);
		$this->assign('eventList', $eventList);
		$this->assign('issue', $issue);
		$this->display();
	}
	
	public function doAddEvent(){
		$clubid = $_POST['clubid'];
		$uid = $this->mid;
		if( !isManager($uid, $clubid) )
			$this->error("您不是管理员，不能发布活动");
		
		$map['title'] = trim($_POST['title']);
		if( empty($map['title']) || $map['title']=="请输入标题" ){
			$this->error("标题不能为空！");
		}
		$issue = trim($_POST['issue']);
		if(!$issue){
			$this->error("期数不能为空!");
		}
		$findEvent = D( 'ClubTopic' )->getEventByIssue($clubid, $issue);
		if( $findEvent ){
			echo 2;
			exit();
		}else {
			$map['issue'] = $issue;
		}
		//获取活动的最大期数
		//$maxIssue = D('ClubTopic')->getMaxIssue( $clubid );
		//$map['issue'] = intval($maxIssue) + 1;
		$map['clubid'] = $clubid;
		$map['uid'] = $uid;
		$map['type'] = 2;
		$map['ctime'] = time();
		$map['replyman'] = $_POST['replyman'];
		$map['content'] = trim($_POST['content']);
		if(!$map['content']){
			$this->error("风采内容不能为空!");
		}
		$map['content'] = str_replace('\"', '"', $map['content']);
		$map['content'] = str_replace('target="_self"', 'target="_blank"', $map['content']);
		preg_match_all("/\\<img.*?src\\=\"(.*?)\"[^>]*>/i", $map['content'], $imgArr);
		foreach ( $imgArr[1] as $val ){
			if( !strstr($val, 'public/js/editor/editor') ){
				$map['topicpic'] = $val;
			}
		}
		//print_r($map);exit;
		$rs = D('ClubTopic')->add( $map );
		if ($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}
	
	public function eventDetail(){
		$clubid = $_GET['id'];
		//社团信息
		$clubInfo = D( 'Club' )->getClubById($clubid, $this->mid);
		
		$topicid = $_GET['topicid'];
		//帖子详细信息
		$topicInfo = D( 'ClubTopic' )->getTopicById($topicid);
		if( $topicInfo['isdel'] ==1 ){
			$this->error("活动已经被删除!");
		}
		$topicInfo['title'] = htmlspecialchars($topicInfo['title']);
		if( $topicInfo['replyman'] == 0 ){
			$topicInfo['whoreply'] = '所有人可评论';
		}else if( $topicInfo['replyman'] == 1 ){
			$topicInfo['whoreply'] = '仅社团成员可评论';
		}
		
		$replyList = D( 'ClubReply' )->getReplyByTopicid( $topicid );
		if( $replyList['data'] == '' ){
			$replyList['replycount'] = 0;
		}else{
			$replyList['replycount'] = count( $replyList['data'] );
		}
		foreach ($replyList['data'] as &$value){
			$value['time'] = date("Y-m-d H:i", $value['ctime']);
		}
		//dump($replyList);exit;
		$member = getMemberinfoInClub($clubid, $this->mid);
		
		//获取当前用户是否具有回复帖子的权限
		$replyman = $topicInfo['replyman'];
		$replylimit = $this->getReplyLimit($replyman, $this->mid, $clubid);
		
		$this->assign( 'mid', $this->mid );
		$this->assign( 'clubInfo', $clubInfo );
		$this->assign('topicInfo', $topicInfo);
		$this->assign('replyList', $replyList);
		$this->assign('member', $member);
		$this->assign('replylimit', $replylimit);
		$this->display();
	}
	
	public function doAddEventReply(){
		$map['topicid'] = $_POST['topicid'];
		if( !$map['topicid'] ){
			$this->error("回复错误！");
		}
		$map['content'] = trim( $_POST['content'] );
		if( empty($map['content']) ){
			$this->error("评论内容不能为空！");
		}
		$map['uid'] = $this->mid;
		$map['replyid'] = 0;
		$map['replyuid'] = $_POST['replyuid'];
		$map['ctime'] = time();
		$rs = D('ClubReply')->addReply( $map );
		if ($rs) {
			//回复数加1
			$data['replytime'] = time();
			$data['replyuid'] = $this->mid;
			D( 'ClubTopic' )->updateReplyInfoInTopic( $data, $map['topicid'] );
			echo 1;
		} else {
			echo 0;
		}
	}
	
	//删除风采
	public function delEvent(){
		$id = $_POST['id'];
		if(!id){
			$this->error("操作失败!");
		}
		$map['isdel'] = 1;
		$delrs = D( 'ClubTopic' )->operateTopic($map, $id);
		if($delrs){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//获取是否具有回复风采的权限
	private function getReplyLimit($replyman, $uid, $clubid){
		if( $replyman == 0 ){
			$replylimit = 1;
		}else if( $replyman == 1 ){
			$ismember = isClubMember($uid, $clubid);
			if( $ismember ){
				$replylimit = 1;
			}
		}
		return $replylimit;
	}
	
	
}
?>