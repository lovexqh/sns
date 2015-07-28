<?php
/**
 +------------------------------------------------------------------------------
 * 广场后台管理Action
 +------------------------------------------------------------------------------
 * @category	square
 * @package		 Lib/Action
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-1 上午02:48:21
 +------------------------------------------------------------------------------
 */
import('admin.Action.AdministratorAction');
class AdminAction extends AdministratorAction {
	
	/**
	 +----------------------------------------------------------
	 * 增加广场分类子栏目
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:46:40
	 +----------------------------------------------------------
	 */
	function addcategory()
	{
		//接收要增加的栏目名称及父类ID
		$data['category_name'] = $_POST['category_name'];
		$data['p_id'] = intval($_POST['p_id']);
		
		//如果没有父类ID和要增加的栏目名称
		if(empty($data['category_name'])||empty($data['p_id'])){
			$this->error('非法操作!');
		}else{
			//查找该父类
			$path = M('square_category')->field('path')->where('id='.$data['p_id'])->find();
			//是否具有子栏目
			$sele = M('square_category')->where('p_id='.$data['p_id'])->findAll();
			
			//不具有其他子栏目,增加一个‘其他’
			if(empty($sele)){
				$sele = M('square_video')->where('category_id='.$data['p_id'])->findAll();
				//该栏目下面有存储具体资源
				if($sele){
					//增加一个‘其他’栏目
					$addfa = D('SquareCategory')->addelse();
					//将原栏目下资源转移到‘其他’下
					foreach ($sele as $v){
						$v['category_id'] = $addfa;
						M('square_video')->where('id='.$v['id'])->save($v);
					}
				}
			}
			
			//如果具有其他子栏目，则直接增加
			$add = M('square_category')->add($data);
			if($path){
				
				$data['path'] = $path['path'].'-'.$add;
				$save = M('square_category')->where('id='.$add)->save($data);
				if($save)
					$this->success('添加成功！');
				else 
					$this->error('添加失败！');
			}else 
				$this->error('父类不存在！');
		}
		
	}
	//
	/**
	 +----------------------------------------------------------
	 * 修改栏目信息跳转
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:49:48
	 +----------------------------------------------------------
	 */
	function modifycategory()
	{
		
		$id = intval($_GET['id']);//要修改的栏目ID
		if($id){
			$data['p_cate']= model('SquareCategory')->where('id='.$id)->find();
			//echo M()->getLastSql();exit();
			//$data['category'] = D('SquareCategory')->getTree(2);  
		
	
// 	 	$cate = $_GET['cate'];//要显示的应用栏目
		
// 		if(empty($cate))//如果没有选择，则显示博客的所有栏目
// 			$cate='blog';
// 		if($cate=='blog'){
// 			$data['category'] = D('SquareCategory')->getTree(1);
// 			if(empty($data['category']))
// 				$data['category'][0]=M('square_category')->where('id=1')->find();
// 		}elseif ('video'==$cate)
// 		{
// 			$data['category'] = D('SquareCategory')->getTree(2);
		
// 			if(empty($data['category']))
// 				$data['category'][0]=M('square_category')->where('id=2')->find();
// 		}elseif('resource'==$cate){
// 			$data['category'] = D('SquareCategory')->getTree(3);
				
// 			if(empty($data['category']))
// 				$data['category'][0]=M('square_category')->where('id=3')->find();
// 		}elseif('tool'==$cate){
// 			$data['category']=D('SquareCategory')->getTree(4);
// 			//echo M()->getLastSql();
// 			//  			dump($data['category']);
// 			// 			exit();
				
// 			if(empty($data['category'])){
// 				$data['category'][0]=M('square_category')->where('id=4')->find();
// 			}
				
// 		}
// 		//dump($data);exit();
// 		$data['square'] = $cate;
		 
		

		$this->assign($data);
		$this->display('modifycategory');
		}else
			$this->error('非法操作！');
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
	/**
	 +----------------------------------------------------------
	 * 修改栏目信息
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:50:14
	 +----------------------------------------------------------
	 */
	function doModifyGroup()
	{
		//接收栏目修改的信息
		$data['id']		=	intval($_POST['id']);
		$data['p_id']	=	intval($_POST['p_id']);
		$data['category_name']	=	$_POST['category_name'];
		$data['display_order']	=	intval($_POST['display_order']);
		//如果接收的有栏目ID
		if($data['id']){
			//修改信息
			$father = M('square_category')->where('id='.$data['p_id'])->find();
			//dump($father['path']);exit();
			if(empty($father['path'])){
				$father['path']='0';
			}
			$data['path'] = $father['path'].'-'.$data['id'];
			$add = model('SquareCategory')->where('id='.$data['id'])->save($data);
			
			if($add){
				$this->assign('jumpUrl', U('square/Admin/category'));
				$this->success('操作成功！');
			}else {
				$this->error('操作失败！'.$data['id']);
			}
		}else 
		{
			$this->error('非法操作！');
		}
	
	}
	//
	/**
	 +----------------------------------------------------------
	 * 删除栏目信息
	 +----------------------------------------------------------
	 * @return	int 0：失败，1：成功
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:50:45
	 +----------------------------------------------------------
	 */
	public function doDelCategory()
	{
		$map['id'] = t($_POST['ids']);
		if($map['id']){
			
			$row = M('square_category')->where($map)->find();
			if($row){
				$rows = M('square_category')->where($map)->delete();
				if($rows) 
				{	
					echo 1;	
					exit;
				}
			}
		}
		
		echo 0;	
	}
	//
	/**
	 +----------------------------------------------------------
	 * 栏目管理，显示所有栏目信息
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:55:47
	 +----------------------------------------------------------
	 */
	function category()
	{
		$cate = $_GET['cate'];//要显示的应用栏目
	
		if(empty($cate))//如果没有选择，则显示博客的所有栏目
			$cate='blog';
		if($cate=='blog'){
			$data['category'] = D('SquareCategory')->getTree(1);
			if(empty($data['category']))
				$data['category'][0]=M('square_category')->where('id=1')->find();
		}elseif ('video'==$cate)
		{	
			$data['category'] = D('SquareCategory')->getTree(2);
		
			if(empty($data['category']))
				$data['category'][0]=M('square_category')->where('id=2')->find();
		}elseif('resource'==$cate){	
			$data['category'] = D('SquareCategory')->getTree(3);
			
			if(empty($data['category']))
				$data['category'][0]=M('square_category')->where('id=3')->find();
		}elseif('tool'==$cate){
			$data['category']=D('SquareCategory')->getTree(4);
			//echo M()->getLastSql();
//  			dump($data['category']);
// 			exit();
			
			if(empty($data['category'])){
				$data['category'][0]=M('square_category')->where('id=4')->find();
			}
			
		}
		$data['square'] = $cate;
		$this->assign($data);
		$this->display('category');
	
	}
	
	//
	/**
	 +----------------------------------------------------------
	 * 博客管理
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	function blog()
	{
		$data['state']=intval($_GET['state']) ? intval($_GET['state']): 0;//接收要显示的博客的状态
		$data['blog']=$_GET['cate'] ? $_GET['cate'] : 'blog';//要选中的tab
		
		//根据广场-博客表中状态查找博客	
		$data['square_blog']	=	M('square_blog')->where('`state`='.$data['state'])->order('display_order ASC')->findpage(20);
		//获取栏目树
		$data['sqcategory']		=	D('SquareCategory')->getTrees(1);
				
		$this->assign($data);
		$this->display('blog');
	}
	/**
	 +----------------------------------------------------------
	 * 博客删除
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	function blogdelete()
	{
		$map['id'] = array('in', t($_POST['ids']));
		if($map['id']){
			$rows = M('square_blog')->where($map)->delete();
			if($rows) 
				echo '1';
		}
		echo '0';
	}
	/**
	 +----------------------------------------------------------
	 * 博客删除
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	function blogsee()
	{
		$_GET['id'] 	&& $map['id'] 	 = intval($_GET['id']);
		if($map['id']){	
			$blog = M('square_blog')->where('`id`='.$map['id'])->find();
			if($blog){	
				$data = M('blog')->where('`id`='.$blog['blog_id'])->find();
				if($data){	
					$data['category'] = D('SquareCategory')->getTree(1);
					$data['square_id']= $blog['square_id'];
					$data['state'] = $blog['state'];
					
					$this->assign('SquareBlogid',$map['id']);
					$this->assign('datas',$data);
					$this->display();
				}else 
					$this->error('博客不存在');
				
			}else 
				$this->error('博客不在广场中');
			
		}else 
			$this->error('非法操作');
	}
	/**
	 +----------------------------------------------------------
	 * 保存编辑博客
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	function doblogsee()
	{
		$map['id']			=	$_POST['SquareBlogid'];
		$maps['square_id']	= 	intval($_POST['p_id']);
		$maps['state']		=	$_POST['state'];
		
		$res = M('square_blog')->where($map)->save($maps);
		if($res){
			$this->assign('jumpUrl', U('square/Admin/blog'));
			$this->success('操作成功！');
		}else 
			$this->error($map['id']);	
	}
	/**
	 +----------------------------------------------------------
	 * 博客审核排序
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	function doblogOrder()
	{
		$id 		= 	intval($_POST['id']);
		$baseid 	= 	intval($_POST['baseid']);
		if ( $id <= 0 || $baseid <= 0 ) {
			echo 0;
			exit;
		}
		$dao 		= 	M('square_blog');
		$res 		= 	$dao->where('id in ('.$id.','.$baseid.')')->findAll();
		if ( count($res) < 2 ) {
			echo 0;
			exit;
		}
		//转为结果集为array('id'=>'order')的格式
    	foreach($res as $v) {
    		$order[$v['id']] = intval($v['display_order']);
    	}
    	unset($res);
    	//交换order值
		$res = $dao->where('`id`=' . $id)->setField( 'display_order', $order[$baseid] );
    	if($res){
    	 	$res = $dao->where('`id`=' . $baseid)->setField( 'display_order', $order[$id] );
    	}	
    	if($res) echo 1;
    	else echo 0;
	}
	/**
	 +----------------------------------------------------------
	 * 视频审核管理
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	function video()
	{	
		$data['state']	=	intval($_GET['id']) ? intval($_GET['id']):0;//要现实的视频的状态
		$data['video']	=	$_GET['cate'] ? $_GET['cate']: 'video';//要选中的tab名
		//根据广场-博客表中状态查找博客
		$data['square_video'] =	M('square_video')->where('`state`='.$data['state'])->order('display_order ASC')->findpage(20);
		//获取视频栏目树
		$data['sqcategory'] = D('SquareCategory')->getTrees(2);
	
		$this->assign($data);
		$this->display('video');
	} 
	/**
	 +----------------------------------------------------------
	 * 视频删除
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	function videodelete()
	{
		$map['id'] = array('in', t($_POST['ids']));
		if($map['id']){
			$rows = M('square_video')->where($map)->delete();
			if($rows) 
				echo '1';
		}
		echo '0';
	}
	/**
	 +----------------------------------------------------------
	 * 视频查看
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	function videosee()
	{

		$_GET['id'] 	&& $map['id'] 	 = intval($_GET['id']);
		if($map['id']){	
			$video = M('square_video')->where('`id`='.$map['id'])->find();
			if($video){	
				$data = M('video')->where('`id`='.$video['video_id'])->find();
				if($data){	
					$data['category']	 	=	D('SquareCategory')->getTree(2);
					$data['category_id'] 	=	$video['category_id'];
					$data['state']		 	=	$video['state'];
					$data['Squarevideoid']	=	$map['id'];
					
					$this->assign('datas',$data);
					$this->display();
				}else 
					$this->error('视频不存在');
				
			}else 
				$this->error('视频不在广场中');
			
		}else 
			$this->error('非法操作');
	}

	/**
	 * 
	 +----------------------------------------------------------
	 * 保存视频编辑
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	public function dovideosee()
	{
		
		$map['id']			= 	intval($_POST['Squarevideoid']);	
		$maps['category_id']= 	intval($_POST['p_id']);
		$maps['state']		=	intval($_POST['state']);
		
		$res = M('square_video')->where($map)->save($maps);
		if($res){
			$this->assign('jumpUrl', U('square/Admin/video'));
			$this->success('操作成功！');
		}else 
			$this->error('操作失败！');	
	}
	/**
	 * 
	 +----------------------------------------------------------
	 * 视频排序
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	function dovideoOrder()
	{
		$id = intval($_POST['id']);
		$baseid = intval($_POST['baseid']);
		if ( $id <= 0 || $baseid <= 0 ) {
			echo 0;
			exit;
		}
		$dao = M('square_video');
		$res = $dao->where('id in ('.$id.','.$baseid.')')->findAll();
		if ( count($res) < 2 ) {
			echo 0;
			exit;
		}
		//转为结果集为array('id'=>'order')的格式
    	foreach($res as $v) {
    		$order[$v['id']] = intval($v['display_order']);
    	}
    	unset($res);
    	//交换order值
		$res = $dao->where('`id`=' . $id)->setField( 'display_order', $order[$baseid] );
    	if($res){
    	 	$res = $dao->where('`id`=' . $baseid)->setField( 'display_order', $order[$id] );
    	}	
    	if($res) 
    		echo 1;
    	else 
    		echo 0;
	}
	/**
	 +----------------------------------------------------------
	 * 资源审核界面
	 +----------------------------------------------------------
	 * @author	美美2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午05:56:56
	 +----------------------------------------------------------
	 */
	function resource()
	{	
		$data['state']	=	intval($_GET['id']) ? intval($_GET['id']):0;//要审核的资源的状态
		$data['resource']	=	$_GET['cate'] ? $_GET['cate']: 'resource';//要选中的tab名
		//根据广场-博客表中状态查找博客
		$data['square_resource'] =	M('square_resource')->where('`state`='.$data['state'])->order('display_order ASC')->findpage(20);
		//获取视频栏目树
		$data['sqcategory'] = D('SquareCategory')->getTrees(3);
	
		$this->assign($data);
		$this->display('resource');
	} 
	/**
	 +----------------------------------------------------------
	 * 搜索功能
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午02:56:21
	 +----------------------------------------------------------
	 */
	public function search()
	{
		$id		= 	intval($_REQUEST['id']);
		$state	=	intval($_REQUEST['state']);
		$cate	=	$_REQUEST['cate'];
		$ids = M('square_category')->field('id')->where("path like '%-".$id."%'")->findAll();
		$split = $fids = '';
		foreach($ids as $val){
			$fids .= $split.$val['id'];
			$split = ',';
		}
		if($cate=='blog')
		{
			$data['square_blog'] =M('square_blog')->where('`state`='.$state.' AND square_id in ('.$fids.')')->findpage(15);
			$data['sqcategory']  = D('SquareCategory')->getTrees(1);
		}elseif ($cate=='video')
		{
			$data['square_video'] = M('square_video')->where('`state`='.$state.' AND category_id in ('.$fids.')')->findpage(15);
			$data['sqcategory']   = D('SquareCategory')->getTrees(2);
		}else 
		{
			$data['square_resource']  = M('square_resource')->where('`state`='.$state.' AND square_id in ('.$fids.')')->findpage(15);
			$data['sqcategory']   = D('SquareCategory')->getTrees(3);
		}
		if($state==0) 
			$data[$cate] = $cate;
		elseif ($state==1)
			$data[$cate] = $cate.'_admin';
		elseif ($state==2)
			$data[$cate] = $cate.'_elect';
			
		$data['state'] = $state;
		$this->assign($data);
		if ('blog'==$_REQUEST['from'])
			$this->display('blogsearchs');
		elseif ('video'==$_REQUEST['from'])
			$this->display('searchs');
		else 
			$this->display($cate);
	}
}
?>