<?php
class TopicAction extends Action {
	const INDEX_TYPE_WEIBO = 0;
	const INDEX_TYPE_GROUP = 1;
	const INDEX_TYPE_ALL = 2;
	function _initialize() {
		
	}
	
	function init() {
		echo './Public/miniblog.js';
	}
	
	//帖子
	public function index(){
		$topicKey = $_POST['topicKey'];
		if( $topicKey ) {
			$map['title'] = array('like', '%'.$topicKey.'%');
		}
		
		$clubid = $_GET['id'];
		$map['clubid'] = $clubid;
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		$clubTopic = D( 'ClubTopic' );
		//获取置顶帖子
		$topTopic = $clubTopic->getTopTopic( $map );
		$idStr = "";
		foreach ($topTopic as &$value){
			$value['title'] = htmlspecialchars($value['title']);
			if( strlen($value['title'])>17 ){
				$value['title'] = mStr(text($value['title']),17);
			}
			$idStr .= $value['id'].',';
		}
		$idStr = substr($idStr,0,(strlen($idStr)-1));
		//获取所有帖子列表
		if( $idStr ){
			$map['id'] = array('not in', $idStr);
		}
		$topicList = $clubTopic->getTopicList( $map );
		$topicList['data'] = $this->doTopicList( $topicList['data'] );
		
		//获取当前用户是否具有发表帖子的权限
		$pubtopic = $headData['clubInfo']['pubtopic'];
		$publish = $this->getPubTopicLimit($pubtopic, $this->mid, $clubid);
		
		$this->assign( $headData );
		$this->assign( 'topTopic', $topTopic );
		$this->assign( 'topicList', $topicList );
		$this->assign( 'publish', $publish);
		$this->display();
	}
	
	//发布帖子
	public function doAddTopic(){
		$map['title'] = trim($_POST['title']);
		if( empty($map['title']) || $map['title']=="请输入标题" ){
// 			echo "标题不能为空！";
// 			exit();
			$this->error("标题不能为空！");
		}
		
		$clubid = $_POST['clubid'];
		$uid = $this->mid;
		//是否置顶
		if( isManager($uid, $clubid) ) {	//判断是否管理员
			$istop = $_POST['istop'];
			if( $istop == 'true' ){
				$map['zding'] = 1;
			}
		}
		
		$map['clubid'] = $clubid;
		$map['uid'] = $uid;
		$map['type'] = 1;
		$map['ctime'] = time();
		$map['replyman'] = $_POST['replyman'];
		//dump($map);exit;
		$map['content'] = safe(trim($_POST['content']));
		$map['content'] = str_replace('\"', '"', $map['content']);
		$map['content'] = str_replace('target="_self"', 'target="_blank"', $map['content']);
		preg_match_all("/\\<img.*?src\\=\"(.*?)\"[^>]*>/i", $map['content'], $imgArr);
		foreach ( $imgArr[1] as $val ){
			if( !strstr($val, 'public/js/editor/editor') ){
				$map['topicpic'] = $val;
			}
		}
		$rs = D('ClubTopic')->add( $map );
		if ($rs) {
			echo 1;
		} else {
			echo 0;
		}
		
	}
	
	//推荐帖子
	public function tjian(){
		$clubid = $_GET['id'];
		$map['clubid'] = $clubid;
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		$tjianTopic = D( 'ClubTopic' )->getTjianTopic($clubid);
		$tjianTopic['data'] = $this->doTopicList( $tjianTopic['data'] );
		
		//获取当前用户是否具有发表帖子的权限
		$pubtopic = $headData['clubInfo']['pubtopic'];
		$publish = $this->getPubTopicLimit($pubtopic, $this->mid, $clubid);
		
		$this->assign( $headData );
		$this->assign('tjianTopic', $tjianTopic);
		$this->assign('publish', $publish);
		$this->display();
	}
	
