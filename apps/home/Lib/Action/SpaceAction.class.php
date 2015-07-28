<?php
/**
 * 个人空间
 */
class SpaceAction extends Action
{
	function _initialize(){
		if (!is_numeric($_GET['uid']) && is_string($_GET['uid'])) {
			$domainuser = D('User')->getUserByIdentifier(h($_GET['uid']), 'domain');
	   		if ($domainuser) {
	   			$this->uid = $domainuser['uid'];
	   			$this->assign('uid',$this->uid);
	   		}else {
	   			$this->assign('jumpUrl',$_SERVER['HTTP_REFERER']);
   	   			$this->error(L('user_not_exist'));
	   		}
		} else {
		    if(empty($_GET['uid'])) $_GET['uid'] = $this->mid;
		    $this->uid=intval($_GET['uid']);
			$this->assign('uid', intval($_GET['uid']));
		}
        
		if ('detail' != ACTION_NAME) {
			$user_info = D('User')->getUserByIdentifier($this->uid);
			$user_profile = D('UserProfile')->getProfiles($this->uid);
			//将个人设置信息填充至模板中
			foreach ($user_profile as $profile){
			    if($profile['module']=='intro'){
			        $this->assign($profile);
			    }
			}
	   	   	if ($user_info) {
	   	   			
		   	   		$userid = $this->uid;
		   	   		$user_link = M('ucenter_user_link')->field('uc_uid')->where('uid='.$userid)->select();
		   	   		$uc_user_info = uc_get_user($user_link[0]['uc_uid'], 1);
		   	   		$identityid = $uc_user_info['identityID'];
		   	   		$identitytype = $uc_user_info['identityType'];
		   	   		$ucInfo = uc_get_baseinfo($identityid,$identitytype);
		   	   		$userinfo = array(
		   	   						'微广播地址' => U('home/Space/index', array('uid' => $user_info['domain'] ? $user_info['domain'] : $this->uid)),
		   	   				 		'性别'    => getSex($user_info['sex']),
		   	   						'所在地'  => $user_info['location'],
		   	   						'真实姓名' => $ucInfo['xm']
		   	   					);
		   	   		$userinfo['identitytype'] = $identitytype;
		   	   		if($identitytype == 2){
		   	   			$userinfo['部门'] = $ucInfo['deptname'];
		   	   			if(!empty($ucInfo['zw'])){
		   	   				$userinfo['职务'] = $ucInfo['zw'];
		   	   			}
		   	   		}else{
		   	   			$userinfo['院系'] = $ucInfo['yxmc'];
		   	   			$userinfo['班级'] = $ucInfo['bjmc'];
		   	   		}
		   	   		
		   	   		
	    			// 基本信息-钩子
		   	   		Addons::hook('home_space_profile_base', array('uid' => $this->uid, 'user_info' => & $userinfo));
					$this->assign('userinfo', $userinfo);
					$this->assign('user_credit', service('Credit')->getUserCredit($this->uid));
					$this->assign('userprofile',$user_profile);
		   	   	} else {
		   	   		$this->assign('jumpUrl',$_SERVER['HTTP_REFERER']);
	   	   			$this->error(L('user_not_exist'));
		   		}
			}

			$uid = $_GET['uid'];
			$data['nav_menu']['主页'] = U('home/space/index', array('uid'=>$uid));
			$_SESSION['ucInfo'] = arrayKeyToLower($_SESSION['ucInfo']);
			if($_SESSION['ucInfo'] && $_SESSION['ucInfo']['identitytype'] == '2') {
				//$data['nav_menu']['教研'] = U('home/space/teach', array('uid'=>$uid));
				//$data['nav_menu']['备课'] = U('home/space/prepare', array('uid'=>$uid));
				$data['nav_menu']['资源'] = U('home/space/resource', array('uid'=>$uid));
			}
			$data['nav_menu']['相册'] = U('home/space/photo', array('uid'=>$uid));
			//$data['nav_menu']['留言板'] = U('home/space/guestbook', array('uid'=>$uid));
			$data['nav_menu']['微广播'] = U('home/space/weibo', array('uid'=>$uid));
			$data['nav_menu']['个人档'] = U('home/Space/profile', array('uid'=>$uid));
			$data['nav_menu']['视频'] = U('video/Index/usercate', array('uid'=>$uid));
			$data['nav_menu']['时光轴'] = U('home/space/timeline', array('uid'=>$uid));
			$this->assign($data);
			
	       	$this->__getSpaceCount( $this->uid );
		}


