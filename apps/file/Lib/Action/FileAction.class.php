<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileAction
 *
 * @author liushaochen
 */
class FileAction extends Action {

    public function __construct() {
        parent::__construct();
        $this->assign("server_time", time());
    }

    //文档库首页
    public function index() {
        $fileTypeDao = D('FileType');
        //后台大分类全显示传'1'与前台大分类显示区别
        $fileType = $fileTypeDao->getType(null, null, true, 1);
        $this->assign("fileType", $fileType);
        if (is_null($_GET['type'])) {
            $_GET['type'] = $fileType[0]['id'];
            $html = $fileType[0]['html_page'];
            $typeName = $fileType[0]['name'];
            $fileSmallLabel = $fileType[0]['type'];
        } else {
            foreach ($fileType as $vo) {
                if ($vo['id'] == $_GET['type']) {
                    $html = $vo['html_page'];
                    $typeName = $vo['name'];
                    $fileSmallLabel = $vo['type'];
                    break;
                }
            }
        }
        $fileSmallType = D("FileSmallType")->getAllSmallType($fileSmallLabel);
        $this->assign("fileSmallType", $fileSmallType);
        $this->assign("typeName", $typeName);
        $this->assign('type', $_GET['type']);
        $this->assign('key1', $_REQUEST['key']);
        $this->display($html);
    }

