<?php

class FileTypeModel extends Model {

    private $icopath;

    public function _initialize() {
	$this->icopath = './apps/file/Tpl/default/Public/images/ico/';
    }

    public function addType($data) {
	//检查数据是否为空
	//-1,类型名为空.-2，描述为空.-3图标为空或者不存在
	if (empty($data['name'])) {
	    return -1;
	}
	if (empty($data['explain'])) {
	    return -2;
	}
	if (empty($data['ico']) || !file_exists($this->icopath . $data['ico'])) {
	    return -3;
	}

	//添加数据
	$rs = $this->add($data);
	return $rs;
    }

    public function getType($id = null, $pid = null, $is_add = false,$proscenium) {
	if (isset($id)) {
	    if (is_array($id)) {
		$map['id'] = array('in', $id);
	    } else {
		$map['id'] = $id;
	    }
	}

	//如果不是管理员，不能使用招聘
//	if (!$is_add && !service('SystemPopedom')->hasPopedom($this->mid, 'admin/*/*', false)) {
//	    $map['type'] = array('NOT IN', array('管理员创建', '文档库'));
//	} else {
//	    if ($is_add) {
//		$map['type'] = array('NOT IN', array('文档库'));
//                if($proscenium == 1){
//                   $map['state'] = 0; 
//                }
//	    }
//	}
	$rs = $this->where($map)->findAll();
	if ($rs) {
	    foreach ($rs as &$value) {
		if (!empty($value['templet'])) {
		    $fieldId = strpos($value['templet'], ',') !== false ? explode(',', $value['templet']) : $value['templet'];
		    $value['extraField'] = D('FileWidget')->getWidget($fieldId, $pid);
		}
	    }
	}
	if (isset($id) && !is_array($id)) {
	    $rs = $rs[0];
	}
	return $rs;
    }

    public function getExtraField($data) {
	$result = array();
	foreach ($data as $value) {
	    $result[$value['label']] = $value['field'];
	}
	return $result;
    }

    public function getTypeName($id) {
	$map['id'] = $id;
	$rs = $this->where($map)->find();
	return $rs['name'];
    }

    public function setState($id, $state) {
	$map['state'] = $state;
	return $this->where('id=' . $id)->save($map);
    }

    public function getIcoList() {
	if (!is_dir($this->icopath))
	    return false;
	return $this->traversalDir($this->icopath);
    }

    public function getPosterTypeByIdArray() {
	$posterSmallType = $this->field('id,name')->findAll();

	$posterST = array();
	foreach ($posterSmallType as $value) {
	    $posterST[$value['id']] = $value['name'];
	}
	return $posterST;
    }

    /**
     * traversalDir
     * 遍历目录获得ico.能迭代目录
     * @param mixed $path 目录
     * @access private
     * @return void
     */
    private function traversalDir($path) {
	$result = array();
	$file = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(($path)));
	$i = 0;
	foreach ($file as $key => $value) {
	    //排除.svn目录的文件
	    if (!strpos($value->getPathname(), ".svn") && strpos($value->getFilename(), ".gif")) {
		$result[$i] = $value->getFilename();
		$i++;
	    }
	}
	return $result;
    }

    public function getPreview($type_name){
        $Map['name'] = t($type_name);
        $data = $this->where($Map)->field("is_preview")->find();
        return $data['is_preview'];
    }
    
}