	private function __getSpaceCount($uid) {
		$followInfo = getUserFollow($uid);
		$data['followstate'] = D('Follow','weibo')->getState($this->mid, $uid, 0);
		$data['isBlackList'] = isBlackList($this->mid,$uid);
		$data['privacy']     = D('UserPrivacy','home')->getPrivacy($this->mid,$uid);
		$data['spaceCount']['miniblog']   = model('UserCount')->getUserWeiboCount($uid);
		$data['spaceCount']['following']  = $followInfo['following'];
		$data['spaceCount']['follower']   = $followInfo['follower'];
		$data['spaceCount']['message']   = 0;
		$data['hotTopic'] = D('Topic','weibo')->getHot();
		$data['usertags'] = D('UserTag')->getUserTagList( $this->uid );
		$this->assign( $data );
	}

    // 用户空间首页
    public function index()
    {
    	$this->_canViewSpace();

        $menu['weibo'] = L('weibo');
        if($_SESSION['ucInfo'] && $_SESSION['ucInfo']['identitytype'] == '2') {
	        //$menu['teach'] = '教研';
	        //$menu['prepare'] = '备课';
	        $menu['resource'] = '资源';
        }
        Addons::hook('home_space_tab', array('uid' => $this->uid, 'menu' => & $menu));
        $this->assign('space_menu', $menu);

        $data['user'] = D('User')->getUserByIdentifier($this->uid);
		//判断用户是否存在
		if(!$data['user']['uid']){
			$this->assign('jumpUrl', $_SERVER['HTTP_REFERER']);
			$this->error('用户不存在或已被删除！');
		}

        $data['type'] = $_GET['type'] ? h($_GET['type']) : 'weibo';
        if ('weibo' === $data['type']) {
	        $weiboType = $data['weibo_type'] = h($_GET['weibo_type']);
	        $data['list'] = D('Operate','weibo')->getSpaceList($this->uid, $weiboType);
			//微广播menu组装
	        $data['weibo_menu'] = array(
	                        ''  => L('all'),
	                        'original' => L('original')
	                      );
	        Addons::hook('home_index_weibo_tab', array(&$data['weibo_menu']));
	    	if(!empty($weiboType)) {
	            $this->assign('typeClass',"on");
	            $this->assign('view','block');
	        }else{
	            $this->assign('typeClass','off');
	            $this->assign('view','none');
	        }
        } else if ('teach' === $data['type']) {
        	$this->_teach();
        } else if ('prepare' === $data['type']) {
        	$this->_prepare();
        } else if ('resource' === $data['type']) {
        	$this->_resource();
        }
        
        $this->assign($data);
       // $this->setTitle($data['user']['uname'] . '的空间');
       $this->setTitle('个人主页');
    	$this->display();
    }

    public function _teach()
    {
		$uc_uid = $_GET['uid'];
		$mid = $uc_uid;
		
		$where = service('ForeAdmin')->getAuditStatus($where, 0, 'teach');	
		$List = M('')
		->table(C ( 'DB_PREFIX' ) . "teach as t")
		->field('t.*')
		->where("$where and ('$mid' = uid or '$uc_uid' in (select uc_uid from ".C ( 'DB_PREFIX' )."teach_member as m where t.meetingId = m.meetingid ) )")
		->findPage($this->config['limitpage']);
		
		//获取内容的审核状态
		$List = service('ForeAdmin')->showAuditStatus($List);
		$this->assign ($List );
    }

    public function teach()
    {
		$this->_teach();
		
		$this->display();
    }

    public function _resource()
    {
    	$map['uid']=$_GET['uid'];
    	$order='id DESC';
    	$mylist=D('Resource')->where($map)->order($order)->findPage($this->config['limitpage']);
    	$this->assign($mylist);
    }

