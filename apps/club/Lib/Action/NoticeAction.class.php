<?php
class NoticeAction extends Action {
	const INDEX_TYPE_WEIBO = 0;
	const INDEX_TYPE_GROUP = 1;
	const INDEX_TYPE_ALL = 2;
	function _initialize() {
		
	}
	
	function init() {
		echo './Public/miniblog.js';
	}
	
// 		$clubTopic = D( 'ClubTopic' );
	
// 		$myClub = $clubMember->getClubByUid($uid);

	/**
	 * 显示公告
	 * lihao
	 */
	public function index(){
		$clubid = $_GET['id'];
		$nid = $_GET['nid'];
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		$clubNotice = D('ClubNotice');
		$noticeList = $clubNotice->getNoticeList($clubid);
		//日期处理将日、月分离
		$flag = '';
		$noticeFlag = 1;
		foreach($noticeList as &$notice){
			$notice['content'] = htmlspecialchars($notice['content']);
			$notice['month'] = date('m月',$notice['ctime']);
			$notice['day']=date('d日',$notice['ctime']);
			
			if($flag == ''){
				$notice['flag'] = $noticeFlag;
			}else{
				if($flag == date('Y-m-d',$notice['ctime'])){
					$notice['flag'] = $noticeFlag;  //标识页面显示位置
				}else{
					if($noticeFlag == 1){
						$noticeFlag = 2;
					}else{
						$noticeFlag = 1;
					}
					$notice['flag'] = $noticeFlag;  //标识页面显示位置
				}
			}
			$flag = date('Y-m-d',$notice['ctime']);
		}
		$this->assign( $headData );
		$this->assign("noticeList", $noticeList);
		$this->assign("isManager",isManager($this->mid, $clubid));
		$this->assign("nid",$nid);
		$this->display();
	}
	
	/**
	 * 保存公告
	 * lihao
	 */
	public function saveNotice(){
		$clubNotice = D('ClubNotice');
		$result = $clubNotice->saveNotice();
		echo $result;
	}
	
	/**
	 * 删除公告
	 */
	public function delNotice(){
		$clubNotice = D('ClubNotice');
		$result = $clubNotice->delNotice();
		echo $result;
	}
}
?>