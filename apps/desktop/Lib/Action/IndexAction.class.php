<?php
/**
 +------------------------------------------------------------------------------
 * Web Desktop 桌面模块Action
 +------------------------------------------------------------------------------
 * @category	desktop
 * @package		Lib/Action
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-18 下午1:55:54
 +------------------------------------------------------------------------------
 */
class IndexAction extends BaseAction{
	public function _initialize() {
		parent::_initialize();
		//判断是否支持本系统 start
		if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0') !== false ){
			$this->assign('jumpUrl',U('home/user/outdsk'));
			$this->error('您的浏览器暂不支持本系统！');
			exit();
		}
		//判断是否支持本系统 end

		//标志是否桌面调用
		$this->assign('curmodule','dskindex');
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
		
		//获取顶部导航
		$navbar=array();
		if($this->setting['dsk_nav']){
			$navbar=dstripslashes(unserialize(stripslashes($this->setting['dsk_nav'])));
		}else{
			$result = M('dsk_navbar')->where('`avaliable`>0')->order('disp')->findAll();
			foreach ($result as $value){
				//重新格式化url
				if (strrpos($value['navurl'], 'http://') !== 0 && strrpos($value['navurl'], 'https://') !== 0) {
					$value['navurl'] = getSiteUrl() . __ROOT__ . '/' . $value['navurl'];
				}
				$navbar[$value['navid']]=$value;
			}
		}
		$data['navbar'] = $navbar;
		
		//获取图标显示方式
		if($this->setting['dsk_iconview']){
			$iconview=dstripslashes(unserialize(stripslashes($this->setting['dsk_iconview'])));
		}else{
			$result = M('dsk_iconview')->where("`avaliable`>0")->order('disp')->findAll();
			$iconview=array();
			foreach ($result as $value){
				$iconview[$value['id']]=$value;
			}
		}
		$data['iconview'] = $iconview;
		$this->assign($data);
		$this->display();
    }
    
    /**
     +----------------------------------------------------------
     * Enter description here ... （方法功能的注释）
     +----------------------------------------------------------
     * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
     * @return return_type <返回类型(void的方法就不用该选项)>
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-3-18 下午3:17:00
     +----------------------------------------------------------
     */
    public function system(){
    	$op = $_POST['op'] || $_GET['op'];
    	
    }

}
?>