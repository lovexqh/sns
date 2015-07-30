<?php

class FileModel extends Model {

    private $api;

    public function _initialize() {
        //$this->api = new TS_API();
    }

    public function getPosterList($pid = null, $type = null, $uid = null, $title = null, $is_warn = null, $ctime = null,$status = null) {

        //加入公司概念
//            $cid = service('Company')->getCompanyList();
//            $map['cid'][] = 'in';
//            foreach ($cid as $value) {
//                $map['cid'][1][] = $value['id'];
//            }

        isset($pid) && $map['pid'] = $pid;
        isset($type) && $map['type'] = $type;
        isset($title) && $map['title'] = array('like', '%' . $title . '%');
        isset($is_warn) && $map['is_warn'] = $is_warn;
        isset($ctime) && $map['cTime'] = array('between',array($ctime,$ctime+24 * 60 * 60));
        if (is_array($uid) && !empty($uid)) {
            $map['uid'] = array('in', $uid);
        } elseif (intval($uid)) {
            $map['uid'] = $uid;
        }
        if($status == 'recycle'){
            $map['status'] = array('neq','0');
        }else{
            $map['status'] = '0';
        }
        $result = $this->where($map)->order('cTime DESC')->findPage();
        $fileTypeDao = M('file_type');
        $commentDao = M('comment');
        $smallTypeDao = M('file_small_type');
        foreach ($result['data'] as $key => $value) {
            if(is_null ($value['type'])){
                $result['data'][$key]['name'] = "来源微博";
            }else{
                $name = $fileTypeDao->where("id={$value['pid']}")->find();
                $result['data'][$key]['name'] = "来源{$name['name']}";
            }
            if(!empty ($value['pid'])){
//                $type = $fileTypeDao->where("id={$value['pid']}")->find();
                $smallType = $smallTypeDao->where("id={$value['type']}")->find();
                $result['data'][$key]['typename'] = $smallType['label']."-".$smallType['name'];
            }
            $count = $commentDao->where("type='file' and appid={$value['id']} and uid={$value['uid']}")->count();
            $result['data'][$key]['count'] = $count;
        }
        $result['data'] = $this->replace($result['data']);
        return $result;
    }

    public function getPoster($id, $mid) {
        $map['id'] = $id;
        $result = $this->where($map)->find();
        $posterSmallTypeDao = D('FileSmallType');
        $posterTypeDao = D('FileType');
        if (!$result)
            return false;
        //if($mid !=$result['uid'] && $result['private'] == 1 && 'unfollow' == getFollowState($result['uid'],$mid)) return false;
        $result['posterType'] = $posterTypeDao->getTypeName($result['pid']);
        $result['posterSmallType'] = $posterSmallTypeDao->getTypeName($result['type']);
        isset($result['cover']) && $result['cover'] = UPLOAD_URL . $result['cover'];
        $result['address'] = getAreaInfo($result['address_province'] . ',' . $result['address_city']);
        return $result;
    }

    public function deletePoster($id, $mid) {
        $poster = $this->where('id=' . $id)->find();
        
        if (!$poster)
            return -2;
        if ($poster['uid'] != $mid)
            return -1;

        $rs = $this->where('id=' . $id)->save(array('status'=>1,'ip'=>$_SERVER["REMOTE_ADDR"],'deleteuid'=>$_SESSION['mid'],'dtime'=>time()));
        if ($rs) {
            return 1;
        } else {
            return 0;
        }
    }

    private function replace($data) {
        $result = $data;
        $posterSmallTypeDao = D('FileSmallType');
        $posterTypeDao = D('FileType');
        $posterST = $posterSmallTypeDao->getPosterSmallTypeByIdArray();
        $posterT = $posterTypeDao->getPosterTypeByIdArray();
        $posterType = D('FileType');
        foreach ($result as &$value) {
            $value['type'] = $posterST[$value['type']];
            $value['content'] = getBlogShort($value['content'], 20);
            $value['posterType'] = $posterT[$value['pid']];
            $value['cover'] && $value['cover'] = UPLOAD_URL . $value['cover'];
        }
        return $result;
    }

