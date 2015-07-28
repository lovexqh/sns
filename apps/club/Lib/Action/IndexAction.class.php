<?php
class IndexAction extends Action {
	const INDEX_TYPE_WEIBO = 0;
	const INDEX_TYPE_GROUP = 1;
	const INDEX_TYPE_ALL = 2;
	private $allClubList;
	function _initialize() {
		
	}
	
	function init() {
		echo './Public/miniblog.js';
	}
	
	function index() {
		$clubMember = D( 'ClubMember' );
		$clubTopic = D( 'ClubTopic' );
		$clubNotice = D( 'ClubNotice' );
		$club = D( 'Club' );
		$uid = $this->mid;
		
		//获取最新公告
		$noticeList = $clubNotice->getAllNoticeList(8);
		foreach ($noticeList as &$val){
			$clubinfo = $club->getClubById($val['clubid']);
			$val['name'] = $clubinfo['title'];
			if( strlen($val['name'])>9 ){
				$val['name'] = mStr(text($val['name']),9);
			}
			if( strlen($val['content'])>20 ){
				$val['content'] = mStr(text($val['content']),20);
			}
		}
		
		//get my club
		$myClub = $clubMember->getClubByUid($uid);
		foreach ($myClub as &$value){
			$clubId = $value['id'];
			$value['hotTopic'] = $clubTopic->getHotTopicByClubid($clubId);
			$topicPic = $clubTopic->getTopicPic($clubId);
			if(count($topicPic) < 4){
				for($i = count($topicPic);$i < 4; $i++){
					$topicPic[$i] = array("topicpic"=>"default");
				}
			}
			$value['description'] = htmlspecialchars($value['description']);
			if( strlen($value['description'])>18 ){
				$value['description'] = mStr(text($value['description']),18);
			}
			$value['hotTopicPic'] = $topicPic;
			$value['applyMember'] = count($clubMember->getApplyMember($clubId));
			$value['topicNum'] = count($clubTopic->getTopicNumByClubid($clubId));
			$value['isManager'] = isManager($this->mid, $clubId);
		}

		//获取社团风采部分，根据活动获取
		$eventShow = $clubTopic->getNewEventShow();
		foreach ($eventShow as &$event){
			if( strlen($event['title'])>20 ){
				$event['title'] = mStr(text($event['title']),20);
			}
		}
		
		//获取热门社团
		$hotClub = $clubTopic->getTopicHotClub(6, 5);
		
		$this->assign('myClub', $myClub);
		$this->assign('noticeList', $noticeList);
		$this->assign('eventShow', $eventShow);
		$this->assign('eventCount',count($eventShow));//记录风采数量
		$this->assign('hotClub', $hotClub);
		$this->display ();
	}
	
	/**
	 * @title  获取所有社团
	 * @description 
	 * @param
	 * @return
	 * @author	lihao 2013-12-10
	 */
	public function allClub($club_type=1){
		//获取最新公告
		$clubNotice = D( 'ClubNotice' );
		$club = D('Club');
		$noticeList = $clubNotice->getAllNoticeList(8);
		foreach ($noticeList as &$val){
			$clubinfo = $club->getClubById($val['clubid']);
			$val['name'] = $clubinfo['title'];
			if( strlen($val['name'])>9 ){
				$val['name'] = mStr(text($val['name']),9);
			}
			if( strlen($val['content'])>20 ){
				$val['content'] = mStr(text($val['content']),20);
			}
		}
		$this->assign('noticeList', $noticeList);
		if($_POST['club_type']){
			$club_type = $_POST['club_type'];
		}
		if(empty($this->allClubList)){
			$allClub = $club->getAllClub(null, '0,1000', $this->mid);
			$this->allClubList['school'] = array();
			$this->allClubList['academy'] = array();
			$this->allClubList['popular'] = array();
			
			foreach ($allClub as $club){
				$type = $club['type'];
				if(!is_array($club)){
					$type = 4;
				}
				//拼接图片路径 src="__ROOT__/thumb.php?w=60&h=60&t=f&url={$vo['logo']|get_photo_url}"
				$logo = $club['logo'];
				$club['logo'] = get_photo_url($logo);
				//$type 1：校社团 2：院社团 3：学生社团
				if($type == 1){
					array_push($this->allClubList['school'], $club);
				}else if ($type == 2){
					array_push($this->allClubList['academy'], $club);
				}else if ($type == 3){
					array_push($this->allClubList['popular'], $club);
				}
			}	
		}

		if(!$_POST['club_type']){
			//根据请求返回相应社团
			if($club_type == 1){
				$this->assign('allClubList', $this->allClubList['school']);
			}else if($club_type == 2){
				$this->assign('allClubList', $this->allClubList['academy']);
			}else if($club_type == 3){
				$this->assign('allClubList', $this->allClubList['popular']);
			}
			//返回总的社团数量
			$this->assign('count', $allClub['count']);
			$this->display();
		}else{
			//根据请求返回相应社团
			if($club_type == 1){
				$data['club'] = $this->allClubList['school'];
			}else if($club_type == 2){
				$data['club'] = $this->allClubList['academy'];
			}else if($club_type == 3){
				$data['club'] = $this->allClubList['popular'];
			}
			//返回总的社团数量
			$data['count'] = $allClub['count'];
			echo (json_encode($data));
		}
	}
	
