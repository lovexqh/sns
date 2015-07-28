<?php
/**
 * 班级空间
 */
class AdminAction extends Action
{
	private $path;
	public function _initialize() {
		$this->path = UPLOAD_PATH.'/glory/';
		$this->assign('picPath',SITE_URL.'/data/uploads/glory/');
	}
	
	/*
	 班级空间设置
	*/
	function classManage() {
		
		$this->display();
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
		$types = $class->where($where)->findPage(10);
		return $types;
	}
	
	/**
	 +----------------------------------------------------------
	 * 每周之星类型查看界面
	 +----------------------------------------------------------
	 * @author	美美2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 上午12:47:47
	 +----------------------------------------------------------
	 */
	 
	public function glory(){
		//查找每周之星类型
		$weekStarType = $this->_weekStarType();
		$this->assign('weekStarType', $weekStarType);
		$this->display();
	}
	

	
	
	/**
	 +----------------------------------------------------------
	 * 荣誉类型保存界面
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-27 下午2:49:11
	 +----------------------------------------------------------
	 */
	function saveGlory() {
		$gloryid = $_REQUEST['gloryid'];
		if(!empty($gloryid)) {
			$type = M('class_weekstar')->where("type = 1 and id=".$gloryid)->find();
			$pics = M('')->table(C('DB_PREFIX') . "class_weekstar_pic")
			->where("gloryid=$gloryid")
			->select();
			$this->assign("type",$type);
			$this->assign("pics",$pics);
		}
		$this->display("add_glory");
	}
	/**
	 +----------------------------------------------------------
	 * 荣誉类型保存
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-27 下午2:49:11
	 +----------------------------------------------------------
	 */
	function typeSave() {
		//print_r($_REQUEST);
		//检查数据
		$result = -1;
		$name = $_REQUEST['name'];
		if($name==null||$name.trim()=='') {
			$this->error("没有设置荣誉姓名！");
		}
		$type = $_REQUEST['type'];
		if($type==null||$type.trim()=='') {
			$this->error( "没有选择荣誉类型！");
		}
		$model = M('');
		$model->startTrans();
		$filesInfo = $this->_upload();
		if(!is_array ($filesInfo)) {
			$model->rollback();
			$this->error($filesInfo);
		}
		//设置保存数据
		$gloryid = $_REQUEST['gloryid'];
		$data['name'] = $name;
		$data['type'] = $type;
		$data['comment'] = $_REQUEST['comment'];
		$data['pic'] = $filesInfo[0]['savename'];
		//判断并保存荣誉类型
		if(empty($gloryid)) {
			//没有gloryid添加
			$result = M('')
			->table(C('DB_PREFIX') . "class_weekstar")
			->data($data)
			->add();
			if(!$result){
				$model->rollback();
				$this->_delFile($filesInfo);
				$this->error("保存失败！");
			}
			$gloryid = $result;
		}else{
			//有gloryid更新
			$result = M('')
			->table(C('DB_PREFIX') . "class_weekstar")
			->where("id=$gloryid")
			->data($data)
			->save();
			if(!$result){
				$model->rollback();
				$this->_delFile($filesInfo);
				$this->error("保存失败！");
			}
		}
		//检查等级图片相关数据
		$picidList = $_REQUEST['id'];
		$commentList = $_REQUEST['describe'];
		$piclvlList = $_REQUEST['piclvl'];
		if(empty($piclvlList)||$piclvlList==0) {
			$model->rollback();
			$this->_delFile($filesInfo);
			$this->error("保存失败！");
		}
		$picradixList = $_REQUEST['picradix'];
		if(empty($picradixList)||$picradixList==0) {
			$model->rollback();
			$this->_delFile($filesInfo);
			$this->error("保存失败！");
		}
		if(count($piclvlList)!=count($picradixList)) {
			$model->rollback();
			$this->_delFile($filesInfo);
			$this->error("保存失败！");
		}
		if(count($piclvlList)!=(count($filesInfo)-1)) {
			$model->rollback();
			$this->_delFile($filesInfo);
			$this->error("保存失败！");
		}
		//保存列表
		for($i=0;$i<count($piclvlList);$i++) {
			unset($data);
			$data['gloryid']=$gloryid;
			$data['lvl']=$piclvlList[$i];
			$data['radix']=$picradixList[$i];
 			$data['comment']=$commentList[$i];
			$data['pic']=$filesInfo[$i+1]['savename'];
			$picid = $picidList[$i];
			if(empty($picid)) {
				//没有picid添加
				$result = M('')
				->table(C('DB_PREFIX') . "class_weekstar_pic")
				->data($data)
				->add();
				if(!$result){
					$model->rollback();
					$this->_delFile($filesInfo);
					//print_r($data);
					$this->error("保存失败！");
				}
			}else{
				//有picid更新
				$result = M('')
				->table(C('DB_PREFIX') . "class_weekstar_pic")
				->where("id=$picid")
				->data($data)
				->save();
				if(!$result){
					$model->rollback();
					$this->_delFile($filesInfo);
					$this->error("保存失败！");
				}
			}
		}
		//提交保存
		$model->commit();
		header('Location:'.$_SERVER['HTTP_REFERER']);
	}
	
