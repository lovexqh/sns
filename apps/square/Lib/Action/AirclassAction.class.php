<?php
/**
 +------------------------------------------------------------------------------
 * 广场课堂纪实Action
 +------------------------------------------------------------------------------
 * @category	square
 * @package		Lib/Action
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-5-13 上午03:28:41
 +------------------------------------------------------------------------------
 */

class AirclassAction extends BaseAction {
	// 用户email
	private $email;
	// 应用名称
	private $appName;
	
	/**
	 * +----------------------------------------------------------
	 * 初始化
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-5-13
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-13 上午03:32:28
	 *         +----------------------------------------------------------
	 */
	public function _initialize() {
		// 应用名称
		parent::_initialize ();
		global $ts;
		$this->appName = $ts ['app'] ['app_alias'];
		$this->email = $_SESSION [userInfo] [email];
		$this->uid = $_SESSION ['mid'];

		//左侧树
		//$data=model('Knowledge')->getTreeArray();
		$data=model('Knowledge')->getTree(1);
		$this->assign('treenode',$data);

		//获取分类信息
		$category=M('select_data')->where("`appname`='airclass' and `type`='category' and `status` = 1")->findAll();
		$this->assign('categoryList',$category);
	}
	
	/**
	 * +----------------------------------------------------------
	 * 课堂纪实主页
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-5-13
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-13 上午03:32:56
	 *         +----------------------------------------------------------
	 */
	public function index() {
		//检索所有课堂结束时间晚于现在并且开始时间早于现在的课堂(正进行)
		$where = "stime<=".time()." and etime>".time()."";
		$onList = D('airclass','airclass')->lists($where, 5);
		$this->assign ( 'onList', $onList);
		$onClassCount = D('airclass','airclass')->listsCount($where);
		$this->assign ( 'onClassCount', $onClassCount);
		
		//检索所有开始时间晚于现在的课堂(未开始)
		$where = "stime>".time();
		$readyList = D('airclass','airclass')->lists($where, 5);
		$this->assign('readyList',$readyList);
		$readyClassCount = D('airclass','airclass')->listsCount($where);
		$this->assign ( 'readyClassCount', $readyClassCount);
		
		//检索所有课堂结束时间早于现在的课堂(已完成)
		$where = "etime<=".time();
		$finishedList = D('airclass','airclass')->lists($where, 5);
		$this->assign('finishedList',$finishedList);
		$finishedClassCount = D('airclass','airclass')->listsCount($where);
		$this->assign ( 'finishedClassCount', $finishedClassCount);

		$categorys=M('select_data')->where("`appname`='airclass' and `type`='category' and `status` = 1")->limit(3)->findAll();
		$classList = array();
		foreach($categorys as $key=>$value) {
			$classList[$key]['id'] = $value['id'];
			$classList[$key]['title'] = $value['title'];
			//随堂纪实
			$where = "category=".$value['id'];
			$classList[$key]['class'] = D('airclass','airclass')->lists($where, 5);
			
			$where = "category=".$value['id']." and xueduan=1";
			$classList[$key]['class_1'] = D('airclass','airclass')->lists($where, 5);
			
			$where = "category=".$value['id']." and xueduan=2";
			$classList[$key]['class_2'] = D('airclass','airclass')->lists($where, 5);
			
			$where = "category=".$value['id']." and xueduan=3";
			$classList[$key]['class_3'] = D('airclass','airclass')->lists($where, 5);
		}
		$this->assign ( 'classList', $classList);
		
		$resourceList['resources_hot'] = M('')
		->table(C ( 'DB_PREFIX' ) . "airclass_resource as r")
		->join("RIGHT JOIN " . C ( 'DB_PREFIX' ) . "attach as a ON r.resourceid = a.id")
		->field('a.id,a.name,ifnull(r.downcount,0) as downcount')
		->order('r.downcount desc')
		->limit(10)
		->findAll();
		$this->assign ( 'resources_hot', $resourceList['resources_hot']);
		
		$classes_hot = D('airclass','airclass')->lists('', 10, 'c.viewcount desc,c.classid desc');
		$this->assign ( 'classes_hot', $classes_hot);

		// 活跃人物
		$data ['teacher'] = M ( 'airclass' )
		->field ( 'uid,count(*) as count' )
		->group ( 'uid' )
		->order ( 'count DESC' )
		->limit ( 10 )
		->findAll ();
		// 活跃学校
		$data ['school'] = M ( 'airclass' )
		->field ( 'schoolid,count(*) as count' )
		->group ( 'schoolid' )
		->order ( 'count DESC' )
		->limit ( 10 )
		->findAll ();
		foreach($data ['school'] as $key=>$value) {
			$schoolInfo = empty($value['schoolid']) ? array() : get_school_by_id($value['schoolid']);
			$data ['school'][$key]['schoolName'] = $schoolInfo ? $schoolInfo[0]['xxmc'] : '未知';
		}

		$this->assign ( arrayKeyTolower ( $data ) );
		$this->display ();
	}
	
