<?php
/**
 +------------------------------------------------------------------------------
 * 我的资源
 +------------------------------------------------------------------------------
 * @category	应用工具 （应用名称）
 * @package		ESN （ Lib/Action）
 * @author		liman<manli@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-8-12 上午10:57
 +------------------------------------------------------------------------------
 */
class IndexAction extends BaseAction {
	private $config;
    /**
     +----------------------------------------------------------
     * 初始化
     +----------------------------------------------------------
     * @author	liman
     +----------------------------------------------------------
     * 创建时间：	2013-8-12 上午10:57
     +----------------------------------------------------------
     */
    public function _initialize() {
		parent :: _initialize();
		$this->config = getConfig();
    }
	 //资源首页
    public function index() {
    	//2013/06/01美美修改支持自动判断是否大桌面调用并调用相应组的模板 start
        if (!empty($_SESSION['system']) && $_SESSION['system'] == 'desktop') {
        	$this->my();
        	exit;
        }
	}
	
	/**
	  +----------------------------------------------------------
	  * 我的资源
	  +----------------------------------------------------------
	  * @author	美美2013-3-1
	  +----------------------------------------------------------
	  * 创建时间：	2013-3-1 上午06:42:45
	  +----------------------------------------------------------
	*/
    public function my() {
	   $map['uid']=$this->mid;
		$order='t.time desc,t.downCount desc,t.readCount desc';
	    $mylist = M()
	    		->table( C('DB_PREFIX').'tool t')
	    		->join(C('DB_PREFIX').'square_category category on t.section = category.id')
			    ->field('t.*,category.category_name')
			    ->where($map)
			    ->order($order)
			    ->findPage($this->config['limitpage']);
	 // echo M()->getLastSql();exit();
		$this->assign('mylist',$mylist);
		//统计我一共下载的次数
		$count=$this->Count();
		//dump($count);exit();
		$this->assign('count',$count);
		$this->display();
    }
	/**
	 +----------------------------------------------------------
	 * 我要上传
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:43:04
	 +----------------------------------------------------------
	 */
	public function myupload() {
	 	$config = model('Xdata')->lget('tool');
	 //	dump($config);exit();
		$this->assign('con',$config);
// 		//专题资源属性
// 		$Attribute=M('category_dictionary')->where("`DataType`='ReSpecialAttribute'")->findall();
// 		$this->assign('Attribute',$Attribute);
		$type=M('tool_type')->findall();//允许上传的gongju格式
		$this->assign('type',$type); 
		if(service('SystemPopedom')->hasPopedom($this->mid, 'admin/*/*', false)){
			$sqcategory = procExecute("call vcCategory(4);");//查找类别是工具(4)的所有分类
			//屏蔽“工具”大类
			$len = count( $sqcategory );
			for( $i=0;$i<$len; $i++){
			    if( $sqcategory[$i]['id'] == "4" ){
			         unset( $sqcategory[$i] );
			    }
			}
			}else{
				$sqcategory=procExecute("call vcCategory(41);");
			}
		$this->assign('sqcategory',$sqcategory);
	 	//基础资源树状结构
		$data=model('Knowledge')->getTreeArray();
		$this->assign('treenode',$data);
		 
		$this->display();
    }
	
	/**
	 +----------------------------------------------------------
	 * 初始化编辑
	 +----------------------------------------------------------
	 * @author	liman 2013-8-15
	 +----------------------------------------------------------
	 */
	public function edit() {		
		$id=$_GET['id'];
		$result=D('Tool','tool')->where('id='.$id)->select();
		//dump($result);
		if(service('SystemPopedom')->hasPopedom($this->mid, 'admin/*/*', false)){
			$sqcategory = procExecute("call vcCategory(4);");
			//屏蔽“工具”大类
			$len = count( $sqcategory );
			for( $i=0;$i<$len; $i++){
				if( $sqcategory[$i]['id'] == "4" ){
					unset( $sqcategory[$i] );
				}
			}
		}else{
			$sqcategory = procExecute("call vcCategory(41);");
		}
		$this->assign('sqcategory',$sqcategory);
		$this->assign('result',$result[0]);
		//dump($result[0]);exit();
		$this->display();
    }
	