    //文档列表信息
    public function loadData() {
        $db_prefix = C('DB_PREFIX');
        $fileTypeDao = M('file_type');
        //获取类型名称
        $typeInfo = $fileTypeDao->where("id={$_POST['pid']}")->find();
        $where = " {$db_prefix}file.pid LIKE '%{$_POST['pid']}%' "; //判定type列的固定写法
        if (!empty($_POST['key']) || $_POST['tag']) {
            //搜索标识
            $key = $_POST['key'] ? $_POST['key'] : $_POST['tag'];
            $tags = M('file_tag')
                            ->join("{$db_prefix}tag ON {$db_prefix}tag.tag_id={$db_prefix}file_tag.tag_id")
                            ->where("tag_name  LIKE '%{$key}%'")->field("file_id")->select();
            if (!$tags) {
                $where .= " AND ({$db_prefix}file.content LIKE '%{$_POST['key']}%' OR {$db_prefix}file.title LIKE '%{$_POST['key']}%' ) ";
            } else {
                $tags = getSubBeKeyArray($tags, "file_id");
                $tags = implode(",", $tags['file_id']);
                if ($_POST['tag']) {
                    $where .= " AND ( {$db_prefix}file.id IN ({$tags})) ";
                } else {
                    $where .= " AND ( {$db_prefix}file.id IN ({$tags}) OR ({$db_prefix}file.content LIKE '%{$_POST['key']}%' OR {$db_prefix}file.title LIKE '%{$_POST['key']}%' ) ) ";
                }
            }
        }
        if (!empty($_POST['is_me']) && $_POST['is_me'] == 1) {
            //是否为自己的标识
            $where .= " AND {$db_prefix}file.uid= {$_SESSION['mid']} ";
        }
        if (!empty($_POST['type']) && $_POST['type']) {
            $where .= " AND {$db_prefix}file.type ={$_POST['type']} ";
        }

//        if (!empty($_POST['pid']) && $_POST['pid']) {
//            $count = $fileTypeDao->where("id={$_POST['pid']}")->find();
//        }

        if (!empty($_POST['is_me']) && $_POST['is_me'] == 2) {
            $where .= " AND {$db_prefix}file_user_link.favorites_status = 1 AND {$db_prefix}file_user_link.uid={$_SESSION['mid']}";
        }
        //添加群组文档
        $groupIds = D('Group', 'group')->getAllMyGroupIDs($_SESSION['mid']);
        if (!empty($groupIds)) {
            $groupIds = implode(',', $groupIds);
            $where .= " AND (gid in ({$groupIds},0) OR gid is null)";
        }
        //添加被删除的判断
        $where .= " AND {$db_prefix}file.status = 0 ";

//	if (!empty($_POST['order1'])) {
//	    //范围标识
//	    if ($_POST['order1'] == "company") {
//		//全公司的附件
////		$where .= " AND {$db_prefix}file.cid={$_SESSION['cid']} ";
//	    } else if ($_POST['order1'] == "group") {
//		//我加入的群组的附件
//		$gids = M()->where()->field("gid")->select();
//		$where .= " AND {$db_prefix}file.gid IN (0," . explode(",", $attach_ids) . ")";
//	    } else if ($_POST['order1'] == "me") {
//		//我的附件
//		$where .= " AND {$db_prefix}file.uid={$_SESSION['mid']} ";
//	    }
//	}
        if (!empty($_POST['order'])) {
            //排序标识
            if ($_POST['order'] == "new" || $_POST['order'] == 'times') {
                $order = ' ctime DESC '; //最新
            } else if ($_POST['order'] == "read" || $_POST['order'] == 'viewnubmer') {
                $order = ' read_count DESC'; //预览数最多
            } else if ($_POST['order'] == "down" || $_POST['order'] == 'loadnumber') {
                $order = ' down_count DESC '; //下载最多
            } else {
                $order = ' id DESC '; //默认为附件ID的倒序
            }
        } else {
            $order = ' id DESC '; //默认为附件ID的倒序
        }
        $data = M('file')->join(array("`{$db_prefix}user` ON {$db_prefix}file.uid = {$db_prefix}user.uid",
                            "`{$db_prefix}attach` ON {$db_prefix}attach.id = {$db_prefix}file.attach_id",
                            "`{$db_prefix}file_small_type` ON {$db_prefix}file_small_type.id = {$db_prefix}file.type",
                            "(SELECT  l.file_id AS file_id,l.favorites_status AS favorites_status,l.uid AS uid  FROM {$db_prefix}file,{$db_prefix}file_user_link AS l WHERE l.file_id = {$db_prefix}file.id AND l.uid={$_SESSION['mid']} ) AS `{$db_prefix}file_user_link` ON {$db_prefix}file_user_link.file_id = {$db_prefix}file.id AND {$db_prefix}file_user_link.uid={$_SESSION['mid']} "))
                        ->field("{$db_prefix}file.*,title AS name,uname,{$db_prefix}attach.extension AS extension,{$db_prefix}attach.savename AS savename,{$db_prefix}attach.uploadTime AS uploadTime,{$db_prefix}file_small_type.name AS smallType,{$db_prefix}file_user_link.favorites_status")->where($where)->order($order)->findPage($typeInfo['count']);
        foreach ($data['data'] as $key => $value) {
//	    //标签获取
            $data['data'][$key]['tag'] = service("Tag")->getFileTags($value['id']);
            if (is_null($data['data'][$key]['tag'])) {
                $data['data'][$key]['tag'] = "无标签";
            }
            //如果为图片库则需要验证缩略是否可用并添加缩略图
            if ($typeInfo['name'] == '图片库') {
                if (empty($value['savename'])) {
                    $imgArr = explode('/', $value['cover']);
                    $value['savename'] = $imgArr[count($imgArr) - 1];
                }
                $data['data'][$key]['smallcover'] = str_replace($value['savename'], 'small_' . $value['savename'], $value['cover']);
                $data['data'][$key]['middlecover'] = str_replace($value['savename'], 'middle_' . $value['savename'], $value['cover']);
            }
            $data['data'][$key]['cTime'] = friendlyDate($value['cTime']);
        }
        //保留查询条件
        $_SESSION['where'] = $_POST;
        print_r(json_encode($data));
    }

//    //文档预览--swf文件判断
//    public function isPreview() {
//	$uploadURL = strtr(getcwd(), "\\", "/") . '/data' . '/uploads';
//	$map['id'] = $_REQUEST['id'];
//	//1.获取标识，得到路径，保存到服务器本地磁盘。
//	$attach = M('attach')->where($map)->find();
//	if (in_array($attach['extension'], C("IMG_TYPE"))) {
//	    $picPath = UPLOAD_URL . $attach['savepath'] . $attach['savename'];
//	    if (@fopen($picPath, 'r') !== FALSE) {
//		echo 2;
//		exit;
//	    } else {
//		echo '图片不存在~';
//		exit;
//	    }
//	} else {
//	    if (!in_array($attach['extension'], C('FILE_TYPE'))) {
//		echo '文件格式有误~'; //返回错误信息
//		exit;
//	    }
//	    $savename = explode('.', $attach['savename']);
//	    //swf文件存储目录
//	    $swfPath = UPLOAD_URL . $attach['savepath'] . $savename[0] . '.swf';
//	    $swfPath = strtr($swfPath, array('file' => 'flash'));
//	    //如果文件存在--1
//	    if (@fopen($swfPath, 'r') !== FALSE) {
//		echo 1;
//		exit;
//	    }
//	    //文件不可读，进行转换操作
//	    //curlFileDownload参数设置
//	    $fileName = $attach['savepath'] . $attach['savename'];
//	    $path = $uploadURL . $attach['savepath'];
//	    $save = $uploadURL . $attach['savepath'] . $attach['savename'];
//	    service('File')->curlFileDownload($fileName, $path, $save);
//	    //验证文件是否已经下载
//	    $fileUploadRe = is_readable($save);
//	    if ($fileUploadRe == TRUE) {//文件下载成功，进行格式转换
//		//convertPDFtoSWF参数设置
//		$file_path = $uploadURL . $attach['savepath'];
//		$file_name = $attach['savename'];
//		$PDFtoSWFRe = service('File')->convertToSWF($file_path, $file_name);
//		if ($PDFtoSWFRe == TRUE) {//转换成功
//		    //删除原文件
//		    unlink($file_path . $attach['savename']);
//		    //上传swf文件到ftp的flash
//		    $uploadUrl = $attach['savepath'] . $savename[0] . '.swf';
//		    $uploadUrl = strtr($uploadUrl, array('file' => 'flash'));
//		    service('File')->fileUpload($uploadUrl, $file_path . $savename[0] . '.swf');
//		    //删除原swf文件
//		    $s_file_local_path = $uploadURL . $attach['savepath'] . $savename[0] . '.swf'; //转换后swf文件路径
//		    unlink($s_file_local_path);
//		} else {
//		    echo '文档加载出错，请重试~'; //返回错误信息
//		    exit;
//		}
//	    } else {
//		echo '文档可能已被删除~'; //返回错误信息
//		exit;
//	    }
//	    echo 1;
//	}
//    }
//
//    //文档预览
//    public function filePreview() {
//	$uploadURL = strtr(getcwd(), "\\", "/") . '/data' . '/uploads';
//	$map['id'] = $_REQUEST['id'];
//	//1.获取标识，得到路径，保存到服务器本地磁盘。
//	$attach = M('attach')->where($map)->find();
//	if (!in_array($attach['extension'], C('FILE_TYPE'))) {
//	    $this->assign('error', '文件格式有误~'); //返回错误信息
//	    $this->display('error');
//	    exit;
//	}
//	$savename = explode('.', $attach['savename']);
//	//swf文件存储目录
//	$swfPath = UPLOAD_URL . $attach['savepath'] . $savename[0] . '.swf';
//	$swfPath = strtr($swfPath, array('file' => 'flash'));
//	//检查文件是否可读，存在，返回路径
//	if (@fopen($swfPath, 'r') !== FALSE) {
//	    $this->assign('url', $swfPath);
//	    $this->display();
//	    exit;
//	}
//	//文件不可读，进行转换操作
//	//curlFileDownload参数设置
//	$fileName = $attach['savepath'] . '/' . $attach['savename'];
//	$path = $uploadURL . $attach['savepath'] . '/';
//	$save = $uploadURL . $attach['savepath'] . '/' . $attach['savename'];
//	service('File')->curlFileDownload($fileName, $path, $save);
//	//验证文件是否已经下载
//	$fileUploadRe = is_readable($save);
//	if ($fileUploadRe == TRUE) {//文件下载成功，进行格式转换
//	    //convertPDFtoSWF参数设置
//	    $file_path = $uploadURL . $attach['savepath'];
//	    $file_name = $attach['savename'];
//	    $PDFtoSWFRe = service('File')->convertToSWF($file_path, $file_name);
//	    if ($PDFtoSWFRe == TRUE) {//转换成功
//		//删除原文件
//		unlink($file_path . $attach['savename']);
//		//上传swf文件到ftp的flash
//		$uploadUrl = $attach['savepath'] . $savename[0] . '.swf';
//		$uploadUrl = strtr($uploadUrl, array('file' => 'flash'));
//		service('File')->fileUpload($uploadUrl, $file_path . $savename[0] . '.swf');
//		//删除原swf文件
//		$s_file_local_path = $uploadURL . $attach['savepath'] . $savename[0] . '.swf'; //转换后swf文件路径
//		unlink($s_file_local_path);
//	    } else {
//		$this->assign('error', '文档加载出错，请重试~'); //返回错误信息
//		$this->display('error');
//		exit;
//	    }
//	} else {
//	    $this->assign('error', '文档可能已被删除~'); //返回错误信息
//	    $this->display('error');
//	    exit;
//	}
//	$this->assign('url', $swfPath);
//	$this->display();
//	exit;
//    }
    //文档预览
    public function fileDetail() {
        $this->assign("id", $_GET['id']);
        $this->display();
    }

