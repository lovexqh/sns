<?php
/**
 +------------------------------------------------------------------------------
 * 广场教研中心Action
 +------------------------------------------------------------------------------
 * @category	square
 * @package		Lib/Action
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-5-1 上午03:28:41
 +------------------------------------------------------------------------------
 */
class TeachingAction extends BaseAction {
	//用户email
     private $email;
     //应用名称
	 private $appName;
    /**
     +----------------------------------------------------------
     * 初始化
     +----------------------------------------------------------
     * @author	美美2013-5-3
     +----------------------------------------------------------
     * 创建时间：	2013-5-3 上午03:32:28
     +----------------------------------------------------------
     */
    public function _initialize() {
		//应用名称
		parent::_initialize();
		global $ts;
		$this->appName = $ts['app']['app_alias'];
		$this->email = $_SESSION[userInfo][email];
		$this->uid = $_SESSION['mid'];
    }
	 /**
	  +----------------------------------------------------------
	  * 教研中心主页
	  +----------------------------------------------------------
	  * @author	美美2013-5-3
	  +----------------------------------------------------------
	  * 创建时间：	2013-5-3 上午03:32:56
	  +----------------------------------------------------------
	  */
    public function index() {
		$this->setTitle('教研中心');
		//优秀团队
		$data['term'] =	M('')
						->table(C ( 'DB_PREFIX' ) . "teaching as t")
						->join("RIGHT JOIN " . C ( 'DB_PREFIX' ) . "teaching_group as g ON t.groupid = g.id")
						->field('count(t.groupid)as tcount,g.*')
						->group('t.groupid')
						->order('tcount desc')
						->findAll();


		//正在进行的教研
		$data['recommend'] = M('teaching')
							->where("startTime<".time()." and endTime>".time()." and stat = 1")
							->limit(5)
							->order('startTime DESC')
							->findAll();
		//将要开始
		$data['contribute'] =  M("teaching")
							->where("startTime>".time()." and stat = 1")
							->limit(5)
							->order('startTime ASC')
							->findAll();
		//推荐
		$data['tj'] =  M("teaching")
							->limit(5)
							->order('readcount DESC')
							->findAll();
		//小学教研
		$data['xiao'] =  M("teaching")
							->where("phaseid = 21 and stat = 1")
							->limit(5)
							->order('readcount DESC')
							->findAll();
		//初中教研
		$data['chu'] =  M("teaching")
							->where("phaseid = 31  and stat = 1")
							->limit(5)
							->order('readcount DESC')
							->findAll();
		//高中教研
		$data['gao'] =  M("teaching")
							->where("phaseid = 34 and stat = 1")
							->limit(5)
							->order('readcount DESC')
							->findAll();
		//全部教研协作组
		$data['allgroup'] =  M("teaching_group")
							->where("status = 1")
							->limit(5)
							->order('ctime DESC')
							->findAll();	
		//小学教研协作组
		$data['xiaogroup'] =  M("teaching_group")
							->where("phaseid = 21 and status = 1")
							->limit(5)
							->order('ctime DESC')
							->findAll();	

		//初中教研协作组
		$data['chugroup'] =  M("teaching_group")
							->where("phaseid = 31 and status = 1")
							->limit(5)
							->order('ctime DESC')
							->findAll();	
							
		//高中教研协作组
		$data['gaogroup'] =  M("teaching_group")
							->where("phaseid = 34 and status = 1")
							->limit(5)
							->order('ctime DESC')
							->findAll();		

		//文档资源
		$data['docres'] = array();
		$data['picres'] = array();
		$data['videores'] = array();
		$data['zipres'] = array();
		
		$allres =	M('')
						->table(C ( 'DB_PREFIX' ) . "teaching_resource as r")
						->where('r.type = 0')
						->order('r.downcount desc')
						->findAll();
		//dump($allres);
		foreach($allres as $key =>$value){
			
			$restemp =	M('')
					->table(C ( 'DB_PREFIX' ) . "attach as a")
					->where("a.id = '$value[resourceid]'")
					->find();

			//dump($restemp);
			$restemp['downcount']	=	$value['downcount'];
			//获取文档资源
			if($restemp['extension']=='doc' || $restemp['extension']=='xls' || $restemp['extension']=='xlsx' || $restemp['extension']=='docx')	{		
				array_push($data['docres'],$restemp);
			}	
			//dump($data['docres']);	
			//获取图片
			if($restemp['extension']=='png' || $restemp['extension']=='jpg' || $restemp['extension']=='jpeg' || $restemp['extension']=='bmp' || $restemp['extension']=='gif')	{
				array_push($data['picres'],$restemp);
			}		
			//获取压缩文件
			if($restemp['extension']=='zip' || $restemp['extension']=='rar')	{
				array_push($data['zipres'],$restemp);
			}		
			if(count($data['zipres']) == 5 && count($data['picres']) == 5 && count($data['docres']) == 5 ){
				break;
			}
		}
		//dump($data['docres']);	
	
							
		//上传排行
		$data['up'] =  M("teaching_resource")
							->field("*,count(uid) as count")
							->limit(6)
							->group("uid")
							->order('count DESC')
							->findAll();	
		//下载排行
		$data['down'] =  M("teaching_resourcedown")
							->field("*,count(uid) as count")
							->limit(6)
							->group("uid")
							->order('count DESC')
							->findAll();					
		//dump($data['down']);		
		//个人活跃度
		$data['peractive'] =  M('')
							->table(C ( 'DB_PREFIX' ) . "teaching_member as t")
							->join("RIGHT JOIN " . C ( 'DB_PREFIX' ) . "ucenter_user_link as u ON u.uc_uid = t.uc_uid")
							->field('count(t.id)as tcount,u.uid,u.uc_username')
							->where('status = 1')
							->group('t.uc_uid')
							->order('tcount desc')
							->limit(6)
							->findAll();		

		//dump($data['peractive']);					
		//学校活跃度
		$data['schactive'] =  M("teaching")
							->field("*,count(schoolid) as count")
							->limit(6)
							->group("schoolid")
							->order('count DESC')
							->limit(6)
							->findAll();				

		//统计教研数量
		$where = 'endTime > ' . time () . ' and startTime < ' . time ().' and stat = 1';//正在进行
		$where2 = 'startTime > ' . time ().' and stat = 1';//即将开始
		$where3 = 'endTime < ' . time ().' and stat = 1';//已经完成
		$countTeach['on']	=	count(M('teaching')->where($where)->findAll());
		$countTeach['ready']	=	count(M('teaching')->where($where2)->findAll());
		$countTeach['finish']	=	count(M('teaching')->where($where3)->findAll());
		$countTeach['all']	=	$countTeach['on']+$countTeach['ready']+$countTeach['finish'];
		$this->assign('countTeach',$countTeach);

		
		//教研分类树
		$treeNode = $this->getTree('treeNode');
		$this->assign('treeNode',$treeNode);
		//教研分类另一棵树,没有学校

		$treeNoSchool = $this->getTree('noschool');
		$this->assign('treeNoSchool',$treeNoSchool);
		
		//获取教研类型
		$data['types'] = arrayKeyToLower(M('category_dictionary')->where("DataType	=	'teachingtype'")->select());
        
		
		$this->assign(arrayKeyTolower($data));
		$this->display();
    }
    function getTree($obj){
    	if($obj=='treeNode'){
			$schoolid	=	$_SESSION['ucInfo']['schoolid'];
			$treeNode = get_organization_by_schoolid ('1');
			return  $treeNode;    		
    	}
 		//教研分类树
        if($obj=='noschool'){
			//教研分类另一棵树,没有学校	
			$treeNoSchool = getXdSubjectGrade();
			return $treeNoSchool;     		
    	}
 	
    }