	public function lists() {
		$option['section']=$_REQUEST['section'];
		$option['Grade']=$_REQUEST['Grade'];
		$option['Subject']=$_REQUEST['Subject'];
		$option['Publisher']=$_REQUEST['Publisher'];
		$option['Volume']=$_REQUEST['Volume'];
		$option['Cell']=$_REQUEST['Cell'];
		$type='Publisher';
		$Volume=model('Knowledge')->getTreeNode($type,$option['section'],$option);
		$type='Volume';
		foreach($Volume as $K=>$V){
			$option['Volume']=$V['id'];
			$Volume[$K]['Cell']=model('Knowledge')->getTreeNode($type,$option['section'],$option);
		}
		$this->assign('VolumeList',$Volume);
		$this->assign($option);
		
		if($_REQUEST['section']) $where['xueduan']=$_REQUEST['section'];
		if($_REQUEST['Grade']) $where['grade']=$_REQUEST['Grade'];
		if($_REQUEST['Subject']) $where['course']=$_REQUEST['Subject'];
		if($_REQUEST['Publisher']) $where['edition']=$_REQUEST['Publisher'];
		if($_REQUEST['Volume']) $where['fence']=$_REQUEST['Volume'];
		if($_REQUEST['Cell']) $where['part']=$_REQUEST['Cell'];
		$classes = M('')
		->table(C ( 'DB_PREFIX' ) . "v_airclass as c")
		->field('c.*')
		->where($where)
		->order('c.classid desc')
		->findPage(10);
		$this->assign ( 'classes', $classes);
		$classCount = M('')
		->table(C ( 'DB_PREFIX' ) . "airclass as c")
		->where($where)
		->getField('count(*)');
		$this->assign ( 'classCount', $classCount);
		
		$this->display ();
	}
	