    public function readFile() {
        $db_prefix = C("DB_PREFIX");
        $uploadURL = strtr(LOCAL_UPLOAD_PATH, "\\", "/");
        $map = " attach_id = " . $_REQUEST['id'];
        if (!empty($_REQUEST['fid'])) {
            $map .= " AND {$db_prefix}file.id = {$_REQUEST['fid']}";
        }
        //1.获取标识，得到路径，保存到服务器本地磁盘。
        $attach = M('file')->join(array("`{$db_prefix}user` ON {$db_prefix}file.uid = {$db_prefix}user.uid",
                            "`{$db_prefix}attach` ON {$db_prefix}file.attach_id = {$db_prefix}attach.id",
                            "`{$db_prefix}file_small_type` ON {$db_prefix}file.type = {$db_prefix}file_small_type.id",
                            "`{$db_prefix}file_user_link` ON {$db_prefix}file.id = {$db_prefix}file_user_link.file_id AND {$db_prefix}file_user_link.uid={$_SESSION['mid']}"))
                        ->field("{$db_prefix}file.*,title AS name,uname,{$db_prefix}attach.extension AS extension,{$db_prefix}attach.uploadTime AS uploadTime,{$db_prefix}file_small_type.name AS typeName,{$db_prefix}file_user_link.ding_cai_status,preview_status,download_status,favorites_status")
                        ->where($map)->find();
        if (!in_array(strtolower($attach['extension']), C('FILE_TYPE'))) {
            if (in_array(strtolower($attach['extension']), C('IMG_TYPE'))) {
                $swfPath = UPLOAD_URL . $attach['cover'];
                $this->assign('url', $swfPath);
                $attach['extension'] = 'img'; //图片显示
            } else {
                if(is_null($attach['extension'])){
                    $attach['extension'] = '该文档不存在或已经删除'; //返回不能预览信息
                }else{
                    $attach['extension'] = '文件类型不能预览'; //返回不能预览信息
                }
            }
        } else {
            $savename = explode('.', $attach['cover']);
            //swf文件存储目录
            $swfPath = UPLOAD_URL . $savename[0] . '.swf';
            $swfPath = strtr($swfPath, array('file' => 'flash'));
            //检查文件是否可读，存在，返回路径
//            if (@fopen($swfPath, 'r') !== FALSE) {
                $this->assign('url', $swfPath);
                $attach['extension'] = 'file';
//            }else{
//                $attach['extension'] = '该文档为加密文件无法转换'; //返回错误信息
//            }
//            } else {
//                //文件不可读，进行转换操作
//                //curlFileDownload参数设置
//                $fileName = $attach['savepath'] . '/' . $attach['savename'];
//                $path = $uploadURL . $attach['savepath'] . '/';
//                $save = $uploadURL . $attach['savepath'] . '/' . $attach['savename'];
//                service('File')->curlFileDownload($fileName, $path, $save);
//                //验证文件是否已经下载
//                $fileUploadRe = is_readable($save);
//                if ($fileUploadRe == TRUE) {//文件下载成功，进行格式转换
//                    //convertPDFtoSWF参数设置
//                    $file_path = $uploadURL . $attach['savepath'];
//                    $file_name = $attach['savename'];
//                    $PDFtoSWFRe = service('File')->convertToSWF($file_path, $file_name);
//                    if ($PDFtoSWFRe == TRUE) {//转换成功
//                        //删除原文件
//                        unlink($file_path . $attach['savename']);
//                        //上传swf文件到ftp的flash
//                        $uploadUrl = $attach['savepath'] . $savename[0] . '.swf';
//                        $uploadUrl = strtr($uploadUrl, array('file' => 'flash'));
//                        service('File')->fileUpload($uploadUrl, $file_path . $savename[0] . '.swf');
//                        //删除原swf文件
//                        $s_file_local_path = $uploadURL . $attach['savepath'] . $savename[0] . '.swf'; //转换后swf文件路径
//                        unlink($s_file_local_path);
//                    } else {
//                        $attach['extension'] = '该文档尚未完成转换，请重新加载'; //返回错误信息
//                    }
//                } else {
//
//                    $attach['extension'] = '该文档不存在或已经删除'; //返回错误信息
//                }
//            }
            if($attach['no_read'] == 1){
                $attach['extension'] = "该文档不可预览";
            }
        }

        //资料库预览开关
        $file_type = M("file_type")->select();
        foreach ($file_type as $value) {
            if (!D("FileType")->getPreview($value['name']) && $value['name'] == D("FileType")->getTypeName($attach['pid'])) {
                $attach['extension'] = "{$value['name']}已经关闭预览功能";
            }
        }
        return $attach;
    }