    public function resource()
    {
    	$this->_resource();
    	
    	$this->display();
    }

    public function guestbook()
    {
		$map['type'] = 'space';
		$map['to_uid']  = $_GET['uid'];
		$list=M('comment')->where($map)->order('cTime desc')->findPage(20);
		foreach($list['data'] as $key => $value) {
			if($list['data'][$key]!="") {
				$list['data'][$key]['reply'] = M('comment')->where('toId=' . $value['id'])->order('cTime desc')->findAll();
			}
		}
		$this->assign($list);

    	$this->display();
    }

    public function doPostContent()
    {
    	if (!lockSubmit()) {
			$this->error("发信频率太快啦, 请10秒后重试");
		}
		if (empty($_POST['touid'])) {
			$this->error("请选择接收人");
		}
		if ($_POST['touid'] == $this->mid) {
			$this->error("不能给自己留言！");
		}
		if (empty($_POST['content'])) {
			$this->error("请填写内容");
		}
		$map['type'] = 'space';
		$map['uid'] = $this->mid;
		$map['toId'] = $_POST['toid'];
		$map['to_uid'] = $_POST['touid'];
		$map['quietly'] = '0';
		$map['comment'] = $_POST['content'];
		$map['cTime'] = time();
		$res = M('comment')->add($map);
		if ($res) {
			$this->success("发送成功");
		}else {
			$this->error("发送失败");
		}
    }
    /**
     +----------------------------------------------------------
     + 查询备课数据无分页
     +----------------------------------------------------------
     + @param	
     + @return	return_type
     + @author	小波 (Administrator)
     +----------------------------------------------------------
     + 创建时间：	2013-9-16 下午4:47:23
     +----------------------------------------------------------
     */
    public function _prepare()
    {		
		$prepare = M('prepare');
		$chapter = M('prepare_chapter');
		$map['uid']= $_GET['uid'] ? $_GET['uid'] : $this->mid;
		$map['status'] = 1;
		//统一权限
		$map = service('ForeAdmin')->getAuditStatus($map, 0, 'prepare');
		//获取最新Id
		$prepareKnowid = $prepare->where($map)->order('id DESC')->limit(1)->getField('id');
		$prepareChaptid = $chapter->where($map)->order('id DESC')->limit(1)->getField('id');
		$prepareKnowid = $prepareKnowid ? $prepareKnowid : 0;
		$prepareChaptid = $prepareChaptid ? $prepareChaptid : 0;
		//定义缓存的键
        $skey = "S_prepare_date_".$map['uid'];
        $tmp_result  =  S($skey);
        if($tmp_result){    //获取缓存中最新的两个键ID
            $kid = 0;
            foreach ($tmp_result as $k=>$pare){
                $kid = $pare[$k]['id'];
                break;
            }
        }
        //如果缓存为空 或者 最新的两个键id和数据库中查询的最新ID不等时做缓存处理
        if(empty($tmp_result) || ($kid != $prepareKnowid && $prepareChaptid != $kid)) {
            //获取全部数据
            $knowledData = $prepare->field('*,1 as stype')->where($map)->order('id DESC')->findAll();
            $chapterData = $chapter->field('*,2 as stype')->where($map)->order('id DESC')->findAll();
            //合并数组，相同的键时不做覆盖操作
            $data = array_merge_recursive($knowledData, $chapterData);
            foreach ($data as $prepare){
                $tmp_result[$prepare['mtime']] = $prepare;
            }
            krsort($tmp_result);
            
            //存储缓存
            S($skey, $tmp_result, 3600*24);
        }
        
		$result = array_chunk($tmp_result,10);
		$this->assign('data',$result[0]);
    }
    /**
     +----------------------------------------------------------
     + 查询全部备课，有分页
     +----------------------------------------------------------
     + @param	
     + @return	return_type
     + @author	小波 (Administrator)
     +----------------------------------------------------------
     + 创建时间：	2013-9-16 下午4:47:00
     +----------------------------------------------------------
     */
    public function prepare()
    {
        $prepare = M('prepare');
        $chapter = M('prepare_chapter');
        $map['uid']= $_GET['uid'] ? $_GET['uid'] : $this->mid;
        $map['status'] = 1;
        //统一权限
        $map = service('ForeAdmin')->getAuditStatus($map, 0, 'prepare');
        //获取最新Id
        $prepareKnowid = $prepare->field('id')->where($map)->order('id DESC')->limit(1)->find();
        $prepareChaptid = $chapter->field('id')->where($map)->order('id DESC')->limit(1)->find();
    
        $skey = "S_prepare_date_".$map['uid'];
        $tmp_result  =  S($skey);
        if($tmp_result){
            $kid = $cid = 0;
            foreach ($tmp_result as $pare){
                if($pare['stype'] == '1' && $kid == 0 ) $kid = $pare['id'];
                if($pare['stype'] == '2' && $cid == 0 ) $cid = $pare['id'];
            }
        }
        if(empty($tmp_result) || $kid != $prepareKnowid || $prepareChaptid != $cid) {
            //获取全部数据
            $knowledData = $prepare->field('*,1 as stype')->where($map)->order('id DESC')->findAll();
            $chapterData = $chapter->field('*,2 as stype')->where($map)->order('id DESC')->findAll();
    
            $data = array_merge_recursive($knowledData, $chapterData);
            foreach ($data as $prepare){
                $tmp_result[$prepare['mtime']] = $prepare;
            }
            krsort($tmp_result);
            
            //存储缓存
            S($skey, $tmp_result, 3600*24);
        }

        $count = count($tmp_result);
        $pagesize = 10;
        $result = array_chunk($tmp_result,$pagesize);
        
        unset($data);
        // 如果查询总数大于0
		if ($count > 0) {
			// 解析分页参数
			$pagesize 				=	is_numeric($pagesize) ? intval($pagesize) : intval(C('LIST_NUMBERS'));
			$p		  				=	new Page($count,$pagesize);
			// 查询数据
			$limit					=	$p->firstRow.','.$p->listRows;

			// 输出控制
			$data['count']		=	$count;
			$data['totalPages']	=	$p->totalPages;
			$data['totalRows']	=	$p->totalRows;
			$data['nowPage']		=	$p->nowPage;
			$data['html']			=	$p->show();
			$data['data']			=	$result[$p->nowPage-1];
			unset($p);
			unset($count);
		}else {
			$data['count']		=	0;
			$data['totalPages']	=	0;
			$data['totalRows']	=	0;
			$data['nowPage']		=	1;
			$data['html']			=	'';
			$data['data']			=	'';
		}

        $this->assign($data);
        $this->display();
    }
    
