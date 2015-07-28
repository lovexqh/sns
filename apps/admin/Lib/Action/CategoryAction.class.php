<?php
class CategoryAction extends AdministratorAction {

	public function index() {
		echo '<h3>正在开发中...</h3>';
	}

	/***
	 * 验证数据的完整性
	 */
	private function __isValidRequest($field, $array = 'post') {
		$field = is_array($field) ? $field : explode(',', $field);
		$array = $array == 'post' ? $_POST : $_GET;
		foreach ($field as $v){
			$v = trim($v);
			if ( !isset($array[$v]) || $array[$v] == '' ) return false;
		}
		return true;
	}

	/***
	 * 基础分类管理
	 */
	public function dataDict(){
		$limit = 20;
	    $datadict = M('category_dictionary')->order('ID DESC,DataOrder ASC')->findPage($limit);
		$this->assign('data',$datadict);
		$this->assign('isadd',0);
		$this->display();
	}

	/***
	 * 添加基础分类展示
	 */
	public function modifyDataDict(){
		$_GET['id'] 	&& $map['ID'] 	 = intval($_GET['id']);
		if($map['ID']){
			$this->assign('do','modify');
			$data = M('category_dictionary')->where($map)->find();
			$this->assign('data',$data);
		}else{
			$this->assign('do','add');
		}

		$data = M('category_dictionary')->field('id,datatype,datadescribe,dataorder')->group('DataType,DataDescribe')->order('DataOrder DESC')->findAll();
		$this->assign('datatype',$data);

		$this->assign('isadd',1);
		$this->display();
	}

	/***
	 * 执行添加修改功能
	 */
	public function doModifyDataDict(){
		if (!$this->__isValidRequest('do,dataDescribe,dataType,dataName,dataCode')) {
			$this->error('数据不完整');
		}

		$action = $_POST['do'];

		$_POST['dataDescribe'] 	&& $data['DataDescribe']  = $_POST['dataDescribe'];
		$_POST['dataType'] 		&& $data['DataType'] 	 = $_POST['dataType'];
		$_POST['dataName'] 		&& $data['DataName'] 	 = $_POST['dataName'];
		$_POST['dataCode'] 		&& $data['DataCode'] 	 = $_POST['dataCode'];
		$_POST['dataOrder'] 	&& $data['DataOrder'] 	 = intval($_POST['dataOrder']);
		$_POST['remark'] 		&& $data['Remark'] 		 = $_POST['remark'];

		if(!intval($_POST['dataOrder'])){
			$_POST['dataType'] 		&& $where['DataType'] 	 = $_POST['dataType'];
			$result = M('category_dictionary')->where($where)->order('DataOrder DESC')->find();
			$data['DataOrder'] = $data[DataOrder]+1;
		}

		if($action=='add'){
			$row = M('category_dictionary')->add($data);
		}else{
			$_POST['id'] 	&& $map['ID'] 	 = intval($_POST['id']);
			$row = M('category_dictionary')->where($map)->save($data);
		}
		if($row){
			$jumpUrl = $_POST['jumpUrl'] ? $_POST['jumpUrl'] : U('admin/Category/dataDict');
			$this->assign('jumpUrl', $jumpUrl);
			$this->success();
		}else{
			$this->error();
		}
	}

	/***
	 * 执行删除基础分类数据
	 */
	public function doDeleteDataDict(){
		if( empty($_REQUEST['ids']) ) {
			echo 0;
			exit ;
		}
		$map['id'] = array('in', t($_POST['ids']));

		echo M('category_dictionary')->where($map)->delete() ? '1' : '0';
	}

	//知识点及分类管理
	public function knowledge(){

		$db_prefix = C('DB_PREFIX');

		//为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
		if ( !empty($_POST) ) {
			$_SESSION['admin_searchClassification'] = serialize($_POST);
		}else if ( isset($_GET[C('VAR_PAGE')]) && !is_null($_SESSION['admin_searchClassification']) ) {
			$_POST = unserialize($_SESSION['admin_searchClassification']);
		}else {
			unset($_SESSION['admin_searchClassification']);
		}

		if ( !empty($_POST['edukey']) ) {
    		$this->assign('type', 'searchClassification');
		}

		$_POST['edukey'] 	&& $map['Course'] 	 = array('exp', 'LIKE "%' . t($_POST['edukey']) . '%"');
		$_POST['publisher']	&& $map['Publisher'] = array('exp', 'LIKE "%' . t($_POST['publisher']) . '%"');
		$_POST['subject']   && $map['Subject']   = array('exp', 'LIKE "%' . t($_POST['subject']) . '%"');
		$_POST['grade']   	&& $map['Grade']  	 = array('exp', 'LIKE "%' . t($_POST['grade']) . '%"');

		$page = intval($_POST['page']);
		$limit = 20;

		//$map['1'] = 1;
		$field = "`Publisher`,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Publisher`) as PublisherName,`Subject`,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Subject`) as SubjectName,`Grade`,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Grade`) as GradeName,`Course`,`CourseID`,`Remark`";
		if($page){
			$dataList = M('category_knowledge')->field($field)->where($map)->order('CourseID DESC')->limit('0,'.$limit)->findAll();
        }else{
            $dataList = M('category_knowledge')->field($field)->where($map)->order('CourseID DESC')->findPage($limit);
        }

		//默认获取出版（发行）者列表
        $dataPublisher = M('category_dictionary')->where("`DataType`='Publisher'")->order('DataOrder ASC')->findAll();

        if ( !empty($_POST['publisher']) ) {	//判断是否查询行为并获取科目列表
			$field = "`Subject` as Subject,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Subject` order by DataOrder ASC) as SubjectName";
			$where = "`Publisher`='".t($_POST['publisher'])."'";
			$group = "`Subject`";
			$dataSubject = M('category_dictionary')->field($field)->where($where)->group($group)->order('CourseID ASC')->findAll();
			$this->assign('subject', 'show');
			$this->assign('dataSubject',$dataSubject);
        }

        if ( !empty($_POST['subject']) ) {	//判断是否查询行为并获取年级列表
        	$field = "`Grade`,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Grade` order by DataOrder ASC) as GradeName";
			$where = "`Publisher`='".t($_POST['publisher'])."' and `Subject` = '".t($_POST['subject'])."'";
			$group = "`Grade`";
			$dataGrade = M('category_dictionary')->field($field)->where($where)->group($group)->order('CourseID ASC')->findAll();

			$this->assign('grade', 'show');
			$this->assign('dataGrade',$dataGrade);
        }

    	$this->assign($_POST);

		$this->assign('dataPublisher',$dataPublisher);	//出版(或发行)者

    	$this->assign('dataList',$dataList);
		$this->display();
	}

