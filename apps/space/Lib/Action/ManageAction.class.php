<?php
/**
 +------------------------------------------------------------------------------
 * ESN 相册信息 Action
 +------------------------------------------------------------------------------
 * @category	ESN ManageAction
 * @package		ESN Lib/Action
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 下午05:34:55
 +------------------------------------------------------------------------------
 */
class ManageAction extends BaseAction{
	
	private $db_prefix;
    private $classid;//班级编号id
	private $classinfo;//array类型，从api和sns中查询到的班级基础信息
	private $identityid;
	private $identitytype;
	private $action;//方法名
	private $module;//控制器名称
	private $adviser;
	private $uc_session; //session
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
	   $this->uc_session=arrayKeyTolower($_SESSION['ucInfo']);//当前
	   $this->identityid=$this->uc_session['identityid'];
	   $this->identitytype=$this->uc_session['identitytype'];
	   if(empty($_REQUEST['classid']) || !intval($_REQUEST['classid'])>0){
			$this->error('非法操作！');
		}
	   $this->classid=intval($_REQUEST['classid']);
	   $this->assign('classid',$this->classid);
	   $uid=$this->mid;
	   $this->assign($uid);
	   D('Visit')->_visited($this->uid,$this->classid);//记录访问记录
	   $this->classinfo=D('ClassBasic')->_classInfo($this->classid);//获取班级基础信息
	   $this->assign('classinfo',$this->classinfo);
	   if($this->_getclassAdviser()){
	   		$this->adviser=1;
	   }else if($this->_ishavePower()){
	   		$arrays=array('doManageInfo','AddSubject','doAddSubject','doDeleteSubject','GetPersonPower','AddPower','GetPersonLeader','AddLeader','doSetCourse','doManageCourse','doSetSeat','doManageSeat','doManageAnnounce','doManageHonor','doDeleteAnnounce','doDeleteHonor','addGlory');
	   		if(!in_array($this->action,$arrays)){
	   			if(!$this->_getpower($this->module,$this->action)){	//判断是否有此次访问连接地址的权限
	    			$this->error('您没有权限访问此功能');
	  		 	}
	   		}
	   		$powerlist=M('')->table($this->db_prefix.'class_manager as m,'.$this->db_prefix.'class_setmanager as s')
							->where("m.id=s.mid and s.classid=".$this->classid." and s.identityid=".$this->identityid)
							->order("m.id ASC")
							->findall();
	   		$this->adviser=2;
	   		$this->assign('powerlist',$powerlist);
	   }else{
	   		$this->adviser=0;
	   }
	   $this->assign('adviser',$this->adviser);
	  
    }
	
	
	
	/**
	 +----------------------------------------------------------
	 * 班级基础信息
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午03:27:07
	 +----------------------------------------------------------
	 */
	public function ManageInfo(){
		$subject=M('class_per_subject')->where('classid='.$this->classid)->findAll();
		$this->assign('subject',$subject);
		$this->display('m_classinfo');
	}	

	/**
	 +----------------------------------------------------------
	 * 处理班级基础信息设置
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午03:55:05
	 +----------------------------------------------------------
	 */
	public function doManageInfo(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageInfo')){
				$this->error('您没有权限');
			}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		$logan = text(h($_POST['logan']))?text(h($_POST['logan'])):'';
		if( mb_strlen($logan, 'UTF-8') > 100 ){
			$this->error( "班级口号不得大于100个字符" );
			exit;
		}
		$data['logan'] = $logan;
		$data['message'] = text(h($_POST['message']));
		$data['info'] = text(h($_POST['info']));
		$data['classid'] = $this->classid;
		/*处理班级logo上传*/
		$options['userId']		=	$this->mid;
		$options['allow_exts']	=	'jpeg,gif,jpg,png';
		$options['max_size']    =   floatval(1024*1024*2);
		$logo	=	X('Xattach')->upload('classlogo',$options);
		if($logo['status']){									//图片上传
			$size['small']['x']	=	110;						//缩图规格
			$size['small']['y']	=	81;
			$bigpic		=	$logo['info'][0]['savepath'].$logo['info'][0]['savename']; 	//缩图路径-文件名
			$smallpic	=	$logo['info'][0]['savepath'].'small_'.$logo['info'][0]['savename'];
			include_once SITE_PATH.'/addons/libs/Image.class.php';						//缩图
			Image::thumb( UPLOAD_PATH.'/'.$bigpic , UPLOAD_PATH.'/'.$smallpic , '' , $size['small']['x'] , $size['small']['y'] );
		}
		if(is_array($logo['info'][0]))	//获取图片在attach表中的id
			$data['logo']=$logo['info'][0]['id'];
		$info=D('ClassBasic')->where('classid='.$this->classid)->find();
		if($info){
			D('ClassBasic')->where('classid='.$this->classid)->save($data);
			$this->success('修改成功！');
		}
		else{
			$add =D('ClassBasic')->add($data);
			if($add){
				$this->success('设置成功！');
			}
			else{
				$this->error( "设置失败！" );
			}
		}		
	}			
	
	/**
	 +----------------------------------------------------------
	 * 选择编辑课程页
	 +----------------------------------------------------------
	 * @return	array data 课程列表
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午03:44:54
	 +----------------------------------------------------------
	 */
	public function AddSubject(){
		$data['dictionary']=M('category_dictionary')		
					->where("DataType='Subject'")
					->findall();						//查询所有科目信息
		$data = arrayKeyTolower($data);
		$this->assign($data);
		$subjectlist=D('Subject')->field('subjectid')->where('classid='.$this->classid)->findall();	//以选择科目信息
		$arr=array();
		foreach($subjectlist as $k=>$v){
			$arr[$k]=$v['subjectid'];
		}
		$this->assign('subjectlist',$arr);
		$this->display('m_addsubject');
	}	
			
	/**
	 +----------------------------------------------------------
	 * 处理选课信息
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午03:48:09
	 +----------------------------------------------------------
	 */
	public function doAddSubject(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageInfo')){
				$this->error('您没有权限');
			}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		if(!is_array($_POST['subjectid']))  $this->error('数据错误');
		$items=$_POST['subjectid'];
		if(empty($items)){$this->error('非法操作！');}
		$res=M('class_per_subject')->where('classid='.$this->classid)->delete();
		foreach($items as $k=>$v){
			$data['subjectid'] = $v;
			$data['classid'] = $this->classid;
			$add =M('class_per_subject')->add($data);
			if(!$add){$this->error('非法操作！');}
		}
		$result=M('class_per_subject')->field('subjectid')->where('classid='.$this->classid)->findall();
		$subjectstring='';
		foreach($result as $k=>$v){
			if($k>0){
				$subjectstring.=','.$v['subjectid'];
			}else{
				$subjectstring=$v['subjectid'];
			}
		}
		D('Course')->where('classid='.$this->classid.' and subjectid not in ('.$subjectstring.')')->delete();
		$this->success('添加成功');
	}		
	/**
	 +----------------------------------------------------------
	 * ajax删除班级选课（方法功能的注释）
	 +----------------------------------------------------------
	 * @param	int id 课程id
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午04:30:04
	 +----------------------------------------------------------
	 */
	public function doDeleteSubject(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageInfo')){
				echo 0;exit;
			}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		$id=intval($_POST['id']);
		if(empty($id)){echo 0;exit;}
		$sub=D('Subject')->where('classid='.$this->classid.' and id='.$id)->find();
		$result=D('Subject')->where('classid='.$this->classid.' and id='.$id)->delete();
		if($result){
			D('Course')->where('classid='.$this->classid.' and subjectid='.$sub['subjectid'])->delete();
			echo 1;
		}else{
			echo 0;exit;
		}
		   
	}	
	 
	/**
	 +----------------------------------------------------------
	 * 班级空间管理权限
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:30:03
	 +----------------------------------------------------------
	 */
	public function ManagePower(){
	    $uc_adviser_uid=intval(uc_class_adviser_get_id($this->classid));//获取班主任
	    //获取班级教师
	    $teacherlist = uc_teacher_get_id($this->classid);
	    foreach($teacherlist as $k=>$v){
	    	if($v['uid']==$uc_adviser_uid){
	    		unset($teacherlist[$k]);
	    	}
	    }
	    $data['teacherlist']=$teacherlist;
	    //获取班级成员信息
	    $data['userinfo'] = uc_student_get_id($this->classid);
		//所有权限查询
		$data['Jurisdicte']=D('Manager')->order('id ASC')->findall();
		$date=D('SetManager')->_power($this->classid,$this->db_prefix);
		$this->assign($date);
		$this->assign($data);
		//var_dump($data);
        $this->display('m_managepower');
    }
	
	/**
	 +----------------------------------------------------------
	 * ajax动态Click获取权限信息
	 +----------------------------------------------------------
	 * @author	美美2013-3-30
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-30 上午02:32:01
	 +----------------------------------------------------------
	 */
	public function GetPersonPower(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePower')){
				echo 0;exit;
			}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		if(empty($_POST['identityid']) || empty($_POST['identitytype'])) {echo 0;exit;}
		$map['identityid'] = intval($_POST['identityid']);
		$map['identitytype'] = intval($_POST['identitytype']);
		$map['classid']=$this->classid;
		$position = M('class_setmanager')->where($map)->findAll();
		echo json_encode($position);
	}
	
	/**
	 +----------------------------------------------------------
	 * 班主任给用户设置权限
	 +----------------------------------------------------------
	 * @param	int identityid 身份id
	 * @param	array	$_POST['mid'] 权限类型id
	 * @author	美美2013-3-29
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-29 上午06:06:55
	 +----------------------------------------------------------
	 */	
	public function AddPower(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePower')){
				echo 0;exit;
			}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		$data['identityid']=intval($_POST['identityid']);
		$data['identitytype']=intval($_POST['identitytype']);
		$data['classid']=$this->classid;
		M('class_setmanager')->where($data)->delete();
		if(!empty($_POST['mid'])){
			if(is_array($_POST['mid'])){
				$positionid=$_POST['mid'];
				foreach($positionid as $v){
					$data['mid']=$v;
					M('class_setmanager')->add($data);
				}
			}
		}
		$date=D('SetManager')->_power($this->classid,$this->db_prefix);
		$this->assign($date);
		$this->display('m_showpower');
		
	}
	
	/**
	 +----------------------------------------------------------
	 * 班干部管理页面
	 +----------------------------------------------------------
	 * @return	Array <返回类型(void的方法就不用该选项)>
	 * @author	美美2013-3-29
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-29 上午01:49:55
	 +----------------------------------------------------------
	 */
	public function ManageLeader(){
		$data['userinfo'] = uc_student_get_id($this->classid);//api获取班级所有成员信息
		$data['leaderinfo'] = M('class_position')->findAll();//获取班级职位
	 	$date=D('Leader')->_leader($this->classid);
	 	$this->assign($data);
		$this->assign($date);
		$this->display('m_manageleader');
	}
	
	/**
	 +----------------------------------------------------------
	 * ajax动态获取学生班级干部信息
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	Array <返回类型(void的方法就不用该选项)>
	 * @author	美美2013-3-30
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-30 上午02:32:01
	 +----------------------------------------------------------
	 */
	public function GetPersonLeader(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageLeader')){
				echo 0;exit;
			}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		if(empty($_POST['identityid'])) {echo 0;exit;}
		$identityid = intval($_POST['identityid']);
		$position = D('Leader')->where('identityid='.$identityid.' and classid='.$this->classid)->findAll();
		echo json_encode($position);
	}
	
	/**
	 +----------------------------------------------------------
	 * 修改职务方法
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	Array <返回类型(void的方法就不用该选项)>
	 * @author	美美2013-3-29
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-29 上午06:06:55
	 +----------------------------------------------------------
	 */	
	public function AddLeader(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageLeader')){
				echo 0;exit;
			}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		if(empty($_POST['identityid']) || !intval($_POST['identityid'])>0) {echo 0;exit;}
		$data['identityid']=intval($_POST['identityid']);
		$data['classid']=$this->classid;
		D('Leader')->where($data)->delete();
		if(!empty($_POST['positionid'])){
			if(is_array($_POST['positionid'])){
				$positionid=$_POST['positionid'];
				foreach($positionid as $v){
				$data['positionid']=$v;
				D('Leader')->add($data);
				}
			}
		}
		$date=D('Leader')->_leader($this->classid);
		$this->assign($date);
		$this->display('m_showleader');
	}
	
	/**
	 +----------------------------------------------------------
	 * 课程表管理页（方法功能的注释）
	 +----------------------------------------------------------
	 * @param	array course 班级已经排定课程
	 * @param	array subject 班级已经选择学科
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午04:32:17
	 +----------------------------------------------------------
	 */
	 public function ManageCourse(){
		 $course=D('Course')->_course($this->classid);
		 $this->assign('result',$course);
		 $subject=D('Subject')->_subject($this->classid);
		 $this->assign('subject',$subject);
		 $this->display('m_managecource');
	  } 
	  
	  /**
	   +----------------------------------------------------------
	   * 课程表设置（课程节数设置）
	   +----------------------------------------------------------
	   * @author	小朱 2013-3-4
	   +----------------------------------------------------------
	   * 创建时间：	2013-3-4 下午04:36:35
	   +----------------------------------------------------------
	   */
	  public function doSetCourse(){
	  	if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageCourse')){
				$this->error('您没有权限');
			}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		  $map['classid']=$this->classid;
		  $data['zzx']=intval($_POST['zzxcourse']);
		  $data['zw']=intval($_POST['morcourse']);
		  $data['xw']=intval($_POST['aftcourse']);
		  $data['wzx']=intval($_POST['wzxcourse']);
		  D('ClassBasic')->where($map)->save($data);
		  D('Course')->where('classid='.$this->classid.' and quantum=1 and festival >'.$data['zzx'])->delete();
		  D('Course')->where('classid='.$this->classid.' and quantum=2 and festival >'.$data['zw'])->delete();
		  D('Course')->where('classid='.$this->classid.' and quantum=3 and festival >'.$data['xw'])->delete();
		  D('Course')->where('classid='.$this->classid.' and quantum=4 and festival >'.$data['wzx'])->delete();
		  $this->success('设置成功');
	  }
	  
	 /**
	   +----------------------------------------------------------
	   * 保存课程信息
	   +----------------------------------------------------------
	   * @param	array $seatlist 需保存信息
	   * @author	小朱 2013-3-4
	   +----------------------------------------------------------
	   * 创建时间：	2013-3-4 下午04:51:35
	   +----------------------------------------------------------
	   */ 
	 public function doManageCourse(){
	 	if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageCourse')){
				echo 0;exit;
			}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		$seatlist=$_POST['data'];
		foreach($seatlist as $k=>$v){
			$sqlArr[]="($v,$this->classid)";
		}
		if($sqlArr){
			 D('Seat')->execute("DELETE FROM ".$this->db_prefix."class_course where classid=".$this->classid);
			 $sql="INSERT INTO ".$this->db_prefix."class_course (quantum,festival,weekday,subjectid,classid) VALUES ".implode(',',$sqlArr);
			 $res=D('Course')->execute($sql);
			 if($res)
			  echo 1;
			 else
			  echo 0;
		  }
		else
		  echo 0;
	  }
	  
	  /**
	   +----------------------------------------------------------
	   * 班级座位信息
	   +----------------------------------------------------------
	   * @param	array $user_list 班级座位数组
	   * @author	小朱 2013-3-4
	   +----------------------------------------------------------
	   * 创建时间：	2013-3-4 下午04:54:48
	   +----------------------------------------------------------
	   */
	public function ManageSeat(){
		$user_list=D('Seat')->_seat($this->classid,$this->classinfo);
		$this->assign($user_list);
		$this->display('m_manageseat');	
	}

	/**
	 +----------------------------------------------------------
	 * 设置班级座位几排几列
	 +----------------------------------------------------------
	 * @param	array $data 排、列
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午04:56:59
	 +----------------------------------------------------------
	 */
	public function doSetSeat(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageSeat')){
				$this->error('您没有权限');
			}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		if(!$_POST['cols'] && !$_POST['rows']){$this->error("非法操作");}
		$data['seatcol']=intval($_POST['cols']);
		$data['seatrow']=intval($_POST['rows']);
		$res=D('ClassBasic')->where('classid='.$this->classid)->save($data);
		D('Seat')->where('col >'.$data['seatcol'])->delete();
		$this->success("设置成功");
	}	
	
	/**
	 +----------------------------------------------------------
	 * 处理保存班级座位
	 +----------------------------------------------------------
	 * @param	array $seatlist 座位数据
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:00:46
	 +----------------------------------------------------------
	 */
	public function doManageSeat(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageSeat')){
				echo 0;exit;
			}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		$seatlist=$_POST['data'];
		foreach($seatlist as $k=>$v){
			  $sqlArr[]="($v,$this->classid)";
		}
		if($sqlArr){
			D('Seat')->execute("DELETE FROM ".$this->db_prefix."class_seat where classid=".$this->classid);
			$sql="INSERT INTO ".$this->db_prefix."class_seat (identityid,row,col,classid) VALUES ".implode(',',$sqlArr);
			$res=D('Seat')->execute($sql);
			if(!$res)
				echo 0;
			else
				echo 1;
		}else{
			D('Seat')->execute("DELETE FROM ".$this->db_prefix."class_seat where classid=".$this->classid);
			echo 1;
		}
	}	
	
	/**
	 +----------------------------------------------------------
	 * 公告管理
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:27:22
	 +----------------------------------------------------------
	 */
	public function ManageAnnounce(){
		$announcelist=D('Announce')->where('classid='.$this->classid)->order('id DESC,aTime desc')->findPage(15);
		$this->assign($announcelist);
		$this->display('m_manageannounce');
	}
	
	/**
	 +----------------------------------------------------------
	 * 处理添加班级公告
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:27:49
	 +----------------------------------------------------------
	 */
	public function doManageAnnounce(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageAnnounce')){
				$this->error('您没有权限');
			}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		if(empty($_POST['title'])) $this->error( "请填写公告标题！" );
		 $data['title'] = h(t($_POST['title']));
        if(empty($_POST['content'])) $this->error( "请填写公告内容！" );
         $data['content'] = safe($_POST['content']);
		$data['aTime'] = time();
		$data['classid']=$this->classid;
		$data['uid']=$this->mid;
		if(D('Announce')->add($data)) 
			$this->success('公告发表成功！');
		else 
			$this->error('公告发表失败');
	}
	
	/**
	 +----------------------------------------------------------
	 * 删除公告
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-4-15
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-15 下午05:38:18
	 +----------------------------------------------------------
	 */
	public function doDeleteAnnounce(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageAnnounce')){
				echo 0;exit;
			}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		if(empty($_POST['id']) || !intval($_POST['id'])>0){
			echo 0;exit;
		}
		$map['id']=intval($_POST['id']);
		$map['classid']=$this->classid;
		if(D('Announce')->where($map)->delete()){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 班级相册管理
	 +----------------------------------------------------------
	 * @param	$classalbum 班级相册数组
	 * @param	$useralbum	班级学生相册信息
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:28:16
	 +----------------------------------------------------------
	 */
    public function ManagePhoto(){
    	$classalbum=D('ClassAlbum')->_albums($this->classid);
		$this->assign('classalbum',$classalbum);
		$this->display('m_managephoto');
	}
	
	/**
	 +----------------------------------------------------------
	 * 班级荣誉管理
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:28:16
	 +----------------------------------------------------------
	 */
    public function ManageHonor(){
    	$honorlist=D('Honor')->HonorList($this->classid);
		$this->assign($honorlist);
		$this->display('m_managehonor');
	}
	
	/**
	 +----------------------------------------------------------
	 * 处理添加荣誉
	 +----------------------------------------------------------
	 * @param   $toUserId 荣誉接收人数组
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午05:23:58
	 +----------------------------------------------------------
	 */
	public function doManageHonor(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageHonor')){
				$this->error('您没有权限');
			}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		$toUserId = $_POST['fri_ids'];
		if(empty($_POST['title'])) $this->error( "请填写标题！" );
		$data['title'] = h(t($_POST['title']));
        if(empty($_POST['content'])) $this->error( "请填写内容！" );
        $data['content'] = safe($_POST['content']);
		$data['uid']=$this->mid;
		$formuid=$this->mid;
		$data['classid']=$this->classid;
		$result = D('Honor')->sendHonor($toUserId,$data,$formuid,$this->classid); 
		if($result===true){
			$this->success('荣誉发布成功！');
		}else{
			//如果发送失败就跳转到错误提示页并显示失败原因
			$this->error('发布失败');
		} 
	}
	
	/**
	 +----------------------------------------------------------
	 * 删除荣誉
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-4-15
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-15 下午05:38:18
	 +----------------------------------------------------------
	 */
	public function doDeleteHonor(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManageHonor')){
				echo 0;exit;
			}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		if(empty($_POST['id']) || !intval($_POST['id'])>0){
			echo 0;exit;
		}
		$map['id']=intval($_POST['id']);
		$map['classid']=$this->classid;
		if(D('Honor')->where($map)->delete()){
			D('HonorList')->where("id=".$map['id'])->delete();
			echo 1;
		}else{
			echo 0;
		}
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
	 * 创建时间：	2013-7-9 下午01:11:56
	 +----------------------------------------------------------
	 */
	private function _ishavePower(){
		$map['identityid']=$this->identityid;
		$map['classid']=$this->classid;
		$map['identitytype']=$this->identitytype;
		$result=D('SetManager')->where($map)->order('mid ASC')->findall();
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 检验用户是否具有某权限
	 * @param   $mid 权限id
	 * @return	是否具有权限
	 * @author	美美2013-4-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-1 上午08:33:57
	 +----------------------------------------------------------
	 */
	private function _getpower($module,$act){
		$result=M('')->table($this->db_prefix.'class_manager as m,'.$this->db_prefix.'class_setmanager as s')
			->where("m.id=s.mid and s.classid=".$this->classid." and s.identityid=".$this->identityid." and m.module='".$module."' and act='".$act."'")
			->find();
		if(!$result){
			return false;
		}else{
			return true;
		}
	}
	
	 /**
	  +----------------------------------------------------------
	  * 班费管理
	  +----------------------------------------------------------
	  * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	  * @return	return_type <返回类型(void的方法就不用该选项)>
	  * @author	小朱 2013-7-9
	  +----------------------------------------------------------
	  * 创建时间：	2013-7-9 下午04:31:32
	  +----------------------------------------------------------
	  */
	 public function ManageMoney(){
	 	 $result=M('class_expense')->where('isdelete=0')->findall();
	 	 $this->assign('data',$result);
	 	 $date['sr']=M('class_expense')->field('sum(amount)as shouru')->where("type=1 and isdelete=0")->find();
	 	 $date['zc']=M('class_expense')->field('sum(amount)as zhichu')->where("type=2 and isdelete=0")->find();
	 	 $this->assign($date);
		 $this->display('m_managemoney');
	 }
	 
	 
	/**
 	public function addMoney(){
	 	$this->display('m_addMoney');
	 }
	 
	 public function doaddMoney(){
	 	$map['id']=$_POST['id'];											//POST与GET的区别
	 	$data['classid']=$_POST['classid'];
	 	$data['uid']= $_POST['uid'];
	 	$data['type'] = $_POST['type'];
	 	$data['amount'] = $_POST['amount'];
	 	$data['handle'] = $_POST['handle'];
	 	$data['ctime'] =strtotime(now);										//$data['dealtime']=strtotime("now");
	 	$data['year']=((int)substr(date('Ymd',$data['ctime']),0,4));		//取得年份
	 	$data['isdelete']=0;
	 	$data['remark'] = $_POST['remark'];
	 	//操作纪录表
	 	$datar['uid']= $_POST['uid'];
	 	$datar['ctime']=strtotime(now);
	 	if($map['id']){
	 		$datar['type']= 3;
	 		$info=array('uid'=>$datar['uid'],"在",'mandle'=>"编辑");
	 		//李丽在2013-04-05　22:22:22向烟台三中高二19班添加100元
	 		//李丽在2013-04-05　22:22:22向烟台三中高二19班删除100元
	 		//李丽在2013-04-05　22:22:22向烟台三中高二19班将收入100元改为支出200元
	 		$datar['userinfo']=serialize($info);
	 		$expenserecord=M('class_expense_record')->add($datar);
	 		$result=M('class_expense')->where($map)->save($data);
	 		if($result&&$expenserecord){
	 			$this->success('编辑成功');
	 		}
	 		else{
	 			$this->error('编辑失败');
	 		}
	 	}
	   	else {
	   		 $datar['type']=1;
	   		 $info=array('uid'=>$datar['uid'],'mandle'=>"1");
	   		 $datar['userinfo']=serialize($info);
	   		 $expenserecord=M('class_expense_record')->add($datar);
	   		 $result=M('class_expense')->add($data);
	   		 if($result&&$expenserecord){
			 $this->success('添加成功');
	   		 }
	   		 else{
	   		 	$this->error('添加失败');
	   		 }
	   	}
	 }
	 
	 
	 public function selMoney(){
	 	if(empty($_GET['id'])) $this->error('数据错误');
	 	$map['id']=$_GET['id'];
	 	$result=M('class_expense')->where($map)->find();
	 	$this->assign($result);				
	 	$this->display('m_selMoney');
	 }
	 
	
	 
	 public function editMoney(){
	 	if(empty($_GET['id'])) $this->error('数据错误');
	 	$map['id']=$_GET['id'];
	 	
	 	$result=M('class_expense')->where($map)->find();
	 	$this->assign($result);
	 	$this->display('m_addMoney');
	 }
	 
	 
	 
	 public function delMoney(){
	 	$id=$_REQUEST['id'];												//REQUEST和GET方法都可以
	 	if(empty($_GET['id'])) $this->error('数据错误');
	 	$classid=$_REQUEST['classid'];
	 	$datar['uid']= $this->mid;											//获取登录者的id
	 	$datar['type']= 2;
	 	$info=array('uid'=>$datar['uid'],'type'=>'2');
	 	$datar['userinfo']=serialize($info);
	 	$datar['ctime']=strtotime(now);
	 	if (!empty($id)&&!empty($classid)) {
	 		$result=M('class_expense')->setField('isdelete','1',"id=$id");		//dump($result);exit();int(1)
	 		$result1=M('class_expense_record')->add($datar);						//dump($result1);exit();id的值
	 		if($result&&$result1){
	 			$this->success('删除成功');
	 		}
	 		else{
	 			$this->error('删除失败');
	 		}
	 	}
	 	else{
	 		$this->error('ID错误');
	 	}
	 }
	 
	 
	 public function search(){
	 	$type=intval($_POST['type']);
	 	 	
	 	$m_from=$_POST['m_from'];
	 	$m_to=$_POST['m_to'];
	 	$m_span="amount between $m_from and $m_to";
	 	 
	 	$handle=$_POST['handle'];
	 	 
	 	$t_from=strtotime($_POST['t_from']);
	 	$t_to=strtotime($_POST['t_to']);
	 	$t_span="ctime between $t_from and $t_to";
	 	 
	 	$condition='';
	 	if($_POST['type']){
	 		$condition="type= '$type'";
	 	}
	 	//$condition.=" and ";
	 	if($m_from&&$m_to){
	 		$condition.=" and $m_span";
	 	}
	 	if($handle){
	 		$condition.=" and handle='$handle'";
	 	}
	 	if($t_from&&$t_to){
	 		$condition.=" and $t_span";
	 	}
	 	
	 	$condition.=" and isdelete=0";
	 	//dump($condition);exit();
	 	$result=M('class_expense')->where($condition)->select();
	 	//dump($result);exit();
	 	$su['sr']=M('class_expense')->field('sum(amount) as shouru')->where($condition)->select();
	 	$this->assign($su);
	 	dump($su);exit();
	 	$this->assign($result);
	 	
	 	//var_dump($su['sr']);exit();
	 	$this->assign('tfrom',$_POST['t_from']);
	 	$this->assign('tto',$_POST['t_to']);
	 	$this->display('m_money');
	 }

	
	 
	 public function ManageBooks(){
		 $this->display('m_managebooks');
	 }

	 
	 
	 public function ManageGlory(){
		 $this->display('m_manageglory');
	 }
	 
	
	 
	 public function addGlory(){
		 $this->display('m_addglory');
	 }
	 
	 */
}
?>