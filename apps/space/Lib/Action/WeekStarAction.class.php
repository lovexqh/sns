<?php
/**
 +------------------------------------------------------------------------------
 * 每周之星控制器
 +------------------------------------------------------------------------------
 * @category	空间 （应用名称）
 * @package		 Lib/Action
 * @author		董良
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-4-22 上午11:24:19
 +------------------------------------------------------------------------------
 */
header("Content-Type: text/html; charset=utf-8");

class WeekStarAction extends BaseAction {
	private $classid;
	private $db_prefix;
	private $uc_session;
	private $identityid;
	private $identitytype;
	private $path;
	private $adviser = 0;

	public function _initialize() {
		parent :: _initialize();

		$this->db_prefix = C('DB_PREFIX');
		$this->uc_session = arrayKeyTolower($_SESSION['ucInfo']);
		$this->identityid = $this->uc_session['identityid'];


		//判断用户类型
		// 		$this->userType = 'other';
		$this->classid = $_REQUEST['classid'];
		// 		$clazz = uc_get_adviserclassids_by_identityid($this->identityid);
		// 		for($i=0;$i<count($clazz);$i++) {
		// 			if($clazz[$i]['classid'] == $this->classid) {
		// 				$this->userType = 'adviser';
		// 			}
		// 		}
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
		$this->adviser = $adviser;

		// 		$this->assign('userType', $this->userType);
		$this->assign('classid', $this->classid);

		//设置图片保存路径
		$this->path = UPLOAD_PATH.'/glory/';
		$this->assign('picPath',SITE_URL.'/data/uploads/glory/');

		$control = $_REQUEST['control'];
		$this->assign('control',$control);
	}

	/**
	 +----------------------------------------------------------
	 * 获取一周之星的类别列表
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-23 下午2:49:11
	 +----------------------------------------------------------
	 */
	private function _weekStarType() {
		$class = M('class_weekstar');
		$where = "type = 1";
		$types = $class->where($where)->select();
		return $types;
	}
	/**
	 +----------------------------------------------------------
	 * 获取一周的开始时间
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-23 下午2:49:11
	 +----------------------------------------------------------
	 */
	private function _getStartTime($time) {
		//获取一周的开始时间和结束时间
		$date = getdate($time);
		$year = $date['year'];
		$month = $date['mon'];
		$day = $date['wday'];
		if ($day == 0) {
			$day = 7;
		}
		$firstday = $date['mday'] - $day +1;
		$nowMonthDay = date("t",$time);
		if (substr($firstday, 0, 1) == "-") {
			$firstMonth = $month -1;
			$lastMonthDay = date("t", $firstMonth);
			$firstday = $lastMonthDay + $date['mday'] - $day;
			$time_1 = strtotime($year . "-" . $firstMonth . "-" . $firstday);
		} else {
			$time_1 = strtotime($year . "-" . $month . "-" . $firstday);
		}
		return $time_1;
	}
	/**
	 +----------------------------------------------------------
	 * 获取一周的结束时间
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-23 下午2:49:11
	 +----------------------------------------------------------
	 */
	private function _getEndTime($time) {
		$date = getdate($time);
		$year = $date['year'];
		$month = $date['mon'];
		$day = $date['wday'];
		if ($day == 0) {
			$day = 7;
		}
		$nowMonthDay = date("t",$time);
		$lastday = $date['mday'] + (7 - $day);
		if ($lastday > $nowMonthDay) {
			$lastday = $lastday - $nowMonthDay;
			$lastMonth = $month +1;
			$time_2 = strtotime($year . "-" . $lastMonth . "-" . $lastday);
		} else {
			$time_2 = strtotime($year . "-" . $month . "-" . $lastday);
		}
		return $time_2;
	}