    //文档预览(文档应用里面的预览)
    public function filePreview_app() {
        $attach = $this->readFile();
//	switch ($attach['attach_type']) {
//	    case "weibo_file":
//		$map['attach_id'] = $attach['id'];
//		$weibo_id = M('weibo_attach')->where($map)->field("weibo_id")->find();
//		$intId = intval($weibo_id['weibo_id']);
//		$this->assign('weibo_id', $intId);
//		break;
//	    case "poster_file":
//
//		break;
//	    case "group_file":
//
//		break;
//	}
        $attach['comment_count'] = D('GlobalComment')->countComment("file", $attach['id']);
        //标签获取----未完~
        $attach['tag'] = service("Tag")->getFileTags($attach['id'], "file", 1);
        if (is_null($attach['tag'])) {
            $attach['tag'] = "无标签";
        }
        if (!is_null($attach['src_tag'])) {
            $app = explode("_", $attach['src_tag']);
            if ($app[0] != 'weibo') {
                $app_name = M("app")->where("app_name='" . $app[0] . "'")->field("app_alias")->find();
                $attach['app_name'] = $app_name['app_alias'];
            } else {
                $attach['app_name'] = '社区微博';
            }
        } else {
            $app_name = M("file_type")->where("id={$attach['pid']}")->find();
            $attach['app_name'] = $app_name['name'];
            $attach['type'] = $app_name['id'];
        }
        //下载记录获取
        $attach['download_log'] = D("FileUserLink")->getDownloadLog($attach['id']);

        //如果读过，不添加预览计数
        if (!$attach['preview_status']) {
            //新增资料库与用户关联 add by zhao.wy
            D('FileUserLink')->readAttach($_SESSION['mid'], $attach['attach_id']);
        }

        $this->assign("attachInfo", $attach);
        $this->assign('id', $_REQUEST['id']);
        if (!isset($_GET['t'])) {
            $this->display();
        } else {
            $this->display("filePreview");
        }
    }

