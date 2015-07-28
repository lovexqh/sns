<?php
//图片墙
class PictureAction extends Action{

	function index(){
		/**
		 * 图片墙显示页面
		 */
		$picture = D('Photo','photo')->getPhotoAllList();
		//print_r($picture);
		$this->assign('picture',$picture);
		$this->display();
	}
	function getOwninfo(){
		$id = isset($_POST['pid']) ? $_POST['pid'] : '' ;
		/*
		<div class="ruserinfo">
		<div  class="ruserpic">
		<img alt="{$pic['userId']|getUserName}" src="{$pic['userId']|getUserFace}" width="50" height="50" />
		</div>
		<div class="ruseri">
		<div style="width:100%;height:20px;line-height:20px;"><a style="color:#3089e3;" href="{:U('home/Space/index',array('uid'=>$pic['userId']))}" title="{$pic['userId']|getUserName}" >{$pic['userId']|getUserName}</a></div>
		<div style="width:100%;height:30px;line-height:30px;"><a href="{:U('photo/Index/album',array('id'=>$pic['albumId'],'uid'=>$pic['userId'],'iframe'=>'yes'))}">{$pic['albumname']}</a></div>
		</div>
  		</div>
  		*/
	}
	function getinfo(){
		$id = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '' ;
		$out = '';
		if(!empty($id)){
			$photo =  D('Photo','photo')->getPhotoById($id);
			
			//$article = M('comment')->
			
		}
		//echo $out;
		//print_r($photo);
		$this->assign('photo',$photo);
		$this->display();
	}
}
?>