	/**
	 +----------------------------------------------------------
	 * 显示更新界面
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-26 下午2:49:11
	 +----------------------------------------------------------
	 */
	function saveView() {
		if($this->adviser == 0) {
			$this->error("没有权限");
		}
		if(!empty($_REQUEST['rid'])) {
			$this->assign('rid',$_REQUEST['rid']);
			$selectResult = M('')
			->table(C('DB_PREFIX') . "class_user_weekstar")
			->field('*')
			->where("id =".$_REQUEST['rid'])->find();
			$info['identityid'] = $selectResult['uid'];
			$info['date'] = date("Y-m-d",$selectResult['date']);
			$info['gloryid'] = $selectResult['gloryid'];
			$info['comment'] = $selectResult['comment'];
		}
		$this->assign('info',$info);
		//荣誉类型列表
		$this->assign('weekStarType',$this->_weekStarType());
		//学生列表
		$studentList = uc_student_get_id($this->classid);
		$this->assign('studentList', $studentList);

		$this->display("../Manage/m_addglory");
		//		print_r($this->_weekStarType());
		//		print_r($info);
	}
	/**
	 +----------------------------------------------------------
	 * 添加更新关系表记录
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-26 下午2:49:11
	 +----------------------------------------------------------
	 */
	function save() {
		//检查数据
		$result = -1;

		if($this->adviser == 0) {
			$this->ajaxReturn($result, "没有操作权限！", 0);
		}
		$identityid = $_REQUEST['identityid'];
		if($identityid==null||$identityid.trim()=='') {
			$this->ajaxReturn($result, "没有设置学生姓名！", 0);
		}
		$gloryid = $_REQUEST['gloryid'];
		if($gloryid==null||$gloryid.trim()=='') {
			$this->ajaxReturn($result, "没有选择荣誉类型！", 0);
		}
		$date = $_REQUEST['date'];
		if($date==null||$date.trim()=='') {
			$this->ajaxReturn($result, "没有选择时间！", 0);
		}
		if(!ereg("^[0-9]*[1-9][0-9]*$",$date)) {
			$date = strtotime($date);
		}
		//设置保存数据
		$data['uid'] = $identityid;
		$data['gloryid'] = $gloryid;
		$data['date'] = $date;
		$data['comment'] = $_REQUEST['comment'];
		$rid = $_REQUEST['rid'];
		//判断并保存
		if(empty($rid)) {
			$data['classid'] = $this->classid;
			$selectResult = M('')
			->table(C('DB_PREFIX') . "class_user_weekstar")
			->field('id')
			->where("gloryid = $gloryid and classid=$this->classid and date>=".$this->_getStartTime($date)." and date<=".$this->_getEndTime($date))->find();
			if($selectResult!=null&&$selectResult!=''&&count($selectResult)!=0) {
				$this->ajaxReturn(-1, "所选时间的每周之星已存在！", 0);
			} else {
				$result = M('')
				->table(C('DB_PREFIX') . "class_user_weekstar")
				->data($data)
				->add();
				if($result) {
					$data['rid'] = $result;
					$this->ajaxReturn($data, "保存成功！", 1);
				}else{
					$this->ajaxReturn($result, "保存失败！", 0);
				}
			}
		} else {
			$selectResult = M('')
			->table(C('DB_PREFIX') . "class_user_weekstar")
			->field('id')
			->where("id!=$rid and gloryid = $gloryid and classid=$this->classid and date>=".$this->_getStartTime($date)." and date<=".$this->_getEndTime($date))->find();
			if($selectResult!=null&&$selectResult!=''&&count($selectResult)!=0) {
				$this->ajaxReturn(-1, "所选时间的每周之星已存在！", 0);
			} else {
				$result = M('')
				->table(C('DB_PREFIX') . "class_user_weekstar")
				->where("id=$rid")
				->data($data)
				->save();
				if($result) {
					$data['rid'] = $rid;
					$this->ajaxReturn($data, "保存成功！", 1);
				}else{
					$this->ajaxReturn($result, "保存失败！", 0);
				}
			}
		}
	}

	/**
	 +----------------------------------------------------------
	 * 删除关系表记录
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-26 下午2:49:11
	 +----------------------------------------------------------
	 */
	function del() {
		if($this->adviser == 0) {
			$this->ajaxReturn(-1, "没有操作权限！", 0);
		}
		if( empty($_REQUEST['rid']) ) {
			$this->ajaxReturn(-1, "没有获取记录ID！", 0);
		}
		$rid = $_REQUEST['rid'];
		$result = M('')
		->table(C('DB_PREFIX') . "class_user_weekstar")
		->where("id=".$rid)
		->delete();
		if($result) {
			$this->ajaxReturn($result, "删除成功！", 1);
		}else{
			$this->ajaxReturn($result, "删除失败！", 0);
		}
	}

