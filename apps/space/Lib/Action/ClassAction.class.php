<?php
/**
 +------------------------------------------------------------------------------
 * ESN 班级空间 Action
 +------------------------------------------------------------------------------
 * @category	ESN Class
 * @package		ESN Lib/Action
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午10:48:18
 +------------------------------------------------------------------------------
 */
class ClassAction extends Action
{   
	private $db_prefix;
    private $classid;//班级编号id
	private $classinfo;//array类型，从api和sns中查询到的班级基础信息
	private $identityid;
	private $action;//方法名
	private $module;//控制器名称
	private $uc_session;
	/**
	 +----------------------------------------------------------
	 * _initialize 初始化加载，验证classid是否有值，并且验证是否是管理员
	 +----------------------------------------------------------
	 * @param	int uid 用户id
	 * @param	int classid 班级id
	 * @param	array classinfo 班级基础信息
	 * @param	int adviser	1有权限0没有权限
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 上午11:01:51
	 +----------------------------------------------------------
	 */
	function _initialize(){
	   $this->action=ACTION_NAME;//获取当前访问方法名
	   $this->module=MODULE_NAME;//获取当前访问控制器名
	   $this->db_prefix = C('DB_PREFIX');
	   $this->uid=$this->mid;//获取当前登录uid
	   $this->uc_session=arrayKeyTolower($_SESSION['ucInfo']);
	   
	   $this->identityid=$this->uc_session['identityid'];
	   if(empty($_REQUEST['classid']) || !intval($_REQUEST['classid'])>0){
			$this->error('非法操作！');
		}
	   $this->classid=intval($_REQUEST['classid']);
	   $this->assign('classid',$this->classid);
	   D('Visit')->_visited($this->uid,$this->classid);//记录访问记录
	   $this->classinfo=D('ClassBasic')->_classInfo($this->classid);//获取班级基础信息
	   $this->assign('classinfo',$this->classinfo);
	   if($this->_getclassAdviser()){
	   		$adviser=1;
	   }else if($this->_ishavePower()){
	   		$powerlist=$this->_ishavePower();
	   		$adviser=2;
	   		$this->assign('powerlist',$powerlist);
	   }else{
	   		$adviser=0;
	   }
	   $this->assign('adviser',$adviser);
    }
    
    /**
     +----------------------------------------------------------
     * Enter 班级主页
     +----------------------------------------------------------
     * @author	小朱 2013-3-4
     +----------------------------------------------------------
     * 创建时间：	2013-3-4 下午01:19:42
     +----------------------------------------------------------
     */
    public function index(){
	   $course=D('Course')->_course($this->classid);			//课程表显示
	   $this->assign('course',$course);
	   
	   $seat=D('Seat')->_seat($this->classid,$this->classinfo);	//首页班级座位显示
	   $this->assign($seat);
	   
	   $birstlist=$this->_birst();									//班级寿星
	   $this->assign('birstlist',$birstlist);
	   
	   $result=M('class_expense')->where('isdelete=0')->select();			//班费统计
	   $this->assign('data',$result);
	   $date['sr']=M('class_expense')->field('sum(amount)as shouru')->where("type=1 and isdelete=0")->find();
	   $date['zc']=M('class_expense')->field('sum(amount)as zhichu')->where("type=2 and isdelete=0")->find();
	   $this->assign($date);
	   
	   $exelist = $this->_exelist();							//班级今日作业
	    $this->assign('exelist',$exelist);
	   
	   $visit=$this->_visitedlist();							//访客列表
	   $this->assign('visit',$visit);
	   
	   $classalbum=D('ClassAlbum')->order("mTime DESC")->where("classid=".$this->classid." and isDel=0")->limit(3)->findall();	//首页班级相册
	   $this->assign('classalbum',$classalbum);
	   
	   $leader=D('Leader')->_leaderlist($this->classid);		//班干部显示
	   $this->assign($leader);
	   
	   $data['subjectlist']=D('Subject')->_subject($this->classid);		//---首页班级选修课程（左下角）
	   $data['announcelist']=D('Announce')->order("id DESC")->where("classid=".$this->classid)->limit(6)->findall();	//首页班级公告
	   foreach($data['announcelist'] as &$v){
	   	$v['content']=text($v['content']);
	   }
	   $data['honorlist']=D('Honor')->order("id DESC")->where("classid=".$this->classid)->limit(6)->findall();			//首页班级荣誉
	   $this->assign($data);
	   
	   $weibo = $this->__getWeiboList($weibo_type,3,'','',5); //显示微博列表
	   $this->assign ($weibo );
	   
       $this->display();
    }
	

	
	/**
	 +----------------------------------------------------------
	 * 班级成员中学生信息
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:34:00
	 +----------------------------------------------------------
	 */ 
	public function StudentMember(){
	   $userinfo=uc_student_get_id($this->classid);
	   $memberlist=array();
	   foreach($userinfo as $k=>&$v){
		  $memberlist[$k]=$v;
		  //获取班级干部
		  $where = "classid=".$this->classid." AND identityid=".$v['identityid'];
		  $memberlist[$k]['leader'] = D('Leader')->where($where)->findAll();
		   //获取用户id
		  if($v['uid']){
		  	$follow=M('')->field('snsuser.uid AS uid')
							->table($this->db_prefix."user AS snsuser LEFT JOIN ".$this->db_prefix."ucenter_user_link AS uc_user ON snsuser.uid=uc_user.uid")
							->where("uc_user.uc_uid=".$v['uid'])
							->find();
			$memberlist[$k]['uid'] = $follow['uid'];
		  }
	   }
	   $this->assign('memberlist',$memberlist);
       $this->display('mem_student');
    }
    