    //个人资料
    public function profile()
    {
    	$this->_canViewSpace();
    	$menu = array ('video'=>'个人档');
    	$this->assign('space_menu', $menu);
    	$pUserProfile = D('UserProfile');
    	$pUserProfile->uid = $this->uid;
    	$data['userInfo']  = $pUserProfile->getUserInfo(true);

        // 个人情况-钩子
    	Addons::hook('home_space_profile_intro', array('uid' => $this->uid, 'intro' => & $data['userInfo']['intro']['list']));


    	// 联系方式-钩子
    	Addons::hook('home_space_profile_contact', array('uid' => $this->uid, 'contact' => & $data['userInfo']['contact']['list']));

    	$this->assign( $data );
    	 
    	$this->setTitle(getUserName($this->uid) . '的详细资料');
    	$this->display();
    }

    // 查看微广播详细
    function detail(){
    	$intId = intval( $_GET['id'] );
	    $data['mini']      =  D('Operate','weibo')->getOneLocation( $intId );
	    if(!$data['mini']) $this->error(L('post_arg_error'));
		$data['comment']   =  D('Comment','weibo')->getComment( $intId );
		$data['privacy'] = D('UserPrivacy','home')->getPrivacy($this->mid,$data['mini']['uid']);
    	$this->assign( $data );
	    $this->uid = $data['mini']['uid'];

		$user_info = D('User')->getUserByIdentifier($this->uid);
	   	if ($user_info) {
			$this->assign('userinfo',$user_info);
	   	}
	    $this->__getSpaceCount( $this->uid );

		//SEO优化: 标题栏增加微广播内容摘要
	    $this->setTitle(getUserName($this->uid) . ':'.getShort($data['mini']['content'],30).'...');
	    $this->assign('weibo_id',$intId);
    	$this->display();
    }

