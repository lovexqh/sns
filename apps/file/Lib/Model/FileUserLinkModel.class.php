<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AttachUserLinkModel
 *
 * @author liushaochen
 */
class FileUserLinkModel extends Model {

    //put your code here
    protected $tableName = 'file_user_link';

    //获取该用户是否对文档进行操作
    //@pram $map = array();2
    public function countAttachUserLink($map) {
	$count = $this->where($map)->count();
	return $count;
    }

    //用户对资料库的操作记录
    //return 该类操作记录数(单一对象)
    public function setFile($uid, $attach_id, $pram, $value = 1) {
	$mapA['attach_id'] = intval($attach_id);
	$rs = M("file")->where($mapA)->find();
	$map['file_id'] = $rs['id'];
	$map['uid'] = intval($uid);
	$data["{$pram}_status"] = $value;
	$data["{$pram}_time"] = time();
	$count = $this->countAttachUserLink($map);
	if (!$count) {
	    $data['uid'] = $map['uid'];
	    $data['file_id'] = $rs['id'];
	    $rs1 = $this->add($data);
	} else {
	    $rs1 = $this->where($map)->save($data);
	}
	unset($map['uid']);
	$map["{$pram}_status"] = $value;
	$countStr = $this->where($map)->count();
	return intval($countStr);
    }

    //预览操作处理
    public function readAttach($uid, $attach_id) {
	$count = $this->setFile($uid, $attach_id, "preview");
	$fileMap['attach_id'] = $attach_id;
	$rs2 = D("File", "file")->countRead($fileMap, $count);
	return $rs2;
    }

    //下载操作处理
    public function downloadAttach($uid, $attach_id) {
	$count = $this->setFile($uid, $attach_id, "download");
	$fileMap['attach_id'] = $attach_id;
	$rs2 = D("File", "file")->countDown($fileMap, $count);
	return $rs2;
    }

    //下载操作处理
    public function favoriteAttach($uid, $attach_id, $value = 1) {
	$count = $this->setFile($uid, $attach_id, "favorites", $value);
	if ($value) {
	    $fileMap['attach_id'] = $attach_id;
	    $rs2 = D("File", "file")->countFavorite($fileMap, $count);
	} else {
            $fileMap['attach_id'] = $attach_id;
	    $rs2 = D("File", "file")->countFavorite($fileMap, $count);
	}
	return $rs2;
    }

    //顶踩操作处理
    //$ding 顶踩操作
    public function dingCaiAttach($uid, $attach_id, $ding) {
	$count = $this->setFile($uid, $attach_id, "ding_cai", intval($ding));
	$fileMap['attach_id'] = intval($attach_id);
	if ($ding == 1) {
	    $rs2 = D("File", "file")->countDing($fileMap, $count);
	} else if ($ding == 2) {
	    $rs2 = D("File", "file")->countCai($fileMap, $count);
	}
	return $rs2;
    }

    //最近下载记录
    public function getDownloadLog($attach_id, $count = 5) {
	$attach_id = intval($attach_id);
	//数据隔离！
	if ($_SESSION['cid'] != -1) {
	    $joinStr = " AND cid IN (-1,{$_SESSION['cid']}) ";
	} else {
	    $joinStr = "";
	}
	$data = $this->join("{$this->tablePrefix}user AS user ON user.uid={$this->tablePrefix}{$this->tableName}.uid {$joinStr}")
			->where("file_id={$attach_id} AND download_status=1")
			->field("download_time,user.uname,user.uid AS uid,id")
			->order("download_time DESC")->limit($count)->select();
	return $data;
    }

    public function preview_status($uid,$file_id){
        $status = $this->where("file_id={$file_id} and uid={$uid}")->field("preview_status")->find();
        $status = $status['preview_status'] == 1 ? 1 : 0;
        return $status;
    }
}

?>