	/**
	 +----------------------------------------------------------
	 * 搜索详细荣誉记录
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-24 下午2:49:11
	 +----------------------------------------------------------
	 */
	private function _searchByTime($where, $order) {
		$result = M('')->table(C('DB_PREFIX') . "class_user_weekstar r")
		->join(" INNER JOIN " . C('DB_PREFIX') . "class_weekstar g ON r.gloryid=g.id INNER JOIN ".UC_DBTABLEPRE."studentinfo s ON r.uid=s.identityid")
		->field('r.id as rid,r.comment as t_comment, r.*,g.*,s.*')
		->where($where)
		->order($order)
		->findPage(10);
		return $result;
	}

	/**
	 +----------------------------------------------------------
	 * 显示详细列表
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-26 下午2:49:11
	 +----------------------------------------------------------
	 */
	function showByTime() {
		$info['name']=$_REQUEST['name'];
		//设置where条件uid
		$where = ' 1=1 ';
		if($info['name'] != null && $info['name'].trim()!='') {
			$where = $where.' and (';
			$students = uc_student_get_id_byname($this->classid, $info['name']);
			for($i=0;$i<count($students);$i++) {
				$where = $where.'r.uid='.$students[$i]['identityid'].' or ';
			}
			if(count($students)>0) {
				$where = substr($where,0,count($where)-5).') ';
			}
		}
		//设置where条件gloryid
		$info['gloryid'] = $_REQUEST['gloryid'];
		if($info['gloryid'] != null && $info['gloryid'].trim()!='') {
			$where = $where.' and gloryid='.$info['gloryid'].' ';
		}
		//设置where条件date
		$info['starttime'] = $_REQUEST['starttime'];
		$startTime = $_REQUEST['starttime'];
		if($startTime == null || $startTime.trim()=='') {
			$startTime = 0;
		}else if(!ereg("^[0-9]*[1-9][0-9]*$",$startTime)) {
			$startTime = strtotime($startTime);;
		}
		$info['endtime'] = $_REQUEST['endtime'];
		$endTime = $_REQUEST['endtime'];
		if($endTime == null || $endTime.trim()=='') {
			$endTime = 0;
		}else if(!ereg("^[0-9]*[1-9][0-9]*$",$endTime)) {
			$endTime = strtotime($endTime);
		}
		if($startTime!=0 && $endTime!=0 && $startTime<=$endTime) {
			$where = $where.' and date>='.$startTime.' and date<='.($endTime+86400);
		}else if($startTime==0 && $endTime!=0) {
			$where = $where.' and date<='.($endTime+86400).' ';
		}else if($startTime!=0 && $endTime==0) {
			$where = $where.' and date>='.$startTime.' ';
		}
		$where = $where.' and r.classid='.$this->classid.' ';
		//获取排序方式
		$info['list'] = $_REQUEST['list'];
		$order='';
		if(!empty($info['list'])) {
			$this->assign('orderList',$info['list']);
			for($i=0;$i<count($info['list'])-1;$i++) {
				$order = $order.$info['list'][$i].' desc,';
			}
			$order = $order.$info['list'][count($info['list'])-1];
		} else {
			$order = 'date  desc, xm ';
		}

		$this->assign('info', $info);
		// 		echo $where.'<br>';
		//echo $order.'<br>';
		//查找每周之星记录
		$result = $this->_searchByTime($where, $order);
		$this->assign('resultList', $result);
		// 		print_r($result);
		//查找每周之星类型
		$weekStarType = $this->_weekStarType();
		$this->assign('weekStarType', $weekStarType);
		//学生列表
		$studentList = uc_student_get_id($this->classid);
		$this->assign('studentList', $studentList);
		// 		print_r($studentList);
		$this->display("../Manage/m_manageglory");
	}