	/*
	 * 微广播文档下载页
	 */
	public function file()
	{
		$aid	=	intval($_REQUEST['id']);
		$uid	=	intval($_REQUEST['uid']);

		$user_info = D('User')->getUserByIdentifier($this->uid);
	   	if ($user_info) {
			$this->assign('userinfo',$user_info);
	   	}
	    $this->__getSpaceCount( $this->uid );

		$attach	=	model('Attach')->field('id,userId,name,extension,size,uploadTime')->where("id={$aid} AND userId={$uid}")->find();
		$this->assign('data', $attach);
		$this->display();
	}

    //关注
    function follow(){
    	$this->_canViewSpace();

    	$data['type'] = ($_GET['type']=='follower')?'follower':'following';
    	if($data['type'] == 'following'){
    		//关注分组列表
	    	$data['gid']  = is_numeric($_GET['gid'])?$_GET['gid']:'all';
	    	$group_list = D('FollowGroup','weibo')->getGroupList($this->uid);
	    	//调整分组列表
	    	if(!empty($group_list)){
		    	$group_count = count($group_list);
		    	for($i=0;$i<$group_count;$i++){
		    		if($group_list[$i]['follow_group_id'] != $data['gid']){
		    			$group_list[$i]['title'] = (strlen($group_list[$i]['title'])+mb_strlen($group_list[$i]['title'],'UTF8'))/2>8?getShort($group_list[$i]['title'],3).'...':$group_list[$i]['title'];
		    		}
		    		if($i<2){
		    			$data['group_list_1'][] = $group_list[$i];
		    		}else{
		    			if($group_list[$i]['follow_group_id'] == $data['gid']){
		    				$data['group_list_1'][2]  = $group_list[$i];
		    				continue;
		    			}
		    			$data['group_list_2'][] = $group_list[$i];
		    		}
		    	}
		    	if(empty($data['group_list_1'][2]) && !empty($data['group_list_2'][0])){
		    		$data['group_list_1'][2] = $data['group_list_2'][0];
		    		unset($data['group_list_2'][0]);
		    	}
	    	}
    	}
    	// 关注的人列表
    	$data['list'] = D('Follow','weibo')->getList($this->uid,$data['type'],0,$data['gid']);
    	$this->assign($data);
    	$this->setTitle(getUserName($this->uid) . '的' . ($data['type'] == 'follower' ? '关注我' : '关注'));
    	$this->display();

    }

    /**
     +----------------------------------------------------------
     * 小名片
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-3-16 下午3:56:54
     +----------------------------------------------------------
     */
    public function showSpaceCard(){
		$uid = intval( $_GET['uid'] );
    	if($uid){
			$data = getUserInfo($uid);

			$usertags = D('UserTag')->getUserTagList( $this->uid );
			foreach($usertags as $v){
				$tags[]	=	'<a href="'.U('home/User/searchtag',array('k'=>$v['tag_name'])).'">'.$v['tag_name'].'</a>';
			}
			$data['location']		=	getLocation($data['province'],$data['city']);
			if(!$data['location'])	$data['location'] ='<br />';
			$data['tags']			=	(!$tags)?'无':implode(' ',$tags);
			$data['following_url']	=	U('home/Space/follow',array('type'=>'following','uid'=>$uid));
			$data['follower_url']	=	U('home/Space/follow',array('type'=>'follower','uid'=>$uid));
			$data['space_url']		=	U('home/Space/index',array('uid'=>$uid));
			$data['space_link']		=	getUserSpace($uid,'nocard','_blank');
			//真实姓名
			$data['realname']		=	empty($data['realname'])?'':' （'.$data['realname'].'）';
			$data['follow_state']	=	($this->mid==$uid)?'self':D('Follow','weibo')->getState($this->mid, $uid, 0);
			$this->ajaxReturn($data,L('get_success'),1);
    	}else{
    		$this->ajaxReturn('',L('get_success'),0);
    	}
    }