	/**
	 +----------------------------------------------------------
	 * 处理上传工具
	 +----------------------------------------------------------
	 * @author	李嫚
	 +----------------------------------------------------------
	 * 创建时间：	2013-8-15
	 +----------------------------------------------------------
	 */
	public function domyupload() {
	    $title = h(t($_POST['title']));
	 	if(empty($title) ||  mb_strlen($title, 'UTF-8') > 50) {
			$info['info']='标题不能为空或者大于50个字符';
			$info['status']=0;
			echo json_encode($info);
			exit;
        }
        
        
		$data = $this->__getDataPost();
		$data['time'] = time();
		$data['type']='public';
		
		$map['id']=$data['section'];
		$result=M('')->table('ts_square_category')->where($map)->find();
		$classtemp=explode("-",$result['path']);
		if($classtemp[2]==42){
			$data['type']="official";//官方
		}
		$data['class']=$classtemp[3];//将path分割，获取器父类id(比如：0-4-41-2017，获取2017)
// 		if($data['class']){
// 			$data['class']=41;
// 		}
		$where['id']=$data['class'];
		$res=M('')->table('ts_square_category')->where($where)->find();
		$data['class_name']=$res['category_name'];//新增class_name为广场中导航栏分类不重复做准备
		//dump($data);
		$add = D('Tool','tool')->add($data);
// 		echo M()->getLastSql();
// 		exit();
	 	if($add){
		  $option = $this->__getPost();
		  $option['id'] = $add;
		  //$option['saveaddress'] = $this->config['server'];
          $option['saveaddress'] = __UPLOAD__.'/';
		  $option['time'] = time();
		  
		  $options ['userId'] = $this->mid;		  
		  $options ['max_size'] = 2 * 1024 * 1024; // 2MB
		  $options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
		  $info1 = X ( 'Xattach' )->upload ( 'tool_logo', $options );
		  //dump($info1);//上传图片
		  //exit();
		  if ($info1 ['status']) {
		  	$option['thumb'] = $info1 ['info'] [0] ['savepath'] . $info1 ['info'] [0] ['savename'];
		  } else {
		  /* 	$info['info']='请输入正确格式的Logo';
		  	$info['status']=0;
		  	echo json_encode($info);
		  	exit; */
		  	$option ['thumb'] ='tooldefault.png';
		  }
		  
		  $toolfile_res= D('ToolFile','tool')->add($option);
		  
		  if(!empty($toolfile_res)){
		  	  echo json_encode(array('title'=>$title,'id'=>$add,'status'=>1,'info'=>'工具上传成功'));
		  }else{
		  	 $res=D('Tool','tool')->where("id=".$add)->delete();
		  	 echo json_encode(array('status'=>0,'info'=>'工具上传失败'));
		  	 exit();
		  }
		 
		}else{
			echo json_encode(array('status'=>0,'info'=>'工具上传失败'));
		}  
		
    }
	
	/**
	 +----------------------------------------------------------
	 * 保存资源重新编辑信息
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:43:20
	 +----------------------------------------------------------
	 */
	public function doedit() {
		$map['id']=$_POST['id'];
	    $title = h(t($_POST['title']));
		if(empty($title) ||  mb_strlen($title, 'UTF-8') > 50) {
			echo json_encode(array('status'=>0,'info'=>'标题不能为空或者大于50个字符'));
			exit;
        }
		$data = $this->__getDataPost();
		$data['type']='public';
		$map1['id']=$data['section'];
		$result=M('')->table('ts_square_category')->where($map1)->find();
		$classtemp=explode("-",$result['path']);
		if($classtemp[2]==42){
			$data['type']="official";//官方
		}
		$data['class']=$classtemp[3];//将path分割，获取器父类id(比如：0-4-41-2017，获取2017)
		$where['id']=$data['class'];
		$res=M('')->table('ts_square_category')->where($where)->find();
		$data['class_name']=$res['category_name'];//新增class_name为广场中导航栏分类不重复做准备
		$res=D('Tool','tool')->where($map)->save($data);
		if(!empty($res)){
		  echo json_encode(array('title'=>$title,'id'=>$add,'status'=>1,'info'=>'编辑成功'));
		}else{
		  echo json_encode(array('status'=>0,'info'=>'编辑失败'));	
		}
    }
	
