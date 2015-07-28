<?php
class MemberAction extends Action {
	const INDEX_TYPE_WEIBO = 0;
	const INDEX_TYPE_GROUP = 1;
	const INDEX_TYPE_ALL = 2;
	function _initialize() {
		
	}
	
	function init() {
		echo './Public/miniblog.js';
	}
	
	//成员
	public function index(){
		$memberKey = $_POST['memberKey'];
		if( $memberKey ) {
			$map['username'] = array('like', '%'.$memberKey.'%');
		}
		
		$deptid = $_GET['deptid'];
		if( $deptid ) {
			$map['deptid'] = $deptid;
		}
		
		$clubid = $_GET['id'];
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		//获取社团部门
		$deptList = D( 'ClubDept' )->getDeptByClubid($clubid);
		
		//获取社团成员列表
		$map['clubid'] = $clubid;
		$memberList = D( 'ClubMember' )->getMemberByClubid( $map );
		foreach ($memberList['data'] as &$value){
			if($value['mtime']==null || $value['mtime']==0){
				$value['mtime'] = '';
			}
			$value['joindate'] = date('Y-m-d', $value['mtime']);
			if( $value['type'] == 1 ){
				$value['identity'] = '创建者';
			}else if ( $value['type'] == 2 ){
				$value['identity'] = '管理员';
			}else if ( $value['type'] == 3 ){
				$value['identity'] = '成员';
			}
		}
		//dump($memberList);exit;
		$this->assign( $headData );
		$this->assign('deptList', $deptList);
		$this->assign('memberList', $memberList);
		$this->display();
	}
	
