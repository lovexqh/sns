<?php
class AlbumListWidget extends Widget{
	private $classinfo;
	private $classname;
	//type:select
	public function render( $data ){
		//初始化参数
		if(empty($data['type']))		$data['type']		= 'select';
		if(empty($data['form_name']))	$data['form_name']	= 'albumlist';
		if(empty($data['form_id']))		$data['form_id']	= 'albumlist';
		
	    $classinfo=$this->_class_info($data['classid']);
	    $classname=$classinfo['uc_class']['bj'];
		//创建默认相册
		$pre	=	C('DB_PREFIX');
		if(D()->table("{$pre}class_photo_album")->where("isDel=0 AND classid='".$data['classid']."'")->count()==0){
			$album['cTime']		=	time();
			$album['mTime']		=	time();
			$album['classid']	=	$data['classid'];
			$album['name']		=	$classname.'的相册';
			$album['privacy']	=	1;
			D()->table("{$pre}class_photo_album")->add($album);
		}

		//获取相册列表数据
		$data['data']	=	M('class_photo_album')->where("isDel=0 AND classid='".$data['classid']."'")->findAll();
		return $this->renderFile( $data );
	}
	/*
		Widget模版在第一次初始化的时候，写不进数据，故先改成如下形式。
	*/
	protected function renderFile( $data ){
		$out	=	'<select name="'.$data['form_name'].'" id="'.$data['form_id'].'">';
		foreach($data['data'] as $vo){
			if( $vo['id'] == intval($data['selected']) ){
				$out	.=	'<option value="'.$vo['id'].'" selected="selected">'.getShort($vo['name'],13).'</option>';
			}else{
				$out	.=	'<option value="'.$vo['id'].'">'.getShort($vo['name'],13).'</option>';
			}
		}
		$out	.=	'</select>';

		return	$out;
	}
	/*
		班级基础信息调用公共方法
		
		1.从Api获取班级基础信息
		2.从班级基础信息表中（class）获取班级设置基本信息
		
	*/
	public function _class_info($classid)
	{
		$data['uc_class']=uc_class_get_id($classid);
		$data['uc_classcount']=uc_studentcount_get_id($classid);
    	$data['sns_class']=D('ClassBasic')->where('classid='.$classid)->find();
		//var_dump($data);
		return $data;
	}
}