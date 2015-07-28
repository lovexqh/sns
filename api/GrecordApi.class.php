<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yangxiaowei
 * Date: 14-3-22
 * Time: 上午10:36
 * To change this template use File | Settings | File Templates.
 */
class GrecordApi extends Api{
	/**
	 * 
	 * 在数据库里创建记录，返回ID
	 * 总共34个参数 -_-!
	 * @author yangxiaowei
	 */
	public function index(){
		$configs = model('Xdata')->lget('upload');
		$broad = model('Broadcast');
		$video =  model('Video');
		$map['schoolid']	= empty($_SESSION['ucInfo']['schoolid'])?'0':$_SESSION['ucInfo']['schoolid'];//关键
		$map['uid']			= empty($_SESSION['mid'])?'0':$_SESSION['mid'];//关键
		$map['realname']	= empty($_SESSION['ucInfo']['xm'])?'未知':$_SESSION['ucInfo']['xm'];//关键
		$map['appid']		= empty($_REQUEST['appid'])?'0':$_REQUEST['appid'];
		$map['appfid']		= empty($_REQUEST['appfid'])?'0':$_REQUEST['appfid'];
		$map['title']		= '未命名文件';
		$map['info']		= empty($_REQUEST['info'])?'':$_REQUEST['info'];
		$map['categoryid']	= empty($_REQUEST['categoryid'])?'':$_REQUEST['categoryid'];
		$map['class']		= empty($_REQUEST['class'])?'':$_REQUEST['class'];
		$map['info']		= empty($_REQUEST['info'])?'':$_REQUEST['info'];
		$map['section']		= empty($_REQUEST['sectio'])?'':$_REQUEST['section'];
		$map['subject']		= empty($_REQUEST['subject'])?'':$_REQUEST['subject'];
		$map['grade']		= empty($_REQUEST['grade'])?'':$_REQUEST['grade'];
		$map['publisher']	= empty($_REQUEST['publisher'])?'':$_REQUEST['publisher'];
		$map['cell']		= empty($_REQUEST['cell'])?'':$_REQUEST['cell'];
		$map['volume']		= empty($_REQUEST['volume'])?'':$_REQUEST['volume'];
		$map['courseid']	= empty($_REQUEST['courseid'])?'':$_REQUEST['courseid'];
		$map['type']		= empty($_REQUEST['type'])?'':$_REQUEST['type'];
		$map['attribute']	= empty($_REQUEST['attribute'])?'':$_REQUEST['attribute'];
		$map['serverpath']	= $configs['vodserver'];//关键
		$map['savepath']	= '';//关键
		$map['filename']	= empty($_REQUEST['filename'])?'':$_REQUEST['filename'];
		$map['outfilename']	= empty($_REQUEST['outfilename'])?'':$_REQUEST['outfilename'];
		$map['thumb']		= empty($_REQUEST['thumb'])?'':$_REQUEST['thumb'];
		$map['size']		= empty($_REQUEST['size'])?'':$_REQUEST['size'];		
		$map['times']		= empty($_REQUEST['times'])?'':$_REQUEST['times'];
		$map['filecode']	= 'file'.rand(1,9999);//关键
		$map['status']		= 1;//转码状态(1转码完成 2 转码失败 0未转码)		
		$map['state']		= 1;//审核状态
		$map['original']	= empty($_REQUEST['original'])?'':$_REQUEST['original'];
		$map['price']		= empty($_REQUEST['price'])?'':$_REQUEST['price'];
		$map['average']		= empty($_REQUEST['average'])?'':$_REQUEST['average'];
		$map['privacy']		= empty($_REQUEST['privacy'])?'':$_REQUEST['privacy'];
		$map['ctime']		= time();
			
		$result = $video->createItem($map);
		//$handle=fopen("debug911.txt","a");
		//fwrite($handle,"\r\n".$video->getLastSql());
		//fclose($handle);	
		//exit;
		if($result){
			$map['resid']		=	$result;
			$map['filetype']	=	'';	
			$map['flag']		=	1;
			$map['recordtime']	=	time();
			$bresult = $broad->createItem($map);
			//$weburl = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$weburl = "cond.gridinfo.com.cn#index.php?app=api&mod=Grecord&act=dopost";
			$uploadurl = "condvod.gridinfo.com.cn#uploadnew.aspx";
			//$returnStr = "GcamStudio://id=8753|cond.gridinfo.com.cn#index.php?app=api&mod=Grecord&act=dopost|condvod.gridinfo.com.cn#uploadnew.aspx"
			$returnStr = 'id='.$bresult.'|'.$weburl.'|'.$uploadurl;
			return $returnStr;
		}else{
			return 0;
		}
		//echo($inputVideo->getLastSql());
	}
	/**
	 * 
	 * 处理录屏软件提交的filecode和保存路径更新
	 * broadcast=8753 video-new=13968
	 */
	public function dopost(){
		if(empty($_SESSION['Grecord'])){
			$_SESSION['Grecord'] = 1;
			exit;
		}
		$broad = model('Broadcast');
		$video = model("Video");
		$relation = model('BroadcastRelation');
		//检查是否空值
		$v = $this->ifempty($_REQUEST);
		if($v != 1){
			return $v.' empty';
		}
		//查询broadcast表
		$bid = $_REQUEST['id'];	
		$broadinfo = $broad->getItem($bid);
		
		//先根据id获取resid,然后调用两个model的删除方法
		if($_REQUEST['action'] == 'del'){		
			$result = $video->delItem($broadinfo['resid']);	
			
			if($result !== false){				
					$delbroad = $broad->delItem($bid);
				if($delbroad !== false){
					unset($_SESSION['Grecord']);
					return 'success';
				}else{
					return 'del video fail';
				}
			}else{	
				return 'del broadcast fail';
			}

		}
		//先根据id获取所有字段，获取resid,
		//再根据返回的filecode和savepath把这两个字段信息更新到两个model里。
		//第三,再创建一个记录。关联表。
		if($_REQUEST['action'] == 'update'){			
			$map['id']			= $broadinfo['resid'];
			$map['filecode']	=	$_REQUEST['filecode'];
			$map['savepath']	=	$_REQUEST['savepath'];
			$map['title']	=	$_REQUEST['outfilename'];
			$map['outfilename']	=	$_REQUEST['outfilename'];			

			$result = $video->updateItem($map);
			if($result !== false){
				if($broadinfo['appid'] != '0'){

					$data['bid'] 	= 	$bid;
					$data['appid']	=	$broadinfo['appid'];
					$data['appfid']	=	$broadinfo['appfid'];
					$data['from']	=	1;
					//if($relation->getItem($bid)){
						$relrtn = $relation->createItem($data);
						if($relrtn !== false){
							unset($_SESSION['Grecord']);
							return 'success';
						}else{		
							return 'create fail';
						}
					//}else{
					//	return 'already exist';
					//}

				}
			}else{				
				//$handle=fopen("http://127.0.0.1/vcppupload/debug999.txt","a");
				//fwrite($handle,"\r\n".$video->getLastSql());
				//fclose($handle);				
				return 'update video fail';
			}		
		}
	}
	public function ifempty($map){
		$result = 1;//无空值
		foreach($map as $key => $value){
			if(empty($value)){
				$result = $key;//key有空值
				break;
			}
		}
		return $result;
	}
	public function test(){
		dump($_SERVER);
	}
}