	/**
	 +----------------------------------------------------------
	 * 搜索功能
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-6-8
	 +----------------------------------------------------------
	 */    
	public function search() {
		if(empty($_POST['category'])){
			$category	=	'teaching';
		} else {
			$category	=	$_POST['category'];
		}
		
		$keyword = text($_POST['keyword']);	
		//$keyword	=	str_replace('  ',' ',$keyword);	
		$allKey	=	explode(' ',$keyword);

		$dic	=	getAllCourseXd();
		foreach($allKey as $key	=> $value){
			if($value!=''){
					switch ($value){
					case '小学':
						$where['phaseid']	=	'21';
						unset($allKey[$key]);
						break;
					case '初中':
						$where['phaseid']	=	'31';
						unset($allKey[$key]);
						break;
					case '高中':		
						$where['phaseid']	=	'34';
						unset($allKey[$key]);
						break;		
					default:
						$a	=	1;
				}				
			}

		}
		foreach($allKey as $key => $value){
			if($value!=''){
				foreach($dic as $v){
					if($v['kmmc']==$value){
						$where['courseid']	=	$v['kmbm'];
						unset($allKey[$key]);				
					}

					if($v['xxmc']==$value){
						$where['schoolid']	=	$v['xxid'];		
						unset($allKey[$key]);				
					}

				}			
			}

		}

		$newarr	=	array();
		foreach($allKey as $value){
			if($value!=''){
				$newarr[]	=	$value;
			}
		}

		$data['teachList']=array();
		$data['groupList']=array();
		$data['sourceList']=array();
		if(count($where)>0){
			Switch ($category){
				case 'teaching':
					//检索符合条件的教研
					$temp = M('')
								->table(C ( 'DB_PREFIX' ) . "teaching as t")
								->field('t.*')
								->where($where)
								->order('t.meetingId desc')
								->findAll();
					//echo M()->getLastSql();
					foreach($temp as $key =>$value){
						foreach($newarr as $v){
							if(strpos($value['meetingName'],$v)!==false){
								array_push($data['teachList'],$value);
							}
						}
					}
					break;
				case 'group':
					//检索符合条件的教研组
					$temp = M('')
								->table(C ( 'DB_PREFIX' ) . "teaching_group as g")
								->field('g.*')
								->where($where)
								->order('g.id desc')
								->findAll();
					//echo M()->getLastSql();
					
					foreach($temp as $key =>$value){
						foreach($newarr as $v){
							if(strpos($value['name'],$v)!==false){
								array_push($data['groupList'],$value);
							}
						}
						//dump($data['groupList']);					
					}
					break;
				case 'resource':
					//检索符合条件的教研组
					$temp = M('')
								->table(C ( 'DB_PREFIX' ) . "teaching_resource as r")
								->join("RIGHT JOIN " . C ( 'DB_PREFIX' ) . "attach as a ON r.resourceid = a.id")
								->field('r.*,a.*')
								->where($where)
								->order('a.id desc')
								->findAll();
					//echo M()->getLastSql();
					foreach($temp as $key =>$value){
						foreach($newarr as $v){
							if(strpos($value['name'],$v)===false){
								array_push($data['sourceList'],$value);
							}
						}
					}
					break;
			}			
		}else{

			Switch ($category){
				case 'teaching':
					foreach($newarr	as $v){
						//检索符合条件的教研
						$result[$v] = M('')
									->table(C ( 'DB_PREFIX' ) . "teaching as t")
									->field('t.*')
									->where("t.meetingName like '%".$v."%'")
									->order('t.meetingId desc')
									->findAll();
						//echo M()->getLastSql();
						//遍历数组,把数组的每一个元素重新push进新数组.
						foreach($result[$v] as $rv){
							array_push($data['teachList'],$rv);
						}					
					}
					break;
				case 'group':
					foreach($newarr	as $v){
						//检索符合条件的教研组
						$result[$v] = M('')
									->table(C ( 'DB_PREFIX' ) . "teaching_group as g")
									->field('g.*')
									->where("g.name like '%".$v."%'")
									->order('g.id desc')
									->findAll();
						//echo M()->getLastSql();
						//遍历数组,把数组的每一个元素重新push进新数组.
						foreach($result[$v] as $rv){
							array_push($data['groupList'],$rv);
						}					
					}
					break;
				case 'resource':
					foreach($newarr	as $v){
						//检索符合条件的资源
						$result[$v] = M('')
									->table(C ( 'DB_PREFIX' ) . "teaching_resource as r")
									->join("RIGHT JOIN " . C ( 'DB_PREFIX' ) . "attach as a ON r.resourceid = a.id")
									->field('r.*,a.*')
									->where("a.name like '%".$v."%'")
									->order('a.id desc')
									->findAll();
						//echo M()->getLastSql();
						//遍历数组,把数组的每一个元素重新push进新数组.
						foreach($result[$v] as $rv){
							array_push($data['sourceList'],$rv);
						}					
					}
					break;
			}
		}

		$this->assign($data);
		//dump($data);
		//$this->display('test');exit;			
	  	//dump($List);
		//教研分类树
		$treeNode = $this->getTree('treeNode');
		$this->assign('treeNode',$treeNode);
		//教研分类另一棵树,没有学校

		$treeNoSchool = $this->getTree('noschool');
		$this->assign('treeNoSchool',$treeNoSchool);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 展示页面
	 +----------------------------------------------------------
	 * @author	美美2013-5-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-1 上午03:34:43
	 +----------------------------------------------------------
	 */
	public function show(){
	    $id = intval($_REQUEST['meetingId']);

	    $teaching = M ( 'teaching' )->where ( "meetingId = '$id'" )->find ();
	    //相关教研
	    $where['schoolid']	=	$teaching['schoolid'];
	    $where['phaseid']	=	$teaching['phaseid'];
	    $where['gradeid']	=	$teaching['gradeid'];
	    $where['courseid']	=	$teaching['courseid'];
	    $related	=	M('teaching')
	    				->where($where)
	    				->findAll();
	    $this->assign('related',$related);
	    
	    if(!empty($teaching)){
	    	$count['readcount'] = $teaching['readcount']+1;
	    	M('teaching')->where( "meetingid = '$id'" )->save($count);
	    }
		$teaching = arrayKeyTolower ( $teaching );
			// 更新学段字段为中文值
		if ($teaching ['phaseid'] == '21') {
			$teaching ['phasename'] = '小学';
		}
		if ($teaching ['phaseid'] == '31') {
			$teaching ['phasename'] = '初中';
		}
		if ($teaching ['phaseid'] == '34') {
			$teaching ['phasename'] = '高中';
		}
		// 获取已选择加入的人
		$members ['allmember'] = M ( 'teaching_member' )->where ( "meetingid = '$id'" )->findAll ();
		foreach ( $members ['allmember'] as $member ) {
			$member = arrayKeyTolower ( $member );
			if ($member ['identitytype'] == 2) { // 老师角色
				$members ['allteacher'] [] = $member; // 需参加人员
				if ($member ['status'] == 1) {
					$members ['jointeacher'] [] = $member; // 已参加人员
				}
			}
			if ($member ['identitytype'] == 3) { // 学生角色
				$members ['allstudent'] [] = $member; // 需参加人员
				if ($member ['status'] == 1) {
					$members ['joinstudent'] [] = $member; // 已参加人员
				}
			}
			if ($member ['identitytype'] == 4) { // 家长角色
				$members ['allparent'] [] = $member; // 需参加人员
				if ($member ['status'] == 1) {
					$members ['joinparent'] [] = $member; // 已参加人员
				}
			}
		}
		
		// 相关资料获取
		$sql = "SELECT a.* FROM " . C ( 'DB_PREFIX' ) . "teaching_resource r LEFT JOIN " . C ( 'DB_PREFIX' ) . "attach a ON r.resourceid = a.id WHERE r.meetingid = '$id'";
		$resourceList ['resources'] = M ( 'teaching_resource' )->query ( $sql );
		
		//获取视频相关信息
		$teaching['video']  = arrayKeyTolower(M('')
		->table(C ( 'DB_PREFIX' ) . "video as v")
		->join("RIGHT JOIN " . C ( 'DB_PREFIX' ) . "teaching_resource as t ON v.fcode = t.resourceid")
		->field('v.*')
		->where("t.meetingid = '$id'")
		->order('v.id desc')
		->findAll());

		$teaching = array_merge ( $teaching, $members, $resourceList );
		
		$this->assign('teaching',$teaching );
	  	$this->display('detail');	
	}
	/**
	 +----------------------------------------------------------
	 * 资源详细信息查看页
	 +----------------------------------------------------------
	 * @author	美美2013-5-21
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-21 上午06:12:52
	 +----------------------------------------------------------
	 */
	public function showresource(){
		$this->display('showresource');
	}
	/**
	 +----------------------------------------------------------
	 * 视频显示跳转页面
	 +----------------------------------------------------------
	 * @param	id 视频id
	 * @return	视频信息
	 * @author	美美2013-5-16
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-16 上午05:27:36
	 +----------------------------------------------------------
	 */
	public function videoshow(){
		$id = $_GET[id];
		$data = M('video')->where('id='.$id)->find();
		$this->assign($data);
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 资源显示跳转页面
	 +----------------------------------------------------------
	 * @param	id附件id
	 * @return	附件具体信息
	 * @author	美美2013-5-16
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-16 上午05:50:16
	 +----------------------------------------------------------
	 */
	public function resshow(){
		$id = $_GET[id];
		$data = M('attach')->where('id='.$id)->find();
		$this->assign($data);
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * ajax异步显示组织结构
	 +----------------------------------------------------------
	 * @return	Array 搜索结果
	 * @author	美美2013-5-14
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-14 上午03:37:03
	 +----------------------------------------------------------
	 */
	public function dosearch() {
		$pId = "0";
		$pName = "";
		$pLevel = "";
		$pCheck = "";
		if(array_key_exists( 'id',$_REQUEST)) {
			$pId=$_REQUEST['id'];
		}
		if(array_key_exists( 'lv',$_REQUEST)) {
			$pLevel=$_REQUEST['lv'];
		}
		if(array_key_exists('n',$_REQUEST)) {
			$pName=$_REQUEST['n'];
		}
		if(array_key_exists('chk',$_REQUEST)) {
			$pCheck=$_REQUEST['chk'];
		}
		if ($pId==null || $pId=="") $pId = "0";
		if ($pLevel==null || $pLevel=="") $pLevel = "0";
		if ($pName==null) $pName = "";
		else $pName = $pName.".";
		
		for ($i=1; $i<5; $i++) {
			$nId = $pId.$i;
			$nName = $pName."n".$i;
			echo "{ id:'".$nId."',	name:'".$nName."',	isParent:".(( $pLevel < "2" && ($i%2)!=0)?"true":"false").($pCheck==""?"":((($pLevel < "2" && ($i%2)!=0)?", halfCheck:true":"").($i==3?", checked:true":"")))."}";
			if ($i<4) {
				echo ",";
			}
		}
    }
	/**
	 +----------------------------------------------------------
	 * 排行榜页面
	 +----------------------------------------------------------
	 * @author	美美2013-5-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-1 上午04:37:03
	 +----------------------------------------------------------
	 */
	public function charts(){
		$this->setTitle('排行榜');
    	//获取来源
    	
    	if(empty($_GET['from'])){
    		$from	=	'1';
    	}else{
    		$from	=	$_REQUEST['from'];
    	};
    	$this->assign('from',$from);
			
    	//增加判断条件
    	if(!empty($_GET['phaseid']) ){
    		$phaseid	=	" and phaseid = ".$_GET['phaseid'];
    		$where['phaseid']=$_GET['phaseid'];
    	}else {
    		$phaseid = '';
    	}
		if(!empty($_GET['schoolid'])){
			$schoolid	=	' and schoolid = '.$_GET['schoolid'];
			$where['schoolid']	=	$_GET['schoolid'];
		}else {
			$schoolid = '';
		}
		if(!empty($_GET['courseid'])){
			$courseid	=	' and courseid = '.$_GET['courseid'];
			$where['courseid']	=	$_GET['courseid'];
		}else 	{
			$courseid	= '';
		}
		if(!empty($_GET['gradeid'])){			
			$gradeid	=	' and gradeid = '.$_GET['gradeid'];
			$where['gradeid']	=	$_GET['gradeid'];
		}else {
			$gradeid	=	'';
		}
		//dump($where);
		if($from=='1'){
			//个人活跃度
			if(empty($_GET['schoolid']) && empty($_GET['phaseid']) && empty($_GET['courseid']) && empty($_GET['gradeid'])){
				$person	=	get_teacher_by_grade();
			}else{
				$person	=	get_teacher_by_grade($_GET['schoolid'],$_GET['phaseid'],$_GET['courseid'],$_GET['gradeid']);
			}
			foreach($person as $key =>$value){
				//获取老师的社区UID
				$uid	=	M('')
						->table(C('DB_PREFIX')."ucenter_user_link")
						->where("uc_uid = '$value[uc_uid]'")
						->find();
				$person[$key]['uid']	=	$uid['uid'];
				//上传资源数
				$upcount =  M("teaching_resource")
						->field("count(id) as upcount")
						->where("uid = '$uid[uid]'")
						->group("uid")
						->find();	
				if($upcount['upcount']==null) $upcount['upcount'] = 0;				
				$person[$key]['upcount']	=	$upcount['upcount'];
				//下载资源数
				$dcount =  M("teaching_resourcedown")
						->field("count(id) as dcount")
						->where("uid = '$uid[uid]'")
						->group("uid")
						->find();					
				if($dcount['dcount']==null) $dcount['dcount'] = 0;
				$person[$key]['dcount']	=	$dcount['dcount'];
				//创建教研数
				$cteachnum =  M("teaching")
						->field("count(meetingId) as cteachnum")
						->where("uid = '$uid[uid]'")
						->group("uid")
						->find();		
				if($cteachnum['cteachnum']==null) $cteachnum['cteachnum'] = 0;			
				$person[$key]['cteachnum']	=	$cteachnum['cteachnum'];
				
				//应参加教研数
				$joinnum =  M("teaching_member")
						->field("count(id) as joinnum")
						->where("uc_uid = '$value[uc_uid]'")
						->group("uc_uid")
						->find();		
				if($joinnum['joinnum']==null) $joinnum['joinnum'] = 0;			
				$person[$key]['joinnum']	=	$joinnum['joinnum'];
				
				//实际参加教研数
				$sjoinnum =  M("teaching_member")
						->field("count(id) as sjoinnum")
						->where("uc_uid = '$value[uc_uid]' and status = 1")
						->group("uc_uid")
						->find();		
				if($sjoinnum['sjoinnum']==null) $sjoinnum['sjoinnum'] = 0;			
				$person[$key]['sjoinnum']	=	(int)$sjoinnum['sjoinnum'];
				
			}
	
		//冒泡排序
		$len = count($person);
		for($i=1;$i<=$len;$i++){
			for($j=$len-1;$j>=$i;$j--){
				if($person[$j]['sjoinnum']>$person[$j-1]['sjoinnum']){
					$temp	=	$person[$j-1];
					$person[$j-1] = $person[$j];
					$person[$j] = $temp;
				}
			}
		}
		//dump($person);
		$this->assign('person',$person);
		}else if($from=='2'){			
			//优秀团队
			$terms =	M('')
					->table(C ( 'DB_PREFIX' ) . "teaching_group")
					->where("status = 1 ".$phaseid.$schoolid.$courseid.$gradeid)
					->field('*')
					->order('id desc')
					->findAll();
			//dump($terms);
			foreach($terms as $key=>$arr){
				//获取成员数
				
				$num	= M('')
						->table(C ( 'DB_PREFIX' ) . "teaching_group_member")
						->where("gid = '$arr[id]' ")
						->field('count(uid) as num')
						->group('gid')
						->find();	
				if($num['num']==null) 
					$num['num'] = '0';
				$terms[$key]['num'] = $num['num'];
				//echo M('')->getLastSql();
				//获取教研数
				$tcount = M('')
						->table(C ( 'DB_PREFIX' ) . "teaching")
						->where("groupid = '$arr[id]' ")
						->field('count(meetingId) as tcount')
						->group('groupid')
						->find();	
				if($tcount['tcount']==null) 
					$tcount['tcount'] = '0';
				$terms[$key]['tcount'] = $tcount['tcount'];
			}
			//dump($terms);
			$this->assign('terms',$terms);
			
		}else if($from=='3'){			
			//活跃学校
			$schools =	M('')
					->table(C ( 'DB_PREFIX' ) . "teaching")
					->where("stat = 1 ".$phaseid.$schoolid.$courseid.$gradeid)
					->field('count(meetingId) as tcount,schoolid')
					->group('schoolid')
					->order('tcount desc')
					->findAll();
			//dump($schools);	
			foreach($schools as $key=>$arr){
				$mids =	M('')
						->table(C ( 'DB_PREFIX' ) . "teaching as t")
						->where("t.schoolid = '$arr[schoolid]' ")
						->field('meetingId,schoolid')
						->findAll();
				//dump($mids);
				foreach($mids as $k => $brr){
					$temp =	M('')
							->table(C ( 'DB_PREFIX' ) . "teaching_resource as r")
							->where("r.meetingid = '$brr[meetingId]' ")
							->field('count(id) as rcount')
							->group('r.meetingid')
							->find();
					
					if($temp['rcount']==null) 
						$temp['rcount'] = 0;
					$rcount += $temp['rcount'];
				}
				$schools[$key]['rcount'] = $rcount;
				unset($rcount);
				//dump($schools);
			}	
			//dump($schools);	
			$this->assign('schools',$schools);

		}else if($from=='4'){
			//教研活动
			$teachings =	M('')
					->table(C ( 'DB_PREFIX' ) . "teaching")
					->where("stat = 1 ".$phaseid.$schoolid.$courseid.$gradeid)
					->field('*')
					->order('meetingId desc')
					->findAll();
			//dump($teachings);
			foreach($teachings as $key=> $arr){
				//获取资源数
				$rcount =	M('')
							->table(C ( 'DB_PREFIX' ) . "teaching_resource")
							->where("meetingid = '$arr[meetingId]'")
							->field('count(meetingid) as rcount')
							->group('meetingid')
							->find();
				if($rcount['rcount']==null) 
						$rcount['rcount'] = 0;
				$teachings[$key]['rcount']	=	$rcount['rcount'];
				//获取应参加人数
				$jcount =	M('')
							->table(C ( 'DB_PREFIX' ) . "teaching_member")
							->where("meetingid = '$arr[meetingId]'")
							->field('count(meetingid) as jcount')
							->group('meetingid')
							->find();
				if($jcount['jcount']==null) 
						$jcount['jcount'] = 0;
				$teachings[$key]['join'] = $jcount['jcount'];
				//获取实参加人数
				$sjcount =	M('')
							->table(C ( 'DB_PREFIX' ) . "teaching_member")
							->where("meetingid = '$arr[meetingId]' and status = 1")
							->field('count(meetingid) as sjcount')
							->group('meetingid')
							->find();
				if($sjcount['sjcount']==null) 
						$sjcount['sjcount'] = 0;
				$teachings[$key]['sjoin'] = $sjcount['sjcount'];			
			}
			//dump($teachings);
			$this->assign('teachings',$teachings);
			
		}

		//教研分类树
		$treeNode = $this->getTree('treeNode');
		$this->assign('treeNode',$treeNode);
		//教研分类另一棵树,没有学校
		$treeNoSchool = $this->getTree('noschool');
		$this->assign('treeNoSchool',$treeNoSchool);
		
		$this->display('charts');
	}

    public function teachList(){
    	//增加判断条件
    	if(!empty($_GET['phaseid']) )
    		$where['phaseid']=$_GET['phaseid'];
		if(!empty($_GET['schoolid']))
			$where['schoolid']	=	$_GET['schoolid'];
		if(!empty($_GET['courseid']))
			$where['courseid']	=	$_GET['courseid'];
		if(!empty($_GET['gradeid']))			
			$where['gradeid']	=	$_GET['gradeid'];

		//根据教研类型提交
		if(!empty($_GET['teachingtype']))
			$where['teachingtype']	=	$_GET['teachingtype'];
		
		
		//查询数据库
		$data['all'] = M('teaching')
							->where($where)
							->order('startTime DESC')
							->findPage(10);
		//dump(M()->getLastSql());
		//获取教研类型
		$data['types'] = arrayKeyToLower(M('category_dictionary')->where("DataType	=	'teachingtype'")->select());

		//教研分类树
		$treeNode = $this->getTree('treeNode');
		$this->assign('treeNode',$treeNode);
		//教研分类另一棵树,没有学校
		$treeNoSchool = $this->getTree('noschool');
		$this->assign('treeNoSchool',$treeNoSchool);
		
		$this->assign($data);
    	$this->display();
    }
    public function resList(){
    	//增加判断条件
    	if(!empty($_GET['phaseid']) )
    		$phaseid	=	" and t.phaseid = ".$_GET['phaseid'];
    	else 
    		$phaseid = '';
		if(!empty($_GET['schoolid']))
			$schoolid	=	' and t.schoolid = '.$_GET['schoolid'];
		else 
			$schoolid = '';
		if(!empty($_GET['courseid']))
			$courseid	=	' and t.courseid = '.$_GET['courseid'];
		else 	
			$courseid	= '';
		if(!empty($_GET['gradeid']))			
			$gradeid	=	' and t.gradeid = '.$_GET['gradeid'];
		else 
			$gradeid	=	'';
    	
    	//获取教研资源
		$data['res'] =	M('')
						->table(C ( 'DB_PREFIX' ) . "teaching_resource as r")
						->join("RIGHT JOIN " . C ( 'DB_PREFIX' ) . "attach as a ON a.id = r.resourceid")
						->join("RIGHT JOIN " . C ( 'DB_PREFIX' ) . "teaching as t ON t.meetingId = r.meetingid")
						->where('r.type = 0 '.$phaseid.$schoolid.$courseid.$gradeid)
						->field('a.*,t.*,r.downcount')
						->order('r.addtime desc')
						->findPage(10);
		//dump(M()->getLastSql());				
		//dump($data);
		//教研分类树
		$treeNode = $this->getTree('treeNode');
		$this->assign('treeNode',$treeNode);
		//教研分类另一棵树,没有学校
		$treeNoSchool = $this->getTree('noschool');
		$this->assign('treeNoSchool',$treeNoSchool);
		
		$this->assign($data);
    	$this->display();
    }
    public function groupList(){
    	
    	//获取来源
    	
    	if(empty($_GET['from'])){
    		$from	=	'2';
    	}else{
    		$from	=	$_REQUEST['from'];
    	};
    	//增加判断条件
    	if(!empty($_GET['phaseid']) )
    		$where['phaseid']=$_GET['phaseid'];
		if(!empty($_GET['schoolid']))
			$where['schoolid']	=	$_GET['schoolid'];
		if(!empty($_GET['courseid']))
			$where['courseid']	=	$_GET['courseid'];
		if(!empty($_GET['gradeid']))			
			$where['gradeid']	=	$_GET['gradeid'];
		if(empty($_GET['schoolid'])) 
			$schoolid	=	'';
		else 
			$schoolid	=	$_GET['schoolid'];
		if(empty($_GET['phaseid'])) 
			$phaseid	=	'';	
		else 
			$phaseid	=	$_GET['phaseid'];
		
		//如果来源为1.则是备课教研组.
		if($from=='1'){
			$data['group']['data']	=	getBKgroupBySchoolidXd($schoolid,$phaseid);
			//$data['xzgroup']	=	getBKgroupBySchoolidXd();
		}
    	//如果来源为2.则是协作组.
		if($from=='2'){
			$where['status']=1;
     		//获取教研协作组
			$data['group'] = M('teaching_group')
							->where($where)
							->order('ctime desc')
							->findPage(10);   			
		}
        //如果来源为3.则是教研组.
		if($from=='3'){
			$data['group']['data']	=	getXZgroup($schoolid,$phaseid);	
		}
		$this->assign('from',$from);
		//教研分类树
		$treeNode = $this->getTree('treeNode');
		$this->assign('treeNode',$treeNode);
		//教研分类另一棵树,没有学校
		$treeNoSchool = $this->getTree('noschool');
		$this->assign('treeNoSchool',$treeNoSchool);

		$this->assign($data);
		//$this->assign('group',$data['group']);
    	$this->display();
    }
    public function detail(){
    	$this->display();
    }
    public function test(){
    	//$treeNoSchool = $this->getTree('noschool');
    	//dump($treeNoSchool);
    	//$treeNode = $this->getTree('treeNode');
    	//dump($treeNode);
    	//dump($_SERVER);
    	//$arr	=	explode('&teach',$_SERVER['QUERY_STRING']);
    	//$data	=	explode('&teachingtype',$_SERVER["QUERY_STRING"]);
    	//echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    	//echo $_SERVER["QUERY_STRING"];
    	//echo $_GET['teachType'];
    	//dump(getXZgroup('',''));
    	//$arr	=	getXZgroup('','');
    	//$a=array("a"=>"Dog1","b"=>"Cat1");
    	//$b=array("a"=>"Dog2","b"=>"Cat2");
		//array_push($b,$a);
		//print_r($b);
		//dump($arr);
		$person = get_teacher_by_grade();
    	//冒泡排序
		$len = count($person);

		for($i=1;$i<=$len;$i++){
			for($j=$len-1;$j>=$i;$j--){
				if($person[$j]['identityid']>$person[$j-1]['identityid']){
					$temp	=	$person[$j-1];
					$person[$j-1] = $person[$j];
					$person[$j] = $temp;
					
				}
			}
		}
		dump($person);
    	
    }
}
?>
