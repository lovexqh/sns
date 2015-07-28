<?php
class AppClassInfoHooks extends Hooks
{
    static $cache_list=array();
	public function init()
	{
	}

	/* 插件后台管理项 */
	public function classInfo()
	{
    	//为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
		if ( !empty($_POST) ) {
			$_SESSION['admin_searchClassinfo'] = serialize($_POST);
		}else if ( isset($_GET[C('VAR_PAGE')]) && !is_null($_SESSION['admin_searchClassinfo']) ) {
			$_POST = unserialize($_SESSION['admin_searchClassinfo']);
		}else {
			unset($_SESSION['admin_searchClassinfo']);
		}

		if ( !empty($_POST['edukey']) ) {
    		$this->assign('type', 'searchClassinfo');
		}

		$_POST['app_id'] 	&& $map['app_id'] 	 = $_POST['app_id'];
		$_POST['classname']	&& $map['class_name'] = array('exp', 'LIKE "%' . t($_POST['classname']) . '%"');
		$_POST['status'] 	&& $map['status'] 	 = intval((bool)$_POST['status']);
		//$_POST['subject']   && $map['Subject']   = array('exp', 'LIKE "%' . t($_POST['subject']) . '%"');
		//$_POST['grade']   	&& $map['Grade']  	 = array('exp', 'LIKE "%' . t($_POST['grade']) . '%"');

		$page = intval($_POST['page']);
		$limit = 20;

		//$map['1'] = 1;
		$field = "*";
		if($page){
			$dataList = M('app_class')->field($field)->where($map)->order( 'cTime DESC')->limit( '0,'.$limit )->findAll();
        }else{
            $dataList = M('app_class')->field($field)->where($map)->order( 'cTime DESC' )->findPage($limit);
        }

		//默认获取全部应用列表
        $appdata = M('app')->where('status=1 and host_type=0')->order('display_order ASC')->findAll();
        $this->assign('dataApp',$appdata);	//全部应用

        if ( !empty($_POST['publisher']) ) {	//判断是否查询行为并获取科目列表
			$field = "`Subject` as Subject,(SELECT `DataName` FROM `ts_edu_datadictionary` WHERE `DataCode` = `Subject` order by DataOrder ASC) as SubjectName";
			$where = "`Publisher`='".t($_POST['publisher'])."'";
			$group = "`Subject`";
			$dataSubject = M('edu_classification')->field($field)->where($where)->group($group)->order( 'CourseID ASC')->findAll();
			$this->assign('subject', 'show');
			$this->assign('dataSubject',$dataSubject);
        }

        if ( !empty($_POST['subject']) ) {	//判断是否查询行为并获取年级列表
        	$field = "`Grade`,(SELECT `DataName` FROM `ts_edu_datadictionary` WHERE `DataCode` = `Grade` order by DataOrder ASC) as GradeName";
			$where = "`Publisher`='".t($_POST['publisher'])."' and `Subject` = '".t($_POST['subject'])."'";
			$group = "`Grade`";
			$dataGrade = M('edu_classification')->field($field)->where($where)->group($group)->order( 'CourseID ASC')->findAll();

			$this->assign('grade', 'show');
			$this->assign('dataGrade',$dataGrade);
        }

    	$this->assign($_POST);

		$this->assign('dataPublisher',$dataPublisher);	//出版(或发行)者

    	$this->assign('dataList',$dataList);
		$this->display('classinfo');
	}

	public function addClass()
	{
		//默认获取全部应用（开启，并是本地应用）
		$map['status']=1;
        $map['host_type']=0;
        $appdata = M('app')->where($map)->order('display_order ASC')->findAll();
        $this->assign('appdata',$appdata);	//全部应用

    	if (intval($_GET['id']) > 0) {
    		$classinfo = M('app_class')->where('class_id=' . intval($_GET['id']))->find();
    		$this->assign('classinfo', $classinfo);
    		$this->assign('jumpUrl', $_SERVER['HTTP_REFERER']);
    	}
    	$this->display('addclass');
	}