    private function _canViewSpace()
    {
    	$user_set = D('UserPrivacy')->getUserSet($this->mid);
    	$can_view = true;
    	if (1 == $user_set['space']) {
    	    // 我关注的人
    	    if ($this->mid && $this->mid != $this->uid && 'unfollow' === getFollowState($this->mid, $this->uid, 0)) {
	    		$can_view = false;
    	    }
    	} else {
    		// 所有人（不包括黑名单）
	    	if ($this->mid && $this->mid != $this->uid && isBlackList($this->uid, $this->mid)) {
	    		$can_view = false;
	    	}
    	}
    	!$can_view && $this->error('对方不允许访问');
    }

    /**
     +----------------------------------------------------------
     * 学科空间展示 （仅用于大桌面系统）
     +----------------------------------------------------------
     * @author 小波 2013-6-29
     +----------------------------------------------------------
     */
    public function courseSpace(){
    	$ucinfo = arrayKeyToLower($_SESSION['ucInfo']);
    	//如果为老师
    	if($ucinfo['identitytype'] == '2'){
    		$organize = $ucinfo['organize'];
    		$jyparam = array();
    		$bkgroup = array();
    		foreach ($organize as $data){
    			//获取学段及学科信息合并为一个数组
    			if (empty($jyparam[$data['xd']])) {
    				$jyparam[$data['xd']][] = $data['xkid'];
    			}else{
    				if (!in_array($data['xkid'], $jyparam[$data['xd']])) {
    					$jyparam[$data['xd']][] = $data['xkid'];
    				}
    			}
    		}
    		//获取教研组
    		$jygroup = array();
    		$bkgroup = array();
//     		foreach ($jyparam as $key=>$val){
//     			$jygroup = array_merge($jygroup, get_user_xz_group($ucinfo['schoolid'], $key, $val));
//     		}
			$jyz = get_user_jy_group(getUcUid($this->mid));
			$bkz = get_user_bk_group($_SESSION['ucInfo']['identityid']);
			//格式化教研组
			foreach ($jyz as $value){
				unset($value['njmc'],$value['gradeid']);
				if (!empty($value['xkid']) && empty($jygroup[$value['xkid']])) {
					$jygroup[$value['xkid']] = $value;
				}
			}
    	    //格式化备课组
			foreach ($bkz as $value){
				//unset($value['njmc'],$value['gradeid']);
				if (!empty($value['xkid'])) {
					$bkgroup[$value['xkid'].$value['nj']] = $value;
				}
			}

    		//获取协作组
    		if($ucinfo['identitytype'] == '2'){
    			$xzgroup = M()
    			->table(C('DB_PREFIX').'teach_group_member M')
    			->field('G.*')
    			->join('right join '.C('DB_PREFIX').'teach_group G on M.gid = G.id')
    			->where("M.uid = '{$this->mid}' OR G.uid='{$this->mid}'")
    			->group("G.id")
    			->findAll();

    			if ($xzgroup) {
    				$data['xzgroup'] = $xzgroup;;
    			}
    		}

    		//获取备课组
    		$data['jygroup'] = $jygroup;
    		$data['bkgroup'] = $bkgroup;
    	}

    	$this->assign($data);
    	$this->display();
    }
    
    
    public function video(){
    	$this->_canViewSpace();
     	$menu = array ('video'=>'视频');
     	$this->assign('space_menu', $menu);
   	
		//获取配置参数
		$config = $this->getConfig_video();
		//获取视频数据
		$order	=	'categoryId DESC,cTime DESC';
		$map['userId']	=	$this->uid;
	 
		$map = service('ForeAdmin')->getAuditStatus($map, 0, 'video');
		$videos	=	D('Video')->order($order)->where($map)->findPage($config['video_raws']);
		$this->assign('video_preview',$config['video_preview']);
		$this->assign('type','mAll');
		$this->assign('videos',$videos);
		$this->display();
    	
    }
    public function photo(){
    	$uid = $_GET['uid'];
    	$this->_canViewSpace();
    	$menu = array ('video'=>'相册');
    	$this->assign('space_menu', $menu);
    	//获取相册数据
		$map['userId']	=	$uid;
		$map['isDel']	=	0;

        $map['privacy'] = array('in','1,4');
        $relationship	=	getFollowState($this->mid,$uid);

        if($this->mid==$uid){
            unset($map['privacy']);
        }elseif($relationship !='unfollow'){
            $map['privacy'] = array('in','1,2,4');
        }

		$data	=	M('photo_album')->order("mTime DESC")->where($map)->findPage($this->getConfig_photo('album_raws'));
		//获取微广播相册
		$weibo  =  D('WeiboAttach','weibo')->getWeiboAlbum($this->uid);

		$this->assign('weibo',$weibo);
		$this->assign('data',$data);
	 
		$this->display('photo');
    	 
    }
    