	/**
	 +----------------------------------------------------------
	 *我收藏的资源
	 +----------------------------------------------------------
	 * @author	美美2013-6-6
	 +----------------------------------------------------------
	 * 创建时间：	2013-6-6 上午01:09:58
	 +----------------------------------------------------------
	 */
	public function favorite(){
		//list_favorite_all 有两个参数 第一个为要获取的应用名称，第二个为每页显示多少条
    	$data =model('Favorite')->list_favorite_all('tool',$this->config['limitpage']);
    	//获取已经得到的视频ID信息
    	foreach ($data['data'] as $v){
    		$ids[] = $v['fid'];
    	}	
    	//扩展本应用中收藏列表所需的字段详细信息
    	$map['t.id'] = Array('in',$ids);
    	//将查询到的数据再次写入data下标数组中，从tool表里获取软件的详细信息
    	$data['data'] = M('tool t')
  			    	->join('ts_square_category category ON t.class=category.id')
 			    	->field('t.*,category.category_name')
			    	->where($map)
			    	->findAll();
    	//dump($data);
    	//echo M()->getLastSql();
    	$this->assign('data',$data);
    	$this->display('collect');
	}
	
 	/**
	  +----------------------------------------------------------
	  * 系统推荐资源
	  +----------------------------------------------------------
	  * @return	Array 热门资源信息
	  * @author	美美2013-6-6
	  +----------------------------------------------------------
	  * 创建时间：	2013-6-6 上午06:46:20
	  +----------------------------------------------------------
	  */
	 public function hostlist(){
	 	$uid=$this->mid;
		  $data=D('Tool','tool')->gethostlist($uid);
		  
		  $this->assign('data',$data);
	      $this->display();
	 }
	
	/**
	 +----------------------------------------------------------
	 * 下载信息列表
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:44:48
	 +----------------------------------------------------------
	 */
	public function down(){
		
		$result=M('')
				->table('ts_tool_down d,ts_tool t,ts_square_category category')
				->field('t.*,category.category_name,t.downCount,d.d_id')
				->where("d.id=t.id and d.uid=$this->uid and category.id=t.section")
				->group('d.id')
				->findPage(15);//此处一定要获取down表中的d_id,html页面要获取这个d_id,用于删除所选信息
		$this->assign('result',$result);
		//统计
		$count=$this->Count();
		$this->assign('count',$count);
		$this->display('down');
	}
	/**
	 +----------------------------------------------------------
	 * 删除下载
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:45:02
	 +----------------------------------------------------------
	 */
	public function deldown()
	{
		$map['id']		=	t($_REQUEST['id']);
		$result	=	D('ToolDown')->deleteres($map['id']);
		if($result){
			//删除成功
			if ( !strpos($_REQUEST['id'],",") ){
                echo 2;exit;         //说明只是删除一个
            }else{
                echo 1;exit;            //删除多个
            }
		}else{
			//删除失败
			echo "0";exit;
		}
		
	}
	
	/**
	 +----------------------------------------------------------
	 * 处理resource表插入信息
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:45:18
	 +----------------------------------------------------------
	 */
private function __getDataPost() {
		$data['title']     = !empty($_POST['title']) ?text($_POST['title']):"无标题";
		
		$data['uid']     = $this->mid;
		//$data['tags']     = h(t($_POST['tags']));
		$data['price']     =$_POST['price'];
		$data['content']  =$_POST['content'];
		//dump($data);
		$data['type'] = h(t($_POST['type']));//public/official
		$data['section'] = h(t($_POST['section']));//树的分类，学段
		//$data['version']=$_POST['version'];
		
		return $data;
     }
     
	 /**
	  +----------------------------------------------------------
	  * 处理tool_file表插入信息
	  +----------------------------------------------------------
	  * @author	美美2013-3-1
	  +----------------------------------------------------------
	  * 创建时间：	2013-3-1 上午06:45:32
	  +----------------------------------------------------------
	  */
	 private function __getPost() {
		$option['savepath']     = h(t($_POST['savepath']));
		$option['savename']     = h(t($_POST['savename']));
		$option['savetype']     = h(t($_POST['savetype']));
		$option['toolsize'] = h(t($_POST['toolsize']));
		$option['filecode']     = h(t($_POST['filecode']));
		$option['uid']     = $this->mid;
		return $option;
     }
	 
	 /**
	  +----------------------------------------------------------
	  * 统计数据
	  +----------------------------------------------------------
	  * @author	美美2013-3-1
	  +----------------------------------------------------------
	  * 创建时间：	2013-3-1 上午06:45:49
	  +----------------------------------------------------------
	  */
	 public function Count(){
		 $data['upload']=D('Tool')->where("uid=$this->mid")->count();
		 $data['down']=D('ToolDown')->where("uid=$this->mid")->count();
		 return $data;
	 }
	 
