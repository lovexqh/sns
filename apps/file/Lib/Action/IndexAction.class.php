<?php

/**
 * GiftAction
 * 礼物控制层
 *
 * @uses
 * @package
 * @version
 * @copyright 2009-2011 SamPeng
 * @author SamPeng <sampeng87@gmail.com>
 * @license PHP Version 5.2 {@link www.sampeng.cn}
 */
class IndexAction extends Action {

    private $icopath = "";
    protected $app_alias;

    public function _initialize() {
        //参数转义
        $this->icopath = '../Public/images/ico/';
        $this->assign('icopath', $this->icopath);

        global $ts;
        $this->app_alias = $ts['app']['app_alias'];
    }

    public function index() {
        $uid = array();
        if ($_GET['order'] == 'following') {
            $following = M('weibo_follow')->field('fid')->where("uid={$this->mid} AND type=0")->findAll();
            foreach ($following as $v) {
                $uid[] = $v['fid'];
            }
            $this->setTitle("我关注的人的{$this->app_alias}");
        } else {
            $this->setTitle("最新{$this->app_alias}");
        }
        $this->__setAssign($uid);
        $this->display();
    }

    public function personal() {
        $this->__setAssign($this->uid);
        $this->assign('uid', $this->uid);
        $this->assign('name', getUserName($this->uid));
        $this->setTitle("我的{$this->app_alias}");
        $this->display();
    }

    public function addPosterSort() {
        $posterTypeDao = D('FileType');
        $posterType = $posterTypeDao->getType(null, null, true);
        $this->assign('posterType', $posterType);
        $this->setTitle("发布{$this->app_alias}");
        $this->display();
    }

    public function posterDetail() {
        $posterTypeDao = D('FileType');
        $poster = D('File');
        $id = intval($_GET['id']);
        if ($id == 0) {
            $this->error("错误的信息地址.请检查后再访问");
            exit;
        }

        $posterData = $poster->getPoster($id, $this->mid);
        if (!$posterData) {
            $this->assign('jumpUrl', U('poster/Index/index'));
            $this->error("这个信息被删除或者不允许查看");
            exit;
        }

        $posterType = $posterTypeDao->getType($posterData['pid']);

        $posterTypeExtraField = $posterTypeDao->getExtraField($posterType['extraField']);
        unset($posterType['extraField']);

        if ($posterData['uid'] == $this->mid) {
            $posterData['name'] = '我';
            $this->assign('admin', 1);
        } else {
            $posterData['name'] = getUserName($posterData['uid']);
        }

        $this->assign('poster', $posterData);
        $this->assign('extraField', $posterTypeExtraField);
        $this->assign('type', $posterType);
        $this->display();
    }

    public function doDeletePoster() {
        $id = intval($_POST['id']);
        if (0 == $id) {
            echo -3;
        } else {
            $poster = D('File');
            if ($res = $poster->deletePoster($id, $this->mid)) {
                //积分
                X('Credit')->setUserCredit($this->mid, 'delete_poster');
            }
            echo $res;
        }
    }

    private function __setAssign($uid = null) {
        $poster = D('File');
        //以下注释为原有的互助列表数据
//	$pid = intval($_GET['pid']) ? intval($_GET['pid']) : null;
//	$stid = intval($_GET['stid']) ? intval($_GET['stid']) : null;
//	$posterData = $poster->getPosterList($pid, $stid, $uid);
        $this->getPosterType($poster);
//	if (!$_REQUEST['ajax']) {
//	    $this->assign($posterData);
//	} else {
//	    print_r(json_encode($posterData));
//	}
    }

    private function getPosterType($poster) {
        $posterTypeDao = D('FileType');
        $posterSmallTypeDao = D('FileSmallType');
        $posterType = $posterTypeDao->getType(null, null, true);
        foreach ($posterType as $value) {
            $id = $value['id'];
            if (isset($value['type']) && $id == intval($_GET['pid'])) {
                $posterType = $value;
                $posterSmallType = $posterSmallTypeDao->getPosterSmallType($value['type']);
            }
        }

        $posterSmallType = $this->getPosterCount($poster, $posterSmallType);
        $this->assign('fileType', $posterType);
        $this->assign('type', $posterSmallType);
    }

