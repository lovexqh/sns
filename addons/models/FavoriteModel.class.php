<?php
/**
 * 收藏模型
 * 
 * @author Ricker <rickeryu@gridinfo.com.cn>
 */
class FavoriteModel extends Model {
	protected $tableName = 'favorite';
	
	/**
	 * @author RickerYu
	 * @Fields $appConfig : 配置应用的基本参数
	 * url参数  链接名称=>数据库字段
	 */
	protected $appConfig = array(
		'blog'=>array("title"=>'博客收藏','tablename'=>'blog','keyname'=>'id','titlecolumn'=>'title',url=>array('id'=>'fid','mid'=>'uid')),
		'video'=>array("title"=>'视频收藏','tablename'=>'video','keyname'=>'id','titlecolumn'=>'name',url=>array('id'=>'fid','aid'=>'categoryId','uid'=>'userId')),
		'resource'=>array("title"=>'资源收藏','tablename'=>'resource','keyname'=>'id','titlecolumn'=>'title',url=>array('id'=>'fid','uid'=>'uid')),
		'tool'=>array("title"=>'工具收藏','tablename'=>'tool','keyname'=>'id','titlecolumn'=>'title','appconfig'=>'tool',url=>array('id'=>'fid','uid'=>'uid')),
		'event'=>array("title"=>'活动收藏','tablename'=>'event','keyname'=>'id','titlecolumn'=>'title','url'=>array('fid'=>'fid','uid'=>'uid','id'=>'id')),
		'preparechapter'=>array("title"=>'备课册、单元收藏','tablename'=>'prepare_chapter','keyname'=>'id','titlecolumn'=>'title','url'=>array('id'=>'fid','stype'=>2)),
		'prepareknowledge'=>array("title"=>'备课知识点收藏','tablename'=>'prepare','keyname'=>'id','titlecolumn'=>'title','url'=>array('id'=>'fid','stype'=>1)),
		'teaching'=>array("title"=>'教研收藏','tablename'=>'teaching','keyname'=>'meetingId','titlecolumn'=>'meetingName',url=>array('meetingId'=>'fid','uid'=>'uid')),
		'teach'=>array("title"=>'教研收藏','tablename'=>'teach','keyname'=>'meetingId','titlecolumn'=>'meetingName',url=>array('meetingId'=>'fid','uid'=>'uid')),
		'airclass'=>array("title"=>'课堂收藏','tablename'=>'airclass','keyname'=>'classid','titlecolumn'=>'name','url'=>array('classid'=>'classid')),
		'exercise'=>array("title"=>'作业收藏','tablename'=>'exercise','keyname'=>'id','titlecolumn'=>'title',url=>array('id'=>'fid','mid'=>'uid')),
		'group'=>array("title"=>'社团收藏','tablename'=>'group','keyname'=>'id','titlecolumn'=>'name',url=>array('gid'=>'fid')),
		'grouptopic'=>array("title"=>'社团热贴','tablename'=>'group_topic','keyname'=>'id','titlecolumn'=>'title',url=>array('gid'=>'gid','tid'=>'id')),
		'album'=>array("title"=>'相册收藏','tablename'=>'photo_album','keyname'=>'id','titlecolumn'=>'name',url=>array('uid'=>'userId','id'=>'id')),
		'photo'=>array("title"=>'图片收藏','tablename'=>'photo','keyname'=>'id','titlecolumn'=>'name',url=>array('aid'=>'albumId','uid'=>'userId','id'=>'id')),
		'poster'=>array("title"=>'招贴收藏','tablename'=>'poster','keyname'=>'id','titlecolumn'=>'title',url=>array('id'=>'fid')),
		'vote'=>array("title"=>'投票收藏','tablename'=>'vote','keyname'=>'id','titlecolumn'=>'title',url=>array('id'=>'fid')),
		'college'=>array("title"=>'群组收藏','tablename'=>'college','keyname'=>'id','titlecolumn'=>'title',url=>array('cid'=>'id')),
		'job'=>array("title"=>'就业信息收藏','tablename'=>'job','keyname'=>'id','titlecolumn'=>'title',url=>array('id'=>'fid')),
		
	);
	
