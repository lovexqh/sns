<?php
/**
 +------------------------------------------------------------------------------
 * 挂件市场模块Action
 +------------------------------------------------------------------------------
 * @category	desktop
 * @package		Lib/Action
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-6-14 下午17:55:54
 +------------------------------------------------------------------------------
 */
class WidgetAction extends BaseAction{
		
	public function _initialize() {
		global $ts;
		parent::_initialize();
				
	}

	/**
	 +----------------------------------------------------------
	 * 索引入口
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-18 下午1:58:19
	 +----------------------------------------------------------
	 */
	public function index(){
		$data['class']=array();
		$query=M('dsk_widget_class')->where("1")->order("disp")->findAll();
		foreach ($query as $value){
			$data['class'][$value['fupid']][]=$value;
		}
		$data['navtitle']=L('widget_market');
		$this->assign($data);
		$this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 获取插件列表功能
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-6-14 上午9:19:16
     +----------------------------------------------------------
     */
    public function getlist(){
    	$classid=intval($_GET['classid']);
    	$subid=intval($_GET['subid']);
    	$perpage = 12;

    	//获取所有分类
    	$map = array();
    	$order = "disp";
    	//主分类选项
    	if($classid) $map['classid'] = intval($classid);
    	else{
    		$order = "setupnum";
    	}
    	//子分类选项
    	if($subid){
    		$wids=array();
    		$query=M('dsk_widget_relative')->field("wid")->where("classid='{$subid}'")->findAll();
    		foreach($query as $value){
    			$wids[]=$value['wid'];
    		}
    		if($wids) $map['wid'] = array( 'in', $wids ) ;
    		else $map['wid'] = 0 ;
    	}
    	
    	//取得所有符合条件的应用列表
    	$list=array();
    	$count=M('dsk_widget_market')->where($map)->count();
    	if($count){
    		$data= M('dsk_widget_market')->where($map)->order($order)->findPage($perpage);
    		foreach($data['data'] as &$values){
    			$values['dateline']=date('Y-m-d',$values['dateline']);
    			if($values['icon']=='') $values['icon']=='public/themes/edustyle/desktop/images/default/widgetdefault.png';
    			if(!preg_match("/^(http|ftp|https|mms)\:\/\/(.+?)/i", $values['icon'])){
    				$values['icon']=getSiteUrl()."/".__ROOT__."/".$values['icon'];
    			}
    			$list[$values['wid']]=$values;
    		}
    	}
    	
    	//获取当前用户的ukey
    	$data['ukey'] = M('dsk_userconfig')->where("uid='$this->mid'")->getField('ukey');
    	
    	$this->assign($data);
    	$this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 添加插件至本人
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-6-14 下午1:58:20
     +----------------------------------------------------------
     */
    public function addtodesktop(){
	    $msg='';
		if(!$this->space['self']){
			echo json_encode(array('msg'=>L('no_privilege')));
			exit();
		}
		$ukey=trim($_GET['ukey']);
		if($ukey!=M('dsk_userconfig')->where("uid='$this->mid'")->getField('ukey')){
			echo json_encode(array('msg'=>L('need_refresh')));
			exit();
		}
		if(!empty($msg)){
			echo json_encode(array('msg'=>$msg));
			exit();
		}
		//添加挂件
		$wid=intval($_GET['wid']);
		
		if(!$widget=M('dsk_widget_market')->where("wid='{$wid}'")->find()){
			echo json_encode(array('msg'=>L('widget_not_exist')));
			exit();
		}
		
		//判断挂件是否已安装
		if(M('dsk_widget')->where("uid='{$this->mid}' AND oid='{$wid}'")->find()){
			echo json_encode(array('msg'=>L('widget_add_exist')));
			exit();
		}
		
		$setarr=array(
				'uid'=>$this->mid,
				'username'=>getUserName($this->mid),
				'oid'=>$wid,
				'notdelete'=>intval($widget['notdelete']),
				'url'=>replace_url($widget['url']),
				'type'=>$widget['type'],
				'open'=>$widget['open'],
				'height'=>$widget['height'],
				'width'=>$widget['width'],
				'idtype'=>$widget['idtype'],
				'typeid'=>$widget['typeid'],
				'href'=>$widget['href'],
				'classname'=>$widget['classname'],
				'dateline'=>time()
			);
		
		if($setarr['gid']=M('dsk_widget')->add($setarr)){
			M()->query("update ".C('DB_PREFIX')."widget_market set setupnum=setupnum+1 where wid = '{$wid}'");
			echo json_encode(array('msg'=>'success','data'=>$setarr));
			exit();
		}
    }
    
    /**
     +----------------------------------------------------------
     * 每个插件初始化时要附加执行的功能 （JS调用）
     +----------------------------------------------------------
     * @author 小波 2013-6-19
     +----------------------------------------------------------
     */
    public function widgetInit(){
    	$val = base64_decode($_GET['val']);
    	$val = "app=home&mod=Widget&act=myFriend";
    	if (empty($val)) {//如果参数为空则退出
    		exit();
    	}
    	$widget = M("dsk_widget_market")->where("url like '%{$val}%'")->find();
    	if(empty($widget)){//如果查询结果为空则退出
    		exit();
    	}
    	switch ($widget['open']){
    		case '1': //挂件窗体
    			break;
    		case '2': //内部浏览器
    			$open = "top.OpenSpaceWin(\"'+url+'\",\"'+title+'\");";
    			break;
    		case '3': //新窗口
    			break;
    		default: ///应用窗体
    			
    	}
    	
    	$document = "$(document).find('a').each(function(i, e) {
			if('undefined' != typeof($(this).attr('onclick'))){
				//设置白名单
				var func = new Array('ui','tabs','Alert','Confirm','Prompt');
				//获取onclick的方法名
				var fn = $(this).attr('onclick');
				fn = fn.substr(0,fn.indexOf('('));
				if(fn.indexOf('.')!=-1){
					fn = fn.substr(0,fn.indexOf('.'));
				}
				//如果是白名单的函数则增加parent
				if(func.in_array(fn))
					$(this).attr('onclick','parent.' + $(this).attr('onclick'));
			}else{
				$(this).removeAttr('target');
				var title = $(this).attr('title')?$(this).attr('title'):$(this).text();
				if(title=='') title = '';
				var url = $(this).attr('href');
				$(this).attr('href','javascript:;');
				$(this).attr('onclick','{$open}return false;');
			}
		});";
    	echo $document;
    }
    
    
}
?>