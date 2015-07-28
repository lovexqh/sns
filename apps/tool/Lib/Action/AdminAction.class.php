<?php
	import('admin.Action.AdministratorAction');
	/**
	 +------------------------------------------------------------------------------
	 * 资源后台管理Action
	 +------------------------------------------------------------------------------
	 * @category	tool
	 * @package		Lib/Action
	 * @author		liman
	 * @version		1.0
	 +------------------------------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:21:03
	 +------------------------------------------------------------------------------
	 */
	class AdminAction extends AdministratorAction {
        
        private $tool;
        private $newarray = array();
        /**
         +----------------------------------------------------------
         * 初始化
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午06:21:47
         +----------------------------------------------------------
         */
        public function _initialize(){
	        //管理权限判定
	        parent::_initialize();
            $this->tool = D( 'Tool' );
        }
        /**
         +----------------------------------------------------------
         * 修改全局设置
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午06:22:14
         +----------------------------------------------------------
         */
        public function doChangeBase (){
	        //变量过滤 todo:更细致的过滤
	        foreach($_POST as $k=>$v){
	            $config[$k] =   t($v);
	        }
	        //$config['limitsuffix'] = preg_replace("/bmp\|||\|bmp/",'',$config['photo_file_ext']);//过滤bmp
	        if(model('Xdata')->lput('tool',$config)){
	            $this->assign('jumpUrl', U('tool/Admin/index'));
	            $this->success('设置成功！');
	        }else{
	            $this->error('设置失败！');
	        }
        }
        /**
         +----------------------------------------------------------
         * Enter description here ... （方法功能的注释）
         +----------------------------------------------------------
         * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
         * @return	Array <返回类型(void的方法就不用该选项)>
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午06:22:41
         +----------------------------------------------------------
         */
		public function Set (){
	        $server=$_GET['server'];
			$config['server'] = $server;
	        if(model('Xdata')->lput('tool',$config)){
	            $this->assign('jumpUrl', U('tool/Admin/index'));
	            $this->success('设置成功！');
	        }else{
	            $this->error('设置失败！');
	        }
        }
        /**
         +----------------------------------------------------------
         * 阳光资源管理首页面
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午06:22:59
         +----------------------------------------------------------
         */
        public function index (){
        	$config   = model('Xdata')->lget('tool');
            $this->assign($config);
            $this->display();
        } 
		/**
		 +----------------------------------------------------------
		 * 存储首页
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:23:48
		 +----------------------------------------------------------
		 */
		public function Storage(){
		    $result=M('tool_storage')->findall();
			$this->assign('result',$result); 
			$config   = model('Xdata')->lget('tool');
            $this->assign($config);
			$this->display();
        } 
		/**
		 +----------------------------------------------------------
		 * 添加存储地址
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:24:03
		 +----------------------------------------------------------
		 */
	    public function addStorage(){
		   $id=intval($_GET['id']);
		   if($id)
		   {
			 $result=M('tool_storage')->where("id=$id")->find();
			 $this->assign('result',$result);		  
		     $this->assign('do','do_mody');
			 $this->assign('id',$id);  
		   }
		   $this->display();
		}
		/**
		 +----------------------------------------------------------
		 * 处理添加存储地址
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:24:22
		 +----------------------------------------------------------
		 */
		public function doStorage(){
		    $id=intval($_POST['id']);
			$data['address']=h(t($_POST['storage']));
			if($id)
			{
			 $result=M('tool_storage')->where("id=$id")->save($data);
			 if($result)
			 {
				 $this->success("修改成功！");
			 }
			 else
			 {
				 $this->error('修改失败');
			 }
			}
			else
			{
			 $data['id']=$id;
			 $res=M('tool_storage')->add($data);
			 if($res)
			 {
				 $this->success("添加成功！");
			 }
			 else
			 {
				 $this->error('添加失败');
			 }
			}
			
        } 
		/**
		 +----------------------------------------------------------
		 * 删除存储地址
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:24:47
		 +----------------------------------------------------------
		 */
		public function DelStorage(){
			$id=intval($_POST['id']);
			$res=M('tool_storage')->where("id=$id")->delete();
			 if($res)
			 {
				 echo 1;
			 }
			 else
			 {
				 echo 0;
			 }
		}
		
		
		
		/**
		 +----------------------------------------------------------
		 * 无限极分类添加页面
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:25:07
		 +----------------------------------------------------------
		 */
		public function Unlimited()
		{ 
			include_once SITE_PATH.'/addons/libs/Tree.class.php';
			$list = array();
			$tree = new tree();
			$c_list =M('')->table('ts_tool_contact c,ts_category_dictionary d')->where('c.ID=d.ID')->findall();
			foreach($c_list as $key => $clist) {
				$tree->setNode($clist['c_id'],$clist['f_id'], $clist);
			}
			$childs = $tree->getChilds();
			foreach ($childs as $key => $vid) {
				$one = $tree->getValue($vid);
				$list[] = $one;
			}
		   $this->assign('list',$list);
		   $this->display();
		}
		/**
		 +----------------------------------------------------------
		 * do无限极分类添加
		 +----------------------------------------------------------
		 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
		 * @return	Array <返回类型(void的方法就不用该选项)>
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:34:06
		 +----------------------------------------------------------
		 */
		public function doUnlimited() {
		  $f_id=intval($_POST[f_id]);
		  $DataID=intval($_POST[DataID]);
		  $DataCode=h(t($_POST[DataCode]));
		  $f_name=h(t($_POST[f_name]));
		  $DataName=h(t($_POST[DataName]));
		  if(!empty($DataID))
		  {
			$data['f_id']=$f_id;
			$data['ID']=$DataID;
			$result=D('ReContact')->where('ID='.$data['ID'])->find();
			if($result)
			{
			   $this->assign('jumpUrl', U('tool/Admin/Unlimited'));
			   $this->error( "添加分类失败,该分类已经被添加" );
			}
			else
			{
				$add=D('ReContact')->add($data);
				if($add)
				{
				  $this->assign('jumpUrl', U('tool/Admin/Unlimited'));
				  $this->success('添加分类成功');
				}
				else
				{ 
				  $this->assign('jumpUrl', U('tool/Admin/Unlimited'));
				  $this->error( "添加分类失败" );
				 }
			}
		  }
		  else
		  {
			 $map['DataDescribe']=$f_name;
			 $datatype=M('category_dictionary')->where($map)->find();
			 $variable['DataDescribe']=$f_name;
			 $variable['DataType']=$datatype['DataType'];
			 $variable['DataCode']=$DataCode;
			 $variable['DataName']=$DataName;
			 $addata=M('category_dictionary')->add($variable);
			 if($addata){
			   $option['f_id']=$f_id;
			   $option['ID']=$addata;
			   $end=D('ReContact')->add($option);
			   if($end){
				   $this->assign('jumpUrl', U('tool/Admin/Unlimited'));
				  $this->success('添加分类成功');
			   }
			   else
				{ 
				  $this->assign('jumpUrl', U('tool/Admin/Unlimited'));
				  $this->error( "添加分类失败" );
				 }
			 }
		  }
		}
		/**
		 +----------------------------------------------------------
		 * 从字典表里获取某类型下的所有信息
		 +----------------------------------------------------------
		 * @return	Array 查找到的信息
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:35:33
		 +----------------------------------------------------------
		 */
		public function Dictionary() {
			$Dname=$_POST['DataName'];
			$result = M('category_dictionary')->where("DataDescribe='$Dname'")->findall();
			echo json_encode($result);
		}
		
		
		/**
		 +----------------------------------------------------------
		 * 文件管理页面
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:38:12
		 +----------------------------------------------------------
		 */
		public function Annex(){
		    $config   = model('Xdata')->lget('tool');
		    $limitpage=$config['limitpage'];
			
		  //为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
		    if ( !empty($_POST) ) {
			    $_SESSION['tool_admin_search'] = serialize($_POST);
		    }else if ( isset($_GET[C('VAR_PAGE')]) ) {
			    $_POST = unserialize($_SESSION['tool_admin_search']);
		    }else {
			    unset($_SESSION['tool_admin_search']);
		    }
            $this->assign('isSearch', isset($_POST['isSearch'])?'1':'0');
			$where.="r.id=f.id";
			if($_POST[isSearch]==1)
			{
				
				$_POST['id']        && $where.=" and r.id =".intval($_POST['id']);
				$_POST['uid']		&& $where.= " and r.uid=".intval( t( $_POST['uid'] ) );
				$_POST['title']    && $where.=" and r.title like '%".t($_POST['title'])."%'";
            }
            $_POST['eorder']    && $order     		 =  'r.id '.t( $_POST['eorder']);
            $_POST['limit']     && $limit            =   intval($_POST['limit']);
             if(!$_POST[isSearch])
			 {
			   $limit=$limitpage;
			   $order='r.id DESC';
			 }
            $result=M('')->table('ts_tool r,ts_tool_file f')->where($where)->order($order)->findPage($limit);
		    $this->assign($_POST);
		    $this->assign($result);
		    $this->display();
		}
		/**
		 +----------------------------------------------------------
		 * 初始化编辑资源信息
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:38:48
		 +----------------------------------------------------------
		 */
		public function Editresource()
		{   $classes=M('category_dictionary')->where("`DataTyptoolurce'")->findall();
		    $this->assign('classes',$classes);
		    $edition=M('category_dictionary')->where("`DataType`='Publisher'")->findall();
		    $this->assign('edition',$edition);
			$type=M('category_dictionary')->where("`DattoolResourceType'")->findall();
		    $this->assign('type',$type);
			//专题属性
			$sattribute=M('category_dictionary')->where("`DataType`='ReSpecialAttribute'")->findall();
		    $this->assign('sattribute',$sattribute);
			//专题类型
			$stype=M('category_dictionary')->where("`DataType`='ReSpecialType'")->findall();
		    $this->assign('stype',$stype);
		    $map['id']=intval($_GET[id]);
		    $edit=D('Tool')->where($map)->find();
			$this->assign('edit',$edit);
		    $this->display('Edit');
		}
		/**
		 +----------------------------------------------------------
		 * 保存修改编辑信息
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:39:09
		 +----------------------------------------------------------
		 */
		public function doEdit(){
		   $map[id]=intval($_POST[id]);
		   $data[title]=h(t($_POST[title]));
		   $data[tags]=h(t($_POST[tags]));
		   $data['class']=$_POST['rclass'];
		   if($data['class']=='ElementaryEDU')
		   {
			   $_POST['select1']        && $data[edition]=h(t($_POST[select1]));
			   $_POST['select2']        && $data[subject]=h(t($_POST[select2]));
			   $_POST['select3']        && $data[grade]=h(t($_POST[select3]));
			   $_POST['select4']        && $data[chapters]=h(t($_POST[select4]));
			   $data[attribute]='';
			   $data[type]=h(t($_POST[type]));
		   }
		   else if($data['class']=='SpecialEDU')
		   {
			   $_POST['select5']        && $data[edition]=h(t($_POST[select5]));
			   $_POST['select6']        && $data[attribute]=h(t($_POST[select6]));
			   $data[subject]="";
			   $data[grade]="";
			   $data[chapters]="";
			   $data[type]=h(t($_POST[type]));
		   }
		   else if($data['class']=='OtherEDU')
		   {
			   $data[edition]="";
			   $data[subject]="";
			   $data[grade]="";
			   $data[chapters]="";
			   $data[attribute]="";
			   $data[type]="";
		   }
		   
		   $result=D('tool')->where($map)->save($data);
		   if($result)
		    {
			  $this->assign('jumpUrl', U('tool/Admin/Annex'));
			  $this->success('修改成功');
			}
		   else
		   {
		     $this->assign('jumpUrl', U('tool/Admin/Annex'));
			  $this->error('修改失败');
		   }
		}
		/**
		 +----------------------------------------------------------
		 * ajax异步调用数据章节
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:39:27
		 +----------------------------------------------------------
		 */
		public function doGetSelect() {
			$key = intval($_POST ['key']); // 获取要查询的键.
			$edition = $_POST ['edition']?$_POST ['edition']:'';	//获取要查询的值.
			$subject = $_POST ['subject']?$_POST ['subject']:'';	//获取要查询的值
			$grade = $_POST ['grade']?$_POST ['grade']:'';	//获取要查询的值
	
			switch($key){
				case "1":
					$field = "`Subject` as cloumn,(SELECT `DataName` FROM `ts_category_dictionary` WHERE `DataCode` = `Subject` order by DataOrder ASC) as text";
					$where = "`Publisher`='$edition'";
					$group = "`Subject`";
					$result = M('category_knowledge')->field($field)->where($where)->group($group)->order( 'CourseID ASC')->findAll();
					break;
				case "2":
					$field = "`Grade` as cloumn,(SELECT `DataName` FROM `ts_category_dictionary` WHERE `DataCode` = `Grade` order by DataOrder ASC) as text";
					$where = "`Publisher`='$edition' and `Subject` = '$subject'";
					$group = "`Grade`";
					$result = M('category_knowledge')->field($field)->where($where)->group($group)->order( 'CourseID ASC')->findAll();
					break;
				case "3":
					$field = "`CourseID` as cloumn,Course as text";
					$where = "`Publisher`='$edition' and `Subject` = '$subject' and `Grade` = '$grade'";
					$result = M('category_knowledge')->field($field)->where($where)->order( 'CourseID ASC')->findAll();
					break;
				
				case "4":
					$field = "`Grade` as cloumn,(SELECT `DataName` FROM `ts_category_dictionary` WHERE `DataCode` = `Grade` order by DataOrder ASC) as text";
					$where = "`Publisher`='$edition' and `Subject` = '$subject'";
					$group = "`Grade`";
					$result = M('category_knowledge')->field($field)->where($where)->group($group)->order( 'CourseID ASC')->findAll();
					break;
				case "5":
					$field = "`Course` as cloumn,Course as text";
					$where = "`Publisher`='$edition' and `Subject` = '$subject' and `Grade` = '$grade'";
					$result = M('category_knowledge')->field($field)->where($where)->order( 'CourseID ASC')->findAll();
					break;
				
				default:
			}
			echo json_encode($result);
		}
		/**
		 +----------------------------------------------------------
		 * 删除记录和附件操作
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午06:39:44
		 +----------------------------------------------------------
		 */
		public function doDeleteAttach() {
		if( empty($_POST['ids']) ) {
			echo 0;
			exit ;
		}
        $ids=$_POST['ids'];
		$_LOG['uid'] = $this->mid;
		$_LOG['type'] = '2';
		$data[] = '资源库 - 附件管理 ';
		$map['id'] = array('in',t($_POST['ids']));
		$data[] = model('Attach')->getAttachByMap($map);
		$data[] = array('isFile'=>intval($_POST['withfile']));
		$_LOG['data'] = serialize($data);
		$_LOG['ctime'] = time();
		M('AdminLog')->add($_LOG);
		$map['attach_id'] = $map['id'];
		unset($map['id']);
		$weibo = M('weibo_attach')->where($map)->findAll();
		unset($map['attach_id']);
		foreach ($weibo as $v) {
			$weibo_id[] = $v['weibo_id'];
		}
		$weibo_id = implode(',', $weibo_id);
		$map['weibo_id'] = array('in',$weibo_id);
		M('weibo')->where($map)->delete();
		$option['id'] = array('in', $ids);
		$result=D('toolFile')->field('saveaddress,savepath,savename,swffile')->where($option)->select();
		$resultFile=D('toolFile')->where($option)->delete();
		$resultData=D('tool')->where($option)->delete();
		D('ResDown')->where($option)->delete();
		D('ResScore')->where($option)->delete();
		if($resultFile && $resultData){
			if(intval($_POST['withfile'])==1)
			{
			  foreach($result as $key=>$obj){
				  if($obj['swffile']){
					  $url=$obj['saveaddress'].'upload.php?do=del'.'&path='.$obj['savepath'].$obj['savename'].'&swfpath='.$obj['savepath'].$obj['swffile'];
				  }else{
					  $url=$obj['saveaddress'].'upload.php?do=del'.'&path='.$obj['savepath'].$obj['savename'];
				  }
                  $html=file_get_contents($url);
				  if($html==0)
				  {echo 0;exit;}
			  }
				echo 1;
			}
			else
			 echo 1;
		}else{
		   echo 0;
		}
		
	}
	
	
	
	/**
	 +----------------------------------------------------------
	 * 文件类型管理
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:40:00
	 +----------------------------------------------------------
	 */
	public function uploadtype(){
		$result=M('tool_type')->findall();
		$this->assign('result',$result);
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 添加文件类型页面
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:40:17
	 +----------------------------------------------------------
	 */
	public function addType(){
		
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 添加文件类型
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:40:37
	 +----------------------------------------------------------
	 */
	public function editType(){
		$map[id]=$_GET['id'];
		$res=M('tool_type')->where($map)->find();
		$this->assign('res',$res);
		$this->display('addType');
	}
	/**
	 +----------------------------------------------------------
	 * 处理添加文件类型请求
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:40:52
	 +----------------------------------------------------------
	 */
	public function doaddType(){
	  $data['exttype']=h(t($_POST['exttype']));
	  $data['remark']=h(t($_POST['remark']));
	  $map['id']=h(t($_POST['id']));
	  if(!$map['id'])
	  {
		  $res=M('tool_type')->add($data);
		  if($res)
			{
			  $this->assign('jumpUrl', U('tool/Admin/uploadtype'));
			  $this->success('添加成功');
			}
		   else
		   {
			 $this->assign('jumpUrl', U('tool/Admin/uploadtype'));
			  $this->error('添加失败');
		   }
	  }else
	  {
		  $res=M('tool_type')->where($map)->save($data);
		  if($res)
			{
			  $this->assign('jumpUrl', U('tool/Admin/uploadtype'));
			  $this->success('修改成功');
			}
		   else
		   {
			 $this->assign('jumpUrl', U('tool/Admin/uploadtype'));
			  $this->error('修改失败');
		   }
	  }
	  
	}
	/**
	 +----------------------------------------------------------
	 * 删除上传文件类型
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:41:39
	 +----------------------------------------------------------
	 */
	public function delType(){
		if( empty($_POST['ids']) ) {
			echo 0;
			exit ;
		}
        $ids=$_POST['ids'];
		$option['id'] = array('in', $ids);
		$result=M('tool_type')->where($option)->delete();
		if($result)
		{
		   echo 1;
		}
		else
		{
		   echo 0;
		}
	}
}
?>