	//我的帖子
	public function mine(){
		$clubid = $_GET['id'];
		$map['clubid'] = $clubid;
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		$map['clubid'] = $clubid;
		$map['uid'] = $this->mid;
		$myTopic = D( 'ClubTopic' )->getMyTopic( $map );
		$myTopic['data'] = $this->doTopicList( $myTopic['data'] );
		foreach ($myTopic['data'] as &$val){
			if( strlen($val['title'])>17 ){
				$val['title'] = mStr($val['title'],17);
			}
		}
		
		//获取当前用户是否具有发表帖子的权限
		$pubtopic = $headData['clubInfo']['pubtopic'];
		$publish = $this->getPubTopicLimit($pubtopic, $this->mid, $clubid);
		
		$this->assign( $headData );
		$this->assign('myTopic', $myTopic);
		$this->assign('publish', $publish);
		$this->display();
	}
	
	//帖子详情页面
	public function topicDetail(){
		$clubid = $_GET['id'];
		//社团信息
		$clubInfo = D( 'Club' )->getClubById($clubid, $this->mid);
		
		$topicid = $_GET['topicid'];
		//帖子详细信息
		$topicInfo = D( 'ClubTopic' )->getTopicById($topicid);
		if( $topicInfo['isdel'] ==1 ){
			$this->error("帖子已经被删除!");
		}
		$topicInfo['title'] = htmlspecialchars($topicInfo['title']);
		$topicInfo['followstate'] = D('Follow','weibo')->getState($this->mid, $topicInfo['uid'], 0);
		if( $topicInfo['replyman'] == 0 ){
			$topicInfo['whoreply'] = '所有人可回复';
		}else if( $topicInfo['replyman'] == 1 ){
			$topicInfo['whoreply'] = '仅社团成员可回复';			
		}
		//获取该条帖子的回复内容
		$replyList = D( 'ClubReply' )->getReplyByTopicid( $topicid );
		foreach ($replyList['data'] as &$reply){
			$reply['reply1'] = D( 'ClubReply' )->getReplyByReplyid( $reply['id'] );
			$reply['reply1count'] = count( $reply['reply1'] );
			foreach ($reply['reply1'] as &$v){
				$v['time'] = date("Y-m-d H:i", $v['ctime']);
			}
			$reply['followstate'] = D('Follow','weibo')->getState($this->mid, $reply['uid'], 0);
		}
		//dump($replyList);exit;
		//点击量加1
		$uid = $this->mid;
		if( empty( $_COOKIE['click_'.$topicid.'_'.$uid] ) ){
			setcookie("click_".$topicid."_".$uid, "1", time()+3600*24);
			D( 'ClubTopic' )->addClickCount( $topicid );	
		}
		
		//获取当前用户是否具有回复帖子的权限
		$replyman = $topicInfo['replyman'];
		$replylimit = $this->getReplyLimit($replyman, $this->mid, $clubid);
		
		$this->assign( 'mid', $this->mid );
		$this->assign( 'clubInfo', $clubInfo );
		$this->assign('topicInfo', $topicInfo);
		$this->assign('replyList', $replyList);
		$this->assign('replylimit', $replylimit);
		$this->display();
	}
	
	private function doTopicList($topicList){
		foreach ($topicList as &$topic){
			$topic['title'] = htmlspecialchars($topic['title']);
			if( strlen($topic['title'])>20 ){
				$topic['title'] = mStr(text($topic['title']),20);
			}
			if( $topic['ctime'] == null ){
				$topic['ctime'] = '';
			}else{
				$topic['ctime'] = date("m-d H:i", $topic['ctime']);
			}
			if( $topic['replytime'] == null ){
				$topic['replytime'] = '';
			}else{
				$topic['replytime'] = date("m-d H:i", $topic['replytime']);
			}
			
			preg_match_all("/\\<img.*?src\\=\"(.*?)\"[^>]*>/i", $topic['content'], $imgArr);
			$topic['imgcount'] = count($imgArr[1]);
			if($imgArr){
				$topic['imglist'][0]['num'] = 1;
				$topic['imglist'][0]['url'] = $imgArr[1][0];
				$topic['imglist'][1]['num'] = 2;
				$topic['imglist'][1]['url'] = $imgArr[1][1];
				$topic['imglist'][2]['num'] = 3;
				$topic['imglist'][2]['url'] = $imgArr[1][2];
			}
			$topic['content'] = text( $topic['content'] );
			if( strlen($topic['content'])>28 ){
				$topic['content'] = mStr($topic['content'],28);
			}
		}
		return $topicList;
	}
	