    //文档更新（重新转换加载）
//    public function fileReload() {
//        $uploadURL = strtr(LOCAL_UPLOAD_PATH, "\\", "/");
//        $map['id'] = $_REQUEST['id'];
//        //1.获取标识，得到路径，保存到服务器本地磁盘。
//        $attach = M('attach')->where($map)->find();
//        if (!in_array(strtolower($attach['extension']), C('FILE_TYPE'))) {
//            $this->assign('error', '文件格式有误~'); //返回错误信息
//            $this->display('error');
//            exit;
//        }
//        $savename = explode('.', $attach['savename']);
//        //重新转换文件
//        //curlFileDownload参数设置
//        $fileName = $attach['savepath'] . $attach['savename'];
//        $path = $uploadURL . $attach['savepath'];
//        $save = $uploadURL . $attach['savepath'] . $attach['savename'];
//        service('File')->curlFileDownload($fileName, $path, $save);
//        //convertPDFtoSWF参数设置
//        $file_path = $uploadURL . $attach['savepath'];
//        $file_name = $attach['savename'];
//        service('File')->convertToSWF($file_path, $file_name);
//        //删除原文件
//        unlink($file_path . $attach['savename']);
//        //上传swf文件到ftp的flash
//        $uploadUrl = $attach['savepath'] . $savename[0] . '.swf';
//        $uploadUrl = strtr($uploadUrl, array('file' => 'flash'));
//        service('File')->fileUpload($uploadUrl, $file_path . $savename[0] . '.swf');
//        //删除原swf文件
//        $s_file_local_path = $uploadURL . $attach['savepath'] . $savename[0] . '.swf'; //转换后swf文件路径
//        unlink($s_file_local_path);
//        //swf文件存储目录
//        $swfPath = UPLOAD_URL . $attach['savepath'] . $savename[0] . '.swf';
//        $swfPath = strtr($swfPath, array('file' => 'flash'));
//        $this->assign('url', $swfPath);
//        $this->display('filePreview');
//    }

