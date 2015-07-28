<?php
/**
 +------------------------------------------------------------------------------
 * ESN 班级空间座位模型 Model
 +------------------------------------------------------------------------------
 * @category	ESN SeatModel
 * @package		ESN Lib/Model
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午10:48:18
 +------------------------------------------------------------------------------
 */
class SeatModel extends Model{
		var $tableName = 'class_seat';
		private $db_prefix;
		function _initialize(){
			$this->db_prefix = C('DB_PREFIX');
		}
	/**
	 +----------------------------------------------------------
	 * 班级座位信息 （公用方法）
	 +----------------------------------------------------------
	 * @param	array sns_userinfo 当前已有座位人员
	 * @param	int uc_count 班级总人数
	 * @param	array sns_existseat 已经有座位成员信息
	 * @param	array xms 姓名数组
	 * @param	array identityids 身份数组
	 * @param	array headpic 头像数组
	 * @param	array user_info 未安排座位人员
	 * @return	array data 姓名、identityid、headpic、count、row、col
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午01:43:36
	 +----------------------------------------------------------
	 */
	public function _seat($classid,$classinfo){
	   	$width=100/$classinfo['sns_class']['seatcol'];
	   	$data['width']=$width-0.3;
	   
	   	$usercount=uc_studentcount_get_id($classid); //获取班级总人数
	   	$uc_count=$usercount['count'];
	   
	   	$sns_existseat=D('Seat')->field("identityid,row,col")->where("classid=".$classid)->findall();
	   	$obj=array();//将已安排座位人员数据存成数组形式，方便下面in_array
	   	foreach($sns_existseat as $k=>$v){
			$obj[$k]=$v['identityid'];
	   	}
	   	$userinfo=uc_student_get_id($classid);//api获取班级所有成员信息
	   	$xms=array();//姓名
	   	$identityids=array();//身份id
	   	$headpic=array();//头像
	   	$user_info=array();
	    $n=0;
	   	foreach($userinfo as $k=>$v){
		   if(in_array($v['identityid'],$obj)){
			   foreach($sns_existseat as $key=>$vo){
					if($vo['identityid']==$v['identityid']){
						$xms[$vo['row']][$vo['col']]=$v['xm'];
						$identityids[$vo['row']][$vo['col']]=$v['identityid'];
						if($v['uid']){   
							$follow=M('')->field('snsuser.uid AS fuid')
							   ->table("{$this->db_prefix}user AS snsuser LEFT JOIN {$this->db_prefix}ucenter_user_link AS uc_user ON snsuser.uid=uc_user.uid")
							   ->where("uc_user.uc_uid=".$v['uid'])
							   ->find();
							if($follow)
								$headpic[$vo['row']][$vo['col']]=getUserFace($follow['fuid'],'big');
							else
								$headpic[$vo['row']][$vo['col']]="__THEME__/images/user_pic_bigjj.gif";
						}
						else{
							$headpic[$vo['row']][$vo['col']]="__THEME__/images/user_pic_bigjj.gif";
						}
					}
			   }
		   }
		   else{
			   $user_info[$n]=$v;
				if($v['uid']){
					$follow=M('')->field('snsuser.uid AS fuid')
					   ->table("{$this->db_prefix}user AS snsuser LEFT JOIN {$this->db_prefix}ucenter_user_link AS uc_user ON snsuser.uid=uc_user.uid")
					   ->where("uc_user.uc_uid=".$v['uid'])
					   ->find();
					if($follow)
						$user_info[$n]['pic']=getUserFace($follow['fuid'],'big');
					else
						$user_info[$n]['pic']="__THEME__/images/user_pic_bigjj.gif";
				}
				else{$user_info[$n]['pic']="__THEME__/images/user_pic_bigjj.gif";}
				$n++;
		   }
	    }
	    $data['count']=$uc_count;
		$data['row']=$classinfo['sns_class']['seatrow'];//班级几排几列
		$data['col']=$classinfo['sns_class']['seatcol'];
		$data['userlist']=$user_info;//未安排座位信息表
		$data['xms']=$xms;
		$data['identityids']=$identityids;
		$data['headpic']=$headpic;
		return $data;
	}
}