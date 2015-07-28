<?php
    /**
     +------------------------------------------------------------------------------
     * 博客后台管理Action
     +------------------------------------------------------------------------------
     * @category	blog
     * @package		 Lib/Action
     * @author		美美 <meimeili@gridinfo.com.cn>
     * @version		1.0
     +------------------------------------------------------------------------------
     * 创建时间：	2013-3-1 上午05:05:35
     +------------------------------------------------------------------------------
     */
   	  import('admin.Action.AdministratorAction');
	  class AdminAction extends AdministratorAction {
        //BlogModel的实例化对象
        private $blog;
		//Smile的实例化对象
        private $smile;
        //BlogConfig的实例化对象
        private $config;
		//
        private $category;
        /**
         +----------------------------------------------------------
         * 初始化
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午05:09:43
         +----------------------------------------------------------
         */
        public function _initialize(){
        	parent::_initialize();
        	$this->config = D( 'AppConfig' );
            $this->blog  = D( 'Blog' );
        }
        /**
         +----------------------------------------------------------
         * 基础设置管理
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午05:10:24
         +----------------------------------------------------------
         */
        public function index (){
            $config   = $this->config->getConfigData();
            $this->assign( $config );
            $this->display();
        }
		/**
		 +----------------------------------------------------------
		 * 回收站
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:10:55
		 +----------------------------------------------------------
		 */
        public function recycle(  ) {
        	//为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
			if ( !empty($_POST) ) {
				$_SESSION['blog_admin_search_recycle'] = serialize($_POST);
			}else if ( isset($_GET[C('VAR_PAGE')]) ) {
				$_POST = unserialize($_SESSION['blog_admin_search_recycle']);
			}else {
				unset($_SESSION['blog_admin_search_recycle']);
			}

        	$this->assign('isSearch', isset($_POST['isSearch'])?'1':'0');

            //姓名，uid,博客内容
            //$_POST['name']     && $map['name']    = t( $_POST['name'] );
            $_POST['uid']      && $map['uid']     = intval( t( $_POST['uid'] ) );
            $_POST['content']  && $map['content'] = array( 'like',"%".t( $_POST['content'] )."%" );
            $_POST['title']    && $map['title']   = array( 'like',"%".t( $_POST['title'] )."%" );
            //isset($_POST['isHot']) && $_POST['isHot']!='' && $map['isHot'] = intval( $_POST['isHot'] );

            //处理时间
            //$_POST['stime'] && $_POST['etime'] && $map['cTime'] = $this->blog->DateToTimeStemp(t( $_POST['stime'] ),t( $_POST['etime'] ) );

            //处理排序过程
            //$order = isset( $_POST['sorder'] )?t( $_POST['sorder'] )." ".t( $_POST['eorder'] ):"cTime DESC";
            $order = 'cTime DESC';

            $map['status'] = 2;

            $list = $this->blog->where( $map )->order( $order )->findPage( 20 );
            $list['uid'] = $map['uid'];
            $this->assign( $list );
            $this->assign($_POST);
            $this->display();
        }
		/**
		 +----------------------------------------------------------
		 * 回收站动作
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:11:32
		 +----------------------------------------------------------
		 */
        public function recycleMan(  ){
            $act = $_REQUEST['type'];  //动作
            isset($_REQUEST['id']) && $map['id']  = array('in',$_REQUEST['id']);  //博客的id

            switch( $act ){
                case "resume":  //恢复
                    $result = $this->blog->setField( 'status',1,$map );
                    break;
                case "delete"://彻底物理删除
                    if( empty( $map ) ){
                        echo -1;
                        exit();
                    }
                    $map['status'] = 2;
                    $result = $this->blog->where( $map )->delete();
                    break;
                case "allresume":  //全部恢复
                    $result = $this->blog->setField( 'status',1);
                    break;
                case "alldelete"://全部彻底物理删除
                    $map['status'] = 2;
                    $result = $this->blog->where( $map )->delete();
                    if( $result ){
                        $this->success( "删除成功" );
                    }
                    break;
                default:
                    echo -1;
                    exit;
                    $this->error( "error_no_action" );
            }

            if( $result ){
                if ( !strpos($_REQUEST['id'],",") ){
                    echo 2;            //说明只是删除一个
                }else{
                    echo 1;            //删除多个
                }
            }else{
                echo -1;
            }

        }
        /**
         +----------------------------------------------------------
         * Enter description here ... （方法功能的注释）
         +----------------------------------------------------------
         * @param	$var
         * @return	Array <返回类型(void的方法就不用该选项)>
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午05:12:13
         +----------------------------------------------------------
         */
        public function filterUser($var){
            if( 0 != intval($var['uid']) )
                return false;
            return true;
        }
        /**
         +----------------------------------------------------------
         * 图像设置
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午05:12:54
         +----------------------------------------------------------
         */
        public function ico (){
            //获取数据库的表情列表
            $smiletype     =  $this->smile->getSmileType() ;
            $this->assign( 'smiletype' , $smiletype );
            $this->display();
        }
		/**
		 +----------------------------------------------------------
		 * Enter description here ... （方法功能的注释）
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:13:13
		 +----------------------------------------------------------
		 */
        public function smile(){
            $type      = $_REQUEST['type'];
            $smilelist = $this->smile->getSmileFileList($type);
            $path      = __APP__."/Admin/doAddSmile/";

            $this->assign( 'smile_list',$smilelist );
            $this->assign( 'action_path',$path );
            $this->assign( 'smilepath',__PUBLIC__.'/images/biaoqing/'.$type.'/' );
            $this->assign( 'smiletype',$type );
            $this->display(  );
        }

		/**
		 +----------------------------------------------------------
		 * 博客分类展示
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:13:28
		 +----------------------------------------------------------
		 */
        public function category() {
     	    $this->assign( 'category_list',$this->blog->getCategory());
            $this->display();
        }
		/**
		 +----------------------------------------------------------
		 * 增加博客分类
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:13:55
		 +----------------------------------------------------------
		 */
        public function addCategory() {
        	$this->display('editCategory');
        }
		/**
		 +----------------------------------------------------------
		 * do增加博客分类
		 +----------------------------------------------------------
		 * @return	增加成功返回1，失败返回0
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:14:13
		 +----------------------------------------------------------
		 */
        public function doAddCategory(){
            $data['name'] = t(h($_POST['title']));
            $data['uid']  = 0;
            if (empty($data['name'])) {
            	echo 0;
            }else {
            	echo intval( M('blog_category')->add($data) );
            }
        }
		/**
		 +----------------------------------------------------------
		 * 修改博客分类
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:14:33
		 +----------------------------------------------------------
		 */
        public function editCategory() {
        	$category = M('blog_category')->where('id='.intval($_GET['gid']))->find();
        	$this->assign('category', $category);
        	$this->display('editCategory');
        }
		/**
		 +----------------------------------------------------------
		 * do修改博客分类
		 +----------------------------------------------------------
		 * @return	成功返回1，失败返回0 
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:14:48
		 +----------------------------------------------------------
		 */
        public function doEditCategory() {
            $_POST['title'] = t(h($_POST['title']));
            if ( empty($_POST['title']) ) {
            	echo 0;
            }else {
            	echo M('blog_category')->where('`id`='.intval($_POST['gid']))->setField('name', $_POST['title']) ? '1' : '0';
            }
        }
		/**
		 +----------------------------------------------------------
		 * 删除博客分类
		 +----------------------------------------------------------
		 * @return	成功返回1，失败返回0 
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:15:06
		 +----------------------------------------------------------
		 */
	  	public function doDeleteCategory(){
            echo M('blog_category')->where('`id`='.intval($_POST['gid']))->delete() ? '1' : '0';
        }
		/**
		 +----------------------------------------------------------
		 * 根据具体信息判断分类是否存在
		 +----------------------------------------------------------
		 * @return	存在返回1，不存在返回0
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:15:44
		 +----------------------------------------------------------
		 */
        public function isCategoryExist() {
        	echo D('BlogCategory')->isCategoryExist( t($_POST['title']), 0, intval($_POST['gid']) ) ? '1' : '0';
        }
		/**
		 +----------------------------------------------------------
		 *根据分类名称判断分类是否存在
		 +----------------------------------------------------------
		 * @return	存在返回1，不存在返回0
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:15:53
		 +----------------------------------------------------------
		 */
        public function isCategoryEmpty() {
        	echo D('BlogCategory')->isCategoryEmpty(intval($_POST['gid'])) ? '1' : '0';
        }
        /**
         +----------------------------------------------------------
         * 获得所有人的bloglist
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午05:25:21
         +----------------------------------------------------------
         */
        public function bloglist (){
	        //为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
			if ( !empty($_POST) ) {
				$_SESSION['blog_admin_search'] = serialize($_POST);
			}else if ( isset($_GET[C('VAR_PAGE')]) ) {
				$_POST = unserialize($_SESSION['blog_admin_search']);
			}else {
				unset($_SESSION['blog_admin_search']);
			}

        	$this->assign('isSearch', isset($_POST['isSearch'])?'1':'0');

            //姓名，uid,博客内容
            //$_POST['name']		&& $this->blog->name    = t( $_POST['name'] );
            $_POST['uid']		&& $this->blog->uid     = intval( t( $_POST['uid'] ) );
            $_POST['content']	&& $this->blog->content = array( 'like',"%".t( $_POST['content'] )."%" );
            $_POST['title']		&& $this->blog->title   = array( 'like',"%".t( $_POST['title'] )."%" );
            isset($_POST['isHot']) && $_POST['isHot']!=''	&&	$this->blog->isHot = intval( $_POST['isHot'] );

            //处理时间
            //$_POST['stime'] && $_POST['etime'] && $this->blog->cTime = $this->blog->DateToTimeStemp(t( $_POST['stime'] ),t( $_POST['etime'] ) );

            //处理排序过程
            //$order = isset( $_POST['sorder'] )?t( $_POST['sorder'] )." ".t( $_POST['eorder'] ):"cTime DESC";
            $order = 'cTime DESC';
            $list  = $this->blog->getBlogList(null,null,$order,20);
            $this->assign( $_POST );
            $this->assign( $list );
            $this->display();
        }
        /**
         +----------------------------------------------------------
         * 删除博客
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午05:25:51
         +----------------------------------------------------------
         */
        public function doDeleteBlog(){
            $blogid['id'] = array( 'in',$_REQUEST['id']);//要删除的id.
            $result       = $this->blog->doDeleteBlog($blogid);
            if( false !== $result){
                if ( !strpos($_REQUEST['id'],",") ){
                    echo 2;            //说明只是删除一个
                }else{
                    echo 1;            //删除多个
                }
            }else{
                echo -1;               //删除失败
            }
        }
        /**
         +----------------------------------------------------------
         * 修改全局设置
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午05:26:31
         +----------------------------------------------------------
         */
        public function doChangeBase (){
            $config = $_POST;
            if( $this->config->editConfig($config)){
            	$this->assign('jumpUrl', U('blog/Admin/index'));
            	$this->success('保存成功');
            }else{
                $this->error( "保存失败" );
            }
        }
		/**
		 +----------------------------------------------------------
		 * 更改是否推荐
		 +----------------------------------------------------------
		 * @author	美美2013-3-1
		 +----------------------------------------------------------
		 * 创建时间：	2013-3-1 上午05:26:52
		 +----------------------------------------------------------
		 */
        public function doChangeIsHot(){

        	$blog['id'] = array( 'in',$_REQUEST['id']);        //要推荐的id.
        	//$blog['id'] = array( 'in',$_POST['id']);        //要推荐的id.
            $act  = $_REQUEST['type'];  //推荐动作

            $result  = $this->blog->doIsHot($blog,$act);

            if( false !== $result){
                    echo 1;            //推荐成功
            }else{
                echo -1;               //推荐失败
            }
        }
        /**
         +----------------------------------------------------------
         * 删除表情
         +----------------------------------------------------------
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午05:27:34
         +----------------------------------------------------------
         */
        public function doChangeIco(){
            $id     = $_POST['id'];
            $idlist = explode( ',',$id );
            if( is_array( $idlist ) ){
                foreach ( $idlist as $value ){
                    $this->blog->unsetConfig( $value,'ico' );
                }
                $this->blog->getWrite()->write('smaile');
                echo 2;
            }else{
                $this->blog->unsetConfig( $id,'ico' );
                $this->blog->getWrite()->write('smaile');
                echo 1;
            }
        }

        public function doChangePath(){

        }
        /**
         +----------------------------------------------------------
         * 将数组中的数据转换成指定类型
         +----------------------------------------------------------
         * @param mixed $data 要转换的数据
         * @param mixed $type 要转换成的类型
         * @return	mixed 转换后的结果
         * @author	美美2013-3-1
         +----------------------------------------------------------
         * 创建时间：	2013-3-1 上午05:28:56
         +----------------------------------------------------------
         */
        private function changeType( $data , $type ){
            $result = $data;

            switch( $type ){
            case 'int':
                $method = "intval";
                break;
            case 'string':
                $method = "strtval";
                break;
            default:
                throw new ThinkException( '暂时只能转换数组和字符串类型' );
            }
            foreach ( $result as &$value ){
                $value = $method( $value );
            }
            return $result;
        }
    }