    public function delete_file() {

        $id = intval($_POST['id']);
        $result = service('File')->delete_file($id);
        echo $result;
    }

    public function favoriteFile() {
        $uid = $_SESSION['mid'];
        $attach_id = intval($_POST['id']);
        $favorite = intval($_POST['favorite']);
        $rs = D("FileUserLink")->favoriteAttach($uid, $attach_id, $favorite);
        echo $rs;
    }

    public function doDingCai() {
        $rs = D('FileUserLink')->dingCaiAttach($_SESSION['mid'], $_POST['id'], $_POST['val']);
        echo $rs ? "1" : "0";
    }

    public function deleteFiles() {

        $id = intval($_POST['id']);
        $type = $_POST['filepath'];
        $result = service('File')->delete_file($id, $type);
        echo $result;
    }

    public function infor_document() {
        $attach = $this->readFile();
        $attach['comment_count'] = D('GlobalComment')->countComment("file", $attach['id']);
        //标签获取----未完~
        $attach['tag'] = service("Tag")->getFileTags($attach['id'], "file", 1);
        if (is_null($attach['tag'])) {
            $attach['tag'] = "无标签";
        }
        if (!is_null($attach['src_tag'])) {
            $app = explode("_", $attach['src_tag']);
            if ($app[0] != 'weibo') {
                $app_name = M("app")->where("app_name='" . $app[0] . "'")->field("app_alias")->find();
                $attach['app_name'] = $app_name['app_alias'];
            } else {
                $attach['app_name'] = '社区微博';
            }
        } else {
            $app_name = M("file_type")->where("id={$attach['pid']}")->find();
            $attach['app_name'] = $app_name['name'];
            $attach['type'] = $app_name['id'];
        }
        //下载记录获取
        $attach['download_log'] = D("FileUserLink")->getDownloadLog($attach['id']);

        //如果读过，不添加预览计数
        if (!$attach['preview_status']) {
            //新增资料库与用户关联 add by zhao.wy
            D('FileUserLink')->readAttach($_SESSION['mid'], $attach['attach_id']);
        }

        $this->assign("attachInfo", $attach);
        $this->assign('id', $_REQUEST['id']);
        $this->display();
    }

    //图片预览页
    public function children_image() {
        $attach = $this->readFile();
        if ($attach['status'] != 0 || is_null($attach['status'])) {
            $this->display('indexError');
            exit;
        }
        if (!is_null($attach['src_tag'])) {
            $app = explode("_", $attach['src_tag']);
            if ($app[0] != 'weibo') {
                $app_name = M("app")->where("app_name='" . $app[0] . "'")->field("app_alias")->find();
                $attach['app_name'] = $app_name['app_alias'];
            } else {
                $attach['app_name'] = '社区微博';
            }
        } else {
            $app_name = M("file_type")->where("id={$attach['pid']}")->find();
            $attach['app_name'] = $app_name['name'];
            $attach['type'] = $app_name['id'];
        }

        $this->assign('attachInfo', $attach);
        $this->assign('id', $_REQUEST['id']);
        $this->assign('selectMap', $_SESSION['where']);
        $this->display();
    }