	/**
	 +----------------------------------------------------------
	 * 展示页面,新桌面添加
	 +----------------------------------------------------------
	 * @author	美美2013-6-3
	 +----------------------------------------------------------
	 * 创建时间：	2013-6-3 上午06:05:44
	 +----------------------------------------------------------
	 */
	public function showtool(){
	    $id = $_GET['id'];
	    $result = M('')
	    ->table('ts_tool t')
	    ->join('ts_square_category c ON t.section=c.id')
	    ->join('ts_tool_file f on t.id=f.id')
	    ->field('t.*,f.toolsize as toolsize,c.category_name as category')
	    ->where('t.id='.$id)
	    ->find();
	   // echo M()->getLastSql();exit();
	   // dump($id);
	   $result['readCount'] = $result['readCount']+1;
		$vNa = M('tool')->where("id = $id")->save($result);
		
		
	//	echo M()->getLastSql();
		//dump($result);exit();
	    $this->assign('result',$result);
	  	$this->display();	
	}
	
	 /**
	  +----------------------------------------------------------
	  * 资源搜索
	  +----------------------------------------------------------
	  * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	  * @author	美美2013-6-6
	  +----------------------------------------------------------
	  * 创建时间：	2013-6-6 上午09:33:19
	  +----------------------------------------------------------
	  */
	public function search(){
		$tags = $_REQUEST['tags'];
		$where = "title like '%".$tags."%'";
		//$where = service('ForeAdmin')->getAuditStatus($where, 0, 'tool');
		$data = M('tool t')
			->join('ts_square_category category ON t.class=category.id')
			->field('t.*,category.category_name')
			->where($where)
			->order('downCount DESC')
			->findPage(10);
		//echo M()->getLastSql();
		$this->assign('data',$data);
		$this->display('hostlist');
	}  
	/**
	 +----------------------------------------------------------
	 * 下载信息详情
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:38:37
	 +----------------------------------------------------------
	 */
	public function downdetil(){
		$id = intval($_GET['id']);
		//echo $id;
		$result = M('')->table("".C('DB_PREFIX')."tool t,".C('DB_PREFIX')."tool_file f")->where("t.id=$id and t.id=f.id")->find();
		$this->assign('result',$result);
		
		$money = X('Credit')->getUserCredit($this->mid);
		$moneyType = getConfig('credit');
		$this->assign('money',$money[$moneyType]);
		$this->display();
	}
	