	/***
	 * 添加知识点展示
	 */
	public function modifyKnowledge(){

		$db_prefix = C('DB_PREFIX');

		$do = $_GET['id']?'modify':'add';

		//默认获取出版（发行）者列表
        $dataPublisher = M('category_dictionary')->where("`DataType`='Publisher'")->order('DataOrder ASC')->findAll();
        $this->assign('dataPublisher',$dataPublisher);	//出版(或发行)者

        //获取科目列表
        $dataSubject = M('category_dictionary')->where("`DataType`='Subject'")->order('DataOrder ASC')->findAll();
        $this->assign('dataSubject',$dataSubject);	//出版(或发行)者

        //获取年级列表
        $dataGrade = M('category_dictionary')->where("`DataType`='Grade'")->order('DataOrder ASC')->findAll();
        $this->assign('dataGrade',$dataGrade);	//出版(或发行)者

    	if (intval($_GET['id']) > 0) {
    		$field = "`Publisher`,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Publisher`) as PublisherName,`Subject`,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Subject`) as SubjectName,`Grade`,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Grade`) as GradeName,`Course`,`CourseID`,`Remark`";
			$data = M('category_knowledge')->field($field)->where('CourseID=' . intval($_GET['id']))->order( 'CourseID DESC')->find();
    		$data['id'] = intval($_GET['id']);
    		$this->assign('data', $data);
    		$this->assign('jumpUrl', $_SERVER['HTTP_REFERER']);
    	}

    	$this->assign('do',$do);

    	$this->display();
	}

	/***
	 * 储存知识点内容
	 */
	public function doModifyKnowledge(){
		if (!$this->__isValidRequest('do,publisher,subject,grade,course')) {
			$this->error('数据不完整');
		}

		$action = $_POST['do'];

		$data[Publisher]	=	t($_POST['publisher']);
		$data[Subject]		=	t($_POST['subject']);
		$data[Grade]		=	t($_POST['grade']);
		$data[Course]		=	t($_POST['course']);
		$data[Remark]		=	t($_POST['remark']);

		if($action!='add'){
			$id = intval($_POST['id']);
			$res = M('category_knowledge')->where('CourseID ='.$id)->find();
	        if(!$res){
	           $this->error('该知识点不存在！');
	        }
			$map[CourseID]		=	$id;
			$result = M('category_knowledge')->where($map)->save($data);
		}else{
			$result = M('category_knowledge')->add($data);
		}
		if (false !== $result) {
			$jumpUrl = $_POST['jumpUrl'] ? $_POST['jumpUrl'] : U('admin/Category/knowledge');
			$this->assign('jumpUrl', $jumpUrl);
			$this->success();
		} else {
			$this->error();
		}
	}

	/***
	 * 执行删除知识点数据
	 */
	public function doDeleteKnowledge(){
		if( empty($_REQUEST['ids']) ) {
			echo 0;
			exit ;
		}
		$map['CourseID'] = array('in', t($_POST['ids']));

		echo M('category_knowledge')->where($map)->delete() ? '1' : '0';
	}

	/***
	 * 获取知识点数据类型分类
	 */
	public function doKnowledgeSelect()
    {
    	$db_prefix = C('DB_PREFIX');

		$key = $_POST ['key']; // 获取要查询的键.
		$pvalue = $_POST ['pvalue']?$_POST ['pvalue']:'';	//获取要查询的值.
		$value = $_POST ['value'];	//获取要查询的值.
		switch($key){
			case "publisher":
				$field = "`Subject` as cloumn,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Subject` order by DataOrder ASC) as text";
				$where = "`Publisher`='$value'";
				$group = "`Subject`";
				$result = M('category_knowledge')->field($field)->where($where)->group($group)->order('CourseID ASC')->findAll();
				break;
			case "subject":
				$field = "`Grade` as cloumn,(SELECT `DataName` FROM `".$db_prefix."category_dictionary` WHERE `DataCode` = `Grade` order by DataOrder ASC) as text";
				$where = "`Publisher`='$pvalue' and `Subject` = '$value'";
				$group = "`Grade`";
				$result = M('category_knowledge')->field($field)->where($where)->group($group)->order('CourseID ASC')->findAll();
				break;
			default:
		}

    	echo json_encode($result);
    }
}