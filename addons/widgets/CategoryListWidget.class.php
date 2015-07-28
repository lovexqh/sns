<?php
class CategoryListWidget extends Widget{
	//type:select
	var $style = '';
	public function render( $data ){
		//初始化参数
		if(empty($data['type']))		$data['type']		= 'select';
		if(empty($data['form_name']))	$data['form_name']	= 'categorylist';
		if(empty($data['form_id']))		$data['form_id']	= 'categorylist';
		if(empty($data['uid']))			$data['uid']		= $_SESSION['mid'];
		if(!empty($data['style'])){
			$style = '';
			foreach($data['style'] as $key=>$value){
				$style .= $key.":".$value.";";
			}
			$this->style = $style;
		}
		//创建默认专辑
		$pre	=	C('DB_PREFIX');
		if(M()->table("{$pre}video_category")->where("isDel=0 AND userId='".$data['uid']."'")->count()==0){
			$category['cTime']		=	time();
			$category['mTime']		=	time();
			$category['userId']	=	$data['uid'];
			$category['name']		=	getShort(getUserName($data['uid']),5).'的专辑';
			$category['privacy']	=	1;
			M()->table("{$pre}video_category")->add($category);
		}

		//获取专辑列表数据
		$data['data']	=	M('video_category')->where("isDel=0 AND userId='".$data['uid']."'")->findAll();
		return $this->renderFile( $data );
	}
	/*
		Widget模版在第一次初始化的时候，写不进数据，故先改成如下形式。
	*/
	protected function renderFile( $data ){
		if($data['type'] != 'no'){
			$type = ' class="easyui-combobox" ';
		}
		$out	=	'<select '.$type.' name="'.$data['form_name'].'" comboname="'.$data['form_name'].'" style="'.$this->style.'" id="'.$data['form_id'].'">';
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
}