	/**
	 +----------------------------------------------------------
	 * 根据姓名查询统计记录
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-26 下午2:49:11
	 +----------------------------------------------------------
	 */
	private function _searchByName() {
		$name = $_REQUEST['name'];
		if($name==null || $name.trim()=='') {
			$where = '';
		}else {
			$where = " where s.xm like '%".$name."%' ";
		}
		$weekStarType = $this->_weekStarType();
		$fields = "r.uid,max(s.xm) xm,";
		for($i=0;$i<count($weekStarType);$i++) {
			$fields = $fields."sum(if(r.gloryid="
					.$weekStarType[$i]['id'].",1,0)) as g".$weekStarType[$i]['id'].",";
		}
		$fields = $fields."sum(1) as num";
		//		echo "select ".$fields." from ".C('DB_PREFIX')."class_user_weekstar r INNER JOIN ".UC_DBTABLEPRE."studentinfo s ON r.uid=s.identityid ".$where." group by uid order by num";
		import('ORG.Util.Page');
		$data = M('')->query("select count(1) as num from (select 1 from ts_class_user_weekstar group by uid) r;");// 查询满足要求的总记录数
		$count = $data[0]['num'];
		$Page  = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show= $Page->show();
		$result = M('')
		->query("select ".$fields." from ".C('DB_PREFIX')."class_user_weekstar r INNER JOIN ".UC_DBTABLEPRE."studentinfo s ON r.uid=s.identityid ".$where." group by uid order by num desc limit ".$Page->firstRow.",".$Page->listRows);

		$this->assign("show",$show);
		return $result;
	}
	/**
	 +----------------------------------------------------------
	 * 获取等级图片信息
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-5-8 下午2:49:11
	 +----------------------------------------------------------
	 */
	private function _getLvlInfo($gloryid) {
		$result = M('')
		->query("select * from ".C('DB_PREFIX')."class_weekstar_pic where gloryid=$gloryid order by lvl desc ");
		// 		echo "select * from ".C('DB_PREFIX')."class_weekstar_pic where gloryid=$gloryid order by lvl desc <br>";
		return $result;
	}
	/**
	 +----------------------------------------------------------
	 * 根据姓名统计显示记录
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-5-8 下午2:49:11
	 +----------------------------------------------------------
	 */
	function showByName() {
		//学生列表
		$studentList = uc_student_get_id($this->classid);
		$this->assign('studentList', $studentList);

		//设置搜索记录
		if(!empty($_REQUEST['name'])) {
			$info["name"] = $_REQUEST['name'];
			$this->assign('info',$info);
		}
		//设置每周之星类型列表
		$weekStarType = $this->_weekStarType();
		$this->assign('weekStarType',$weekStarType);
		//查询数据库，获取结果
		$result = $this->_searchByName();

		//设置等级图片
		for($j=0;$j<count($result);$j++) {//循环查询统计结果
			$num = 0;
			for($i=0;$i<count($weekStarType);$i++) {//循环荣誉类型
				$picInfo = $this->_getLvlInfo($weekStarType[$i]['id']);
				// 			print_r($picInfo);
				$n = $result[$j]['g'.$weekStarType[$i]['id']]; //活动荣誉数量
				for($k=0;$k<count($picInfo);$k++) {//循环等级图片信息
					$m = (int)($n/$picInfo[$k]['radix']);
					for($l=0;$l<$m;$l++) { //根据计算出等级倍数设置图片
						$result[$j]['pic'][$num]=$picInfo[$k]['pic'];
						$num++;
					}
					$n = $n%$picInfo[$k]['radix'];
					if($n == 0) {
						break;
					}
				}
			}
		}
		// 		print_r($result);
		$this->assign("result",$result);
		$this->display("../class/move_glory_byname");
	}

	private function _getclassAdviser(){
		$uc_session=arrayKeyTolower($_SESSION['ucInfo']);
		$uc_uid=uc_class_adviser_get_id($this->classid);
		if($uc_uid){
			if($uc_uid==$uc_session['uid']){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	private function _ishavePower(){
		$map['identityid']=$this->identityid;
		$map['classid']=$this->classid;
		$result=D('SetManager')->where($map)->order('mid ASC')->findall();
		return $result;
	}
}
?>