<?php
/**
 +------------------------------------------------------------------------------
 * ESN 班级相册 Model
 +------------------------------------------------------------------------------
 * @category	ESN ClassAlbumModel
 * @package		ESN Lib/Modul
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午10:48:18
 +------------------------------------------------------------------------------
 */
class ClassAlbumModel extends Model{
		var $tableName = 'class_photo_album';
		//更新相册图片数量
	    public function updateAlbumPhotoCount($aid) {
			$count	=	D('ClassPhoto')->where("albumId='$aid' AND isDel=0")->count();
			$map['photoCount']	=	$count;
			$map['mTime']	=	time();
			return $this->where("id='$aid'")->save($map);
		}
		//删除图片
		public function deleteClassPhoto($pids,$classid,$isAdmin=0) {
			//解析ID成数组
			if(!is_array($pids)){
				$pids	=	explode(',',$pids);
			}
			//非管理员只能删除自己的图片
			if(!$isAdmin){
				$map['classid']	=	$classid;
			}
			//获取图片信息
			$photoDao  = D('ClassPhoto');
			$map['id'] = array('in',$pids);
			$photos	   = $photoDao->where($map)->findAll();
			//删除封面
			foreach ($photos as $key => $value){
				$id = $value['albumId'];
				$data['coverImageId'] = '';
				$data['coverImagePath'] = '';
				D('ClassAlbum')->where(array('id'=>$id))->save($data);
			}
			///删除图片
			//$save['isDel']	=	1;
			$result	   = $photoDao->where($map)->delete();
	
			if($result){
				foreach($photos as $v){
					$attachIds[]	=	$v['attachId'];
					//重置相册图片数
					$this->updateAlbumPhotoCount($v['albumId']);
				}
				//处理附件			
				model('Attach')->deleteAttach($attachIds, true);
				return true;
			}else{
				return false;
			}
		}
		
		//删除相册
	  function deleteAlbum($aids,$classid,$isAdmin=0) {
		  //解析ID成数组
		  if(!is_array($aids)){
			  $aids	=	explode(',',$aids);
		  }
		  //非管理员只能删除自己的图片
		  if(!$isAdmin){
			  $map['classid']	=	$classid;
		  }
		  //同步删除图片及附件
		  $album['albumId']	=	array('in',$aids);
		  $photos		=	D('ClassPhoto')->field('id')->where($album)->findAll();
		  foreach($photos as $v){
			  $photoIds[]	=	$v['id'];
		  }
		  //处理图片及附件
		  $this->deleteClassPhoto($photoIds,$classid,$isAdmin,$delFile);
  
		  //删除相册		
		  $map['id']		=	array('in',$aids);
		  //$save['isDel']	=	1;
		  $result	=	$this->where($map)->delete();			
		  if($result){
			  return true;
		  }else{
			  return false;
		  }
	  }
	  //通过相册ID 获取图片集
	function getPhotos($classid,$albumId,$type,$order='id ASC',$shownum=5) {
		//某个人的全部图片
		if($type=='mAll'){
			$map['classid']	=	$classid;
		}else{
		//某个专辑的全部图片(无type下默认)
			$map['albumId']	=	$albumId;
			$map['classid']	=	$classid;
		}
		$map['isDel']	=	0;
		$result	=	 D('ClassPhoto')->order($order)->where($map)->findAll();
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 班级相册（公共方法）
	 +----------------------------------------------------------
	 * @param	array map 查询条件
	 * @return	array data 相册数据信息
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午01:41:31
	 +----------------------------------------------------------
	 */
	public  function _albums($classid) {
		$map['classid']	=$classid;
		$map['isDel']	=	0;
		$data	=	D('ClassAlbum')->order("mTime DESC")->where($map)->findall();
		return $data;
	}
   }