<?php
/**
 +------------------------------------------------------------------------------
 * ESN 班级空间荣誉模型 Model
 +------------------------------------------------------------------------------
 * @category	ESN HonorModel
 * @package		ESN Lib/Model
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午10:48:18
 +------------------------------------------------------------------------------
 */
class HonorModel extends Model{
		var $tableName = 'class_honor';
		
		
		/**
		 * sendHonor
		 * 发表表彰
		 * 
		 
		 * @param $sendInfo  附加信息和发送方式
		 * @param $giftInfo  礼品信息
		 */
		public function sendHonor($toUid,$senddata,$formUid,$classid){
			
			
			//判断参数是否合法.不合法返回false
			if(!is_numeric($formUid)){				
				return '非法操作！';
			}
			$toUser = explode(',',$toUid);
			$senddata['hTime']=time();
			$res=$this->add($senddata);
			
			//如果入库过程成功.则做相应的处理
			if($res){
				$map['id']=$res;
				foreach($toUser as $fid)//记录每个人接收礼物，并发送通知
				{
				  $map['uid']=$fid;
				  D('HonorList')->add($map);
				  $data['sendback']     = '<br/><a href="'.U('space/Class/showHonor',array('classid'=>$classid,'hid'=>$res)).'">请到班级空间内查看</a>';
				  X('Notify')->send($fid,'space_honorsend',$data,$formUid);
				}				
				return true;
			}else{
				return '发送失败！';
			}	
		}
		/***********
		表彰列表
		******/
		public function HonorList($classid)
		{
		  $return=$this->where('classid='.$classid)->order('id DESC')->findPage(10);
		  foreach($return['data'] as $k=>&$v){
		  	$v['content']=	text($v['content']);
		  }
		  return $return;
		}
   }