	/**
	 +----------------------------------------------------------
	 * 下载
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:40:10
	 +----------------------------------------------------------
	 */
	public function dodown(){
		$mid = $this->mid;
		$map['id'] = $_GET['id'];
		
		$data = D('Tool')->where($map)->find();
	
		$result = D('ToolDown')->setCredit($mid,$data['uid'],$data['price'],$map['id']);  
		if($result==true){
			$resfile  = M('tool_file')->where($map)->find();
			$filepath = $resfile['saveaddress']."/".$resfile['savepath'].$resfile['savename'];
			$filename = $resfile['name'];
			$size = $resfile['toolsize'];
			file_down($filepath,$filename,$size);
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 删除资源
	 +----------------------------------------------------------
	 * @author	小朱
	 +----------------------------------------------------------
	 * 创建时间：	2013-7-31 上午03:40:10
	 +----------------------------------------------------------
	 */
	public function delResource(){
		$ids['id'] = array( 'in',explode(',',$_POST['id']));
		$return=D('Tool')->delResource($ids);
		if($return)
			echo 1;
		else
			echo 2;
	}
	
	/**
	 +----------------------------------------------------------
	 * 批量上传
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:43:04
	 +----------------------------------------------------------
	 */
// 	public function myuploadbatch() {
// 		$config = model('Xdata')->lget('resource');
// 		$con=M('resource_storage')->field('address')->where("id=$config[server]")->find();
// 		$this->assign('con',$con);
// 	    //获取分类信息
// 		$classid=M('category_dictionary')->where("`DataType`='Resource'")->findall();
// 		$this->assign('classid',$classid);
// 	    //获取版本信息
// 		$edition=M('category_dictionary')->where("`DataType`='Publisher'")->findall();
// 		$this->assign('edition',$edition);
// 		//基础资源类型
// 		$Basicstype=M('category_dictionary')->where("`DataType`='ResourceType'")->findall();
// 		$this->assign('Basicstype',$Basicstype);
// 		//专题资源类型
// 		$Specialtype=M('category_dictionary')->where("`DataType`='ReSpecialType'")->findall();
// 		$this->assign('Specialtype',$Specialtype);
// 		//专题资源属性
// 		$Attribute=M('category_dictionary')->where("`DataType`='ReSpecialAttribute'")->findall();
// 		$this->assign('Attribute',$Attribute);
		
// 		$type=M('resource_type')->findall();
// 		$this->assign('type',$type);
		
// 		$category = procExecute("call vcCategory(".$id.");");
// 		$this->assign('category',$category);
		
// 		$sqcategory = procExecute("call vcCategory(4);");
// 		$this->assign('sqcategory',$sqcategory);
// 		$data=model('Knowledge')->getTreeArray();
// 		$this->assign('treenode',$data);
		
// 		$this->display();
//     }
    
	/**
	 +----------------------------------------------------------
	 * 处理上传资源数据
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午06:43:20
	 +----------------------------------------------------------
	 */
	public function dobatchmyupload() {
		//将数据整理为数组形式
		$data = $this->__getDataPost();
		$data['time'] = time();	
		$data['state'] = service('ForeAdmin')->isAuditApp() ? '0' : '1';
		$option=array();
	    //查询当前存储服务器地址
	    $config = model('Xdata')->lget('resource');
	    $con=M('resource_storage')->field('address')->where("id=$config[server]")->find();
	    $option['saveaddress'] = $con['address'];
		
		$names=$_POST['name'];
		$savepaths=$_POST['savepath'];
		$savenames=$_POST['savename'];
		$savetypes=$_POST['extension'];
		$resourcesizes=$_POST['size'];
		$thumbs=$_POST['thumb'];
		$swffiles=$_POST['swffile'];
	 	foreach($names as $key=>$val){
	 	  $info = pathinfo($names[$key]);
		  $suffix = strtolower($info['extension']);
		  $name = basename($names[$key],'.'.$suffix);
	 	  $data['info'] = $data['tags']	= $data['title'] 	  = basename($name,'.'.$suffix);
		  $data['uid']     = $this->mid;
		  $add = D('Resource')->add($data);
		  $option['id'] = $add;
		  $option['name']     		= $names[$key];
		  $option['savepath']     	= $savepaths[$key];
		  $option['savename']     	= $savenames[$key];
		  $option['savetype']     	= $savetypes[$key];
		  $option['resourcesize']   = $resourcesizes[$key];
		  $option['thumb']     		= $thumbs[$key];
		  $option['swffile']     	= $swffiles[$key];
		  $option['uid']     		= $this->mid;
		  $option['time'] 			= time();
		  D('ResourceFile')->add($option);
	    }
		echo json_encode(array('title'=>$name,'id'=>$add,'status'=>1));
    }

    public function doupload(){
        $options['userId']		=	$this->mid;
        $options['allow_exts']	=	array('gif', 'jpg', 'png', 'zip', 'rar', 'txt', 'doc', 'pdf','docx','xls','xlsx','ppt','pptx','doc');
        $options['max_size']    =   2000000000;

        $info	=	X('Xattach')->upload('attach',$options);

        if($info['status']){
            //{"status":true,"info":[{"attach_type":"attach","userId":null,"name":"goagent.zip","type":"application\/octet-stream","size":4853097,"extension":"zip","hash":"f601b0144d23761c4b0fce1f5b25d382","savepath":"tool\/2014\/0806\/11\/","savename":"53e19d93dcfb6.zip","uploadTime":1407294867,"id":2599,"key":"file"}]}
            //{"name":"goagent.zip","type":"application/octet-stream","size":"4853097","ext":".zip","savename":"47ac8f590ea5jy97.zip","filecode":"47ac8f590ea5jy97","webpath":"resource/2014/8/6/10/14/","convertAble":"1","result":"true","info":"上传成功","status":"1"}
            $obj = $info['info'][0];
            $result = array(
                'name' => $obj['name'],
                'type' => $obj['type'],
                'size' => $obj['size'],
                'ext' =>  '.'.$obj['extension'],
                'savename' =>  $obj['savename'],
                'filecode' =>  '0',
                'webpath' =>  $obj['savepath'],
                "convertAble" =>1,
                "result" =>true,
                "info" => "上传成功",
                "status" =>1
            );
            //上传成功
            echo json_encode($result);
        }else{
            $result = array(
                "status" =>0
            );
            //上传出错
            echo json_encode($result);
        }
    }
}
?>