    //预览图片控件调用
    public function getImages() {
        $param = $_POST;
        if ($_POST['button'] == "default" && !empty($_POST['limit'])) {
            //默认左右各查询5条数据+本身数据
            $param['limit'] = $_POST['limit'];
            $param['button'] = 'left';
            $left_data = $this->getAdjoinDataByID($param);
            //调整数组顺序
            krsort($left_data);
            $param['button'] = 'right';
            $param['limit'] = $_POST['limit'] * 2 - count($left_data);
            $right_data = $this->getAdjoinDataByID($param);
            if (count($right_data) < $_POST['limit']) {
                $param['button'] = 'left';
                $param['limit'] = $_POST['limit'] * 2 - count($right_data);
                $left_data = $this->getAdjoinDataByID($param);
                //调整数组顺序
                krsort($left_data);
            }
            $param['button'] = 'main';
            $main_data = $this->getAdjoinDataByID($param);

            //合并数据
//            $data = array_merge($left_data,$main_data,$right_data);//不能出现重复项和空值
            foreach ($left_data as $lv) {
                $data[] = $lv;
            }
            foreach ($main_data as $mv) {
                $data[] = $mv;
            }
            foreach ($right_data as $rv) {
                $data[] = $rv;
            }
        } elseif ((!empty($_POST['button'])) && ($_POST['button'] != "defualt") && (!empty($_POST['limit']))) {
            $dataArray = $this->getAdjoinDataByID($param);
            if ($_POST['button'] == "left") {
                for ($i = count($dataArray) - 1; $i >= 0; $i--) {
                    $data[] = $dataArray[$i];
                }
            } else {
                $data = $dataArray;
            }
        } else {
            print_r(json_encode('error'));
        }
        //添加分类名，转换时间格式
        foreach ($data as $key => $value) {
            $aid = $data[0]['id'];
            $data[$key]['app_name'] = D('FileType')->getTypeName($value['pid']);
            $data[$key]['uploadTime'] = friendlyDate($value['uploadTime']);
            $data[$key]['tpl_data'] = urlencode(
                    serialize(
                            array(
                                'author' => getUserName($value['uid']),
                                'title' => $value['title'],
                                'url' => U('file/File/fileDetail', array('id' => $value['id'])),
                            )
                    )
            );
//            $data[$key]['preview_status'] = D('FileUserLink')->preview_status($_SESSION['mid'],$value['id']);
        }
        $info['data'] = $data;
        $info['aid'] = empty ($param['aid']) ? $aid : $param['aid'];
        print_r(json_encode($info));
    }