	public function detail() {
		$classId	=	$_GET['classId'];
		$class	=	M('airclass')->where("`classid`= '".$classId."'")->find();
		$this->assign("classinfo",$class);
		
		// 获取已选择加入的人
		$members ['allmember'] = M ( 'airclass_member m' )
		->field("m.*,l.uid")
		->join("LEFT JOIN ".C ( 'DB_PREFIX' )."ucenter_user_link as l on l.uc_uid=m.uc_uid")
		->where ( "m.classid = '$classId'" )
		->findAll ();
		
		foreach ( $members ['allmember'] as $member ) {
			$member = arrayKeyTolower ( $member );
			$member['signStatus'] = $member['sign'] == 1 ? "已签收" : "未签收";
			$member['regStatus'] = !empty($member['uid']) ? "已注册" : "未注册";
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
		$this->assign('members',$members);
		// 相关资料获取
		$sql = "SELECT a.* FROM " . C ( 'DB_PREFIX' ) . "airclass_resource r LEFT JOIN " . C ( 'DB_PREFIX' ) . "attach a ON r.resourceid = a.id WHERE r.classid = '$classId'";
		$data['resources'] = M ( 'airclass_resource' )->query ( $sql );
		
		//dump($teachers);
		$data['parents'] = $parents;
		$data['teachers'] = $teachers;
		$data['students'] = $students;
		$data['realparents'] = $realParents;
		$data['realteachers'] = $realTeachers;
		$data['realstudents'] = $realStudents;
		$this->assign($data);
		
		$onList = M('')
		->table(C ( 'DB_PREFIX' ) . "airclass as c")
		->field('c.*')
		->where("stime<=".time()." and etime>".time()."")
		->order('c.classid desc')
		->limit(5)
		->findAll();
		$this->assign ( 'onList', $onList);
		
		$this->display ();
	}
	
	public function classes() {
		if($_REQUEST['category']) $where['category']=$_REQUEST['category'];
		$this->assign ( 'category', $where['category']);
		
		$classes = M('')
		->table(C ( 'DB_PREFIX' ) . "v_airclass as c")
		->field('c.*')
		->where($where)
		->order('c.classid desc')
		->findPage(10);
		$this->assign ( 'classes', $classes);
		
		$this->display ();
	}
	
	public function resource() {
		$resourceList['resourceList'] = M('')
		->table(C ( 'DB_PREFIX' ) . "airclass_resource as r")
		->join("JOIN " . C ( 'DB_PREFIX' ) . "attach as a ON r.resourceid = a.id")
		->join("JOIN " . C ( 'DB_PREFIX' ) . "airclass as c ON c.classid = r.classid")
		->field('a.id,a.name,a.size,r.uid,r.downcount,r.addtime,r.classid,c.name as classname')
		->where($map)
		->order('r.downcount desc')
		->findPage(10);
		
		$this->assign($resourceList);
		
		$this->display ();
	}
	
	public function toplist() {
		$resourceList['classes_hot'] = M('')
		->table(C ( 'DB_PREFIX' ) . "v_airclass as c")
		->field('c.*')
		->order('c.viewcount desc,c.classid desc')
		->findPage(10);
		$this->assign ( 'classes_hot', $resourceList['classes_hot']);
		
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 课堂活动列表页
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-5-7
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-7 上午03:33:27
	 *         +----------------------------------------------------------
	 */
	public function getList() {
		$id = intval ( $_REQUEST ['id'] );
		if (empty ( $id )) {
			$id = 5;
		}
		$this->setTitle ( '课堂活动' );
		$data ['leftcate'] = procExecute ( "call vcCategory(" . $id . ");" );
		$data ['type'] = M ( 'select_data' )->where ( "appname='airclass' and type='category'" )->findAll ();
		$data ['context'] = M ( '' )->table ( "" . C ( 'DB_PREFIX' ) . "airclass r," . C ( 'DB_PREFIX' ) . "square_airclass f" )->where ( "f.airclassid=r.classid" )->order ( 'stime DESC' )->findpage ( 10 );
		foreach ( $data ['type'] as $type ) {
			$data ['category'] [$type ['id']] = M ( '' )->table ( "" . C ( 'DB_PREFIX' ) . "airclass r," . C ( 'DB_PREFIX' ) . "square_airclass f" )->where ( "r.category=" . $type ['id'] . " and f.airclassid=r.classid" )->order ( 'stime DESC' )->findpage ( 10 );
		}
		$data ['column'] = '课堂活动';
		$data ['path'] = procExecute ( "call vcPath(" . $id . ");" );
		$this->assign ( arrayKeyTolower ( $data ) );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 课堂纪实列表页对应搜索页
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-5-7
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-7 上午03:33:27
	 *         +----------------------------------------------------------
	 */
	public function search() {
		$category=$_REQUEST['category'];
		$keyword=$_REQUEST['keyword'];
		$this->assign ( 'keyword', $keyword);
		if($keyword=='请输入您要搜索的课堂关键词，例小学语文') $keyword='';
		if($keyword) {
			$arr = explode(" ", $keyword);
			$keywords = "";
			foreach ($arr as $v) {
				if(!empty($v)) {
					$keywords .= " or val like '%".$v."%'";
				}
			}
			
			if(!empty($keywords)) $where = " and (".substr($keywords, 4).")";
		}
		if($category) $where .= " and category='".$category."'";
		$where = substr($where, 5);
		
		$classes_all = M('')
		->table(C ( 'DB_PREFIX' ) . "v_airclass as c")
		->field("c.*,CONCAT(c.name,'|',c.GradeName,'|',c.SubjectName,'|',c.PublisherName,'|',c.VolumeName,'|',c.CourseName,'|',c.xdName) AS val")
		->having($where)
		->order('c.classid desc')
		->findAll();
		$classes = findPage($classes_all, 10);
		$this->assign ( 'classes', $classes);
		
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 课堂纪实资源列表页
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-5-13
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-13 上午03:33:27
	 *         +----------------------------------------------------------
	 */
	public function getresList() {
		$id = intval ( $_REQUEST ['id'] );
		if (empty ( $id )) {
			$id = 5;
		}
		$this->setTitle ( '课堂资源' );
		$data ['leftcate'] = procExecute ( "call vcCategory(" . $id . ");" );
		$data ['context'] = M ( 'airclass_resource' )->findPage ( 10 );
		$data ['column'] = '课堂资源';
		$data ['path'] = procExecute ( "call vcPath(" . $id . ");" );
		$this->assign ( arrayKeyTolower ( $data ) );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 课堂纪实资源列表页对应搜索页
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-5-12
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-12 上午03:33:27
	 *         +----------------------------------------------------------
	 */
	public function ResSearchs() {
		$id = intval ( $_POST ['id'] );
		$ids = procExecute ( "call vcCategory(" . $id . ");" );
		$idss = $id;
		foreach ( $ids as $k => $v ) {
			$idss .= ',';
			$idss .= $v ['id'];
		}
		$map ['id'] = array (
				'in',
				$idss 
		);
		$data ['context'] = M ()->table ( 'ts_square_airclass s,ts_airclass_resource t' )->where ( "t.classid=s.airclassid and s.categoryid in(" . $idss . ")" )->findPage ( 10 );
		$data ['column'] = '课堂资源';
		$data ['path'] = procExecute ( "call vcPath(" . $id . ");" );
		$this->assign ( arrayKeyTolower ( $data ) );
		$this->display ( 'ressearchs' );
	}
	
	/**
	 * +----------------------------------------------------------
	 * 课堂纪实展示页面
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-5-13
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-13 上午03:34:43
	 *         +----------------------------------------------------------
	 */
	public function show() {
		$classId = intval ( $_REQUEST ['id'] );
		$class = M ( 'airclass' )->where ( "`classid`= '" . $classId . "'" )->find ();
		$this->assign ( "classinfo", $class );
		
		// 获取已选择加入的人
		$members ['allmember'] = M ( 'airclass_member' )->where ( "classid = '$classId'" )->findAll ();
		
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
		$this->assign ( 'members', $members );
		// 相关资料获取
		$sql = "SELECT a.* FROM " . C ( 'DB_PREFIX' ) . "airclass_resource r LEFT JOIN " . C ( 'DB_PREFIX' ) . "attach a ON r.resourceid = a.id WHERE r.classid = '$classId'";
		$data ['resources'] = M ( 'airclass_resource' )->query ( $sql );
		$data ['parents'] = $parents;
		$data ['teachers'] = $teachers;
		$data ['students'] = $students;
		$data ['realparents'] = $realParents;
		$data ['realteachers'] = $realTeachers;
		$data ['realstudents'] = $realStudents;
		$this->assign ( $data );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * ajax异步显示搜索
	 * +----------------------------------------------------------
	 * 
	 * @return Array 搜索
	 * @author 美美2013-5-13
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-13 上午03:37:03
	 *         +----------------------------------------------------------
	 */
	public function dosearch() {
	}
	public function charts() {
		$id = intval ( $_REQUEST ['id'] );
		if (empty ( $id )) {
			$id = 5;
		}
		$this->setTitle ( '排行榜' );
		$data ['leftcate'] = procExecute ( "call vcCategory(" . $id . ");" );
		
		// 活跃人物排行榜
		$data ['person'] = M ( 'airclass' )->field ( 'uid,count(*) as count' )->group ( 'uid' )->order ( 'count DESC' )->findpage ( 10 );
		foreach ( $data ['person'] ['data'] as $k => $v ) {
			$ycanjia = M ( 'airclass_member' )->field ( 'count(id) as canjia' )->where ( 'uid=' . $v ['uid'] )->find ();
			$scanjia = M ( 'airclass_member' )->field ( 'count(id) as canjia' )->where ( 'uid=' . $v ['uid'] . ' and status=1' )->find ();
			$resource = M ( 'airclass_resource' )->field ( 'count(id) as resource' )->where ( 'uid=' . $v ['uid'] )->find ();
			$data ['person'] ['data'] [$k] ['ycanjia'] = $ycanjia ['canjia'];
			$data ['person'] ['data'] [$k] ['scanjia'] = $scanjia ['canjia'];
			$data ['person'] ['data'] [$k] ['resource'] = $resource ['resource'];
		}
		
		// 教研活动排行榜
		$data ['teaching'] = M ( 'airclass' )->findPage ( 10 );
		foreach ( $data ['teaching'] ['data'] as $k => $v ) {
			$ycanjia = M ( 'airclass_member' )->field ( 'count(classid) as canjia' )->where ( 'classid=' . $v ['classid'] )->find ();
			$scanjia = M ( 'airclass_member' )->field ( 'count(classid) as canjia' )->where ( 'classid=' . $v ['classid'] . ' and status=1' )->find ();
			$resource = M ( 'airclass_resource' )->field ( 'count(classid) as resource' )->where ( 'classid=' . $v ['classid'] )->find ();
			$data ['teaching'] ['data'] [$k] ['ycanjia'] = $ycanjia ['canjia'];
			$data ['teaching'] ['data'] [$k] ['scanjia'] = $scanjia ['canjia'];
			$data ['teaching'] ['data'] [$k] ['resource'] = $resource ['resource'];
		}
		$data ['column'] = '课堂排行榜';
		$data ['path'] = procExecute ( "call vcPath(" . $id . ");" );
		$this->assign ( arrayKeyTolower ( $data ) );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 教研中心资源列表页对应搜索页
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-5-12
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-12 上午03:33:27
	 *         +----------------------------------------------------------
	 */
	public function ChartSearchs() {
		$id = intval ( $_REQUEST ['id'] );
		if (empty ( $id )) {
			$id = 5;
		}
		$this->setTitle ( '排行榜' );
		$data ['leftcate'] = procExecute ( "call vcCategory(" . $id . ");" );
		
		// 活跃人物排行榜
		$data ['person'] = M ( 'airclass' )->field ( 'uid,count(*) as count' )->group ( 'uid' )->order ( 'count DESC' )->findpage ( 10 );
		foreach ( $data ['person'] ['data'] as $k => $v ) {
			$ycanjia = M ( 'airclass_member' )->field ( 'count(id) as canjia' )->where ( 'uid=' . $v ['uid'] )->find ();
			$scanjia = M ( 'airclass_member' )->field ( 'count(id) as canjia' )->where ( 'uid=' . $v ['uid'] . ' and status=1' )->find ();
			$resource = M ( 'airclass_resource' )->field ( 'count(id) as resource' )->where ( 'uid=' . $v ['uid'] )->find ();
			$data ['person'] ['data'] [$k] ['ycanjia'] = $ycanjia ['canjia'];
			$data ['person'] ['data'] [$k] ['scanjia'] = $scanjia ['canjia'];
			$data ['person'] ['data'] [$k] ['resource'] = $resource ['resource'];
		}
		// 教研活动排行榜
		$data ['teaching'] = $this->searchcate ( $id, 1 );
		
		$data ['column'] = '课堂纪实排行榜';
		$data ['path'] = procExecute ( "call vcPath(" . $id . ");" );
		$this->assign ( arrayKeyTolower ( $data ) );
		$this->display ( 'chartsearchs' );
	}
	
	/**
	 * +----------------------------------------------------------
	 * 按栏目搜索的搜索结果
	 * +----------------------------------------------------------
	 * 
	 * @param int $id
	 *        	所要显示的分组id
	 * @param String $where
	 *        	搜索条件
	 * @return Array 搜索到的教研的信息
	 * @author 美美2013-5-13
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-5-13 上午03:27:01
	 *         +----------------------------------------------------------
	 */
	private function searchcate($id, $where) {
		$ids = procExecute ( "call vcCategory(" . $id . ");" );
		$idss = $id;
		foreach ( $ids as $k => $v ) {
			$idss .= ',';
			$idss .= $v ['id'];
		}
		$map ['id'] = array (
				'in',
				$idss 
		);
		$categoryList = M ()->table ( 'ts_square_airclass s,ts_airclass t' )->where ( "t.classid=s.airclassid and s.categoryid in(" . $idss . ") and " . $where )->findPage ( 10 );
		foreach ( $categoryList ['data'] as $k => $v ) {
			$ycanjia = M ( 'airclass_member' )->field ( 'count(classid) as canjia' )->where ( 'classid=' . $v ['classid'] )->find ();
			$scanjia = M ( 'airclass_member' )->field ( 'count(classid) as canjia' )->where ( 'classid=' . $v ['classid'] . ' and status=1' )->find ();
			$resource = M ( 'airclass_resource' )->field ( 'count(classid) as resource' )->where ( 'classid=' . $v ['classid'] )->find ();
			$categoryList ['data'] [$k] ['ycanjia'] = $ycanjia ['canjia'];
			$categoryList ['data'] [$k] ['scanjia'] = $scanjia ['canjia'];
			$categoryList ['data'] [$k] ['resource'] = $resource ['resource'];
		}
		return $categoryList;
	}
}
?>