	//获取用户是否具有发帖的权限
	private function getPubTopicLimit($pubtopic, $uid, $clubid){
		if( $pubtopic == 0 ){
			$ismember = isClubMember($uid, $clubid);
			if( $ismember ){
				$publish = 1;
			}
		}else if( $pubtopic == 1 ){
			$publish = 1;
		}
		return $publish;
	}
	
	//获取是否具有回复帖子的权限
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
	
	//回复帖子
	public function doAddTopicReply(){
		$map['topicid'] = $_POST['topicid'];
		$map['content'] = $_POST['content'];
		
		if( empty($map['content']) ){
			$this->error("回复内容不能为空！");
		}
		$map['content'] = str_replace('\"', '"', $map['content']);
		$map['content'] = str_replace('target="_self"', 'target="_blank"', $map['content']);
		$topicInfo = D( 'ClubTopic' )->getTopicById( $map['topicid'] );
		
		$map['uid'] = $this->mid;
		$map['replyid'] = 0;
		$map['replyuid'] = $topicInfo['uid'];
		$maxFloor = D( 'ClubReply' )->getMaxFloor( $map['topicid'] );
		if( $maxFloor == null ){
			$map['floor'] = 2;
		}else{
			$map['floor'] = $maxFloor + 1;
		}
		$map['ctime'] = time();
		//dump($map);exit;
		$rs = D('ClubReply')->addReply( $map );
		if ($rs) {
			//更新帖子的回复信息:回复数,回复时间,回复人的uid
			$data['replytime'] = time();
			$data['replyuid'] = $this->mid;
			D( 'ClubTopic' )->updateReplyInfoInTopic( $data, $map['topicid'] );
			echo 1;
		} else {
			echo 0;
		}
	}
	
	public function doAddReply1(){
		$map['topicid'] = $_POST['topicid'];
		$map['replyid'] = $_POST['replyid'];
		$map['content'] = $_POST['content'];
		$map['uid'] = $this->mid;
		$map['ctime'] = time();
		
		if( !$map['topicid'] || !$map['replyid'] ){
			$this->error("回复错误！");
		}
		$map['content'] = str_replace('&nbsp;', '', $map['content']);
		$map['content'] = str_replace('<br>', '', $map['content']);
		$map['content'] = trim($map['content']);
		//dump($map['content']);exit;
		if( empty($map['content']) || $map['content']==''){
			echo 2;
			exit();
		}
		if( strstr($map['content'], 'public/js/editor/editor/plugins/smiley/48x48') ){
			echo 3;
			exit();
		}
		
		$replyInfo = D( 'ClubReply' )->getReplyById( $map['replyid'] );
		$map['replyuid'] = $replyInfo['uid'];
		
		$rs = D( 'ClubReply' )->addReply( $map );
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//对帖子进行相关操作,包括:置顶与取消置顶,推荐与取消推荐
	public function operateTopic(){
		$id = $_POST['id'];
		$type = $_POST['type'];
		$num = $_POST['num'];
		
		if( $type == 1 ){
			$map['zding'] = $num;
		}else if( $type == 2 ){
			$map['tjian'] = $num;
		}else{
			$this->error("操作错误!");
		}
		
		$rs = D( 'ClubTopic' )->operateTopic($map, $id);
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	public function delTopic(){
		$id = $_POST['id'];
		$map['isdel'] = 1;
		
		if( !$id ){
			$this->error("操作错误!");
		}
		
		$rs = D( 'ClubTopic' )->operateTopic($map, $id);
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	public function setBanner(){
		$banner = $_POST['banner'];
		$id     = $_POST['id'];
		if ($banner) {
			$result = D('Club')->where(array('id'=>$id))->save(array('banner'=>$banner));
			if ($result==1) {
				echo 1;
				exit();
			}else{
				echo 0;
				exit();
			}
		}else{
			echo 0;
			exit();
		}
	}
}
?>