	/**
	 +----------------------------------------------------------
	 * 删除图片
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-27 下午2:49:11
	 +----------------------------------------------------------
	 */
	private function _delFile($fileList) {
		for($i=0;$i<count($fileList);$i++) {
			$file = $this->path.$fileList[$i]['savename'];
			if(is_file($file)) {
				unlink ($file);
			}
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 删除荣誉类型和服务器上的图片
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-27 下午2:49:11
	 +----------------------------------------------------------
	 */
	function typeDel() {
		//开始一个事务
		$model = M('');
		$model->startTrans();
		$result = -1;
		//检查数据
		if( empty($_REQUEST['gloryid']) ) {
			$this->ajaxReturn(-1, "没有获取记录ID！", 0);
		}
		//删除服务器上的图片
		$id = $_REQUEST['gloryid'];
		$type = M('class_weekstar')->where("type = 1 and id=".$id)->find();
	
		$file = $this->path.$type['pic'];
		if(is_file($file)) {
			if(!unlink ($file)) {
				$model->rollback();
				$this->ajaxReturn($result, "删除失败！", 0);
			}
		}
		//删除class_weekstar表上的数据
		$result = M('')
		->table(C('DB_PREFIX') . "class_weekstar")
		->where("id=".$id)
		->delete();
		if(!$result) {
			$model->rollback();
			$this->ajaxReturn($result, "删除失败！", 0);
		}
		//删除等级图片和class_weekstar_pic上的记录
		$pics = M('')->table(C('DB_PREFIX') . "class_weekstar_pic")
		->where("gloryid=$id")
		->findAll();
		//print_r($pics);
		if(empty($pics)) {
			$model->commit();
			$this->ajaxReturn($result, "删除成功！", 1);
		}
		for($i=0;$i<count($pics);$i++) {
			$file = $this->path.$pics[$i]['pic'];
			if(is_file($file)) {
				if(!unlink ($file)) {
					$model->rollback();
					$this->ajaxReturn($result, "删除失败！", 0);
				}
			}
			$result = M('')
			->table(C('DB_PREFIX') . "class_weekstar_pic")
			->where("id=".$pics[$i]['id'])
			->delete();
			if(!$result) {
				$model->rollback();
				$this->ajaxReturn($result, "删除失败！", 0);
			}
		}
		$model->commit();
		$this->ajaxReturn($result, "删除成功！", 1);
	}
	
	/**
	 +----------------------------------------------------------
	 * 权限查看界面
	 +----------------------------------------------------------
	 * @author	美美2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 上午12:47:47
	 +----------------------------------------------------------
	 */
	public function Power(){
		$manager = M('class_manager')->findAll();
		$this->assign('manager',$manager);
		$this->display('power');
	}
	/**
	 +----------------------------------------------------------
	 * 班干部查看界面
	 +----------------------------------------------------------
	 * @author	美美2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 上午12:47:47
	 +----------------------------------------------------------
	 */
	public function Leader(){
		$manager = M('class_position')->findAll();
		$this->assign('manager',$manager);
		$this->display('leader');
	}
	/**
	 +----------------------------------------------------------
	 * 增加、修改权限跳转
	 +----------------------------------------------------------
	 * @author	美美2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 上午12:47:47
	 +----------------------------------------------------------
	 */
	public function edit_power_tab(){
		if(!empty($_REQUEST['gid'])){
			$id = $_REQUEST['gid'];
			$result = M('class_manager')->where('id='.$id)->find();
			$this->assign('manage',$result);
		}
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 增加修改权限
	 +----------------------------------------------------------
	 * @author	美美2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 上午12:47:47
	 +----------------------------------------------------------
	 */
	public function EditPower(){
		$map['id'] = $_POST['id'];
		$map['typeName'] = $_POST['name'];
		$map['userType'] = $_POST['type'];
		if(!empty($map['typeName'])&&!empty($map['userType'])){
			if(!empty($map['id'])){
				$result = M('class_manager')->save($map);
			}else {
				$result = M('class_manager')->add($map);
			}
			if(!empty($result)){
				echo 1;
			}else {
				echo 0;
			}
		}else {
			echo -1;
		}
	}
	/**
	 +----------------------------------------------------------
	 * 删除权限信息
	 +----------------------------------------------------------
	 * @author	美美2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 上午01:32:37
	 +----------------------------------------------------------
	 */
	public function DelPower(){
		$id = $_POST['id'];
		$result = M('class_manager')->where('id='.$id)->delete();
		if(!empty($result)){
			echo 1;
		}else {
			echo 0;
		}
	}
/**
	 +----------------------------------------------------------
	 * 增加、修改班干部跳转
	 +----------------------------------------------------------
	 * @author	美美2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 上午12:47:47
	 +----------------------------------------------------------
	 */
	public function edit_Leader_tab(){
		if(!empty($_REQUEST['gid'])){
			$id = $_REQUEST['gid'];
			$result = M('class_position')->where('id='.$id)->find();
			$this->assign('position',$result);
		}
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 增加修改权限
	 +----------------------------------------------------------
	 * @author	美美2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 上午12:47:47
	 +----------------------------------------------------------
	 */
	public function EditLeader(){
		$map['id'] = $_POST['id'];
		$map['position'] = $_POST['position'];
		if(!empty($map['position'])){
			if(!empty($map['id'])){
				$result = M('class_position')->save($map);
			}else {
				$result = M('class_position')->add($map);
			}
			if(!empty($result)){
				echo 1;
			}else {
				echo 0;
			}
		}else {
			echo -1;
		}
	}
	/**
	 +----------------------------------------------------------
	 * 删除权限信息
	 +----------------------------------------------------------
	 * @author	美美2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 上午01:32:37
	 +----------------------------------------------------------
	 */
	public function DelLeader(){
		$id = $_POST['id'];
		$result = M('class_position')->where('id='.$id)->delete();
		if(!empty($result)){
			echo 1;
		}else {
			echo 0;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 执行单图上传操作
	 +----------------------------------------------------------
	 * @author 董良
	 +----------------------------------------------------------
	 * 创建时间：2013-4-27 下午2:49:11
	 +----------------------------------------------------------
	 */
	private function _upload() {
		if(!is_dir($this->path)){
			if(!mkdir($this->path)) {
				return '目录创建失败: '.$this->path;
			}
		}
		//		import('ORG.Net.UploadFile');
		include VENDOR_PATH.'libs/UploadFile.class.php';
		$upload = new UploadFile();// 实例化上传类
		//上传参数
		//设置上传文件大小
		$upload->maxSize = '2000000' ;
	
		//设置上传文件类型
		$upload->allowExts = explode(',',strtolower('jpg,gif,png,jpeg,bmp'));
	
		//设置上传路径
		$upload->savePath = $this->path;
		//保存的名字
		$upload->saveRule = 'uniqid';
	
		//执行上传操作
		if(!$upload->upload()) {
			//捕获上传异常
			return $upload->getErrorMsg();
		}else{
			//上传成功
			return $upload->getUploadFileInfo();
		}
	}
	
}