	/**
	 +----------------------------------------------------------
	 * 班级教师
	 +----------------------------------------------------------
	 * @param	adminlist 代课老师信息
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:32:04
	 +----------------------------------------------------------
	 */
	public function TeacherMember()
    {
	   $teacherlist=uc_teacher_get_id($this->classid);
	   $adminlist=array();
	   $i=0;
	   foreach($teacherlist as $k=>&$v){
	   	  if($v['uid']){
		  	$follow=M('')->field('snsuser.uid AS uid')
							->table($this->db_prefix."user AS snsuser LEFT JOIN ".$this->db_prefix."ucenter_user_link AS uc_user ON snsuser.uid=uc_user.uid")
							->where("uc_user.uc_uid=".$v['uid'])
							->find();
			$teacherlist[$k]['uid'] = $follow['uid'];
		  }
		  //过滤出代课老师的信息
		  if($v['dutyid']==1)
		  {
			  $adminlist[$i]=$v;
		  	  $i++;
		  }
	    }
	   $this->assign('teacherlist',$teacherlist);
	   $this->assign('adminlist',$adminlist);
       $this->display('mem_teacher');
    }
    
	/**
	 +----------------------------------------------------------
	 * 班干部查看
	 +----------------------------------------------------------
	 * @author	美美2013-3-30
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-30 上午08:53:15
	 +----------------------------------------------------------
	 */
	public function LeaderMember(){
		$data=D('Leader')->_leaderlist($this->classid);
	   	$this->assign($data);
		$this->display('mem_leader');
	}
	
	/**
	 +----------------------------------------------------------
	 * 班级成员中座位信息
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:33:00
	 +----------------------------------------------------------
	 */
	public function SeatMember()
	  {
	     $user_list=D('Seat')->_seat($this->classid,$this->classinfo);
	     $this->assign($user_list);
		 $this->display('mem_seat');
	  }
	
	/**
	 +----------------------------------------------------------
	 * 公告列表
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:27:22
	 +----------------------------------------------------------
	 */
	public function MoveAnnounce()
	{
		$date=D('Announce')->where('classid='.$this->classid)->order('id DESC,aTime desc')->findPage(20);
		
		$this->assign($date);
		$this->display('move_announce');
	}