    // 微博
    public function weibo()
    {
    	$this->_canViewSpace();
    
    	$menu['weibo'] = L('weibo');
    	$menu = array ('weibo'=>'微广播');
    	$this->assign('space_menu', $menu);
    
    	$data['user'] = D('User')->getUserByIdentifier($this->uid);
    	//判断用户是否存在
    	if(!$data['user']['uid']){
    		$this->assign('jumpUrl', $_SERVER['HTTP_REFERER']);
    		$this->error('用户不存在或已被删除！');
    	}
    
    	$data['type'] = $_GET['type'] ? h($_GET['type']) : 'weibo';
    	if ('weibo' === $data['type']) {
    		$weiboType = $data['weibo_type'] = h($_GET['weibo_type']);
    		$data['list'] = D('Operate','weibo')->getSpaceList($this->uid, $weiboType);
    		//微广播menu组装
    		$data['weibo_menu'] = array(
    				''  => L('all'),
    				'original' => L('original'),
    		);
    		Addons::hook('home_index_weibo_tab', array(&$data['weibo_menu']));
    		if(!empty($weiboType)) {
    			$this->assign('typeClass',"on");
    			$this->assign('view','block');
    		}else{
    			$this->assign('typeClass','off');
    			$this->assign('view','none');
    		}
    	}
    
    
    	$this->assign($data);
    	$this->setTitle($data['user']['uname'] . '的空间');
    	$this->display();
    }
    
    function getConfig_video($key = NULL) {
    	$config = model('Xdata')->lget('video');
    	$config['category_raws'] || $config['category_raws'] = 6;
    	$config['video_raws'] || $config['video_raws'] = 8;
    	$config['video_preview'] == 0 || $config['video_preview'] = 1;
    	($config['video_max_size'] = floatval($config['video_max_size']) * 1024 * 1024) || $config['video_max_size'] = -1;
    	$config['video_file_ext'] || $config['video_file_ext'] = 'mp4,flv,wmv,avi';
    	$config['max_flash_upload_num'] || $config['max_flash_upload_num'] = 10;
    	$config['open_watermark'] == 0 || $config['open_watermark'] = 1;
    	$config['watermark_file'] || $config['watermark_file'] = 'public/images/watermark.png';
    	if ($key == NULL) {
    		return $config;
    	} else {
    		return $config[$key];
    	}
    }
    //获取应用配置参数
    function getConfig_photo($key=NULL){
    	$config = model('Xdata')->lget('photo');
    	$config['album_raws'] || $config['album_raws']=6;
    	$config['photo_raws'] || $config['photo_raws']=8;
    	$config['photo_preview']==0 || $config['photo_preview']=1;
    	($config['photo_max_size']=floatval($config['photo_max_size'])*1024*1024) || $config['photo_max_size']=-1;
    	$config['photo_file_ext'] || $config['photo_file_ext']='jpeg,gif,jpg,png';
    	$config['max_flash_upload_num'] || $config['max_flash_upload_num']=10;
    	//$config['max_storage_size'] || $config['max_storage_size']=0;
    	//$config['max_album_num'] || $config['max_album_num']=0;
    	//$config['max_photo_num'] || $config['max_photo_num']=0;
    	$config['open_watermark']==0 || $config['open_watermark']=1;
    	$config['watermark_file'] || $config['watermark_file']='public/images/watermark.png';
    	if($key==NULL){
    		return $config;
    	}else{
    		return $config[$key];
    	}
    }
    
