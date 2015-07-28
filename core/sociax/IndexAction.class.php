<?php
class IndexAction extends Action{
	
	private $societyId;//圈子ID
	private $status;//1成员访客  0非成员访客
    private $societyInfo;//圈子信息
    private $type;//0自定义 1班级 2专业 3年级 4院系 5老师
    private $typeArray = array('0','1','2','3','4','5');//圈子类型
    private $societyTypeid;//相对应的$society_type1，2，3，4的ID
    private $userInfo;//用户社区信息
    private $ucInfo;//用户UIA信息
    private $memberUser;//用户Member信息
    private $m_uid;//用户Member信息
    private $societyListOfMy;//用户自定义圈子
	/**
	 +----------------------------------------------------------
	 * 社交圈初始化函数
	 +----------------------------------------------------------
	 * @author ssq  2014-1-10
	 +----------------------------------------------------------
	 */
	public function _initialize() {
		$this->m_uid = getMid();
		$this->userInfo = $_SESSION['userInfo'];
		$this->ucInfo   = $_SESSION['ucInfo'];
		$this->memberUser = get_user_by_uid(getUcUid($_SESSION['mid']));
		$this->societyId = isset($_REQUEST['societyId']) ? intval($_REQUEST['societyId']) : NULL;
		$this->type = isset($_REQUEST['type']) ? intval($_REQUEST['type']):NULL;
		if (($this->societyId==''||$this->societyId==null) && ($this->type==''||type==null)) { //通过社区模块进入
			switch ($this->memberUser[0]['identitytype']) {
				case 1://管理员
					$this->getBaseInfo();
					break;
				case 2://老师
					$param['type']   = 5;
					if ($this->ucInfo['depid']==''||$this->ucInfo['depid']==null) {
						$this->getBaseInfo();
						break;
					}
					$param['typeid'] = $this->ucInfo['depid'];//部门ID
					$this->societyInfo = D('Society')->getSocietyInfoBypara($param);
					if ($this->societyInfo) {
						$this->status = 1;
						$this->societyId = $this->societyInfo['id'];
						$this->type = $this->societyInfo['type'];
					}else{
						$param['societyName'] = $this->ucInfo['departname'];//部门名称
						$this->addSociety($param, 1);
					}
					$this->getBaseInfo();
					break;
				case 3://学生
					$param['type']   = 1;
					if ($this->ucInfo['bjid']==''||$this->ucInfo['bjid']==null) {
						$this->getBaseInfo();
						break;
					}
					$param['typeid'] = $this->ucInfo['bjid'];//班级ID
					$this->societyInfo = D('Society')->getSocietyInfoBypara($param);
					if ($this->societyInfo) {
						$this->status = 1;
						$this->societyId = $this->societyInfo['id'];
						$this->type = $this->societyInfo['type'];
					}else{
						$param['societyName'] = $this->ucInfo['bm'];//班名
						$this->addSociety($param, 1);
					}
					$this->getBaseInfo();
					break;
				case 4://暂无该角色
					$this->errorJump('没有该用户身份！', U('desktop/Index/index'));
					break;
				case 5://校友
					$this->getBaseInfo();
					break;
				default:
					$this->getBaseInfo();
					break;
			}
		}else if ($this->societyId && ($this->type==''||$this->type==null)) { //通过societyId访问
			$param['id'] = $this->societyId;
			$param['isDel'] = 0;
			$this->societyInfo = D('Society')->getSocietyInfoBypara($param);
			$yesOrNo = D('SocietyMember')->checkMemberBySociety($this->societyId,$this->m_uid); //1 是 0 否
			$enter = $_REQUEST['enter'];
			if ($this->societyInfo) {
				if ($this->societyInfo['visitable']==0) {
					if ($yesOrNo==0 && ($enter==''||$enter==null)) {
						$this->backJump('该圈子设置了访问权限，您没有访问权限！');
					}
				}
				$this->societyId = $this->societyInfo['id'];
				$this->type = $this->societyInfo['type'];
				$this->status = $yesOrNo;
				$this->getBaseInfo();
			}else{
				$this->errorJump('没有该圈子！', U('desktop/Index/index'));
			}
		}else if (($this->societyId==''||$this->societyId==null) && $this->type) { //通过圈子快捷模块进入
			switch ($this->type) {
				case 1://学生班级
					$param['type']   = 1;
					if (($this->ucInfo['bjid']==''||$this->ucInfo['bjid']==null)) {
						$this->errorJump('无该学生的班级信息！', U('society/Index/showOthersIndex'));
						break;
					}
					$param['typeid'] = $this->ucInfo['bjid'];//班级ID
					$this->societyInfo = D('Society')->getSocietyInfoBypara($param);
					if ($this->societyInfo) {
						$this->status = 1;
						$this->societyId = $this->societyInfo['id'];
						$this->type = $this->societyInfo['type'];
					}else{
						$param['societyName'] = $this->ucInfo['bm'];//班名
						$this->addSociety($param, 1);
					}
					$this->getBaseInfo();
				break;
				case 2://学生专业
					$param['type']   = 2;
					if (($this->ucInfo['zyid']==''||$this->ucInfo['zyid']==null)) {
						$this->errorJump('无该学生的专业信息！', U('society/Index/showOthersIndex'));
						break;
					}
					$param['typeid'] = $this->ucInfo['zyid'];//专业ID
					$this->societyInfo = D('Society')->getSocietyInfoBypara($param);
					if ($this->societyInfo) {
						$this->status = 1;
						$this->societyId = $this->societyInfo['id'];
						$this->type = $this->societyInfo['type'];
					}else{
						$param['societyName'] = $this->ucInfo['zymc'];//专业名称
						$this->addSociety($param, 1);
					}
					$this->getBaseInfo();
				break;
				case 3://学生年级
					$param['type']   = 3;
					if (($this->ucInfo['nj']==''||$this->ucInfo['nj']==null)) {
						$this->errorJump('无该学生的年级信息！', U('society/Index/showOthersIndex'));
						break;
					}
					$param['typeid'] = $this->ucInfo['nj'];//年级
					$this->societyInfo = D('Society')->getSocietyInfoBypara($param);
					if ($this->societyInfo) {
						$this->status = 1;
						$this->societyId = $this->societyInfo['id'];
						$this->type = $this->societyInfo['type'];
					}else{
						$param['societyName'] = $this->ucInfo['nj'];//年级
						$this->addSociety($param, 1);
					}
					$this->getBaseInfo();
				break;
				case 4://学生院系
					$param['type']   = 4;
					if (($this->ucInfo['yxid']==''||$this->ucInfo['yxid']==null)) {
						$this->errorJump('无该学生的院系信息！', U('society/Index/showOthersIndex'));
						break;
					}
					$param['typeid'] = $this->ucInfo['yxid'];//院系ID
					$this->societyInfo = D('Society')->getSocietyInfoBypara($param);
					if ($this->societyInfo) {
						$this->status = 1;
						$this->societyId = $this->societyInfo['id'];
						$this->type = $this->societyInfo['type'];
					}else{
						$param['societyName'] = $this->ucInfo['yxmc'];//院系名称
						$this->addSociety($param, 1);
					}
					$this->getBaseInfo();
				break;
				case 5://老师部门
					$param['type']   = 5;
					if (($this->ucInfo['depid']==''||$this->ucInfo['depid']==null)) {
						$this->errorJump('无您的部门信息！', U('society/Index/showOthersIndex'));
						break;
					}
					$param['typeid'] = $this->ucInfo['depid'];//部门ID
					$this->societyInfo = D('Society')->getSocietyInfoBypara($param);
					if ($this->societyInfo) {
						$this->status = 1;
						$this->societyId = $this->societyInfo['id'];
						$this->type = $this->societyInfo['type'];
					}else{
						$param['societyName'] = $this->ucInfo['departname'];//部门名称
						$this->addSociety($param, 1);
					}
					$this->getBaseInfo();
				break;
				
				default:
					$this->errorJump('没有您访问的圈子信息！', U('desktop/Index/index'));
				break;
			}
		}
	}
	
	public function index(){
		if ($this->societyId) {
	        $userVisitedInfo = D('SocietyVisitor','societyvisitor')->checkUserVisited($this->societyId,$this->m_uid);
	        if($userVisitedInfo){
	        	$now = date('Y-m-d',time());
	        	$previous = date('Y-m-d',$userVisitedInfo['cTime']);
	        	if(($this->status == 1) && $userVisitedInfo['status']==0){
	        		D('SocietyVisitor')->innerMemberVisitor(array('sv_id'=>$userVisitedInfo['sv_id']));
	        	}
	        	if(($this->status == 0) && $userVisitedInfo['status']==1){
	        		D('SocietyVisitor')->outOfMemberVisitor(array('sv_id'=>$userVisitedInfo['sv_id']));
	        	}
	        	if (strtotime($now) != strtotime($previous)) {
		            $saveVisitor = D('SocietyVisitor','societyvisitor')->updateUserVisited($this->societyId,$this->m_uid,$this->status,$userVisitedInfo['sv_id'],$userVisitedInfo['times']);
	        	}
	        }else{
	            $addVisitor = D('SocietyVisitor','societyvisitor')->addUserVisitor($this->societyId,$this->m_uid,$this->status);
	            $userVisitedInfo = D('SocietyVisitor','societyvisitor')->checkUserVisited($this->societyId,$this->m_uid);
	            $now = date('Y-m-d',time());
	            $previous = date('Y-m-d',$userVisitedInfo['cTime']);
	            if(($this->status == 1) && $userVisitedInfo['status']==0){
	            	D('SocietyVisitor')->innerMemberVisitor(array('sv_id'=>$userVisitedInfo['sv_id']));
	            }
	            if(($this->status == 0) && $userVisitedInfo['status']==1){
	            	D('SocietyVisitor')->outOfMemberVisitor(array('sv_id'=>$userVisitedInfo['sv_id']));
	            }
	            if (strtotime($now) != strtotime($previous)) {
	            	$saveVisitor = D('SocietyVisitor','societyvisitor')->updateUserVisited($this->societyId,$this->m_uid,$this->status,$userVisitedInfo['sv_id'],$userVisitedInfo['times']);
	            }
	        }
	        $this->getSocietyVisitor($this->societyId);
	        $this->message();
		}else{
			$this->getSocietyVisitor($this->societyId);
			$this->showOthersIndex();
		}
	}