	/**
	 * @title  根据社团名称模糊查询社团
	 * @description 
	 * @param
	 * @return
	 * @author	lihao 2013-12-10
	 */
	public function ClubByTitle(){
		$title = $_POST['title'];
		$type = $_POST['type'];
		
		$club = D('Club');
		$allClub = $club->getClubByTitle($title, $type, null, $this->mid);
		$data['club'] = array();
		foreach ($allClub as $club){
			//拼接图片路径 src="__ROOT__/thumb.php?w=60&h=60&t=f&url={$vo['logo']|get_photo_url}"
			$logo = $club['logo'];
			$club['logo'] = get_photo_url($logo);
			array_push($data['club'], $club);
		}
		//$data['club'] = $allClub;
		$data['type'] = $type;
		$data['title'] = $title;
		echo (json_encode($data));
	}
	
	/**
	 * @title  根据社团名称模糊查询社团(社团首页的搜索按钮)
	 * @description
	 * @param
	 * @return
	 * @author	lihao 2013-12-10
	 */
	public function searchClub(){
		//获取最新公告
		$clubNotice = D( 'ClubNotice' );
		$club = D('Club');
		$noticeList = $clubNotice->getAllNoticeList(8);
		foreach ($noticeList as &$val){
			$clubinfo = $club->getClubById($val['clubid']);
			$val['name'] = $clubinfo['title'];
			if( strlen($val['name'])>9 ){
				$val['name'] = mStr(text($val['name']),9);
			}
			if( strlen($val['content'])>20 ){
				$val['content'] = mStr(text($val['content']),20);
			}
		}
		$this->assign('noticeList', $noticeList);
		
		$title = $_GET['title'];
		
		$club = D('Club');
		$clubTopic = D( 'ClubTopic' );
		$allClub = $club->getClubByTitle($title, null, null, $this->mid);
		foreach ($allClub as &$club){
			//拼接图片路径 src="__ROOT__/thumb.php?w=60&h=60&t=f&url={$vo['logo']|get_photo_url}"
			$logo = $club['logo'];
			$club['logo'] = get_photo_url($logo);
			$clubId = $club['id'];
			$club['topic'] = $clubTopic->getHotTopicByClubid($clubId);
		}
		$this->assign("ClubList", $allClub);
		$this->display();
	}
	
	public function creatClub(){
		$this->display();
	}
	