    private function getPosterCount($poster, $posterSmallType) {
        $tableName = $poster->getTableName();
        //$otherWhere = $this->private;
        if (!empty($posterSmallType)) {
            for ($i = 0; $i < count($posterSmallType); $i++) {
                //if(isset($otherWhere)){
                //	$where = "type = {$posterSmallType[$i]['id']} AND ".$otherWhere;
                //}else{
                $where = "type = {$posterSmallType[$i]['id']}";
                //}
                $sql[] = "select '{$posterSmallType[$i]['id']}' as `id`,count(1) as count from  {$tableName} where {$where}";
            }
        }
        $sql = implode(' union all ', $sql);
        $count = $poster->query($sql);
        $temp_array = array();
        foreach ($count as $value) {
            $temp_array[$value['id']] = $value['count'];
        }
        $result = $posterSmallType;
        foreach ($result as &$value) {
            $value['count'] = $temp_array[$value['id']];
        }
        return $result;
    }

    public function addPoster() {
        if(X('Xattach')->user_space_count() == 0){
            $this->error("空间已满");
            exit;
        }
        $pid = intval($_GET['pid']);
        if (empty($pid))
            $this->error('参数有误');
        $system_default = model('Xdata')->lget('attach');
        $this->assign('system_default', $system_default);
        $this->assign('pid', $pid);
        $this->assign("do", "add");
        $this->__setAssign();
        $this->display("formFile");
    }

    //批量上传文档
    public function addBatchFile() {
        $pid = intval($_GET['pid']);
        if (empty($pid))
            $this->error('参数有误');
        $this->assign('pid', $pid);
        $this->__setAssign();
        $system_default = model('Xdata')->lget('attach');
        $this->assign('system_default',$system_default);
        $this->display('formBatchFile');
    }

    //批量上传图片
    public function addBatchImage() {
        $pid = intval($_GET['pid']);
        if (empty($pid))
            $this->error('参数有误');
        $this->assign('pid', $pid);
        $this->__setAssign();
        $system_default = model('Xdata')->lget('attach');
        $this->assign('system_default',$system_default);
        $this->display('formBatchImage');
    }

    public function editPoster() {
        $posterDao = D('File');
        $fileData = $posterDao->getPoster($_GET['id'], $this->mid);
        $_GET['pid'] = $fileData['pid'];
        $fileData['tags'] = service("Tag")->getFileTags($fileData['id']);
        $this->assign('file', $fileData);
        $this->assign('pid', $fileData['pid']);
        $this->assign("tags", service("Tag")->getAllTags());
        if (!empty($_GET['src_tag'])) {
            $this->assign("to_set", "1");
        }
        $this->assign("do", "edit");
        $this->__setAssign();
        $this->display("formFile");
    }