	/**
	 * -----------------------------
	 * 创建圈子 【方法】
	 * -----------------------------
	 * @param 	array 
	 * @param	{0 自定义 1校方圈子}
	 * @return 	返回操作的条数 
	 * @author 	ssq  2014-1-10
	 */
	function addSociety($param,$type){
		if(isset($param['societyName'])){
			if ($type==0) {
				//自定义
			}else if($type==1){
				//校方
				$param['icon']         = 'default.gif';
				$param['visitable']    = 1;
				$param['downloadable'] = 1;
				$param['joinable']     = 1;
				$param['sign']         = '不管遇到什么障碍，我都要朝着我的目标前进。';
				$param['tags']         = '青春,活力,奉献';
			}else{
				$this->errorJump('创建圈子参数错误！', U('desktop/Index/index'));
			}
			$param['cTime'] = time();
			$param['isDel'] = 0;
			$this->societyId = D('Society')->addSocietyInfoBypara($param); //返回操作成功的记录id
			
			$this->societyInfo = D('Society')->getSocietyInfoBypara(array('id'=>$this->societyId));
			$this->status = 1;
			$data['societyId'] = $this->societyId;
			$data['uid'] = $this->m_uid;
			if ($type==1) {
				$data['uid'] = 0;
			}
			$data['cTime'] = time();
			$data['status'] = '2';
			$data['isDel'] = '0';
			$result = D('SocietyMember')->addMemberBySocietyid_Uid($data);
			if ($result==0) {
				$this->errorJump('添加圈主成员失败！', U('desktop/Index/index'));
			}
			return $this->societyId;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 社交圈帖子列表
	 +----------------------------------------------------------
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	public function message(){
		//本圈帖子列表
		$messageList = $this->getMessagelist();
		$messageList = $this->doTopicList($messageList);
		$this->assign('messageList',$messageList);
		//本圈本人发帖统计
		$messageCount=$this->getMessageCount($this->societyId,$this->m_uid);
		$this->assign('messageCount',$messageCount);
		$this->assign('nav',1);
		$this->display('index');
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 添加帖子
	 +----------------------------------------------------------
	 * @author Snail 2013-11-22
	 +----------------------------------------------------------
	 */
	public function addMessage(){
		$data['title'] = htmlspecialchars(addslashes($_POST['p_title']));
		$content = stripslashes($_POST['p_content']);
		
		if(($data['title'])==''||($data['title'])==null) {
			returnMsg( "请填写标题",'-1');
			exit();
		}

//		if( mb_strlen($data['title'], 'UTF-8') > 25 ) {
//			returnMsg( "标题不得大于25个字符",'-1');
//			exit();
//		}
		//检查是否为空
		if( ($content==''||$content==null) || this_tags_html($content) == ''  ) {
			returnMsg( "请填写文字内容",'-1');
			exit();
		}
		//检查是否为空
// 		if( strlen(this_tags_html($content)) < 100) {
// 			returnMsg( "内容不能小于100字！",'-1');
// 			exit();
// 		}
		$data['mTime']=time();
		$societyId=intval(trim($_POST['p_societyId']));
		//检查是否为空
        if( ($societyId==''||$societyId==null)){
            returnMsg("无法获取ID",'-1');
        }
		//用是否传递$id判别是新增还是修改,并返回操作的帖子ID	
		if(($_POST['id']==''||$_POST['id']==null)){
			$data['societyId'] = $societyId;
			$data['uid'] = $this->m_uid;
			$data['cTime'] = time();
			$data['content']  = addslashes(this_tags_html(stripslashes($_POST['p_content'])));
			$resultId=D('SocietyMessage')->add($data);
		}else{
		    $id=intval($_POST('id'));
		    //检查是否存在,如果存在着按修改操作，如果不存在则按添加
		    $messageNum=D('SocietyMessage')->getSocietyMessageCount($societyId,$this->m_uid,$id);
		    if ($messageNum==1) {
		    	$data['id']=$id;
		        $result=D('SocietyMessage')->save($data);
		        if ($result==1) {
		            $resultId=$id;
		        }else{
		        	returnMsg( "帖子修改失败,请刷新后再试。",-1 );
		        }
		    }else{
		    	returnMsg( "您修改的帖子不存在。",-1 );
		    }
		}
		if(($resultId==''||$resultId==null)){
			returnMsg( "添加失败,请刷新后再试。",-1 );
		}else{
			returnMsg( $resultId,0 );
		}
	}
	
    /**
     *
    +----------------------------------------------------------
     * 社交圈帖子内容
    +----------------------------------------------------------
     * @author Kung 2013-12-11
    +----------------------------------------------------------
     */
    public function messView(){
        $id = intval($_GET['id']);
        $p  = isset($_GET['p'])?intval($_GET['p']):1;
        $society_comment = D('SocietyComment');
        $messResult = D('SocietyMessage')->getMessById($this->societyId,$id);
        if(!$messResult){
        	$this->errorJump('该新鲜事已经被删除', U('society/Index/index',array('societyId'=>$this->societyId)));
        }
        $messResult['title'] = stripslashes($messResult['title']);
        $messResult['content'] = stripslashes($messResult['content']);
        $commentRresult = $society_comment->getCommentLimit($this->societyId,$id,0,10); //$toId,$start,$limit
        $commentSon = $society_comment->getCommentHasSon($id);
       if($commentSon){
            foreach($commentRresult['data'] as $k=>$v){
                if(in_array($v['id'],$commentSon)){
                    $commentRresult['data'][$k]['son'] = $society_comment->getCommentLimit($this->societyId,$id,$v['id'],100);
                }else{
                    $commentRresult['data'][$k]['son'] = NULL;
                }
            }
        }
        $resultManage = D('SocietyMember')->getManagBySocietyid($this->societyId);//get society manager
        foreach($resultManage as $vm){
            $result_manager[] = $vm['uid'];
        }

        $floor = ($p-1)*2+1;
        static $per =1 ;
        $this->assign('result_manager',$result_manager);
        $this->assign('commentResult',$commentRresult);
        $this->assign('messResult',$messResult);
        $this->assign('messid',$id);
        $this->assign('uid',$this->m_uid);
        $this->assign('page',$p);
        $this->assign('floor',$floor);
        $this->assign('per',$per);
        $this->assign('nav',1);
        $this->display();
    }

	/**
	 * ----------------------------------------------------------
	 * 社交圈博客列表
	 * ----------------------------------------------------------
	 * @author Snail 2013-11-21 Kung rewite 13-12-19
	 * ----------------------------------------------------------
	 */
	public function blog(){
        $type = $this->societyInfo['type'];
        switch($type){
            case '0':
                //获取本社交圈所有成员
                $memberList=$this->getMemberBySocietyid($this->societyId);
                $uidstr = '-1';
                foreach($memberList as $k=>$v){
                    $uidstr .=','.$v['uid'];
                }
                $param['uidstr'] = ltrim($uidstr,',');
                $blogList=D('Blog','blog')->getBlogForSociety($param);
                break;
            case '1':
            	$param['bjid'] = $this->societyInfo['typeid'];
                $blogList=D('Blog','blog')->getBlogForSociety($param);
                break;
            case '2':
            	$param['zyid'] = $this->societyInfo['typeid'];
                $blogList=D('Blog','blog')->getBlogForSociety($param);
                break;
            case '3':
            	$param['nj'] = $this->societyInfo['typeid'];
                $blogList=D('Blog','blog')->getBlogForSociety($param);
                break;
            case '4':
            	$param['yxid'] = $this->societyInfo['typeid'];
                $blogList=D('Blog','blog')->getBlogForSociety($param);
                break;
            case '5':
            	$param['depid'] = $this->societyInfo['typeid'];
                $blogList=D('Blog','blog')->getBlogForSociety($param);
                break;
        }
        $blogList =$this-> doTopicList($blogList);
        $this->assign('blogList',$blogList);
        $this->assign('nav',2);
        $this->display();
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 社交圈相册列表
	 +----------------------------------------------------------
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	public function photo(){
        $type = $this->societyInfo['type'];
        $p = intval($_GET['p']);
        $photos = array();
        $memberIdArr = D('SocietyMember')->getMemberBySocietyid($this->societyId);
        switch($type){
            case '0':
        		$uidstr = '-1';
                foreach($memberIdArr as $k=>$v){
                    $uidstr .=','.$v['uid'];
                }
                $param['uidstr'] = $uidstr;
                $photos = D('Photo','photo')->getPhotoForSociety($param);
                break;
            case '1':
            	$param['bjid'] = $this->societyInfo['typeid'];
                $photos = D('Photo','photo')->getPhotoForSociety($param);
                break;
            case '2':
            	$param['zyid'] = $this->societyInfo['typeid'];
                $photos = D('Photo','photo')->getPhotoForSociety($param);
                break;
            case '3':
            	$param['nj'] = $this->societyInfo['typeid'];
                $photos = D('Photo','photo')->getPhotoForSociety($param);
                break;
            case '4':
            	$param['yxid'] = $this->societyInfo['typeid'];
                $photos = D('Photo','photo')->getPhotoForSociety($param);
                break;
            case '5':
            	$param['depid'] = $this->societyInfo['typeid'];
                $photos = D('Photo','photo')->getPhotoForSociety($param);
                break;
        }
        if($p>$photos['totalPages']){
            $photos ='';
        }
		$this->assign('picture',$photos);
		$this->assign('nav',3);
		$this->display();
	}
	
	/**
	 *
	 +----------------------------------------------------------
	 * AJAX 获取社交圈相册列表
	 +----------------------------------------------------------
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	public function photoAJAX(){
		$societyId=$_POST['societyId'];
		$lastDate=$_POST['lastDate'];
		$memberList=D('SocietyMember','society')->getMemberBySocietyid($societyId);
		$photos=D('Photo','photo')->getPhotoList($memberList,$lastDate);
		$this->assign('photoList',$photos);
		$this->display();
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 社交圈成员列表
	 +----------------------------------------------------------

	 * @author Snail 2013-11-29  Kung update 2013-12-16
	 +----------------------------------------------------------
	 */
	public function member(){
		$schoolid   = $GLOBALS["_SESSION"]['ucInfo']['schoolid'] ? $GLOBALS["_SESSION"]['ucInfo']['schoolid'] : 1;
        $type       = $this->societyInfo['type'];
        $yxid       = $this->societyInfo['typeid'];
        $zyid       = $this->societyInfo['typeid'];
        $bjid       = $this->societyInfo['typeid'];
        $nj         = $this->societyInfo['typeid'];
        $page       = isset($_GET['page'])?intval($_GET['page']):1;
        $notpage    = isset($_GET['notpage'])?intval($_GET['notpage']):1;
        $memberList = array();
        $diff = 1;
        switch($type){
            case '0':
                //获取本社交圈所有成员(已注册)
                $memberList = $this->getMemberBySocietyid($this->societyId);
                $this->assign('regMember',$memberList);
                $diff = 0;
                break;
            case '1':
                //获取已经注册的用户
                $memberLists = get_Student_baseinfo_by_org($schoolid,null,null,$bjid,null,1,12,$page);
                $memberList = $memberLists[0];
                foreach ($memberList['data'] as $v){
                    $reg_member[]= $v['uid'];
                }
                $map['uc_uid']  = array('in',$reg_member);
                $regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
                $page_show = $this->findPage($memberList['totalcount'],12,$page);

                //获取未注册的用户
                $memberListNots = get_Student_baseinfo_by_org($schoolid,null,null,$bjid,null,2,12,$notpage);
                $memberListNot  = $memberListNots[0];
                $NotRegMember   = $memberListNot['data'];
                $notPage_show = $this->findPage($memberListNot['totalcount'],12,$notpage,true);

                $allMember = $memberList['totalcount']+$memberListNot['totalcount'];
                $this->assign('reged',$memberList['totalcount']);
                $this->assign('notreged',$memberListNot['totalcount']);
                $this->assign('allmember',$allMember);
                $this->assign('regMember',$regMember);
                $this->assign('pageshow',$page_show);
                $this->assign('NotRegMember',$NotRegMember);
                $this->assign('notPageshow',$notPage_show);
                break;
            case '2':
                //获取已经注册的用户
                $memberLists = get_Student_baseinfo_by_org($schoolid,null,$zyid,null,null,1,12,$page);
                $memberList = $memberLists[0];
                foreach ($memberList['data'] as $v){
                    $reg_member[]= $v['uid'];
                }
                $map['uc_uid']  = array('in',$reg_member);
                $regMember = M('ucenter_user_link')->where($map)->field('uid')->select();

                $page_show = $this->findPage($memberList['totalcount'],12,$page);
                $this->assign('regMember',$regMember);
                $this->assign('pageshow',$page_show);

                //获取未注册的用户
                $memberListNots = get_Student_baseinfo_by_org($schoolid,null,$zyid,null,null,2,12,$notpage);
                $memberListNot  = $memberListNots[0];
                $NotRegMember   = $memberListNot['data'];
                $notPage_show = $this->findPage($memberListNot['totalcount'],12,$notpage,true);
                $this->assign('NotRegMember',$NotRegMember);
                $this->assign('notPageshow',$notPage_show);
                $allMember = $memberList['totalcount']+$memberListNot['totalcount'];
                $this->assign('reged',$memberList['totalcount']);
                $this->assign('notreged',$memberListNot['totalcount']);
                $this->assign('allmember',$allMember);
                break;
            case '3':
                //获取已经注册的用户
                $memberLists = get_Student_baseinfo_by_org($schoolid,null,null,null,$nj,1,12,$page);
                $memberList = $memberLists[0];
                foreach ($memberList['data'] as $v){
                    $reg_member[]= $v['uid'];
                }
                $map['uc_uid']  = array('in',$reg_member);
                $regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
                $page_show = $this->findPage($memberList['totalcount'],12,$page);
                $this->assign('regMember',$regMember);
                $this->assign('pageshow',$page_show);

                //获取未注册的用户
                $memberListNots = get_Student_baseinfo_by_org($schoolid,null,null,null,$nj,2,12,$notpage);
                $memberListNot  = $memberListNots[0];
                $NotRegMember   = $memberListNot['data'];
                $notPage_show = $this->findPage($memberListNot['totalcount'],12,$notpage,true);
                $this->assign('NotRegMember',$NotRegMember);
                $this->assign('notPageshow',$notPage_show);
                $allMember = $memberList['totalcount']+$memberListNot['totalcount'];
                $this->assign('reged',$memberList['totalcount']);
                $this->assign('notreged',$memberListNot['totalcount']);
                $this->assign('allmember',$allMember);
                break;
            case '4':
                //获取已经注册的用户
                $memberLists = get_Student_baseinfo_by_org($schoolid,$yxid,null,null,null,1,12,$page);
                $memberList = $memberLists[0];
                foreach ($memberList['data'] as $v){
                    $reg_member[]= $v['uid'];
                }
                $map['uc_uid']  = array('in',$reg_member);
                $regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
                $page_show = $this->findPage($memberList['totalcount'],12,$page);
                $this->assign('regMember',$regMember);
                $this->assign('pageshow',$page_show);

                //获取未注册的用户
          		$memberListNots = get_Student_baseinfo_by_org($schoolid,$yxid,null,null,null,2,12,$notpage);
                $memberListNot  = $memberListNots[0];
                $NotRegMember   = $memberListNot['data'];
                $notPage_show = $this->findPage($memberListNot['totalcount'],12,$notpage,true);
                $this->assign('NotRegMember',$NotRegMember);
                $this->assign('notPageshow',$notPage_show);
                $allMember = $memberList['totalcount']+$memberListNot['totalcount'];
                $this->assign('reged',$memberList['totalcount']);
                $this->assign('notreged',$memberListNot['totalcount']);
                $this->assign('allmember',$allMember);
                break;
            case '5':
                //获取已经注册的用户
                $memberLists = get_teacher_same_dept_includeMe($this->societyInfo['typeid'],($page-1),12,1);
                $memberList = $memberLists[0];
                foreach ($memberList['data'] as $v){
                    $reg_member[]= $v['uid'];
                }
                $map['uc_uid']  = array('in',$reg_member);
                $regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
                $page_show = $this->findPage($memberList['total'],12,$page);
                $this->assign('regMember',$regMember);
                $this->assign('pageshow',$page_show);

                //获取未注册的用户
          		$memberListNots =  get_teacher_same_dept_includeMe($this->societyInfo['typeid'],$notpage-1,12,2);
                $memberListNot  = $memberListNots[0];
                $NotRegMember   = $memberListNot['data'];
                $notPage_show = $this->findPage($memberListNot['total'],12,$notpage,true);
                $this->assign('NotRegMember',$NotRegMember);
                $this->assign('notPageshow',$notPage_show);
                $allMember = $memberList['total']+$memberListNot['total'];
                $this->assign('reged',$memberList['total']);
                $this->assign('notreged',$memberListNot['total']);
                $this->assign('allmember',$allMember);
                break;
        }
		$this->assign('diff',$diff);
		$this->assign('nav',6);
		$this->display();
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 文件分享
	 +----------------------------------------------------------
	 * @author Snail 2013-12-2
	 +----------------------------------------------------------
	 */
	public function share(){
		//获取请求页数
		$page = isset( $_GET['page'] ) ?  $_GET['page'] : 1;
		//暂用资源服务器
		$config = model('Xdata')->lget('resource');
		$this->assign('con',$config);
		$type = $this->societyInfo['type'];
		switch ($type) {
			case 1:
				$this->assign('typeid',$this->ucInfo['bjid']);
			break;
			case 2:
				$this->assign('typeid',$this->ucInfo['zyid']);
			break;
			case 3:
				$this->assign('typeid',$this->ucInfo['nj']);
			break;
			case 4:
				$this->assign('typeid',$this->ucInfo['yxid']);
			break;
			case 5:
				$this->assign('typeid',$this->ucInfo['depid']);
			break;
		}
		//获取分享列表
		$shareList=D('SocietyShare')->getSocietyShareBySocietyid($this->societyId,$page);
		$this->assign('shareList',$shareList);
		//分页
		$shareListCount=D('SocietyShare')->getCount($this->societyId);
		$html =$this->findPage($shareListCount, '20', $page);
		$this->assign('html',$html);
		$this->assign('uid',$this->m_uid);
		$this->assign('nav',4);
		$this->display();
	}

	/**
	 *
	 +----------------------------------------------------------
	 * 下载次数
	 +----------------------------------------------------------
	 * @author ssq 2013-12-10
	 +----------------------------------------------------------
	 */
	public function download(){
		$id = $_REQUEST['id'];
		$result=D('SocietyShare')->download($id);
		if($result){
			$filepath = $result['path'];
			$filename = $result['shareName'];
			$size = $result['size'];
			file_down($filepath,$filename,$size);
		}
	}

	/**
	 +----------------------------------------------------------
	 * 删除共享
	 +----------------------------------------------------------
	 * @author ssq 2013-12-10
	 +----------------------------------------------------------
	 */
	public function deleteShare(){
		$id = $_POST['id'];
		$result=D('SocietyShare')->deleteShare($id);
		if($result){
			echo $result;
		}
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 添加文件分享
	 +----------------------------------------------------------
	 * @author Snail 2013-12-2
	 +----------------------------------------------------------
	 */
	public function addShare(){
		$data=array();
		$data['societyId']=$this->societyId;

		$data['shareName']=$_POST['shareName'];
		$data['size']=ceil(($_POST['size'])/1024);
		$data['path']=$_POST['path'];
		$data['format']=str_replace('.', '', $_POST['ext']);
		$data['uid']=$this->m_uid;
		$data['cTime']=time();

		foreach ($data as $key=>$val){
			if(($val==''||$val==null))
				returnMsg('上传失败[code:'.$key.']',-1);
		}
		$result=D('SocietyShare')->add($data);
		if($result>0){
			returnMsg($result.'_'.getMid(),0);
		}else{
			returnMsg('上传失败',-1);
		}
	
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 校方圈子选举
	 +----------------------------------------------------------
	 * @author shiqiangshao 2013-12-06
	 +----------------------------------------------------------
	 */
	public function vote(){
		$schoolid   = $GLOBALS["_SESSION"]['ucInfo']['schoolid'] ? $GLOBALS["_SESSION"]['ucInfo']['schoolid'] : 1;
		$type       = $this->societyInfo['type'];
		$yxid       = $this->societyInfo['typeid'];
		$zyid       = $this->societyInfo['typeid'];
		$bjid       = $this->societyInfo['typeid'];
		$nj         = $this->societyInfo['typeid'];
		$page       = isset($_GET['page'])?intval($_GET['page']):1;
		$memberList = array();
		switch($type){
			case '1': //班级
				$memberLists = get_Student_baseinfo_by_org($schoolid,null,null,$bjid,null,1,12,$page);
				$memberList = $memberLists[0];
				foreach ($memberList['data'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
				$page_show = $this->findPage($memberList['totalcount'],12,$page);
				$managerList = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'status'=>array('NEQ','0'),'isDel'=>0));
				$this->assign('regMember',$regMember);
				$this->assign('pageshow',$page_show);
				break;
			case '2': //专业
				$memberLists = get_Student_baseinfo_by_org($schoolid,null,$zyid,null,null,1,12,$page);
				$memberList = $memberLists[0];
				foreach ($memberList['data'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
				$page_show = $this->findPage($memberList['totalcount'],12,$page);
				$managerList = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'status'=>array('NEQ','0'),'isDel'=>0));
				$this->assign('regMember',$regMember);
				$this->assign('pageshow',$page_show);
				break;
			case '3'://年级
				$memberLists = get_Student_baseinfo_by_org($schoolid,null,null,null,$nj,1,12,$page);
				$memberList = $memberLists[0];
				foreach ($memberList['data'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
				$page_show = $this->findPage($memberList['totalcount'],12,$page);
				$managerList = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'status'=>array('NEQ','0'),'isDel'=>0));
				$this->assign('regMember',$regMember);
				$this->assign('pageshow',$page_show);
				break;
			case '4'://院系
				$memberLists = get_Student_baseinfo_by_org($schoolid,$yxid,null,null,null,1,12,$page);
				$memberList = $memberLists[0];
				foreach ($memberList['data'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
				$page_show = $this->findPage($memberList['totalcount'],12,$page);
				$managerList = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'status'=>array('NEQ','0'),'isDel'=>0));
				$this->assign('regMember',$regMember);
				$this->assign('pageshow',$page_show);
				break;
			case '5'://部门
				$memberLists = get_teacher_same_dept_includeMe($this->societyInfo['typeid'],($page-1),12,1);
				$memberList = $memberLists[0];
				foreach ($memberList['data'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
				$page_show = $this->findPage($memberList['totalcount'],12,$page);
				$managerList = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'status'=>array('NEQ','0'),'isDel'=>0));
				$this->assign('regMember',$regMember);
				$this->assign('pageshow',$page_show);
				break;
		}
		foreach ($managerList as $value) {
			$List[] = $value['uid'];
		}
		$this->assign('List',$List);
		$this->assign('nav',5);
	    $this->display();
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 圈子印象墙
	 +----------------------------------------------------------
	 * @author Snail 2013-11-28
	 +----------------------------------------------------------
	 */
	public function wall(){
	    $wallList=D('SocietyWall')->getSocietyWallBySocietyid($this->societyId,10);
	    $this->assign('wallList',$wallList['data']);
	    $this->assign('html',$wallList['html']);
	    $this->assign('nav',7);
	    $this->display();
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 添加印象
	 +----------------------------------------------------------
	 * @return string
	 * @author Snail 2013-11-28
	 +----------------------------------------------------------
	 */
	public function addWall(){
		$yesOrNo = D('SocietyMember')->checkMemberBySociety($this->societyId,$this->m_uid);
		if ($yesOrNo==1) {
			returnMsg( "您是圈子成员，不可添印象！",-1 );
		}
	    $data['content'] =  htmlspecialchars(addslashes($_POST['content']));
	    $data['societyId']=$this->societyId;
	    $data['uid']=$this->m_uid;
	    $data['cTime']=time();
	    $data['isDel']=0;
	    $result=D('SocietyWall')->addWall($data);
	    if($result){
	    	returnMsg( $result,0 );
	    }else {
	    	returnMsg( "添加失败，请稍后再试",-1 );
	    }
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 本社交圈帖子列表
	 +----------------------------------------------------------
	 * @return array
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	private function getMessagelist(){
		$messageList=D('SocietyMessage')->getSocietyMessageBySocietyid($this->societyId,10);
	    foreach ($messageList['data'] as $key=>$item){
	    	$messageList['data'][$key]['commentNum']=D('SocietyComment')->getSocietyCommentCounts($item['societyId'],NULL,$item['id']);
            $result =D('SocietyComment')->getSocietyCommentLast($item['societyId'],$item['id']);
            $messageList['data'][$key]['commentUid'] = $result['uid'];
            $messageList['data'][$key]['commentTime'] = $result['cTime'];

	    }
	    return $messageList;
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 获取本圈帖子统计
	 +----------------------------------------------------------
	 * @param integer $societyId
	 * @param integer $uid
	 * @return array
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	private function getMessageCount($societyId=NULL,$uid=NULL){
		//本圈发帖统计
		$MessageCount['all']= D('SocietyMessage')->getSocietyMessageCount($societyId);
		//本人本圈发帖统计
		$MessageCount['self']=D('SocietyMessage')->getSocietyMessageCount($societyId,$uid);
		return $MessageCount;
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 根据圈子ID获取圈子内成员
	 +----------------------------------------------------------
	 * @param integer $societyId 圈子ID
	 * @return array  
	 * @author Snail 2013-11-21
	 +----------------------------------------------------------
	 */
	private function getMemberBySocietyid($societyId){
		$memberList=D('SocietyMember')->getMemberBySocietyid($societyId);
		return $memberList;
	}

    /**
     *
    +----------------------------------------------------------
     * 根据圈子ID获取圈子访客成员以及访问数量
    +----------------------------------------------------------
     * @param integer $societyId 圈子ID
     *
     * @author Kung 2013-12-11
    +----------------------------------------------------------
     */
    private function getSocietyVisitor($societyId){
        $visitorModel  = D('SocietyVisitor');
        
        $startTime = strtotime(date('Y-m-d',time()));
        $memberVisitor = $visitorModel->getMemVisitor($societyId,1,12);
        $member    = $visitorModel->getVisitorNum($societyId,1,$startTime);
        if (($member['allTimes']==''||$member['allTimes']==null)) {
        	$member['allTimes'] = '0';
        }
        $otherVisitor  = $visitorModel->getMemVisitor($societyId,0,12);
        $other     = $visitorModel->getVisitorNum($societyId,0,$startTime);
        if (($other['allTimes']==''||$other['allTimes']==null)) {
        	$other['allTimes'] = '0';
        }
        $resultManage = D('SocietyMember')->getManagBySocietyid($this->societyId);
        foreach($resultManage as $v){
        	$manageId[] = $v['uid'];
        }
        if(in_array($this->m_uid,$manageId))
        	$this->assign('isManage','1');
        else
        	$this->assign('isManage','0');
        $this->assign('societyManage',$resultManage);
        $this->assign('memberVisitor',$memberVisitor);
        $this->assign('memberTimes',$member);
        $this->assign('otherVisitor',$otherVisitor);
        $this->assign('otherTimes',$other);
    }

	private function getAddMessagePsot(){
	}
	
	public function showMyMess(){
		$this->display();
	}
	
	public function showBanJiOf9(){
		$param['s.status'] = 2;
		$param['sm.type'] = 1;
		$societyList = D('Society')->getSocietyBypara($param,$limit=9);
		foreach ($societyList['data'] as $key => $value) {
			$societyList['data'][$key]['societyName'] = stripslashes($value['societyName']);
		}
		$societyList = $this->getMessageOf4($societyList);
		$this->assign('type','banji');
		$this->assign('societyList',$societyList);
	}
	
	public function showBanJi(){
		$param['s.status'] = 2;
		$param['sm.type'] = 1;
		$societyList = D('Society')->getSocietyBypara($param,$limit=9);
		foreach ($societyList['data'] as $key => $value) {
			$societyList['data'][$key]['societyName'] = stripslashes($value['societyName']);
		}
		$societyList = $this->getMessageOf4($societyList);
		
		$this->assign('type','banji');
		$this->assign('societyList',$societyList);
		$this->display('showOthersIndex');
	}
	
	public function showZhuanYe(){
		$param['s.status'] = 2;
		$param['sm.type'] = 2;
		$societyList = D('Society')->getSocietyBypara($param,$limit=9);
		foreach ($societyList['data'] as $key => $value) {
			$societyList['data'][$key]['societyName'] = stripslashes($value['societyName']);
		}
		$societyList = $this->getMessageOf4($societyList);
		
		$this->assign('type','zhuanye');
		$this->assign('societyList',$societyList);
		$this->display('showOthersIndex');
	}
	
	public function showNianJi(){
		$param['s.status'] = 2;
		$param['sm.type'] = 3;
		$societyList = D('Society')->getSocietyBypara($param,$limit=9);
		foreach ($societyList['data'] as $key => $value) {
			$societyList['data'][$key]['societyName'] = stripslashes($value['societyName']);
		}
		$societyList = $this->getMessageOf4($societyList);
		
		$this->assign('type','nianji');
		$this->assign('societyList',$societyList);
		$this->display('showOthersIndex');
	}
	
	public function showYuanXi(){
		$param['s.status'] = 2;
		$param['sm.type'] = 4;
		$societyList = D('Society')->getSocietyBypara($param,$limit=9);
		foreach ($societyList['data'] as $key => $value) {
			$societyList['data'][$key]['societyName'] = stripslashes($value['societyName']);
		}
		$societyList = $this->getMessageOf4($societyList);
		
		$this->assign('type','yuanxi');
		$this->assign('societyList',$societyList);
		$this->display('showOthersIndex');
	}
	
	public function showBuMen(){
		$param['s.status'] = 2;
		$param['sm.type'] = 5;
		$societyList = D('Society')->getSocietyBypara($param,$limit=9);
		foreach ($societyList['data'] as $key => $value) {
			$societyList['data'][$key]['societyName'] = stripslashes($value['societyName']);
		}
		$societyList = $this->getMessageOf4($societyList);
		
		$this->assign('type','bumen');
		$this->assign('societyList',$societyList);
		$this->display('showOthersIndex');
	}
	
	public function showZiDingYi(){
		$param['s.status'] = 2;
		$param['sm.type'] = 0;
		$societyList = D('Society')->getSocietyBypara($param,$limit=9);
		foreach ($societyList['data'] as $key => $value) {
			$societyList['data'][$key]['societyName'] = stripslashes($value['societyName']);
		}
		$societyList = $this->getMessageOf4($societyList);
		
		$this->assign('type','zidingyi');
		$this->assign('societyList',$societyList);
		$this->display('showOthersIndex');
	}
	
	public function showSearch(){
		$societyName = $_GET['societyName'];
		if ($societyName) {
			$param['_string'] = 'sm.societyName like "%'.$societyName.'%"  OR sm.tags like "%'.$societyName.'%"';
			$param['s.status'] = 2;
			$societyList = D('Society')->getSocietyBypara($param,$limit=9);
			$societyList = $this->getMessageOf4($societyList,'3');
		}else{
			$societyList = null;
		}
		
		$this->assign('societyName',$societyName);
		$this->assign('societyList',$societyList);
		$this->assign('type','search');
		$this->display('showOthersIndex');
	}
	
	public function showOthersIndex(){
		$societyList = $this->showBanJiOf9();
		$this->display('showOthersIndex');
	}
	
	public function getMessageOf4($societyList,$limit=4){
		foreach ($societyList['data'] as $key=>$value) {
			$societyList['data'][$key]['message'] = D('SocietyMessage')->getSocietyMessagesBySocietyid($value['societyId'],$limit);
		}
		return $societyList;
	}
	
	public function openSociety(){
		$group_list = D('Follow','weibo')->getListByUid($this->m_uid,1);
		$this->assign('group_list',$group_list);
		$this->display();
	}
	
	/**
	 * 不同参数获取不同用户
	 * @param  string 关注 同班 同专业 同年级 同院系
	 * @return array 
	 * @author  ssq 2013-12-12
	 */
	public function getList(){
		$str = $_POST['str'];
		$this->display();
	}
	
	public function inviteFriends(){
		 $this->display();
	}
	
	public function more(){
		 $this->display('more');
	}
	
	public function setting(){
		 $this->display();
	}
	
	public function memberManager(){
		$memberList =D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'isDel'=>0));
		foreach ($memberList as $key => $value) {
			if($value['uid']==getMid()){
				$memberOfMy = $value;
			}
		}
		$this->assign('memberOfMy',$memberOfMy);
		$this->assign('memberList',$memberList);
		$this->display();
	}
	
	/**
	 * ------------------------------------------
	 * 退出圈子
	 * ------------------------------------------
	 * @author ssq 2014-1-10
	 */
	public function tuichuquanzi(){
		$members = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'isDel'=>0));
		$this->assign('members',$members);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 分页
	 +----------------------------------------------------------
	 * @param integer $societyId 圈子ID
	 * @return array
	 * @author ssq 2013-12-11
	 +----------------------------------------------------------
	 */
	private function findPage($list, $limit, $page, $isnot=false) {
		$url = __URL__ . '&act=' . ACTION_NAME;
		foreach ( $_REQUEST as $K => $V ) {
			if (strtolower ( $K ) != 'page'||strtolower ( $K ) != 'notpage')
				$url .= '&' . $K . '=' . $V;
		}
		if (! is_numeric ( $limit ) || ( int ) $limit != $limit || $limit < 1)
			$limit = 10;
		$total = $list;
		$num = ( int ) (($total - 1) / $limit) + 1;
		if($total <= $limit){
			return '';
			exit();
		}
		if ($page < 1)
			$page = 1;
		if ($page > $num)
			$page = $num;
	
		$html = '';
        if($isnot){
            $html .= $page == 1 ? '' : '<a href="' . $url . '&notpage=' . ($page - 1) . '">上一页</a>';
            $html .= $page == 1 ? '<span class="current">1</span>' : '<a href="' . $url . '&notpage=1">1</a>';
        }else{
            $html .= $page == 1 ? '' : '<a href="' . $url . '&page=' . ($page - 1) . '">上一页</a>';
            $html .= $page == 1 ? '<span class="current">1</span>' : '<a href="' . $url . '&page=1">1</a>';
        }
		$prev = $page - 2;

		if ($prev < 1 + 1)
			$prev = 1 + 1;
		if ($page < $num - 1) {
			$next = $page + 2;
		} else {
			$next = $num;
		}
		if ($next > $num - 1)
			$next = $num - 1;
		$html .= $prev > 2 ? '<span class="current">...</span>' : '';
		for($i = $prev; $i <= $next; $i ++) {
            if($isnot){
                $html .= $i == $page ? '<span class="current">' . $i . '</span>' : '<a href="' . $url . '&notpage=' . $i . '">' . $i . '</a>';
            }else{
                $html .= $i == $page ? '<span class="current">' . $i . '</span>' : '<a href="' . $url . '&page=' . $i . '">' . $i . '</a>';
            }
		}
		$html .= $next < $num - 1 ? '<span class="current">...</span>' : '';
        if($isnot){
            $html .= $page == $num ? ($page == 1 ? '' : '<span class="current">' . $num . '</span>') : '<a href="' . $url . '&notpage=' . $num . '">' . $num . '</a>';
            $html .= $page == $num ? '' : '<a href="' . $url . '&notpage=' . ($page + 1) . '">下一页</a>';
        }else{
            $html .= $page == $num ? ($page == 1 ? '' : '<span class="current">' . $num . '</span>') : '<a href="' . $url . '&page=' . $num . '">' . $num . '</a>';
            $html .= $page == $num ? '' : '<a href="' . $url . '&page=' . ($page + 1) . '">下一页</a>';
        }

		if ($total<$limit) {
			$html='';
		}
		return $html;
	}

	/**
	 +----------------------------------------------------------
	 * 验证圈子名称是否重名
	 +----------------------------------------------------------
	 * @param  $_POST string 圈子名称
	 * @return int societyId
	 * @author ssq 2013-12-19
	 +----------------------------------------------------------
	 */
	public function checkSocietyName(){
		$societyName = $_POST['societyName'];
		$result = D('Society')->getSocietyInfoBypara(array('societyName'=>$societyName));
		if ($result) {
			if($result['id'] == $this->societyInfo['id']){
				echo 0;
			}else{
				echo 1;
			}
		}else{
			echo 0;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 添加圈子
	 +----------------------------------------------------------
	 * @param array 圈子基本信息
	 * @return int societyId
	 * @author ssq 2013-12-19
	 +----------------------------------------------------------
	 */
	function doOpenSociety(){
		$data['societyName'] = htmlspecialchars(addslashes(trim($_POST['societyName'])));
		$data['societyName'] = str_replace(' ', '', $data['societyName']);
		$result = D('Society')->getSocietyInfoBypara(array('societyName'=>$data['societyName']));
		if ($result) {
			$this->error('该圈子名称已经存在!');
		}
		$data['sign'] = htmlspecialchars(addslashes(trim($_POST['sign'])));
		$data['sign'] = str_replace(' ', '', $data['sign']);
		$data['sign'] = str_replace('&nbsp;', '', $data['sign']);
		$data['tags'] = htmlspecialchars(addslashes(trim($_POST['tags'])));
		$data['tags'] = str_replace('&nbsp;', '', $data['tags']);
		$data['tags'] = str_replace('，', ',',$data['tags']);
		$data['tags'] = str_replace(' ', '',$data['tags']);
		$data['visitable'] = intval($_POST['visitable']);
		$data['joinable'] = intval($_POST['joinable']);
		$data['downloadable'] = intval($_POST['downloadable']);
		$data['type'] = '0';
		
		$options ['userId'] = $this->mid;
		$options ['max_size'] = 2 * 1024 * 1024; // 2MB
		$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
		$info = X ( 'Xattach' )->upload ( 'group_logo', $options );
		if ($info ['status']) {
			$data ['icon'] = $info ['info'] [0] ['savepath'] . $info ['info'] [0] ['savename'];
		} else {
			$data ['icon'] = 'default.gif';
		}
		
		$result = $this->addSociety($data,0);
		if ($result>0) {
			$this->successJump('创建圈子 "'.$data['societyName'].'" 成功！', (U('society/Index/index',array('societyId'=>$this->societyId))));
		}else {
			$this->error('创建失败，请稍后再试！');
		}
	}

	/**
	 +----------------------------------------------------------
	 * 修改圈子
	 +----------------------------------------------------------
	 * @param array 圈子基本信息
	 * @return int societyId
	 * @author ssq 2013-12-19
	 +----------------------------------------------------------
	 */
	function doSettingSociety(){
		$societyId = $_POST['societyId'];		
		$result =D('SocietyMember')->getSocietyInfoByParam(array('uid'=>$this->m_uid,'societyId'=>$societyId));
		if ($result['status']!='0') {
			$data['societyName'] = htmlspecialchars(addslashes(trim($_POST['societyName'])));		
			$data['societyName'] = str_replace(' ', '', $data['societyName']);
			$result = D('Society')->getSocietyInfoBypara(array('societyName'=>$data['societyName']));
			if ($result) {
				if($result['id'] != $this->societyInfo['id']){
					$this->error('该圈子名称已经存在!');
				}
			}
			$data['sign'] = htmlspecialchars(addslashes(trim($_POST['sign'])));		
			$data['sign'] =str_replace('&nbsp;', ',',$data['sign']);
			$data['sign'] =str_replace(' ', ',',$data['sign']);
			$data['tags'] = htmlspecialchars(addslashes(trim($_POST['tags'])));		
			$data['tags'] =str_replace('&nbsp;', ',',$data['tags']);
			$data['tags'] =str_replace('，', ',',$data['tags']);
			$data['tags'] =str_replace(' ', '',$data['tags']);
			$data['visitable'] = intval($_POST['visitable']);
			$data['joinable']  = intval($_POST['joinable']);		
			$data['downloadable'] = intval($_POST['downloadable']);		
			
			$options ['userId'] = $this->mid;
			$options ['max_size'] = 2 * 1024 * 1024; // 2MB
			$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
			$info = X ( 'Xattach' )->upload ( 'group_logo', $options );
			if ($info ['status']) {
				$data ['icon'] = $info ['info'] [0] ['savepath'] . $info ['info'] [0] ['savename'];
			}
			
			$result = D('Society')->settingSocietyBypara($data,$societyId);
			if ($result) {
				$this->successJump('修改成功！',(U('society/Index/index',array('societyId'=>$this->societyId))));
			}else{
				$this->successJump('您没有做任何修改！',(U('society/Index/index',array('societyId'=>$this->societyId))));
			}
		}else{
			$this->errorJump('您不是管理员，没有修改权限！',(U('society/Index/index',array('societyId'=>$this->societyId))));
		}
	}
	
	/**
	 * 删除圈子成员
	 * -------------------------------------------
	 * @param   post  uid(int)  societyId(int)
	 * @return  int   成功1  失败0
	 * @author  ssq   2013-12-23
	 * -------------------------------------------
	 */
	public function delMember(){
		$uid = $_POST['uid'];
		$result = D('SocietyMember')->delMember(array('uid'=>$uid,'societyId'=>$this->societyId));
		if ($result) {
			D('SocietyVisitor')->outOfMemberVisitor(array('uid'=>$uid,'societyId'=>$this->societyId));
			D('SocietyNews')->addNews(array('fromUid'=>$this->m_uid,'newsType'=>3,'result'=>1,'cTime'=>time(),'toUid'=>$uid,'societyId'=>$this->societyId));
			returnMsg($result,1);
		}else{
			returnMsg('操作失败，请稍后再试！',0);
		}		
	}
	
	/**
	 * 圈子成为升为OR取消管理员
	 * -------------------------------------------
	 * @param   post  uid(int)  societyId(int)
	 * @return  int   成功1  失败0
	 * @author  ssq   2013-12-23
	 * -------------------------------------------
	 */
	public function confManager(){
		$uid = $_POST['uid'];
		$status = $_POST['status'];
		$count = D('SocietyMember')->countMember(array('societyId'=>$this->societyId,'status'=>1));
		if (($count<3) || ($status==0)) {
			$result = D('SocietyMember')->memberManager(array('uid'=>$uid,'societyId'=>$this->societyId),$status);
			$list['societyId'] = $this->societyId;
			$list['fromUid'] = $this->m_uid;
			$list['toUid'] = $uid;
			$list['newsType'] = 4;
			$list['result'] = $status;
			$list['cTime'] = time();
			D('SocietyNews')->addNews($list);
			if ($result) {
				returnMsg($result,1);
			}else{
				returnMsg('操作失败，请稍后再试！',0);
			}
		}else{
			returnMsg('管理员不能超过三位！',0);
		}
	}
	
	/**
	 * 圈子消息列表
	 * -------------------------------------------
	 * @param   int   societyId
	 * @return  array 圈子消息列表
	 * @author  ssq   2013-12-23
	 * -------------------------------------------
	 */
	public function getNewsBySocietyId($societyId){
		$newsList = D('SocietyNews')->getNews($societyId);
		$this->assign('societyNews',$newsList);
	}
	
	/**
	 * 我的消息列表
	 * -------------------------------------------
	 * @param   int   uid
	 * @return  array 我的消息列表
	 * @author  ssq   2013-12-23
	 * -------------------------------------------
	 */
	public function getNewsCountByUid($uid,$societyListOfMy){
		$societyIdarray = '0';
		foreach ($societyListOfMy as $key => $value) {
			if ($value['status']>0) {
				$societyIdarray = $societyIdarray.','.$value['societyId'];
			}
		}
		$newsList = D('SocietyNews')->getNewsByUid($uid,$societyIdarray);
		foreach ($newsList as $key => $value) {
			if ($value['newsType']==1 && $value['result']==1) {
				unset($newsList[$key]);
			}
			if ($value['newsType']==1 && $value['result']==2 && $uid = $value['fromUid']) {
				unset($newsList[$key]);
			}
			if ($value['newsType']==3 && $value['result']==1) {
				unset($newsList[$key]);
			}
			if ($value['newsType']==2 && $value['result']==0) {
				unset($newsList[$key]);
			}
		}
		$myNewsList = D('SocietyNews')->getMyNewsByUid($uid);
		foreach ($myNewsList as $key => $value) {
			$newsList[] = $value;
		}
		
		$last = array();
		foreach ($newsList as $key => $value) {
			if (!(in_array($value, $last))) {
				$last[] = $value;
			}
		}
		$this->assign('myNews',$last);
	}
	
	/**
	 * 跳转圈子申请页面
	 * -------------------------------------------
	 * @param   null
	 * @return  null
	 * @author  ssq   2013-12-23
	 * -------------------------------------------
	 */
	public function joinSociety(){
		$societyName = $this->societyInfo['societyName'];
		$this->assign('societyName',$societyName);
		$this->display();
	}
	
	
	/**
	 * 首页加入跳转页面
	 * -------------------------------------------
	 * @param   null
	 * @return  null
	 * @author  ssq   2013-12-23
	 * -------------------------------------------
	 */
	public function joinSocietyIndex(){
		$societyName = $this->societyInfo['societyName'];
		$this->assign('societyName',$societyName);
		$this->display('joinSocietyIndex');
	}
	
	/**
	 * 圈子申请消息
	 * -------------------------------------------
	 * @param   null
	 * @return  int   返回插入的newsId
	 * @author  ssq   2013-12-23
	 * -------------------------------------------
	 */
	public function doJoinSociety(){
		$society = D('Society')->getSocietyInfoBySocietyid($this->societyId);
		$member = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'uid'=>$this->m_uid));
		if ($society['joinable']==0) {  //不需要验证
			if (!$member) {             //没加入过
				$result =D('SocietyMember')-> addMemberBySocietyid_Uid(array('societyId'=>$this->societyId,'uid'=>$this->m_uid,'status'=>0)); 
				if ($result>=1) {
					$lis = array();
					$lis['societyId'] = $this->societyId;
					$lis['fromUid'] = $this->m_uid;
					$lis['newsType'] = 2;
					$lis['result'] = 3;
					$lis['cTime'] = time();
					D('SocietyNews')->addNews($lis);
					returnMsg('加入圈子成功！',2);
				}else{
					returnMsg('申请操作失败，请稍后再试！',0);
				}
			}
			if ($member[0]['isDel']=='1') {//加入过  已删除成员
				$result = D('SocietyMember')->reMember(array('societyId'=>$this->societyId,'uid'=>$this->m_uid));
				if ($result==1) {
					$lis = array();
					$lis['societyId'] = $this->societyId;
					$lis['fromUid'] = $this->m_uid;
					$lis['newsType'] = 2;
					$lis['result'] = 3;
					$lis['cTime'] = time();
					D('SocietyNews')->addNews($lis);
					returnMsg('加入圈子成功！',2);
				}else{
					returnMsg('申请操作失败，请稍后再试！',0);
				}
			}
			if ($member[0]['isDel']=='0') {//加入过   未删除成员
				returnMsg('您已经加入了该圈子，无需申请加入该圈子！',0);
			}
		}else if($society['joinable']==1) { //需要验证
			if ($member[0]['isDel']=='0') {
				returnMsg('您已经加入了该圈子，无需申请加入该圈子！',0);
			}
			$list['societyId'] = $this->societyId;
			$list['fromUid'] = $this->m_uid;
			$list['newsType'] = 1;
// 			$result = D('SocietyNews')->getNewsByParam($list);
// 			if ($result) {
// 				returnMsg('您已经发出了申请请求，请勿重复操作！',0);
// 			}
			$list['cTime'] = time();
			$result = D('SocietyNews')->addNews($list);
			if ($result) {
				returnMsg('申请已发送，请耐心等候管理员回复！',1);
			}else{
				returnMsg('申请操作失败，请稍后再试！',0);
			}
		}
	}

    /**
     *
    +----------------------------------------------------------
     * 帖子回复
    +----------------------------------------------------------
     * @param integer $societyId 圈子ID
     *
     * @author Kung 2013-12-26
    +----------------------------------------------------------
     */
    function addComment(){
        $data['societyId'] = intval($_POST['p_societyid']);
        $data['messageId'] = intval($_POST['p_messageid']);
        $data['uid']       = intval($_POST['p_uid']);
        $data['toId']      = intval($_POST['p_toid']);
        $data['toUid']     = intval($_POST['p_touid']);
        $data['content']   = trim($_POST['p_content']);
        $data['content']   = safe($data['content']);
        $data['cTime']     = time();
        $data['isDel']     = 0;
        if($data['content']==''||$data['content']==null){
            returnMsg("评论内容不能为空",'-1');
            exit;
        }
        $result = D("SocietyComment")->addMainComment($data);
        if($result)
            returnMsg("评论添加成功",'0');
        else
            returnMsg("评论失败，请重新提交","-1");
    }

    /**
     *
     +----------------------------------------------------------
     * 帖子回复子回复
     +----------------------------------------------------------
     * @param integer $societyId 圈子ID
     *
     * @author Kung 2013-12-26
     +----------------------------------------------------------
     */
    function addCommentSon(){
    	$data['societyId'] = intval($_POST['p_societyid']);
    	$data['messageId'] = intval($_POST['p_messageid']);
    	$data['uid']       = intval($_POST['p_uid']);
    	$data['toId']      = intval($_POST['p_toid']);
    	$data['toUid']     = intval($_POST['p_touid']);
    	$data['content']   = trim($_POST['p_content']);
    	$data['cTime']     = time();
    	$data['isDel']     = 0;
    	$str = str_replace('&nbsp;', '', $data['content']);
    	$str = str_replace(' ', '', $str);
    	$str = str_replace('<br>', '', $str);
    	if($str==''){
    		returnMsg("评论内容不能为空",'-1');
    		exit;
    	}
    	$result = D("SocietyComment")->addMainComment($data);
    	if($result)
    		returnMsg("评论添加成功",'0');
    	else
    		returnMsg("评论失败，请重新提交","-1");
    }
	/**
	 * -------------------------------------------
	 * 删除消息
	 * -------------------------------------------
	 * @param   int  $newsId  消息newsId
	 * @return  int  返回操作条数
	 * @author  ssq  2013-12-24
	 * -------------------------------------------
	 */
	public function deleteNews(){
		$newsId = $_POST['newsId'];
		$result = D('SocietyNews')->deleteNews($newsId);
		if ($result==1) {
			returnMsg($result,1);
		}else{
			returnMsg($result,0);
		}
	}
	
	/**
	 * -------------------------------------------
	 * 个人忽略消息
	 * -------------------------------------------
	 * @param   int  $newsId  消息newsId
	 * @return  int  返回操作条数
	 * @author  ssq  2013-12-24
	 * -------------------------------------------
	 */
	public function ignoreNews(){
		$newsId = $_POST['newsId'];
		$news = D('SocietyNews')->getNewsByParam(array('newsId'=>$newsId));
		$list['fromUid'] = $this->m_uid;
		$list['toUid']   = '';
		$list['cTime']   = time();
		$list['result']  = 2;
		$result = D('SocietyNews')->ignoreNews($newsId,$list);
		if ($result==1) {
			returnMsg($result,1);
		}else{
			returnMsg($result,0);
		}
	}
	
	/**
	 * -------------------------------------------
	 * 圈子忽略消息
	 * -------------------------------------------
	 * @param   int  $newsId  消息newsId
	 * @return  int  返回操作条数
	 * @author  ssq  2013-12-24
	 * -------------------------------------------
	 */
	public function ignoreNewsS(){
		$newsId = $_POST['newsId'];
		$news = D('SocietyNews')->getNewsByParam(array('newsId'=>$newsId));
		$list['fromUid'] = $this->m_uid;
		$list['toUid']   = $news[0]['fromUid'];
		$list['cTime']   = time();
		$list['result']  = 2;
		$result = D('SocietyNews')->ignoreNews($newsId,$list);
		if ($result==1) {
			returnMsg($result,1);
		}else{
			returnMsg($result,0);
		}
	}
	
	/**
	 * -------------------------------------------
	 * 通过消息
	 * -------------------------------------------
	 * @param   int  $newsId  消息newsId
	 * @return  int  返回操作条数
	 * @author  ssq  2013-12-24
	 * -------------------------------------------
	 */
	public function tongGuoNews(){
		$newsId = $_POST['newsId'];
		$news = D('SocietyNews')->getNewsByParam(array('newsId'=>$newsId));
		$list['fromUid'] = $this->m_uid;
		$list['toUid']   = $news[0]['fromUid'];
		$list['cTime']   = time();
		$list['result']  = 1;
		$member = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'uid'=>$news[0]['fromUid']));
		if ($member) {
			if ($member[0]['isDel']==0) {
				$result = D('SocietyNews')->deleteNews($newsId);
				if ($result==1) {
					returnMsg('该成员已经加入，请勿重复加入！','-99');
				}else{
					returnMsg('操作失败，请稍后再试！',0);
				}
			}else if($member[0]['isDel']==1){
				D('SocietyNews')->tongGuoNews($newsId,$list);
				$result = D('SocietyMember')->reMember(array('societyId'=>$this->societyId,'uid'=>$news[0]['fromUid']));
				if ($result==1) {
					returnMsg($result,1);
				}else{
					returnMsg('操作失败，请稍后再试！',0);
				}
			}
		}else{
			D('SocietyNews')->tongGuoNews($newsId,$list);
			$result = D('SocietyMember')-> addMemberBySocietyid_Uid(array('societyId'=>$this->societyId,'uid'=>$news[0]['fromUid'],'status'=>0));
			if ($result>=1) {
				returnMsg($result,1);
			}else{
				returnMsg('操作失败，请稍后再试！',0);
			}
		}
	}
	
	/**
	 * -------------------------------------------
	 * 加入消息
	 * -------------------------------------------
	 * @param   int  $newsId  消息newsId
	 * @return  int  返回操作条数
	 * @author  ssq  2013-12-24
	 * -------------------------------------------
	 */
	public function joinNews(){
		$newsId = $_POST['newsId'];
		$news = D('SocietyNews')->getNewsByParam(array('newsId'=>$newsId));
		$list['fromUid'] = $news[0]['toUid'];
		$list['toUid']   = '';
		$list['cTime']   = time();
		$list['result']  = 1;
		$member = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'uid'=>$this->m_uid));
		if ($member) {
			if ($member[0]['isDel']==0) {
				$result = D('SocietyNews')->deleteNews($newsId);
				if ($result==1) {
					returnMsg('您已经是该圈子成员,请勿重复加入！','-99');
				}else{
					returnMsg('操作失败，请稍后再试！',0);
				}
			}else if($member[0]['isDel']==1){
				D('SocietyNews')->joinNews($newsId,$list);
				$result = D('SocietyMember')->reMember(array('societyId'=>$this->societyId,'uid'=>$this->m_uid));
				if ($result==1) {
					returnMsg($result,1);
				}else{
					returnMsg('操作失败，请稍后再试！',0);
				}
			}
		}else{
			D('SocietyNews')->joinNews($newsId,$list);
			$result = D('SocietyMember')-> addMemberBySocietyid_Uid(array('societyId'=>$this->societyId,'uid'=>$this->m_uid,'status'=>0));
			if ($result>=1) {
				returnMsg($result,1);
			}else{
				returnMsg('操作失败，请稍后再试！',0);
			}
		}
	}
	
	/**
	 * -------------------------------------------
	 * 通过圈子名称连接是否可浏览
	 * -------------------------------------------
	 * @param   int  $newsId  消息newsId
	 * @return  int  返回操作条数
	 * @author  ssq  2013-12-24
	 * -------------------------------------------
	 */
	public function visitable(){
		$result = D('Society')->getSocietyInfoBySocietyid($this->societyId);	
		$yesOrNo = D('SocietyMember')->checkMemberBySociety($this->societyId,$this->m_uid);
		if ($result['visitable']=='0'&&(!$yesOrNo)) {
			returnMsg('该圈子设置了圈子访问权限，您无权限访问！',0);
		}else{
			returnMsg('',1);
		}
	}
	/**
	 * -------------------------------------------
	 * 通过圈子帖子是否可浏览
	 * -------------------------------------------
	 * @param   int  $newsId  消息newsId
	 * @return  int  返回操作条数
	 * @author  ssq  2013-12-24
	 * -------------------------------------------
	 */
	public function message_visitable(){
		$result = D('Society')->getSocietyInfoBySocietyid($this->societyId);	
		$yesOrNo = D('SocietyMember')->checkMemberBySociety($this->societyId,$this->m_uid);
		if ($result['visitable']=='0'&&(!$yesOrNo)) {
			returnMsg('该圈子设置了圈子访问权限，您无权限访问！',0);
		}else{
			returnMsg('',1);
		}
	}

	private function doTopicList($topicList){
		foreach ($topicList['data'] as &$topic){
			preg_match_all("/\\<img.*?src\\=\"(.*?)\"[^>]*>/i", stripslashes($topic['content']), $imgArr);
			$topic['title'] = htmlspecialchars_decode(( stripslashes($topic['title'])));
			if( strlen($topic['title'])>=40 ){
				$topic['title'] = htmlspecialchars(getShort_S($topic['title'],20));
			}else{
				$topic['title'] = htmlspecialchars($topic['title']);
			}
 			$topic['content'] = stripslashes(strip_tags($topic['content']));
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
				
			if($imgArr){
				$topic['imglist'][0]['num'] = 1;
				$topic['imglist'][0]['url'] = $imgArr[1][0];
				if (($imgArr[1][0]==''||$imgArr[1][0]==null)) {
					unset($topic['imglist'][0]);
				}
				$topic['imglist'][1]['num'] = 2;
				$topic['imglist'][1]['url'] = $imgArr[1][1];
				if (($imgArr[1][1]==''||$imgArr[1][1]==null)) {
					unset($topic['imglist'][1]);
				}
				$topic['imglist'][2]['num'] = 3;
				$topic['imglist'][2]['url'] = $imgArr[1][2];
				if (($imgArr[1][2]==''||$imgArr[1][2]==null)) {
					unset($topic['imglist'][2]);
				}
			}
			$topic['imgcount'] = count($topic['imglist']);
		}
		return $topicList;
	}

    /**
     * -------------------------------------------
     * 是帖子回复删除机制 （网站创建者，圈子管理员，帖子发布者）
     * -------------------------------------------
     * @return     bool
     * @author  Kung  2013-12-30
     * -------------------------------------------
     */
    function deleteSon(){
        $id = intval($_POST['sonid']);
        $isManager = false;

        if(!$id){
            returnMsg('删除失败',1);
            exit;
        }
        $resultManage = D('SocietyMember')->getManagBySocietyid($this->societyId);//get society manager
        foreach($resultManage as $vm){
            $result_manager[] = $vm['uid'];
        }
        if($this->m_uid == 1 || in_array($this->m_uid,$result_manager))
            $isManager = true;
        $res = D('SocietyComment')->deleteMyComment($id,$this->m_uid,$isManager);
        if($res)
            returnMsg('删除成功',0);
        else
            returnMsg('删除失败',1);
    }
	
	/**
	 +----------------------------------------------------------
	 * 修改签名 标签 公告 Ajax
	 +----------------------------------------------------------
	 * @param array 圈子基本信息
	 * @return int societyId
	 * @author ssq 2013-12-19
	 +----------------------------------------------------------
	 */
	function ajaxSetting(){
		$societyId = $_POST['societyId'];
		$type = $_POST['type'];
		$result =D('SocietyMember')->getSocietyInfoByParam(array('uid'=>$this->m_uid,'societyId'=>$societyId));
		if ($result['status']!='0') {
			switch ($type){
				case '1':
						$data['sign'] = htmlspecialchars(addslashes($_POST['content']));
						break;
				case '2':
						$data['tags'] = htmlspecialchars(addslashes($_POST['content']));
						$data['tags'] = str_replace('，', ',',$data['tags']);
						$data['tags'] = str_replace(' ', '',$data['tags']);
						break;
				case '3':
						$data['notice'] = htmlspecialchars(addslashes($_POST['content']));
						break;
			}
			$result = D('Society')->settingSocietyBypara($data,$societyId);
			if ($result) {
				returnMsg($result,1);
			}else{
				returnMsg('您没有做任何修改！',0);
			}
		}else{
			returnMsg('您不是管理员，没有修改权限！',0);
		}
	}
	
	/**
	 * ---------------------------------------------------
	 * 邀请好友
	 * ---------------------------------------------------
	 * @param    int  $societyId 
	 * 			 string	POST $member
	 * @return   int $societyId
	 * @author   ssq  2013-12-30
	 * ---------------------------------------------------
	 */
	public function doYaoqingMember(){
		$member = $_POST['member'];
		$societyId = $_POST['societyId'];
		$uidList = explode(',',$member);
		foreach ($uidList as $val){
			$list = array();
			$list['societyId'] = $societyId;
			$list['fromUid'] = $this->m_uid;
			$list['toUid'] = $val;
			$list['newsType'] = 2;
			$list['cTime'] = time();
			D('SocietyNews')->addNews($list);
		}
		$param['to'] = $member;
		$param['content'] = '您好，邀请您加入圈子 "'.$this->societyInfo['societyName'].'" 请点击 <a href="'.U('society/Index/index').'" target="_blank">社交圈</a> 板块查看您的消息！';
		$res = model('Message')->postMessage($param, $this->m_uid);
	}
	
	/**
	 * --------------------------------------
	 * 邀请好友
	 * --------------------------------------
	 * @param   int   $societyId
	 * @return	array 关注的人列表 
	 * @author  ssq   2013-12-30
	 * --------------------------------------
	 */
	public function yaoqingMember(){
		$group_list['list'] = D('Follow','weibo')->getListByUid($this->m_uid,1);
		$memberList = D('SocietyMember')->getMemberBySocietyid($this->societyId);
		$group_list['page'] = 2;
		$group_list['type'] = 0;
		$this->assign('group_list',$group_list);
		$this->assign('memberCount',count($memberList));
		$this->assign('memberList',json_encode($memberList));
		$this->display('yaoqingMember');
	}	
	
	/**
	 * --------------------------------------
	 * 不同类型查询用户
	 * --------------------------------------
	 * @param   int   $societyId
	 * @return	array 关注的人列表
	 * @author  ssq   2013-12-30
	 * --------------------------------------
	 */
	public function searchUser(){
		$page     = $_REQUEST['page'];
		$type     = $_REQUEST['type'];
		$userName = $_REQUEST['userName'];
		$schoolid = $GLOBALS["_SESSION"]['ucInfo']['schoolid'] ? $GLOBALS["_SESSION"]['ucInfo']['schoolid'] : 1;
		$yxid     = $GLOBALS["_SESSION"]['ucInfo']['yxid'];
		$zyid     = $GLOBALS["_SESSION"]['ucInfo']['zyid'];
		$bjid     = $GLOBALS["_SESSION"]['ucInfo']['bjid'];
		$nj       = $GLOBALS["_SESSION"]['ucInfo']['nj'];
		
		if ($userName) {
			$group_list['list'] = D('UserOf')->getSocietyBypara($userName,$page);
			$group_list['page'] = $page+1;
			if (!$group_list['list']) {
				$group_list['list'] = D('UserOf')->getSocietyBypara($userName,1);
				$group_list['page'] = 2;
			}
			foreach ($group_list['list'] as $key=>$value) {
				$group_list['list'][$key]['href'] = getUserFace($value['uid'], 'm');
				$group_list['list'][$key]['userName'] = $value['uname'];
				$group_list['list'][$key]['fid'] = $group_list['list'][$key]['uid'];
				unset($group_list['list'][$key]['uid']);
			}
		}else{
			if ($type==0){
				$group_list['list'] = D('Follow','weibo')->getListByUid($this->m_uid,$page);
				$group_list['page'] = $page+1;
				if (!$group_list['list']) {
					$group_list['list'] = D('Follow','weibo')->getListByUid($this->m_uid,1);
					$group_list['page'] = 2;
				}
				foreach ($group_list['list'] as $key=>$value) {
					$group_list['list'][$key]['href'] = getUserFace($value['fid'], 'm');
					$group_list['list'][$key]['userName'] = getUserName($value['fid']);
				}
				$group_list['type'] = 0;
			}
			if ($type==1){
				$list = get_Student_baseinfo_by_org($schoolid, $yxid, $zyid, $bjid, $nj, 1, 21, $page);
				$group_list['list'] = $list[0]['data'];
				$group_list['page'] = $page+1;
				if (!$group_list['list']) {
					$list = get_Student_baseinfo_by_org($schoolid, $yxid, $zyid, $bjid, $nj, 1, 21, 1);
					$group_list['list'] = $list[0]['data'];
					$group_list['page'] = 2;
				}
				foreach ($group_list['list'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$group_list['list'] = array();
				$group_list['list'] = M('ucenter_user_link')->where($map)->field('uid')->select();
				foreach ($group_list['list'] as $key=>$value) {
					$group_list['list'][$key]['href'] = getUserFace($value['uid'], 'm');
					$group_list['list'][$key]['userName'] = getUserName($value['uid']);
					$group_list['list'][$key]['fid'] = $group_list['list'][$key]['uid'];
					unset($group_list['list'][$key]['uid']);
				}
				$group_list['type'] = 1;
			}
			if ($type==2){
				$list = get_Student_baseinfo_by_org($schoolid, $yxid, $zyid, null, $nj, 1, 21, $page);
				$group_list['list'] = $list[0]['data'];
				$group_list['page'] = $page+1;
				if (!$group_list['list']) {
					$list = get_Student_baseinfo_by_org($schoolid, $yxid, $zyid, null, $nj, 1, 21, 1);
					$group_list['list'] = $list[0]['data'];
					$group_list['page'] = 2;
				}
				foreach ($group_list['list'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$group_list['list'] = array();
				$group_list['list'] = M('ucenter_user_link')->where($map)->field('uid')->select();
				foreach ($group_list['list'] as $key=>$value) {
					$group_list['list'][$key]['href'] = getUserFace($value['uid'], 'm');
					$group_list['list'][$key]['userName'] = getUserName($value['uid']);
					$group_list['list'][$key]['fid'] = $group_list['list'][$key]['uid'];
					unset($group_list['list'][$key]['uid']);
				}
				$group_list['type'] = 2;
			}
			if ($type==3){
				$list = get_Student_baseinfo_by_org($schoolid, $yxid, null, null, $nj, 1, 21, $page);
				$group_list['list'] = $list[0]['data'];
				$group_list['page'] = $page+1;
				if (!$group_list['list']) {
					$list = get_Student_baseinfo_by_org($schoolid, $yxid, null, null, $nj, 1, 21, 1);
					$group_list['list'] = $list[0]['data'];
					$group_list['page'] = 2;
				}
				foreach ($group_list['list'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$group_list['list'] = array();
				$group_list['list'] = M('ucenter_user_link')->where($map)->field('uid')->select();
				foreach ($group_list['list'] as $key=>$value) {
					$group_list['list'][$key]['href'] = getUserFace($value['uid'], 'm');
					$group_list['list'][$key]['userName'] = getUserName($value['uid']);
					$group_list['list'][$key]['fid'] = $group_list['list'][$key]['uid'];
					unset($group_list['list'][$key]['uid']);
				}
				$group_list['type'] = 3;
			}
			if ($type==4){
				$list = get_Student_baseinfo_by_org($schoolid, $yxid, null, null, null, 1, 21, $page);
				$group_list['list'] = $list[0]['data'];
				$group_list['page'] = $page+1;
				if (!$group_list['list']) {
					$list = get_Student_baseinfo_by_org($schoolid, $yxid, null, null, null, 1, 21, 1);
					$group_list['list'] = $list[0]['data'];
					$group_list['page'] = 2;
				}
				foreach ($group_list['list'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$group_list['list'] = array();
				$group_list['list'] = M('ucenter_user_link')->where($map)->field('uid')->select();
				foreach ($group_list['list'] as $key=>$value) {
					$group_list['list'][$key]['href'] = getUserFace($value['uid'], 'm');
					$group_list['list'][$key]['userName'] = getUserName($value['uid']);
					$group_list['list'][$key]['fid'] = $group_list['list'][$key]['uid'];
					unset($group_list['list'][$key]['uid']);
				}
				$group_list['type'] = 4;
			}
			if ($type==5){
				$list = get_teacher_same_dept_includeMe($this->ucInfo['depid'],($page-1),21,1);
				$group_list['list'] = $list[0]['data'];
				$group_list['page'] = $page+1;
				if (!$group_list['list']) {
					$list = get_teacher_same_dept_includeMe($this->ucInfo['depid'],0,21,1);
					$group_list['list'] = $list[0]['data'];
					$group_list['page'] = 2;
				}
				foreach ($group_list['list'] as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				$group_list['list'] = array();
				$group_list['list'] = M('ucenter_user_link')->where($map)->field('uid')->select();
				foreach ($group_list['list'] as $key=>$value) {
					$group_list['list'][$key]['href'] = getUserFace($value['uid'], 'm');
					$group_list['list'][$key]['userName'] = getUserName($value['uid']);
					$group_list['list'][$key]['fid'] = $group_list['list'][$key]['uid'];
					unset($group_list['list'][$key]['uid']);
				}
				$group_list['type'] = 5;
			}
		}
		returnMsg($group_list,1);
	}
	
	/**
	 * --------------------------------------
	 * 我的所有圈子
	 * --------------------------------------
	 * @param   
	 * @return	
	 * @author  ssq   2013-12-30
	 * --------------------------------------
	 */
	public function societyList(){
		$param['sm.type'] = 0;
		$param['s.uid'] = $this->m_uid;
		$societyList = D('Society')->getSocietyBypara($param,$limit=12);
		
		$this->assign('societyList',$societyList);
		$this->display();
	}
	
	/**
	 * --------------------------------------
	 * 退出圈子
	 * --------------------------------------
	 * @param  $_POST
	 * @return json 
	 * @author ssq 2014-1-2
	 * --------------------------------------
	 */
	public function doTuiChuQuanZi(){
		$jiesan = $_REQUEST['jiesan'];
		$other = $_REQUEST['other'];
		$uid = $_REQUEST['uid'];
		$type = $_REQUEST['tuichuType'];
		if($type==1){
			if ($jiesan == 1) {
				$result = D('Society')->settingSocietyBypara(array('isDel'=>1,'dTime'=>time()),$this->societyId);
				if ($result==1) {
					$memberList =D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'isDel'=>0));
					$list['societyId'] = $this->societyId;
					$list['fromUid']   = $this->m_uid;
					$list['newsType']  = 4;
					$list['result']    = 3;
					$list['cTime']     = time();
					foreach ($memberList as $value) {
						$list['toUid']    = $value['uid'];
						D('SocietyMember')->delMember(array('id'=>$value['id']));
						D('SocietyNews')->addNews($list);
					}
					returnMsg('解散圈子成功！',1);
				}
			}else if($jiesan==0){
				//退出消息
				$list['societyId'] = $this->societyId;
				$list['fromUid']   = $this->m_uid;
				$list['newsType']  = 3;
				$list['result']    = 0;
				$list['cTime']     = time();
				if(!D('SocietyNews')->addNews($list)){
					returnMsg('退出圈子失败，稍后再试',0);
				};
				//圈主退出
				if(!D('SocietyMember')->delMember(array('societyId'=>$this->societyId,'uid'=>$this->m_uid))){
					returnMsg('退出圈子失败，稍后再试',0);
				}
				//交接消息
				$list['fromUid']   = $this->m_uid;
				if($other=='-99'){
					$list['toUid']     = $uid;
					if(!D('SocietyMember')->memberManager(array('societyId'=>$this->societyId,'uid'=>$uid),2)){
						returnMsg('退出圈子失败，稍后再试',0);
					}
				}else{
					$list['toUid']     = $other;
					if(!D('SocietyMember')->memberManager(array('societyId'=>$this->societyId,'uid'=>$other),2)){
						returnMsg('退出圈子失败，稍后再试',0);
					}
				}
				$list['newsType']  = 4;
				$list['result']    = 2;
				if(!D('SocietyNews')->addNews($list)){
					returnMsg('退出圈子失败，稍后再试',0);
				}else{
					returnMsg('',1);
				};
				
			}
		}else if($type==0){
			$list['societyId'] = $this->societyId;
			$list['fromUid']   = $this->m_uid;
			$list['newsType']  = 3;
			$list['cTime']     = time();
			$return = D('SocietyNews')->addNews($list);
			if ($return>=1) {
				$return = D('SocietyMember')->delMember(array('societyId'=>$this->societyId,'uid'=>$this->m_uid));
				if ($return==1) {
					returnMsg('退出圈子成功！',1);
				}else{
					returnMsg('退出圈子失败，稍后再试！',0);
				}
			}else{
				returnMsg('退出圈子失败，稍后再试',0);
			}
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * Logo更新上传
	 +----------------------------------------------------------
	 * @param array 圈子基本信息
	 * @return int societyId
	 * @author ssq 2013-12-19
	 +----------------------------------------------------------
	 */
	function doUploadIcon(){
		$options ['userId'] = $this->mid;
		$options ['max_size'] = 2 * 1024 * 1024; // 2MB
		$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
		$info = X ( 'Xattach' )->upload ( 'group_logo', $options );
		if ($info ['status']) {
			$data ['icon'] = $info ['info'] [0] ['savepath'] . $info ['info'] [0] ['savename'];
		}
		
		$result = D('Society')->settingSocietyBypara($data,$this->societyId);
		if ($result==1) {
			$this->successJump('Logo更新成功！', (U('society/Index/index',array('societyId'=>$this->societyId))));
		}else{
			$this->errorJump('更新失败，稍后再试！', (U('society/Index/index',array('societyId'=>$this->societyId))));
		}
	}
	
	/**
	 * -------------------------------------------
	 * 投票
	 * -------------------------------------------
	 * @param
	 * @return
	 * @author ssq 2014-1-7
	 * -------------------------------------------
	 */
	public function doVote(){
		$now = date('Y-m-d',time());
		$now = strtotime($now);
		$map['toUid'] = $_REQUEST['toUid'];
		$map['societyId'] = $this->societyId;
		$map['fromUid'] = $this->m_uid;
		$map['cTime'] = array('gt',$now);
		$return = D('SocietyVote')->getVoteCountByParam($map);
		if ($return>0) {
			returnMsg('今天已投票！',0);
		}else{
			$map['cTime'] = time();
			$result = D('SocietyVote')->vote($map);
			if ($result>=1) {
				$this->doReManager($this->societyId);
				returnMsg($result,1);
			}else{
				returnMsg($result,0);
			}
		}
	}
	
	/**
	 * ------------------------------------------
	 * 校方圈子竞选
	 * ------------------------------------------
	 * @author  ssq  2014-1-7
	 * ------------------------------------------
	 */
	public function doReManager(){
		$result = D('SocietyVote')->getVoteCountBySocietyId($this->societyId);
		$max = '';
		 for ($i = 0; $i < count($result); $i++) {
		 	if ($result[$i]['count(toUid)']>$max) {
		 		$max = $result[$i]['count(toUid)'];
		 	}
		 }
		 $list = array();
		 for ($i = 0; $i < count($result); $i++) {
		 	if ($result[$i]['count(toUid)']==$max) {
		 		$list[] = $result[$i];
		 	}
		 }
		 D('SocietyMember')->delMember(array('societyId'=>$this->societyId,'uid'=>array('NEQ','0')));
		 $i = 1;
		 foreach ($list as $value) {
		 	$param['societyId'] = $this->societyId;
		 	$param['uid'] = $value['toUid'];
		 	$member = D('SocietyMember')->getSocietyInfoByParam($param);
		 	if ($member) {
		 		D('SocietyMember')->memberManager($param,1);
		 	}else{
				$param['status'] = 1;
			 	D('SocietyMember')->addMemberBySocietyid_Uid($param);
		 	}
		}
	}
	
	/**
	 * -------------------------------------------
	 * 竞选 搜索用户
	 * -------------------------------------------
	 * @param
	 * @return
	 * @author ssq 2014-1-7
	 * -------------------------------------------
	 */
	public function searchForVote(){
		$param['schoolid'] = $GLOBALS["_SESSION"]['ucInfo']['schoolid'] ? $GLOBALS["_SESSION"]['ucInfo']['schoolid'] : 1;
        $param['yxid'] = null;
        $param['nj']   = null;
        $param['zyid'] = null;
       	$param['bjid'] = null;
       	$param['depid'] = null;
		if($this->societyInfo['type']==1){
			$param['bjid'] = $this->societyInfo['typeid'];
		}
		if($this->societyInfo['type']==2){
			$param['zyid'] = $this->societyInfo['typeid'];
		}
		if($this->societyInfo['type']==3){
			$param['nj']   = $this->societyInfo['typeid'];
		}
		if($this->societyInfo['type']==4){
			$param['yxid'] = $this->societyInfo['typeid'];
		}
		if($this->societyInfo['type']==5){
			$param['depid'] = $this->societyInfo['typeid'];
		}
       	$userName = $_REQUEST['userName'];
       	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		$memberList = D('UserOf')->getSocietyByparaNoPage($userName);
       	foreach ($memberList as $v){
       		$reg_member[]= $v['uid'];
       	}
       	$map['uid']  = array('in',$reg_member);
       	$regMember = M('ucenter_user_link')->where($map)->field('uc_uid')->select();
       	$uidString = '-1';
       	foreach ($regMember as $value) {
       		$uidString = $uidString.','.$value['uc_uid'];
       	}
       	$param['uid'] = $uidString;
		$memberList = getUserByUserNameANDParam($param);
		unset($reg_member);
		foreach ($memberList as $v){
			$reg_member[]= $v['uid'];
		}
		$map['uc_uid']  = array('in',$reg_member);
		unset($regMember);
		$count = M('ucenter_user_link')->where($map)->count();
		$limit = ($page-1)*12;
		$limit = $limit.",12";
		$regMember = M('ucenter_user_link')->where($map)->field('uid')->limit($limit)->select();
       	$managerList = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'status'=>array('NEQ','0'),'isDel'=>0));
       	foreach ($managerList as $value) {
       		$List[] = $value['uid'];
       	}
       	$this->assign('userName',$userName);
       	$this->assign('List',$List);
       	$pageshow = $this->findPage($count, 12, $page , false);
       	$this->assign('regMember',$regMember);
       	$this->assign('pageshow',$pageshow);
       	$this->assign('nav',5);
       	$this->display('vote');
	}
	

	//errorJump
	function errorJump($message,$url){
		$this->assign('jumpUrl',$url);
		$this->error($message);
	}
	
	//successJump
	function successJump($message,$url){
		$this->assign('jumpUrl',$url);
		$this->success($message);
	}
	
	//backJump
	function backJump($message){
		$this->error($message);
	}
	
	public function sendEmail() {
		$name = $_REQUEST['name'];
		$this->assign('name',$name);
		$this->display();
	}
	
	public function doSendEmail() {
		$email = t($_POST["email"]);
		$name = $_POST["userName"];
		$fromName = getUserName(getMid());
		if ( !$this->isValidEmail($email) )
			$this->error(L('email_format_error'));
	
		$url  = SITE_URL;
// 		$url  = U('home/index/index');
		$body = <<<EOD
		<strong> {$_POST["userName"]}，您好: </strong><br/>
	
		$fromName 邀请您注册学校社区，请点击下面链接完成注册操作: <br/>
	
		<a href="$url">$url</a><br/>
	
		如果通过点击以上链接无法访问，请将该网址复制并粘贴至新的浏览器窗口中。<br/>
EOD;
		global $ts;
		$email_sent = service('Mail')->send_email($email, '邀请注册学校社区', $body);

		if ($email_sent) {
			$this->successJump('发送邀请邮件成功！', U('society/Index/member',array('societyId'=>$this->societyInfo['id'])));
		}else {
		}
	}
	
	//检查Email地址是否合法
	public function isValidEmail($email) {
		if(UC_SYNC){
			$res = uc_user_checkemail($email);
			if($res == -4){
				return false;
			}else{
				return true;
			}
		}else{
			return preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
		}
	}
	
	//基础信息加载
	function getBaseInfo(){
		
		//用户身份
		$this->assign('identitytype',$this->memberUser[0]['identitytype']);
		
		//圈子信息
		$this->societyInfo['societyName'] = stripslashes($this->societyInfo['societyName']);
		$this->societyInfo['tags'] = stripslashes($this->societyInfo['tags']);
		$this->societyInfo['sign'] = stripslashes($this->societyInfo['sign']);
		$this->societyInfo['notice'] = stripslashes($this->societyInfo['notice']);
		$this->assign('societyInfo',$this->societyInfo);
		
		//我的自定义圈子列表
		$societyListOfMy = D('SocietyMember')->getListByUID($this->m_uid);
		foreach ($societyListOfMy as $key => $value) {
			$societyListOfMy[$key]['societyName'] = stripslashes($value['societyName']);
			$societyListOfMy[$key]['newsCount'] = stripslashes($value['societyName']);
		}
		$this->societyListOfMy = $societyListOfMy;
		$this->assign('societyListOfMy',$societyListOfMy);
		
		//我在圈子中的信息
		$myInfo = D('SocietyMember')->getSocietyInfoByParam(array('societyId'=>$this->societyId,'uid'=>$this->m_uid,'isDel'=>0));
		$this->assign('myInfo',$myInfo[0]);
		
		//我的消息
		$this->getNewsCountByUid(getMid(),$societyListOfMy); //获取数量
		
		//圈子消息
		$this->getNewsBySocietyId($this->societyId);
		
		//管理员 访客
		$this->getSocietyVisitor($this->societyId);
	}
	
	//删除新鲜事
	function deleteMessage(){
		$result = D("SocietyMember")->checkMemberManager($this->societyId,$this->m_uid);
		$id = $_POST['id'];
		$message = D("SocietyMessage")->getMessById($this->societyId,$id);
		if(!$message){
			returnMsg('该新鲜事已经被删除！',-1);
		}
		if ($result==1 || $message['uid']==$this->m_uid) {
			$re = D("SocietyMessage")->deleteMess($id);
			if($re==1){
				D("SocietyComment")->deleteComment($id);
				returnMsg('删除成功！',0);
			}else{
				returnMsg('删除失败！',-1);
			}
		}else{
			returnMsg('没有操作权限！',-1);
		}
	}
	
	//删除印象
	function deleteWall(){
		$result = D("SocietyMember")->checkMemberManager($this->societyId,$this->m_uid);
		$id = $_POST['id'];
		if ($result==1) {
			$re = D("SocietyWall")->deleteWall($id);
			returnMsg('删除成功！',0);
		}else{
			returnMsg('没有操作权限！',-1);
		}
	}
	
	//同步Blog
	function tongBuBlog(){
		$result = D('Blog','blog')->getBlogLists();
		$map=array();
		foreach ($result as $key => $value) {
			$map[$value['uid']][] = $value['id'];
		}
		foreach ($map as $key => $value) {
			$ss = null;
			$param = null;
			$ss = get_baseinfo_by_uid ( getUcUid($key) );
			if ($key==0) {
				$ss = null;
			}
			if(($ss==''||$ss==null)) {
				foreach ($value as $k => $v) {
					$param['uid']    = $key;
					$param['blogid'] = $v;
					D('BlogLink','blog')->addBlodLink($param);
				}
			}else{
				foreach ($value as $k => $v) {
					$param['uid']    = $key;
					$param['blogid'] = $v;
					switch ($ss['identitytype']) {
						case 1: //管理员
						break;
						case 2: //老师
							$param['depid'] = $ss['depid'];
						break;
						case 3: //学生
							$param['bjid'] = $ss['bjid'];
							$param['zyid'] = $ss['zyid'];
							$param['nj']   = $ss['nj'];
							$param['yxid'] = $ss['yxid'];
						break;
						case 4: //家长
						break;
						case 5: //校友
						break;
					}
					D('BlogLink','blog')->addBlodLink($param);
				}
			}
		}
	}
	
	//同步Photo
	function tongBuPhoto(){
		$result = D('Photo','photo')->getPhotoLists();
		$map=array();
		foreach ($result as $key => $value) {
			$map[$value['userId']][] = $value['id'];
		}
		foreach ($map as $key => $value) {
			$ss = null;
			$param = null;
			$ss = get_baseinfo_by_uid ( getUcUid($key) );
			if ($key==0) {
				$ss = null;
			}
			if(($ss==''||$ss==null)) {
				foreach ($value as $k => $v) {
					$param['uid']    = $key;
					$param['photoid'] = $v;
					D('PhotoLink','photo')->addPhotoLink($param);
				}
			}else{
				foreach ($value as $k => $v) {
					$param['uid']    = $key;
					$param['photoid'] = $v;
					switch ($ss['identitytype']) {
						case 1: //管理员
							break;
						case 2: //老师
							$param['depid'] = $ss['depid'];
							break;
						case 3: //学生
							$param['bjid'] = $ss['bjid'];
							$param['zyid'] = $ss['zyid'];
							$param['nj']   = $ss['nj'];
							$param['yxid'] = $ss['yxid'];
							break;
						case 4: //家长
							break;
						case 5: //校友
							break;
					}
					D('PhotoLink','photo')->addPhotoLink($param);
				}
			}
		}
	}
}
?>