    //获取某数据id的相邻数据（由loadData中的sql为标准，修改loadData时同时修改本方法sql）
    public function getAdjoinDataByID($param) {
        $fileTypeDao = M('file_type');
        //获取类型名称
        if (empty($param['pid'])) {
            $typeInfo = $fileTypeDao->where("name like '图片库'")->find();
            $param['pid'] = $typeInfo['id'];
        } else {
            $typeInfo = $fileTypeDao->where("id={$param['pid']}")->find();
        }
        if ($param['button'] == 'left') {
            $sc = 'ASC';
        } else {
            $sc = 'DESC';
        }

        $db_prefix = C('DB_PREFIX');
        $where = " {$db_prefix}file.pid LIKE '%{$param['pid']}%' "; //判定type列的固定写法
        if (!empty($param['key']) || !empty ($param['tag'])) {
            //搜索标识
            $key = empty ($param['key']) ? $param['tag'] : $param['key'];
            $tags = M('file_tag')
                            ->join("{$db_prefix}tag ON {$db_prefix}tag.tag_id={$db_prefix}file_tag.tag_id")
                            ->where("{$db_prefix}file_tag.file_id <> 0 and {$db_prefix}tag.tag_name  LIKE '%{$key}%'")->field("file_id")->select();
            if (!$tags) {
                $where .= " AND ({$db_prefix}file.content LIKE '%{$key}%' OR {$db_prefix}file.title LIKE '%{$key}%' ) ";
            } else {
                $tags = getSubBeKeyArray($tags, "file_id");
                if(empty ($param['aid'])){
                    $aid = $tags['file_id'][0];
                }
                if($param['tag']){
                    $where .= " AND ( {$db_prefix}file.id IN (" . implode(",", $tags['file_id']) . ") )";
                }else{
                    $where .= " AND ( {$db_prefix}file.id IN (" . implode(",", $tags['file_id']) . ") OR ({$db_prefix}file.content LIKE '%{$key}%' OR {$db_prefix}file.title LIKE '%{$key}%' ) ) ";
                }
            }
        }
        if (!empty($param['is_me']) && $param['is_me'] == 1) {
            //是否为自己的标识
            $where .= " AND {$db_prefix}file.uid= {$_SESSION['mid']} ";
        }
        if (!empty($param['type']) && $param['type']) {
            $where .= " AND {$db_prefix}file.type ={$param['type']} ";
        }

        if (!empty($param['pid']) && $param['pid']) {
            $count = $fileTypeDao->where("id={$param['pid']}")->find();
        }

        if (!empty($param['is_me']) && $param['is_me'] == 2) {
            $where .= " AND {$db_prefix}file_user_link.favorites_status = 1 AND {$db_prefix}file_user_link.uid={$_SESSION['mid']}";
        }
        //添加群组文档
        $groupIds = D('Group', 'group')->getAllMyGroupIDs($_SESSION['mid']);
        if (!empty($groupIds)) {
            $groupIds = implode(',', $groupIds);
            $where .= " AND (gid in ({$groupIds},0) OR gid is null)";
        }
        //添加被删除的判断
        $where .= " AND {$db_prefix}file.status = 0 ";
        //左数据
        $aid = empty ($param['aid']) ? $aid : $param['aid'];
        if ($param['button'] == 'left') {
            $where .=" AND {$db_prefix}file.id > {$aid} ";
        }
        //右数据
        if ($param['button'] == 'right') {
            $where .=" AND {$db_prefix}file.id < {$aid} ";
        }
        //自身数据
        if ($param['button'] == 'main') {
            $where .=" AND {$db_prefix}file.id = {$aid} ";
        }

        if (!empty($param['order'])) {
            //排序标识
            if ($param['order'] == "new") {
                $order = " ctime {$sc}"; //最新
            } else if ($param['order'] == "read") {
                $order = " read_count {$sc}"; //预览数最多
            } else if ($param['order'] == "down") {
                $order = " down_count {$sc} "; //下载最多
            } else {
                $order = " id  {$sc}"; //默认为附件ID的倒序
            }
        } else {
            $order = " id {$sc} "; //默认为附件ID的倒序
        }
        $data = M('file')->join(array("`{$db_prefix}user` ON {$db_prefix}file.uid = {$db_prefix}user.uid",
                            "`{$db_prefix}attach` ON {$db_prefix}attach.id = {$db_prefix}file.attach_id",
                            "`{$db_prefix}file_small_type` ON {$db_prefix}file_small_type.id = {$db_prefix}file.type",
                            "(SELECT  l.file_id AS file_id,l.favorites_status AS favorites_status,l.uid AS uid  FROM {$db_prefix}file,{$db_prefix}file_user_link AS l WHERE l.file_id = {$db_prefix}file.id AND l.uid={$_SESSION['mid']} ) AS `{$db_prefix}file_user_link` ON {$db_prefix}file_user_link.file_id = {$db_prefix}file.id AND {$db_prefix}file_user_link.uid={$_SESSION['mid']} "))
                        ->field("{$db_prefix}file.*,title AS name,uname,{$db_prefix}attach.extension AS extension,{$db_prefix}attach.savename AS savename,{$db_prefix}attach.uploadTime AS uploadTime,{$db_prefix}file_small_type.name AS smallType,{$db_prefix}file_user_link.favorites_status")->where($where)->order($order)->limit($param['limit'])->select();
        foreach ($data as $key => $value) {
//	    //标签获取
            $data[$key]['tag'] = service("Tag")->getFileTags($value['id']);
            if (is_null($data[$key]['tag'])) {
                $data[$key]['tag'] = "无标签";
            }
            //如果为图片库则需要验证缩略是否可用并添加缩略图
            if ($typeInfo['name'] == '图片库') {
                if (empty($value['savename'])) {
                    $imgArr = explode('/', $value['cover']);
                    $value['savename'] = $imgArr[count($imgArr) - 1];
                }
                $data[$key]['smallcover'] = str_replace($value['savename'], 'small_' . $value['savename'], $value['cover']);
                $data[$key]['middlecover'] = str_replace($value['savename'], 'middle_' . $value['savename'], $value['cover']);
            }
        }
        return $data;
    }

    //更新图片数据
    public function doUpdateSize() {
        var_dump(D('file')->doUpdateSize());
    }

    //更新图片阅读数
    public function preview_status_count() {
        $res = D('FileUserLink')->readAttach($_SESSION['mid'], $_POST['attach_id']);
        if ($res) {
            echo '1';
        } else {
            echo '0';
        }
    }

}

?>