    public function doEditPoster() {
        $dao = D('File');
        $condition['id'] = intval($_POST['id']);

        //加入公司概念
//            $map['cid'] = $_POST['cid'];
//	print_r($_POST);
//	exit;

        $map['title'] = t($_POST['title']);
        $map['type'] = intval($_POST['type']);
        $map['content'] = h($_POST['explain']);
//	$map['contact'] = t($_POST['contact']);

//        if (isset($_POST['is_read'])) {
            if ($_POST['is_read']) {
                $map['no_read'] = 0;
            } else {
                $map['no_read'] = 1;
            }
//        }
//        if (isset($_POST['is_down'])) {
            if ($_POST['is_down']) {
                $map['no_down'] = 0;
            } else {
                $map['no_down'] = 1;
            }
//        }
//	$address = explode(',', $_POST['areaid']);
//	$map['address_province'] = $address[0];
//	$map['address_city'] = $address[1];
//	if ($_POST['deadline']) {
//	    $map['deadline'] = $deadline = $this->_paramDate($_POST['deadline']);
//	    $sendPosterTime = $dao->where('id=' . intval($_POST['id']))->getField('cTime');
//	    $deadline < $sendPosterTime && $this->error("结束时间不得小于发布时间");
//	} else {
//	    $map['deadline'] = NULL;
//	}
        // 检查详细介绍
        if (get_str_length($map['content']) <= 0) {
            if (isset($_POST['explain'])) {
                $this->error('详细介绍不能为空');
            } else {
                unset($map['content']);
            }
        }

        $map = $this->_extraField($map, $_POST);
        //得到上传的图片
        $option = array();
        if ($_FILES['cover']['size'] > 0) {
            $options['userId'] = $this->mid;
            $options['max_size'] = 2 * 1024 * 1024; //2MB
            $options['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
            $cover = X('Xattach')->uploaD('file_file', $options);

            //标签设置接口
            service("Tay")->setFileTags($_POST['type'], $cover['id']);

            if ($cover['status']) {
                $map['cover'] = $cover['info'][0]['savepath'] . $cover['info'][0]['savename'];
            } else {
                $this->error($cover['info']);
            }
        }

        //$map['private'] = isset($_POST['friend'])?$_POST['friend']:0;

        $rs = $dao->where($condition)->save($map);
        service("Tag")->setFileTags($_POST['tag'], $condition['id']);
        if (false !== $rs) {
            $this->assign('jumpUrl', U('file/File/Index', array('pid' => $_GET['pid'])));
            $this->success("编辑成功");
        } else {
            $this->error('编辑失败');
        }
    }

    private function _paramDate($date) {
        $date_list = explode(' ', $date);
        list( $year, $month, $day ) = explode('-', $date_list[0]);
        list( $hour, $minute, $second ) = explode(':', $date_list[1]);
        return mktime($hour, $minute, $second, $month, $day, $year);
    }

    public function doAddPoster() {
        $map['title'] = t(h($_POST['title']));
        $map['type'] = intval($_POST['type']);
        $map['pid'] = intval($_POST['pid']);
        $map['content'] = h($_POST['explain']);
        $map['contact'] = t($_POST['contact'] ? $_POST['contact'] : "");
        $map['uid'] = $this->mid;
        $map['cTime'] = time();
        if ($_POST['is_read']) {
            $map['no_read'] = 0;
        } else {
            $map['no_read'] = 1;
        }
        if ($_POST['is_down']) {
            $map['no_down'] = 0;
        } else {
            $map['no_down'] = 1;
        }
        if($map['pid'] == 10){
            $ext = explode('.', $_FILES['cover']['name']);
            $extType =  C("IMG_TYPE") ;
            if(!in_array($ext[count($ext)-1], $extType)){
                $this->error("上传类型错误");
            }
        }
        if ($_FILES['cover']['size'] > 0) {
            $system_default = model('Xdata')->lget('attach');
            $options['userId'] = $this->mid;
            $options['max_size'] = floatval($system_default['attach_max_size']) * 1000000; //单位: 兆
//	    $options['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
            $cover = X('Xattach')->upload('file_file', $options);
//	    //插入描述和标签
//	    service('Xattach')->setAttachInfo($cover['info'][0]['id'], $_POST['tag'], $map['content']);

            if ($cover['status']) {
                $map['cover'] = $cover['info'][0]['savepath'] . $cover['info'][0]['savename'];
            } else {
                $this->error($cover['info']);
            }

            $map['attach_id'] = $cover['info'][0]['id'];
        }
        $map['tag'] = $_POST['tag'];
        //$map['private'] = isset($_POST['friend'])?$_POST['friend']:0;
        $dao = D('File');
        $rs = $dao->addFile($map);
        if ($rs) {
//	    $rs = service("Tag")->setFileTags($_POST['tag'], $rs);
//	    if ($rs) {
            //发微薄
            $_SESSION['new_file'] = 1;
            //积分
            X('Credit')->setUserCredit($this->mid, 'add_file');
            $this->assign("jumpUrl", U('file/File/index', array('type' => $_POST['pid'])) . "#isme");
            $this->success("发送成功");
//	    } else {
//		$this->error("发送失败");
//	    }
        } else {
            $this->error("发送失败");
        }
    }

    //提交批量添加文件
    public function doAddBatchPoster() {
        $config = model('AddonData')->lget('file_upload');
        $options['userId'] = $this->mid;
        $options['max_size'] = $config['file']['size'] * 1024;
        $options['allow_exts'] = str_replace(";", ",", $config['file']['ext']);
        if($_REQUEST['hook'] == 'uploadImage'){
            $ext = explode('.', $_FILES['files']['name']);
            $extType =  C("IMG_TYPE") ;
            if(!in_array($ext[count($ext)-1], $extType)){
                $result['boolen'] = 0;
                $result['message'] = '上传类型错误';
                exit(json_encode($result));
            }
        }
        
        $info = X('Xattach')->upload("file_file", $options);
        
        if ($info['status']) {
            $result['boolen'] = 1;
            $result['file_id'] = $info['info'][0]['id'];
            $result['file_ext'] = $info['info'][0]['extension'];
            $result['file_name'] = $info['info'][0]['name'];
            $result['file_url'] = $info['info'][0]['savepath'] . $info['info'][0]['savename'];
            $result['file_fullurl'] = __UPLOAD__.$info['info'][0]['savepath'] . $info['info'][0]['savename'];
            $result['file_size'] = byte_format($info['info'][0]['size']);
            $result['file_page'] = getShortUrl(Addons::createAddonUrl('FileUploads', "weibo_file_page", array('id' => $info['info'][0]['id'], 'uid' => $this->mid)));
            //$result['file_page'] = getShortUrl(U('home/Space/file', array('id'=>$info['info'][0]['id'], 'uid'=>$this->mid)));
        } else {
            $result['boolen'] = 0;
            $result['message'] = $info['info'];
        }
        $result['publish_type'] = $this->typeCode;
        exit(json_encode($result));
    }

    //添加批量操作表单
    public function doAddFormBatchPoster(){
        $pid = intval($_POST['pid']);
        $dao = D('File');
        $map['pid'] = $pid;
        $map['uid'] = $this->mid;
        $map['cTime'] = time();
        
        for($i = 0;$i<count($_POST['title']);$i++){
            $map['title'] = $_POST['title']["$i"];
            $map['type'] = intval($_POST['type']["$i"]);
            $map['content'] = h($_POST['explain']["$i"]);
            $map['contact'] = t($_POST['contact']["$i"] ? $_POST['contact']["$i"] : "");
            
//            if ($_POST['is_read']["$i"]) {
//                $map['no_read'] = 1;
//            } else {
                $map['no_read'] = 0;
//            }
//            if ($_POST['is_down']["$i"]) {
//                $map['no_down'] = 1;
//            } else {
                $map['no_down'] = 0;
//            }
            $map['cover'] = $_POST['file_url']["$i"];
            $map['attach_id'] = $_POST['file_id']["$i"];
            $map['tag'] = $_POST['tag']["$i"];
            $rs = $dao->addFile($map);
        }
        
        if($rs){
          //发微薄
            $_SESSION['new_file'] = 1;
            //积分
            X('Credit')->setUserCredit($this->mid, 'add_file');
            echo '1';
        } else {
            echo '0';
        }
        
    }
    
    
    private function _extraField($map, $post) {
        for ($i = 1; $i < 6; $i++) {
            if (isset($post['extra' . $i]) && !empty($post['extra' . $i])) {
                if (is_array($post['extra' . $i])) {
                    $map['extra' . $i] = implode(',', $post['extra' . $i]);
                } else {
                    $map['extra' . $i] = $post['extra' . $i];
                }
            }
        }
        return $map;
    }

}