	public function saveClass()
	{
		$class_id 			= 	intval($_POST['class_id']);
		$data[app_id]		=	intval($_POST['app_id']);
		$data[parentid]		=	intval($_POST['parentid']);
		$data[uid]			=	intval($_POST['uid']);
		$data[status]		=	intval((bool)$_POST['status']);
		$data[display_order]=	intval($_POST['display_order']);
		$data[class_name]	=	t($_POST['class_name']);
		$data[description]	=	t($_POST['description']);
		$data[ctime]		=	time();
		if($class_id){
			$res = M('app_class')->where('class_id ='.$class_id)->find();
	        if(!$res){
	           $this->error('该分类不存在！');
	        }
			$map[class_id]		=	$class_id;
			$data[mtime]		=	time();
			$result = M('app_class')->where($map)->save($data);
		}else{
			$result = M('app_class')->add($data);
		}
		if (false !== $result) {
			$jumpUrl = $_POST['jumpUrl'] ? $_POST['jumpUrl'] : U('admin/Addons/admin').'&pluginid=26&page=classInfo';
			$this->assign('jumpUrl', $jumpUrl);
			$this->success();
		} else {
			$this->error();
		}
	}

	public function doGetAppClass()
    {
		$app_id = $_REQUEST['app_id'];	//获取要查询的值.
		if($app_id){
			include_once SITE_PATH.'/addons/libs/SelectTree.class.php';
			$map['app_id']=$app_id;
			$result = M('app_class')->field('class_id,parentid,class_name')->where($map)->findAll();
			//$tree = new SelectTree($res, 'class_id', 'parentid', 'class_name', 'parentid',0,1);
			//$result = $tree->getSelectTree();
		}

		$rturnArray = array();
		$array_tmp = array(
			'class_id'	=>	0,
			'parentid'	=>	0,
			'class_name'=>	'根分类',
			'open'		=>	true,
			'iconSkin'=>"floder"
		);
		array_push($rturnArray,$array_tmp);

		if($result){	//如果有返回分类信息则
			$s = '';
			foreach($result as $r){
				$open = '';
				foreach($result as $v){
					if($r[class_id]==$v[parentid]){
						$open = true;
					}
				}
				$array_tmp = array(
					'iconSkin'=>"floder"
				);
				$r[iconSkin] = "floder";
				if(!empty($open))
				$r[open] = true;
				$rturnArray[] = $r;
			}
		}
    	echo json_encode($rturnArray);
    }

    public function doGetSelect()
    {
		$key = $_POST ['key']; // 获取要查询的键.
		$pvalue = $_POST ['pvalue']?$_POST ['pvalue']:'';	//获取要查询的值.
		$value = $_POST ['value'];	//获取要查询的值.
		switch($key){
			case "publisher":
				$field = "`Subject` as cloumn,(SELECT `DataName` FROM `ts_edu_datadictionary` WHERE `DataCode` = `Subject` order by DataOrder ASC) as text";
				$where = "`Publisher`='$value'";
				$group = "`Subject`";
				$result = M('edu_classification')->field($field)->where($where)->group($group)->order( 'CourseID ASC')->findAll();
				break;
			case "subject":
				$field = "`Grade` as cloumn,(SELECT `DataName` FROM `ts_edu_datadictionary` WHERE `DataCode` = `Grade` order by DataOrder ASC) as text";
				$where = "`Publisher`='$pvalue' and `Subject` = '$value'";
				$group = "`Grade`";
				$result = M('edu_classification')->field($field)->where($where)->group($group)->order( 'CourseID ASC')->findAll();
				break;
			default:
		}

    	echo json_encode($result);
    }

	/***
	 * 删除一个分类
	 */
    public function deleteClassinfo()
    {
		$id = is_array($_POST ['id']) ? '(' . implode ( ',', $_POST ['id'] ) . ')' : '(' . $_POST ['id'] . ')'; // 判读是不是数组
		$result = M('app_class')->where('parentid IN ' . t($id) )->find();
		if($result){
			echo -1;
		}else{
			$result = M('app_class')->where('class_id IN ' . t($id) )->delete(); // 删除知识点
	    	if (false!=$result) {
				echo 1;
			} else {
				echo 0;
			}
		}
    }

}