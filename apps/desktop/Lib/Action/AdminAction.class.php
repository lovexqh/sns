<?php
/**
 +------------------------------------------------------------------------------
 * 电台直播管理Action
 +------------------------------------------------------------------------------
 * @category	电台 （应用名称）
 * @package		Lib/Action
 * @author		小伟 <ericyang@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-5 下午04:19:02
 +------------------------------------------------------------------------------
 */

import('admin.Action.AdministratorAction');
class AdminAction extends AdministratorAction {

	public function _initialize() {
		//管理权限判定
        parent::_initialize();
	}

	/**
	 +----------------------------------------------------------
	 * 获取配置信息
	 +----------------------------------------------------------
	 * @author 小伟 2013-3-4
	 +----------------------------------------------------------
	 */
    public function index(){
		//获取配置
        $config   = model('Xdata')->lget('live');
		$this->assign($config);
		$this->display();
    }

    /**
     +----------------------------------------------------------
     * 更改直播配置
     +----------------------------------------------------------
     * @author 小伟 2013-3-4
     +----------------------------------------------------------
     */
	public function do_change_config() {
		//变量过滤 todo:更细致的过滤
		foreach($_POST as $k=>$v){
			$config[$k]	=	t($v);
		}
		if(model('Xdata')->lput('live',$config)){
            $this->assign('jumpUrl', U('live/Admin/index'));
			$this->success('设置成功！');
		}else{
			$this->error('设置失败！');
		}
	}

	/**
	 +----------------------------------------------------------
	 * 直播管理
	 +----------------------------------------------------------
	 * @author 小伟 2013-3-4
	 +----------------------------------------------------------
	 */
    public function live_list(){
		//为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
		if ( !empty($_POST) ) {
			$_SESSION['vote_admin_search'] = serialize($_POST);
		}else if(!empty($_GET['id'])){
			$_SESSION['vote_admin_search'] = serialize($_GET);
		}else if ( isset($_GET[C('VAR_PAGE')]) ) {
			$_POST = unserialize($_SESSION['vote_admin_search']);
		}else {
			unset($_SESSION['vote_admin_search']);
		}
        $this->assign('isSearch', isset($_POST['isSearch'])?'1':'0');

        $_POST['userId']     && $map['userId']    =   intval(t( $_POST['userId']));
        $_POST['id']   		 && $map['id']        =   intval(t( $_POST['id']));
        $_GET['id'] 		 && $map['id']        =   intval(t($_GET['id']));
        $_POST['name']     	 && $map['name']      =   array( 'like','%'.t( $_POST['name'] ).'%' );
        ($_POST['order']     && $order     	      =   'id '.t( $_POST['order'] )) || $order='id DESC';
        $_POST['limit']    	 && $limit            =   intval( t( $_POST['limit'] ) );

		$list	=	D('Live')->where($map)->order($order)->findPage($limit);

		$this->assign($_POST);
		$this->assign($list);
		$this->display();
    }

    /**
     +----------------------------------------------------------
     * 创建直播
     +----------------------------------------------------------
     * @author 小伟 2013-3-4
     +----------------------------------------------------------
     */
    public function create_live(){
    	$config = getConfig();
    	$this->assign('config',$config);
    	$this->assign('do','do_create_live');
		$this->display();
    }

    /**
     +----------------------------------------------------------
     * 保存创建直播的数据
     +----------------------------------------------------------
     * @author 小伟 2013-3-4
     +----------------------------------------------------------
     */
    public function do_create_live(){
    	$live['userId']   = $this->mid;
		$live['name']  = h(t($_POST['name']));
		$live['info'] = h(t($_POST['info']));
		$live['cTime'] = time();
		$live['mTime'] = time();

		$live['file'] = $_POST['file'];
		$live['streamer'] = $_POST['streamer'];

		$live['privacy'] = intval($_POST['privacy']);

		//设置密码
		if(intval($_POST['privacy'])==4)
			$live['privacy_data'] = t($_POST['privacy_data']);


		// 直播频道封面
 		$options['userId']		=	$this->mid;
		$options['max_size']    =   2*1024*1024;  //2MB
		$options['allow_exts']	=	'jpg,gif,png,jpeg,bmp';
        $info	=	X('Xattach')->upload('live_cover',$options);
	    if($info['status']) {
		    $live['thumb'] = $info['info'][0]['savepath'] . $info['info'][0]['savename'];
	    }else{
	    	$live['thumb'] = 'default.gif';
	    }

	    $result = D('Live')->add($live);

	    if($result){
            $this->assign('jumpUrl', U('live/Admin/live_list'));
			$this->success('直播频道添加成功！');
		}else{
			$this->error('直播频道添加失败！');
		}

    }