    /*
     * 添加资料
     * 参数类型：array
     * 参数值：
     * $fileArray['pid'] 资料大分类 
     * $fileArray['title'] 资料标题
     * $fileArray['type'] 资料小分类 默认空--其他分类
     * $fileArray['content'] 描述
     * $fileArray['private'] 是否私有 0-非，1-是 默认0
     * $fileArray['cover'] 附件路径
     * $fileArray['extra1']
     * $fileArray['extra2']
     * $fileArray['extra3']
     * $fileArray['extra4']
     * $fileArray['extra5']
     * $fileArray['attach_id'] 上传附件ID
     * $fileArray['src_tag'] 来源应用名，如果来源资料为空
     * $fileArray['src_id'] 来源应用对应ID，如果来源资料为空
     * $fileArray['gid'] 来源群组ID 默认为空
     * 返回值：
     * */

    public function addFile($fileArray) {
	if(empty($fileArray['pid'])){
	    $data = M("file_type")->where("name='{$fileArray['file_tag']}'")->field("id AS pid")->find();
	} else {
	    $data['pid'] = $fileArray['pid'];
	}
	if(empty($fileArray['type'])){
	    $typeIdMap['id'] = $data['pid'];
	    $map = M('file_type')->where($typeIdMap)->field("type AS label")->find();
	    $map['name'] = "其它类";
	    $typeId = M('file_small_type')->where($map)->field("id")->find();
	    $data['type'] = $typeId['id'];
	} else {
	    $data['type'] = $fileArray['type'];
	}
        $data['title'] = $fileArray['title'];
        $data['uid'] = $_SESSION['mid'];
        $data['cTime'] = time();
        $data['content'] = $fileArray['content'];
        $data['private'] = empty($fileArray['private']) ? '0' : $fileArray['private'];
        $data['cover'] = $fileArray['cover'];
        $data['no_read'] = $fileArray['no_read'];
        $data['no_down'] = $fileArray['no_down'];
        //根据文件路径获取类型，如果是图片类型，则添加宽和高属性，同时验证是否存在缩略图
        $image = explode('.',$fileArray['cover']);
        if(in_array(strtolower($image[count($image)-1]),C('IMG_TYPE'))){ //如果为图片库则需要添加原图长宽数据
                $imagsInfo = getimagesize(UPLOAD_URL.$data['cover']);
                $imageJson['width'] = $imagsInfo[0];
                $imageJson['height'] = $imagsInfo[1];
                $data['length_width'] = json_encode($imageJson);
                $this->doThumb($data['cover']);
        }
        $data['extra1'] = empty($fileArray['extra1']) ? null : $fileArray['extra1'];
        $data['extra2'] = empty($fileArray['extra2']) ? null : $fileArray['extra2'];
        $data['extra3'] = empty($fileArray['extra3']) ? null : $fileArray['extra3'];
        $data['extra4'] = empty($fileArray['extra4']) ? null : $fileArray['extra4'];
        $data['extra5'] = empty($fileArray['extra5']) ? null : $fileArray['extra5'];
        $data['attach_id'] = $fileArray['attach_id'];
        if (!empty($fileArray['src_tag']) && !empty($fileArray['src_id'])) {
            $data['src_tag'] = $fileArray['src_tag'];
            $data['src_id'] = $fileArray['src_id'];
        }
        $data['gid'] = empty($fileArray['gid']) ? null : $fileArray['gid'];
        //判断数据时候已经存在
        $re = $this->where("cover like '{$data['cover']}'")->select();
        if(count($re)>0 && empty($data['gid'])){//数据存在，来源于群组，新增是微博数据，则将群组数据去掉，替换成微博数据
            $saveMap['id'] = array('in',implode(',',getSubByKey($re,"id")));
            $saveData['status'] = 1;
            $this->where($saveMap)->save($saveData);
        }
        $fileId = $this->add($data);
	$rs = service("Tag")->setFileTags($fileArray['tag'], $fileId);
	return $fileId;
    }
    //删除资料
    
    //设置任意_count值
    public function setCount($pram, $count, $set){
	$data["{$set}_count"] = intval($count);
        $rs = $this->where($pram)->save($data);
	return $rs;
    }
    
    //预览计数
    public function countRead($pram, $count) {
	return $this->setCount($pram, $count, "read");
    }