	/**
	 * @author Ricker <Rickeryu@gridinfo.com.cn>
	 * @param  $appname  对应用户的名称
	 * @param  $uid 对应用户的ID
	 * @param  $fid 要收藏内容的ID
	 * @param  $isexsite 定义了两种方式，如果是1 - 重复收藏的时候，更新原收藏时间 ；0 - 重复收藏的时候，不做任何操作
	 * @return boolean
	 */
	public function add_favorite($map){
		$appconfig = $this->appConfig[$map['appconfig']];
		$map['favuid'] = getMid();
		$map['tablename'] = $appconfig['tablename'];
		$res = $this->field("id")->where($map)->find();
		$map['addtime'] = $data['addtime'] = time();
		$result = false;
		if(!$res){
			//如果是不存在，则进行插入操作
			$result =  $this->add($map);
		}else{
			//如果存在这里面有两种操作，默认的是不进行操作
			if($isexsite){
				$result = $this->where($res)->save($data);
			}
		}
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据应用名称及id来判断是否已收藏
	 +----------------------------------------------------------
	 * @param $appname 应用名称
	 * @param $fid 收藏的id
	 * @return 收藏表中的主键id
	 * @author 小波 2013-6-4
	 +----------------------------------------------------------
	 */
	public function check_favorite($appname,$actname,$modname,$appconfig,$fid){
		$map['appconfig'] = $appconfig;
		$appconfig = $this->appConfig[$appconfig];
		$map['tablename'] = $appconfig['tablename'];
		$map['appname'] = trim($appname);
		$map['action'] = trim($actname);
		$map['method'] = trim($modname);
		$map['fid'] = intval($fid);
		$map['favuid'] = getMid();
		
		$id = $this->where($map)->getField('id');
		return $id;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据收藏的ID获取收藏的全部信息
	 +----------------------------------------------------------
	 * @param $fid 收藏的id
	 * @return 收藏的数据数组
	 * @author 小波 2013-6-4
	 +----------------------------------------------------------
	 */
	public function get_favorite($id){
		$map['id'] = intval($id);
		return $this->where($map)->find();
	}
	
	/**
	 +----------------------------------------------------------
	 * 统计某信息的收藏次数
	 +----------------------------------------------------------
	 * @param $appname 应用名称
	 * @param $fid 收藏的id
	 * @return 收藏表中的主键id
	 * @author 小波 2013-6-4
	 +----------------------------------------------------------
	 */
	public function count_favorite($appname, $fid,$actname='',$modname='',$appconfig=''){
		
		if(!empty($appconfig)){
			$map['appconfig'] = $appconfig;
			$appconfig = $this->appConfig[$appconfig];
			$map['tablename'] = $appconfig['tablename'];
		}
		if(!empty($actname)){
			$map['action'] = trim($actname);
		}
		if(!empty($modname)){
			$map['method'] = trim($modname);
		}
		$map['appname'] = trim($appname);
		$map['fid'] = intval($fid);
		
		$num  = $this->where($map)->count();
		return $num;
	}
	
	/**
	 *
	 * @Title: up_favorite
	 * @Description: 更新收藏表里面的status状态
	 * @param @param unknown $map
	 * @param @return number    设定文件
	 * @return number
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	public function up_favorite($tablename,$fid,$status){
		$map['fid'] = intval($fid);
		$map['tablename'] = $tablename;
		$data['status'] =$status;
		$this->where($map)->save($data);
		return 1;
	
	}
	/**
	 * 
	* @Title: del_favorite_table
	* @Description: 审核操作删除的时候，删除收藏的信息
	* @param @param 传入的表名=>数据库中tablename  $tablename
	* @param @param 传入的id名=>数据库中的fid  $fid
	* @param @return number    设定文件
	* @return number  
	* @author RickerYu rickeryu@gridinfo.com.cn
	 */
	public  function del_favorite_table($tablename,$fid){
		$map['tablename'] = $tablename;
		$map['fid'] = $fid;
		return $this->where($map)->delete();
		return  1;
	}
	
	/**
	 +----------------------------------------------------------
	 * 删除指定收藏过的信息
	 +----------------------------------------------------------
	 * @param $fid 收藏的id
	 * @return 状态值
	 * @author 小波 2013-6-4
	 +----------------------------------------------------------
	 */
	public function del_favorite($id){
		$map['id'] = intval($id);
		$map['favuid'] = getMid();
		return $this->where($map)->delete();
	}
	
	/**
	 * @description 根据app来显示当前用户收藏信息
	 * @author Ricker <Rickeryu@gridinfo.com.cn>
	 * @param  $appname  对应用户的名称
	 * @param  $keyid 应用里面要收藏的内容对应表的自动增长或主键列名
	 */
	public function list_favorite($map){
		$map['favuid'] = getMid();
		$data['appconfig'] = $map['appconfig'];
		$map['tablename'] = $data['appconfig']['tablename'];
		unset($map['appconfig']);
		$temp = $this->field('a.id as favid,a.favuid,a.appname,a.action,a.method,a.addtime,a.tablename,b.*')->table(C('DB_PREFIX').$this->tableName.' a')->join(C('DB_PREFIX').$data['appconfig']['tablename'].' b ON a.fid = b.'.$data['appconfig']['keyname'])->where($map)->select();
		foreach ($temp as $key=>$val){
			$result[$val['favid']] = $val;
		}
		return $result;
	}
	
	public function list_user_favorite_app(){
		$map['favuid'] = getMid();	
		$temp = $this->field('appname,appconfig')->group('tablename')->where($map)->findAll();
		foreach ($temp as $key=>$val){
			$appconfig = $this->appConfig[$val['appconfig']];
			$result[$val['appname']]['appname'] = $val['appname'];
			$result[$val['appname']]['title'] = $appconfig['title'];
		}
		return $result;
	}
	
			
	public function list_favorite_all($appname = '', $pagesize=20, $tablename=''){
		$appid = $favlist = $appnlist =  array();
		$map['favuid'] = getMid();
		//$map['status'] = 1;
		//得到当前用户的应用信息
		//$indexsize = ((int)$page-1)*$pagesize;
		//如果得到的appname是空，就显示全部的信息
		if(!empty($appname)){
			$map['appname'] = $appname;
		}
		if(!empty($tablename)){
			$map['tablename'] = $tablename;
		}
		$total = $this->where($map)->count();
		$appidList = $this->field('id as favid,appname,action,method,fid,addtime,tablename,appconfig')->where($map)->order('addtime desc')->findpage($pagesize);

		foreach($appidList['data'] as $key=>$val){
			//生成对应 app的记录链接及时间数组
			$favlist[$val['favid']]= array(
					'fid'=>$val['fid'],
					'appname'=>$val['appname'],
					'url'=>$val['appname'].'/'.$val['action'].'/'.$val['method'],
					'tablename'=>$val['tablename'],
					'appconfig'=>$this->appConfig[$val['appconfig']],
					'addtime'=>$val['addtime']);
			//生成对应app的id数组
			$appid[$val['appname'].'_'.$val['tablename']]['fid'][] = $val['fid'];	
			if(!isset($appid[$val['appname'].'_'.$val['tablename']]['appconfig']))	{
				$appid[$val['appname'].'_'.$val['tablename']]['appconfig'] = $this->appConfig[$val['appconfig']];
			}	
		}
		//遍历appid得到对应的值
		foreach ($appid as $key=>$val){
			$keyar = explode('_', $key);
			$nmap['fid']  = array('in',implode(',',$val['fid']));
			$nmap['appname'] = $keyar[0];
			//$nmap['tablename'] = $keyar[1];
			$nmap['favuid'] = getMid();
			$nmap['appconfig'] = $val['appconfig'];
			$result = $this->list_favorite($nmap);
			foreach ($result as $rkey=>$rval){
				$favlist[$rkey] = array_merge($favlist[$rkey],$result[$rkey]);
				
				$favlist[$rkey]['name'] = $rval[$nmap['appconfig']['titlecolumn']];
				//进行链接的拼接操作
				foreach ($nmap['appconfig']['url'] as $akey=>$aval){
					if(isset($favlist[$rkey][$aval])){
						$newurl[$rkey][$akey] = $favlist[$rkey][$aval];
					}else{
						$newurl[$rkey][$akey] = $aval;
					}
				}
				$newurl[$rkey]['iframe'] = 'yes';
				$newurl[$rkey]['nsl'] = '1';
				//print_r($newurl[$rkey]);
				//$favlist[$rkey]['url'] = ustr_replace('['.$akey.']',$favlist[$rkey][$akey],$favlist[$rkey]['url']);
				$favlist[$rkey]['url'] = U($favlist[$rkey]['url'],$newurl[$rkey]);
			}
		}
		$appidList['data'] = $favlist;
		return $appidList;
	}
	
}