<?php
class DocumentAction extends Action {
	function _initialize() {
		
	}
	
	//文档
	public function index(){
		$clubid = $_GET['id'];
		if(!$clubid){
			$this->error('社团错误!');
		}
		if(!isClubMember($this->mid, $clubid)){
			$this->error('您还不是该社团成员!');
		}
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		$clubDocument = D('ClubDocument');
		//文档列表
		$fileList = $clubDocument->getFileList($clubid);
		//dump($fileList);exit;
		//文档数
		$fileCount = $clubDocument->getFileCount($clubid);
		if(!$fileCount){
			$fileCount = 0;
		}
		
		//已使用空间
		$usedSpace = $clubDocument->getUsedSpace($clubid);
		//$this->assign('usedRate', ($usedSpace/($this->config['spaceSize']*1024*1024)));  //空间使用率
		
		//获取当前用户是否具有上传和下载文档的权限
		$updoc = $headData['clubInfo']['updoc'];
		//$downdoc = $headData['clubInfo']['downdoc'];
		$uplimit = $this->getUploadDocLimit($updoc, $this->mid, $clubid);
		//$downlimit = $this->getDownDocLimit($downdoc, $this->mid, $clubid);
		
		//暂用资源服务器
		$config = model('Xdata')->lget('resource');
		$this->assign('con',$config);
		
		$upload['maxSize']   = model('Xdata')->get('group:simpleFileSize');
		$allow_exts = model('Xdata')->get('group:uploadFileType');
		$upload['allowExts'] = str_replace('|', ',', $allow_exts);
		$extsArr = explode('|', $allow_exts);
		foreach ($extsArr as $val){
			$upload['showExts'] .= '*.'.$val.';';
		}
		//$upload['showExts'] = substr($upload['showExts'],0,(strlen($upload['showExts'])-1));
		
		$this->assign( $headData );
		$this->assign('fileCount', $fileCount);	 //文档数
		$this->assign('usedSpace', $usedSpace);  //使用空间大小
		$this->assign('fileList', $fileList);    //文档列表
		$this->assign('upload', $upload);    //文档列表
		$this->assign('uplimit', $uplimit);    //文档列表
		//$this->assign('downlimit', $downlimit);    //文档列表
		$this->display();
	}
	
	//上传文档
	public function uploadDocument(){
		$map['clubid'] = $_POST['clubid'];
		
		$map['title'] = $_POST['title'];
		$map['filesize'] = $_POST['filesize'];
		$map['filetype'] = str_replace('.', '', $_POST['filetype']);
		//$map['fileurl'] = $_POST['fileurl'];
		$map['savepath'] = $_POST['savepath'];
		$map['savename'] = $_POST['savename'];
		
		$map['uid'] = $this->mid;
		$map['ctime'] = time();
		
		$rs = D('ClubDocument')->add( $map );
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
		
// 		$upload['max_size']   = model('Xdata')->get('group:simpleFileSize')*1024*1024;
// 		$allow_exts = model('Xdata')->get('group:uploadFileType');
// 		$upload['allow_exts'] = str_replace('|', ',', $allow_exts);
// 		//dump($upload);exit;
// 		$info = X('Xattach')->upload('group_file',$upload);
// 		if($info['status']){  //上传成功
// 			$uploadFileInfo = $info['info'][0];
		
// 			$map['clubid'] = $clubid;
// 			$map['uid'] = $this->mid;
// 			$map['title'] = $uploadFileInfo['name'];
// 			$map['filesize'] = $uploadFileInfo['size'];
// // 			if( $map['filesize'] > 100*1024*1024 ){
// // 				$this->error("最大上传100M");
// // 			}
// 			$map['filetype'] = $uploadFileInfo['extension'];
// 			$map['fileurl'] = $uploadFileInfo['savepath'] . $uploadFileInfo['savename'];
// 			$map['ctime'] = time();
// 			$result = D( 'ClubDocument' )->add($map);
// 			if($result){
// 				$this->success("上传成功!");
// 			}else{
// 				$this->error("上传失败!");
// 			}
// 			$this->redirect('club/Document/index', array('id'=>$clubid));
// 		}else{
// 			$this->error($info['info']);
// 		}
		
	}
	
	//下载文档
	public function downloadDoc(){
		
		$id = intval($_POST['docid']) > 0 ? intval($_POST['docid']) : 0;
		$clubid = intval($_POST['clubid']) > 0 ? intval($_POST['clubid']) : 0;
		if($id == 0 || $clubid == 0) exit();
		
		//验证是否社团成员
		$memberInfo = D( 'ClubMember' )->getMemberInfoInClub($clubid, $this->mid);
		if( !in_array($memberInfo['type'], array(1,2,3)) ){
			$this->error("下载文档需为社团成员");
		}
		
		$docInfo = D( 'ClubDocument' )->getDocById($id);
		if( !$docInfo ){
			$this->error("文档不存在或已被删除!");
		}
		
		$config = model('Xdata')->lget('resource');
		$filepath = $config['server'].$docInfo['savepath'].$docInfo['savename'];
		$fileExist = @file_get_contents($filepath, null, null, -1, 1) ? true : false;
		//dump($fileExist);exit;
		//dump(file_exists($filepath));exit;
		if ( $fileExist ) {
			// 增加下载次数
			D( 'ClubDocument' )->addDowncount($id);
			
			//include_once(SITE_PATH . '/addons/libs/Http.class.php');
			//$docName = iconv("utf-8", 'gb2312', $docInfo['title']);
			file_down($filepath, $docInfo['title']);
			//Http::download($filepath, $docName);
		}else{
			$filepath = $config['server'].$docInfo['savepath'].$docInfo['savename'].'_out/'.$docInfo['savename'];
			//dump($filepath);exit;
			D( 'ClubDocument' )->addDowncount($id);
			file_down($filepath, $docInfo['title']);
		}
		
	}
	
	public function delDocument(){
		$ids = $_POST['fids'];
		$idstr = "";
		foreach($ids as $val){
			$idstr .= $val.",";
		}
		$idstr = substr($idstr,0,(strlen($idstr)-1));
		$rs = D( 'ClubDocument' )->delDocument($idstr);
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//获取是否具有上传文档的权限
	private function getUploadDocLimit($updoc, $uid, $clubid){
		if( $updoc == 0 ){
			$ismanager = isManager($uid, $clubid);
			if( $ismanager ){
				$upload = 1;
			}
		}else if( $updoc == 1 ){
			$ismember = isClubMember($uid, $clubid);
			if( $ismember ){
				$upload = 1;
			}
		}
		return $upload;
	}
	
	//获取是否具有下载文档的权限
	private function getDownDocLimit($downdoc, $uid, $clubid){
		if( $downdoc == 0 ){
			$ismember = isClubMember($uid, $clubid);
			if( $ismember ){
				$download = 1;
			}
		}else if( $downdoc == 1 ){
			$download = 1;
		}
		return $download;
	}
	
	
	
}
?>