    //下载计数
    public function countDown($pram, $count) {
	return $this->setCount($pram, $count, "down");
    }

    //顶行为计数
    public function countDing($pram, $count) {
	return $this->setCount($pram, $count, "ding");
    }

    //踩行为计数
    public function countCai($pram, $count) {
	return $this->setCount($pram, $count, "cai");
    }

    //收藏计数
    public function countFavorite($pram, $count) {
	return $this->setCount($pram, $count, "favorite");
    }
    
    //更新图片库图片长宽
    public function doUpdateSize(){
        set_time_limit(300);
        $fileDao = M('file');
        $fileTypeDao = M('file_type');
        $typeRe = $fileTypeDao->where("name like '%图片库%'")->find();
        $fileList = $fileDao->select();
        foreach ($fileList as $key => $value) {
            if($value['pid'] == $typeRe['id']){//type是图片库类型ID
                $imagsInfo = getimagesize(UPLOAD_URL.$value['cover']);
                if($imagsInfo === false || empty($imagsInfo[0])){
                    $data['status'] = 1;
                    $fileDao->where("id = {$value['id']}")->save($data);
                }else{
                    $imageJson['width'] = $imagsInfo[0];
                    $imageJson['height'] = $imagsInfo[1];
                    $data['length_width'] = json_encode($imageJson);
                    $data['status'] = 0;
                    $fileDao->where("id = {$value['id']}")->save($data);
                    $ids[] = $value['id'];
                }
                $this->doThumb($value['cover']);
            }
        }
        return $ids;
    }
    //更新图片缩略图
    public function doThumb($imageUrl) {
        $imageUrl = preg_replace("/^\./",'',$imageUrl);
        if (empty($imageUrl) || !fopen(UPLOAD_URL.$imageUrl,'r')) {
            return false;
        }
        //解析图片信息
        $imArr = explode('.',$imageUrl);
        $info['extension'] = $imArr[count($imArr)-1];
        $imArr = explode('/',$imageUrl);
        $info['savename'] = $imArr[count($imArr)-1];
        $info['savepath'] = str_replace($info['savename'],'', $imageUrl);
        unset($imArr);
        //缩略
        $size['small']['x'] = 120;
        $size['small']['y'] = 120;
        $size['middle']['x'] = 465;
        $size['middle']['y'] = -1; //不限制
        $smallpic = $info['savepath'] . 'small_' . $info['savename'];
        $middlepic = $info['savepath'] . 'middle_' . $info['savename'];
        //判断资源服务器是否已经存在
        include_once SITE_PATH . '/addons/libs/Image.class.php';
        require_cache(SITE_PATH . "/addons/libs/WaterMark/WaterMark.class.php");
        mkdir(SITE_PATH . '/data/uploads/temp/',0777);
        $small_pic = Image::thumb(UPLOAD_URL.$imageUrl, SITE_PATH . '/data/uploads/temp/' . 'small_' . $info['savename'], '', $size['small']['x'], $size['small']['y']);
        $middle_pic = Image::thumb(UPLOAD_URL.$imageUrl, SITE_PATH . '/data/uploads/temp/' . 'middle_' . $info['savename'], '', $size['middle']['x'], ($size['middle']['y'] == -1) ? 'auto' : $size['middle']['y'] );
        if ($info['extension'] != 'gif') {
            WaterMark::iswater(SITE_PATH . '/data/uploads/temp/' . 'small_' . $info['savename']);
            WaterMark::iswater(SITE_PATH . '/data/uploads/temp/' . 'middle_' . $info['savename']);
        }
        if( $small_pic){
            $small_pic_ftp = service("File")->fileUpload("/" . $smallpic, SITE_PATH . '/data/uploads/temp/' . 'small_' . $info['savename']);
        }
        if ($middle_pic) {
            $middle_pic_ftp = service("File")->fileUpload("/" . $middlepic, SITE_PATH . '/data/uploads/temp/' . 'middle_' . $info['savename']);
        }
        if ($middle_pic_ftp && $small_pic_ftp) {
            unlink(SITE_PATH . '/data/uploads/temp/' . 'middle_' . $info['savename']);
            unlink(SITE_PATH . '/data/uploads/temp/' . 'small_' . $info['savename']);
        }
        return true;
    }
}