    /**
     +----------------------------------------------------------
     + 时光轴首页
     +----------------------------------------------------------
     + @param
     + @return	return_type
     + @author	小波 (Administrator)
     +----------------------------------------------------------
     + 创建时间：	2013-9-6 下午3:53:37
     +----------------------------------------------------------
     */
    public function timeline()
    {
    	$photos = $weibos = $blogs = array();
    
    	$_GET['uid']!=''? $map['uid']=$_GET['uid']:$map['uid']=$this->mid;
    
    	//获取微博信息
    	$map['isdel']=0;
    	$result = M('weibo')->where($map)->field('weibo_id,content,ctime')->order('ctime desc')->select();
    	if ($result!=NULL && !empty($result)) $weibos = $result;
    	foreach ($weibos as &$obj){
    		$obj['content'] = str_replace("addons", __ROOT__.'/addons', $obj['content']);
    	}
    	$weibos=$this->timeline_replace($weibos);
    
    	//获取日志信息
    	unset($map['isdel']);
    	$map['status']='1';
    	$result=M('blog')->where($map)->field('id,title,content,cTime')->select();
    	if ($result!=NULL && !empty($result)) $blogs = $result;
    	$blogs=$this->timeline_replace($blogs);
    
    	//获取照片信息
    	$photos = array();
    	$map['userId']=$map['uid'];
    	unset($map['uid']);
    	$result=M('photo')->where($map)->field('id,name,cTime,savepath')->select();
    	if ($result!=NULL && !empty($result)) $photos = $result;
    	$photos=$this->timeline_replace($photos);
    	
    	$datas=array_merge($weibos,$blogs,$photos);
    	foreach ($datas as $key => $row) {
    		$volume[$key] = $row['cTime'];
    	}
    	array_multisort($volume, SORT_DESC, $datas);
    
    	//获取日期
    	$times = array();
    	foreach($datas as $value){
    		$times[]= date('Y,m',$value['cTime']);
    	}
    	$times = array_unique($times);
    
    	//创建更适合使用的数据格式
    	$events = array();
    	foreach($datas as $event){
    		$day = date('Y,m',$event['cTime']);
    		if(is_array($event)){
    			$ev['id'] = $event['id'];
    			$ev['title'] = $event['title'];
    			$ev['type'] = $event['type'];
    			$ev['content'] = $event['content'];
    			$ev['savepath'] = $event['savepath'];
    			$ev['cTime'] = $event['cTime'];
    		}
    		else{
    			throw new Exception("没有动态");
    		}
    		$events[$day][] = $ev;
    	}
    	$this->assign('time',$times);
    	$this->assign('datas',$events);
    	$this->display();
    }
    
    private function timeline_replace($data){
    	$result = $data;
    	foreach($result as &$value){
    		if($value['content']){
    			$value['content'] = htmlspecialchars_decode($value['content']);
    		}else{
    			$value['content'] = '';
    		}
    		if($value['savepath']){
    			$value['savepath'] = __ROOT__.'/data/uploads/'.$value['savepath'];
    			list($width, $height, $type, $attr) = getimagesize('data/uploads/'.$value['savepath']);
    			$w = $width < 400? $width:430;
    			$h = (int)($height*($w/$width));
    			$value['width'] = $w;
    			$value['height'] = $h;
    			$value['type'] = '照片';
    		}else{
    			$value['savepath'] = '';
    		}
    		if($value['name']){
    			$value['title'] = $value['name'];
    		}elseif($value['title']){
    			$value['title'] = $value['title'];
    			$value['type'] = '文章';
    		}else{
    			$value['title'] = '';
    		}
    		if($value['ctime']){
    			$value['cTime'] = $value['ctime'];
    		}else{
    			$value['cTime'] = $value['cTime'];
    		}
    		if($value['weibo_id']){
    			$value['id'] = $value['weibo_id'];
    			$value['type'] = '微广播';
    		}else{
    			$value['id'] = $value['id'];
    		}
    	}
    	return $result;
    }

    public function getSnsUid(){
        $uc_uid = $_GET['uid'];
        $uid = M('ucenter_user_link')->where('uc_uid='.$uc_uid)->getField('uid');
        if($uid){
            echo $uid;
        }else{
            echo 0;
        }
    }
}