	public function doCreatClub(){
		$map['title'] = $_POST['clubname'];
		$findClub = D( 'Club' )->getClubByClubname( $map['title'] );
		if( $findClub ){
			$this->error("社团名称已存在!");
		}
		
		$map['description'] = $_POST['clubdesc'];
		$map['type'] = $_POST['clubtype'];
		$map['uid'] = $this->mid;
		$map['pubtopic'] = $_POST['pubtopic'];
		$map['updoc'] = $_POST['updoc'];
		//$map['downdoc'] = $_POST['downdoc'];
		
		$options ['userId'] = $this->mid;
		$options ['max_size'] = 2 * 1024 * 1024; // 2MB
		$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
		$info = X ( 'Xattach' )->upload ( 'group_logo', $options );
		if ($info ['status']) {
			$map ['logo'] = $info ['info'] [0] ['savepath'] . $info ['info'] [0] ['savename'];
		}
		$map['membercount'] = 1;
		$map['ctime'] = time();
		$addRs = D( 'Club' )->add ( $map );
		if($addRs){
			$data['clubid'] = $addRs;
			$data['uid'] = $this->mid;
			$data['username'] = getUserName( $data['uid'] );
			$ucUid = M('ucenter_user_link')->where('uid = '.$data['uid'])->getField('uc_uid');
			$userinfo = get_baseinfo_by_uid($ucUid);
			if($userinfo['identitytype'] == 3){ //学生
				$studentinfo = getStudentinfoByUid($ucUid);
				$data['grade'] = $studentinfo[0]['nj'];
			}else if($userinfo['identitytype'] == 2){
				$data['grade'] = 1;
			}
			$data['type'] = 1;
			$data['ctime'] = $map['ctime'];
			$data['mtime'] = $map['ctime'];
			if( D('ClubMember')->add($data) ){
				$this->success('创建成功!');
				redirect(U('club/Index/index'));
			}else{
				$this->error('创建失败!!');
				redirect(U('club/Index/index'));
			}
		}else{
			$this->error('创建失败!!');
			redirect(U('club/Index/index'));
		}
// 		if($addRs){
// 			//$this->assign('jumpUrl', U('club/Index/index'));
// 			echo "<script>ui.success('操作成功!');ui.box.close();window.location.reload();</script>";
// 		}
// 		if ( $addRs ) {
// 			$this->success ( '创建成功', 'club/Index/index' );
// 		} else {
// 			$this->error ( '创建失败' );
// 		}
		
	}
	
	public function editClub(){
		$clubid = $_GET['clubid'];
		if(!$clubid){
			$this->error('社团错误!');
		}
		$clubinfo = D( 'Club' )->getClubById($clubid);
		if(!$clubinfo){
			$this->error('社团不存在或已被删除!');
		}
		$clubinfo['description'] = htmlspecialchars($clubinfo['description']);
		if( $clubinfo['type'] == 1 ){
			$clubinfo['typename'] = '校社团';
		}else if( $clubinfo['type'] == 2 ){
			$clubinfo['typename'] = '院社团';
		}else if( $clubinfo['type'] == 3 ){
			$clubinfo['typename'] = '学生社团';
		}
		$this->assign('clubinfo', $clubinfo);
		$this->display();
	}
	
	public function doEditClub(){
		$clubid = $_POST['clubid'];
		if(!$clubid) $this->error('社团错误!');
		
// 		$map['title'] = $_POST['clubname'];
// 		$findClub = D( 'Club' )->getClubByClubname( $map['title'], $clubid );
// 		if( $findClub ){
// 			$this->error("社团名称已存在!");
// 		}
		
		$map['description'] = $_POST['clubdesc'];
// 		$map['type'] = $_POST['clubtype'];
		$map['pubtopic'] = $_POST['pubtopic'];
		$map['updoc'] = $_POST['updoc'];
		//$map['downdoc'] = $_POST['downdoc'];
		
		if($_FILES['logo']['size'] > 0) {
			$options ['userId'] = $this->mid;
			$options ['max_size'] = 2 * 1024 * 1024; // 2MB
			$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
			$info = X ( 'Xattach' )->upload ( 'group_logo', $options );
			if ($info ['status']) {
				$map ['logo'] = $info ['info'] [0] ['savepath'] . $info ['info'] [0] ['savename'];
			}
		}
		
		$editRs = D( 'Club' )->editClub($map, $clubid);
		if($editRs>=0){
			$this->success('修改成功!');
			redirect(U('club/Topic/index&id='.$clubid));
		}else{
			$this->error('创建失败!');
		}
	}
	
	public function chkClubname(){
		$clubname = $_POST['clubname'];
		$findClub = D( 'Club' )->getClubByClubname($clubname);
		if( $findClub ){
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>