	/**
	 +----------------------------------------------------------
	 * 班级动态中的课程展示页面
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:05:14
	 +----------------------------------------------------------
	 */
	public function MoveCourse()
	{
	   $result=D('Course')->_course($this->classid);
	   $this->assign('result',$result);
       $this->display('move_course');
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 班级荣誉展示
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:16:22
	 +----------------------------------------------------------
	 */
	public function MoveHonor(){
		$honorlist=D('Honor')->HonorList($this->classid);
		$this->assign($honorlist);
		$this->display('move_honor');
	}
	/**
	 +----------------------------------------------------------
	 * 班级动态
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:04:02
	 +----------------------------------------------------------
	 */
	public function MoveState()
	{
		//默认$weibo_type为空选择所有微博类型，3表示调用班级动态信息
		$data = $this->__getWeiboList($weibo_type,3,'','',15); //显示微博列表
		$this->assign ($data);
		$this->display('move_state');
	}
	/**
	 +----------------------------------------------------------
	 * 作业模块
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-4-12
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-12 下午03:20:12
	 +----------------------------------------------------------
	 */
	public function MoveExercise()
	{
		$data = M()->table($this->db_prefix."exercise_class as c,".$this->db_prefix."exercise as e")
				->where("c.classid=".$this->classid." and c.eid=e.id")
				->order('e.id DESC,e.mtime DESC')
				->findpage(20);
		$this->assign($data);
		$this->display('move_exercise');
	}
	
	
	
	
	

	
	
	

	/**
	 +----------------------------------------------------------
	 * 班级相册
	 +----------------------------------------------------------
	 * @param	$classalbum 班级相册数组
	 * @param	$useralbum	班级学生相册信息
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:28:16
	 +----------------------------------------------------------
	 */
    public function MovePhoto(){
		$classalbum=D('ClassAlbum')->_albums($this->classid);
		$this->assign('classalbum',$classalbum);
		$userlist=uc_student_get_id($this->classid);
		$userids=array();
		$n=0;
		foreach($userlist as $key=>$obj){
			 if($obj['uid']){
				  $userids[$n]=$obj['uid'];
				  $n++;
			  }
		}
		$uids=implode(",",$userids);
		$follow=M('')->field('snsuser.uid AS fuid')
				   ->table("{$this->db_prefix}user AS snsuser LEFT JOIN {$this->db_prefix}ucenter_user_link AS uc_user ON snsuser.uid=uc_user.uid")
				   ->where("uc_user.uc_uid in ($uids)")
				   ->order('snsuser.uid ASC')
				   ->findall();
		unset($uids);
		foreach($follow as $k=>$v){
			if($k==0)
			$uids=$v['fuid'];
			else
			$uids.=','.$v['fuid'];
		}
		$useralbum	=	D('Album','photo')->order("mTime DESC")->where("userId in (".$uids.") and privacy=1")->findall();
		$this->assign('useralbum',$useralbum);
		$this->display('move_photo');
	}
	
	/**
	 +----------------------------------------------------------
	 * 班级寿星
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-4-15
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-15 下午05:02:38
	 +----------------------------------------------------------
	 */
	public function MoveBirthday(){
		$birstlist=$this->_birst();
		foreach ($birstlist as $k=>$v){
			$birstlist[$k]['month']=intval(substr($v['csrq'],4,2));
			$birstlist[$k]['day']=intval(substr($v['csrq'],6,2));
		}
		$this->assign('birstlist',$birstlist);
		$this->display("move_birthday");
	}
	
	/**
	 +----------------------------------------------------------
	 * 相册
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-4-15
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-15 下午05:02:16
	 +----------------------------------------------------------
	 */
	public function albums() {
		$id		=	intval($_REQUEST['id']);
		//获取相册信息
		$albumDao = D('ClassAlbum');
		$album	  =	$albumDao->where("id={$id}")->find();

		if(!$album){
			$this->assign('jumpUrl', U('space/Manage/ManagePhoto',array(classid=>$this->classid)));
			$this->error('专辑不存在或已被删除！');
		}
		//获取图片数据
		//$raws	=	$this->setting['photo_raws'];
		//$order	=	$this->setting['album_default_order'];
		$order	=	'`order` DESC,id DESC';
		$map['albumId']	=	$id;
		$map['classid']	=	$this->classid;
		$map['isDel']	=	0;

		$config = getConfig();
		$photos	= D('ClassPhoto')->order($order)->where($map)->findPage($config['photo_raws']);
		if($photos['data']){
			$this->assign('photos',$photos);

			//点击率加1
			$albumDao->execute('UPDATE '.C('DB_PREFIX').$albumDao->tableName." SET readCount=readCount+1 WHERE id={$id} AND userId={$this->uid} LIMIT 1");
			$this->assign('photo_preview',$config['photo_preview']);
			$this->assign('album',$album);
		}
		$this->setTitle($album['name']);
		$this->display();
	}
	
/**
	 +----------------------------------------------------------
	 * 显示一张照片信息
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:24:16
	 +----------------------------------------------------------
	 */
	public function photos() {
		$classid  = $this->classid;
		$aid  =	intval($_REQUEST['aid']);
		$id   = intval($_REQUEST['id']);
		$type =	t($_REQUEST['type']);	//图片来源类型，来自某相册，还是其它的

		//判断来源类型
		if(!empty($type) && $type!='mAll'){
			$this->error('错误的链接！');
		}
		$this->assign('type',$type);

		//获取所在相册信息
		$albumDao = D('ClassAlbum');
		$album = $albumDao->find($aid);
		if(!$album){
			$this->assign('jumpUrl', U('space/Manage/ManagePhoto',array(classid=>$this->classid)));
			$this->error('专辑不存在或已被删除！');
		}

		//获取图片信息
		$photoDao = D('ClassPhoto');
		$photo	  =	$photoDao->where(" albumId={$aid} AND `id`={$id} AND classid={$classid} ")->find();
		$this->assign('photo',$photo);

		//验证图片信息是否正确
		if(!$photo){
			$this->assign('jumpUrl', U('space/Upload/album', array('classid'=>$classid,'id'=>$aid)));
			$this->error('图片不存在或已被删除！');
		}

		$this->assign('album',$album);
		//$order	=	$this->setting['album_default_order'];

		//获取所有图片数据
		$photos	=	$albumDao->getPhotos($this->classid,$aid,$type,$order,5);
		//$this->assign('photos',$photos);

		//获取上一页 下一页 和 预览图
		if($photos){
			foreach($photos as $v){
				$photoIds[]	=	intval($v['id']);
			}
			$photoCount	=	count($photoIds);

			//颠倒数组，取索引
			$pindex		=	array_flip($photoIds);

			//当前位置索引
			$now_index	=	$pindex[$id];

			//上一张
			$pre_index	=	$now_index-1;
			if( $now_index <= 0 )	{
				$pre_index	=	$photoCount-1;
			}
			$pre_photo	=	$photos[$pre_index];

			//下一张
			$next_index	=	$now_index+1;
			if( $now_index >= $photoCount-1 ) {
				$next_index	=	0;
			}
			$next_photo	=	$photos[$next_index];

			//预览图的位置索引
			$start_index	=	$now_index - 2;
			if($photoCount-$start_index<5){
				$start_index	=	($photoCount-5);
			}
			if($start_index<0){
				$start_index	=	0;
			}

			//取出预览图列表 最多5个
			$preview_photos	=	array_slice($photos,$start_index,5);
		}else{
			$this->error('图片列表数据错误！');
		}
		//点击率加1
		$photoDao->execute('UPDATE '.C('DB_PREFIX').$photoDao->tableName." SET readCount=readCount+1 WHERE id={$id} AND albumId={$aid} AND classid={$classid} LIMIT 1");

		$this->assign('photoCount',$photoCount);
		$this->assign('now',$now_index+1);
		$this->assign('pre',$pre_photo);
		$this->assign('next',$next_photo);
		$this->assign('previews',$preview_photos);

		unset($pindex);
		unset($photos);
		unset($album);
		unset($preview_photos);
		$this->display();
	}
	
	/**
	 +----------------------------------------------------------
	 * 展示公告页面
	 +----------------------------------------------------------
	 * @param	int aid 公告id
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-4-15
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-15 上午11:36:09
	 +----------------------------------------------------------
	 */
	public function ShowAnnounce(){
		if(empty($_GET['aid']) || !intval($_GET['aid'])>0)	$this->error('数据错误');
		$map['id']=intval($_GET['aid']);
		$map['classid']=$this->classid;
		$date=D('Announce')->where($map)->find();
		if(!$date){
			$this->error("该公告不存在或者已被删除");
		}
		$date['content'] = htmlspecialchars_decode($date['content']);
		$this->assign($date);
		$this->display('showannounce');
	}
	
	/**
	 +----------------------------------------------------------
	 * 班级公告展示
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-4-15
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-15 下午03:50:10
	 +----------------------------------------------------------
	 */
    public function showHonor(){
    	if(empty($_GET['hid']) || !intval($_GET['hid'])>0)	$this->error('数据错误');
		$map['id']=intval($_GET['hid']);
		$map['classid']=$this->classid;
		$date=D('Honor')->where($map)->find();
		if(!$date){
			$this->error("该荣誉不存在或者已被删除");
		}
		$date['content'] = htmlspecialchars_decode($date['content']);
		$this->assign($date);
		$this->display('showhonor');
    }
	
    /**
     +----------------------------------------------------------
     * 班级作业展示
     +----------------------------------------------------------
     * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
     * @return	return_type <返回类型(void的方法就不用该选项)>
     * @author	小朱 2013-4-15
     +----------------------------------------------------------
     * 创建时间：	2013-4-15 下午04:08:30
     +----------------------------------------------------------
     */
	public function showExercise(){
		if(empty($_GET['hid']) || !intval($_GET['hid'])>0)	$this->error('数据错误');
		$id=intval($_GET['hid']);
		$date = M()->table($this->db_prefix."exercise_class as c,".$this->db_prefix."exercise as e")
				->where("c.classid=".$this->classid." and c.eid=e.id and e.id=".$id)
				->find();
		if(!$date){
			$this->error("该作业不存在或者已被删除");
		}
		$date['attach']	  = D('ExerciseAttach')->where('eid='.$id)->findAll();//获取作业附件
		$date['resource'] = D('ExerciseResource')->where('eid='.$id)->findAll();//获取资源附件
		$date['content'] = htmlspecialchars_decode($date['content']);
		$this->assign($date);
		//var_dump($date);
		$this->display('showexercise');
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取班级动态信息
	 +----------------------------------------------------------
	 * @return	array data 动态信息 
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午01:38:47
	 +----------------------------------------------------------
	 */
	private function __getWeiboList($weibo_type,$simple=0,$classid,$lastId,$num=5){
        // 关注的分组列表
        $data ['follow_gid'] = 'all';
		$classid=$this->classid;
        $data ['list'] = D('Operate', 'weibo')->getClasspaceList($classid, $weibo_type,$lastId,$num, $data ['follow_gid'],4);
		if($data['list']['data']){
			// 最新一条微广播的Id (countNew时使用)
			$_last_weibo = reset($data ['list'] ['data']);
			$data ['lastId'] = $_last_weibo['weibo_id'];
			$_since_weibo = end($data ['list'] ['data']);
			$data['sinceId'] = $_since_weibo['weibo_id'];
		}
        return $data;
    }
	
	
	
	
	
	
	/**
	 +----------------------------------------------------------
	 * 获取今日作业信息列表
	 +----------------------------------------------------------
	 * @return	Array 作业列表
	 * @author	美美2013-4-3
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-3 上午02:18:22
	 +----------------------------------------------------------
	 */
	private function _exelist(){
		$exelist = M()->table($this->db_prefix."exercise_class as c,".$this->db_prefix."exercise as e")
				->where("c.classid=".$this->classid." and c.eid=e.id")
				->order('e.id DESC,e.mtime DESC')
				->limit(5)
				->findAll();
		return $exelist;
	}
	/**
	 +----------------------------------------------------------
	 * 访问记录（公共方法）
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午03:18:17
	 +----------------------------------------------------------
	 */
	private function _visitedlist()
	{
		return D('Visit')->where('classid='.$this->classid)->order('vTime DESC')->limit(9)->findall();
	}
	
	
	
	
	
	public function _birst(){
	   $birthlist=uc_birth_student_get_id($this->classid);		//获取班级寿星
	   foreach($birthlist as $key=>$obj){
	   	 if($obj['uid']){
		  	$follow=M('')->field('snsuser.uid AS uid')
				 	->table("{$this->db_prefix}user AS snsuser LEFT JOIN {$this->db_prefix}ucenter_user_link AS uc_user ON snsuser.uid=uc_user.uid")
				 	->where("uc_user.uc_uid=".$obj['uid'])
				 	->order('snsuser.uid ASC')
				 	->find(); 
		  	$birthlist[$key]['uid']=$follow['uid'];
		 }
	   }
	 return $birthlist;
	}
	
	/**
	 +----------------------------------------------------------
	 * 验证管理权限
	 +----------------------------------------------------------
	 * @param	str session 读取SESSION中是否具有管理权限
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午03:24:23
	 +----------------------------------------------------------
	 */
	private function _getclassAdviser(){
		$uc_uid=uc_class_adviser_get_id($this->classid);
		if($uc_uid){
			if($uc_uid==$this->uc_session['uid']){
				return true;
			}else{
				return false;
			}	
		 }else{
		 	return false;
		 }
	}
	
	/**
	 +----------------------------------------------------------
	 * 检查是否有权限
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-7-9
	 +----------------------------------------------------------
	 * 创建时间：	2013-7-9 下午01:15:42
	 +----------------------------------------------------------
	 */
	private function _ishavePower(){
		$map['identityid']=$this->identityid;
		$map['classid']=$this->classid;
		$result=D('SetManager')->where($map)->order('mid ASC')->findall();
		return $result;
	}
	/**
	
	public function MoveMoney(){
		$this->display("move_money");
	}
	
	
	public function ShareBooks(){
		$this->display("move_sharebooks");
	}
	
	
	public function MoveGloryByname(){
		$this->display("move_glory_byname");
	}
	
	
	public function MoveBooks(){
		$this->display("move_books");
	}
	
	
	public function MoveGlory(){
		$this->display("move_glory");
	}
	
	
	public function BookDetail(){
		$this->display("move_bookdetail");
	}
	*/
}