	/**
	 * @title  更新部门名
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-5
	 */
	public function updateDeptname(){
		$id = $_POST['id'];
		$deptinfo = D('ClubDept')->getDeptById($id);
		$map['deptname'] = $_POST['deptname'];
		$isExist = D('ClubDept')->chkDeptName($deptinfo['clubid'], $map['deptname'], $id);
		if($isExist){
			echo 3;
			exit();
		}
		$map['inputuid'] = $this->mid;
		$updateRs = D( 'ClubDept' )->updateDept( $id, $map );
		if($updateRs >= 0){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/**
	 * @title  删除部门
	 * @description 
	 * @param  部门id
	 * @return
	 * @author	xiawei 2013-12-6
	 */
	public function delDept(){
		$id = $_POST['id'];
		
		//查找该部门下的成员
		$deptMember = D( 'ClubMember' )->getMemberByDeptid( $id );
		
		$delRs = D( 'ClubDept' )->delDept( $id );
		if($delRs){
			//若删除的部门中有成员,将这些成员的部门设为空
			if( $deptMember ){
				D( 'ClubMember' )->setMemberDeptid($id);
			}
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/**
	 * @title  添加部门
	 * @description 
	 * @param  社团id, 部门名
	 * @return
	 * @author	xiawei 2013-12-6
	 */
	public function addDept(){
		$map['clubid'] = $_POST['clubid'];
		$deptInClub = D( 'ClubDept' )->getDeptByClubid( $map['clubid'] );
		$deptCount = count($deptInClub);
		if($deptCount > 9){
			echo 2;
			exit();
		}
		$map['deptname'] = $_POST['deptname'];
		$isExist = D('ClubDept')->chkDeptName($map['clubid'], $map['deptname']);
		if($isExist){
			echo 3;
			exit();
		}
		$map['inputuid'] = $this->mid;
		$addRs = D( 'ClubDept' )->addDept( $map );
		if($addRs){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//更改成员type
	public function chgMemberType(){
		$id = $_POST['id'];
		$type = $_POST['type'];
		if( !$id ){
			exit();
		}
		$meminfo = D( 'ClubMember' )->getMemberById($id);
		if( in_array($type, array(3,4,5)) ){
			$managerList = D( 'ClubMember' )->getManagerByClubid( $meminfo['clubid'] );
			if( count($managerList) == 1 && $meminfo['uid'] == $this->mid ){
				echo 2;
				exit();
			}
		}
		$map['type'] = $type;
		$rs = D( 'ClubMember' )->chgMemberType($id, $map);
		if($rs){
			$this->updateMemberCount($meminfo['clubid']);
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//待审核成员页面
	public function auditMember(){
		$clubid = $_GET['id'];
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		//查找待审核的成员
		$auditMember = D( 'ClubMember' )->getAuditMember($clubid);
		
		$deptList = D('ClubDept')->getDeptByClubid($clubid);
		
		$this->assign( $headData );
		$this->assign('auditMember', $auditMember);
		$this->assign('deptList', $deptList);
		$this->display();
	}
	
	//没课成员页面
	public function noclassMember(){
		$clubid = $_GET['id'];
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
	
		$searchtime = $_POST['searchtime'];
// 		if($searchtime){
// 			$week = D('Course','teaching')->datetoweek( $searchtime );
// 			if($week > 24 || $week < 1){
// 				$this->error("日期非本学期");
// 			}
// 		}
		
		$classno = "";
		for($i=1; $i<=6; $i++){
			$class = $_POST['class'.$i];
			if( $class == 'on' ){
				$classno .= $i.',';
			}
		}
		if( $searchtime && $classno ){
			$haveClassInfo = $this->findHaveClassMember($clubid, $searchtime, $classno);
			//dump($haveClassInfo);exit;
			foreach ($haveClassInfo as $val){
				$uid = M('ucenter_user_link')->where('uc_uid = '.$val)->getField('uid');
				$haveClassUid[] = $uid;
			}
			$memList = D( 'ClubMember' )->getMemberInfoByClubid( $clubid );
			foreach ($memList as $v){
				if( !in_array($v['uid'], $haveClassUid) ){
					$noClassMember[] = $v;
				}
			}
			foreach ($noClassMember as &$value){
				if( $value['type'] == 1 ){
					$value['identity'] = '创建者';
				}else if ( $value['type'] == 2 ){
					$value['identity'] = '管理员';
				}else if ( $value['type'] == 3 ){
					$value['identity'] = '成员';
				}
			}
			$this->assign('search', 1);
			$this->assign('noClassMember', $noClassMember);
		}else if( !$searchtime && $classno ){
			$this->error("请选择日期!");
		}else if( $searchtime && !$classno ){
			$this->error("请选择上课节次!");
		}
		
		$this->assign( $headData );
		$this->display();
	}
	
	//退出成员页面
	public function cancelMember(){
		$clubid = $_GET['id'];
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		//查找已退出的成员
		$cancelMember = D( 'ClubMember' )->getCancelMember($clubid);
		foreach ($cancelMember['data'] as &$value){
			if($value['mtime']==null){
				$value['mtime'] = '';
			}
			$deptInfo = D( 'ClubDept' )->getDeptById( $value['deptid'] );
			$value['deptname'] = $deptInfo['deptname'];
		}
		
		$this->assign( $headData );
		$this->assign( 'cancelMember', $cancelMember );
		$this->display();
	}
	
	//拒绝待审核成员
	public function auditRejectMember(){
		$id = $_POST['id'];
		$mtime = time();
		$delRs = D( 'ClubMember' )->auditRejectMember($id, $mtime);
		if($delRs){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//修改社团成员所在部门
	public function chgMemberDept(){
		$clubid = $_GET['clubid'];
		$deptid = $_GET['deptid'];
		$memid = $_GET['memid'];
		if(!$clubid || !$memid) exit();
		//查找该社团下的部门
		$deptList = D( 'ClubDept' )->getDeptByClubid( $clubid );
		$this->assign('deptList', $deptList);
		$this->assign('deptid', $deptid);
		$this->assign('memid', $memid);
		$this->display();
	}
	
	//修改社团成员所在的部门
	public function doChgMemberDept(){
		$data['deptid'] = $_POST['deptid'];
		$memid = $_POST['memid'];
		if(!$memid){
			$this->error("操作失败!");
		}
		if(!$data['deptid']){
			$data['deptid'] = 0;
		}
		$meminfo = D( 'ClubMember' )->getMemberById($memid);
		$clubid = $meminfo['clubid'];
		if( $meminfo['type'] == 0 ){
			$data['type'] = 3;
			$data['mtime'] = time();
		}
		$rs = D('ClubMember')->chgMemberDept($data, $memid);
		if($rs >= 0){
			$this->updateMemberCount($clubid);
			echo 1;
		}else{
			echo 0;
		}
	}
	
	private function updateMemberCount($clubid){
		$map['membercount'] = D( 'ClubMember' )->getMemberCount($clubid);
		D( 'Club' )->updateClub($clubid, $map);
	}
	
	//查询某个成员的课程表
	public function classTable(){
		$uid = $_GET['uid'];
		
		$ucUid = M('ucenter_user_link')->where('uid = '.$uid)->getField('uc_uid');
		$userinfo = get_baseinfo_by_uid($ucUid);
		
		$data = D('Course','teaching')->getThisXnd();
		$xnd = $data['xnd'];
		$kkxq = $data['kkxq'];
		if($userinfo['identitytype'] == 3){
			$course = D('Course','teaching')->getStudentCourse($ucUid,$xnd,$kkxq);
		}else{
			$course = D('Course','teaching')->getTeacherCourse($ucUid,$xnd,$kkxq);
		}
		//dump($course);
		//本周是第几周
		$thisweek = D('Course','teaching')->datetoweek(date('Y-m-d',time()));
		$thiswk = date("w",time());
		//本周开始日期
		$headdate = add_date(date("Y-m-d",time()),'-'.((int)$thiswk-1));
		
		$this->assign('course',$course[$thisweek]);
		$this->assign('headdate',$headdate);
		$this->display();
	}
	
	//申请加入社团
	public function applyJoinClub(){
		$clubid = $_GET['clubid'];
		if(!clubid){
			$this->error("操作失败!");
		}
		$this->assign('clubid', $clubid);
		$this->display();
	}
	
	public function doApplyJoinClub(){
		$clubid = $_POST['clubid'];
		$reason = $_POST['reason'];
		if(!clubid || !$reason){
			$this->error("操作失败!");
		}
		$member = D( 'ClubMember' )->getMemberInfoInClub($clubid, $this->mid);
		if( $member ){
			$map['type'] = 0;
			$map['reason'] = $reason;
			$map['ctime'] = time();
			$map['mtime'] = '';
			$rs = D( 'ClubMember' )->updateMember($member['id'], $map);
		}else{
			$map['clubid'] = $clubid;
			$map['reason'] = $reason;
			$map['uid'] = $this->mid;
			$map['username'] = getUserName($map['uid']);
			
			$ucUid = M('ucenter_user_link')->where('uid = '.$map['uid'])->getField('uc_uid');
			$userinfo = get_baseinfo_by_uid($ucUid);
			if($userinfo['identitytype'] == 3){ //学生
				$studentinfo = getStudentinfoByUid($ucUid);
				$map['grade'] = $studentinfo[0]['nj'];
			}else if($userinfo['identitytype'] == 2){	//老师
				$map['grade'] = 1;
			}
			$map['type'] = 0;
			$map['ctime'] = time();
			$rs = D( 'ClubMember' )->addMember($map);
		}
		
		if( $rs >= 0 ){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//根据日期,节次查找没课的成员
	private function findHaveClassMember($clubid, $searchtime, $classno){
		//社团成员uid,字符串形式
		$uidList = D( 'ClubMember' )->getMemberInfoByClubid( $clubid );
		$stustr = "";
		$teastr = "";
		foreach ($uidList as $value){
			$ucUid = M('ucenter_user_link')->where('uid = '.$value['uid'])->getField('uc_uid');
			$userinfo = get_baseinfo_by_uid($ucUid);
			if( $userinfo['identitytype'] == 2 ){
				$teastr .= $ucUid.',';
			}else if( $userinfo['identitytype'] == 3 ){
				$stustr .= $ucUid.',';
			}
		}
		
		//第几周
		$week = D('Course','teaching')->datetoweek( $searchtime );
		//星期几
		$weeknum = date("w", strtotime($searchtime));
		//上课节次,字符串形式
		$classno = substr($classno,0,(strlen($classno)-1));
		//获取的字段
		//$columns = 'uid, member.identityID, member.identityType';
		
		if($teastr){
			$teastr = substr($teastr,0,(strlen($teastr)-1));
			$haveClassTeacherUids = getHaveClassTeacherUids($teastr, $week, $weeknum, $classno);
		}else{
			$haveClassTeacherUids = "";
		}
		if($stustr){
			$stustr = substr($stustr,0,(strlen($stustr)-1));
			$haveClassUids = getHaveClassUids($stustr, $week, $weeknum, $classno);
		}else{
			$haveClassUids = "";
		}
		
		$uids = array();
		foreach ($haveClassTeacherUids as $val){
			$uids[] = $val['uid'];
		}
		foreach ($haveClassUids as $v){
			$uids[] = $v['uid'];
		}
		//$uids = array_merge($haveClassUids, $haveClassTeacherUids);
		//dump($haveClassTeacherUids);exit;
		
		return $uids;
	}
	
	public function chkSearchtime(){
		$searchtime = $_POST['searchtime'];
		$week = D('Course','teaching')->datetoweek( $searchtime );
		if($week>24 || $week<1){
			echo 0;
		}else{
			echo 1;
		}
	}
	
	
	
	
}
?>