    /**
     +----------------------------------------------------------
     * 编辑直播
     +----------------------------------------------------------
     * @author 小伟 2013-3-4
     +----------------------------------------------------------
     */
    public function edit_live(){
    	$config = getConfig();
    	//$map['userId'] = $this->mid;
    	$map['id'] = t($_REQUEST['id']);
    	$result = D('Live')->where($map)->find();
    	$this->assign('config',$config);
		$this->assign('do','do_edit_live');
		$this->assign('live',$result);
		$this->display('create_live');
    }

    /**
     +----------------------------------------------------------
     * 保存更新直播的数据
     +----------------------------------------------------------
     * @author 小伟 2013-3-4
     +----------------------------------------------------------
     */
    public function do_edit_live(){
    	$live['userId']   = $this->mid;
    	$map['id']	= $_REQUEST['id'];
		$live['name']  = h(t($_POST['name']));
		$live['info'] = h(t($_POST['info']));
		$live['mTime'] = time();
		$live['streamer'] = $_POST['streamer'];
		$live['file'] = $_POST['file'];
		$live['privacy'] = intval($_POST['privacy']);

		//设置密码
		if(intval($_POST['privacy'])==4)
			$live['privacy_data'] = t($_POST['privacy_data']);

		// 直播频道封面
		$options['userId']		=	$this->mid;
		$options['max_size']    =   2*1024*1024;  //2MB
		$options['allow_exts']	=	'jpg,gif,png,jpeg,bmp';
        $info	=	X('Xattach')->upload('live_cover',$options);
	    if($info['status']) {
		    $live['thumb'] = $info['info'][0]['savepath'] . $info['info'][0]['savename'];
	    }else{
	    	$live['thumb'] = 'default.gif';
	    }

	    $result = D('Live')->where($map)->save($live);

	    if($result){
            $this->assign('jumpUrl', U('live/Admin/live_list'));
			$this->success('直播频道更新成功！');
		}else{
			$this->error('直播频道更新失败！');
		}

    }

    /**
     +----------------------------------------------------------
     * 删除直播
     +----------------------------------------------------------
     * @author 小伟 2013-3-4
     +----------------------------------------------------------
     */
	public function delete_live() {
		$map['id']		=	t($_REQUEST['id']);
		$result	=	D('Live')->deleteLive($map['id'],$this->mid,1);
		if($result){
			//删除成功
			if ( !strpos($_REQUEST['id'],",") ){
                echo 2;exit;         //说明只是删除一个
            }else{
                echo 1;exit;            //删除多个
            }
		}else{
			//删除失败
			echo "0";exit;
		}
	}
	/**
	 +----------------------------------------------------------
	 * 改变直播是否推荐
	 +----------------------------------------------------------
	 * @author 小伟 2013-3-4
	 +----------------------------------------------------------
	 */
	 public function doChangeIsHot(){
        	$map['id'] = array( 'in',t($_REQUEST['id']));        //要推荐的id
            $act  = $_REQUEST['type'];  //推荐动作

			if( empty($map) ) {
				throw new ThinkException( "不允许空条件操作数据库" );
			}
			switch( $act ) {
				case "recommend":   //推荐
					$field = array( 'isHot','rTime' );
					$val = array( 1,time() );
					$result = D('Live')->setField( $field,$val,$map );
					break;
				case "cancel":   //取消推荐
					$field = array( 'isHot','rTime' );
					$val = array( 0,0 );
					$result = D('Live')->setField( $field,$val,$map );
					break;

			}
            if( false !== $result){
                echo 1;exit;       //推荐成功
            }else{
                echo -1;exit;      //推荐失败
            }
        }

}
?>